<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'content' => $this->faker->sentence(),
            'ip' => $this->faker->ipv6(),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail(),
            'mobile' => '0911' . rand(111111, 9999999),
            'country' => $this->faker->country(),
        ];
    }
}
