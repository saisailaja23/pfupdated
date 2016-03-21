<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    //
    
   	protected $table = 'Age_group';
    public $timestamps = false;
    
    public function ageGroupPrefer() {
        return $this->belongsTo('AgeGroupPref');
    } 

}
