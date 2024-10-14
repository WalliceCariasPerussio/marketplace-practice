<?php

namespace App\UseCases\OfferImporter\States;

use App\UseCases\OfferImporter\Contracts\OfferImportingInterface;

class OfferImportingCanceled extends OfferImportingInterface
{
    public function process(): void
    {
        $this->offerImportProcess->status = 'canceled';
    }
}
