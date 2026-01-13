<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $architect_rep_id
 * @property int $id
 * @property string $architect_name
 * @property int $architect_type_id
 * @property string|null $class_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\User|null $architectRep
 * @property-read \App\Models\ArchitectType $architectType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Specifier> $specifiers
 * @property-read int|null $specifiers_count
 * @method static \Database\Factories\ArchitectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereArchitectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereArchitectRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereArchitectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Architect whereUpdatedAt($value)
 */
	class Architect extends \Eloquent {}
}

