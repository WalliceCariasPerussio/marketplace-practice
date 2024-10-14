<?php

namespace App\UseCases\OfferImporter\States;

use App\UseCases\OfferImporter\Contracts\OfferImportingInterface;

class OfferImporting extends OfferImportingInterface
{
    public function process(): void
    {
        $this->toNextState();
    }

    protected function toNextState(): void
    {
        $this->offerImportProcess->status = 'importing-offers';
    }
}
