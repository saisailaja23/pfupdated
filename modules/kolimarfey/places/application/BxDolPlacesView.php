<?php

class BxDolPlacesView extends BxDolPageView  
{	
	var $aPlace;
    var $aPlacePhotos;
    var $aPlaceVideos;
    var $oHtml;
    var $oController;
    var $aForm;
		
	function BxDolPlacesView(&$aPlace, &$aPlacePhotos, &$aPlaceVideos, &$oHtml, &$aForm, &$oController) 
    {
        $this->aPlace = &$aPlace;
        $this->aPlacePhotos = &$aPlacePhotos;
        $this->aPlaceVideos = &$aPlaceVideos;
        $this->oHtml = &$oHtml;
        $this->oController = $oController;
        $this->aForm = &$aForm;		
		parent::BxDolPageView('places_view');
	}
		
	function getBlockCode_Info() 
    {
        ob_start();
        $this->oHtml->info($this->aPlace, $this->aForm);
        $sRet = ob_get_clean();
        return $sRet ? array($sRet) : '';
	}

	function getBlockCode_Photos() 
    {
        ob_start();
        $this->oHtml->photos($this->aPlacePhotos);
        $sRet = ob_get_clean();
        return $sRet ? array($sRet) : '';
	}

	function getBlockCode_Videos() 
    {
        ob_start();
        $this->oHtml->videos($this->aPlaceVideos);
        $sRet = ob_get_clean();
        return $sRet ? array($sRet) : '';
    }

	function getBlockCode_Map() 
    {        
        $sRet = $this->oHtml->mapView($this->aPlace);
        return $sRet ? array($sRet) : '';
	}

	function getBlockCode_Rate() 
    {  	
		$o = new BxTemplVotingView ('places', (int)$this->aPlace['pl_id']);
        if (!$o->isEnabled()) 
            return '';
        return array($o->getBigVoting ());
	}
	
	function getBlockCode_Comments() 
    {		
        $o = new BxTemplCmtsView ('places', (int)$this->aPlace['pl_id']);
        if (!$o->isEnabled()) return '';
        return $o->getCommentsFirst ();
	}
      
	function getBlockCode_Actions() 
    {	         
        ob_start();

        if ($this->oController->isAdministrator())
        {             
            if ($this->aPlace['pl_status'] != 'active')
            {
                $this->oHtml->url('Places Activate', 'activate/'.$this->aPlace['pl_id'], 1, array (), true, 'accept'); 
                echo '<br />';
            }
            if (0 == (int)$this->aPlace['pl_featured'])
            {
                $this->oHtml->url('Places Mark As Featured', 'mark_as_featured/1/'.$this->aPlace['pl_id'], 1, array (), true, 'accept'); 
                echo '<br />';
            } 
            else 
            {
                $this->oHtml->url('Places Remove From Featured', 'mark_as_featured/0/'.$this->aPlace['pl_id'], 1, array (), true, 'accept'); 
                echo '<br />';                
            }            
        }

        if ($this->oController->isAdministrator() || ($GLOBALS['logged']['member'] > 0 && $this->aPlace['pl_author_id'] == $_COOKIE['memberID']))
        {
            $this->oHtml->url('Places Edit Place', 'edit/'.$this->aPlace['pl_id'], 1, array(), true, 'page_edit');
            echo '<br />';
            $this->oHtml->url('Places Manage Photos', 'photos/'.$this->aPlace['pl_id'], 1, array(), true, 'camera');
            echo '<br />';
            $this->oHtml->url('Places Manage Videos', 'videos/'.$this->aPlace['pl_id'], 1, array(), true, 'film');
            echo '<br />';            
            $this->oHtml->url('Places Manage KML Files', 'kml/'.$this->aPlace['pl_id'], 1, array(), true, 'map');
            echo '<br />';                        
            $this->oHtml->url('Places Draw', 'draw/'.$this->aPlace['pl_id'], 1, array(), true, 'paintbrush');
            echo '<br />';                        
            $this->oHtml->url('Places Delete', 'delete/'.$this->aPlace['pl_id'], 1, array ('onclick' => "return confirm('" . $this->oHtml->t('Are you sure?') . "')"), true, 'delete');
        }

        $sRet = ob_get_clean();

        return $sRet ? array($sRet) : '';
	}    

	function getBlockCode_Description() 
    {	        
        return $this->aPlace['pl_desc'] ? array($this->aPlace['pl_desc']) : '';
	}        

	function getBlockCode_Rss() 
    {  	
        $sRet = '';
        if ($_COOKIE['memberID'] == $this->aPlace['pl_author_id'] && !$this->aPlace['pl_rss'] && $this->oController->isAddRssAllowed ()) 
        {
            ob_start();
            $this->oHtml->rss_edit($this->aPlace['pl_id']);
            $sRet = ob_get_clean();            
        } 
        elseif ($this->aPlace['pl_rss']) 
        {
            ob_start();
            $a = explode ('#', $this->aPlace['pl_rss']);
            $iNum = is_array($a) && isset($a[1]) && (int)$a[1] > 0 ? (int)$a[1] : 4;
            $this->oHtml->rss_view($this->aPlace['pl_id'], $iNum);
            $sRet = ob_get_clean();
        } 
        
        if ($_COOKIE['memberID'] == $this->aPlace['pl_author_id'] && $this->oController->isAddRssAllowed ()) 
        {
            $aDBTopMenu[$this->oController->t('Places Edit RSS')] = array('href' => "javascript: $('div.kRSSAggrCont').kRSSForm(); void(0);", 'active' => false);
            return array ($sRet, $aDBTopMenu, '', 1);
        } 
        else
        {
            return $sRet;
        }
	}
}

?>
