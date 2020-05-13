<?php

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'card',
                'withdrawal_rate' => 0,
                'deposit_rate' => 2.5,
            ],
            [
                'title' => 'paypal',
                'withdrawal_rate' => 0,
                'deposit_rate' => 2.5,
            ],
            [
                'title' => 'skrill',
                'withdrawal_rate' => 0,
                'deposit_rate' => 2.5,
            ],
            [
                'title' => 'bank',
                'withdrawal_rate' => 0,
                'withdrawal_fixed_rate' => 25,
                'deposit_rate' => 2.5,
            ],
            [
                'title' => 'momo',
                'withdrawal_rate' => 3.0,
                'deposit_rate' => 2.5,
            ],
        ];

        foreach ($data as $d) {
            PaymentProvider::create($d);
        }
    }
}
