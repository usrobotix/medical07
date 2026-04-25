<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $fillable = [
        'code',
        'title',
        'channel',
        'language',
        'subject',
        'body',
        'target_partner_type',
    ];
}
