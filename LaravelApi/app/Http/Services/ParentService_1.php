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

    private $profile_id;

    // public function __construct($profileId) {
    //     return $this;
    // }
    
    /* Get First Name of User  */
    public  function getFirstName($id) {
        $profiles=new Profiles;
        $first_name =$profiles->where('accounts_id', '=',$id)->first()->first_name;
        if($first_name){
            return $first_name;
        }
        else{
                //Add Exception here...
                return 'No values';
        }
       
    }

    /* Get Last Name of User  */
    public  function getLastName($id) {
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
    public  function getAllProfiles(){

        $users =  User::all();
        foreach($users as $user){
            if( !$first_name=$this->getFirstName($user->account_id)){   
                //Add Exception here...            
            }

            if( !$last_name=$this->getLastName($user->account_id)){
               //Add Exception here...
            }

            $profiles[]=array(
                            "first_name"=>$first_name,
                             "last_name"=>$last_name,
                            );
           
        }
        return $profiles;

    }    
     public  function getProfile(){

        $users =  User::all();
       // $parentService=new ParentService();
        foreach($users as $user){
            if( !$first_name=$this->getFirstName($user->account_id)){   
                //Add Exception here...            
            }

            if( !$last_name=$this->getLastName($user->account_id)){
               //Add Exception here...
            }

            $profiles[]=array(
                            "first_name"=>$first_name,
                             "last_name"=>$last_name
                            );
           
        }
        return $profiles;

    }    
        
    
}
