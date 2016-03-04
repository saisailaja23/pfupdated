<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Profiles;
use App\Models\Account;
use App\Models\Eprofile;

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
        
    public function getProfileByName($name){
       
         try{
            $nameobj=new Profiles;
            $nameDetails =$nameobj->where('first_name', 'like','%'.$name.'%')
                                  ->orWhere('last_name', 'like','%'.$name.'%')
                                  ->get();       
            return $nameDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }


    public function getProfilesByEthinicity($ethinicityId){
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

         public  function getAllProfilesBySort($sort){ 
           
        try{
            $user=new User;
            $users=$user->get();
            $profiles=new Profiles;
            foreach($users as $User){
                if($sort == 'newFirst'){
                    $profileDetails =$profiles->where('status','=',1)
                         ->groupBy('accounts_id')
                         ->orderBy('profile_id','DESC')
                         ->get(); 
               
            }
             else if($sort == 'oldFirst'){
                $profileDetails = $profiles->where('status','=',1)
                                ->groupBy('accounts_id')
                                ->orderBy('profile_id','ASC')
                                ->get(); 
            }
             else if($sort == 'FirstName'){
                $profileDetails = $profiles->where('status','=',1)
                                ->groupBy('accounts_id')
                                ->orderBy('first_name','ASC')
                                ->get(); 
            }
            else if($sort == 'random'){
                $profileDetails = $profiles->where('status','=',1)
                                ->groupBy('accounts_id')
                                ->orderByRaw("RAND()")
                                ->get(); 
            }     
            }
            return $profileDetails;
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

    public function getFlipbook($acc_id){
         try{
            $eprofileobj=new Eprofile;
            $flipbookdetails =$eprofileobj->where('owner_id', '=',$acc_id)
                                  ->where('title', '=','E-book Profile')
                                  ->first();       
            return  $flipbookdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }

}