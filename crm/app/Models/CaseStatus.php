<?php

namespace App\Models;

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
}