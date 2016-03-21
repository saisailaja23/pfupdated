<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EthnicityPref;
use App\Models\AgeGroupPref;
use App\Models\AdoptionTypePref;
use App\Models\AdoptionType;

/**
 * Description of ParentService
**/
class ChildPreferRepository {

   
    private $ethnicityId;
    private $accountId;

    public function __construct($accountId) {
       $this->setAccountId($accountId);

    }
    
    public  function getAccountId() {
       return $this->accountId;
    }

    public  function setAccountId($accountId) {
         $this->accountId = $accountId;
    }
   
  
    /* Get ethnicity preference */
    public function getEthnicityPrefDetails() {
        try{
            $ethnicityObj=new EthnicityPref;
            $ethnicityPreferDetails =$ethnicityObj->where('account_id', '=',$this->getAccountId())->get();
            return $ethnicityPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('ethnicity-prefer-not-found',$e->getMessage());
        }  
    }

    /* Get age preference */
    public function getAgePrefDetails() {
        try{
            $ageObj=new AgeGroupPref;
            $agePreferDetails =$ageObj->where('account_id', '=',$this->getAccountId())->get();
            return $agePreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('age-prefer-not-found',$e->getMessage());
        }  
    }

    /* Get ethnicity */
    public function getAdoptionPrefDetails() {
        try{
            $adoptionObj=new Adoptionpref;
            $adoptionPreferDetails =$adoptionObj->where('account_id', '=',$this->getAccountId())->get();
            return $adoptionPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('adoption-prefer-not-found',$e->getMessage());
        }  
    }

    
    
}
