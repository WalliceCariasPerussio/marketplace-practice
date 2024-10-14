<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria o usuário "Test User"
        $user =  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Cria o vínculo do usuário com o marketplace "mockoon"
        Account::factory()->create([
            'user_id' => $user->id,
        ]);
    }
}
