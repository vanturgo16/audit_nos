<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstParentChecklists extends Model
{
    use HasFactory;
    protected $table = 'mst_parent_checklists';
    protected $guarded=[
        'id'
    ];
}
