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
use App\Repository\MembershipRepository;
use App\Repository\ChildProfileRepository;
use App\Exceptions\ParentFinderException;

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


    /*Get profiles By Religion*/
	public function getProfilesByReligion($religion){
        try{     
        $profileObj=new ProfileRepository(null);
        $religionObj=new ReligionRepository($religion); 
        $religions=$religionObj->getReligionDetails();
        if(!empty($religions)){
            $religionId=   $religions->ReligionId;    
        $profileDetails=$profileObj->getProfilesByReligion($religionId);
        foreach ($profileDetails as $account) {
              $accountIds[]=$account->accounts_id;
        }
        return $accountIds;
            
        }
        else{
           throw new ParentFinderException('no-profiles-found');
        }
        
    }catch(\Exception $e){
             //Add Exception here
        } 
    }

    /*Get profiles By Region*/
     public function getProfilesByRegion($region){ 
        try{
        $regionObj=new RegionRepository(null); 
        $regions=$regionObj->getRegionById($region);
        if(!empty($regions)){
        $regionId=   $regions->RegionId;
        $regionObj1=new ContactRepository(null); 
        $accountDetails=$regionObj1->getContactByRegion($regionId);
        foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->Account_id;
        }
         return $accountIds;
        }
         else{
           throw new ParentFinderException('no-profiles-found');
        }
     }catch(\Exception $e){
             //Add Exception here
        } 
    }


    /*Get profiles By Kids*/
    public function getProfilesByKids($kids)   {

        try{
        $childObj=new ChildRepository($kids); 
        if($childDetails=$childObj->getChildDetails()){
             foreach ($childDetails as $childDetail) {
                $accountIds[]= $childDetail->Accounts_id;

            }
            if($accountIds){
             return $accountIds;
            }
            else{
                throw new ParentFinderException('user_not_found',$e->getMessage());
            }
         }
     }
     catch(\Exception $e){       
             //Add Exception here
        } 
    }


    /*Get profiles By State*/
    public function getProfilesByState($state){ 
        try{
        $stateObj=new StateRepository(null); 
        $states=$stateObj->getStateById($state);
        if(!empty($states)){
        $stateId=   $states->state_id;
        $stateObj1=new ContactRepository(null); 
        $accountDetails=$stateObj1->getContactByState($stateId);
        foreach ($accountDetails as $accountDetail) {
          $accountIds[]= $accountDetail->Account_id;

        }
        return $accountIds;
    }
    else{
        throw new ParentFinderException('user_not_found',$e->getMessage());
    }
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    /*Get profiles By Name*/
    public function getProfilesByName($name){ 
        try{ 
        $profileObj=new ProfileRepository(null);
         $accountDetails=$profileObj->getProfileByName($name);
         if(count($accountDetails) > 0){
           foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->accounts_id;
        }
        return $accountIds;
         }
         else{
            throw new ParentFinderException('user_not_found',$e->getMessage()); 
         }
        
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


    /*Get profiles By ChildPreference*/
    public function getProfilesChildPref($child_pref){
        try{  
        $ethnicityObj=new EthnicityRepository(null); 
        $ethnicity=$ethnicityObj->getEthinicityById($child_pref);
        $ethinicityId=   $ethnicity->ethnicity_id;
        $accountDetails=$ethnicityObj->getProfilesByEthinicity($ethinicityId);
        if(count($accountDetails) > 0){
            foreach ($accountDetails as $accountDetail) {
           $accountIds[]= $accountDetail->account_id;
        }
        return $accountIds;
        }
        else{
            throw new ParentFinderException('user_not_found',$e->getMessage()); 
        }

        
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


    /*Get profiles By Sort*/
    public function getProfilesBySort($sort){ 
        try{ 
        $profile=new ProfileRepository(null);  
        $accountDetails=$profile->getAllProfilesBySort($sort);
        foreach ($accountDetails as $accountDetail) {
            $accountIds[]= $accountDetail->accounts_id;
        }
        return $accountIds;
    } 
    
    catch(\Exception $e){
             //Add Exception here
        }  
    }



    /*Get All Memberships*/
    public  function getAllMembershipIds(){
    try{ 
        $membershipObj=new MembershipRepository(null);  
        $membershipidDetails=$membershipObj->getAllMembershipids();
        if(count($membershipidDetails) > 0){
            foreach ($membershipidDetails as $membershipid) {
            $membershipids[]=$membershipid->ID;
        }
         return  $membershipids;
        }
        else{
           throw new ParentFinderException('membership_not_found');
        }
        
       
    }catch(\Exception $e){
           // throw new ParentFinderException('membership_not_found');
        } 
    }

    public function getProfilesByCountry($country){ 
        try {
        $countryObj=new CountryRepository(null); 
        $country=$countryObj->getCountryByName($country);
         if(!empty($country)){
           $countryId=   $country->country_id;
         $countryObj1=new ContactRepository(null); 
         $accountDetails=$countryObj1->getContactByCountry($countryId);
         if(count($accountDetails)!=0)
         {
         foreach ($accountDetails as $accountDetail) {
          $accountIds[]= $accountDetail->Account_id;
            }
        return $accountIds;
        }
       else
       {
        throw new ParentFinderException('no-profiles-found');
        }
     } 
        else{
           
         throw new ParentFinderException('countries-not-found');
         }
      }
        catch (Exception $e) {
           
           //Add Exception here 
        }
    }

    
    public function getAllChildIds(){

        try{ 
            $childObj=new ChildProfileRepository(null);  
            $childdetails=$childObj->getAllChildids();
            if(count($childdetails) > 0){
                foreach ($childdetails as $childId) {
                 $childIds[]=$childId->child_id;
            }
             return  $childIds;
            }else{
               throw new ParentFinderException('child_not_found');
            }
        
       
            }catch(\Exception $e){
               // throw new ParentFinderException('child_not_found');
            } 
        }
    
}