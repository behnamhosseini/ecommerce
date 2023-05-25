<?php

namespace INVOICE\database\seeders;

use Illuminate\Database\Seeder;
use INVOICE\Models\Invoice;
class InvoiceSeeder extends Seeder
{

    public function run()
    {
        Invoice::factory(50)->create();
    }
}
