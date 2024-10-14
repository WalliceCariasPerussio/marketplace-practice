<?php

namespace App\Jobs;

use App\Services\ImportOfferByPageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportOffersByPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $page;
    protected int $offerImportId;

    /**
     * Cria uma nova instância do job.
     *
     * @param int $page
     * @param int $offerImportId
     */
    public function __construct(int $page, int $offerImportId)
    {
        $this->page = $page;
        $this->offerImportId = $offerImportId;
    }

    /**
     * Executa o job.
     *
     * @param ImportOfferByPageService $importOfferByPageService Serviço de importação por página.
     * @return void
     * @throws \Exception
     */
    public function handle(ImportOfferByPageService $importOfferByPageService): void
    {
        // Chama o serviço para importar os ofertas da página específica
        $importOfferByPageService->importByPage($this->page, $this->offerImportId);
    }
}
