<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;

use App\Repository\ProfileRepository;

/**
 * Description of ParentService
**/
class EthnicityService {

   
    private $ethnicityId;
    private $ethnicity;

    public function __construct($ethnicityId) {
       $this->setEthnicityId($ethnicityId);

    }
    
    public  function getEthnicityId() {
       return $this->ethnicityId;
    }

    public  function setEthnicityId($ethnicityId) {
         $this->ethnicityId = $ethnicityId;
    }

    public function getEthnicity(){        
        return $this->ethnicity;
    }

    public function setEthnicity($ethnicity){        
        $this->ethnicity=$ethnicity;
    }
   
    /* Get a single profiles */
   
    public  function getEthnictyDetails(){
        $ethnicity=new EthnicityRepository($this->ethnicityId);  
        $ethnicityDetails=$ethnicity->getEthnictyDetails();
        $this->ethnicityVal=$ethnicityDetails->ethnicity;
        return $this;       
    }  


        
    
}
