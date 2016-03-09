<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Albums;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;

/**
 * Description of ParentService
**/
class AlbumsRepository {

   
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
    
    public function getAlbumID($account_id){
echo $account_id;
        try{
            $albumobj=new Albums;
            $albumdetails =$albumobj->where('account_id', '=',$account_id)
                                  ->where('Caption', '=','Home Pictures')
                                  ->where('Type', '=','bx_photos')
                                  ->where('AllowAlbumView', '!=','2')
                                  ->get();       
            $albumid = $albumdetails->ID;
            /*$bxphotomainobj=new BxPhotosMain;
            $bxphotomain = $bxphotomainobj
                           ->join('sys_albums_objects', 'bx_photos_main.ID', '=', 'sys_albums_objects.id_object') 
                           ->where('sys_albums_objects.id_album', '=',$albumid)
                           ->where('bx_photos_main.Status', '=','approved')
                           ->where('bx_photos_main.account_id', '=',$account_id)
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();*/
            return $albumid;              
        }catch(\Exception $e){
             //Add Exception here
        } 
    }
    public function getJournalsByAccount($accountId){
    try{
        $journal=new Journal;
        $journalDetails =$journal->select('PostId')->where('account_id', '=', $accountId)
                                ->where('PostStatus','=','approval')
                                //->where('allowView','=',3)
                                ->get(); 
        return $journalDetails;
    }catch(\Exception $e){
             //Add Exception here
        } 
   }
     public function getAlbumsByAccount($account_id){

        try{
            $bxphotomainobj=new BxPhotosMain;
            $bxphotomain = $bxphotomainobj
                           ->join('sys_albums_objects', 'bx_photos_main.ID', '=', 'sys_albums_objects.id_object') 
                           ->where('sys_albums_objects.id_album', '=',$albumid)
                           ->where('bx_photos_main.Status', '=','approved')
                           ->where('bx_photos_main.account_id', '=',$account_id)
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();
            return $albumid;              
        }catch(\Exception $e){
             //Add Exception here
        } 
    }


    }



