<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\UtilityService;
use App\Services\CoupleService;
use App\Services\FilterService;
use App\Services\AlbumsService;
use App\Services\JournalService;
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
			foreach($flipbook as $flipbooks) {

				$profileDetails[]=array(
						     	"flip_book"=>$flipbooks['flipbook'],
						     	"id"=>$flipbooks['id']
						     	);	
			}
			
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

    	 	
    	
	    return json_encode($profileDetails);	    	
  	}


    /* Journals */
  	public function getJournalApi(){
  		$api=Input::segment(1);
  		if($api=='journals') {  

  			$user_name=Input::segment(2);
  			$title=urldecode(Input::segment(3));
  			if(isset($title)){
  				$profile=new UtilityService();
				$account_id=$profile->getAccountIdByUserName($user_name);
				$journals=$profile->getJournalsByTitle($account_id,$title);
  			}  				
  			else{
    			$profile=new UtilityService();
				$account_id=$profile->getAccountIdByUserName($user_name);
				$journalObj=new CoupleService($account_id);
    			$journals=$journalObj->getJournalDetails();
    			
				
  			}
  			
    		
			
    	}
    	else if($api=='journal'){	
    		$profilename=Input::segment(2);
    		$journal_id=Input::segment(3);
    		$profile=new UtilityService(null);
    		$account_id= $profile->getAccountIdByUserName($profilename);
			$journals=$profile->getJournalsById($account_id,$journal_id);
			
    	}
    	foreach($journals as $journal){
    		$journalDetails[]=array(
						     	"Caption"=>$journal->getJournalCaption(),
						     	"Text"=>$journal->getJournalText(),
						     	"Uri"=>$journal->getJournalUri(),
						     	"Photo"=>$journal->getJournalPhoto()
						     	);
    			}  

    	 return json_encode($journalDetails);
  	}
  	
  	 public function getAlbumApi(){
  	 	$user_name=Input::segment(3);
  	 	$profile=new UtilityService();
		$account_id=$profile->getAccountIdByUserName($user_name);
		$album=new CoupleService($account_id);
		$albums= $album->getAlbumDetails(); 
		foreach($albums as $album){
    				$albumDetails[]=array(
						     	"Ext"=>$album->getAlbumExt(),
						     	"Title"=>$album->getAlbumTitle(),
						     	"Hash"=>$album->getAlbumHash(),
						     	"Uri"=>$album->getAlbumUri(),
						     	"Id"=>$album->getAlbumId()
						     	);
    			}  
		return json_encode($albumDetails);
  	 } 

  	 /*  Letters  */
  	public function getLetterApi(){
  	 	$api=Input::segment(1);
  	 	$user_name=Input::segment(2);
  	 	$profile=new UtilityService();
		$account_id=$profile->getAccountIdByUserName($user_name);
		

  	 	if($api=='letters'){ 	
  	 		$letterObj=new CoupleService($account_id);
			$letters=$letterObj->getLetterDetails();

  	 	}else if($api=='letter'){
  	 		$letter_id=Input::segment(3);
  	 		$letters=$profile->getLetterById($account_id,$letter_id);
  	 		
  	 	}

  	 	foreach($letters as $letter){
    		$letterDetails[]=array(
						     	"Title"=>$letter->getTitle(),
						     	"Content"=>$letter->getContent(),
						     	"Image"=>$letter->getAssociatedImage()
						     	);
    			}  
  	 	return json_encode($letterDetails);
  	}
  	

		
}