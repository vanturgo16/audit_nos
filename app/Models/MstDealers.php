<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstDealers extends Model
{
    use HasFactory;
    protected $table = 'mst_dealers';
    protected $guarded=[
        'id'
    ];
}
