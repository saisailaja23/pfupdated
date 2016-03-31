<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Agency;
use App\Models\Account;

/**
 * Description of ParentService
**/
class AgencyRepository {

   
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
    

   public function getAgencyId($account_id){
       try{
            $Agencyobj=new Account;
            $ethnicityPreferDetails =$Agencyobj->where('account_id', '=',$account_id)->first();
            return $ethnicityPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('agency-not-found',$e->getMessage());
        }  

   }
   public function getAgencyDetails($agency_id){
       try{
            $Agencyobj=new Agency;
            $ethnicityPreferDetails =$Agencyobj->where('author_id', '=',$agency_id)
                                              ->join('ContactDetails', 'ContactDetails.Account_id', '=', 'bx_groups_main.author_id') 
                                                ->first();
            return $ethnicityPreferDetails;
        }catch(\Exception $e){
               throw new ParentFinderException('agency-not-found',$e->getMessage());
        }  

   }

   
   
}



