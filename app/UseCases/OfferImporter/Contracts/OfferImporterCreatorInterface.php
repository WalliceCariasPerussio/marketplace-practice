<?php

namespace App\UseCases\OfferImporter\Contracts;

interface OfferImporterCreatorInterface
{
    /**
     * Agenda o job de importação.
     *
     * @param int $userId
     * @return void
     */
    public function scheduleImport(int $userId): array;
}
