<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ethnicity;
use App\Models\EthnicityPref;

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

    public function getEthinicityById($child_pref){
        try{
            $ethinicityObj=new Ethnicity;
            $regionDetails =$ethinicityObj->where('ethnicity', '=',$child_pref)->first();
            return $regionDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  

    }
    public function getProfilesByEthinicity($ethinicityId){
        try{
            $ethinicityObj=new EthnicityPref;
             $ethinicityDetails =$ethinicityObj->where('ethnicity_id', '=',$ethinicityId)->get();
            return $ethinicityDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  

    }    
    
}
