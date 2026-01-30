<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $architect_id
 * @property string|null $job_title
 * @property string|null $last_name
 * @property string $first_name
 * @property int $id
 * @property-read \App\Models\Address $address
 * @property-read \App\Models\Architect $architect
 */
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

    /**
     * Perform any actions required after the model boots.
     */
    #[\Override]
    protected static function booted()
    {
        static::deleting(function (Specifier $specifier) {
            // This ensures addresses are deleted when the architect is
            $specifier->address()->delete();
        });
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function architect(): BelongsTo
    {
        return $this->belongsTo(Architect::class);
    }
}
