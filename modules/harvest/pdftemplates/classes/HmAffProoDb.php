<?
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModuleDb');
bx_import('BxDolAlerts');

class HmAffProoDb extends BxDolModuleDb {
    var $_oConfig;
	function HmAffProoDb(&$oConfig) {
		parent::BxDolModuleDb();
        $this->_sPrefix = $oConfig->getDbPrefix();
		$this->_oConfig = &$oConfig;
		$this->_BannersDir = BX_DIRECTORY_PATH_MODULES.'harvest/pdftemplates/images/banners/';
    }
	//-------------------------------- MISC -----------------------------------//
	
	
	
	//-------------------------------- CAMPAIGNS -----------------------------------//
	function getCampaigns(){
		$sSql = "SELECT * FROM `pdf_template_master` where isDeleted = 'N' and template_disbale_status = 'N'";
		return $this->getAll($sSql);
	}
	function getCampaignsArray(){
		$sSql = "SELECT `id`,`name` FROM `{$this->_sPrefix}campaigns`";			
		$aCampaigns = $this->getAll($sSql);
		if(is_array($aCampaigns)){
			foreach($aCampaigns as $aCampaign){
				$aCampaignArray[$aCampaign['id']] = $aCampaign['name'];
			}
		}
		return $aCampaignArray;
	}
	function getCampaignInfo($iCid){
		$sSql = "SELECT * FROM `pdf_template_master` WHERE `template_id` = '{$iCid}' LIMIT 1";
		return $this->getRow($sSql);
	}

		function createCampaign($aVars){
	          $sSql1 = "INSERT INTO `pdf_template_master` SET
		 `template_name` = '{$aVars['campaign_name']}',
		 `template_description` 	= '{$aVars['campaign_name1']}',
		 `template_type` 	= '{$aVars['country']}',
		  `isDeleted` 		= 'N',
		  `template_disbale_status` = 'N',
                  `printMode` = '{$aVars['printmode']}'";

            
              $multi = $aVars['state'];
              $counter = count($multi);
        
	     $this->query($sSql1);
             $iNewID1 = db_last_id();

/*
                
     if($aVars['country'] == 'agency') {
 
              for ($i=0;$i<$counter;$i++) {
              $queryString = "INSERT INTO pdf_template_agency (template_id, agency_id)
              VALUES";
              $queryString.= "($iNewID1, {$multi[$i]})";
              $this->query($queryString);
 



     }
     }
*/
    
    
	}

    function createCampaign1($multi,$iNewID1,$counter) {
	         

                
    // if($aVars['country'] == 'agency') {
 
              for ($i=0;$i<$counter;$i++) {
              $queryString = "INSERT INTO pdf_template_agency (template_id, agency_id)
              VALUES";
              
              $queryString.= "($iNewID1, {$multi[$i]})";
            //  echo $queryString;
            //  exit();
              $this->query($queryString);
 



     }
    // }

	}


  function getStateArray($sCountry=''){

		$aStates = array();
		$aDbStates = $this->getAll("SELECT * FROM `bx_groups_main`");

		//$aStates[] = '';
		foreach ($aDbStates as $aEachState){
			$sState = $aEachState['title'];
			$sStateCode = $aEachState['id'];

			$aStates[$sStateCode] = $sState;
                        
  		}
		return $aStates;
	}

   function getStateArrayy($tid){
                 $aStates = $this->getStateArray($sCountry);
		 $curvalues = array();
                 $result = mysql_query("SELECT * FROM `pdf_template_agency` where template_id='$tid'");

                 while ($Row = mysql_fetch_array ($result)) {

                  $curvalues[ $Row['agency_id'] ] = $Row['agency_id'];
                        }

		foreach($aStates as $aEachCode=>$aEachState){

                  if(in_array($aEachCode, $curvalues))
                          $sSelected[] = $aEachCode;
                                   }

                  return $sSelected;
	}


   function getStateOptions($sCountry) {
		$aStates = $this->getStateArray($sCountry);

                if($sCountry == 'agency') {

		foreach($aStates as $aEachCode=>$aEachState){
			$sOptions .= "<option value='{$aEachCode}'>{$aEachState}</option>";
		}
                  }
		return $sOptions;
	}

function getStateOptionss($sCountry) {
		$aStates = $this->getStateArray($sCountry);

              //  if($sCountry == 'agency') {
                 $curvalues = array();
                 $result = mysql_query("SELECT * FROM `pdf_template_agency` where template_id='1'");

                 while ($Row = mysql_fetch_array ($result)) {

                  $curvalues[ $Row['agency_id'] ] = $Row['agency_id'];
                        }

		foreach($aStates as $aEachCode=>$aEachState){


                 $sSelected = in_array($aEachCode, $curvalues) ? 'selected="selected"' : '';
                 $sOptions .= "<option value='{$aEachCode}' $sSelected>{$aEachState}</option>";


		}
              //    }
		return $sOptions;
	}



 function updateCampaign($iCid, $aVars){
		$sSql = "UPDATE `pdf_template_master` SET
				`template_name` 				= '{$aVars['campaign_name']}',
                                `template_type` 				= '{$aVars['country']}',
                                 `printMode` 				  = '{$aVars['printmode']}',
				`template_description` 			= '{$aVars['campaign_name1']}'
				 WHERE `template_id` = '{$iCid}' LIMIT 1";

               // echo $sSql;exit();
                                $this->query($sSql);



	}



      function updateCampaign1($multi,$iNewID1,$counter,$ttype) {

           $curvalues = array();
           $result = mysql_query("SELECT * FROM `pdf_template_agency` where template_id='$iNewID1'");
           $i=0;
           while ($Row = mysql_fetch_array ($result)) {

           $curvalues[$i] = $Row['agency_id'];
           $i++;
                        }

           $counter1 = count($curvalues);

           if( $counter1 > 0 || $counter > 0) {

           if($counter1 > $counter) {
           $sSelected = array_diff($curvalues,$multi);
           $this->deleteagecny($sSelected,$iNewID1,$ttype);
            }
           else {
           $sSelected = array_diff($multi,$curvalues);
          
           $this->insertagecny($sSelected,$iNewID1);
                   }  

           }

    	}

    function insertagecny($sSelected,$iNewID1) {

    $counterr = count($sSelected);

     

              
             foreach($sSelected as $k=>$v) {
              $test[] = $v;

              }
              for ($i=0;$i<$counterr;$i++) {
              

             
              $queryStrings = "INSERT INTO pdf_template_agency (template_id, agency_id)
              VALUES";
              $queryStrings.= "($iNewID1, {$test[$i]})";
             // echo $queryStrings;
          // exit();
              $this->query($queryStrings);
                        
                   }


    }

 function deleteagecny($sSelected,$iNewID1,$ttype) {

 if($ttype == 'global'){



           $cdelete = "Delete from  pdf_template_agency  WHERE `template_id` = '{$iNewID1}'";

           $this->query($cdelete);

     }
     
    $counterr = count($sSelected);

     foreach($sSelected as $k=>$v) {
              $test[] = $v;

              }
   
          
           for ($i=0;$i<$counterr;$i++) {
          
           $queryStringss = "Delete from  pdf_template_agency  WHERE `agency_id` = '{$test[$i]}'";
          // echo $queryStringss;
           $this->query($queryStringss);
           }
         

    }

    function deleteCampaigns($aVars){
		foreach($aVars as $k=>$v){
			//$this->query("DELETE FROM `{$this->_sPrefix}campaigns` WHERE `id` ='{$v}'");

                    $sSql = "UPDATE `pdf_template_master` SET
				`isDeleted` = 'Y'
			    WHERE `template_id` = '{$v}' LIMIT 1";

                                $this->query($sSql);
		}
	}

        function disableCampaigns($aVars){
		foreach($aVars as $k=>$v){
			//$this->query("DELETE FROM `{$this->_sPrefix}campaigns` WHERE `id` ='{$v}'");

                    $sSql = "UPDATE `pdf_template_master` SET
				`template_disbale_status` = 'Y'
			    WHERE `template_id` = '{$v}' LIMIT 1";
                           

                                $this->query($sSql);
		}
	}
       
        function getagency(){
		$sSql = "SELECT `id`,`title` FROM `bx_groups_main`";
		$aCampaigns = $this->getAll($sSql);
		if(is_array($aCampaigns)){
			foreach($aCampaigns as $aCampaign) {
				//$aCampaignArray[$aCampaign['id']] = $aCampaign['id'];
                                $aCampaignArray[$aCampaign['title']] = $aCampaign['title'];
			}
		}
		return $aCampaignArray;
	}

        function getMemberships() {
	    $sMethod = "getAll";
         $sSql = "SELECT * FROM `bx_groups_main` ORDER BY `id` ASC";
	   return $this->$sMethod($sSql);
	}

   	

	
}

?>