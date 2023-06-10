<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'global',
        'group',
        'method',
    ];

    protected $guarded = ['id'];

    public function scopeSearch($query, $string)
    {
        return scopeSearchHandler($query, $string, $this->fillable);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
