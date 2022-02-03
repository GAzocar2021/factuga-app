<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => '0000000001',
            'name' => 'producto 1',
            'description' => 'producto 1',
            'stock' => '10',
            'alert_stock' => '2',
            'cost' => '117.57',
            'price' => '123.45',
            'tax_perc' => '5',
            'image' => $this->faker->randomDigit(),
            'is_active' => 'ACTIVO',
        ];
}
