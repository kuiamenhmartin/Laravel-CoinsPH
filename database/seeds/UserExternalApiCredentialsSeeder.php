<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\UserExternalApiCredentials;

class UserExternalApiCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        $user->apis()->create([
            'app_name' => 'coinsph',
            'client_id' => 'AvqyiU4aXflEti601FV5UMQLl44mEEdBymaSbGhY',
            'client_secret' => 'F99Sb7kRxkeiIMGEnUMOIoPLZBIozFm2MNXFaTch3hJA0TKN3Q',
            'scopes' => 'buy_order+wallet_history+history',
            'redirect_uri' => 'http://localhost:8000/api/coinsph/callback',
            'authentication_uri' => 'https://coins.ph/user/oauthtoken'
        ]);

        \Artisan::call('passport:client --name="Laravel Personal Access Client" --no-interaction --personal');
    }
}
