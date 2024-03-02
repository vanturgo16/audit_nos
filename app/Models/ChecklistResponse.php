<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistResponse extends Model
{
    use HasFactory;
    protected $table = 'checklist_response';
    protected $guarded=[
        'id'
    ];
}
