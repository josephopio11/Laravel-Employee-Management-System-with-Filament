<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'address' => $this->faker->address,
            // 'department_id' => $this->faker->numberBetween(1, 10),
            'department_id' => 1,
            'city_id' => $this->faker->numberBetween(1, 2),
            'state_id' => $this->faker->numberBetween(1, 2),
            'country_id' => $this->faker->numberBetween(1, 6),
            'zip_code' => $this->faker->postcode,
            'date_of_birth' => $this->faker->date(),
            'date_of_hire' => $this->faker->date(),
        ];

    }
}
