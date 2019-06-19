<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table='tax';


     protected $fillable = [
        'tax_type', 'pecentage','organization','tax_date'
    ];
    
      protected $guarded = [];
}
