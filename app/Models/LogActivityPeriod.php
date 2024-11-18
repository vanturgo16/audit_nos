<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivityPeriod extends Model
{
    use HasFactory;
    protected $table = 'log_activity_period';
    protected $guarded = [
        'id'
    ];
}
