<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsLatter>
 */
class NewsLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mobile' => '0911' . rand(111111, 9999999),
            'email' => $this->faker->email(),
            'ip' => $this->faker->ipv4(),
        ];
    }
}
