<?php

namespace App\Services\Contracts;

interface MarketplaceServiceInterface
{
    public function fetchTotalPages(): int;

    public function fetchTotalOffers(): int;


    public function fetchOffers(int $page): array;

    public function convertOffer(array $offer): array;
}
