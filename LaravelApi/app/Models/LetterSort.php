<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterSort extends Model
{
    //
    protected $table = 'letters_sort';
   
    public $timestamps = false;
    protected $dateFormat = 'U';

    public function user() {
        return $this->belongsTo('User');
    } 
}
