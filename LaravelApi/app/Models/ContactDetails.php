<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetails extends Model
{
    //
    
     protected $table = 'contactdetails';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 


}
