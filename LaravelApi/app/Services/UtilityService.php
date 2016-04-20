<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\PdfTemplate;
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
use App\Repository\JournalRepository;
use App\Repository\AlbumsRepository;
use App\Repository\LetterRepository;
use App\Exceptions\ParentFinderException;
use App\Repository\AppUserRepository;
use App\Repository\ProfileTypeRepository;
use App\Repository\AccountRepository;
/**
 * Description of AccountService
**/
class UtilityService {


    /*Get Account By Username*/
    public function getAccountIdByUserName($user_name){
        try{
            $profileObj=new ProfileRepository(null);           
            $accountId=$profileObj->getAccountIdByUserName($user_name);
            if(count($accountId)>0){
              return $accountId->account_id;
            }
            else{
             throw new ParentFinderException('user_not_found',$e->getMessage());
            }
            
            
        }catch(\Exception $e){
             throw new ParentFinderException('user_not_found',$e->getMessage());
        } 
    
   } 
  
	/*Get FlipBook*/
	public function getFlipbookByID($acc_id){  
        try{
        $profile=new ProfileRepository(null);  
        $flipbookDetails=$profile->getFlipbook($acc_id);
        if(!empty($flipbookDetails))
        {
           $flipbooks =   $flipbookDetails->content;
        $start = strpos($flipbooks, ".com/") + 5;
        $end = strpos($flipbooks, ".html") - $start + 5;
        $flipbook = substr($flipbooks, $start, $end);
        $flipDetails[]=array(
                                "flipbook"=>$flipbook,
                                "id"=>$flipbookDetails->id
                                );
        return $flipDetails;
        }
        else{

          throw new ParentFinderException('flip_not_found');
        }
       
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

      /*Get Pdf*/
    	public function getPdf($acc_id,$type){
        try{
        $profile=new ProfileRepository(null);  
        $pdfDetails=$profile->getPdfDetails($acc_id);
        if(!empty($pdfDetails)){
            $pdf =   $pdfDetails->template_file_path;
        $path_parts = explode('/', $pdf);
        $pdf_output =  $path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
        
        return $pdfDetails;
        }
        else{
          throw new ParentFinderException('pdf_not_found');
        }
      
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }


    /*Get Journal By Tittle*/
    public function getJournalsByTitle($account_id,$title){
        try{
        $journalObj=new JournalRepository(null);
        if($journalIds=$journalObj->getJournalsByTitle($account_id,$title)){
        foreach($journalIds as $journalId){
            $journalObj=new JournalService($journalId->PostId);
            $journalDetails[]=$journalObj->getJournal();        
        }
        return $journalDetails;
      }
       else{
         throw new ParentFinderException('journal_not_found');
    }
    }
    catch(\Exception $e){
              throw new ParentFinderException('journal_not_found');
        } 
    }


    /*Get Journal ById*/
    public function getJournalsById($account_id,$journal_id){
      try{
      $journalObj=new JournalRepository(null);
      $journalIds=$journalObj->getJournalsById($account_id,$journal_id);
      if(!empty($journalIds)){
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
             //Add Exception here
        } 
    }


    /*Get Ethnicity Details*/
    public  function getEthnictyDetails($ethnicityId){
        try{
        $ethnicity=new EthnicityRepository($ethnicityId);  
        $ethnicityDetails=$ethnicity->getEthnictyDetails();
        if($ethnicityDetails){
        $ethnicityVal=$ethnicityDetails->ethnicity;
        return $ethnicityVal;
      }
      else{
        throw new ParentFinderException('ethnicity-prefer-not-found');
      }
        }
        catch(\Exception $e){
             //Add Exception here
        }        
    }  


    /*Get Letter ById*/
    public function getLetterById($account_id,$letter_id){
        try{
        $letterObj=new LetterRepository(null);
        $letterIds=$letterObj->getLettersById($account_id,$letter_id);
        if(count($letterIds)>0){
        foreach($letterIds as $letterId){
            $letterObj=new LetterService($letterId->id);
            $letterDetails[]=$letterObj->getLetter();        
        }
        return $letterDetails;
      }
      else{
        throw new ParentFinderException('letter_not_found');
      }
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    } 


     /*Get Photo ById*/
     public function getPhotoById($photoid){
        try{
        $albumservice=new AlbumsService($photoid);
              $albumout[]=$albumservice->getAlbum();
             //print_r($albumout);
        return $albumout;
       }
       catch(\Exception $e){
             //Add Exception here
        } 
      }
     
    /*Get Video ById*/
    public function getVideoById($videoid){
        try{
        $albumservice=new VideoService($videoid);
              $albumout[]=$albumservice->getAlbum();
             //print_r($albumout);
        return $albumout;
      }
      catch(\Exception $e){
               //Add Exception here
      } 
    }


    /*Check AppKey*/
    public function checkAppKey($key,$url){
      $appObj=new AppUserRepository($url);
      $keyIdentity=$appObj->getAppUserKey($key);
      return $keyIdentity;
    }


    /*Get UserName By Account Id*/
    public function getUsernameByAccountId($account_id){
      try{
            $profileObj=new ProfileRepository(null);           
            $accountId=$profileObj->getUsernameByAccountId($account_id);
            return $accountId->account_id;
            
        }catch(\Exception $e){
             throw new ParentFinderException('user_not_found',$e->getMessage());

        } 
    
    }


    /*Get ProfileTypes*/
    public function getProfileTypes(){
      try{
      $profiletypeobj=new ProfileTypeRepository(null);           
      $profiletype=$profiletypeobj->getProfileTypes();
      if(count($profiletype) > 0){
        return  $profiletype;
      }
      else{
        throw new ParentFinderException('No_profile_type');
        
      }
       }catch(\Exception $e){
             

        } 
    }
    /* Get country details */
    public function getCountry(){
      try{
        
            $country=new CountryRepository(null);  
            $countryDetails=$country->getCountrysDetails();  

            if(count($countryDetails)!=0)
            {
             return $countryDetails;
            }else{
              throw new ParentFinderException('countries_not_found',$e->getMessage());
            }
            

          }
    catch(\Exception $e){
     
          //throwing default exceptions
        } 
    }
    /* Get State BYId*/
    public function getStatesByCountryId($country_id)
    {
 
      try{
       
       $state=new StateRepository(null);
       $stateDetails=$state->getStatesDetails($country_id);
       if(count($stateDetails)!=0)
       {
        return $stateDetails;
       }
       else
       {
         throw new ParentFinderException('state_country_not_found',$e->getMessage());
       }
      }
      catch(\Exception $e)
      {
      //throwing default exceptions
      } 

    }
    /* Get Region Details*/
    public function getRegionDetails()
    {
  
     try {
       $region=new RegionRepository(null);
       $regionDetails=$region->getRegionByStateId();
       if(count($regionDetails)!=0)
       {
        return $regionDetails;
       }
       else
       {
         throw new ParentFinderException('region-not-found',$e->getMessage());
       }
      } catch (\Exception $e) {
            //throwing default exceptions
         }
    } 
    /*Get Email By AccountId */
    public function getEmailById($account_id)
    {
      try{
       $email=new AccountRepository($account_id);
       $accountDetails=$email->getAccountDetails();
      return $accountDetails;
       } catch (\Exception $e) {
            //throwing default exceptions
         }
     
   }
    /* Get Family Status single or couple*/
    public function getFamilystatus($account_id){
      try
      {
       $profile=new ProfileRepository(null);  
        $countAccount=$profile->getFStatus($account_id);
        return $countAccount;
      }

     catch (\Exception $e) {
            //throwing default exceptions
         }
    
    }

    /* 
      *   Edit Child Details 
        
        *   @param  Request $request
        *   @return boolean $insertStatus

    */
    public function editChild($data){
      try
      {

       $child=new ChildRepository(null);  
       $child->setType($data['type']);
       $child->setNoOfChildren($data['NoOfChildren']);
       $child->setAccountId($data['accounts_id']);
       $status=$child->updateChild();
        return $status;
      }

     catch (\Exception $e) {
            //throwing default exceptions
         }
    
    }
    

    
}