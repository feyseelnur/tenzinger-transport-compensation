<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
  protected $model = Employee::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
    public function configure(): EmployeeFactory
    {
        return $this->afterCreating(function (Employee $employee) {
            $transport = Transport::inRandomOrder()->first();
            if($transport){ // Add this check to avoid null object error.
                \App\Models\Commute::factory()->create([
                    'employee_id' => $employee->id,
                    'transport_id' => $transport->id,
                ]);
            }
        });
    }
}
