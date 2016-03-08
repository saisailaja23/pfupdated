<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Journal;

/**
 * Description of ParentService
**/
class Journal {

   
    private $account_id;

    public function __construct($account_id) {
       $this->setOwnerId($account_id);

    }

     public  function getOwnerId() {
       return $this->account_id;
    }
    public  function setOwnerId($account_id) {
         $this->profileId = $account_id;
    }   
    

   
    }



