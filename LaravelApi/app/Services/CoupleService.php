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
use App\Repository\LetterRepository;
use App\Exceptions\ParentFinderException;

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

         try{

         $profileId = $this->getProfileId();

         if(count($profileId>0)){
             $parentProfile = new ProfileService($profileId['parent1']);
             $this->parentprofile1 = $parentProfile->getProfile();
             return $this->parentprofile1;
        } }
    catch(\Exception $e){
          //print_r(getErrorMessage('ProfileNotFound',$e));exit;
        } 
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    function getParentprofile2() {
        try{
         $profileId = $this->getProfileId();
         if(count($profileId)==2){
             $parentProfile = new ProfileService($profileId['parent2']);
             $this->parentprofile2 = $parentProfile->getProfile();
             return $this->parentprofile2;
         }
     }
     catch(\Exception $e){
             //Add Exception here
        } 
    }

    private function getProfileId(){
        try{
        $accountObj=new AccountRepository($this->accountId);
        try{

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

    }
    catch(\Exception $e){
         // throw new ParentFinderException('not_found',4);

        } 
        return $parentId;       
    }

    public function getAccountDetails() {
        try{
        $accountObj=new AccountRepository($this->accountId);
        $accountDetails1=$accountObj->getAccountDetails();
        $this->avatar=$accountDetails1->Avatar;
        return $this;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getContactDetails(){
        try{
        $contactObj=new ContactService($this->accountId);
        $contactDetails=$contactObj->getContactDetails();
        return $contactDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    } 

    public function getJournalDetails(){
        try{
        $journalObj=new JournalRepository(null);
        $journalIds=$journalObj->getJournalsByAccount($this->accountId);
        foreach($journalIds as $journalId){
            $journalObj=new journalService($journalId->PostId);
            $journalDetails[]=$journalObj->getJournal();        
        }
        return $journalDetails;
    }catch(\Exception $e){
             //Add Exception here
        } 
    }

     public function getAlbumDetails(){
        try{
        $albumObj=new AlbumsRepository(null);
        $albumId=$albumObj->getAlbumByID($this->accountId);
        $albumDetail=$albumObj->getAlbums($albumId,$this->accountId);   
        foreach($albumDetail as $albumDetails){
            $albumserviceObj=new AlbumsService($albumDetails->ID);
             $albumout[]=$albumserviceObj->getAlbum();
        }     
      
        return $albumout;
    }
        catch(\Exception $e){
             //Add Exception here
        } 
    }

     public function getAlbumDetailsByAlbumId($albumid,$type){
        try{
        $albumObj=new AlbumsRepository(null);
        $albumDetail=$albumObj->getAlbumsByAlbumId($albumid,$this->accountId,$type);   
        foreach($albumDetail as $albumDetails){
            $albumserviceObj=new AlbumsService($albumDetails->ID);
             $albumout[]=$albumserviceObj->getAlbum();
        }     
      
        return $albumout;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getLetterDetails(){
        try{
        $letterObj=new LetterRepository(null);
        $letterIds=$letterObj->getSortedLetters($this->accountId);
        if(count($letterIds)){
            foreach($letterIds as $letterId){
            $letterObj=new LetterService($letterId->letter_id);
            $letterDetails[]=$letterObj->getLetter();        
        }
            
        }else{
            $letterIds=$letterObj->getLettersByAccount($this->accountId);
            foreach($letterIds as $letterId){
            $letterObj=new LetterService($letterId->id);
            $letterDetails[]=$letterObj->getLetter();        
        }
        }       
        
        return $letterDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }
 
}