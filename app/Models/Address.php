<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    protected $casts = [
        'id' => 'string',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
