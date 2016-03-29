<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\AgencyRepository;
/**
 * Description of ParentService
**/

class AgencyService{

    private $id; 
    private $uri;
    private $title;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function geturi() {
       return $this->uri;
    }
    public  function gettitle() {
       return $this->title;
    }
    public  function getcountry() {
       return $this->country;
    }
     public  function getcity() {
       return $this->city;
    }
     public  function getzip() {
       return $this->zip;
    }
     public  function getwebsite() {
       return $this->website;
    }
    
  
   
    


   public function getAgencyDetails($account_id){
         try{
        $agencyobj=new AgencyRepository(null);
        $adoptionPreferDetails=$agencyobj->getAgencyId($account_id);
                   $adoptionDetails=$agencyobj->getAgencyDetails($adoptionPreferDetails->agency_id);
                   $this->id=$adoptionDetails->id;
                   $this->title=$adoptionDetails->title;
                   $this->uri=$adoptionDetails->uri;
                   $this->country= $adoptionDetails->country;
                   $this->city= $adoptionDetails->city;
                   $this->zip= $adoptionDetails->zip;
                   $this->website= $adoptionDetails->website;
           // print_r($this);
        return $this;   
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    
    }

    
    
}


    
