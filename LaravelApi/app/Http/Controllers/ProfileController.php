<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\ProfileService;
use App\Models\User;
use Response;
use Illuminate\Support\Facades\Input;


class ProfileController extends Controller
{
   
   /* Single Profile Api */
   public function getProfileApi()
    {
    	
    	$api=Input::segment(1);

    	if($api=='profile'){			/*To get a single profile */

    		$profile_id=Input::segment(2);
			$profile=new ProfileService($profile_id);
			$profiles =  $profile->getProfile();

			$profileDetails=array(
						     	"first_name"=>$profile->getFirstName(),
						     	"last_name"=>$profile->getLastName(),
						     	"dob"=>$profile->getDob(),
						     	"gender"=>$profile->getGender(),
						     	"ethnicity"=>$profile->getEthnicity(),
						     	"faith"=>$profile->getFaith(),
						     	"religion_id"=>$profile->getReligionId(),
						     	"waiting"=>$profile->getWaiting(),
						     	"account_id"=>$profile->getAccountId(),
						     	"couple_first_name"=>$profile->getCoupleFirstName(),
						     	"couple_last_name"=>$profile->getCoupleLastName(),
						     	"couple_dob"=>$profile->getCoupleDob(),
						     	"couple_gender"=>$profile->getCoupleGender(),
						     	"couple_faith"=>$profile->getCoupleFaith(),
						     	"couple_ethnicity"=>$profile->getCoupleEthnicity()
						     	);	

    	}
    	else if($api=='profiles'){			/* To list all profiles */
    		
    		$profile=new ProfileService(0);
    		$profiles= $profile->getAllProfiles();
    		$profileIds=$profile->getProfileIds();
     		foreach($profileIds as $profile_id){
     			$profile=new ProfileService($profile_id);
				$profiles =  $profile->getProfile();
				$profileDetails[]=array(
						     	"first_name"=>$profile->getFirstName(),
						     	"last_name"=>$profile->getLastName(),
						     	"dob"=>$profile->getDob(),
						     	"faith"=>$profile->getFaith(),
						     	"waiting"=>$profile->getWaiting(),
						     	"avatar"=>$profile->getAvatar(),
						     	"couple_first_name"=>$profile->getCoupleFirstName(),
						     	"couple_last_name"=>$profile->getCoupleLastName(),
						     	"couple_dob"=>$profile->getCoupleDob()
						     	);	
     		}
    		
    	}
    	
    	    
	    return json_encode($profileDetails);	    	

  	}

  	
  	
		
}
