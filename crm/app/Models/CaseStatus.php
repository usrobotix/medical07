<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CaseStatus extends Model
{
    protected $fillable = [
        'sort_order',
        'code',
        'name',
        'description',
        'is_service',
        'is_terminal',
    ];

    protected $casts = [
        'is_service'  => 'boolean',
        'is_terminal' => 'boolean',
    ];

    /** Pipeline (non-service) statuses ordered by sort_order. */
    public function scopePipeline(Builder $query): Builder
    {
        return $query->where('is_service', false)->orderBy('sort_order');
    }

    /** Service/pause overlay statuses ordered by sort_order. */
    public function scopeService(Builder $query): Builder
    {
        return $query->where('is_service', true)->orderBy('sort_order');
    }
}