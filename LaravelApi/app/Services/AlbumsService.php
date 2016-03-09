<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\Albums;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;
use App\Repository\ProfileRepository;
use App\Repository\AlbumsRepository;
/**
 * Description of AccountService
**/
class AlbumsService {

    public function getAlbum($account_id){  
        $album=new AlbumsRepository(null);  
        echo $albumDetails=$album->getAlbumById($account_id);
        return $this;
    }
    
}