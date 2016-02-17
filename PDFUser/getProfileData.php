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

$add_section     = $_GET['section'];
$sel_ods         = $_GET['selblockids'];
$parent_ID       =   getLoggedId();
header ("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
switch($add_section)
{
    case "blocks":
        //$retun_block  = addblock($sel_ods,$parent_ID);
        //echo $retun_block;
        $BlockArray             = getAllBlockData($parent_ID);
        //print_r($BlockArray);

        $selectd_blocks  = split(',',$sel_ods);
         ?>
        <rows height="30" valign="middle">
        <?php
        $block_Flag      = 0;
        foreach($BlockArray as $blck)
        {
            if(!(in_array($blck['blockname'], $selectd_blocks)))
            {   
                $block_Flag       = 1;
                ?>
                <row id="<?php echo $blck['blockname']?>" >
                    <cell style ="vertical-align:middle;text-align: center;"></cell>
                    <cell style ="vertical-align:middle;text-align: left;"><![CDATA[<?php echo $blck['blockheading']?>]]></cell>
                </row>
                <?php
            }
        }
        if(!$block_Flag)
        {           
            ?>
            <row id="No" > 
                <cell style ="vertical-align:middle;text-align: center;"></cell>
                <cell style ="vertical-align:middle;text-align: left;" rowspan ="2">No Block Exists</cell>
            </row>
            <?php
        }
        ?>
        </rows>
        <?php
        break;
    case "photos":
        //$retun_photos = addphotos($sel_ods,$parent_ID);
        //echo $retun_photos;
        
        $getPhotos = getPhotoAlbums($parent_ID);

        $selectd_photos  = split(',',$sel_ods);
        ?>
        <rows height="30" valign="middle">
        <?php
        $photo_Flag      = 0;
        foreach($getPhotos as $photFetch)
        {
            if(!(in_array($photFetch['Hash'], $selectd_photos)))
            {
                $filename = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'.'.$photFetch['Ext'];
                $file_medium_name = $GLOBALS['dir']['root'].'modules/boonex/photos/data/files/'.$photFetch['ID'].'_m.'.$photFetch['Ext'];
                if (file_exists($filename) && file_exists($file_medium_name)) {
                    $photo_Flag = 1;
                    $img_path = $GLOBALS['site']['url']."m/photos/get_image/file/".$photFetch['Hash'].".".$photFetch['Ext'];
                    
                    ?>
                    <row id="<?php echo $photFetch['Hash'];?>" >
                        <cell style ="vertical-align:middle;text-align: center;"></cell>
                        <cell style ="vertical-align:middle;text-align: left;"><![CDATA[<?php echo '<img src="'.$img_path.'" height="50" width="50" style="padding-top:5px;"/>'?>]]></cell>
                    </row>
                    <?php
                }
            }
        }
        if(!$photo_Flag)
        {
            ?>
            <row id="No" > 
                <cell style ="vertical-align:middle;text-align: center;"></cell>
                <cell style ="vertical-align:middle;text-align: left;" rowspan ="2">No Photo Exists</cell>
            </row>
            <?php
        }
        ?>
        </rows>
        <?php
        break;
}

?>