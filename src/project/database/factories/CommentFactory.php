<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $article = Article::get()->random();

        return [
            'content' => $this->faker->sentence(),
            'ip' => $this->faker->ipv4(),
            'user_id' => User::get()->random()->id,
            'country' => $this->faker->country(),
            'commentable_id' => $article->id,
            'commentable_type' => get_class($article),
        ];
    }
}
