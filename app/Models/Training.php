<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'passing_grade',
        'max_attempts',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => 'string',
            'passing_grade' => 'decimal:2',
            'max_attempts' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(TrainingMaterial::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function preTest(): HasOne
    {
        return $this->hasOne(Test::class)->where('type', 'pre_test')->withDefault();
    }
}
