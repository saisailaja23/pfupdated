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

   
  private $AlbumId;

    public function __construct($AlbumId) {
       $this->setAlbumId($AlbumId);

    }

     public  function getAlbumId() {
       return $this->AlbumId;
    }
    public  function setAlbumId($AlbumId) {
         $this->AlbumId = $AlbumId;
    }      
    
    public function getAlbumByID($account_id){
      $albumid = '';
        try{
            $albumobj=new Albums;
            $albumdetails =$albumobj->where('account_id', '=',$account_id)
                                  ->where('Caption', '=','Home Pictures')
                                  ->where('Type', '=','bx_photos')
                                  ->where('AllowAlbumView', '!=','2')
                                  ->get(); 
            foreach ($albumdetails as $albumdetail) {
             $albumid[]= $albumdetail->ID;
                  }      
             //$albumid = substr($albumid, 0, -1);
            return  $albumid;              
        }catch(\Exception $e){
             //Add Exception here
        } 
    }
        public function getAlbums($albumId,$account_id){
           try{
        $bxphotomainobj=new BxPhotosMain;
        $bxphotomain = $bxphotomainobj
                           ->join('sys_albums_objects', 'bx_photos_main.ID', '=', 'sys_albums_objects.id_object') 
                           ->whereIn('sys_albums_objects.id_album',$albumId)
                           ->where('bx_photos_main.Status', '=','approved')
                           ->where('bx_photos_main.account_id', '=',$account_id)
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();
         return $bxphotomain;
          }catch(\Exception $e){
             //Add Exception here
        } 
    }
public function getAlbumsByAlbumId($albumId,$account_id,$type){
           try{
        $bxphotomainobj=new BxPhotosMain;
        if(empty($type)){
        $bxphotomain = $bxphotomainobj
                           ->join('sys_albums_objects', 'bx_photos_main.ID', '=', 'sys_albums_objects.id_object') 
                           ->where('sys_albums_objects.id_album','=',$albumId)
                           ->where('bx_photos_main.Status', '=','approved')
                           ->where('bx_photos_main.account_id', '=',$account_id)
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();
          }
          else{
             $bxphotomain = $bxphotomainobj
                           ->join('sys_albums_objects', 'bx_photos_main.ID', '=', 'sys_albums_objects.id_object') 
                           ->where('sys_albums_objects.id_album','=',$albumId)
                           ->where('bx_photos_main.Categories','=',$type)
                           ->where('bx_photos_main.Status', '=','approved')
                           ->where('bx_photos_main.account_id', '=',$account_id)
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();
          }
         return $bxphotomain;
          }catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getAlbumDetails(){
          try{
            $bxphotomainobj=new BxPhotosMain;
            $bxphotomain = $bxphotomainobj
                           ->where('Status', '=','approved')
                           ->where('ID', '=',$this->AlbumId)
                           ->first();

            return $bxphotomain;              
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

     public function getPhotosById(){
          try{
            $bxphotomainobj=new BxPhotosMain;
            $bxphotomain = $bxphotomainobj
                           ->where('Status', '=','approved')
                           ->where('ID', '=',$this->AlbumId)
                           ->first();

            return $bxphotomain;              
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
   

    }



