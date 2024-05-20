<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;


class CommuteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'transport_id' =>  Transport::inRandomOrder()->first()->id,
            'distance' => $this->faker->numberBetween(1, 80),
            'workdays_per_week' => $this->faker->numberBetween(1, 5),
        ];
    }
}
