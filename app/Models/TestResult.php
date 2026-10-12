<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'user_id',
        'numerical_score',
        'verbal_score',
        'spatial_score',
        'interest_score',
        'total_score',
        'recommended_combination',
        'careers',
        'universities',
        'motivational_message',
    ];

    protected $casts = [
        'careers'      => 'array',
        'universities' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
