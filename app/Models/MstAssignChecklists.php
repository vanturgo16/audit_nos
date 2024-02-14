<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstAssignChecklists extends Model
{
    use HasFactory;
    protected $table = 'mst_assign_checklists';
    protected $guarded=[
        'id'
    ];
}
