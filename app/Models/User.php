<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property \App\Enums\UserRole $user_role_id
 * @property \Illuminate\Support\Carbon|null $two_factor_confirmed_at
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_secret
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $remember_token
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $email
 * @property string $name
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'user_role_id' => UserRole::class,
        ];
    }

    /**
     * Check if user is administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->user_role_id === UserRole::ADMIN;
    }

    /**
     * Check if user is manager or above.
     *
     * @return bool
     */
    public function isManagerOrAbove(): bool
    {
        return $this->user_role_id->atLeast(UserRole::MANAGER);
    }
}
