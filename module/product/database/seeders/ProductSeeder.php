<?php

namespace PRODUCT\database\seeders;

use Illuminate\Database\Seeder;
use PRODUCT\Models\Product;
class ProductSeeder extends Seeder
{

    public function run()
    {
        Product::factory(50)->create();
    }
}
