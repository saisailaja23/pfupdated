<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\AppUser;

/**
 * Description of ParentService
**/
class AppUserRepository {

   
   
    private $Url;

    public function __construct($Url) {
         $this->setUrl($Url);
    }
    
    public  function getUrl() {
       return $this->Url;
    }

    public  function setUrl($Url) {
         $this->Url = $Url;
    } 

   
    

    public  function getAppUserKey($key){   
        try{
            $appUser=new AppUser;
            $appUserKey =$appUser->where('user_key', '=', md5($key))
                                ->where('website','=',$this->Url)
                                ->count();                               
            return $appUserKey;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }
   
        
    
}
