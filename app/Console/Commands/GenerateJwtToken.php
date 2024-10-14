<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GenerateJwtToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-jwt-token {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new JWT token for the user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $user = $email ? User::where('email', $email)->first() : User::first();

        if (!$user) {
            $this->error('No user found.');
            return 1;
        }

        $token = JWTAuth::fromUser($user);

        $this->info(json_encode([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], JSON_PRETTY_PRINT));

        return 0;
    }
}
