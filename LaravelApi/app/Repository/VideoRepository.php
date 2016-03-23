<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Albums;
use App\Models\Video;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;

/**
 * Description of ParentService
**/
class VideoRepository {

   
  private $VideoId;

    public function __construct($VideoId) {
       $this->setVideoId($VideoId);

    }

     public  function getVideoId() {
       return $this->VideoId;
    }
    public  function setVideoId($VideoId) {
         $this->VideoId = $VideoId;
    }      
    
    public function getVideoAlbumByID($account_id){
      $albumid = '';
        try{
            $albumobj=new Albums;
            $albumdetails =$albumobj->where('account_id', '=',$account_id)
                                  ->where('Caption', '!=','Home Videos')
                                  ->where('Type', '=','bx_videos')
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
        public function getVideoAlbums($VideoId,$account_id){
           try{
        $videonobj=new Video;
        $video = $videonobj
                           ->join('sys_albums_objects', 'RayVideoFiles.ID', '=', 'sys_albums_objects.id_object') 
                           ->whereIn('sys_albums_objects.id_album',$VideoId)
                           ->where('RayVideoFiles.Status', '=','approved')
                           ->orderBy('sys_albums_objects.obj_order','DESC')
                           ->get();
         return $video;
          }catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getVideoDetails(){
          try{
            $videonobj=new Video;
            $video = $videonobj
                           ->where('Status', '=','approved')
                           ->where('ID', '=',$this->VideoId)
                           ->first();

            return $video;              
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

  public function getHomeVideos($account_id){
      try{
            $albumobj=new Albums;
            $albumdetails =$albumobj->where('account_id', '=',$account_id)
                                  ->where('Caption', '=','Home Videos')
                                  ->where('Type', '=','bx_videos')
                                  ->where('AllowAlbumView', '!=','2')
                                  ->get(); 
          foreach ($albumdetails as $albumdetail) {
             $albumid[]= $albumdetail->ID;
          }             
          return  $albumid;              
      }catch(\Exception $e){
             
      } 
    }

}



