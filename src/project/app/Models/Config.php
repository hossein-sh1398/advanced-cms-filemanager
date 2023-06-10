<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Config extends Model
{
    use HasFactory, SoftDeletes, WithSearch, WithFilter;

    protected $fillable = [
        'key',
        'value',
        'type',
        'num',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public $search = ['value', 'key'];

    public function logo()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function defaultAvatarProfile()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function promotionImage()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function waterMark()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function favicon()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
