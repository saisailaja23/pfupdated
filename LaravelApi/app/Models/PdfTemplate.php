<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfTemplate extends Model
{
    //
    
    protected $table = 'pdf_template_user';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
