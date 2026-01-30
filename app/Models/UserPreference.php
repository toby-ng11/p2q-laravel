<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property array $value
 * @property string $key
 * @property int $user_id
 * @property int $id
 */
class UserPreference extends Model
{
    protected $fillable = ['user_id', 'key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];
}
