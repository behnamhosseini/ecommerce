<?php

namespace PERSON\database\seeders;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use PERSON\database\factories\PersonFactory;
use PERSON\Models\Person;
class PersonSeeder extends Seeder
{

    public function run()
    {
        app()->make(PersonFactory::class)->count(50)->create();
    }
}
