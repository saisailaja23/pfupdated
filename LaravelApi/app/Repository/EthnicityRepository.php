<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ethnicity;

/**
 * Description of ParentService
**/
class EthnicityRepository {

   
    private $ethnicityId;
    private $ethnicity;

    public function __construct($ethnicityId) {
       $this->setEthnicityId($ethnicityId);

    }
    
    public  function getEthnicityId() {
       return $this->ethnicityId;
    }

    public  function setEthnicityId($ethnicityId) {
         $this->ethnicityId = $ethnicityId;
    }

    public function getEthnicity(){        
        return $this->ethnicity;
    }

    public function setEthnicity($ethnicity){        
        $this->ethnicity=$ethnicity;
    }  
  
    /* Get ethnicity */
    public function getEthnicityDetails() {
        try{
            $ethnicityObj=new Ethnicity;
            $ethnicityDetails =$ethnicityObj->where('ethnicity_id', '=',$this->getEthnicityId())->first();
            return $ethnicityDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }


        
    
}
