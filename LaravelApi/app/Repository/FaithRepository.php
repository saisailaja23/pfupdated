<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Faith;

/**
 * Description of ParentService
**/
class FaithRepository {

   
    private $faithId;
    private $faith;

    public function __construct($faithId) {
       $this->setFaithId($faithId);

    }
    
    public  function getFaithId() {
       return $this->faithId;
    }

    public  function setFaithId($faithId) {
         $this->faithId = $faithId;
    }

    public function getFaith(){        
        return $this->faith;
    }

    public function setFaith($faith){        
        $this->faith=$faith;
    }  
  
    /* Get faith */
    public function getFaithDetails() {
        try{
            $faithObj=new Faith;
            $faithDetails =$faithObj->where('faith_id', '=',$this->getFaithId())->first();
            return $faithDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }


        
    
}
