<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPeriodName extends Model
{
    use HasFactory;
    protected $table = 'mst_period_name';
    protected $guarded=[
        'id'
    ];
}
