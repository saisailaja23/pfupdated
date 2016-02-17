<?
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolModuleDb');
bx_import('BxDolAlerts');

class HmAffProDb extends BxDolModuleDb {
    var $_oConfig;
	function HmAffProDb(&$oConfig) {
		parent::BxDolModuleDb();
        $this->_sPrefix = $oConfig->getDbPrefix();
		$this->_oConfig = &$oConfig;
		$this->_BannersDir = BX_DIRECTORY_PATH_MODULES.'harvest/affiliates/images/banners/';
    }
	//-------------------------------- MISC -----------------------------------//
	function getSettingsCategory($sCat){
        return $this->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `Name` = '{$sCat}'");
	}
	function getCountriesArray(){
		$sSql = "SELECT `Value`,`LKey` FROM `sys_pre_values` WHERE `Key`='Country'";		
		$aCountries = $this->getAll($sSql);
		foreach($aCountries as $aCountry){
			$aCountryInputs[$aCountry['Value']] = _t($aCountry['LKey']);
		}
		return $aCountryInputs;
	}
	function getTimeStamp($sDateTime){
		if($sDateTime){
			list($sDate, $sTime) = explode(' ', $sDateTime);
			list($iYear, $iMonth, $iDay) = explode('-', $sDate);
			list($iHour, $iMin, $iSec) = explode(':', $sTime);	
			$sResult = mktime($iHour, $iMin, $iSec, $iMonth, $iDay, $iYear);	
		}		
		return $sResult;
	}
	function setUserReferralData($iId,$sApTrack){
		$sSql = "UPDATE `Profiles` SET `ap_data` = '{$sApTrack}' WHERE `ID` = '{$iId}'";
		return $this->query($sSql);
	}
	function getMembershipInfo($iMembershipID){
		$aLevl = $this->getRow("SELECT * FROM `sys_acl_levels` WHERE `ID` = '{$iMembershipID}'");
		$aLevlPrice = $this->getRow("SELECT * FROM `sys_acl_level_prices` WHERE `IDLevel` = '{$iMembershipID}'");		
		return array_merge($aLevl,$aLevlPrice);
	}
	function getUsernameList(){
		$sSql = "SELECT `NickName` FROM `Profiles` WHERE `ID` > '0'";
		$aProfiles = $this->getAll($sSql);
		foreach($aProfiles as $aProfile){
			$aNickNames[] = $aProfile['NickName'];
		}
		return $aNickNames;
	}
	//-------------------------------- AFFILIATES -----------------------------------//
	function getAffiliates(){
		$sSql = "SELECT * FROM `{$this->_sPrefix}affiliates`";			
		return $this->getAll($sSql);
	}
	function getAffiliatesCount($sType){
		if($sType == 'total'){
			$sWhere = '';
		}else{
		 	$sWhere = "WHERE `status` = '{$sType}'";
		}	
		$sSql = "SELECT * FROM `{$this->_sPrefix}affiliates` {$sWhere}";
		$aAffiliates = $this->getAll($sSql);
		return count($aAffiliates);
	}
	function isAffiliate($iId){
        $sSql = "SELECT `id` FROM `{$this->_sPrefix}affiliates` WHERE `user_id`='{$iId}' LIMIT 1";		
		$iAffId = $this->getOne($sSql);
		if($iAffId){
			return $iAffId;
		}
		return false;
	}
	function approveAffiliate($aVars){
		foreach($aVars as $k=>$v){
			$this->query("UPDATE `{$this->_sPrefix}affiliates` SET `status` = 'active' WHERE `id` ='{$v}'");
		}
	}
	function getAffiliate($iId){
		$sSql = "SELECT * FROM `{$this->_sPrefix}affiliates` WHERE `id` = '{$iId}' LIMIT 1";
		return $this->getRow($sSql);
	}
	function getAffiliateInfo($iId){
		$sSql = "SELECT * FROM `{$this->_sPrefix}affiliates` WHERE `user_id` = '{$iId}' LIMIT 1";
		return $this->getRow($sSql);
	}
	function getAffiliateCampaigns($iAid){
		$sSql = "SELECT `campaigns` FROM `{$this->_sPrefix}affiliates` WHERE `id` = '{$iAid}' LIMIT 1";
		$aAffiliateCampaigns = $this->getOne($sSql);
		return unserialize($aAffiliateCampaigns);
	}
	function getAffiliateCampaignsFromUserId($iId){
		$sSql = "SELECT `campaigns` FROM `{$this->_sPrefix}affiliates` WHERE `user_id` = '{$iId}' LIMIT 1";
		$aAffiliateCampaigns = $this->getOne($sSql);
		return unserialize($aAffiliateCampaigns);
	}
	function updateAffiliateCampaigns($iAid, $aCampaignList){
		$aCampaignList = serialize($aCampaignList);
		$sSql = "UPDATE `{$this->_sPrefix}affiliates` SET `campaigns` = '{$aCampaignList}' WHERE `id` = '{$iAid}'";
		return $this->query($sSql);
	}
	function getAffiliateCommissions($iAid){
		$sSql = "SELECT SUM(`amount`) FROM `{$this->_sPrefix}commissions` WHERE `affiliate_id` = '{$iAid}' AND `approved` = 'approved'";
		$iCommissions = $this->getOne($sSql);
		if(!$iCommissions)
			return '0';
		return $iCommissions;
	}
	function getAffiliateDataFromUserId($iUserId){
		$aAffiliate = $this->getAffiliateInfo($iUserId);
		$aImp = $this->getAll("SELECT * FROM `{$this->_sPrefix}impressions` WHERE `affiliate_id` = '{$aAffiliate['id']}'");
		$aClicks = $this->getAll("SELECT * FROM `{$this->_sPrefix}clicks` WHERE `affiliate_id` = '{$aAffiliate['id']}'");
		$iPaidCom = $this->getOne("SELECT SUM(`amount`)  FROM `{$this->_sPrefix}commissions` WHERE `affiliate_id` = '{$aAffiliate['id']}' AND `status` = 'paid'");
		$iUnpaidCom = $this->getOne("SELECT SUM(`amount`) FROM `{$this->_sPrefix}commissions` WHERE `affiliate_id` = '{$aAffiliate['id']}' AND `status` = 'unpaid'");
		$aAffiliateData = array(
			'imp' => count($aImp),
			'clicks' => count($aClicks),
			'paid' => $iPaidCom,
			'unpaid' => $iUnpaidCom,
		);
		return $aAffiliateData;
	}
	function getAffiliateDetails($iAid, $sFilter=''){
		$sWhereExtra = '';
		if($sFilter != ''){
			$sWhereExtra = $this->getWhereExtra($sFilter);
		}
		$aImpRaw = $this->getAll("SELECT * FROM `{$this->_sPrefix}impressions` WHERE `affiliate_id` = '{$iAid}' AND `raw` = '1' {$sWhereExtra}");
		$aImpUnique = $this->getAll("SELECT * FROM `{$this->_sPrefix}impressions` WHERE `affiliate_id` = '{$iAid}' AND `unique` = '1' {$sWhereExtra}");
		$aClicksRaw = $this->getAll("SELECT * FROM `{$this->_sPrefix}clicks` WHERE `affiliate_id` = '{$iAid}' AND `raw` = '1' {$sWhereExtra}");
		$aClicksUnique = $this->getAll("SELECT * FROM `{$this->_sPrefix}clicks` WHERE `affiliate_id` = '{$iAid}' AND `unique` = '1' {$sWhereExtra}");
		$aComTrans = $this->getAll("SELECT * FROM `{$this->_sPrefix}commissions` WHERE `affiliate_id` = '{$iAid}' {$sWhereExtra}");
		$iComEarned = $this->getOne("SELECT SUM(`amount`)  FROM `{$this->_sPrefix}commissions` WHERE `affiliate_id` = '{$iAid}' AND `approved` = 'approved' {$sWhereExtra}");
		$aAffiliateDetails = array(
			'imp_raw' => count($aImpRaw),
			'imp_uni' => count($aImpUnique),
			'cli_raw' => count($aClicksRaw),
			'cli_uni' => count($aClicksUnique),
			'com_trans' => count($aComTrans),
			'com_earned' => $iComEarned
		);
		return $aAffiliateDetails;		
	}
	function getWhereExtra($sFilter){
		$iThisMonth = date('n');
		$iLastMonth = ($iThisMonth == '1') ? '12' : $iThisMonth-1;
		$iThisYear = date('Y');
		$iLastYear = $iThisYear-1;
		switch($sFilter){
			case 'total':
				$sExtra = '';
			break;
			case 'last_seven_days':
				$sExtra = 'AND `date` > DATE_SUB(curdate(), INTERVAL 1 WEEK)';
			break;
			case 'last_thirty_days':
				$sExtra = 'AND `date` > DATE_SUB(curdate(), INTERVAL 1 MONTH)';
			break;
			case 'this_month':
				$sExtra = "AND MONTH(`date`) = '{$iThisMonth}'";
			break;
			case 'last_month':
				$sExtra = "AND MONTH(`date`) = '{$iLastMonth}'";
			break;
			case 'this_year':
				$sExtra = "AND YEAR(`date`) = '{$iThisYear}'";
			break;
			case 'last_year':
				$sExtra = "AND YEAR(`date`) = '{$iLastYear}'";
			break;
		}
		return $sExtra;
	}
	function registerAffiliate($iId,$aVars){
		$aDefaultCampaign = serialize(array('1'));
		$sAutoApprove = getParam('dol_aff_auto_approve');
		$sStatus = ($sAutoApprove == 'on') ? 'active' : 'pending';
		$sSql = "INSERT INTO `{$this->_sPrefix}affiliates` SET
				`user_id` 			= '{$iId}',
				`first_name` 		= '{$aVars['first_name']}',
				`last_name` 		= '{$aVars['last_name']}',
				`user_email` 		= '{$aVars['user_email']}',
				`address1` 			= '{$aVars['address1']}',
				`address2` 			= '{$aVars['address2']}',
				`city` 				= '{$aVars['city']}',
				`state` 			= '{$aVars['state']}',
				`country` 			= '{$aVars['country']}',
				`zip` 				= '{$aVars['zip']}',
				`payout_preference` = '{$aVars['payout_preference']}',
				`paypal_email` 		= '{$aVars['paypal_email']}',
				`date_start` 		= NOW(),
				`status` 			= '{$sStatus}',
				`campaigns`			= '{$aDefaultCampaign}'";
	 	$this->query($sSql);

		if($sAutoApprove == 'on'){
			$iLastInsert = mysql_insert_id();
			$iAffiliate = array($iLastInsert);			
			$oZ = new BxDolAlerts('affiliates', 'approve', '', $iId, $iAffiliate);
			$oZ->alert();
		}
	}
	function updateAffiliate($iId,$aVars){
		$sSql = "UPDATE `{$this->_sPrefix}affiliates` SET
				`first_name` 		= '{$aVars['first_name']}',
				`last_name` 		= '{$aVars['last_name']}',
				`user_email` 		= '{$aVars['user_email']}',
				`address1` 			= '{$aVars['address1']}',
				`address2` 			= '{$aVars['address2']}',
				`city` 				= '{$aVars['city']}',
				`state` 			= '{$aVars['state']}',
				`country` 			= '{$aVars['country']}',
				`zip` 				= '{$aVars['zip']}',
				`payout_preference` = '{$aVars['payout_preference']}',
				`paypal_email` 		= '{$aVars['paypal_email']}'
				WHERE `user_id` = '{$iId}' LIMIT 1";
		return $this->query($sSql);
	}
	function deleteAffiliates($aAids){
		foreach($aAids as $k=>$v){
			$this->query("DELETE FROM `{$this->_sPrefix}affiliates` WHERE `id` ='{$v}'");
		}
	}
	//-------------------------------- CAMPAIGNS -----------------------------------//
	function getCampaigns(){
		$sSql = "SELECT * FROM `{$this->_sPrefix}campaigns`";			
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
		$sSql = "SELECT * FROM `{$this->_sPrefix}campaigns` WHERE `id` = '{$iCid}' LIMIT 1";			
		return $this->getRow($sSql);
	}
	function getCampaignNameById($iCid){
		$sSql = "SELECT `name` FROM `{$this->_sPrefix}campaigns` WHERE `id` = '{$iCid}' LIMIT 1";			
		return $this->getOne($sSql);
	}
	function createCampaign($aVars){
		$sSql = "INSERT INTO `{$this->_sPrefix}campaigns` SET
				`name` 				= '{$aVars['campaign_name']}',
				`status` 			= '{$aVars['campaign_status']}',
				`click_commission` 	= '{$aVars['click_commission']}',
				`click_amount` 		= '{$aVars['click_amount']}',
				`join_commission` 	= '{$aVars['join_commission']}',
				`join_amount` 		= '{$aVars['join_amount']}',
				`membership_commission` = '{$aVars['membership_commission']}',
				`membership_amount` 	= '{$aVars['membership_amount']}'";
		$this->query($sSql);	
	}
	function updateCampaign($iCid, $aVars){
		$sSql = "UPDATE `{$this->_sPrefix}campaigns` SET
				`name` 				= '{$aVars['campaign_name']}',
				`status` 			= '{$aVars['campaign_status']}',
				`click_commission` 	= '{$aVars['click_commission']}',
				`click_amount` 		= '{$aVars['click_amount']}',
				`join_commission` 	= '{$aVars['join_commission']}',
				`join_amount` 		= '{$aVars['join_amount']}',
				`membership_commission` = '{$aVars['membership_commission']}',
				`membership_amount` 	= '{$aVars['membership_amount']}'
				WHERE `id` = '{$iCid}' LIMIT 1";
		$this->query($sSql);
	}
	function deleteCampaigns($aVars){
		foreach($aVars as $k=>$v){
			$this->query("DELETE FROM `{$this->_sPrefix}campaigns` WHERE `id` ='{$v}'");
		}
	}
	//-------------------------------- BANNERS -----------------------------------//
	function getBanners(){
		$sSql = "SELECT * FROM `{$this->_sPrefix}banners`";			
		return $this->getAll($sSql);
	}
	function getBannerInfo($iBid){
		$sSql = "SELECT * FROM `{$this->_sPrefix}banners` WHERE `id` = '{$iBid}' LIMIT 1";			
		return $this->getRow($sSql);
	}
	function getBannersForCampaign($iCid){
		$sSql = "SELECT * FROM `{$this->_sPrefix}banners` WHERE `campaign_id` = '{$iCid}'";			
		return $this->getAll($sSql);
	}
	function createTextBanner($aVars){
		$sSql = "INSERT INTO `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['link_name']}',
				`hidden` 			= '{$aVars['link_hidden']}',
				`campaign_id` 		= '{$aVars['link_campaign']}',
				`target_url` 		= '{$aVars['link_target']}',
				`type` 				= 'text',
				`text_title` 		= '{$aVars['link_title']}',
				`text_details`		= '{$aVars['link_details']}'";
		$this->query($sSql);	
	}

	function createImageBanner($sFileName,$aVars){		
		$sSql = "INSERT INTO `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['image_name']}',
				`hidden` 			= '{$aVars['image_hidden']}',
				`campaign_id` 		= '{$aVars['image_campaign']}',
				`target_url` 		= '{$aVars['image_target']}',
				`type` 				= 'image',
				`filename` 			= '{$sFileName}'";
		$this->query($sSql);	
	}
	function createFlashBanner($sFileName,$aVars){		
		$sSql = "INSERT INTO `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['flash_name']}',
				`hidden` 			= '{$aVars['flash_hidden']}',
				`campaign_id` 		= '{$aVars['flash_campaign']}',
				`target_url` 		= '{$aVars['flash_target']}',
				`type` 				= 'flash',
				`filename` 			= '{$sFileName}'";
		$this->query($sSql);	
	}
	function updateTextBanner($iBid, $aVars){
		$sSql = "UPDATE `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['link_name']}',
				`hidden` 			= '{$aVars['link_hidden']}',
				`campaign_id` 		= '{$aVars['link_campaign']}',
				`target_url` 		= '{$aVars['link_target']}',
				`type` 				= 'text',
				`text_title` 		= '{$aVars['link_title']}',
				`text_details`		= '{$aVars['link_details']}'
				WHERE `id` = '{$iBid}' LIMIT 1";
		$this->query($sSql);	
	}
	function updateImageBanner($iBid,$sFileName, $aVars){
		$sSql = "UPDATE `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['image_name']}',
				`hidden` 			= '{$aVars['image_hidden']}',
				`campaign_id` 		= '{$aVars['image_campaign']}',
				`target_url` 		= '{$aVars['image_target']}',";				
		if($sFileName){
			$sSql.= "`type` 		= 'image',
				`filename` 			= '{$sFileName}'";
		}else{
			$sSql.= "`type` 		= 'image' ";
		}
		$sSql.= " WHERE `id` = '{$iBid}' LIMIT 1";
		$this->query($sSql);	
	}
	function updateFlashBanner($iBid,$sFileName, $aVars){
		$sSql = "UPDATE `{$this->_sPrefix}banners` SET
				`name` 				= '{$aVars['flash_name']}',
				`hidden` 			= '{$aVars['flash_hidden']}',
				`campaign_id` 		= '{$aVars['flash_campaign']}',
				`target_url` 		= '{$aVars['flash_target']}',";				
		if($sFileName){
			$sSql.= "`type` 		= 'flash',
				`filename` 			= '{$sFileName}'";
		}else{
			$sSql.= "`type` 		= 'flash' ";
		}
		$sSql.= " WHERE `id` = '{$iBid}' LIMIT 1";
		$this->query($sSql);	
	}
	function deleteBanners($aVars){
		foreach($aVars as $k=>$v){
			$this->query("DELETE FROM `{$this->_sPrefix}banners` WHERE `id` ='{$v}'");
		}
	}
	//----------------------------- IMPRESSIONS  ---------------------------//
	function getImpressionsCount($sType){
		$iMonth = date('n');
		if($sType == 'total'){
			$sWhere = '';
		}else{
		 	$sWhere = "WHERE MONTH(`date`) = '{$iMonth}'";
		}	
		$sSql = "SELECT * FROM `{$this->_sPrefix}impressions` {$sWhere}";
		$aImpressions = $this->getAll($sSql);
		return count($aImpressions);
	}
	function trackImpression($aVars){
		$rIPCheck = db_arr("SELECT `banner_id`,`ip` FROM `{$this->_sPrefix}impressions` WHERE `ip` = '{$aVars['ip']}' AND `banner_id` = '{$aVars['bid']}' LIMIT 1");
		$iCountIP = count($rIPCheck);
		if($iCountIP == '0'){
			$sSql = "INSERT INTO `{$this->_sPrefix}impressions` SET 
					`campaign_id` 	= '{$aVars['cid']}',
					`affiliate_id` 	= '{$aVars['aid']}',
					`banner_id` 	= '{$aVars['bid']}',
					`unique` 		= '1',
					`ip` 			= '{$aVars['ip']}'";	
		}else{
			$sSql = "INSERT INTO `{$this->_sPrefix}impressions` SET 
					`campaign_id` 	= '{$aVars['cid']}',
					`affiliate_id` 	= '{$aVars['aid']}',
					`banner_id` 	= '{$aVars['bid']}',
					`raw` 			= '1',
					`ip` 			= '{$aVars['ip']}'";
		}
		$this->query($sSql);
		return true;
	}
	//----------------------------- CLICKS  ---------------------------//
	function getClicksCount($sType){
		$iMonth = date('n');
		if($sType == 'total'){
			$sWhere = '';
		}else{
		 	$sWhere = "WHERE MONTH(`date`) = '{$iMonth}'";
		}	
		$sSql = "SELECT * FROM `{$this->_sPrefix}clicks` {$sWhere}";
		$aClicks = $this->getAll($sSql);
		return count($aClicks);
	}

	function trackClick($aVars){
		$rIPCheck = db_arr("SELECT `banner_id`,`ip` FROM `{$this->_sPrefix}clicks` WHERE `ip` = '{$aVars['ip']}' AND `banner_id` = '{$aVars['bid']}' LIMIT 1");
		$iCountIP = count($rIPCheck);
		if($iCountIP == '0'){
			$sSql = "INSERT INTO `{$this->_sPrefix}clicks` SET 
					`campaign_id` 	= '{$aVars['cid']}',
					`affiliate_id` 	= '{$aVars['aid']}',
					`banner_id` 	= '{$aVars['bid']}',
					`unique` 		= '1',
					`ip` 			= '{$aVars['ip']}'";	
		}else{
			$sSql = "INSERT INTO `{$this->_sPrefix}clicks` SET 
					`campaign_id` 	= '{$aVars['cid']}',
					`affiliate_id` 	= '{$aVars['aid']}',
					`banner_id` 	= '{$aVars['bid']}',
					`raw` 			= '1',
					`ip` 			= '{$aVars['ip']}'";
		}
		$this->query($sSql);
		return true;
	}
	function isUniqueClick($aVars){
		$iRaw = db_value("SELECT `raw` FROM `{$this->_sPrefix}clicks` WHERE `ip` = '{$aVars['ip']}' AND `banner_id` = '{$aVars['bid']}' ORDER BY `date` DESC LIMIT 1");
		if($iRaw == '1'){
			return false;
		}
		return true;
	}
	//----------------------------- PAYOUTS  ---------------------------//
	function getAvailablePayouts(){
		$sMinPayout = getParam('dol_aff_min_payout');
		$sSql =	"SELECT `affiliate_id`, SUM(`amount`) `total_amount`
				FROM `{$this->_sPrefix}commissions` 
				WHERE `status` = 'unpaid'
				GROUP BY `affiliate_id`
				HAVING `total_amount` > '{$sMinPayout}'";
		
		return $this->getAll($sSql);
	}
	function processNewPayout($aVars){
		foreach($aVars['aid'] as $iAid){
			$this->setAffiliateCommissionsPaid($iAid);
		}
		foreach($aVars['amount'] as $iAid=>$iAmount){
			$aAffiliate = $this->getAffiliate($iAid);
			$sSql = "INSERT INTO `{$this->_sPrefix}payouts` SET 
					`affiliate_id` = '{$iAid}',
					`amount` = '{$iAmount}',
					`date` = NOW()";
			$this->query($sSql);
		}
		// Send an email invoice.
	}
	function getPayoutsHistory(){
		$sSql = "SELECT * FROM `{$this->_sPrefix}payouts`";			
		return $this->getAll($sSql);
	}
	//--------------------------- COMMISSIONS ---------------------------//
	function getCommissions($sFilter = ''){
		if($sFilter){
			$sSql =	"SELECT `{$this->_sPrefix}commissions`.* FROM `{$this->_sPrefix}commissions`
			INNER JOIN `{$this->_sPrefix}affiliates` ON `{$this->_sPrefix}affiliates`.`id` = `{$this->_sPrefix}commissions`.`affiliate_id`
			INNER JOIN `Profiles` ON `Profiles`.`ID` = `{$this->_sPrefix}affiliates`.`user_id`
			WHERE `Profiles`.`NickName` like '%".mysql_real_escape_string($sFilter)."%'
			AND `{$this->_sPrefix}commissions`.`status` = 'unpaid'";
		}else{
			$sSql = "SELECT * FROM `{$this->_sPrefix}commissions` WHERE `status` = 'unpaid'";
		}
		$aCommissions =	$this->getAll($sSql);
		return $aCommissions;
	}
	function getCommissionsCount($sType){
		$iMonth = date('n');
		if($sType == 'total'){
			$sWhere = '';
		}else{
		 	$sWhere = "WHERE MONTH(`date`) = '{$iMonth}'";
		}	
		$sSql = "SELECT * FROM `{$this->_sPrefix}commissions` {$sWhere}";
		$aCommissions = $this->getAll($sSql);
		return count($aCommissions);
	}
	function isUniqueMembershipCommission($aVars){
		$aCom = db_arr("SELECT `user_id`,`txn_id` FROM `{$this->_sPrefix}commissions` 
				WHERE `affiliate_id` = '{$aVars['aid']}' 
				AND `banner_id` = '{$aVars['bid']}' 
				AND `user_id` = '{$aVars['user_id']}' 
				AND `txn_id` = '{$aVars['txn_id']}' 
				ORDER BY `date` DESC LIMIT 1");
		if(count($aCom) > '0'){
			return false;
		}
		return true;
	}
	function isUniqueJoinCommission($aVars){
		$aJoin = db_arr("SELECT `user_id` FROM `{$this->_sPrefix}commissions` 
				WHERE `affiliate_id` = '{$aVars['aid']}' 
				AND `banner_id` = '{$aVars['bid']}' 
				AND `user_id` = '{$aVars['user_id']}' 
				AND `type` = 'join' 
				AND `ip` = '{$aVars['ip']}' 
				ORDER BY `date` DESC LIMIT 1");
		if(count($aJoin) > '0'){
			return false;
		}
		return true;
	}
	function trackCommission($aVars){
		$sApproved = (getParam('dol_aff_auto_approve_com') == 'on') ? 'approved' : 'pending';
		$sSql = "INSERT INTO `{$this->_sPrefix}commissions` SET 
				`campaign_id` 	= '{$aVars['cid']}',
				`affiliate_id` 	= '{$aVars['aid']}',
				`banner_id` 	= '{$aVars['bid']}',
				`type` 			= '{$aVars['type']}',
				`user_id` 		= '{$aVars['user_id']}',
  				`txn_id` 		= '{$aVars['txn_id']}',
				`amount` 		= '{$aVars['amount']}',
				`approved`		= '{$sApproved}',
				`status`		= 'unpaid',
				`ip`			= '{$aVars['ip']}'";
		if($aVars['type'] == 'click' && $this->isUniqueClick($aVars)){
			$this->query($sSql);
		}
		if($aVars['type'] == 'join' && $this->isUniqueJoinCommission($aVars) == true){
			$this->query($sSql);
		}
		if($aVars['type'] == 'membership' && $this->isUniqueMembershipCommission($aVars) == true){
			// To Do: If recurring commision enabled for campaign
			$this->query($sSql);
		}
	}
	function approveCommissions($aVars){
		foreach($aVars as $k=>$v){
			$this->query("UPDATE `{$this->_sPrefix}commissions` SET `approved` = 'approved' WHERE `id` ='{$v}'");
		}
	}
	function declineCommissions($aVars){
		foreach($aVars as $k=>$v){
			$this->query("UPDATE `{$this->_sPrefix}commissions` SET `approved` = 'declined' WHERE `id` ='{$v}'");
		}
	}
	function setAffiliateCommissionsPaid($iAid){
		$this->query("UPDATE `{$this->_sPrefix}commissions` SET `status` = 'paid' WHERE `affiliate_id` ='{$iAid}'");
	}
}

?>