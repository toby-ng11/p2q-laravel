<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Specifier extends Model
{
    /** @use HasFactory<\Database\Factories\SpecifierFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'job_title',
        'architect_id',
        'address_id',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function architect(): BelongsTo
    {
        return $this->belongsTo(Architect::class);
    }
}
