<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'thumbnail',
        'status',
        'description',
        'target',
        'collected',
        'donors',
        'target_date',
        'is_admin',
    ];

    protected $casts = [
        'target' => 'integer',
        'collected' => 'integer',
        'donors' => 'integer',
        'target_date' => 'date',
    ];
}
