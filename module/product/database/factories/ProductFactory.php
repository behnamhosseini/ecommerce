<?php


namespace PRODUCT\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use PRODUCT\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'item_name' => $this->faker->text(100),
            'active' => $this->faker->boolean(),
            'selling_price' => $this->faker->numberBetween(1, 999999999999),
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'inventory' => $this->faker->randomFloat(2, 0, 999999999999),
        ];
    }
}
