<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'is_developer',
        'is_admin',
        'is_user',
        'created_at_formatted',
        'has_bci_token',
    ];

    /**
     * Check is the user has the role "developer"
     *
     * @return string
     */
    public function getIsDeveloperAttribute()
    {
        return $this->isDeveloper();
    }

    /**
     * Check is the user has the role "admin"
     *
     * @return string
     */
    public function getIsAdminAttribute()
    {
        return $this->isAdmin();
    }

    /**
     * Check is the user has the role "user"
     *
     * @return string
     */
    public function getIsUserAttribute()
    {
        return $this->isUser();
    }

    /**
     * Get the created date formatted as text
     *
     * @return string
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }

    /**
     * Check is the user has the bci token
     *
     * @return string
     */
    public function getHasBciTokenAttribute()
    {
        return $this->hasBciToken();
    }

    /**
     * Get the user's referrals
     */
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user's referrals
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    /**
     * Check is the user has the role "developer"
     *
     * @return bool
     */
    public function isDeveloper()
    {
        return $this->role == 'developer';
    }

    /**
     * Check is the user has the role "admin"
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRoles('admin') || $this->isDeveloper();
    }

    /**
     * Check is the user has the role "user"
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->role == 'user';
    }

    /**
     * Check is the user has the givin role
     *
     * @param  string|array  $role
     * @return bool
     */
    public function hasRoles($roles)
    {
        if($this->isDeveloper()){
            return true;
        }

        $roles = is_array($roles) ? $roles : (Str::contains($roles, '|') ? explode('|', $roles) : $roles);

        return in_array($this->role, Arr::wrap($roles));
    }

    /**
     * Check is the user has the bci token
     *
     * @return bool
     */
    public function hasBciToken()
    {
        return ! is_null($this->bci_token);
    }
}
