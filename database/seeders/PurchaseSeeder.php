<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchase = [
            [
                'invoice_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'amount' => 117.57,
            ],
            [
                'invoice_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'amount' => 39.70,
            ],
            [
                'invoice_id' => 1,
                'product_id' => 3,
                'quantity' => 1,
                'amount' => 35.47,
            ],
            [
                'invoice_id' => 2,
                'product_id' => 4,
                'quantity' => 1,
                'amount' => 231.48,
            ],
            [
                'invoice_id' => 3,
                'product_id' => 2,
                'quantity' => 1,
                'amount' => 39.70,
            ],
            [
                'invoice_id' => 3,
                'product_id' => 5,
                'quantity' => 1,
                'amount' => 53.95,
            ],
            [
                'invoice_id' => 4,
                'product_id' => 3,
                'quantity' => 2,
                'amount' => 35.47,
            ],
            [
                'invoice_id' => 4,
                'product_id' => 5,
                'quantity' => 1,
                'amount' => 53.95,
            ],
            [
                'invoice_id' => 4,
                'product_id' => 2,
                'quantity' => 1,
                'amount' => 39.70,
            ],
        ];

        foreach ($purchase as $key => $value) {
            Purchase::create($value);
        }
    }
}
