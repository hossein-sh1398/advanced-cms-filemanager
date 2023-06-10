<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, WithSearch, SoftDeletes, WithFilter;

    protected $fillable = [
        'active',
        'read',
        'parent_id',
        'user_id',
        'content',
        'ip',
        'country',
    ];

    public $search = [
        'content',
    ];


    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
