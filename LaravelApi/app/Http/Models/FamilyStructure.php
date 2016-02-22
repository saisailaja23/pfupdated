<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyStructure extends Model
{
    //
    
    protected $table = 'Family_structure';
    public $timestamps = false;
    
    public function home() {
        return $this->belongsTo('Home');
    } 

}
