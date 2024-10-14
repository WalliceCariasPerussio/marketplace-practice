<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    /**
     * @param int $userId
     * @return int|null
     */
    public function findAccountId(int $userId): ?int
    {
        return Account::where('user_id', $userId)
            ->value('id'); // Retorna o campo 'id' ou null se não encontrado
    }

    /**
     * @param int $id
     * @return Account|null
     */
    public function getAccountById(int $id): ?Account
    {
        return Account::find($id);
    }
}
