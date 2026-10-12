<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolRegistration extends Model
{
    protected $fillable = [
        'school_name',
        'address',
        'country',
        'state',
        'email',
        'phone',
        'website',
        'number_of_classes',
        'student_count_range',
        'reason',
        'status',
    ];
}
