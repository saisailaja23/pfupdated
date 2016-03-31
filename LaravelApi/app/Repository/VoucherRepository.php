<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Account;
use App\Models\VoucherTransaction;
use App\Exceptions\ParentFinderException;

/**
 * Description of ParentService
**/
class VoucherRepository {

   
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
    
    public function getVoucherDetails($voucher){
       try{
        
            $voucherobj=new Voucher;
            $voucherDetails =$voucherobj
                                ->join('sys_acl_level_prices', 'aqb_membership_vouchers_codes.PriceID', '=', 'sys_acl_level_prices.id')  
                                ->where('sys_acl_level_prices.IDLevel','=',$voucher['idlevel'])
                                ->where('aqb_membership_vouchers_codes.Code','=',$voucher['vocher_code'])
                                ->first();
           return $voucherDetails;
        }catch(\Exception $e){
            //Add Exception here
        }  

   }

       public function getVoucherUsed($voucher,$PriceID){
       try{
            $voucherobj=new VoucherTransaction;
            $voucherDetails =$voucherobj
                                ->where('ProfileID','=',$voucher['accountid'])
                                ->where('PriceID','=',$PriceID)
                                ->where('Code','=',$voucher['vocher_code'])
                                ->where('Status','=','Processed')
                                ->first();
           return $voucherDetails;
        }catch(\Exception $e){
            //Add Exception here
        }  

   }
   
  
   
   
}



