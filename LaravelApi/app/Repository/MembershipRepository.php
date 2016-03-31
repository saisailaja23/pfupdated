<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Membership;
use App\Models\Account;
use App\Exceptions\ParentFinderException;

/**
 * Description of ParentService
**/
class MembershipRepository {

   
    private $id;

    public function __construct($id) {
       $this->setId($id);

    }

     public  function getId() {
       return $this->id;
    }
    public  function setId($id) {
         $this->id = $id;
    }   
    

   public function getMembershipDetails(){
       try{echo $this->id;
            $Membershipobj=new Membership;
            $MembershipDetails =$Membershipobj
                                ->join('sys_acl_level_prices', 'sys_acl_levels.ID', '=', 'sys_acl_level_prices.IDLevel')  
                                ->where('sys_acl_levels.ID','=',$this->id)->first();
           // print_r($MembershipDetails);
            return $MembershipDetails;
        }catch(\Exception $e){
            //Add Exception here
        }  

   }
   
 public function getAllMembershipids(){
       try{
            $Membershipobj=new Membership;
             $MembershipDetails =$Membershipobj
                                  ->join('sys_acl_level_prices', 'sys_acl_levels.ID', '=', 'sys_acl_level_prices.IDLevel')
                                  ->get();
            return $MembershipDetails;
        }catch(\Exception $e){
            //Add Exception here
        }  

   }
   
   
}



