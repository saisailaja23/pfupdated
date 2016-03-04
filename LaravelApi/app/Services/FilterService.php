<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
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
        $accountObj=new AccountRepository(null);  
        $accountDetails=$accountObj->getAllAccounts();
        foreach ($accountDetails as $account) {
            $accountIds[]=$account->account_id;
        }
        return  $accountIds;
    }

	public function getProfilesByReligion($religion){     
        $profileObj=new ProfileRepository(null);
        $religionObj=new ReligionRepository($religion); 
        $religions=$religionObj->getReligionDetails();
        $religionId=   $religions->ReligionId;    
        $profileDetails=$profileObj->getProfilesByReligion($religionId);
        foreach ($profileDetails as $account) {
              $accountIds[]=$account->accounts_id;
        }
        return $accountIds;
    }
     public function getProfilesByRegion($region){ 
        $regionObj=new RegionRepository(null); 
        $regions=$regionObj->getRegionById($region);
        $regionId=   $regions->RegionId;
        $regionObj1=new ContactRepository(null); 
        $accountDetails=$regionObj1->getContactByRegion($regionId);
        foreach ($accountDetails as $accountDetail) {
          $accountId= $accountDetail->Account_id;
           $profileObj=new ProfileRepository(null);
           $profileIds=$profileObj->getProfileIdByAccount($accountId);
           $this->profileIds[]=$profileIds->profile_id;
        }
        return $this;
    }

    public function getProfilesByKids($kids)   {
        $childObj=new ChildRepository($kids); 
        $childDetails=$childObj->getChildDetails();
         foreach ($childDetails as $childDetail) {
          $accountId= $childDetail->Accounts_id;
           $profileObj=new ProfileRepository(null);
           $profileIds=$profileObj->getProfileIdByAccount($accountId);
           $this->profileIds[]=$profileIds->profile_id;
        }
        return $this;
    }

    public function getProfilesByState($state){ 
        $stateObj=new StateRepository(null); 
        $states=$stateObj->getStateById($state);
        $stateId=   $states->state_id;
        $stateObj1=new ContactRepository(null); 
        $accountDetails=$stateObj1->getContactByState($stateId);
        foreach ($accountDetails as $accountDetail) {
          $accountId= $accountDetail->Account_id;
           $profileObj=new ProfileRepository(null);
           $profileIds=$profileObj->getProfileIdByAccount($accountId);
           $this->profileIds[]=$profileIds->profile_id;
        }
        return $this;
    }

   public function getAccountIdByUserName($user_name){
    $profileObj=new ProfileRepository(null);
    $accountId=$profileObj->getAccountIdByUserName($user_name);
    $this->accountId=$accountId->account_id;
   }
    
}