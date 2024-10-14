<?php

namespace App\UseCases\OfferImporter\Contracts;

use Exception;

abstract class OfferImportingInterface
{
    protected $offerImportProcess;

    /**
     * OfferImporting's constructor.
     *
     */
    public function __construct($offerImportProcess)
    {
        $this->offerImportProcess = $offerImportProcess;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function process(): void
    {
        throw new Exception('Method process() not implemented');
    }
}
