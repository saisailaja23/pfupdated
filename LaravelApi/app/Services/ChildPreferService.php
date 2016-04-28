<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\ChildPreferRepository;
use App\Repository\EthnicityRepository;
/**
 * Description of ParentService
**/

class ChildPreferService {

    private $accountId; 
    private $ethnicityPref;
    private $agePref;  
    private $adoptionPref;
        
    public function __construct($accountId) {
       $this->setAccoountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccoountId($accountId) {
         $this->accountId = $accountId;
    } 

    public function getEthnicityPref() {
        return $this->ethnicityPref;
    } 

    public function getAgePref() {
        return $this->agePref;
    } 

    public function getAdoptionTypePref() {
        return $this->adoptionPref;
    }


    /* Get Child Preference Details */
    public function getChildPreferDetails() {
        try{
                   
        $childPrefer=new ChildPreferRepository($this->accountId);
        if($ethnicityPreferDetails=$childPrefer->getEthnicityPrefDetails()){
            foreach ($ethnicityPreferDetails as $ethnicityPrefer) {
                    $ethnicity=new EthnicityRepository($ethnicityPrefer->ethnicity_id);
                    $ethnicityDetails=$ethnicity->getEthnicityDetails();
                    $this->ethnicityPref[]=$ethnicityDetails->ethnicity;   
                               
            }
        } 
        if($agePreferDetails=$childPrefer->getAgePrefDetails()){
            foreach ($agePreferDetails as $agePrefer) {
                    $ageDetails=$childPrefer->getAgeGroupFromId($agePrefer->age_group_id);
                    $this->agePref[]=$ageDetails->Age_group;   
                      
            }
        } 
        if($adoptionPreferDetails=$childPrefer->getAdoptionPrefDetails()){
            foreach ($adoptionPreferDetails as $adoptionPrefer) {
                    $adoptionDetails=$childPrefer->getAdoptionFromId($adoptionPrefer->adoption_type_id);
                    $this->adoptionPref[]=$adoptionDetails->adoption_type;  

            }
        }      
        return $this;   
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    } 
    
    
}


    
