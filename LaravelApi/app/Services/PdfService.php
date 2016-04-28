<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\Albums;
use App\Models\Video;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;
use App\Repository\ProfileRepository;
use App\Repository\AlbumsRepository;
use App\Repository\EprofileRepository;
use App\Repository\PdfRepository;

/**
 * Description of AccountService
**/
class PdfService {

    private $id;
    private $template_file_path;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function gettemplate_file_path() { 
       return $this->template_file_path;
    }
    
  
  
   /*Get Pdf Profile*/
    public function getPdfDetails($type,$account_id) {
        try{ 
        $eprofileObj=new PdfRepository(null);
        if($eprofileDetails=$eprofileObj->getPdfDetails($this->id)){
        $pdf = $eprofileDetails->template_file_path;
        $path_parts = explode('/', $pdf);
        $pdf_output =  $path_parts[5].'/'.$path_parts[6].'/'.$path_parts[7];
            }
        else{
             $pdf_output = '';
        }
        if($type == 'single_profile'){
            $this->template_file_path2="ProfilebuilderComponent/pdf.php?id=".$account_id;
            $this->template_file_path='';
            $this->id='';

        }
        else  if($type == 'multi_profile'){
            $this->template_file_path=$pdf_output;
            $this->template_file_path2='';
            $this->id=$this->id;
            
        }

        else{
             $this->template_file_path2="ProfilebuilderComponent/pdf.php?id=".$account_id;
            $this->template_file_path=$pdf_output;
            $this->id=$this->id;
            
        }
        //print_r($this);
        return $this;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
         
    }
    /* Delete pdf profile   */
    public function deletePdf($data,$account_id){
        try{
        $pdfObj=new PdfRepository(null);
        foreach(explode(',',$data) as $val){
        $deleteStatus=$pdfObj->deletePdfDetails($val,$account_id);
       }
        return $deleteStatus;
        }
       catch(\Exception $e){
             //Add Exception here
        } 
    }
       
}