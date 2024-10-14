<?php

namespace App\Jobs;

use App\Services\ImportOfferService;
use App\UseCases\OfferImporter\OfferImportProcessManager;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportOffersJob implements ShouldQueue
{
    use Queueable;

    protected int $offerImportId;

    /**
     * Cria uma nova instância do job.
     *
     * @param int $offerImportId
     */
    public function __construct(int $offerImportId)
    {
        $this->offerImportId = $offerImportId;
    }

    /**
     * Executa o job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        // Chamar o gerenciador do estado da importação
        $manager = app()->make(OfferImportProcessManager::class);
        $manager->process($this->offerImportId);
    }
}
