<?php

namespace App\Repositories;

use App\Models\OfferImport;

class OfferImportRepository
{
    /**
     * @param int $accountId
     * @return mixed
     */
    public function findOfferImportInProgressByAccountId(int $accountId): mixed
    {

        return OfferImport::where('account_id', $accountId)
            ->whereIn('status', ['pending', 'importing-offers'])
            ->first();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed
    {
        return OfferImport::create($attributes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return OfferImport::find($id);
    }

    /**
     * @return mixed
     */
    public function update(array $attributes)
    {
        return $this->find($attributes['id'])->update($attributes);
    }

}
