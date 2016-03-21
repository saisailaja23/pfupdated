<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionType extends Model
{
    //
    
   	protected $table = 'adoption_type';
    public $timestamps = false;
    
    public function ageGroupPrefer() {
        return $this->belongsTo('AdoptionTypePref');
    } 

}
