<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistResponses extends Model
{
    use HasFactory;
    protected $table = 'checklist_responses';
    protected $guarded=[
        'id'
    ];
}
