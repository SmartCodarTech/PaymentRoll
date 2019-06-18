<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $table='allowance';


     protected $fillable = [
        'allowance_type', 'amount','allowance_date','civilian_id'
    ];
    
      protected $guarded = [];
}