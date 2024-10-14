<?php

namespace App\UseCases\OfferImporter\States;

use App\Services\ImportOfferService;
use App\UseCases\OfferImporter\Contracts\OfferImportingInterface;
use Exception;

class OfferImportingOffers extends OfferImportingInterface
{
    /**
     * @throws Exception
     */
    public function process(): void
    {
        $this->offerImportProcess->refresh();

        if (!empty($this->offerImportProcess->total_offers) && ($this->offerImportProcess->total_imported === $this->offerImportProcess->total_offers)) {
            $this->toNextState();
            return;
        }

        // Chamar serviço de importação de ofertas
        $offerImporterService = new ImportOfferService();
        $offerImporterService->import($this->offerImportProcess->id);
    }

    protected function toNextState(): void
    {
        $this->offerImportProcess->status = 'completed';
    }
}
