<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_type_id',
        'project_id',
        'working_hours',
        'description',
        'timestamp_id'
    ];

    public function timestampDraft(): BelongsTo
    {
        return $this->belongsTo(TimestampDraft::class, 'id', 'timestamp_id');
    }

    public function workType(): BelongsTo
    {
        return $this->belongsTo(WorkType::class, 'work_type_id', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
