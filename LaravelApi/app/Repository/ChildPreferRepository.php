<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EthnicityPref;
use App\Models\AgeGroupPref;
use App\Models\AdoptionTypePref;
use App\Models\AdoptionType;
use App\Models\AgeGroup;
use App\Exceptions\ParentFinderException;
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
             return  $agePreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('age-prefer-not-found',$e->getMessage());
        }  
    }

    /* Get ethnicity */
    public function getAdoptionPrefDetails() {
        try{
            $adoptionObj=new AdoptionTypePref;
            $adoptionPreferDetails =$adoptionObj->where('account_id', '=',$this->getAccountId())->get();
             return  $adoptionPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('adoption-prefer-not-found',$e->getMessage());
        }  
    }

    public function getAgeGroupFromId($age_group_id){
        try{
            $ageObj=new AgeGroup;
            $ageDetails =$ageObj->where('Age_group_id', '=',$age_group_id)->first();
            return $ageDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('age-group-not-found',$e->getMessage());
        } 
    }

    public function getAdoptionFromId($adoption_id){
        try{
            $adoptionObj=new AdoptionType;
            $adoptionDetails =$adoptionObj->where('adoption_type_id', '=',$adoption_id)->first();
            return $adoptionDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('adoption-type-not-found',$e->getMessage());
        } 
    }

    
    
}
