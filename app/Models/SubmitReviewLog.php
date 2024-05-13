<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmitReviewLog extends Model
{
    use HasFactory;
    protected $table = 'submit_review_log';
    protected $guarded=[
        'id'
    ];

}
