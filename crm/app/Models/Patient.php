<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'full_name',
        'dob',
        'phone',
        'email',
        'country',
        'city',
        'notes',
    ];

    public function cases()
    {
        return $this->hasMany(MedicalCase::class, 'patient_id');
    }
}