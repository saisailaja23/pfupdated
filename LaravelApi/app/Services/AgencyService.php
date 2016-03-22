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
  
   
    


   public function getAgencyDetails($account_id){
         try{
        $childPrefer=new AgencyRepository(null);
        $adoptionPreferDetails=$childPrefer->getAgencyId($account_id);
                      $adoptionDetails=$childPrefer->getAgencyDetails($adoptionPreferDetails->agency_id);
                   $this->id=$adoptionDetails->id;
                   $this->title=$adoptionDetails->title;
                   $this->uri=$adoptionDetails->uri;
           // print_r($this);
        return $this;   
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    
    }

    
    
}


    
