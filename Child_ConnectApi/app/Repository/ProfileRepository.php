<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Profiles;
use App\Models\Eprofile;
use App\Models\PdfTemplate;
/**
 * Description of ParentService
**/
class ProfileRepository {
   
    private $profileId;
    private $firstName;
    private $lastName;
    private $gender;
    private $accountId; 
    private $status;
    private $createdAt;
    private $modifiedAt;
    private $dob;
    private $ethnicityId;
    private $educationId;
    private $religionId;
    private $occupationId;
    private $waitingId;
    private $faithId;

    public function __construct($profileId) {
         $this->setProfileId($profileId);
    }
    
    public  function getProfileId() {
       return $this->profileId;
    }
    public  function getFirstName() {
       return $this->firstName;
    }
    public  function getLastName() {
       return $this->lastName;
    }
    public  function getGender() {
       return $this->gender;
    }
    public  function getAccountId() {
       return $this->$accountId;
    }
    public  function getStatus() {
       return $this->status;
    }
    public  function getCreatedAt() {
       return $this->createdAt;
    }

    public  function getModifiedAt($modifiedAt) {
       $this->modifiedAt=$modifiedAt;
    }

    public  function setProfileId($profileId) {
         $this->profileId = $profileId;
    }
    public  function setFirstName($firstName) {
       $this->firstName=$firstName;
    }
    public  function setLastName($lastName) {
       $this->lastName=$lastName;
    }
    public  function setGender($gender) {
       $this->gender=$gender;
    } 
    public  function setAccountId($accountId) {
       $this->$accountId=$accountId;
    }
    public  function setStatus($status) {
       $this->status=$status;
    }    
    public  function setCreatedAt($createdAt) {
       $this->createdAt=$createdAt;
    }
    public  function setModifiedAt($modifiedAt) {
       $this->modifiedAt=$modifiedAt;
    }  

    public  function setEthnicityId($ethnicityId) {
       $this->ethnicityId=$ethnicityId;
    } 
     public  function setEducationId($educationId) {
       $this->educationId=$educationId;
    } 

      public  function setDOB($dob) {
       $this->dob=$dob;
    } 
   public  function setReligionId($religionId) {
       $this->religionId=$religionId;
    } 
     public  function setOccupation($occupation) {
       $this->occupation=$occupation;
    } 

   public  function setWaitingId($waitingId) {
       $this->waitingId=$waitingId;
    } 
   public  function setFaithId($faithId) {
       $this->faithId=$faithId;
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
             
        } 
    }    

     public function getAccountIdByUserName($user_name){
         try{
            $account=new Account;
            $accountDetails =$account->where('username', '=',$user_name)->first();       
            return $accountDetails;
        }catch(\Exception $e){
             //return Redirect::to('/login-me')->with('msg', ' Sorry something went worng. Please try again.');
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
            $user=new Account;
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
    
    
    

    public function getPdfDetails($acc_id){
         try{
            
            $pdfobj=new PdfTemplate;
            $pdfdetails =$pdfobj->where('user_id', '=',$acc_id)
                                  ->where('isDeleted', '=','N')
                                  ->where('isDefault', '=','Y')
                                  ->first();       
            return  $pdfdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function saveProfile(){
        try{
            $profiles=new Profiles;
            $saveprofile=$profiles->insert(
                                        array('first_name'=>$this->firstName(),
                                            'last_name'=>$this->lastName(),
                                            'gender'=>$this->userName(),
                                            'accounts_id'=>$this->accountId,
                                            'status'=>$this->status, 
                                            'created_at'=>$this->createdAt(),
                                            'modified_at'=>$this->modifiedAt()
                                            )
                                    );
        }catch(\Exception $e){
             //Throwing Exception here
        } 
    }

    
    /*  
        *   update profile details
        *   @return boolean $updateprofile
         
    */

     public function updateProfile(){
        try{
           
           $profiles=new Profiles;
           $updateprofile=$profiles->where('profile_id', $this->profileId)
                               ->update(['first_name'=>$this->firstName,
                                         
                                            'gender'=>$this->gender,
                                            'dob'=>$this->dob,
                                            'ethnicity_id'=>$this->ethnicityId,
                                            'education_id'=>$this->educationId,
                                            'religion_id'=>$this->religionId,
                                            'occupation'=>$this->occupation,
                                            'waiting_id'=>$this->waitingId,
                                            'faith_id'=>$this->faithId
                                            ]
                                            
                                    );
              return $updateprofile;

        }catch(\Exception $e){
             //Throwing Exception here
        } 
    }
    /* Get Family Status*/
    public function  getFStatus($account_id)
    {
        try{
        $profiles=new Profiles;
        $countAccount=$profiles->where('accounts_id', $account_id)
                        ->count();
                        return $countAccount;
         }
              
             catch(\Exception $e){
             //Throwing Exception here
           } 
    }

     

}