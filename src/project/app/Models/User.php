<?php

namespace App\Models;

use App\Traits\HasRole;
use App\Utility\SMSUtility;
use App\Utility\Search\WithFilter;
use App\Utility\Search\WithSearch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SMSUtility, HasRole, WithSearch, SoftDeletes, WithFilter;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'two_step_status',
        'two_step_type',
        'sms_code',
        'avatar',
        'country',
        'last_ip',
        'last_login',
        'account_verified_at',
        'mobile_verified_at',
        'email_verified_at',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    public $search = [
        'name', 'email', 'mobile'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'account_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function avatar()
    {
        $avatar = $this->profileAvatar;
        if ($avatar) {
            return $avatar->getUrl();
        }

        $conf = Config::where('key', 'general_default_avatar')->first();
        if ($conf) {
            return $conf->value;
        }

        return 'cdn/theme/admin/media/avatars/blank.png';
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value)
        );
    }

    public function isAdministrator()
    {
        return $this->roles->contains('name', 'superadmin');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => verta($value)->format('j F Y ساعت H:i')
        );
    }

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->email
        ]);

        $this->notify(new ResetPasswordNotification($url));
    }

    /**
     * @return bool
     */
    public function twoStepStatus(): bool
    {
        return (bool)$this->two_step_status;
    }

    /**
     * get towFA type
     * @param int $type
     * @return bool
     */
    public function twoStepType(int $type): bool
    {
        return $this->two_step_type == $type;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)$this->account_verified_at;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function profileAvatar()
    {
        return $this->morphOne(Media::class, 'mediaable');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
