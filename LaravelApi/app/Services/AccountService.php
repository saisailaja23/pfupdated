<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
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
class AccountService {

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
	
	
}