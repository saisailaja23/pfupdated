<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\Albums;
use App\Models\Video;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;
use App\Repository\ProfileRepository;
use App\Repository\AlbumsRepository;
use App\Repository\EprofileRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of AccountService
**/
class EprofileService {

    private $id;
    private $content;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function getcontent() { 
       return $this->content;
    }
    
  
  
   
    public function getFlipbook() {
        try{ 
        $eprofileObj=new EprofileRepository(null);
        if($eprofileDetails=$eprofileObj->getFlipbookDetails($this->id)){
        $flipbooks =   $eprofileDetails->content;
        $start = strpos($flipbooks, ".com/") + 5;
        $end = strpos($flipbooks, ".html") - $start + 5;
        $flipbook = substr($flipbooks, $start, $end);
        $this->content=$flipbook;
        $this->id=$eprofileDetails->id;
        //print_r($this);
        return $this;
    }
    else{
        throw new ParentFinderException('flip_not_found');
    }
    }
    catch(\Exception $e){
              throw new ParentFinderException('flip_not_found');
        } 
         
    }

       
}