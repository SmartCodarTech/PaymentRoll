<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table='tax';


     protected $fillable = [
        'tax_type', 'amount','tax_date','civilian_id'
    ];
    
      protected $guarded = [];
}
