<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Article extends Model
{
    use HasFactory, Sluggable, WithSearch, SoftDeletes, WithFilter;

    protected $fillable = [
        'lang',
        'active',
        'category_id',
        'special',
        'article_id',
        'user_id',
        'title',
        'slug',
        'content',
        'meta_description',
        'published_at',
        'count',
    ];


    public const SESSION_PHOTO = 'article_images_';
    public const SESSION_VIDEO = 'article_videos_';
    public const SESSION_CKEDITOR = 'article_ckeditor_';

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

    public $search = ['title', 'content', 'meta_description'];

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class,  'category_id', 'id',);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'ندارد']);
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'mediaable')->where('type', Media::IMAGE);
    }

    public function videos()
    {
        return $this->morphMany(Media::class, 'mediaable')->where('type', Media::VIDEO);
    }

    public function specificImage()
    {
        return $this->morphMany(Media::class, 'mediaable')->where('type', Media::SPECIFIC_IMAGE)->orderBy('id', 'asc');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }


    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Verta::parse($value)->datetime()->format('Y-m-d H:i:s') : null,
            get: fn($value) => $value ? verta($value)->format('Y-m-d H:i:s') : null
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
