<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleCategory::query()->delete();
        Article::query()->delete();

        ArticleCategory::factory()
            ->has(Article::factory()->count(5))
            ->count(5)
            ->create();
    }
}
