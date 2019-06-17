<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Civilian extends Model
{
    protected $table='civilian';

     protected $fillable = [
            'lastname',
            'firstname',
            'service_id',
            'email',
            'type',
            'gender',
            'salary',
            'date_hired',
            'department_id',
            'bank_id',
            'picture'
    ];
    
      protected $guarded = [];


}
