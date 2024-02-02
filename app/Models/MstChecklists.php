<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstChecklists extends Model
{
    use HasFactory;
    protected $table = 'mst_checklists';
    protected $guarded=[
        'id'
    ];
}
