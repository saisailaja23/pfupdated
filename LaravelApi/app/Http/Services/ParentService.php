<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;
use App\Http\Models\Profiles;

/**
 * Description of ParentService
**/
class ParentService {

    //put your code here  
    
    /* Get First Name of User  */
    public static function getFirstName($id) {
        $first_name = Profiles::where('accounts_id', '=',$id)->first()->first_name;
        if($first_name){
            return $first_name;
        }
        else{
                //Add Exception here...
                return 'No values';
        }
       
    }

    /* Get Last Name of User  */
     public static function getLastName($id) {
        $last_name = Profiles::where('accounts_id', '=',$id)->first()->last_name;
        if($last_name){
            return $last_name;
      }
      else{
            //Exception
            return 'No values';
      }
       
    }


    /* List all profiles */
    public static function getAllProfiles(){

        $users =  User::all();
        foreach($users as $user){
            if( !$first_name=ParentService::getFirstName($user->account_id)){   
                //Add Exception here...            
            }

            if( !$last_name=ParentService::getLastName($user->account_id)){
               //Add Exception here...
            }

            $profiles[]=array(
                            "first_name"=>$first_name,
                             "last_name"=>$last_name,
                            );
           
        }
        return $profiles;

    }    
        
    
}
