<?php

namespace App\Models;

use App\Enums\UserRole as EnumsUserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $role
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 */
class UserRole extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoleFactory> */
    use HasFactory;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
