<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $table='bonus';


     protected $fillable = [
        'allowance_type', 'amount','allowance_date','staff_type'
    ];
    
      protected $guarded = [];
}