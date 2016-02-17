<?php
bx_import('BxDolConfig');

class YnTourGuideConfig extends BxDolConfig {
	
	/*
	* Constructor.
	*/
	function YnTourGuideConfig($aModule) {	
		parent::BxDolConfig($aModule);
        $this->_aPositions = array(
            'tl' => 'Top Left',
            'tc' => 'Top Center',
            'tr' => 'Top Right',
            'bl' => 'Bottom Left',
            'bc' => 'Bottom Center',
            'br' => 'Bottom Right',
            'rt' => 'Right Top',
            'rc' => 'Right Center',
            'rb' => 'Right Bottom',
            'lt' => 'Left Top',
            'lc' => 'Left Center',
            'lb' => 'Left Bottom'
        );
	}
}