<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table='bank';


     protected $fillable = [
        'name', 'branch','code',
    ];
    
      protected $guarded = [];
}
