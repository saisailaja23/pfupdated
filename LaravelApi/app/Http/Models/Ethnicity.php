<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    //
    
   
    public $timestamps = false;
    
    public function ethnicityPref() {
        return $this->belongsTo('EthnicityPref');
    } 

}
