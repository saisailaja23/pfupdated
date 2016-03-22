<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Repository\ProfileRepository;
use App\Repository\AccountRepository;
use App\Services\ProfileService;
use App\Services\EprofileService;
use App\Services\ChildPreferService;
use App\Services\AgencyService;
use App\Repository\JournalRepository;
use App\Repository\AlbumsRepository;
use App\Repository\LetterRepository;
use App\Exceptions\ParentFinderException;
use App\Repository\VideoRepository;
use App\Repository\EprofileRepository;
use App\Repository\PdfRepository;

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
          throw new ParentFinderException('user_not_found',$e->getMessage());

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
          throw new ParentFinderException('journal_not_found',$e->getMessage());
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
             throw new ParentFinderException('album_not_found',$e->getMessage());
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


    public function getVideoDetails(){
        $albumout = '';
         try{
        $albumObj=new VideoRepository(null);
         $albumId=$albumObj->getVideoAlbumByID($this->accountId);
        $albumDetail=$albumObj->getVideoAlbums($albumId,$this->accountId);   
        foreach($albumDetail as $albumDetails){
            $videoserviceObj=new VideoService($albumDetails->ID);
             $albumout[] = $videoserviceObj->getAlbum();
        }     
      
        return $albumout;
    }
        catch(\Exception $e){
             //throw new ParentFinderException('album_not_found',$e->getMessage());
        } 

    }

    public function getHomeVideoDetails(){
        try{
            $albumObj=new VideoRepository(null);
            $albumId=$albumObj->getHomeVideos($this->accountId);

            try{
                $albumDetail=$albumObj->getVideoAlbums($albumId,$this->accountId);

                foreach($albumDetail as $albumDetails){
                    $videoserviceObj=new VideoService($albumDetails->ID);
                    $albumout[] = $videoserviceObj->getAlbum();
                }
            return $albumout; 
            }catch(\Exception $e){
             throw new ParentFinderException('album_not_found',$e->getMessage());              
           }
           
        }catch(\Exception $e){
             throw new ParentFinderException('video_not_found',$e->getMessage());
        }     
    }


    public function getVideoDetailsById($videoid){
        try{
            $albumObj=new VideoRepository(null);
            $albumId[]=$videoid;
            try{
                $albumDetail=$albumObj->getVideoAlbums($albumId,$this->accountId);

                foreach($albumDetail as $albumDetails){
                    $videoserviceObj=new VideoService($albumDetails->ID);
                    $albumout[] = $videoserviceObj->getAlbum();
                }
            return $albumout; 
            }catch(\Exception $e){
             throw new ParentFinderException('video_not_found',$e->getMessage());              
           }
           
        }catch(\Exception $e){
             throw new ParentFinderException('video_not_found',$e->getMessage());
        }     
    }


    public function getFlipbook(){  
        //echo $this->accountId;
        try{
        $profile=new EprofileRepository($this->accountId);  
        $flipbookId=$profile->getFlipbookId();
        $flipserviceObj=new EprofileService($flipbookId['id']);
        $flipbook[] = $flipserviceObj->getFlipbook();
                     
        return $flipbook;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


    public function getPdf($type){
        try{
            $profile=new PdfRepository($this->accountId);  
            $pdfDetails=$profile->getPdfDetail();
            $flipserviceObj=new PdfService($pdfDetails->template_user_id);
            $flipbook[] = $flipserviceObj->getPdfDetails($type,$this->accountId);        
            return $flipbook;
        }
        catch(\Exception $e){
                 //Add Exception here
        } 
    }

    public function getChildPreferences(){
        try{
            $preferences=new ChildPreferService($this->accountId);
            $preferenceDetails=$preferences->getChildPreferDetails();
            $childpreferences['ethnicity']=$preferenceDetails->getEthnicityPref();
            $childpreferences['ageGroup']=$preferenceDetails->getAgePref();
            $childpreferences['adoption']=$preferenceDetails->getAdoptionTypePref();
            return $childpreferences;
            
        } catch(\Exception $e){
                 throw new ParentFinderException('child-preference-not-found',$e->getMessage());
        }
    }

   public function getAgencyDetails(){
        try{
            $agency=new AgencyService(null);
            $agencyeDetails=$agency->getAgencyDetails($this->accountId);
             $agency = '';
           // print_r($agencyeDetails);
            $agency['id']=$agencyeDetails->getId();
            $agency['uri']=$agencyeDetails->geturi();
            $agency['title']=$agencyeDetails->gettitle();
            //print_r($agency);
            return $agency;
            
        } catch(\Exception $e){
                 throw new ParentFinderException('child-preference-not-found',$e->getMessage());
        }
    } 
 
}