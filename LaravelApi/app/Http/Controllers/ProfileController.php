<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Services\ProfileService;
use App\Repository\ProfileRepository;
use App\Models\User;
use Response;
use Illuminate\Support\Facades\Input;


class ProfileController extends Controller
{
   /* Single Profile Api */
   public function getProfile()
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
						     	"Gender"=>$profile->getGender(),
						     	"ethnicity"=>$profile->getEthnicity(),
						     	"Faith"=>$profile->getFaith(),
						     	"religion_id"=>$profile->getReligionId(),
						     	"waiting"=>$profile->getWaiting(),
						     	"account_id"=>$profile->getAccountId(),
						     	"couple_id"=>$profile->getCoupleId()
						     	);	

    	}
    	else if($api=='profiles'){			/* To list all profiles */
    		

    	}
    	
    	    
	    return json_encode($profileDetails);	    	

  	}

  	
  	
		
}
