<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * Class User
 *
 * @property $id
 * @property $transporter
 * @property $driver
 * @property $police_number
 * @property $time_in
 * @property $well_name
 * @property $type
 * @property $rig_name
 * @property $load
 * @property $volume
 * @property $tds
 * @property $facility
 * @property $area_name
 * @property $wbs_number
 * @property $time_out
 * @property $remarks
 * @property $post_id
 * @property $user_id
 *
 * RELATION
 * @property $post
 * @property $user
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WorkTripDetail extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'work_trip_details';

    protected $fillable = [
        'transporter',
        'driver',
        'police_number',
        'time_in',
        'well_name',
        'type',
        'rig_name',
        'load',
        'volume',
        'tds',
        'facility',
        'area_name',
        'wbs_number',
        'time_out',
        'remarks',
        'post_id',
        'user_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
            return $model;
        });
    }
}