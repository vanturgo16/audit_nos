<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileInputResponse extends Model
{
    use HasFactory;
    protected $table = 'file_input_response';
    protected $guarded=[
        'id'
    ];
}
