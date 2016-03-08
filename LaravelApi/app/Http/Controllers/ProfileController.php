<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\UtilityService;
use App\Services\CoupleService;
use App\Services\FilterService;
use App\Services\ContactService;
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
			$accountObj=$parentObj->getAccountDetails();
			$contactInfo=$parentObj->getContactDetails();
			$profileDetails=array(
						     	"first_name"=>$parent1->getFirstName(),
						     	"last_name"=>$parent1->getLastName(),
						     	"dob"=>$parent1->getDob(),
						     	"gender"=>$parent1->getGender(),
						     	"ethnicity"=>$parent1->getEthnicity(),
						     	"faith"=>$parent1->getFaith(),
						     	"religion_id"=>$parent1->getReligionId(),
						     	"waiting"=>$parent1->getWaiting(),
						     	"avatar"=>$accountObj->getAvatar(),
						     	"country"=>$contactInfo->getCountry(),
								"state"=>$contactInfo->getState(),

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
					$accountIds= $filter->getProfilesByRegion($region);
				}
				else if($filter_tag=='kids'){
					$kids=Input::segment(3);
					$accountIds= $filter->getProfilesByKids($kids);
				}
				else if($filter_tag=='state'){
			     $state=Input::segment(3);
			     $accountIds= $filter->getProfilesByState($state);
			    } 
			   else if($filter_tag=='name'){
			     $name=Input::segment(3);
				$accountIds= $filter->getProfilesByName($name);
			    }
			   else if($filter_tag=='child-preference'){
			     $child_pref=Input::segment(3);
			     $accountIds= $filter->getProfilesChildPref($child_pref);
			   }
			   else if($filter_tag=='sort'){
			     $sort=Input::segment(3);
				$accountIds= $filter->getProfilesBySort($sort);
			    }
			}else{
			
    		$accountIds= $filter->getAllProfiles();
			}
    		
     		foreach($accountIds as $account_id){
     			$parent1Details=$parent2Details='';
				$parentObj=new  CoupleService($account_id);
				$parent1 =  $parentObj->getParentprofile1();
				$parent2 =  $parentObj->getParentprofile2();
				$contactInfo=$parentObj->getContactDetails();
				$parent1Details=array(
						     	"first_name"=>$parent1->getFirstName(),
						     	"last_name"=>$parent1->getLastName(),
						     	"dob"=>$parent1->getDob(),
						     	"faith"=>$parent1->getFaith(),
						     	"waiting"=>$parent1->getWaiting(),
								"country"=>$contactInfo->getCountry(),
								"state"=>$contactInfo->getState(),
						     	"avatar"=>$parentObj->getAvatar()
						     	
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
    	 else if($api=='flipbook'){	
    		$profilename=Input::segment(2);
    		$profile=new UtilityService(null);
    		$acc_id= $profile->getAccountIdByUserName($profilename);
			$flipbook= $profile->getFlipbookByID($acc_id);
			$profileDetails[]=array(
						     	"flip_book"=>$flipbook
						     	);	
    	}
    	else if($api=='pdfprofile'){	
    		$profilename=Input::segment(2);
    		$type=Input::segment(4);
    		$profile=new UtilityService(null);
    		$acc_id= $profile->getAccountIdByUserName($profilename);
			$pdfoutput= $profile->getPdf($acc_id,$type);
			$profileDetails[]=array(
						     	"pdf_output"=>$pdfoutput
						     	);
    	}
    	else if($api=='journal'){	
    		$profilename=Input::segment(2);
    		$jid=Input::segment(4);
    		$profile=new UtilityService(null);
    		$account_id= $profile->getAccountIdByUserName($profilename);
			$pdfoutput= $profile->getPdf($account_id,$type);
			$profileDetails[]=array(
						     	"pdf_output"=>$pdfoutput
						     	);
    	}  
    	else if($api=='journals') {
    		$profilename=Input::segment(2);
    		$journalObj=new UtilityService(null);
    		$account_id= $journalObj->getAccountIdByUserName($profilename);
			$journals= $journals->getJournals($account_id);
			$profileDetails[]=array(
						     	"journals"=>$journals
						     	);
    	}
	    return json_encode($profileDetails);	    	
  	}
  	
  	
		
}