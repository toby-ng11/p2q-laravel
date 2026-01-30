<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $architect_type_desc
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Architect> $architects
 * @property-read int|null $architects_count
 */
class ArchitectType extends Model
{
    /** @use HasFactory<\Database\Factories\ArchitectTypeFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'architect_type_desc',
    ];

    public function architects(): HasMany
    {
        return $this->hasMany(Architect::class);
    }
}
