<?php

namespace App\Models;

use App\Enums\MarketSegment;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Opportunity extends Model
{
    /** @use HasFactory<\Database\Factories\OpportunityFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'lead_source',
        'lead_source_id',
        'leed_certified_number',
        'project_value',
        'project_description',
        'project_link',
        'project_owner',
        'sample_submitted',
        'start_date',
        'bid_date',
        'completion_date',
        'status_id',
        'market_segment_id',
        'specifier_id',
        'architect_id',
        'architect_address_id',
        'created_by',
        'updated_by',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'bid_date' => 'datetime',
            'completion_date' => 'datetime',
            'status_id' => ProjectStatus::class,
            'market_segment_id' => MarketSegment::class,
        ];
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function architect(): BelongsTo
    {
        return $this->belongsTo(Architect::class);
    }

    public function architectAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'architect_address_id');
    }

    public function specifier(): BelongsTo
    {
        return $this->belongsTo(Specifier::class);
    }
}
