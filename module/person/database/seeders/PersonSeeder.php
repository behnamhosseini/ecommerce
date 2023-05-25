<?php

namespace PERSON\database\seeders;

use Illuminate\Database\Seeder;
use PERSON\Models\Person;
class PersonSeeder extends Seeder
{

    public function run()
    {
        Person::factory(50)->create();
    }
}
