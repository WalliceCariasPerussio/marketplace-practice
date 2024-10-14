<?php

namespace App\Services;

use App\Repositories\OfferRepository;
use App\Repositories\OfferImportRepository;
use App\UseCases\OfferImporter\OfferImportProcessManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class ImportOfferByPageService
{
    protected OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function importByPage(int $page, int $offerImportId)
    {
        try {
            Log::info("Iniciando importação da página {$page} para o processo de importação ID: {$offerImportId}");

            $offerImportRepository  = app()->make(OfferImportRepository::class);
            $offerImport            = $offerImportRepository->find($offerImportId);

            $account                = $offerImport->account;

            $marketplaceService = new MockoonService(new GuzzleHttpClient($account->url));

            // Busca os ofertas para a página específica
            $offers = $marketplaceService->fetchOffers($page);

            // Salva os ofertas no banco de dados
            foreach ($offers as $offer) {
                $this->offerRepository->save(
                    array_merge(
                        $marketplaceService->convertOffer($offer),
                        [
                            'offer_import_id' => $offerImportId,
                            'status_queue' => 'pending',
                        ]
                    )
                );
            }

            $total_imported = count($offers) + $offerImport->total_imported;
            $offerImportRepository->update(
                array_merge($offerImport->toArray(), ['total_imported' => $total_imported])
            );

            if($total_imported == $offerImport->total_offers){
                $manager = app()->make(OfferImportProcessManager::class);
                $manager->process($offerImportId);
            }

            Log::info("Importação da página {$page} finalizada para o processo de importação ID: {$offerImportId}");

        } catch (\Exception $e) {
            $manager = app()->make(OfferImportProcessManager::class);
            $manager->cancel($offerImportId);

            $message = $e->getMessage();
            Log::error("Erro ao importar ofertas da página {$page} para o processo de importação ID: {$offerImportId}. Motivo: {$message}");
            throw $e;
        }
    }
}
