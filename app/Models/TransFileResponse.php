<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransFileResponse extends Model
{
    use HasFactory;
    protected $table = 'trans_file_response';
    protected $guarded=[
        'id'
    ];
}
