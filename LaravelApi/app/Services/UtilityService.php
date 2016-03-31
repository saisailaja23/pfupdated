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
/**
 * Description of AccountService
**/
class UtilityService {

    public function getAccountIdByUserName($user_name){
        try{
            $profileObj=new ProfileRepository(null);           
            $accountId=$profileObj->getAccountIdByUserName($user_name);
            return $accountId->account_id;
            
        }catch(\Exception $e){
             throw new ParentFinderException('user_not_found',$e->getMessage());
        } 
    
   } 
  
	
	public function getFlipbookByID($acc_id){  
        try{
        $profile=new ProfileRepository(null);  
        $flipbookDetails=$profile->getFlipbook($acc_id);
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
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    	public function getPdf($acc_id,$type){
        try{
        $profile=new ProfileRepository(null);  
        $pdfDetails=$profile->getPdfDetails($acc_id);
        $pdf =   $pdfDetails->template_file_path;
        $path_parts = explode('/', $pdf);
        $pdf_output =  $path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
        
        return $pdfDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

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

    public function getJournalsById($account_id,$journal_id){
      try{
      $journalObj=new JournalRepository(null);
      $journalIds=$journalObj->getJournalsById($account_id,$journal_id);
      if(empty($journalIds)){
      }
      else{
      }
      foreach($journalIds as $journalId){
          $journalObj=new JournalService($journalId->PostId);
          $journalDetails[]=$journalObj->getJournal();        
      }
      return $journalDetails;
  }catch(\Exception $e){
             //Add Exception here
        } 
    }


    public  function getEthnictyDetails($ethnicityId){
        try{
        $ethnicity=new EthnicityRepository($ethnicityId);  
        $ethnicityDetails=$ethnicity->getEthnictyDetails();
        $ethnicityVal=$ethnicityDetails->ethnicity;
        return $ethnicityVal;
        }
        catch(\Exception $e){
             //Add Exception here
        }        
    }  

    public function getLetterById($account_id,$letter_id){
        try{
        $letterObj=new LetterRepository(null);
        $letterIds=$letterObj->getLettersById($account_id,$letter_id);
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

    public function checkAppKey($key,$url){
      $appObj=new AppUserRepository($url);
      $keyIdentity=$appObj->getAppUserKey($key);
      return $keyIdentity;
    }

    public function getUsernameByAccountId($account_id){
      try{
            $profileObj=new ProfileRepository(null);           
            $accountId=$profileObj->getUsernameByAccountId($account_id);
            return $accountId->account_id;
            
        }catch(\Exception $e){
             throw new ParentFinderException('user_not_found',$e->getMessage());
        } 
    
    }
     
    
}