<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodDealerAssesor extends Model
{
    use HasFactory;
    protected $table = 'period_dealer_assessors';
    protected $guarded=[
        'id'
    ];
}
