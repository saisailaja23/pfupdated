<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    //
    
   protected $table = 'Ethnicity';
    public $timestamps = false;
    
    public function ethnicityPref() {
        return $this->belongsTo('EthnicityPref');
    } 

}
