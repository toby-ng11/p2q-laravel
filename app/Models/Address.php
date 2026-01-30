<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $addressable_id
 * @property string $addressable_type
 * @property string|null $url
 * @property string|null $email_address
 * @property string|null $central_phone_number
 * @property string|null $phys_country
 * @property string|null $phys_postal_code
 * @property string|null $phys_state
 * @property string|null $phys_city
 * @property string|null $phys_address2
 * @property string $phys_address1
 * @property string $name
 * @property int $id
 * @property-read \App\Models\Address $addressable
 */
class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'phys_address1',
        'phys_address2',
        'phys_city',
        'phys_state',
        'phys_postal_code',
        'phys_country',
        'central_phone_number',
        'email_address',
        'url',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
