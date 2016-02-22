<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\Http\Services\ParentService;
use App\Http\Models\User;
use Response;


class ParentController extends Controller
{
   public function showProfile()
    {
    		/* Single Profile */
    	    $Parent=new ParentService(11);
	    	$profiles =  $Parent->getProfile();
	    	return json_encode($profiles);
	    	

  	}
		
}
