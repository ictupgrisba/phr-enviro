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
 * @property $status
 * @property $remarks
 * @property $post_id
 * @property $user_id
 * @property $created_at
 *
 * RELATION
 * @property $post
 * @property $user
 * @property $detailIn
 * @property $detailOut
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WorkTripDetail extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $perPage = 10;

    protected $table = 'work_trip_details';

    protected $fillable = [
        'transporter',
        'driver',
        'police_number',
        'time_in',
        'type',
        'load',
        'volume',
        'tds',
        'area_name',
        'time_out',
        'status',
        'remarks',
        'post_id',
        'user_id',
        'created_at'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailIn(): HasOne
    {
        return $this->hasOne(
            WorkTripDetailIn::class, 'work_trip_detail_id', 'id'
        );
    }

    public function detailOut(): HasOne
    {
        return $this->hasOne(
            WorkTripDetailOut::class, 'work_trip_detail_id', 'id'
        );
    }

    protected static function booted(): void
    {
        self::creating(function ($model) {
            $model->id = Str::uuid()->toString();
            return $model;
        });
    }
}
