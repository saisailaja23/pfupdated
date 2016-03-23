<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetails extends Model
{
    //
    
     protected $table = 'ContactDetails';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 


}
