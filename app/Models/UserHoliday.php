<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHoliday extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'max_holiday',
        'year'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
