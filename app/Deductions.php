<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deductions extends Model
{
     protected $table='deduct';


     protected $fillable = [
        'name', 'branch','code',
    ];
    
      protected $guarded = [];
}
