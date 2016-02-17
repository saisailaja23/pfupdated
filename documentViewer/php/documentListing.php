<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../../../userhome/\$ettings.php");
require_once($path["serloc"]."set_link.php");
require_once($path["serloc"]."admin/session.php");
require_once($path["serloc"]."admin/command.php");

global $RolesParams;

$phase_id                                   =   trim($_POST['phase_id']);
$stage_id                                   =   trim($_POST['stage_id']);
$task_id                                    =   trim($_POST['task_id']);
//$caseId                                     =   trim($_GET['case_id']);

$RolesParams->setuserIDValue($agencyid);
$user_id                                    =  $RolesParams->getuserIDValue();
$caseId                                     =  $RolesParams->getConnectionIDValue();

//$agency_id                                  =   trim($_REQUEST['agencyid']);

$agency_id                                  =  $user_id;

if($phase_id !="" && $stage_id !="" && $task_id !=""){
    $checkPage_docSql                       =   "select document_filter,page_document_flg, webpage from formmaker_tasks where phase_id='$phase_id' and stage_id='$stage_id' and task_id='$task_id'";
    $checkPage_docExe                       =   mssql_query($checkPage_docSql);
    $getPage_doc                            =   mssql_fetch_array($checkPage_docExe);
    $documentFlter                          =   $getPage_doc['document_filter'];
    $page_document_flg                      =   $getPage_doc['page_document_flg'];
    $webpage                                =   str_replace("undefined", '', $getPage_doc['webpage']);
    $docid                                  =   array();
    $documentName                           =   array();
    $documentDesc                           =   array();
    $documentFullName                       =   array();
    $weblink                                =   array();
    $fileNameArr                            =   array();
    $agencyID                               =   $_SESSION['preagencyid'];

    $getAgencyUserIDSql                     =   "select user_id from user_agencies where agency_id='$agencyID'";
    $getAgencyUserIDExe                     =   mssql_query($getAgencyUserIDSql);
    $getAgencyUserID                        =   mssql_fetch_row($getAgencyUserIDExe);
    $agencyUserID                           =   $getAgencyUserID[0];
    $current_user_type = getCurrentUserType();
    if($current_user_type == 'adoptive_parent' || $current_user_type == 'birth_parent')
    {
        $user_ids=$login_social_user_id;
    }
    else
    {
        $user_ids=$agency_id;
    }
    $getUserGroupSql                        =   "select group_id from user_accounts where user_id='$user_ids'";
    $getUserGroupExe                        =   mssql_query($getUserGroupSql);
    $getUserGroup                           =   mssql_fetch_row($getUserGroupExe);
    $group_ids                              =   $getUserGroup[0];
    $group_ids                              =  explode(',', $group_ids);
    $group_id                               =  $group_ids[0];


    if(trim($page_document_flg) !=""){
        if(trim($webpage) !=""){
            $documentIDs                    = explode(",", $webpage);
            for($i=0;$i<count($documentIDs);$i++){
               $upload_id                   = $documentIDs[$i];
                              
               if($i==0){
                $where                      .=   " upload_id='$upload_id' ";
               }else{
                $where                      .=   " OR upload_id='$upload_id' ";
               }
            }
           $sql                             =   "select upload_id,file_name,description from upload where ".$where;
           $result2                         =   mssql_query($sql);
           while($myrow2=mssql_fetch_array($result2)){
                //$webpagetaskValues            .=    $myrow2['file_name'].", ";
               $documentIDVal                   =   $myrow2[0];
                $getVersionsQry                 =   mssql_query("select top 1 upload_version.filename,upload_version.versionname,upload.description from upload left join upload_version on upload.file_name=upload_version.filename where upload_id='$documentIDVal' and (upload_version.filename != '' and upload_version.versionname !='') order by upload_version.version_id desc");
                
                if(mssql_num_rows($getVersionsQry) >=1){
                    while($doc1 = mssql_fetch_row($getVersionsQry)){
                        $lastCharFileName           =   substr(strrchr($doc1[1], "."),1);
                        $lastCharFileName           =   ".".$lastCharFileName; 
                        array_push($documentName,str_replace($lastCharFileName,"",$doc1[1]));
                        array_push($documentFullName,$doc1[1]);
                    }
                }else{
                    $lastCharFileName           =   substr(strrchr($myrow2['file_name'], "."),1);
                    $lastCharFileName           =   ".".$lastCharFileName;
                    array_push($documentName,str_replace($lastCharFileName,"",$myrow2['file_name']));
                    array_push($documentFullName,$myrow2['file_name']);
                }
                
                $doc_id = $myrow2['upload_id'];
                array_push($docid,$doc_id);
                array_push($documentDesc,$myrow2['description']);
                array_push($weblink,"N");
                array_push($fileNameArr,$myrow2['file_name']);
                $filename = $myrow2['file_name'];
           }
        }
        if(trim($documentFlter) !=""){
            if(trim($documentFlter) =="P"){
                $checkPage_docSql           =   "select upload_id,file_name,description  from upload where upload_module='documents' and uploaded_by='$agencyUserID' and phase_id like '%,$phase_id,%' or phase_id like '$phase_id' or phase_id like '$phase_id,%' or phase_id like '%,$phase_id'";
                $checkPage_docExe           =   mssql_query($checkPage_docSql);
                while($myrow3=mssql_fetch_array($checkPage_docExe)){
                    $doc_id = $myrow3['upload_id'];
                    
                    $getVersionsQry                 =   mssql_query("select top 1 upload_version.filename,upload_version.versionname,upload.description from upload left join upload_version on upload.file_name=upload_version.filename where upload_id='$doc_id' and (upload_version.filename != '' and upload_version.versionname !='') order by upload_version.version_id desc");
                    if(mssql_num_rows($getVersionsQry) >=1){
                        while($doc1 = mssql_fetch_row($getVersionsQry)){
                            $lastCharFileName           =   substr(strrchr($doc1[1], "."),1);
                            $lastCharFileName           =   ".".$lastCharFileName; 
                            array_push($documentName,str_replace($lastCharFileName,"",$doc1[1]));
                            array_push($documentFullName,$doc1[1]);
                        }
                    }else{  
                        $lastCharFileName2           =   substr(strrchr($myrow3['file_name'], "."),1);
                        $lastCharFileName2           =   ".".$lastCharFileName2;                        
                        array_push($documentName,str_replace($lastCharFileName2,"",$myrow3['file_name']));
                        array_push($documentFullName,$myrow3['file_name']);
                    }                  
                    

                    array_push($docid,$doc_id);
                    array_push($documentDesc,$myrow3['description']);
                    array_push($weblink,"N");
                    array_push($fileNameArr,$myrow3['file_name']);
                    $filename = $myrow3['file_name'];

               }
            }
        }
        if(trim($documentFlter) !=""){
            if(trim($documentFlter) =="G"){
                $checkPage_docSql           =   "select upload_id,file_name,description  from upload where upload_module='documents' and uploaded_by='$agencyUserID' and group_id like '%,$group_id,%' or group_id like '$group_id' or group_id like '$group_id,%' or group_id like '%,$group_id'";
                $checkPage_docExe           =   mssql_query($checkPage_docSql);
                while($myrow3=mssql_fetch_array($checkPage_docExe)){
                    $doc_id = $myrow3['upload_id'];
                    
                    $getVersionsQry                 =   mssql_query("select top 1 upload_version.filename,upload_version.versionname,upload.description from upload left join upload_version on upload.file_name=upload_version.filename where upload_id='$doc_id' and (upload_version.filename != '' and upload_version.versionname !='') order by upload_version.version_id desc");
                    if(mssql_num_rows($getVersionsQry) >=1){
                        while($doc1 = mssql_fetch_row($getVersionsQry)){
                            $lastCharFileName           =   substr(strrchr($doc1[1], "."),1);
                            $lastCharFileName           =   ".".$lastCharFileName; 
                            array_push($documentName,str_replace($lastCharFileName,"",$doc1[1]));
                            array_push($documentFullName,$doc1[1]);
                        }
                    }else{
                        $lastCharFileName2           =   substr(strrchr($myrow3['file_name'], "."),1);
                        $lastCharFileName2           =   ".".$lastCharFileName2;   
                        array_push($documentName,str_replace($lastCharFileName2,"",$myrow3['file_name']));
                        array_push($documentFullName,$myrow3['file_name']);
                    }                      
                    
                    array_push($docid,$doc_id);
                    array_push($documentDesc,$myrow3['description']);
                    array_push($weblink,"N");
                    array_push($fileNameArr,$myrow3['file_name']);
                    $filename = $myrow3['file_name'];
               }
            }
        }
    }else{
        if(trim($webpage)){
                array_push($documentName,$webpage);
                array_push($documentFullName,$webpage);
                array_push($documentDesc,$webpage);
                array_push($weblink,"Y");
           }
        if(trim($documentFlter) != ""){
            if(trim($documentFlter) == "P"){
                $checkPage_docSql           =   "select upload_id,file_name,description  from upload where upload_module='documents' and uploaded_by='$agencyUserID' and phase_id like '%,$phase_id,%' or phase_id like '$phase_id' or phase_id like '$phase_id,%' or phase_id like '%,$phase_id'";
                $checkPage_docExe           =   mssql_query($checkPage_docSql);
                while($myrow3=mssql_fetch_array($checkPage_docExe)){
                    $doc_id = $myrow3['upload_id'];
                    $getVersionsQry                 =   mssql_query("select top 1 upload_version.filename,upload_version.versionname,upload.description from upload left join upload_version on upload.file_name=upload_version.filename where upload_id='$doc_id' and (upload_version.filename != '' and upload_version.versionname !='') order by upload_version.version_id desc");
                    if(mssql_num_rows($getVersionsQry) >=1){
                        while($doc1 = mssql_fetch_row($getVersionsQry)){
                            $lastCharFileName           =   substr(strrchr($doc1[1], "."),1);
                            $lastCharFileName           =   ".".$lastCharFileName; 
                            array_push($documentName,str_replace($lastCharFileName,"",$doc1[1]));
                            array_push($documentFullName,$doc1[1]);
                        }
                    }else{
                        $lastCharFileName2           =   substr(strrchr($myrow3['file_name'], "."),1);
                        $lastCharFileName2           =   ".".$lastCharFileName2;     
                        array_push($documentName,str_replace($lastCharFileName2,"",$myrow3['file_name']));
                        array_push($documentFullName,$myrow3['file_name']);
                    }                    

                    array_push($docid,$doc_id);
                    array_push($documentDesc,$myrow3['description']);
                    array_push($weblink,"N");
                    array_push($fileNameArr,$myrow3['file_name']);
                    $filename = $myrow3['file_name'];
               }
            }
            if(trim($documentFlter) == "G"){
                $checkPage_docSql           =   "select upload_id,file_name,description from upload where upload_module='documents' and uploaded_by='$agencyUserID' and group_id like '%,$group_id,%' or group_id like '$group_id' or group_id like '$group_id,%' or group_id like '%,$group_id'";
                $checkPage_docExe           =   mssql_query($checkPage_docSql);
                while($myrow3=mssql_fetch_array($checkPage_docExe)){
                    $doc_id = $myrow3['upload_id'];
                    $getVersionsQry                 =   mssql_query("select top 1 upload_version.filename,upload_version.versionname,upload.description from upload left join upload_version on upload.file_name=upload_version.filename where upload_id='$doc_id' and (upload_version.filename != '' and upload_version.versionname !='') order by upload_version.version_id desc");
                    if(mssql_num_rows($getVersionsQry) >=1){
                        while($doc1 = mssql_fetch_row($getVersionsQry)){
                            $lastCharFileName           =   substr(strrchr($doc1[1], "."),1);
                            $lastCharFileName           =   ".".$lastCharFileName; 
                            array_push($documentName,str_replace($lastCharFileName,"",$doc1[1]));
                            array_push($documentFullName,$doc1[1]);                            
                        }
                    }else{
                        $lastCharFileName2           =   substr(strrchr($myrow3['file_name'], "."),1);
                        $lastCharFileName2           =   ".".$lastCharFileName2;  
                        array_push($documentName,str_replace($lastCharFileName2,"",$myrow3['file_name']));
                        array_push($documentDesc,$myrow3['description']);
                    }                    

                    array_push($documentFullName,$myrow3['file_name']);
                    array_push($weblink,"N");
                    array_push($fileNameArr,$myrow3['file_name']);
                    $filename = $myrow3['file_name'];
               }
            }
        }
   }
            $filepath                           = '';
            $n=0;
            $current_user_type = getCurrentUserType();
        //echo $agencycwid;
       // global $Data;
             if($current_user_type == 'adoptive_parent' || $current_user_type == 'birth_parent')
            {

                $user_id=$login_social_user_id;
            }
            else
            {

                $user_id=$agency_id;
            }

            $jsonArray = array();
            
            for($j=0;$j<=count($documentName)-1;$j++){

                $pdf                                = explode(".", $documentName[$j]);
                if(trim($documentDesc[$j])){
                    $docName                    = strip_tags(html_entity_decode($documentDesc[$j]));
                }else{
                    $docName                    =   $documentName[$j];
                }
                $documentid=$docid[$j];
                if($weblink[$j] == "N"){

                    //if(in_array('pdf',$pdf)){
                        $name                  =   htmlentities($docName);

                        //$icon_pdf              =   '<a  onClick= "MAP_FlexPaper_ViewPort('."'".$documentFullName[$j]."'".','."'".$filepath."'".')"; title="Swf" alt="Swf"><img border="0" src="auxiliary/dhtmlxfull/icons/print.gif" width="16" height="16"/></a>';
                        $confsql="select doc_id,status from formmaker_tasks_page_document where doc_id=$documentid and phase_id=$phase_id and stage_id=$stage_id and task_id=$task_id and user_id=$user_id";
                        $confresult=mssql_query($confsql);
                        $status=mssql_fetch_row($confresult);

                        if(mssql_num_rows($confresult) == 1 && $status[1] == 1){
                         $check_ack="1" ;
                         $allValues = $phase_id.'_'.$stage_id.'_'.$task_id.'_'.$documentid.'_'.$login_social_user_id.'_'.$n.'_false_'.$caseId;
                        }
                        else{
                           $check_ack="0" ;
                           $allValues = $phase_id.'_'.$stage_id.'_'.$task_id.'_'.$documentid.'_'.$login_social_user_id.'_'.$n.'_true_'.$caseId;
                        }
                         
                   /* }
                    else
                    {
                        $name                   =   htmlentities($docName);
                        $check_ack              ="";
                    }*/
                }else{
                    $name                       =   htmlentities($docName);
                     $check_ack              ="";
                }
                $jsonArray[$j]['name']              = $fileNameArr[$j];
                $jsonArray[$j]['title']             = $documentName[$j];
                $jsonArray[$j]['web_source_path']   = $_POST['thisURL'].'/PDFTemplates/user/'.$name;
                $jsonArray[$j]['description']       = $name;
                $jsonArray[$j]['read']              = $check_ack;
                $jsonArray[$j]['processed_status']  = 'true';
                $jsonArray[$j]['documentid']  = $documentid;
        $icon_pdf                           = '';
       $n++;
        }
    }
	//$agencyid
    echo $jsonArr = json_encode($jsonArray);
?>

