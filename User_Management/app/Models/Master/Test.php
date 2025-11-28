<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Test extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'calendar_tests'; // Different collection name
    
    protected $fillable = [
        'date',
        'description',
        'test_name',
        'session_id',
        'batch_id',
        'status',
        'start_time',
        'end_time',
        'duration',
        'total_marks',
        'passing_marks'
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;
}