<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    //
    
   
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

    public function familyStructure() {
        return $this->hasOne('FamilyStructure');
    } 

    public function relationshipStatus() {
        return $this->hasOne('RelationshipStatus');
    } 

}
