<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\PdfTemplate;
use App\Repository\AccountRepository;
use App\Repository\ProfileRepository;
use App\Repository\EthnicityRepository;
use App\Repository\FaithRepository;
use App\Repository\WaitingRepository;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\Repository\ReligionRepository;
use App\Repository\RegionRepository;
use App\Repository\ChildRepository;
/**
 * Description of ParentService
**/
class FilterService {  
   
    
   
    /*Get all profiles */
    public  function getAllProfiles(){
    try{ 
        $accountObj=new AccountRepository(null);  
        $accountDetails=$accountObj->getAllAccounts();
        foreach ($accountDetails as $account) {
            $accountIds[]=$account->account_id;
        }
        return  $accountIds;
    }catch(\Exception $e){
             //Add Exception here
        } 
    }

	public function getProfilesByReligion($religion){
        try{     
        $profileObj=new ProfileRepository(null);
        $religionObj=new ReligionRepository($religion); 
        $religions=$religionObj->getReligionDetails();
        $religionId=   $religions->ReligionId;    
        $profileDetails=$profileObj->getProfilesByReligion($religionId);
        foreach ($profileDetails as $account) {
              $accountIds[]=$account->accounts_id;
        }
        return $accountIds;
    }catch(\Exception $e){
             //Add Exception here
        } 
    }
     public function getProfilesByRegion($region){ 
        try{
        $regionObj=new RegionRepository(null); 
        $regions=$regionObj->getRegionById($region);
        $regionId=   $regions->RegionId;
        $regionObj1=new ContactRepository(null); 
        $accountDetails=$regionObj1->getContactByRegion($regionId);
        foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->Account_id;
        }
         return $accountIds;
     }catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getProfilesByKids($kids)   {
        try{
        $childObj=new ChildRepository($kids); 
        $childDetails=$childObj->getChildDetails();
         foreach ($childDetails as $childDetail) {
            $accountIds[]= $childDetail->Accounts_id;

        }
         return $accountIds;
     }
     catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getProfilesByState($state){ 
        try{
        $stateObj=new StateRepository(null); 
        $states=$stateObj->getStateById($state);
        $stateId=   $states->state_id;
        $stateObj1=new ContactRepository(null); 
        $accountDetails=$stateObj1->getContactByState($stateId);
        foreach ($accountDetails as $accountDetail) {
          $accountIds[]= $accountDetail->Account_id;

        }
        return $accountIds;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }
    public function getProfilesByName($name){ 
        try{ 
        $profileObj=new ProfileRepository(null);
         $accountDetails=$profileObj->getProfileByName($name);
        foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->accounts_id;
        }
        return $accountIds;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }
    public function getProfilesChildPref($child_pref){
        try{  
        $ethnicityObj=new EthnicityRepository(null); 
        $ethnicity=$ethnicityObj->getEthinicityById($child_pref);
        $ethinicityId=   $ethnicity->ethnicity_id;
        $accountDetails=$ethnicityObj->getProfilesByEthinicity($ethinicityId);
        foreach ($accountDetails as $accountDetail) {
           $accountIds[]= $accountDetail->account_id;
        }
        return $accountIds;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }
    public function getProfilesBySort($sort){ 
        try{ 
         $profile=new ProfileRepository(null);  
        $accountDetails=$profile->getAllProfilesBySort($sort);
        foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->accounts_id;
        }
        return $accountIds;
    } 
    }
    catch(\Exception $e){
             //Add Exception here
        }  

    
    
}