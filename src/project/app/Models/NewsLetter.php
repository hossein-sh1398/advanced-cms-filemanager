<?php

namespace App\Models;

use App\Utility\Search\WithSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsLetter extends Model
{
    use HasFactory, SoftDeletes, WithSearch;

    protected $fillable = [
        'active_email',
        'active_mobile',
        'email',
        'mobile',
        'ip',
        'country',
    ];

    public $search = [
        'email', 'mobile',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
