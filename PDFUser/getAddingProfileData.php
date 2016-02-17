<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
define('BX_PROFILE_PAGE', 1);
require_once( '../inc/header.inc.php' );
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolFilesDb.php');

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
bx_import('BxDolFilesModule');

require_once ('phpfunctions.php');
//require_once ('generatePDF.php');

$add_section     = $_POST['section'];
$sel_ods         = $_POST['selblockids'];
$parent_ID       =   getLoggedId();
switch($add_section)
{
    case "blocks":
        $retun_block  = addblock($sel_ods,$parent_ID);        
        if($retun_block['status'] == 'success')
        {
            echo json_encode(array(
                'status' => 'success', 
                'response' => 'Succcess', 
                'block_list' => array( 'rows' => $retun_block['response'])
            ));
        }
        else
        {
            echo json_encode(array(
                'status' => 'error', 
                'response' => $retun_block['response']
                
            ));
        }
        break;
    case "photos":
        $retun_photos  = addphotos($sel_ods,$parent_ID);  

        if($retun_photos['status'] == 'success')
        {
            echo json_encode(array(
                'status' => 'success', 
                'response' => 'Succcess', 
                'photos_list' => array( 'rows' => $retun_photos['response'])
            ));
        }
        else
        {
            echo json_encode(array(
                'status' => 'error', 
                'response' => $retun_photos['response']
                
            ));
        }
        break;
}
//for abilty to add blocks by user showing all the blocks in grid
function addblock($sel_ods,$parentID)
{ 
    try{
    $BlockArray             = getAllBlockData($parentID);
    //print_r($BlockArray);

    $selectd_blocks  = split(',',$sel_ods);
    $block_Flag      = 0;
    $grid_block     = array();
    foreach($BlockArray as $blck)
    {
        $arrValues  = array();
        if(!(in_array($blck['blockname'], $selectd_blocks)))
        {   
            $block_Flag       = 1;            
            array_push($arrValues, 0);
            array_push($arrValues, $blck['blockheading']);
            array_push($grid_block , array( 
		'id' => $blck['blockname'], 
		'data' => $arrValues,
            ));
        }
    }
    if(!$block_Flag)
    {        
        $retun_block  = (array(
            'status' => 'error',
            'response' => 'No Block Exists'
        ));
    }
    else
    {
        $retun_block  = (array(
                'status' => 'success',
                'response' => $grid_block
            ));
    }
    return $retun_block;
    }
    catch(exception $e)
    {
       echo json_encode(array(
            'status' => 'error',
            'response' => 'Error '.$e
        ));
    }

    
}

function addphotos($sel_ods,$parentID)
{
    try{
        $getPhotos = getPhotoAlbums($parentID);

        $selectd_photos  = split(',',$sel_ods);
        
        $photo_Flag      = 0;
        $grid_photo     = array();
        foreach($getPhotos as $photFetch)
        {
            $arrValues  = array();
            if(!(in_array($photFetch['Hash'], $selectd_photos)))
            {
                $filename = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'.'.$photFetch['Ext'];
                $file_medium_name = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'_m.'.$photFetch['Ext'];
                if (file_exists($filename) && file_exists($file_medium_name)) {
                    $photo_Flag = 1;
                    $img_path = $GLOBALS['site']['url']."m/photos/get_image/file/".$photFetch['Hash'].".".$photFetch['Ext'];
                    /*$photo_str_select .= '<row id="'. $photFetch['Hash'] .'">
                                <cell style ="vertical-align:middle;text-align: center;"></cell>
                                <cell style ="vertical-align:middle;text-align: left;">
                                &lt;img src="'.$img_path.'" height="50" width="50"/&gt;</cell>
                            </row>';*/
                    $img_cellValue  = '<img src="'.$img_path.'" height="50" width="50"/>';
                    array_push($arrValues, 0);
                    array_push($arrValues, $img_cellValue);
                    array_push($grid_photo , array( 
                        'id' => $photFetch['Hash'], 
                        'data' => $arrValues,
                    ));
                }
            }
        }
        if(!$photo_Flag)
        {
            $retun_photo  = (array(
            'status' => 'error',
            'response' => 'No Photo Exists'
            ));
        }
        else
        {
            $retun_photo  = (array(
                    'status' => 'success',
                    'response' => $grid_photo
                ));
        }
        return $retun_photo;


        //$photo_str_select .= '</rows></xml>';
        //$fname = createXMLFile_add_photo($photo_str_select);
        //$pdf_add_photo_url = $GLOBALS['site']['url']."PDFUser/add_pdf_xml/".$fname;
        //echo $photo_str_select;
    }
    catch(exception $e)
    {
       echo json_encode(array(
            'status' => 'error',
            'response' => 'Error '.$e
        ));
    }
}
?>