<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Religions;

/**
 * Description of ParentService
**/
class ReligionRepository {

   
    private $religion;

    public function __construct($religion) {
       $this->setReligion($religion);

    }
    
    public  function getReligion() {
       return $this->religion;
    }

    public  function setReligion($religion) {
         $this->religion = $religion;
    }

  
  
    /* Get Religion */
    public function getReligionDetails() {
        try{
            $religionObj=new Religions;
            $religionDetails =$religionObj->where('Religion', '=',$this->religion)->first();
            return $religionDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }


     public function getAllReligions() {
         try{
         $religionObj=new Religions;
         $religionDetails =$religionObj->get();
            return $religionDetails;
             }catch(\Exception $e){
             //Add Exception here
              }  
        }
        
    
}
