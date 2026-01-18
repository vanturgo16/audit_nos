<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistResponsesLog extends Model
{
    use HasFactory;
    protected $table = 'checklist_responses_log';
    protected $guarded=[
        'id'
    ];
}
