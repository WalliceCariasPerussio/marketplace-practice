<?php

namespace App\Jobs;

use App\Services\ExportOfferService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExportOfferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $offerId;

    /**
     * Cria uma nova instância do job.
     *
     * @param int $offerId ID da oferta a ser exportada.
     */
    public function __construct(int $offerId)
    {
        $this->offerId = $offerId;
    }

    /**
     * Executa o job.
     *
     * @param ExportOfferService $exportOfferService Serviço de exportação de ofertas.
     * @return void
     * @throws \Exception
     */
    public function handle(ExportOfferService $exportOfferService): void
    {
        try {
            $exportOfferService->exportOfferById($this->offerId);
        } catch (\Exception $e) {
            Log::error("Erro ao exportar a oferta ID {$this->offerId}. Erro: {$e->getMessage()}");
            throw $e;
        }
    }
}
