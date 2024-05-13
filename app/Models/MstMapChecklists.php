<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstMapChecklists extends Model
{
    use HasFactory;
    protected $table = 'mst_mapchecklists';
    protected $guarded=[
        'id'
    ];
}
