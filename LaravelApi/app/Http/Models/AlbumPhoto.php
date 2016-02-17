<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumPhoto extends Model
{
    //
    
   
    public $timestamps = false;
    protected $table = 'Album_photo';
    
    public function album() {
        return $this->belongsTo('Album');
    } 

}
