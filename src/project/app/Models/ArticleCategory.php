<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleCategory extends Model
{
    use HasFactory, Sluggable, WithSearch, SoftDeletes, WithFilter;

    protected $fillable = [
        'parent_id',
        'comment',
        'lang',
        'title',
        'slug',
        'content',
    ];

    protected $table = 'article_categories';

        /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public $search = ['title', 'content'];

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function parent()
    {
        return $this->belongsTo(ArticleCategory::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
