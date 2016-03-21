<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AgeGroupPref extends Model
{
    //
    
    protected $table = 'age_group_preference';
    public $timestamps = false;
    public function user() {
        return $this->belongsTo('User');
    } 

    public function ageGroup() {
        return $this->hasOne('AgeGroup');
    } 


}
