<?php

class BxDolPlacesIndex extends BxDolPageView  
{	
    var $oHtml;
    var $oController;

	function BxDolPlacesIndex(&$oHtml, &$oController) 
    {
        $this->oHtml = &$oHtml; 
        $this->oController = &$oController;
		parent::BxDolPageView('places_index');
	}
	
	function getBlockCode_Map() 
    {        
        return array($this->oHtml->mapIndex());
	}

	function getBlockCode_Latest() 
    {  	
        ob_start();
        $this->oController->_browseLatest();
        return array(ob_get_clean());
	}

	function getBlockCode_Best() 
    {  	
        ob_start();
        $this->oController->_browseBest();
        return array(ob_get_clean());
	}    

	function getBlockCode_Featured() 
    {  	
        ob_start();
        $this->oController->_browseFeatured();
        return array(ob_get_clean());
    }        

}

?>
