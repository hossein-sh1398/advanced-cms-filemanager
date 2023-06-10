<?php

namespace App\Models;

use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, SoftDeletes, WithSearch, WithFilter;

    protected $fillable = [
        'moreData',
        'delivery',
        'ricId',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'moreData' => 'array',
    ];

    public function reportable()
    {
        return $this->morphTo();
    }

    public $search = ['id'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
