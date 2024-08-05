<?php

namespace Database\Factories;

use App\Models\User; 
use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
   // protected $model = Employer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Name' => $this->faker->name, // Use $this->faker instead of fake() for consistency
            'logo' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
        ];
    }
}
