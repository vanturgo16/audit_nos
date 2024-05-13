<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstGrading extends Model
{
    use HasFactory;
    protected $table = 'mst_grading';
    protected $guarded=[
        'id'
    ];
}
