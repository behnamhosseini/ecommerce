<?php


namespace INVOICE\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use INVOICE\Models\Invoice;
use PERSON\Models\Person;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'person_id' => function () {
                return Person::factory()->create()->id;
            },
            'total_sum' => $this->faker->randomNumber(5),
        ];
    }
}
