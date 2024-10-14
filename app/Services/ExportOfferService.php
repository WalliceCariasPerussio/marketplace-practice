<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repositories\OfferRepository;
use App\Repositories\AccountRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ExportOfferService
{
    protected Client $client;
    protected OfferRepository $offerRepository;
    protected AccountRepository $accountRepository;

    /**
     * @param OfferRepository $offerRepository
     * @param AccountRepository $accountRepository
     * @param Client $client
     */
    public function __construct(
        OfferRepository   $offerRepository,
        AccountRepository $accountRepository,
        Client            $client
    ) {
        $this->offerRepository = $offerRepository;
        $this->accountRepository = $accountRepository;
        $this->client = $client;
    }

    /**
     *
     * @param int $offerId
     * @return void
     * @throws GuzzleException
     */
    public function exportOfferById(int $offerId)
    {
        try {

            // Busca a oferta a ser exportada
            $offer = $this->offerRepository->find($offerId);

            $account = $offer->offerImport->account;

            // Envia a oferta para o Hub
            $response = $this->client->post($account->callback_url_offers, [
                'json' => $offer
            ]);

            // Muda o status da oferta para completed
            $offer->status_queue = 'completed';
            $this->offerRepository->save($offer->toArray());

            $body = $response->getBody();
            Log::info("Oferta ID {$offerId} enviada para o Hub. Resposta: {$body}");

        } catch (\Exception $e) {
            // Muda o status da oferta para failed
            $offer->status_queue = 'failed';
            $this->offerRepository->save($offer->toArray());

            $message = $e->getMessage();
            Log::error("Erro ao exportar a oferta ID {$offerId} para o Hub. Erro: {$message}");
            throw $e;
        }
    }
}
