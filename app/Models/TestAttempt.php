<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'training_participant_id',
        'employee_id',
        'attempt_number',
        'status',
        'started_at',
        'submitted_at',
        'auto_submitted_at',
        'multiple_choice_score',
        'essay_score',
        'final_score',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'multiple_choice_score' => 'decimal:2',
            'essay_score' => 'decimal:2',
            'final_score' => 'decimal:2',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'auto_submitted_at' => 'datetime',
            'status' => 'string',
        ];
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function trainingParticipant(): BelongsTo
    {
        return $this->belongsTo(TrainingParticipant::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class, 'test_attempt_id');
    }
}
