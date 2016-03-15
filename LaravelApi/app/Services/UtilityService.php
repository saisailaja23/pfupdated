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
/**
 * Description of AccountService
**/
class UtilityService {

    public function getAccountIdByUserName($user_name){
    $profileObj=new ProfileRepository(null);
    if($accountId=$profileObj->getAccountIdByUserName($user_name)){
        return $accountId->account_id;
    }else{

    }
    
   }    
  
	// /*Get Contact details */
	// public function getContactDetails(){
	// 	$contacts=new ContactRepository($this->profileId);
	// 	$contactDetails=$contacts->getContactDetails();
	// 	foreach ($contactDetails as $contact) {
 //            $this->country=$contact->country;
	// 		$this->state=$contact->state;
 //        }
 //        return $this;
	// }
	
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
        if($type == 'single_profile'){
             $pdfDetails=array(
                                "single_profile"=>"ProfilebuilderComponent/pdf.php?id=".$acc_id
                                );
        }
        else  if($type == 'multi_profile'){
             $pdfDetails=array(
                                "multiprofile"=>$pdf_output,
                                "id"=>$pdfDetails->template_user_id
                                );
            
        }
        else{
            $pdfDetails=array(
                                "multiprofile"=>$pdf_output,
                                "id"=>$pdfDetails->template_user_id,
                                "single_profile"=>"ProfilebuilderComponent/pdf.php?id=".$acc_id
                                );
            
        }
        return $pdfDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getJournalsByTitle($account_id,$title){
        try{
        $journalObj=new JournalRepository(null);
        $journalIds=$journalObj->getJournalsByTitle($account_id,$title);
        foreach($journalIds as $journalId){
            $journalObj=new JournalService($journalId->PostId);
            $journalDetails[]=$journalObj->getJournal();        
        }
        return $journalDetails;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getJournalsById($account_id,$journal_id){
      try{
      $journalObj=new JournalRepository(null);
      $journalIds=$journalObj->getJournalsById($account_id,$journal_id);
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
     
    
}