<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timestamp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'day',
        'start_time',
        'end_time',
        'daily_working_hours'
    ];

    public function setDayAttribute($value)
    {
        $this->attributes['day'] = (new Carbon($value))->format('Y-m-d');
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = (new Carbon($value))->addHours(1)->format('Y-m-d H:i:s');
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = (new Carbon($value))->addHours(1)->format('Y-m-d H:i:s');
    }


    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class, 'timestamp_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
