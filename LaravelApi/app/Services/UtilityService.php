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
/**
 * Description of AccountService
**/
class UtilityService {

    public function getAccountIdByUserName($user_name){
    $profileObj=new ProfileRepository(null);
    $accountId=$profileObj->getAccountIdByUserName($user_name);
    return $accountId->account_id;
   }
    
  
	/*Get Contact details */
	public function getContactDetails(){
		$contacts=new ContactRepository($this->profileId);
		$contactDetails=$contacts->getContactDetails();
		foreach ($contactDetails as $contact) {
            $this->country=$contact->country;
			$this->state=$contact->state;
        }
        return $this;
	}
	
	public function getFlipbookByID($acc_id){  
        $profile=new ProfileRepository(null);  
        $flipbookDetails=$profile->getFlipbook($acc_id);
        $flipbooks =   $flipbookDetails->content;
        $start = strpos($flipbooks, ".com/") + 5;
        $end = strpos($flipbooks, ".html") - $start + 5;
        $flipbook = substr($flipbooks, $start, $end);
        $this->country=$flipbook;
        $this->id=$flipbookDetails->id;
        return $this;
    }

    	public function getPdf($acc_id){  
        $profile=new ProfileRepository(null);  
        $pdfDetails=$profile->getPdfDetails($acc_id);
        $pdf =   $pdfDetails->template_file_path;
        $path_parts = explode('/', $pdf);
        $pdf_output =  $path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
        $this->pdf_output=$pdf_output;
        $this->id=$pdfDetails->template_user_id;
        return $this;
    }
}