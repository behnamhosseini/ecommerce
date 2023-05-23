<?php


namespace PERSON\database\factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use PERSON\Models\Person;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        return [
            'demonstration_name' => $this->faker->name(),
            'active' => $this->faker->boolean(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'social_id' => $this->faker->randomNumber(10),
            'birth_date' => $this->faker->date(),
            'mobile_number' => $this->faker->unique()->numerify('###########'),
            'mobile_number_description' => $this->faker->unique()->text(100),
            'email' => $this->faker->unique()->safeEmail(),
            'email_description' => $this->faker->unique()->text(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
