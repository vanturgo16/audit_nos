<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPeriodeChecklists extends Model
{
    use HasFactory;
    protected $table = 'mst_periode_checklists';
    protected $guarded=[
        'id'
    ];
}
