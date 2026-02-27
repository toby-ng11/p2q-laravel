<?php

namespace App\Models;

use App\Contracts\StorableAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $class_id
 * @property int $architect_type_id
 * @property int|null $architect_rep_id
 * @property string $architect_name
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Specifier> $specifiers
 * @property-read int|null $specifiers_count
 * @property-read \App\Models\ArchitectType $architectType
 * @property-read \App\Models\User $architectRep
 */
class Architect extends Model implements StorableAddress
{
    /** @use HasFactory<\Database\Factories\ArchitectFactory> */
    use HasFactory;

    protected $fillable = [
        'architect_name',
        'architect_rep_id',
        'company_id',
        'architect_type_id',
        'class_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['architectType', 'architectRep'];

    /**
     * Perform any actions required after the model boots.
     */
    #[\Override]
    protected static function booted()
    {
        static::deleting(function (Architect $architect) {
            // This ensures addresses are deleted when the architect is
            $architect->addresses()->delete();
            $architect->specifiers->each(function ($specifier) {
                $specifier->delete();
            });
        });
    }

    #[\Override]
    public function storeAddress(array $data): bool
    {
        $this->addresses()->create($data);
        return true;
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function specifiers(): HasMany
    {
        return $this->hasMany(Specifier::class);
    }

    public function architectType(): BelongsTo
    {
        return $this->belongsTo(ArchitectType::class);
    }

    public function architectRep(): BelongsTo
    {
        return $this->belongsTo(User::class, 'architect_rep_id', 'id');
    }

    //public function projects(): HasMany
    //{
    //   return $this->hasMany(Project::class);
    //}
}
