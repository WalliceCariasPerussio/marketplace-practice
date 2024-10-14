<?php

namespace App\UseCases\OfferImporter\States;

use App\Jobs\ExportOfferJob;
use App\UseCases\OfferImporter\Contracts\OfferImportingInterface;

class OfferImportingCompleted extends OfferImportingInterface
{
    /**
     * @return void
     */
    public function process(): void
    {
        $this->offerImportProcess->status = 'completed';

        foreach ($this->offerImportProcess->offers as $offer) {
            ExportOfferJob::dispatch($offer->id);
        }
    }
}
