<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\UtilityService;
use App\Services\CoupleService;
use App\Services\FilterService;
use App\Services\AlbumsService;
use App\Services\JournalService;
use App\Services\MembershipService;
use App\Services\VoucherService;
use App\Services\UserMembershipService;
use App\Services\ProfileService;
use App\Services\ContactService;
use App\Services\LetterService;
use App\Services\PdfService;
use Response;
use Illuminate\Support\Facades\Input;
use App\Exceptions\ParentFinderException;
class ProfileController extends Controller
{
    /**
     * List of the apis for Parent Finder.
     * Get and post apis 
     */
   
   public function getProfileApi() 
    {
    	
    	$api=Input::segment(1);
    	if($api=='profile'){			/*To get a single profile */
    		$user_name=Input::segment(2);
			$profile=new UtilityService();
			$account_id=$profile->getAccountIdByUserName($user_name);
			$parentObj=new CoupleService($account_id);
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
			    else if($filter_tag=='country'){
			    $country=Input::segment(3);
				$accountIds= $filter->getProfilesByCountry($country);
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
							     	"avatar"=>$parentObj->getAvatar(),
							     	"username"=>$parentObj->getusername()
							     	
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
				$profileDetails=Array("status"=>"200","profiles"=>$profileDetail);
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
				$profileDetails[]=array("status"=>"200",
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
			foreach($pdfoutput as $pdfoutputs) {
				$profileDetails[]=array(
									"status"=>"200",
							     	"single_profile"=>$pdfoutputs->template_file_path2,
							     	"multi_profile"=>$pdfoutputs->gettemplate_file_path(),
							     	"id"=>$pdfoutputs->getId()
							     );
	    	}  
   
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
    							"status"=>"200",
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
  	
  	/* Photo Album */
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
		}
		if(!empty($albums)){
		foreach($albums as $album){
    				$albumDetails[]=array(
    							"status"=>"200",
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
    							"status"=>"200",
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



/* Get SEO 

  	*/
  	public function getSeoApi(){
  		$api=Input::segment(1);
		$slug=Input::segment(2);

		if($api=='letter'){
  	 		
	  	 	$letterObj=new CoupleService($slug);
			$letters=$letterObj->getSeoDetails($slug,'letter');
	  	 	if(!empty($letters)){
		  	 	foreach($letters as $letter){
		    		$letterDetails[]=array(
		    							"status"=>"200",
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

        else if($api=='journal')
          {

	            $journalObj=new CoupleService($slug);
    			$journals=$journalObj->getSeoDetails($slug,'journal');
                 if(!empty($journals)){
    	         foreach($journals as $journal){
    		     $journalDetails[]=array(
    							"status"=>"200",
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
    }


  	
/* Page Not Found */

  	public function getPageNotFound(){
  		
  		$message=array( "status"=>'Failed',
  						"Message"=>getStatusCodeMessage(404));
    	return json_encode($message);

  	}

  	/* Video Api */
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
    							"status"=>"200",
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

	/* Membership */
	public function getMembershipApi(){
		$Membership_details1 = '';
		$filter=new FilterService();
		$membershipids= $filter->getAllMembershipIds();
		if($membershipids){
	    foreach($membershipids as $membershipid){
		$membershipobj=new MembershipService($membershipid);
		$Memberships= $membershipobj->getMembershipDetails();
		
    				$Membership_details1[]=array(
						     	"id"=>$Memberships->getId(),
						     	"name"=>$Memberships->getname(),
						     	"icon"=>$Memberships->geticon(),
						     	"description"=>$Memberships->getdescription(),
						     	"active"=>$Memberships->getactive(),
						     	"purchasable"=>$Memberships->getpurchasable(),
						     	"removable"=>$Memberships->getremovable(),
						     	"order"=>$Memberships->getorder(),
						     	"free"=>$Memberships->getfree(),
						     	"trial"=>$Memberships->gettrial(),
						     	"trial_length"=>$Memberships->gettrial_length(),
						     	"membership_period"=>$Memberships->getmembershipamount(),
						     	"membership_amount"=>$Memberships->getmembershipperiod()
						     	);
			    				
				}
				$Membership_details=Array("status"=>"200","Membership_details"=>$Membership_details1);
				return json_encode($Membership_details);
			}

			else{
				throw new ParentFinderException('membership_not_found');
			}
		}

    /* Listing country,state,region  */

    public function getLocationApi(){

        $api=Input::segment(1);
        $countrysdetails = "";
        if($api=='country')
        {
          $countryObj=new UtilityService;
          if($countrys=$countryObj->getCountry())
          {
           
          	$countrysdetails=array("status"=>"200",
          							"Country Details"=>$countrys);
            return json_encode($countrysdetails);
           }
           else
           {
           	throw new ParentFinderException('countries-not-found');
           }
        }

        else if($api=='state')
          {
           $stateDetails="";
           $country_id=Input::segment(2);
       	   $stateObj=new UtilityService;
       	   if($states=$stateObj->getStatesByCountryId($country_id))
           {
       	   $stateDetails=Array("status"=>"200","State Details"=>$states);
           return json_encode($stateDetails);  
           }
          else
          {
             throw new ParentFinderException('state_country_not_found');

          } 
       	
       }

       else if($api=='region')
        {
        	$regionObj=new UtilityService;
        	$region=$regionObj->getRegionDetails();
        	if($region!="")
        	{
        	$regionDetail=Array("status"=>"200","Region Details"=>$region);
        	return json_encode($regionDetail);
           }
           else
           {
           	 throw new ParentFinderException('region-not-found');
           }

        }


    }

	/*  *	MembershipDetails 
		* 	@param  Request $request
     	* 	@return array
		*/
	public function postMembershipDetails(Request $request){
		$member_id=verifyData($request->member_id);
		$member_level=verifyData($request->member_level);
		$transaction_id=verifyData($request->transaction_id);
		if($request->user_key && $request->url){
			$appObj=new UtilityService;			
			$app_key=$appObj->checkAppKey($request->user_key,$request->url);
			if($app_key==1){
				if(!empty($member_id && $member_level && $transaction_id)){
					if(verifyIntegerData($member_id) == 1 && verifyIntegerData($member_level) == 1){
					
						$userMemberObj=new UserMembershipService($request->member_id);
						$userMemberObj->setIdLevel($request->member_level);
					 	$userMemberObj->setTransactionId($request->transaction_id);
						if($saveMember=$userMemberObj->saveMembership()){
							$result=array(
	    							"status"=>"201",
							     	"Message"=>"Inserted"
							     	);
							return json_encode($result);
						}else{
							throw new ParentFinderException('insertion_failed');
						}
					}
					else{
						throw new ParentFinderException('int_error');
						
					}
					
				}else{
					//Need to check null point exception
					throw new ParentFinderException('null_argument_found');
				}
				
			}
			else{
				throw new ParentFinderException('key_not_valid');
			}
		}
		
	}

		/*  *	Coupon Validation 
		* 	@param  Request $request
     	* 	@return array
		*/
	public function postMembershipCouponValidation(Request $request){	
				$voucher['vocher_code'] =  $request->vocher_code;
				$voucher['idlevel']=$request->member_level;
				$voucher['accountid']=$request->accountid;
				if($request->user_key && $request->url){
				    $appObj=new UtilityService;			
					$app_key=$appObj->checkAppKey($request->user_key,$request->url);
					if($app_key==1){	
				if(!empty($voucher['vocher_code']&&$voucher['idlevel'] && $voucher['accountid'])){
					$voucherobj=new VoucherService(null);
					 $getvocher=$voucherobj->getVoucherDetails($voucher);
					 $result=array(
    							"status"=>"200",
						     	"Voucher_Status"=>$getvocher['Voucher_Status']
						     	);
				}
				else{
					//Need to check null point exception
					throw new ParentFinderException('null_argument_found');
				}
				}
				else{
				throw new ParentFinderException('key_not_valid');
					}
				return json_encode($result);

			}		
	}

	/*  *	Registration 
		*	Adoptive family,Birth Mother and Adoption Agency Registration
		* 	Adoptive family=2,Birth Mother=4 , Adoption Agency=8
		* 	@param  Request $request
     	* 	@return array
	*/
	public function postProfile(Request $request){
		$data['profileType']=$request->profile_type;
		if(!empty($data['profileType'])){
			$data['username']=$request->PFusername;
			$data['password']=$request->PFpassword;
			$data['emailId']=$request->PFemail;
			$data['agencyId']=$request->PFagencyId;
			$data['state'] = $request->state;
			$data['region'] = $request->region;
			if($profileType==2){		
			
				$profileObj=new CoupleService(null);
				$data['firstNameSingle']=$request->PFfirstNameSingle;
				$data['lastNameSingle']=$request->PFlastNameSingle;
				$data['firstNameCouple']=$request->PFfirstNameCouple;
				$data['lastNameCouple']=$request->PFlastNameCouple;
				$data['genderSingle'] = $request->PFgenderSingle;
				$data['genderCouple'] = $request->PFgenderCouple;
				$insertStatus=$profileObj->saveParentProfile($data);
			}
			else if($profileType==4){		
				
				$data['firstName']=$request->PFfirstName;
				$insertStatus=$profileObj->saveParentProfile($data);
			}
			else if($profileType==8){		
				
				$insertStatus=$profileObj->saveParentProfile($data);
			}else{
				throw new ParentFinderException('profile_type_not_found');
			}
		}else{
				throw new ParentFinderException('profile_type_not_found');
			}
		
	}

	/* List Profile Types */
	public function getProfileType(){

		$profiletypeobj=new UtilityService();
		$profiletype= $profiletypeobj->getProfileTypes();
		if($profiletype){
			 $result=array(
    							"status"=>"200",
						     	"Profile_types"=>$profiletype
						     	);

		}
		else{
        throw new ParentFinderException('No_profile_type');
        
      }
      return json_encode($result);
	}

    /*List Basic profile Information */
    public function getBasicProfileApi(){
      
        $api=Input::segment(1);
        $param1=Input::segment(2);
        $user_name=Input::segment(3);
        $type=Input::segment(4);
        $profile=new UtilityService();
		if($account_id=$profile->getAccountIdByUserName($user_name))
		{
        $email=$profile->getEmailById($account_id);
	    $creationDate=date("d-m-Y", strtotime($email->created_at));
         $pdfbookobj=new CoupleService($account_id);
		    $pdfoutput= $pdfbookobj->getPdf($type);
		    $Epub=$pdfbookobj->getEpub();
		    if(!empty($pdfoutput)){ $pdf="true";}
		    else{$pdf="false";}
            $flipbook= $pdfbookobj->getFlipbook();
            if(!empty($flipbook)){$flipbook="true";}
		    	else{$flipbook="false";	}
             $memberObj=new UserMembershipService($account_id);
             $member=$memberObj->getMembership($account_id);
             if($member==""){ $membership=""; }
             else {$membership=$member->Name;}
             if(!empty($Epub)){$epub="true";}
             else{$epub="false";}

             $result=array("Status"=>"200",
                      "Username"=>$user_name,
                      "Membership"=>$membership,
                      "creation date"=>$creationDate,
			          "Email on File"=>$email->emailid,
			          "Our Page"=>"",
		              "FlipBook"=>$flipbook,
		              "E-PUB"=>$epub,
		              "Pdf profile"=>$pdf);
          return json_encode($result);
        }
        else
        {
        	
        throw new ParentFinderException('no-profiles-found');	
        }

    }
    /*  *Edit Contact Information
        * @param  Request $request
     	* @return array


     */
    public function editContact(Request $request){
     	  
        $data['account_id']=verifyData($request->accountid);
        $data['State']=verifyData($request->state);
        $data['Country']=verifyData($request->country);
        $data['Region']=verifyData($request->region);
        $data['City']=verifyData($request->city);
        $data['phonenumber']=verifyData($request->phonenumber);
        $data['address1']=verifyData($request->address1);
          if($request->user_key && $request->url){
		  $appObj=new UtilityService;			
		  $app_key=$appObj->checkAppKey($request->user_key,$request->url);
			if($app_key==1){
            $contactObj=new ContactService(null);
              if(!empty($data['account_id']&&$data['State']&&$data['Country']&&$data['Region']&&$data['City']&&$data['address1']&&$data['Zip']&&$data['phonenumber'])){
                if(verifyAlphaNumSpaces($data['phonenumber']) == 1 && verifyZip($data['Zip']) == 1){
                  $updatestatus=$contactObj->updateContact($data);
                  if($updatestatus){
                    $result=array(
	    					 "status"=>"201",
							  "Message"=>"updated"
							     	);
							return json_encode($result);
                   }
                  else
                    {
                          	throw new ParentFinderException('updation_failed');
                     }
                }
                else{

    	           throw new ParentFinderException('int_error');
                   } 
              }
                else{
                  throw new ParentFinderException('null_argument_found');
				}
            }
			 else{
				throw new ParentFinderException('key_not_valid');
			    }
		 }
						
    } 
   /*   *Post Contact		
		* @param  Request $request
     	* @return array
     */

        public function postContact(Request $request){
          $data['account_id']=verifyData($request->accountid);
          $data['State']=verifyData($request->state);
          $data['Country']=verifyData($request->country);
          $data['Region']=verifyData($request->region);
          if($request->user_key && $request->url){
			$appObj=new UtilityService;			
			$app_key=$appObj->checkAppKey($request->user_key,$request->url);
			 if($app_key==1){
                $contactObj=new ContactService(null);
                if(!empty($data['account_id']&&$data['State']&&$data['Country']&&$data['Region'])) {
                  $insertstatus=$contactObj->saveContactDetails($data);	
                   if($insertstatus)
                   {
                     $result=array(
	    					 "status"=>"201",
							  "Message"=>"inserted"
							     	);
							return json_encode($result);
                   }
                     else
                     {
                          	throw new ParentFinderException('insertion_failed');
                     }
                }
                 else{
                    throw new ParentFinderException('null_argument_found');
				    }
			 }
			 else{
                     throw new ParentFinderException('key_not_valid');
			    }
		   }	    
        }
    /* *Edit profile information  
       * @param  Request $request
       * @return array


    */
    public function editProfile(Request $request){
    	$data['accounts_id']=verifyData($request->account_id);
    	$data['profile_id']=verifyData($request->profile_id);
    	if($request->user_key && $request->url){
			$appObj=new UtilityService;			
			$app_key=$appObj->checkAppKey($request->user_key,$request->url);
			if($app_key==1){
    	    $countAccount=$appObj->getFamilystatus($data['accounts_id']);
    	$data['firstNameSingle']=verifyData($request->PFfirstNameSingle); 
        $data['DOB']=verifyData($request->DOB); 
        $data['genderSingle'] = verifyData($request->PFgenderSingle);
        $data['ethnicity']=verifyData($request->Ethnicity);
        $data['education']=verifyData($request->Education);
        $data['religion']=verifyData($request->Religion);
        $data['occupation']=verifyData($request->Occupation);
        $data['waiting']=verifyData($request->Waiting);
        $data['faith']=verifyData($request->Faith);
        $data['NoOfChildren']=verifyData($request->NoOfChildren);
        $data['type']=verifyData($request->Type);
        if(!empty($data['accounts_id']&&$data['profile_id']&&$data['firstNameSingle'])){
           	if(verifyStringData($data['firstNameSingle']) == 1)
           	{
             $profileObj=new ProfileService(null);
		     $updateStatus=$profileObj->updateProfile($data);
             $childObj=new UtilityService(null);
             $childStatus= $childObj->editChild($data);
                if($countAccount>'1'){ 
                 $data['profile_id']=$request->profile_id1;
    	  	     $data['firstNameSingle']=verifyData($request->PFfirstNameSingle1); 
                 $data['DOB']=verifyData($request->DOB1); 
                 $data['genderSingle'] = verifyData($request->PFgenderSingle1);
                 $data['ethnicity']=verifyData($request->Ethnicity1);
                 $data['education']=verifyData($request->Education1);
                 $data['religion']=verifyData($request->Religion1);
                 $data['occupation']=verifyData($request->Occupation1);
                 $data['waiting']=verifyData($request->Waiting);
                 $data['faith']=verifyData($request->Faith);
                   if(!empty($data['accounts_id']&&$data['profile_id']&&$data['firstNameSingle'])){
                   	 if(verifyStringData($data['firstNameSingle']) == 1)
                   	 {
                    $profileObj=new ProfileService(null);
                    $updateStatus=$profileObj->updateProfile($data);
		             }
		              else{
                       throw new ParentFinderException('string_error');
				        }
				    }
				     else
				       {
					     throw new ParentFinderException('null_argument_found');
				        }
    	        }
    	    
    	           if(($updateStatus) || ($childStatus)){
                    $result=array(
	    					 "status"=>"201",
							  "Message"=>"updated"
							     	);
							return json_encode($result);
                    }
                    else
                    {
                   	  throw new ParentFinderException('updation_failed');
                      }

            }
             else
                 {
               	 throw new ParentFinderException('string_error');
                 }
        }
          else{
                   throw new ParentFinderException('null_argument_found');
				  }
		}
         else{
				throw new ParentFinderException('key_not_valid');
			    }
	}

    }
    /* *Post Letter
        * @param  Request $request
     	* @return array

    */
    public function postLetter(Request $request){
    	$data['account_id']=verifyData($request->account_id);
        $data['label']=verifyData($request->label);
        $data['description']=verifyData($request->description);
        $data['slug']=verifyData($request->slug);
        $data['image']=verifyData($request->image);
        $data['isdefault']=verifyData($request->isdefault);
        if(!empty($data['account_id']&&$data['label'])){
           $letterObj=new LetterService(null);
           $insertstatus=$letterObj->saveletterDetails($data);	
           if($insertstatus){
                     $result=array(
	    					 "status"=>"201",
							  "Message"=>"inserted"
							     	);
							return json_encode($result);
            }
             else
                {
                     throw new ParentFinderException('insertion_failed');
                 }
        } 
        else{
               throw new ParentFinderException('null_argument_found');
               }
    }

public function postLetter(Request $request){
    	echo $data['account_id']=verifyData($request->account_id);
          echo   $data['label']=verifyData($request->label);
             $data['description']=verifyData($request->description);
             $data['slug']=verifyData($request->slug);
             $data['image']=verifyData($request->image);
              $letterObj=new LetterService(null);
                
                $insertstatus=$letterObj->saveletterDetails($data);	
}
		

		 /*   *forgot Password		
		* @param  Request $request
     	* @return array
     	*/
		public function forgotPassword(Request $request){

			$data['mail_id']=verifyData($request->email_id);
			$appObj=new UtilityService;
			if(!empty($data['mail_id'])){
				if(emailVerification($data['mail_id']) == 1){
					$emailcheck=$appObj->emailCheck($data['mail_id']);	
					if($emailcheck){
						$result=array(
	    					 "status"=>"201",
							  "Message"=>"Mail Send",
							  "Mail Status" =>1
							     	);
					}else{
						$result=array(
	    					 "status"=>"201",
							  "Message"=>"Mail Not Send",
							  "Mail Status" =>0
							     	);
					}
					return json_encode($result);
				}
				else{
					throw new ParentFinderException('email_error');
					}
			}
			else{
				throw new ParentFinderException('null_argument_found');
				}

		}

     /* *Delete Pdf
        * @param  Request $request
     	* @return array

    */
    public function deletePdf(Request $request){
 	     $data=verifyData($request->template_userid);
         $pdfObj=new PdfService(null);
         if($deleteStatus=$pdfObj->deletePdf($data)){
                     $result=array(
	    					 "status"=>"201",
							  "Message"=>"deleted"
							     	);
							return json_encode($result);
            }
             else
                {

                     throw new ParentFinderException('deletion_failed');
                 }	

    }

}