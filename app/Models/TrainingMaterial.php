<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_type',
        'url',
        'is_downloadable',
        'is_required',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_downloadable' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'integer',
            'type' => 'string',
            'status' => 'string',
        ];
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(MaterialAccessLog::class, 'training_material_id');
    }
}
