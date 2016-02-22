<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RelationshipStatus extends Model
{
    //
    
    protected $table = 'Relationship_status';
    public $timestamps = false;
    
    public function home() {
        return $this->belongsTo('Home');
    } 

}
