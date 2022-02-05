<?php

namespace Database\Seeders;

use App\Models\PurchasePending;
use Illuminate\Database\Seeder;

class PurchasePendingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pending = [
            [
                'user_id' => 2,
                'product_id' => 1,
                'quantity' => 1,
                'amount' => 117.57,
            ],
            [
                'user_id' => 2,
                'product_id' => 4,
                'quantity' => 1,
                'amount' => 231.48,
            ],
        ];

        foreach ($pending as $key => $value) {
            PurchasePending::create($value);
        }
    }
}
