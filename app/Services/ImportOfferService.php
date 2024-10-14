<?php

namespace App\Services;

use App\Jobs\ImportOffersByPageJob;
use App\Repositories\OfferImportRepository;
use App\UseCases\OfferImporter\OfferImportProcessManager;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class ImportOfferService
{
    public function import(int $offerImportId): void
    {
        try {
            Log::info("Iniciando importação de ofertas para o processo de importação ID: {$offerImportId}");

            $offerImportRepository  = app()->make(OfferImportRepository::class);
            $offerImport            = $offerImportRepository->find($offerImportId);
            $account                = $offerImport->account;

            $marketplaceService = new MockoonService(new GuzzleHttpClient($account->url));

            $totalPages = $marketplaceService->fetchTotalPages();
            $totalOffers = $marketplaceService->fetchTotalOffers();

            $offerImportRepository->update(
                array_merge($offerImport->toArray(), ['total_offers' => $totalOffers])
            );

            // Despacha um job para cada página de ofertas
            for ($page = 1; $page <= $totalPages; $page++) {
                ImportOffersByPageJob::dispatch($page, $offerImportId);
            }

            Log::info("Importação de ofertas agendada para {$totalPages} páginas para o processo de importação ID: {$offerImportId}");

        } catch (Exception $e) {
            $manager = app()->make(OfferImportProcessManager::class);
            $manager->cancel($offerImportId);

            $message = $e->getMessage();
            Log::error("Erro ao importar ofertas para o processo de importação ID: {$offerImportId}. Motivo: {$message}");
            throw $e;
        }
    }
}
