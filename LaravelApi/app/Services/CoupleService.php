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
//use App\Exceptions\ParentFinderException;
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
     public function getusername() {
        return $this->username;
    }
    

    /* Get a single profiles */

    public function getParentprofile1() {

         try{

         $profileId = $this->getProfileId();

        
             $parentProfile = new ProfileService($profileId['parent1']);
             $this->parentprofile1 = $parentProfile->getProfile();
             return $this->parentprofile1;
       

         }
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
        $this->username=$accountDetails1->username;
        return $this;
    
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getContactDetails(){
        $contactDetails='';
        try{
        $contactObj=new ContactService($this->accountId);
        if($contactDetails=$contactObj->getContactDetails()){
            return $contactDetails;
        }
        else{
             throw new ParentFinderException('contact_not_found');
        }
        
    }
    catch(\Exception $e){
              throw new ParentFinderException('contact_not_found',$e->getMessage());
        } 
    } 

    public function getJournalDetails(){
        $journalDetails='';
        try{
        $journalObj=new JournalRepository(null);

        if($journalIds=$journalObj->getJournalsByAccount($this->accountId))
        {
            foreach($journalIds as $journalId){
                $journalObj=new JournalService($journalId->PostId);
                $journalDetails[]=$journalObj->getJournal();        
            }
            return $journalDetails;

        }
        else{
            throw new ParentFinderException('journal_not_found');
        }
        

    }catch(\Exception $e){
          throw new ParentFinderException('journal_not_found',$e->getMessage());
        } 
    }

     public function getAlbumDetails(){
        try{
        $albumObj=new AlbumsRepository(null);
         $albumId=$albumObj->getAlbumByID($this->accountId);
        $albumDetail=$albumObj->getAlbums($albumId,$this->accountId);

        if(count($albumDetail)>0)  { 
        foreach($albumDetail as $albumDetails){
            $albumserviceObj=new AlbumsService($albumDetails->ID);
             $albumout[]=$albumserviceObj->getAlbum();
        }     
      
        return $albumout;
    }
    else{
        throw new ParentFinderException('album_not_found');
    }
    }
        catch(\Exception $e){
             throw new ParentFinderException('album_not_found',$e->getMessage());
        } 
    }

     public function getAlbumDetailsByAlbumId($albumid,$type){
        try{
        $albumObj=new AlbumsRepository(null);
        $albumDetail=$albumObj->getAlbumsByAlbumId($albumid,$this->accountId,$type); 
       if(count($albumDetail)>0)  {
        foreach($albumDetail as $albumDetails){
            $albumserviceObj=new AlbumsService($albumDetails->ID);
             $albumout[]=$albumserviceObj->getAlbum();
        }     
      
        return $albumout;
    }
    else{
        throw new ParentFinderException('album_not_found');
    }
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getLetterDetails(){
         $letterDetails='';
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
                if(empty($albumDetail)){               
                foreach($albumDetail as $albumDetails){
                    $videoserviceObj=new VideoService($albumDetails->ID);
                    $albumout[] = $videoserviceObj->getAlbum();
                }
            return $albumout; 
             }
                else{
                   throw new ParentFinderException('album_not_found');
                }
            }catch(\Exception $e){
             throw new ParentFinderException('video_not_found',$e->getMessage());              
           }
           
        }catch(\Exception $e){
             throw new ParentFinderException('video_not_found',$e->getMessage());
        }     
    }


    public function getFlipbook(){  
        try{
        $profile=new EprofileRepository($this->accountId);  
        $flipbookId=$profile->getFlipbookId();
        if($flipbookId){
        $flipserviceObj=new EprofileService($flipbookId['id']);
        $flipbook[] = $flipserviceObj->getFlipbook();
                     
        return $flipbook;
    }
    else{
        throw new ParentFinderException('flip_not_found');
    }
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


    public function getPdf($type){
        try{
            $profile=new PdfRepository($this->accountId);  
            $pdfDetails=$profile->getPdfDetail();
            if(!empty($pdfDetails)){

                $pdfid= $pdfDetails->template_user_id;
            } 
            else{
                 $pdfid= 0;
            }
            $flipserviceObj=new PdfService($pdfid);
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
            if($preferenceDetails){
            $childpreferences['ethnicity']=$preferenceDetails->getEthnicityPref();
            $childpreferences['ageGroup']=$preferenceDetails->getAgePref();
            $childpreferences['adoption']=$preferenceDetails->getAdoptionTypePref();
            return $childpreferences;
        }
         else{
            throw new ParentFinderException('child-preference-not-found');
         }   
        } catch(\Exception $e){
                 throw new ParentFinderException('child-preference-not-found',$e->getMessage());
        }
    }

   public function getAgencyDetails(){
        try{
            $agencyObj=new AgencyService(null);
            $agency = '';
            if($agencyeDetails=$agencyObj->getAgencyDetails($this->accountId)){
            $agency['id']=$agencyeDetails->getId();
            $agency['uri']=$agencyeDetails->geturi();
            $agency['title']=$agencyeDetails->gettitle();
            $agency['country']=$agencyeDetails->getcountry();
            $agency['city']=$agencyeDetails->getcity();
            $agency['zip']=$agencyeDetails->getzip();
            $agency['website']=$agencyeDetails->getwebsite();
             return $agency;
            }
            else{
                throw new ParentFinderException('agency-not-found');
            }
          
           
            
        } catch(\Exception $e){
                 //throw new ParentFinderException('agency-not-found');
        }
    } 
 
}