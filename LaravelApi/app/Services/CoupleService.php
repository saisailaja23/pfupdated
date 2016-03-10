<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Repository\ProfileRepository;
use App\Repository\AccountRepository;
use App\Services\ProfileService;
use App\Repository\JournalRepository;
use App\Repository\AlbumsRepository;
/**
 * Description of ParentService
**/
class CoupleService {
   
  
    private $accountId;
    private $avatar;
   
		
    public function __construct($accountId) {
       $this->setAccoountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccoountId($accountId) {
         $this->accountId = $accountId;
    } 

    public function getAvatar() {
        return $this->avatar;
    } 
    

    /* Get a single profiles */

    public function getParentprofile1() {
         $profileId = $this->getProfileId();
         if(count($profileId>0)){
             $parentProfile = new ProfileService($profileId['parent1']);
             $this->parentprofile1 = $parentProfile->getProfile();
             return $this->parentprofile1;
        }
    }

    function getParentprofile2() {
         $profileId = $this->getProfileId();
         if(count($profileId)==2){
             $parentProfile = new ProfileService($profileId['parent2']);
             $this->parentprofile2 = $parentProfile->getProfile();
             return $this->parentprofile2;
         }
    }

    private function getProfileId(){
        $accountObj=new AccountRepository($this->accountId);
        $profileIds=$accountObj->getProfileIds();
        foreach($profileIds as $profileId){
            $profile_id[]=$profileId->profile_id;
        }
        if(count($profileIds)==2){
            $parentId['parent1']=$profile_id[0];
            $parentId['parent2']=$profile_id[1];
        }else{
             $parentId['parent1']=$profile_id[0];
        }   
        return $parentId;       
    }

    public function getAccountDetails() {
        $accountObj=new AccountRepository($this->accountId);
        $accountDetails1=$accountObj->getAccountDetails();
        $this->avatar=$accountDetails1->Avatar;
        return $this;
    }

    public function getContactDetails(){
        $contactObj=new ContactService($this->accountId);
        $contactDetails=$contactObj->getContactDetails();
        return $contactDetails;
    } 

    public function getJournalDetails(){
        $journalObj=new JournalRepository(null);
        $journalIds=$journalObj->getJournalsByAccount($this->accountId);
        foreach($journalIds as $journalId){
            $journalObj=new journalService($journalId->PostId);
            $journalDetails[]=$journalObj->getJournal();        
        }
        return $journalDetails;
    }

     public function getAlbumDetails(){
        $albumObj=new AlbumsRepository(null);
        echo $albumId=$albumObj->getAlbumByID($this->accountId);
            $journalObj=new AlbumsService(null);
            $journalDetails[]=$journalObj->getAlbums($albumId);        
      
        return $journalDetails;
    }
 
}