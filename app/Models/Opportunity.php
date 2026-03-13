<?php

namespace App\Models;

use App\Enums\MarketSegment;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $updated_by
 * @property int $created_by
 * @property int|null $architect_address_id
 * @property int|null $architect_id
 * @property int|null $specifier_id
 * @property \App\Enums\MarketSegment $market_segment_id
 * @property \App\Enums\ProjectStatus $status_id
 * @property \Illuminate\Support\Carbon|null $completion_date
 * @property \Illuminate\Support\Carbon|null $bid_date
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property bool $sample_submitted
 * @property string|null $project_owner
 * @property string|null $project_link
 * @property string|null $project_description
 * @property float|null $project_value
 * @property string|null $leed_certified_number
 * @property string|null $lead_source_id
 * @property string|null $lead_source
 * @property string $name
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Architect $architect
 * @property-read \App\Models\Address $architectAddress
 * @property-read \App\Models\Specifier $specifier
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereLeadSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereLeadSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereLeedCertifiedNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereProjectValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereProjectDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereProjectLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereProjectOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereSampleSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereBidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereCompletionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereMarketSegmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereSpecifierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereArchitectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereArchitectAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<Opportunity>|Opportunity whereUpdatedAt($value)
 */
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
