<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    //
    
   
    public $timestamps = false;
    
    public function ageGroupPrefer() {
        return $this->belongsTo('AgeGroupPref');
    } 

}
