<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\UtilityService;
use App\Services\CoupleService;
use App\Services\FilterService;
use Response;
use Illuminate\Support\Facades\Input;
class ProfileController extends Controller
{
   
   /* Single Profile Api */
   public function getProfileApi() 
    {
    	
    	$api=Input::segment(1);
    	if($api=='profile'){			/*To get a single profile */
    		$user_name=Input::segment(2);
			$profile=new UtilityService();
			$account_id=$profile->getAccountIdByUserName($user_name);
			$parentObj=new  CoupleService($account_id);
			$parent1 =  $parentObj->getParentprofile1();
			$parent2 =  $parentObj->getParentprofile2();

			$profileDetails=array(
						     	"first_name"=>$parent1->getFirstName(),
						     	"last_name"=>$parent1->getLastName(),
						     	"dob"=>$parent1->getDob(),
						     	"gender"=>$parent1->getGender(),
						     	"ethnicity"=>$parent1->getEthnicity(),
						     	"faith"=>$parent1->getFaith(),
						     	"religion_id"=>$parent1->getReligionId(),
						     	"waiting"=>$parent1->getWaiting(),
						     	"avatar"=>$parent1->getAvatar(),

						     	);	
    	}
    	else if($api=='profiles'){			/* To list all profiles */

			$filter=new FilterService();
    		$filter_tag=Input::segment(2);
			if(isset($filter_tag)){
				if($filter_tag=='religion'){
					$religion=Input::segment(3);
					$accountIds= $filter->getProfilesByReligion($religion);
				}
				else if($filter_tag=='region'){
					$region=Input::segment(3);
					$profiles= $filter->getProfilesByRegion($region);
					$profileIds=$filter->getProfileIds();
				}
				else if($filter_tag=='kids'){
					$kids=Input::segment(3);
					$profiles= $filter->getProfilesByKids($kids);
					$profileIds=$filter->getProfileIds();
				}
				else if($filter_tag=='state'){
			     $state=Input::segment(3);
			     $profiles= $filter->getProfilesByState($state);
			     $profileIds=$filter->getProfileIds();
			    }
			}else{
			
    		$accountIds= $filter->getAllProfiles();
			}
    		
     		foreach($accountIds as $account_id){
     			$parent1Details=$parent2Details='';
				$parentObj=new  CoupleService($account_id);
				$parent1 =  $parentObj->getParentprofile1();
				$parent2 =  $parentObj->getParentprofile2();
				$parent1Details=array(
						     	"first_name"=>$parent1->getFirstName(),
						     	"last_name"=>$parent1->getLastName(),
						     	"dob"=>$parent1->getDob(),
						     	"faith"=>$parent1->getFaith(),
						     	"waiting"=>$parent1->getWaiting(),
								"country"=>$parent1->getCountry(),
								"state"=>$parent1->getState(),
						     	"avatar"=>$parent1->getAvatar()
						     	
						     	);
				if(isset($parent2)){
					$parent2Details=array(
						     	"first_name"=>$parent2->getFirstName(),
						     	"last_name"=>$parent2->getLastName(),
						     	"dob"=>$parent2->getDob(),
						     	"faith"=>$parent2->getFaith()
						     	);
				}
				if(isset($parent1) && isset($parent2)){
					$profileDetails[]=Array("profile"=>array(
					                                 "parent1"=>$parent1Details,
					                                  "parent2"=>$parent2Details
					                                 )
							);
				}
				else{
					$profileDetails[]=Array("profile"=>array(
					                                 "parent1"=>$parent1Details					                                  
					                                 )
							);
				}

     		}
    		
    	
    	}
    	    
	    return json_encode($profileDetails);	    	
  	}
  	
  	
		
}