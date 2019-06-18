<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use SoftDeletes;
	
	protected $dates = ['deleted_at'];
	
	protected $fillable=['civilian_id','over_time','hours','rate','total'];
	
	
	public function civilian(){
		return $this->belongsTo('App\Civilian');
	}
	
	public function grossPay(){
		$calc = 0;
		if($this->civilian->full_time && !$this->over_time){
			return $this->gross = $this->employee->role->salary;
		}
		if($this->civilian->full_time && $this->over_time){
			$calc = $this->hours * $this->rate;
			return $this->gross = $calc + $this->employee->role->salary;
		}
		if($this->over_time || !$this->full_time){
			$calc = $this->hours * $this->rate;
			return $this->gross = $calc;
		}
		return $this->gross = 0;
	}
	
	
}

