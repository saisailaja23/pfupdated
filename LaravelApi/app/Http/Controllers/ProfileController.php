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
use App\Exceptions\ParentFinderException;
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
			$accountInfo=$parentObj->getAccountDetails();
			$contactInfo=$parentObj->getContactDetails();
			$journalDetails='';
			if($journals=$parentObj->getJournalDetails())
			{
				
				foreach($journals as $journal){
    			$journalDetails[]=array(
						     	"Caption"=>$journal->getJournalCaption(),
						     	"Text"=>$journal->getJournalText(),
						     	"Uri"=>$journal->getJournalUri(),
						     	"Photo"=>$journal->getJournalPhoto()
						     	);
    			}  
    		}			
    		$letterDetails='';
			if($letters=$parentObj->getLetterDetails())
    		foreach($letters as $letter){
    		$letterDetails[]=array(
						     	"Title"=>$letter->getTitle(),
						     	"Content"=>$letter->getContent(),
						     	"Image"=>$letter->getAssociatedImage()
						     	);
    			}
    		$AgencyDetails = $parentObj->getAgencyDetails();
    		$childpreferences=$parentObj->getChildPreferences();
			$profileDetails=Array(
								"status"=>"202",
								"data" =>array(
						     	"first_name"=>$parent1->getFirstName(),
						     	"last_name"=>$parent1->getLastName(),
						     	"dob"=>$parent1->getDob(),
						     	"gender"=>$parent1->getGender(),
						     	"ethnicity"=>$parent1->getEthnicity(),
						     	"faith"=>$parent1->getFaith(),
						     	"waiting"=>$parent1->getWaiting(),
						     	"avatar"=>$parentObj->getAvatar(),
						     	"journal"=>$journalDetails,
						     	"letter"=>$letterDetails,
						     	"childpreferences"=>$childpreferences,
						     	"agency"=>$AgencyDetails
						     	)
						     	);	
    	}
    	else if($api=='profiles'){			/*  To list all profiles */

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
    		if($accountIds){
	     		foreach($accountIds as $account_id){
	     			$parent1Details=$parent2Details='';
					$parentObj=new  CoupleService($account_id);
					$parent1 =  $parentObj->getParentprofile1();
					$parent2 =  $parentObj->getParentprofile2();
					$accountInfo=$parentObj->getAccountDetails();
					$contactDetails='';
					if($contactInfo=$parentObj->getContactDetails()){
						$contactDetails=Array(
									"country"=>$contactInfo->getCountry(),
									"state"=>$contactInfo->getState()							     	
							     	);
					}					
					
					$parent1Details=Array(
							     	"first_name"=>$parent1->getFirstName(),
							     	"last_name"=>$parent1->getLastName(),
							     	"dob"=>$parent1->getDob(),
							     	"faith"=>$parent1->getFaith(),
							     	"waiting"=>$parent1->getWaiting(),
							     	"avatar"=>$parentObj->getAvatar()
							     	
							     	);
					if(isset($parent2)){
						$parent2Details=Array(
							     	"first_name"=>$parent2->getFirstName(),
							     	"last_name"=>$parent2->getLastName(),
							     	"dob"=>$parent2->getDob(),
							     	"faith"=>$parent2->getFaith()
							     	);
					}
					if(isset($parent1) && isset($parent2)){
						$profileDetail[]=Array(	"profile"=>array(
						                                 "parent1"=>$parent1Details,
						                                  "parent2"=>$parent2Details,
						                                  "contactDetails"=>$contactDetails
						                                 )
										);
					}
					else{
						$profileDetail[]=Array("profile"=>array(
						                                 "parent1"=>$parent1Details	,
						                                 "contactDetails"=>$contactDetails				                                  
						                                 )
											);
					}

	     		}
				$profileDetails=Array("status"=>"202","profiles"=>$profileDetail);
     	}else{
     		throw new ParentFinderException('no-profiles-found');
     	}
    		
    	
    	}
    	 else if($api=='flipbook'){	
    		$profilename=Input::segment(2);
    		$profile=new UtilityService(null);
    		$account_id= $profile->getAccountIdByUserName($profilename);
    		$flipbookobj=new CoupleService($account_id);
			$flipbook= $flipbookobj->getFlipbook();
			if(!empty($flipbook)){
			foreach($flipbook as $flipbooks) {
				$profileDetails[]=array("status"=>"202",
								"data"=>array(
							     	"flip_book"=>$flipbooks->getcontent(),
							     	"id"=>$flipbooks->getId()
							     	)
						     	);	
			}
		}
		else{
			throw new ParentFinderException('flip_not_found');
		}
			
    	}
    	else if($api=='pdfprofile'){	
    		$profilename=Input::segment(2);
    		$type=Input::segment(4);
    		$profile=new UtilityService(null);
    	    $account_id= $profile->getAccountIdByUserName($profilename);
    		$pdfbookobj=new CoupleService($account_id);
		    $pdfoutput= $pdfbookobj->getPdf($type);
			/*if(!empty($pdfoutput)){*/
			foreach($pdfoutput as $pdfoutputs) {
			$profileDetails[]=array(
								"status"=>"202",
						     	"single_profile"=>$pdfoutputs->template_file_path2,
						     	"multi_profile"=>$pdfoutputs->gettemplate_file_path(),
						     	"id"=>$pdfoutputs->getId()
						     );
    	}  
   /* }
    else{
    	throw new ParentFinderException('pdf_not_found');
    }*/
    	}  	 	
    	
	    return json_encode($profileDetails);	    	
  	}


    /* Journals */
  	public function getJournalApi(){
  		$api=Input::segment(1);
  		if($api=='journals') {  

  			 $user_name=Input::segment(2);
  			 $title=urldecode(Input::segment(3));
  			if(!empty($title)){
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

   	if(!empty($journals)){
    	foreach($journals as $journal){
    		$journalDetails[]=array(
    							"status"=>"202",
						     	"Caption"=>$journal->getJournalCaption(),
						     	"Text"=>$journal->getJournalText(),
						     	"Uri"=>$journal->getJournalUri(),
						     	"Photo"=>$journal->getJournalPhoto()
						     	);
    			}  

    	 return json_encode($journalDetails);
    	}
    	else{
    		throw new ParentFinderException('journal_not_found');
    	}
  	}
  	
  	 public function getAlbumApi(){
  	 	$photoseg=Input::segment(1);
  	 	
  	 	$profile=new UtilityService();
		if($photoseg == 'photos'){
			$user_name=Input::segment(3);
	  	 	$albumid=Input::segment(4);
	  	 	$type=urldecode(Input::segment(5));
	  	 	$albumseg=Input::segment(2);
			$account_id=$profile->getAccountIdByUserName($user_name);
			$album=new CoupleService($account_id);
  	 	
			if($albumseg == 'album'){
				$albums= $album->getAlbumDetailsByAlbumId($albumid,$type); 
			}
			else if($albumseg == 'albums'){
			$albums= $album->getAlbumDetails(); 
			}
		}
		else{
		    $user_name=Input::segment(2);
			$account_id=$profile->getAccountIdByUserName($user_name);
			$photoid = Input::segment(3);
		    $albums=$profile->getPhotoById($photoid);
			//print_r($albums);
		}
		if(!empty($albums)){
		foreach($albums as $album){
    				$albumDetails[]=array(
    							"status"=>"202",
						     	"Ext"=>$album->getAlbumExt(),
						     	"Title"=>$album->getAlbumTitle(),
						     	"Hash"=>$album->getAlbumHash(),
						     	"Uri"=>$album->getAlbumUri(),
						     	"Id"=>$album->getAlbumId()
						     	);
    			}  
		return json_encode($albumDetails);
	}
	else{
		throw new ParentFinderException('album_not_found');
	}
  	 } 

  	 /*  Letters  */
  	public function getLetterApi(){
  	 	$api=Input::segment(1);
  	 	$user_name=Input::segment(2);
  	 	$profile=new UtilityService();
		if($account_id=$profile->getAccountIdByUserName($user_name))
		{
			if($api=='letters'){ 	
  	 			$letterObj=new CoupleService($account_id);
				$letters=$letterObj->getLetterDetails();

  	 	}else if($api=='letter'){
  	 		$letter_id=Input::segment(3);
  	 		$letters=$profile->getLetterById($account_id,$letter_id);
  	 		
  	 	}
  	 	if(!empty($letters)){
  	 	foreach($letters as $letter){
    		$letterDetails[]=array(
    							"status"=>"202",
						     	"Title"=>$letter->getTitle(),
						     	"Content"=>$letter->getContent(),
						     	"Image"=>$letter->getAssociatedImage()
						     	);
    			}  
  	 	return json_encode($letterDetails);
  	 }
  	 else{
  	 	throw new ParentFinderException('letter_not_found');
  	 }
		}
		


  	}

  	public function getPageNotFound(){
  		
  		$message=array( "status"=>'Failed',
  						"Message"=>getStatusCodeMessage(404));
    	return json_encode($message);

  	}

	public function getVideoApi(){

  	 	$param1=Input::segment(2);
  	 	$param2=Input::segment(3);
  	 	$param3=Input::segment(4);
  	 	$videoseg = Input::segment(1);
  	 	$profile=new UtilityService();
		if($videoseg == 'videos'){
		$account_id=$profile->getAccountIdByUserName($param2);
		if($param1=='albums'){
			$video=new CoupleService($account_id);
			$videos= $video->getVideoDetails();
		}
		else if($param1=='album'){
			if(isset($param3) && $param3=='homevideos'){
  	 		$video=new CoupleService($account_id);
			$videos= $video->getHomeVideoDetails(); 
  	 	}
  	 	else{
  	 		$video=new CoupleService($account_id);
			$videos= $video->getVideoDetailsById($param3); 

	  	 	
		}
  	 	
		}
	}
	else{

		 	$user_name=Input::segment(2);
			$account_id=$profile->getAccountIdByUserName($user_name);
			//$profile=new UtilityService();
			$videoid = Input::segment(3);
			$videos=$profile->getVideoById($videoid);
	}

  	 	if(!empty($videos)){
		foreach($videos as $videout){
    				$videoDetails[]=array(
    							"status"=>"202",
						     	"YoutubeLink"=>$videout->getVideoYoutubeLink(),
						     	"Source"=>$videout->getVideoSource(),
						     	"Uri"=>$videout->getVideoUri(),
						     	"Id"=>$videout->getVideoId()
						     	);
    			}  
		return json_encode($videoDetails);
	}
	else{
		throw new ParentFinderException('video_not_found');
	}

	}


}