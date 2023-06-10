<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mediaable_id',
        'mediaable_type',
        'uuid',
        'name',
        'type',
        'url',
        'mime_type',
        'disk',
        'size',
        'user_id',
    ];

    public const IMAGE = 1;
    public const VIDEO = 2;
    public const SPECIFIC_IMAGE = 3;


    public function mediaable()
    {
        return $this->morphTo();
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

    public function getUrl()
    {
        return  "storage/$this->url/$this->name";
    }
}
