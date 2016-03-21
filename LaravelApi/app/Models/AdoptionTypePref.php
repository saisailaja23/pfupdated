<?php

namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class AdoptionTypePref extends Model
{
    //
    
    protected $table = 'adoption_type_preference';
    public $timestamps = false;
    public function user() {
        return $this->belongsTo('User');
    } 

    public function ageGroup() {
        return $this->hasOne('AdoptionType');
    } 


}
