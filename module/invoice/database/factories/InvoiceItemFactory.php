<?php


namespace INVOICE\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use INVOICE\Models\Invoice;
use INVOICE\Models\InvoiceItem;
use PRODUCT\Models\Product;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition()
    {
        return [
            'invoice_id' => function () {
                return Invoice::factory()->create()->id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'quantity' => $this->faker->randomFloat(2, 0, 999999.99),
            'price' => $this->faker->randomNumber(6),
            'amount' => $this->faker->randomNumber(6),
            'discount' => $this->faker->randomNumber(6),
            'total_after_discount' => $this->faker->randomNumber(6),
            'tax' => $this->faker->randomNumber(6),
            'total_due' => $this->faker->randomNumber(6),
        ];
    }
}
