<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Profiles;
/**
 * Description of ParentService
**/
class ProfileRepository {
   
    private $profileId;
    public function __construct($profileId) {
         $this->setProfileId($profileId);
    }
    
    public  function getProfileId() {
       return $this->profileId;
    }
    public  function setProfileId($profileId) {
         $this->profileId = $profileId;
    }   
    
    /*Get Couple Profile */
     public  function getCouple($accountId){   
        try{
            $couple=new Profiles;
            $coupleDetails =$couple->where('accounts_id', '=',$accountId) 
                                ->where('profile_id', '!=',$this->profileId)
                                ->first();   
            return $coupleDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    } 
   
    /* Get all profiles */
    public  function getAllProfiles(){   
        try{
            $user=new User;
            $users=$user->get();
            $profiles=new Profiles;
            foreach($users as $User){
                
                $profileDetails =$profiles->where('status','=',1)
                                        ->groupBy('accounts_id')
                                        ->get();  
            }
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    } 
    /* Get a single profiles */
   
    public  function getProfile(){   
        try{
            $profiles=new Profiles;
            $profileDetails =$profiles->where('profile_id', '=',$this->profileId)->first();       
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    } 

		public function getProfilesByReligion($religion_id){
			try{
            $profiles=new Profiles;
            $profileDetails =$profiles
							 ->join('Religions', 'profiles.religion_id', '=', 'Religions.ReligionId')									
							 ->where('religion_id', '=',$religion_id)
                             ->groupBy('accounts_id')
                             ->get();
							
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        }
		}

        public function getProfilesByRegion($region_id){
            try{
            $contact=new Profiles;
            $profileDetails =$profiles
                             ->join('Regions', 'profiles.region_id', '=', 'Regions.RegionId')                                   
                             ->where('region_id', '=',$region_id)
                             ->get();
                            
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        }
        }


    public function getProfileIdByAccount($account_id){
         try{
            $account=new Profiles;
            $accountDetails =$account->where('accounts_id', '=',$account_id)->first();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }    

     public function getAccountIdByUserName($user_name){
         try{
            $account=new Account;
            $accountDetails =$account->where('username', '=',$user_name)->first();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }    
        
    
}