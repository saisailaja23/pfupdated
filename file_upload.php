<?php
define('BX_PROFILE_PAGE', 1);

require_once 'inc/header.inc.php';
require_once BX_DIRECTORY_PATH_INC . 'profiles.inc.php';
require_once BX_DIRECTORY_PATH_ROOT . "log4php/logForCommon.php";

global $logClassObj;
$logClassObj->setLevel(1);
$logClassObj->setModule("uploadProfile");
$logClassObj->setSubmodule("pdfFormat");
$logClassObj->commonWriteLogInOne("\n---------------------------start--------------------------", "INFO");

bx_import('BxTemplProfileView');
bx_import('BxDolInstallerUtils');
?>
        <?php
$pid = getLoggedId();
$oProfile = new BxBaseProfileGenerator($pid);

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

	$target_path = "profileflipbook/";

	$filename = $_FILES['file']['name'];

	$details = pathinfo($filename);
	$extension = $details['extension'];

	$delete_duplicate = "DELETE FROM pdfdetails where owner_id = $pid";
	//echo "DELETE FROM pdfdetails WHERE owner_id = $pid";
	mysql_query($delete_duplicate);

	$sql = "INSERT INTO `pdfdetails` values('','$title','$filename','$pid')";
	$logClassObj->commonWriteLogInOne("INSERT query pdfdetails : ", "INFO");
	$logClassObj->commonWriteLogInOne($sql, "INFO");
	mysql_query($sql);
	$pdfid = mysql_insert_id();

	$randomnumber = randomDigits(7);

	$newfile = $pid . '-' . $randomnumber . '.' . $extension;

	$newfolder = $pid . '-' . $randomnumber;

	$allowed = array("pdf");

	$membership_info = getMemberMembershipInfo(getLoggedId());

	if (!in_array(strtolower($extension), $allowed)) {
		print_r("{state: false, responseFromServer:'Upload pdf format of profile'}");
		$logClassObj->commonWriteLogInOne("its not in pdf format, file name is :" . $filename, "INFO");
		$logClassObj->commonWriteLogInOne("-----------------------------------------------END without uploading------------------------------------------", "INFO");exit;

	} else {
                if (!is_dir('./profileflipbook/' . $newfolder)) {
                    $existingflipbooks = glob($dir['root'] . './profileflipbook/' . $pid . '-*');
                    rmrf($existingflipbooks);
                    mkdir('./profileflipbook/' . $newfolder);
                    chmod('./profileflipbook/' . $newfolder, 0777);
                }

                chmod('./profileflipbook/' . $newfolder, 0777);

		$filesout = glob($dir['root'] . './profileflipbook/outbound/*');
		foreach ($filesout as $filebound) {
			if (is_file($filebound)) {
				$details = pathinfo($filebound);
			}

			$extension = $details['extension'];
			$allowed = array("pdf");
			if (in_array(strtolower($extension), $allowed)) {
				unlink($filebound); // delete file
			}
		}

		if (move_uploaded_file($_FILES['file']['tmp_name'], './profileflipbook/' . $newfolder . "/" . $newfile)) {

			copy('./profileflipbook/' . $newfolder . "/" . $newfile, './profileflipbook/outbound/' . $newfile);
			
			/**/
			$update_defalutPDF      = "UPDATE pdf_template_user SET isDefault ='N' WHERE user_id =$pid";
			mysql_query($update_defalutPDF);

			$currentTime      =	date("Y-m-d H:i:s",time());
			$currentDate      =	date("Y-m-d",time());

			
			
			$tmp_file_path = "'/var/www/html/pf/profileflipbook/outbound/$newfile'";
    
			$insert_tempuser = "INSERT INTO pdf_template_user (user_id, template_id,template_file_path,template_description,isDeleted,isDefault,lastupdateddate)
						VALUES ($pid,0,'','','N','Y','$currentTime')";   
			mysql_query($insert_tempuser);
			$template_user_id = mysql_insert_id();
			
			$templateFilepath = $GLOBALS['dir']['root']."PDFTemplates/user/".$pid."_".$template_user_id."_".$currentDate.".pdf";
			copy('./profileflipbook/' . $newfolder . "/" . $newfile, './PDFTemplates/user/'.$pid.'_'.$template_user_id.'_'.$currentDate.'.pdf');

			$update_filepath         = "UPDATE pdf_template_user SET template_file_path = '$templateFilepath' WHERE template_user_id = $template_user_id";
			mysql_query($update_filepath);
					
			$logClassObj->commonWriteLogInOne("INSERT query pdf_template_user : ", "INFO");
			$logClassObj->commonWriteLogInOne($insert_tempuser, "INFO");
			
			/**/

			$message = "The file " . basename($_FILES['file']['name']) .
			" has been uploaded";

			$newhtml = $pid . '-' . $randomnumber . '.' . "html";

			$myFile = './profileflipbook/' . $newfolder . "/" . $newhtml;

			$fh = fopen($myFile, 'w') or die("can't open file");

			$stringDatahtml = "<p>&nbsp;</p><p>&nbsp;</p><p align=center>We are currently creating this flip book. Please check back in a few minutes.</p>\n";

			$logClassObj->commonWriteLogInOne("---details-----", "INFO");
			$logClassObj->commonWriteLogInOne($stringDatahtml, "INFO");

			fwrite($fh, $stringDatahtml);
			fclose($fh);
			chmod($myFile, 0777);

			// START -- File with user details added by prashanth

			$newtxt = $pid . '-' . $nick . '.' . "csv";
			$UserFile = './profileflipbook/outbound/' . $newtxt;
//			$fh = fopen($UserFile, 'w') or die("can't open file");
			$getUserDeatils = getProfileInfo($pid);
			$AgencyDetailQuery = "SELECT bx_groups_main.title AS AgencyTitle,Profiles.Email AS AgencyEmail,Profiles.WEB_URL AS agencywebsite FROM Profiles JOIN bx_groups_main WHERE  Profiles.AdoptionAgency=bx_groups_main.id AND Profiles.ID  IN (SELECT bx_groups_main.author_id FROM Profiles JOIN bx_groups_main WHERE Profiles.ID = '$pid' AND Profiles.AdoptionAgency=bx_groups_main.id)";
			$Agencyresult = db_res($AgencyDetailQuery);

			while (($Userrows = mysql_fetch_array($Agencyresult, MYSQL_BOTH))) {
				$agencyName = $Userrows['AgencyTitle'];
				$agencyWebsite = $Userrows['agencywebsite'];
				$agencyEmail = $Userrows['AgencyEmail'];

			}
			$coupleProf = getProfileInfo($getUserDeatils['Couple']);
                        $list = array
			(
                                $pid . '-' . $randomnumber . '.' . "pdf",
				$newhtml,
				$getUserDeatils['Email'],
				$agencyName,
				$agencyWebsite,
				$agencyEmail,
				$getUserDeatils['FirstName'] . '/' . $coupleProf['FirstName'],
				$site['url'] . $nick,
				$site['url'] . 'profileflipbook/' . $newfolder . "/" . $newhtml,
			);
                        $csvfile = fopen($UserFile, "w");

                        foreach ($list as $line) {
                                fputcsv($csvfile, explode(',', $line));
			}

                        fclose($csvfile);

//			$coupleProf = getProfileInfo($getUserDeatils['Couple']);
//			$UserData = $getUserDeatils['Email'] . "," . PHP_EOL;
//			$UserData .= $agencyName . "," . PHP_EOL;
//			$UserData .= $agencyWebsite . "," . PHP_EOL;
//			$UserData .= $agencyEmail . "," . PHP_EOL;
//			$UserData .= $getUserDeatils['FirstName'] . '/' . $coupleProf['FirstName'] . "," . PHP_EOL;
//			$UserData .= $site['url'] . $nick . "," . PHP_EOL;
//			$UserData .= $site['url'] . 'profileflipbook/' . $newfolder . "/" . $newhtml . "," . PHP_EOL;
//
//			$mobilepath = $dir['root'] . "profileflipbook/" . $newfolder . "/mobile/index.html";		
//
//			if (file_exists($mobilepath)) {
//				$UserData .= $site['url'] . 'profileflipbook/' . $newfolder . "/mobile/index.html,";
//			}

			$logClassObj->commonWriteLogInOne("---Agency and user details-----", "INFO");
			$logClassObj->commonWriteLogInOne($UserData, "INFO");

//			fwrite($fh, $UserData);
//			fclose($fh);
			chmod($UserFile, 0777);

			// END -- File with user details added by prashanth

			$myFile1 = './profileflipbook/' . $newfolder . "/index.php";
			$fh = fopen($myFile1, 'w') or die("can't open file");
			$stringData = "";

			fwrite($fh, $stringData);
			fclose($fh);
		} else {
			$message = "There was an error uploading the file, please try again!";

			$logClassObj->commonWriteLogInOne("Error message : ", "INFO");
			$logClassObj->commonWriteLogInOne($message, "INFO");

		}
		$t = $site['url'] . 'profileflipbook/' . $newfolder . "/" . $newhtml;
		$sqlfile = "update `pdfdetails` SET pdfname = '$newfile' where id = '$pdfid'";
		$logClassObj->commonWriteLogInOne("update query pdfdetails : ", "INFO");
		$logClassObj->commonWriteLogInOne($sqlfile, "INFO");
		mysql_query($sqlfile);
		if ($lname != '') {
			$con = "Please click <a href=" . $t . " target= _blank>here</a> to view " . $fname . " and " . $lname . "\'s" . " profile flip book";
		} else {
			$con = "Please click <a href=" . $t . " target= _blank>here</a> to view " . $fname . " profile flip book";
		}

		$resultval = mysql_query("Select content,id from aqb_pc_members_blocks where owner_id = '$pid' and val ='1' and  title = 'E-book Profile'");
		$logClassObj->commonWriteLogInOne("Select query aqb_pc_members_blocks : ", "INFO");
		$logClassObj->commonWriteLogInOne("Select content,id from aqb_pc_members_blocks where owner_id = '$pid' and val ='1' and  title = 'E-book Profile'", "INFO");
		$num_rows = mysql_num_rows($resultval);
		$rowval = mysql_fetch_row($resultval);
		if ($num_rows > 0) {
			$Blockid = $rowval[1];

			$flipbooklink = db_arr("select content from `aqb_pc_members_blocks` where owner_id = '$pid' and id ='$Blockid' LIMIT 1");
			$dirname = explode("/", $flipbooklink['content']);

			Delete('./profileflipbook/' . $dirname[4]);

			$updateval = "Update `aqb_pc_members_blocks` SET `content` = '$con' where owner_id = '$pid' and id ='$Blockid'";

			mysql_query($updateval);
			$logClassObj->commonWriteLogInOne("Update query aqb_pc_members_blocks : ", "INFO");
			$logClassObj->commonWriteLogInOne($updateval, "INFO");
			$num_rows = mysql_num_rows($resultval);
		} else {
			$sql = "INSERT INTO `aqb_pc_members_blocks` SET `visible_group` = '3', `type` = 'text', `date` = UNIX_TIMESTAMP(), `owner_id` = '$pid', `content` = '$con', `title` = 'E-book Profile', `share` = '1', `approved` = '1' , `val` = '1'";
			mysql_query($sql);
			$logClassObj->commonWriteLogInOne("INSERT query aqb_pc_members_blocks : ", "INFO");
			$logClassObj->commonWriteLogInOne($sql, "INFO");
			$iBlockID = mysql_insert_id();

			$cblocks = "SELECT  `c`.`id` as `ID`,
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
				$aResult['cl'] = $aBlocks['Column'];
				$aResult['vm'] = $aBlocks['vm'];
				$aResult['cp'] = $aBlocks['uncollapsable'];
				$aResult['cd'] = $aBlocks['collapsed_def'];
				$aResult['rm'] = $aBlocks['irremovable'];
				$aResult['mv'] = $aBlocks['unmovable'];

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
			} else {
			}
		}

		$path = $dir['root'] . "profileflipbook/" . $newfolder . "/";

		$my_file = $newfile;
		$my_path = $path;
		$my_name = "Parent Finder";
		$my_mail = "info@parentfinder.com";
		$my_replyto = "info@parentfinder.com";
		$my_subject = "CONVERTPDF";
		$my_message = "Agencyname: " . $agencyname . ".\nProfile Name: " . $nick;

	}
	$logClassObj->commonWriteLogInOne("file uploaded , file name is :" . $filename, "INFO");
	$logClassObj->commonWriteLogInOne("---------------------------------------------------END -----------------------------------------------", "INFO");
	print_r("{state: true, name:'" . str_replace("'", "\\'", $filename) . "'}");exit;

}
function rmrf($dir) {
    
    foreach (glob($dir) as $file) {
        echo $file;
        if (is_dir($file)) { 
            rmrf("$file/*");
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

function randomDigits($length) {
	$numbers = range(0, 9);
	shuffle($numbers);
	for ($i = 0; $i < $length; $i++) {
		$digits .= $numbers[$i];
	}

	return $digits;
}

function Delete($path) {
	if (is_dir($path) === true) {
		$files = array_diff(scandir($path), array('.', '..'));

		foreach ($files as $file) {
			Delete(realpath($path) . '/' . $file);
		}

		return rmdir($path);
	} else if (is_file($path) === true) {
		return unlink($path);
	}

	return false;
}

?>


