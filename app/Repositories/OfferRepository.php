<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository
{
    public function save(array $data): Offer
    {
        return Offer::updateOrCreate(
            [
                'external_id' => $data['external_id'],
                'offer_import_id' => $data['offer_import_id'],
            ],
            $data
        );
    }

    public function find(int $id): ?Offer
    {
        return Offer::find($id);
    }
}
