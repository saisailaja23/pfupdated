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


class ProfileController extends Controller
{
   public function getProfile()
    {
    	/* Single Profile */
    	$profile=new ProfileService(11);
	    $profiles =  $profile->getProfile();
	    $profileDetails=array(
	     					"first_name"=>$profile->getFirstName(),
	     					"last_name"=>$profile->getLastName(),
	     					"dob"=>$profile->getDob(),
	     					"ethnicity"=>$profile->getEthnicity(),
	     					"faith_id"=>$profile->getFaithId(),
	     					"religion_id"=>$profile->getReligionId(),
	     					"waiting_id"=>$profile->getWaitingId()
	     				);
	    return json_encode($profileDetails);
	    	

  	}
		
}
