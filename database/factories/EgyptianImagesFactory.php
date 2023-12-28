<?php

namespace Database\Factories;

use App\Models\Egyptian_Images;
use Illuminate\Database\Eloquent\Factories\Factory;

class EgyptianImageFactory extends Factory
{
    protected $model = Egyptian_Images::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Assuming you have a User model
            'caption' => $this->faker->sentence,
            // Add other necessary fields here
        ];
    }
}