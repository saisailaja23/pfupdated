<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\VoucherRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of ParentService
**/

class VoucherService{

    private $id; 
    private $priceid;
    private $code;
    private $discount;
    private $start;
    private $end;
    private $singleuse;
    private $used;
    private $threshold;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function getpriceid() {
       return $this->priceid;
    }
    public  function getcode() {
       return $this->code;
    }
    public  function getdiscount() {
       return $this->discount;
    }
    public  function getstart() {
       return $this->start;
    }
    public  function getend() {
       return $this->end;
    }
    public  function getsingleuse() {
       return $this->singleuse;
    }
    public  function getused() {
       return $this->used;
    }
    public  function getthreshold() {
       return $this->threshold;
    }
    
    
  
   /*Get CoupunDetails*/
   public function getVoucherDetails($voucher){
         try{
        $voucherobj=new VoucherRepository(null);
        $voucherDetails=$voucherobj->getVoucherDetails($voucher);
        
        if(!empty($voucherDetails)){
            $voucherUsed=$voucherobj->getVoucherUsed($voucher,$voucherDetails->PriceID);
            if($voucherDetails->Start > date("Y-m-d") || $voucherDetails->End < date("Y-m-d") ){
                //echo "date";
               throw new ParentFinderException('code_expired');
             }
            else if($voucherDetails->Used >= $voucherDetails->Threshold || ($voucherDetails->SingleUse == 1 && $voucherDetails->Used > 1)) {
                  //echo "used";
                 throw new ParentFinderException('code_expired');
            }
            else if(!empty($voucherUsed)){
                  //echo "applied";
                 throw new ParentFinderException('code_applied');
            }

            else{
                    $result=array(
                            "Voucher_Status"=>"1"
                             );
                  return $result; 
                }
      }
        else{
              //echo "invalid";
             throw new ParentFinderException('invalid_code');
        }
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    exit;
    }

    
    
}


    
