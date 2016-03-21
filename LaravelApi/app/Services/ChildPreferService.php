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
    private $adoptionTypepref;
        
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
        return $this->stateId;
    } 

    public function getAdoptionTypepref() {
        return $this->homeNumber;
    }

    public function getChildPreferDetails() {
        try{
        $childPrefer=new ChildPreferRepository($this->accountId);
        if($ethnicitypreferDetails=$childPrefer->getEthnicityPrefDetails()){
            foreach ($ethnicitypreferDetails as $ethnicityprefer) {
                    $ethnicity=new EthnicityRepository($ethnicityprefer->ethnicity_id);
                    $ethnicityDetails=$ethnicity->getEthnicityDetails();
                    $this->ethnicityPref[]=$ethnicityDetails->ethnicity;                   
            }
        } 
        if($ethnicitypreferDetails=$childPrefer->getEthnicityPrefDetails()){
            foreach ($ethnicitypreferDetails as $ethnicityprefer) {
                    $ethnicity=new EthnicityRepository($ethnicityprefer->ethnicity_id);
                    $ethnicityDetails=$ethnicity->getEthnicityDetails();
                    $this->ethnicityPref[]=$ethnicityDetails->ethnicity;                   
            }
        }     
        return $this;   
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    } 
    
    
}


    
