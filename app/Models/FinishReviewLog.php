<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishReviewLog extends Model
{
    use HasFactory;
    protected $table = 'finish_review_log';
    protected $guarded=[
        'id'
    ];

}
