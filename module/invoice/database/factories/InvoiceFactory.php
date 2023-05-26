<?php


namespace INVOICE\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use INVOICE\Models\Invoice;
use PERSON\database\factories\PersonFactory;
use PERSON\Models\Person;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'person_id' => function () {
                return app()->make(PersonFactory::class)->create()->id;
            }
        ];
    }
}
