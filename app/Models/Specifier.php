<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['address'];

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function architect(): BelongsTo
    {
        return $this->belongsTo(Architect::class);
    }
}
