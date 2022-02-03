<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = [
            [
                'code' => '00000001',
                'name' => 'Producto 1',
                'description' => 'producto 1',
                'stock' => 100,
                'alert_stock' => 5,
                'cost' => 117.57,
                'price' => 123.45,
                'tax_perc' => 5,
            ],
            [
                'code' => '00000002',
                'name' => 'Producto 2',
                'description' => 'producto 2',
                'stock' => 200,
                'alert_stock' => 5,
                'cost' => 39.70,
                'price' => 45.65,
                'tax_perc' => 15,
            ],
            [
                'code' => '00000003',
                'name' => 'Producto 3',
                'description' => 'producto 3',
                'stock' => 150,
                'alert_stock' => 5,
                'cost' => 35.47,
                'price' => 39.73,
                'tax_perc' => 12,
            ],
            [
                'code' => '00000004',
                'name' => 'Producto 4',
                'description' => 'producto 4',
                'stock' => 10,
                'alert_stock' => 5,
                'cost' => 231.48,
                'price' => 250,
                'tax_perc' => 8,
            ],
            [
                'code' => '00000005',
                'name' => 'Producto 5',
                'description' => 'producto 5',
                'stock' => 50,
                'alert_stock' => 5,
                'cost' => 53.95,
                'price' => 59.35,
                'tax_perc' => 10,
            ],
        ];

        foreach ($product as $key => $value) {
            Product::create($value);
        }
    }
}
