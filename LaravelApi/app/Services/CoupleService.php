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
    private $parentprofile1;
    private $parentprofile2;
   
		
    public function __construct($accountId) {
       $this->setAccountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccountId($accountId) {
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

    /* Get Second profiles */
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

    /* Get profiles Id*/
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

    /* Get Account Details*/
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

    /* Get Contact Details */
    public function getContactDetails(){
        $contactDetails='';
        try{
        $contactObj=new ContactService($this->accountId);
        if(count($contactDetails!=0)){
            
           $contactDetails=$contactObj->getContactDetails(); 
            return $contactDetails;
        }
        else{
            throw new ParentFinderException('contact_not_found');
        }
        
    }
    catch(\Exception $e){
            // throw new ParentFinderException('contact_not_found',$e->getMessage());
        } 
    } 

    /* Get Journal Details */
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

    /* Get Album Details */
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

    /* Get Album Details By AlbumId */
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

    /* Get letter Details */
    public function getLetterDetails(){
         $letterDetails='';
        try{
        $letterObj=new LetterRepository(null);
        //$letterIds=$letterObj->getSortedLetters($this->accountId);

            $letterIds=$letterObj->getLettersByAccount($this->accountId);
            foreach($letterIds as $letterId){
            $letterObj=new LetterService($letterId->id);
            $letterDetails[]=$letterObj->getLetter();        
        }       
        
        return $letterDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


/* Get Slug details*/

    public function getSeoDetails($slug,$type){
        if($type=='letter'){
             $letterDetails='';
             try{
             $letterObj=new LetterRepository(null);
             $letterIds=$letterObj->getSeo($slug);
             if(count($letterIds)){
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
        else
        {
         $journalDetails='';
          try{
          $journalObj=new JournalRepository(null);
          $journalIds=$journalObj->getJournalSeo($slug);
          if(count($journalIds)){
                foreach($journalIds as $journalId){
                 $journalObj=new JournalService($journalId->PostID);
                $journalDetails[]=$journalObj->getJournal();        
                 }
            }
                 return $journalDetails;
            }catch(\Exception $e){
               // throw new ParentFinderException('journal_not_found',$e->getMessage());
            } 
        }

    }




    /* Get Video Details */

    public function getVideoDetails(){
        $albumout = '';
         try{
        $albumObj=new VideoRepository(null);
         $albumId=$albumObj->getVideoAlbumByID($this->accountId);
       /* $albumDetail=$albumObj->getVideoAlbums($albumId,$this->accountId);   
        foreach($albumDetail as $albumDetails){
            $videoserviceObj=new VideoService($albumDetails->ID);
             $albumout[] = $videoserviceObj->getAlbum();
        }    */ 
      
        return $albumId;
    }
        catch(\Exception $e){
             //throw new ParentFinderException('album_not_found',$e->getMessage());
        } 

    }

    /* Get HomeVideo Details */
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


    /* Get Video Details ById*/
    public function getVideoDetailsById($videoid){
        try{
            $albumObj=new VideoRepository(null);
            $albumId[]=$videoid;
            try{
                $albumDetail=$albumObj->getVideoAlbums($albumId,$this->accountId);
                if(!empty($albumDetail)){ 
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

    /* Get FlipBook Details */
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
    /* Get Epub Details */
     public function getEpub()
     {
        try {
        $profile=new EprofileRepository($this->accountId);  
        $Epub=$profile->getEpubDetails();
        if(!empty($Epub))
        {
         return $Epub;    
        }
        
        } catch (Exception $e) {
            //Add Exception here
        }
    
     }

    /* Get Pdf Profile */
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

    /* Get ChildPreference Details */
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

    /* Get Agency Details */
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

    /*  
        *   Save Parent Registration 
        *   Saving account details, profile details and contact details
        *   Adoptive family=2,Birth Mother=4 , Adoption Agency=8
        *   @param  Request $request
        *   @return boolean $insertStatus
    *       
    */
    public function saveParentProfile($data){
        try{
            $accountObj=new AccountRepository(null);
            //check for unique username
            $sSalt = genRndSalt();
            $password = encryptUserPwd($data['password'], $sSalt);
            $accountObj->setUserName($data['username']);
            $accountObj->setPassword($password);
            $accountObj->setEmailId($data['emailId']);
            $accountObj->setAgencyId($data['agencyId']);          
            $accountObj->setCreatedAt(getCurrentDateTime());
            $accountObj->setModifiedAt(getCurrentDateTime());
            $accountObj->setStatus(1);    echo "a";
            $accountObj->setRoleId($data['profileType']); 
            if($data['profileType']==2){
                if($data['maritalStatus']=='single)'){
                    echo "ok";
                    $accountObj->setName($data['firstName']);
                    $accountId=$accountObj->saveAccountDetails();
                    $profileObj=new ProfileService();
                    $profileObj->setAccountId($accountId);
                    $profileObj->setFirstName($data['firstName']);
                    $profileObj->setLastName($data['lastName']);
                    $profileObj->setGender($data['gender']);
                    $profileObj->setCreatedAt(getCurrentDateTime());
                    $profileObj->setModifiedAt(getCurrentDateTime());
                    $insertStatus1=$profileObj->saveProfile();
                }else{
                    $accountObj->setName($data['firstName1']);
                    $accountId=$accountObj->saveAccountDetails();
                    $profileObj=new ProfileService();
                    $profileObj->setAccountId($accountId);
                    $profileObj->setFirstName($data['firstName1']);
                    $profileObj->setLastName($data['lastName1']);
                    $profileObj->setGender($data['gender1']);
                    $profileObj->setCreatedAt(getCurrentDateTime());
                    $profileObj->setModifiedAt(getCurrentDateTime());
                    $insertStatus1=$profileObj->saveProfile();
                    if($insertStatus1){ 
                        $profileObj->setFirstName($data['firstName2']);
                        $profileObj->setLastName($data['lastName2']);
                        $profileObj->setGender($data['gender2']);
                        $insertStatus=$profileObj->saveProfile();
                    }
                }
                
                
                
            }else if($profileType==4){
                    
                    $data['firstNameSingle']=$request->PFfirstNameSingle;
                    $accountObj->setName($data['firstNameSingle']);
                    $accountId=$accountObj->saveAccountDetails();
                    $profileObj=new ProfileService();
                    $profileObj->setAccountId($accountId);
                    $profileObj->setFirstName($data['firstNameSingle']);
                    $profileObj->setCreatedAt(getCurrentDateTime());
                    $profileObj->setModifiedAt(getCurrentDateTime());
                    $insertStatus=$profileObj->saveProfile();
            }
            else{        /* Agency Registration*/
                    $accountId=$accountObj->saveAccountDetails();
                    $profileObj=new ProfileService();
                    $profileObj->setAccountId($accountId);                    
                    $profileObj->setCreatedAt(getCurrentDateTime());
                    $profileObj->setModifiedAt(getCurrentDateTime());
                   
                    $insertStatus=$profileObj->saveProfile();
                    if($insertStatus){       /* Insert Agency Informations */
                        $contactObj=new ContactService($accountId);
                        $contactObj->setStateId($data['state']);
                        $contactObj->setRegionId($data['region']);
                        $status=$contactObj->saveContactDetails();
                        return $status;
                    }
            }
            
        }catch(\Exception $e){
                //Throwing  exceptions if any
        }
     } 
    
}