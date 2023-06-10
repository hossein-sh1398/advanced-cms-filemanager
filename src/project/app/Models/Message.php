<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, WithSearch, SoftDeletes, WithFilter;

    protected $fillable = [
        'read',
        'name',
        'email',
        'mobile',
        'content',
        'ip',
        'country',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }

    public $search = [
        'name', 'email', 'mobile', 'content',
    ];
}
