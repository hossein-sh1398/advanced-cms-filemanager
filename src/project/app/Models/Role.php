<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'persian_name'
    ];

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    public $search = ['name', 'persian_name'];

    /**
     * @param $query
     * @param $val
     * @return mixed
     */
    public function scopeSearch($query, $string)
    {
        return scopeSearchHandler($query, $string, $this->fillable);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission->name);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }
}
