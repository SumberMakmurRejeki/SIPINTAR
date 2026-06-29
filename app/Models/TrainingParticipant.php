<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_id',
        'employee_id',
        'progress_status',
        'pre_test_status',
        'material_status',
        'post_test_status',
        'grading_status',
        'pre_test_score',
        'post_test_score',
        'final_score',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'pre_test_score' => 'decimal:2',
            'post_test_score' => 'decimal:2',
            'final_score' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'progress_status' => 'string',
            'pre_test_status' => 'string',
            'material_status' => 'string',
            'post_test_status' => 'string',
            'grading_status' => 'string',
        ];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function testAttempts(): HasMany
    {
        return $this->hasMany(TestAttempt::class);
    }
}
