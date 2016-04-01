<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\MembershipRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of ParentService
**/

class MembershipService{

    private $id; 
    private $name;
    private $icon;
    private $description;
    private $active;
    private $purchasable;
    private $removable;
    private $order;
    private $free;
    private $trial;
    private $trial_length;
    private $membershipamount;
    private $membershipperiod;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function getname() {
       return $this->name;
    }
    public  function geticon() {
       return $this->icon;
    }
    public  function getdescription() {
       return $this->description;
    }
    public  function getactive() {
       return $this->active;
    }
    public  function getpurchasable() {
       return $this->purchasable;
    }
    public  function getremovable() {
       return $this->removable;
    }
    public  function getorder() {
       return $this->order;
    }
    public  function getfree() {
       return $this->free;
    }
    public  function gettrial() {
       return $this->trial;
    }
    public  function gettrial_length() {
       return $this->trial_length;
    }
    public  function getmembershipamount() {
       return $this->membershipamount;
    }
    public  function getMembershipPeriod() {
       return $this->membershipperiod;
    }
    
    
  

   public function getMembershipDetails(){
         try{
        $membershipobj=new MembershipRepository($this->id);
        $membershipDetails=$membershipobj->getMembershipDetails();
       // print_r($membershipDetails);
                    if(count($membershipDetails)>0){
                   $this->id =  $membershipDetails->ID;
                   $this->name=$membershipDetails->Name;
                   $this->icon=$membershipDetails->Icon;
                   $this->description=$membershipDetails->Description;
                   $this->active=$membershipDetails->Active;
                   $this->purchasable=$membershipDetails->Purchasable;
                   $this->removable=$membershipDetails->Removable;
                   $this->order=$membershipDetails->Order;
                   $this->free=$membershipDetails->Free;
                   $this->trial=$membershipDetails->Trial;
                   $this->trial_length=$membershipDetails->Trial_Length;
                   $this->membershipamount=$membershipDetails->Price;
                   $this->membershipperiod=$membershipDetails->Days;
      //print_r($this);            

                  return $this; 
                  }
                  else{
                    throw new ParentFinderException('membership_not_found');
                  }  
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    
    }

    
    
}


    
