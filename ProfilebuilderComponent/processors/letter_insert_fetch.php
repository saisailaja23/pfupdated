<?php

require_once '../../inc/header.inc.php';
require_once '../../inc/profiles.inc.php';
require_once '../../inc/utils.inc.php';
require_once '../../inc/db.inc.php';

$sSalt = genRndSalt();
//$excepting_birthParent= addslashes(trim($_POST['dear_birthParent']));//dear_birthParent
//
//$about_him= addslashes(trim($_POST['about_him']));//DescriptionMe
//$about_her= addslashes(trim($_POST['about_her']));//DescriptionMe 2end person
//$agency_letter=trim($_POST['agency_letter']);
//$about_them=trim($_POST['about_them']);
//$other_letter=trim($_POST['other']);
$item = $_POST['name'];
$id = $_POST['id'];
$label =addslashes( $_POST['label'] );
$labelsec =addslashes( $_POST['labelsec'] );
$img = @$_POST['imgID'];
if ( $label == 'OTHER' ) {

    $label = $labelsec;
}

$profiledet = getProfileInfo( $id );

if ( isset( $_POST['insertFalg'] ) and trim( $_POST['insertFalg'] ) == 1 ) {

    if ( isset( $_POST['content'] ) ) {
        if ( @mysql_num_rows( mysql_query( "SELECT id FROM bx_groups_main WHERE id=".$profiledet['AdoptionAgency']." AND author_id=".$id ) ) ) {
            $mFlag=false;
        }else {
            // echo "SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$profiledet['AdoptionAgency']."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'";exit();
            if ( @mysql_num_rows( mysql_query( "SELECT GroupId FROM bx_groups_moderation WHERE GroupId =".$profiledet['AdoptionAgency']."  AND ApproveStatus= 'on' AND Type = 'editedprofiles'" ) ) ) {
                $mFlag=true;
            }
            else {
                $mFlag=false;
            }
        }

        $otherLettersFlag = false;
        $content =  mysql_real_escape_string( urldecode( $_POST['content'] ) );
        switch ( $item ) {
			case 'MOTHER':
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `DearBirthParent` = '$content', `img_mother` = '$img' WHERE ID=$id";
					$q_draft = "UPDATE `Profiles_draft` SET `DearBirthParent` = '$content', `img_mother` = '$img' WHERE ID=$id";
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `DearBirthParent` = '$content', `img_mother` = '$img' WHERE ID=$id";
				}
				break;
			case 'AGENCY':
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `agency_letter` = '$content', `img_agency` = '$img' WHERE ID=$id";
					$q_draft = "UPDATE `Profiles_draft` SET `agency_letter` = '$content', `img_agency` = '$img' WHERE ID=$id";
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `agency_letter` = '$content', `img_agency` = '$img' WHERE ID=$id";
				}
				break;
			case 'HIM':
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `DescriptionMe` = '$content', `img_him` = '$img' WHERE `ID` =$id";
					$q_draft = "UPDATE `Profiles_draft` SET `DescriptionMe` = '$content', `img_him` = '$img' WHERE `ID` =$id";
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `DescriptionMe` = '$content', `img_him` = '$img' WHERE `ID` =$id";
				}
				break;
			case 'HER':
				$_profile = getProfileInfo( $id );
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `DescriptionMe` = '$content', `img_her` = '$img' WHERE `ID` =".$_profile['Couple'];
					$q_draft = "UPDATE `Profiles_draft` SET `DescriptionMe` = '$content', `img_her` = '$img' WHERE `ID` =".$_profile['Couple'];
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `DescriptionMe` = '$content', `img_her` = '$img' WHERE `ID` =".$_profile['Couple'];

				}
				break;
			case 'THEM':
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `letter_aboutThem` = '$content', `img_them` = '$img' WHERE ID=$id";
					$q_draft = "UPDATE `Profiles_draft` SET `letter_aboutThem` = '$content', `img_them` = '$img' WHERE ID=$id";
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `letter_aboutThem` = '$content', `img_them` = '$img' WHERE ID=$id";
				}
				break;
			case 'OTHER':
				// $q = "UPDATE `Profiles_draft` SET `others` = '$content' WHERE ID=$id";
				$otherLettersFlag = true;
				$q2 = "SELECT id,description FROM `letter` WHERE profile_id=$id and label ='$label'";
				$result = mysql_query( $q2 );
				if ( mysql_num_rows( $result )>0 ) {
					echo json_encode( array(
							'status' => 'fail',
							'message'=>'this letter already there' ) );
					exit;
				}
				//$img = str_replace('"', "'", $img);
				$q = "insert into `letter` (label, description,profile_id, img) VALUES ('$label', '$content',$id, '$img')";
				break;
			case 'HOME':
				if ( $mFlag ) {
					$q = "UPDATE `Profiles` SET `About_our_home` = '$content', `img_home` = '$img' WHERE ID=$id";
					$q_draft = "UPDATE `Profiles_draft` SET `About_our_home` = '$content', `img_home` = '$img' WHERE ID=$id";
				}
				else {
					$q = "UPDATE `Profiles_draft` SET `About_our_home` = '$content', `img_home` = '$img' WHERE ID=$id";
				}
				break;
			default:
				$item  = str_replace( "'", "''", $item );
				$q = "UPDATE `letter` SET `description` = '$content', img = '$img' WHERE profile_id=$id and `label` = '$item'";
				break;

        }
//        echo $q;
        mysql_query( $q_draft );
        
        if($otherLettersFlag && mysql_query($q)){
            $letter_id = (string)mysql_insert_id();
            $letter_label = "letter_".$letter_id;
            $count = mysql_num_rows(mysql_query("SELECT * FROM letters_sort where profile_id=$id"));
            if( $count != 0){
                mysql_query("INSERT INTO `letters_sort` (`label`, `order_by`, `profile_id`) VALUES ('$letter_label', '$count', '$id')");
            }
            echo json_encode( array(
                    'status' => 'success',
                    'letter_id' => $letter_id
                     ) );

        }else if ( mysql_query( $q ) ) {
            echo json_encode( array(
                    'status' => 'success' ) );
        }else {
            echo json_encode( array(
                    'status' => 'fail',
                    'message'=>'try again later',
                    'sql'=>$q ) );
        }

    } } else if ( isset( $_POST['insertFalg'] ) and trim( $_POST['insertFalg'] ) == 2 ) {
        $item  = str_replace( "'", "''", $item );


            $result_letters= mysql_query("SELECT id FROM letter WHERE profile_id=$id and `label` = '$item'");
            while ( $row_letter= mysql_fetch_array($result_letters)) {
                $letter_id = $row_letter[0];
            }




         $q = "DELETE FROM letter WHERE profile_id=$id and `label` = '$item'";


        if ( mysql_query( $q ) ) {
	    //$order = mysql_query("SELECT order_by FROM letters_sort WHERE profile_id=$id AND label='letter_$letter_id'");
            
            mysql_query("DELETE FROM letters_sort WHERE label='letter_$letter_id'");

	    //mysql_query("UPDATE letters_sort SET order_by = order_by - 1 WHERE order_by > $order AND profile_id=$id");
            
		echo json_encode(array(
                    'status' => 'success'
                    ));

        }else {
            echo json_encode( array(
                    'status' => 'fail',
                    'message'=>'try again later',
                    'sql'=>$q ) );
        }


    }else {
	$custom = 0;
    switch ( $item ) {
    case 'MOTHER':
        $feild = 'DearBirthParent';
        $q = "SELECT DearBirthParent, img_mother as img FROM `Profiles_draft` WHERE ID=$id";
        break;
    case 'AGENCY':
        $feild = 'agency_letter';
        $q = "SELECT agency_letter, img_agency as img FROM `Profiles_draft` WHERE ID=$id";
        break;
    case 'HIM':
        $feild = 'DescriptionMe';
        $q = "SELECT DescriptionMe, img_him as img FROM `Profiles_draft` WHERE ID=$id";
        break;
    case 'HER':
        $feild = 'DescriptionMe';
        $_profile = getProfileInfo( $id );
        $q = "SELECT DescriptionMe, img_her as img FROM `Profiles_draft` WHERE ID= ".$_profile['Couple'];
        break;
    case 'THEM':
        $feild = 'letter_aboutThem';
        $q = "SELECT letter_aboutThem, img_them as img FROM `Profiles_draft` WHERE ID=$id";
        break;
    case 'OTHER':
        echo json_encode( array(
                'status' => 'success',
                'data' => ''
            ) ); exit;
        break;
    case 'HOME':
        $feild = 'About_our_home';
        $q = "SELECT About_our_home FROM `Profiles_draft` WHERE ID=$id";
        break;
    default:
		$custom = 1;
        $item  = str_replace( "'", "''", $item );
        $feild = 'description';
        $q = "SELECT description, img, id FROM `letter` WHERE profile_id=$id and label ='$item'";
        break;
    }
    $result = mysql_query($q);
    while ( $row = mysql_fetch_array($result)) {
        $outArray[] = (trim( $row[$feild] )!='')?trim( $row[$feild] ):'';

		$img = json_decode($row['img']);
		$img = implode(',', $img);
		
		$q2 = "SELECT CONCAT(Hash,'.', Ext) as img FROM `bx_photos_main` WHERE ID IN($img);";
		$r2 = mysql_query($q2);
		
		while($rw = mysql_fetch_array($r2)){
			$img2[] = $rw['img'];
		}
		$outArray[] = json_encode($img2);

        if($row['img']){ $outArray[] = $row['img']; }
        else{ $outArray[] = '[]'; }

		if($custom==0){ $outArray[] = ''; }
		else{ $outArray[] = $row['id']; }
    }
    echo json_encode(array(
            'status' => 'success',
            'data' => $outArray
    ));

}
?>
