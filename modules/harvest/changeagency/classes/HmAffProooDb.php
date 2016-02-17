<?
bx_import('BxDolModuleDb');

class HmAffProooDb extends BxDolModuleDb {
        var $_oConfig;
	function HmAffProooDb(&$oConfig) {
		parent::BxDolModuleDb();
        
    }
	
	function createCampaign($aVars) {
	       
              $multi = $aVars['state'];
              $tcountry = $aVars['tcountry'];
              
              foreach($multi as $k=>$v){

          
             $sqlcouple = "SELECT Couple FROM `Profiles` where ID='$v'";
             $result = mysql_query($sqlcouple);
             $Row = mysql_fetch_array ($result);
              
             $vv = $Row['Couple'];
              

              $sSql = "UPDATE `Profiles` SET
		       AdoptionAgency = '$tcountry'
		       WHERE `ID` IN('{$v}','{$vv}')";





             //echo  $sSql;
             // exit();
                    
              $sSqlc = "UPDATE `Profiles_draft` SET
			AdoptionAgency = '$tcountry'
		       WHERE `ID` IN('{$v}','{$vv}')";
                          
              $sSqlfans = "UPDATE `bx_groups_fans` SET
			    id_entry = '$tcountry'
			    WHERE `id_profile` = '{$v}'";


                $this->query($sSql);
                $this->query($sSqlc);
                $this->query($sSqlfans);
                
		}           
    
	}
   
  function getStateArray($sCountry=''){

		$aStates = array();
		$aDbStates = $this->getAll("SELECT `Profiles`.`ID` , `Profiles`.`NickName`, `Profiles`.`Adoptionagency`
                 FROM `Profiles`
                 WHERE `Profiles`.`ProfileType` =2 AND `Profiles`.`Adoptionagency` = '$sCountry'
                 AND (
                `Profiles`.`Couple` =0
                 OR `Profiles`.`Couple` > `Profiles`.`ID`
                 ) ORDER BY `Profiles`.`NickName`");

		foreach ($aDbStates as $aEachState){
			$sState = $aEachState['NickName'];
			$sStateCode = $aEachState['ID'];

			$aStates[$sStateCode] = $sState;
                        
  		}
		return $aStates;
	}

   function getStateArrayy($tid){
                $aStates = array();
		$aDbStates = $this->getAll("SELECT * FROM `bx_groups_main` ORDER BY  `title` ASC");
              $aStates[0] .= 'Select an Agency';   
		//$aStates[] = '';
		foreach ($aDbStates as $aEachState){
			$sState = $aEachState['title'];
			$sStateCode = $aEachState['id'];

			$aStates[$sStateCode] = $sState;

  		}
		return $aStates;
	}


   function getStateOptions($sCountry) {
		$aStates = $this->getStateArray($sCountry);
 		foreach($aStates as $aEachCode=>$aEachState){
                   $sOptions .= "<option value='{$aEachCode}'>{$aEachState}</option>";
		}
            
		return $sOptions;
	}

}

?>