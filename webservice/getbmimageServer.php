<?php
/*
 * Program Name : getbmimageServer.php
 * Author       : Shino John
 * Date Created : 08/10/2014
 * Description  : Server for PF Webservice
 */

require_once ('lib/nusoap.php');
require_once ('pfwbfunctions.php');
function getBMSmallImage($agencyId,$format) { 
    $posts = array();
    $getval = getBMImages($agencyId);
    $i = 0;
    foreach ($getval as $key => $value) {            
        $posts[$value['ID']] = array(
                                'ID' => $value['ID'], 
                                'NickName' => htmlspecialchars($value['NickName'], ENT_QUOTES), 
                                'Avatar' => htmlspecialchars($value['Avatar'], ENT_QUOTES),
                                'Couple' => htmlspecialchars($value['Couple'], ENT_QUOTES),
                                'Sex' => htmlspecialchars($value['Sex'], ENT_QUOTES),
                                'FirstName' => htmlspecialchars($value['FirstName'], ENT_QUOTES),
                                'LastName' => htmlspecialchars($value['LastName'], ENT_QUOTES),
                                'p1FirstName' => htmlspecialchars($value['p1FirstName'], ENT_QUOTES),
                                'p1LastName' => htmlspecialchars($value['p1LastName'], ENT_QUOTES),
                                'ChildAge' => htmlspecialchars($value['ChildAge'], ENT_QUOTES),
                                'Adoptiontype' => htmlspecialchars($value['Adoptiontype'], ENT_QUOTES),
                                'noofchildren' => htmlspecialchars($value['noofchildren'], ENT_QUOTES),
                                'faith' => htmlspecialchars($value['faith'], ENT_QUOTES),
                                'Ethnicity' => htmlspecialchars($value['Ethnicity'], ENT_QUOTES),
                                'Age' => htmlspecialchars($value['Age'], ENT_QUOTES),
                                'p1Age' => htmlspecialchars($value['p1Age'], ENT_QUOTES)
            
            );
    }  
    
    if ($agencyId) {
        if ($format == "json") {
            header('Content-type: application/json');
			return json_encode(array('posts'=>$posts));
        }        
    }
    else {
            return "No products listed under that category";
    }
}
$server = new soap_server();
$server->configureWSDL("sever", "urn:sever");
$server->register("getBMSmallImage",
    array("agencyId" => "xsd:string","formats" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:bmSmallImgList",
    "urn:bmSmallImgList#getBMSmallImage",
    "rpc",
    "encoded",
    "Get a listing of products by category");
if (!isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
$server->service($HTTP_RAW_POST_DATA);
exit();
?>