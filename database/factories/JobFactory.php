<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Employer;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
   

    public function definition(): array
    {

        //$employer = Employer::factory()->create();
        return [
           'Employer_id' => Employer::factory(),
            'title' => $this->faker->jobTitle,
            'salary' => $this->faker->randomElement(['$50,000 USD', '$90,000 USD', '150,000 USD']),
            'location' => 'remote',
            'url' => $this->faker->url,
            'featured' => false,
            'employer_name' => $this->faker->name,
            'schedule' => $this->faker->randomElement(['full time', 'part time']),
            
           
        ];
    }

}
