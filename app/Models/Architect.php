<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Architect extends Model
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
        });
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
