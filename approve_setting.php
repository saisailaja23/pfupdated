<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolDb.php');
$oDb = new BxDolDb();
$flag=0;
$avalues=array('photo','video','journal','profiles','editedprofiles','sections');
if(isset($_POST))
{
    $precheck=$oDb->res("SELECT * FROM bx_groups_moderation WHERE GroupId = ".$_POST['agencyId']);
    if(mysql_num_rows($precheck)==count($avalues)){
        for($i=0;$i<count($avalues);$i++)
        {
         if(isset($_POST['approve_media']))
                (in_array($avalues[$i],$_POST['approve_media']))? $sStatus="on" : $sStatus="off";
         else
             $sStatus="off";
             $oDb->res("UPDATE bx_groups_moderation SET ApproveStatus = '".$sStatus."' WHERE GroupId = ".$_POST['agencyId']." AND Type = '".$avalues[$i]."'");
             $flag=1;
        }
    }else{
        for($i=0;$i<count($avalues);$i++)
        {
            if(isset($_POST['approve_media']))
                (in_array($avalues[$i],$_POST['approve_media']))? $sStatus="on" : $sStatus="off";
             else
                $sStatus="off";
            $oDb->res("INSERT INTO bx_groups_moderation (GroupId,Type,ApproveStatus) VALUES (".$_POST['agencyId'].",'".$avalues[$i]."','".$sStatus."')");
            $flag=1;
        }

    }
}
header("Location:".$_POST['agencycallback']."?msg=".$flag);
?>
