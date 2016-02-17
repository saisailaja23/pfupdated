<?php

define('BX_PROFILE_PAGE', 1);

require_once( 'inc/header.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
//require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );

require_once(BX_DIRECTORY_PATH_ROOT . "log4php/logForCommon.php");
//create log
global $logClassObj;
$logClassObj->setLevel(1);
$logClassObj->setModule("E-PUBProfile");
$logClassObj->setSubmodule("epub");
$logClassObj->commonWriteLogInOne("\n---------------------------start--------------------------", "INFO");

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
?>
<?php

$pid = getLoggedId();
$oProfile = new BxBaseProfileGenerator($pid);
// $aCopA = $oProfile->_aProfile;

$aCopA = $oProfile->_aProfile;
$aCopB = $oProfile->_aCouple;

$fname = $aCopA['FirstName'];
$lname = $aCopB['FirstName'];

$nick = $aCopA['NickName'];
$agency = $aCopA['AdoptionAgency'];

$sqlagency = "select uri from bx_groups_main where id = '$agency'";
$logClassObj->commonWriteLogInOne("query from bx_groups_main: ", "INFO");
$logClassObj->commonWriteLogInOne($sqlagency, "INFO");
$agencyresult = mysql_query($sqlagency);
$agencyrow = mysql_fetch_row($agencyresult);

$agencyname = $agencyrow[0];

if (isset($_FILES['file'])) {
    $target_path = "epub/";
    $filename = $_FILES['file']['name'];
    $details = pathinfo($filename);
    $extension = $details['extension'];
    $newfile = $pid . '-' . $nick . '.' . $extension;
    $newfolder = $pid . '-' . $nick;
    if (!is_dir('./epub/' . $newfolder)) {
        mkdir('./epub/' . $newfolder);
        chmod('./epub/' . $newfolder, 0777);
    }
    chmod('./epub/' . $newfolder, 0777);

//            $allowed = array("pdf");
    $allowed = array("epub");


    $membership_info = getMemberMembershipInfo(getLoggedId());

    if (!in_array(strtolower($extension), $allowed)) {
        print_r("{state: false, responseFromServer:'Upload pdf format of profile'}");
        $logClassObj->commonWriteLogInOne("its not in epub format, file name is :" . $filename, "INFO");
        $logClassObj->commonWriteLogInOne("-----------------------------------------------END without uploading------------------------------------------", "INFO");
        exit;
    } else {
        $files = glob($dir['root'] . './epub/' . $newfolder . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                $details = pathinfo($file);
            $extension = $details['extension'];
//            $allowed = array("pdf");
            $allowed = array("epub");
            if (in_array(strtolower($extension), $allowed)) {
                unlink($file); // delete file
            }
        }
        $filesout = glob($dir['root'] . './epub/outbound/*'); // get all file names
        foreach ($filesout as $filebound) { // iterate files
            if (is_file($filebound))
                $details = pathinfo($filebound);
            $extension = $details['extension'];
//            $allowed = array("pdf");
            $allowed = array("epub");
            if (in_array(strtolower($extension), $allowed)) {
                unlink($filebound); // delete file
            }
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], './epub/' . $newfolder . "/" . $newfile)) {
            copy('./epub/' . $newfolder . "/" . $newfile, './epub/outbound/' . $newfile);
            $message = "The file " . basename($_FILES['file']['name']) . " has been uploaded";

            $filePath = $site['url'] . 'epub/' . $newfolder . "/" . $newfile;
        $resultval = mysql_query("Select content,id from aqb_pc_members_blocks where owner_id = '$pid' and val ='1' and  title = 'E-PUB Profile'");
        $logClassObj->commonWriteLogInOne("Select query aqb_pc_members_blocks : ", "INFO");
        $logClassObj->commonWriteLogInOne("Select content,id from aqb_pc_members_blocks where owner_id = '$pid' and val ='1' and  title = 'E-PUB Profile'", "INFO");
        $num_rows = mysql_num_rows($resultval);
        $rowval = mysql_fetch_row($resultval);
        if ($num_rows > 0) {
            $Blockid = $rowval[1];
                $updateval = "Update `aqb_pc_members_blocks` SET `content` = '$filePath' where owner_id = '$pid' and id ='$Blockid'";
            mysql_query($updateval);
            $logClassObj->commonWriteLogInOne("Update query aqb_pc_members_blocks : ", "INFO");
            $logClassObj->commonWriteLogInOne($updateval, "INFO");
            $num_rows = mysql_num_rows($resultval);
        } else {
                $sql = "INSERT INTO `aqb_pc_members_blocks` SET `visible_group` = '3', `type` = 'text', `date` = UNIX_TIMESTAMP(), `owner_id` = '$pid', `content` = '$filePath', `title` = 'E-PUB Profile', `share` = '1', `approved` = '1' , `val` = '1'";
            mysql_query($sql);
            $logClassObj->commonWriteLogInOne("INSERT query aqb_pc_members_blocks : ", "INFO");
            $logClassObj->commonWriteLogInOne($sql, "INFO");
            $iBlockID = mysql_insert_id();
            $cblocks = "SELECT
										   `c`.`id` as `ID`,
										   `c`.`order` as `Order`,
		                                                          `c`.`column` as `Column`,
										   `c`.`unmovable`,
										   `c`.`irremovable`,
										   `c`.`uncollapsable`,
										   `c`.`collapsed_def`,
										   `c`.`visible_group` as `vm`
									FROM `aqb_pc_members_blocks` as `c`
									WHERE `c`.`id` = '$iBlockID' LIMIT 1";
            $logClassObj->commonWriteLogInOne("SELECT query aqb_pc_members_blocks : ", "INFO");
            $logClassObj->commonWriteLogInOne($cblocks, "INFO");
            $cresult = mysql_query($cblocks);
            while ($aBlocks = mysql_fetch_array($cresult)) {
                $aResult = array();
                $aResult['rw'] = $aBlocks['Order'];
                $aResult['cl'] = $aBlocks['Column']; //col
                $aResult['vm'] = $aBlocks['vm']; //visible for members group
                $aResult['cp'] = $aBlocks['uncollapsable']; //collapsable
                $aResult['cd'] = $aBlocks['collapsed_def']; //collapsable
                $aResult['rm'] = $aBlocks['irremovable']; //removable
                $aResult['mv'] = $aBlocks['unmovable']; //movable
                $ppage = "SELECT `page` FROM `aqb_pc_profiles_info` WHERE `member_id` = '$pid'";
                $logClassObj->commonWriteLogInOne("SELECT query aqb_pc_profiles_info : ", "INFO");
                $logClassObj->commonWriteLogInOne($ppage, "INFO");
                $pageresult = mysql_query($ppage);
                while ($pagerow = mysql_fetch_array($pageresult)) {
                    $pag = $pagerow['page'];
                    eval('$aPBlocks = ' . $pagerow['page'] . ';');
                    $sType = 'c';
                    $iBlockId = $iBlockID;
                    if (!isset($aPBlocks[$sType][$iBlockId])) {
                        $aPBlocks[$sType][$iBlockId] = $aResult;
                        $sBlocks = addslashes(var_export($aPBlocks, true));
                        $newpage = "REPLACE INTO `aqb_pc_profiles_info` SET `page` = '$sBlocks', `member_id` = '$pid'";
                        $logClassObj->commonWriteLogInOne("REPLACE query aqb_pc_profiles_info : ", "INFO");
                        $logClassObj->commonWriteLogInOne($newpage, "INFO");
                        mysql_query($newpage);
                    }
                }
            }
        }
        } else {
            $message = "There was an error uploading the file, please try again!";
        }

//  $sql = "INSERT INTO `aqb_pc_members_blocks` SET `visible_group` = '3', `type` = 'text', `date` = UNIX_TIMESTAMP(), `owner_id` = '$pid', `content` = '<a href=http://www.parentfinder.com/epop/62/index.html target = _blank />Flip Book</a>', `title` = 'New Block', `share` = '1', `approved` = '1'";
        //  echo $sql;
        //  mysql_query($sql);
        /*
          $iBlockID =  mysql_insert_id();

          $aResult = array();
          $aBlocks['ID'] = $iBlockID;

          $aBlocks['$sType'] = 's';
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['rw'] = 0; //row
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['cl'] = 1;//col
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['vm'] = 3;//visible for members group
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['cp'] = 0;//collapsable
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['cd'] = 0;//collapsable
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['rm'] = 0;//removable
          $aResult[$aBlocks['sType']][$aBlocks['ID']]['mv'] = 0;//movable



          $sBlocks = addslashes(var_export($aResult, true));


          function getProfileColumns() {

          $pcolumn = "SELECT `Column` FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Column` > 0 GROUP BY `Column`";
          $columnresult = mysql_query($pcolumn);
          while($columnrow = mysql_fetch_array($columnresult)) {

          $column = $columnrow['Column'];
          }
          return $column;
          }

          $pcount = "SELECT COUNT(*) FROM `aqb_pc_profiles_info` WHERE `member_id` = '$pid'";
          $presult = mysql_query($pcount);
          $prows = mysql_num_rows($presult);

          if($prows > 0) {

          $ppage = "SELECT `page` FROM `aqb_pc_profiles_info` WHERE `member_id` = '$pid'";
          $pageresult = mysql_query($ppage);

          while($pagerow = mysql_fetch_array($pageresult)) {

          $pag =  $pagerow['page'];

          $aRes = array();
          eval('$aRes = ' . $pag . ';');


          $aBlocks = $aRes;
          $iCounter = count(getProfileColumns());

          foreach($aBlocks as $k => $v)
          foreach($v as $key => $value)
          if ((int)$value['cl'] > (int)$iCounter) unset($aBlocks[$k][$key]);

          if (empty($aBlocks))
          return false;
          $sBlocks = addslashes(var_export($aBlocks, true));
          return $this -> query("REPLACE INTO `{$this->_sPrefix}profiles_info` SET `page` = '{$sBlocks}', `member_id` = '{$iProfileID}'");



          }


          }

          else {

          $sql1 = "INSERT INTO `aqb_pc_profiles_info` SET `member_id` = '62', `page`= '{$sBlocks}'";
          echo $sql1;
          mysql_query($sql1);

          }
         */
        function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
            global $logClassObj;
            $file = $path . $filename;
            $file_size = filesize($file);
            $handle = fopen($file, "r");
            $content = fread($handle, $file_size);
            fclose($handle);
            $content = chunk_split(base64_encode($content));
            $uid = md5(uniqid(time()));
            $name = basename($file);
            $header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
            $header .= "Reply-To: " . $replyto . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
            $header .= "This is a multi-part message in MIME format.\r\n";
            $header .= "--" . $uid . "\r\n";
            $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $header .= $message . "\r\n\r\n";
            $header .= "--" . $uid . "\r\n";
            $header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use different content types here
            $header .= "Content-Transfer-Encoding: base64\r\n";
            $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
            $header .= $content . "\r\n\r\n";
            $header .= "--" . $uid . "--";

            $logClassObj->commonWriteLogInOne("Email details  , file name is :" . $filename, "INFO");
            $logClassObj->commonWriteLogInOne("mail to : " . $mailto, "INFO");
            $logClassObj->commonWriteLogInOne("subject : " . $subject, "INFO");
            $logClassObj->commonWriteLogInOne("header : " . $header, "INFO");

            if (mail($mailto, $subject, "", $header)) {
                //echo "Pdf Upload successfully..."; // or use booleans here
            } else {
                //echo "mail send ... ERROR!";
            }
        }

        $path = $dir['root'] . "epub/" . $newfolder . "/";

        $my_file = $newfile;
        $my_path = $path;
        $my_name = "Parent Finder";
        $my_mail = "info@parentfinder.com";
        $my_replyto = "info@parentfinder.com";
        $my_subject = "CONVERTPDF";
        //$my_message = $pdfid . '/' . $title;
        //$my_message = $agencyname .'/'. $nick;
        $my_message = "Agencyname: " . $agencyname . ".\nProfile Name: " . $nick;

        //   mail_attachment($my_file, $my_path, "forms@cairsolutions.com", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
        //  mail_attachment($my_file, $my_path, "prashanth.adkathbail@mediaus.com", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
//bp11643@gmail.com
//header('Location: http://www.parentfinder.com/MikeandMary');
    }
    $logClassObj->commonWriteLogInOne("file uploaded , file name is :" . $filename, "INFO");
    $logClassObj->commonWriteLogInOne("---------------------------------------------------END -----------------------------------------------", "INFO");
    print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "'}");
    exit;
}

//function isAllowedAdd($isPerformAction = false) {
//	        global $logClassObj;
//		defineActions();
//                $user_pid = getLoggedId();
//		$aCheck = checkAction($user_pid, '175', $isPerformAction);
////print_r($aCheck);
//                $logClassObj->commonWriteLogInOne("isAllowedAdd function details ","INFO");
//                $logClassObj->commonWriteLogInOne("user_pid : " . $user_pid,"INFO"); 
//                $logClassObj->commonWriteLogInOne("isPerformAction : " . $isPerformAction,"INFO"); 
//                $logClassObj->commonWriteLogInOne("aCheck : " . print_r($aCheck,true),"INFO"); 
//            	return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
//               
//	}
//function defineActions() {
//		defineMembershipActions(array ('Flip book'));
//	}
//function isAdmin() {
//return isAdmin($user_pid) || isModerator($user_pid);
//}
//function randomDigits($length){
//    $numbers = range(0,9);
//    shuffle($numbers);
//    for($i = 0;$i < $length;$i++)
//       $digits .= $numbers[$i];
//    return $digits;
//}
?>


