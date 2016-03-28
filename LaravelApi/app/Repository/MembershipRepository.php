<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Membership;
use App\Models\Account;

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
       try{
            $Membershipobj=new Membership;
            $MembershipDetails =$Membershipobj->where('account_id', '=',$account_id)->first();
            return $ethnicityPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('agency-not-found',$e->getMessage());
        }  

   }
   

   
   
}



