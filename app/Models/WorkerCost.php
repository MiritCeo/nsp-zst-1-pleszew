<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerCost extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'hours',
        'hourly_wage',
        'tax',
        'total_cost'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
