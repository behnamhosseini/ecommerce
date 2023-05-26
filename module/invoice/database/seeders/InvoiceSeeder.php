<?php

namespace INVOICE\database\seeders;

use Illuminate\Database\Seeder;
use INVOICE\database\factories\InvoiceFactory;
use INVOICE\Models\Invoice;
class InvoiceSeeder extends Seeder
{

    public function run()
    {
        app()->make(InvoiceFactory::class)->count(50)->create();
    }
}
