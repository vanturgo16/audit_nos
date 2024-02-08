<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstChecklistDetails extends Model
{
    use HasFactory;
    protected $table = 'mst_checklist_details';
    protected $guarded=[
        'id'
    ];
}
