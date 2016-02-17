<?
/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

bx_import('BxDolPageViewAdmin');

class AqbPCPageViewAdmin extends BxDolPageViewAdmin{
	var $sPageBulder, $oMain;
	function AqbPCPageViewAdmin($sDBTable, $sCacheFile, $oMain = null) {
		$_REQUEST['Page'] = 'profile';
		$this -> _oMain = $oMain;
		$oMain -> _oDb -> checkForExisting(0);
		
		$GLOBALS['oAdmTemplate']->addJsTranslation(array(
        	'_adm_pbuilder_Reset_page_warning',
        	'_adm_pbuilder_Column_non_enough_width_warn',
        	'_adm_pbuilder_Column_delete_confirmation',
        	'_adm_pbuilder_Add_column',
        	'_adm_pbuilder_Want_to_delete',
			'_adm_btn_Column',
			
        ));
        $GLOBALS['oAdmTemplate']->addJsImage(array(
        	'pb_delete_column' => 'pb_cross.gif'
        ));

        $this -> sDBTable = $sDBTable;
		$this -> sCacheFile = $sCacheFile;
        
        // special actions (without creating page)
        if (isset($_REQUEST['action_sys'])) {
            switch ($_REQUEST['action_sys']) {
                case 'loadNewPageForm':
                    echo $this -> getCssCode();
                    echo $this -> showNewPageForm();
                break;
                
                case 'createNewPage':
                    echo $this->createUserPage();
                break;
            }
            exit;
        }
        
		$sPage = 'profile';
		
		$this -> getPages();
		
		if (strlen($sPage) && in_array($sPage, $this->aPages)) {
            $this->oPage = new AqbPCPVAPage( $sPage, $this );
		}
        
		$this -> checkAjaxMode();
		if( !empty($_REQUEST['action']) and $this -> oPage ) {
			$this -> sPage_db = addslashes( $this -> oPage -> sName );
			
			switch( $_REQUEST['action'] ) {
				case 'load':
					header( 'Content-type:text/javascript' );
					send_headers_page_changed();
					echo $this -> oPage -> getJSON();
					break;
				
				case 'saveColsWidths':
					if( is_array( $_POST['widths'] ) ) {
						$this -> saveColsWidths( $_POST['widths'] );
						$this -> createCache();
					}
					break;
				
				case 'saveBlocks':
					if( is_array( $_POST['columns'] ) ) {
						$this -> saveBlocks( $_POST['columns'] );
					}
					break;
				
				case 'loadEditForm':
					if( $iBlockID = (int)$_POST['id'] ) {
                        header( 'Content-type:text/html;charset=utf-8' );
						echo $this -> getCssCode();
						echo $this -> showPropForm( $iBlockID );
					}
					break;
				
				case 'saveItem':
					if( (int)$_POST['id'] ) {
						$this -> saveItem( $_POST );
						$this -> createCache();
					}
					break;
				
				case 'deleteBlock':
					if( $iBlockID = (int)$_REQUEST['id'] ) {
						$this -> deleteBlock( $iBlockID );
						$this -> createCache();
					}
					break;
				
				case 'checkNewBlock':
					if( $iBlockID = (int)$_REQUEST['id'] )
						$this -> checkNewBlock( $iBlockID );
					break;
				
				case 'savePageWidth':
					if( $sPageWidth = process_pass_data( $_POST['width'] ) ) {
						$this -> savePageWidth( $sPageWidth );
						$this -> createCache();
						
						if( $this -> oPage -> sName == 'index' ) {
							if( $sPageWidth == '100%' )
								setParam( 'promoWidth', '998' );
							else
								setParam( 'promoWidth', (int)$sPageWidth );
							
							ResizeAllPromos();
						}
					}
					break;
				
				case 'saveOtherPagesWidth':
					if( $sWidth = $_REQUEST['width'] ) {
						setParam( 'main_div_width', $sWidth );
						echo 'OK';
					}
					break;
				
				case 'resetPage':
					$this -> resetPage();
					$this -> createCache();
					break;
			}
		}
		if($this -> bAjaxMode)
			exit;

		$sMainPageContent = $this -> showBuildZone();
		$GLOBALS['oAdmTemplate']->addJs(array('jquery.ui.core.min.js', 'jquery.ui.widget.min.js', 'jquery.ui.mouse.min.js', 'jquery.ui.sortable.min.js', 'jquery.ui.slider.min.js'));
		$GLOBALS['oAdmTemplate']->addCss(array('pageBuilder.css'));
		$this -> sPageBulder = $sMainPageContent;
	}
	
	function showBuildZone() {
        return $GLOBALS['oAdmTemplate'] -> parseHtmlByName('pbuilder_content.html', array(
            'selector' => $this->getPageSelector(),
            'bx_if:page' => array(
                'condition' => (bool)$this -> oPage,
                'content' => array(
                    'bx_if:view_link' => array(
                        'condition' => !$this -> oPage-> isSystem,
                        'content' => array(
                            'site_url' => $GLOBALS['site']['url'],
                            'page_name' => htmlspecialchars($this->oPage->sName)
                        ),
					 ),
                    'bx_if:delete_link' => array(
						'condition' => false,						
					 ),
					'parser_url' => BX_DOL_URL_ROOT . $this -> _oMain ->_oConfig->getBaseUri() . 'administration/page',
                    'page_name' => addslashes($this->oPage->sName),
                    'page_width_min' => getParam('sys_template_page_width_min'),
                    'page_width_max' => getParam('sys_template_page_width_max'),
                    'page_width' => $this->oPage->iPageWidth,
                    'main_width' => getParam('main_div_width')
                )
            ),
        ));
	}
	
	function saveBlocks( $aColumns ) {
		$aBlocksDefaultCopy = $aBlocksDefault = $this -> _oMain -> _oDb -> getProfileBlocksAsArray(0);
		
		$aArrayTmp = array();
		foreach( $aColumns as $sBlocks ) {
			$iColCounter ++;
			
			$aBlocks = explode( ',', $sBlocks );
			foreach( $aBlocks as $iOrder => $iBlockID ) {
				$aId = $this -> _oMain -> _oDb -> getBlockIdByPost($iBlockID);
			
				if (isset($aBlocksDefault[$aId['type']][$aId['id']]))
				{
					$aBlocksDefault[$aId['type']][$aId['id']]['rw'] = $iOrder;
					$aBlocksDefault[$aId['type']][$aId['id']]['cl'] = $iColCounter;				
				} else 
				{	
					$aResult = $this -> _oMain -> _oDb -> getBlockInfoForAddToProfile(0, $aId['id'], $aId['type']);	
					$aBlocksDefault[$aId['type']][$aId['id']] = $aResult[$aId['id']];
					$aBlocksDefault[$aId['type']][$aId['id']]['rw'] = $iOrder;
					$aBlocksDefault[$aId['type']][$aId['id']]['cl'] = $iColCounter;	
				}	
			
				$aArrayTmp[] = array('id' => $aId['id'], 'type' => $aId['type']); 
			}
		}
		
		foreach($aBlocksDefaultCopy as $k => $v) 
			foreach($v as $key => $value)
				if (!in_array(array('id' => $key, 'type' => $k), $aArrayTmp)) unset($aBlocksDefault[$k][$key]);
		
		if ($this -> _oMain -> _oDb -> serializeProfileBlocks($aBlocksDefault, 0)) echo 'OK';
	}
	
	function saveColsWidths( $aWidths ) {
		parent::saveColsWidths( $aWidths );
		$aBlocksDefault = $this -> _oMain -> _oDb -> getProfileBlocksAsArray(0);
		$iCounter = count($aWidths);
		
		foreach($aBlocksDefault as $k => $v)
		      foreach($v as $key => $value)
				  if ((int)$value['cl'] > (int)$iCounter) unset($aBlocksDefault[$k][$key]); 				
		
		$this -> _oMain -> _oDb -> serializeProfileBlocks($aBlocksDefault, 0);
	}
	
	function getPageSelector() {
         return $this -> _oMain -> parseHtmlByName('pbuilder_cpanel', array(
            'url' => BX_DOL_URL_ROOT . $this -> _oMain ->_oConfig->getBaseUri() . 'apply'
        ));
	}
}

class AqbPCPVAPage extends BxDolPVAPage {
	var $_oMain;
	
	function AqbPCPVAPage( $sPage, &$oParent ) {
		$this -> _oMain = BxDolModule::getInstance("AqbPCModule");
		parent::BxDolPVAPage( $sPage, $oParent );
	}
	
	function loadContent() {
       $sQuery = "select `System` from `{$this -> oParent -> sDBTable}_pages` where `Name` = '{$this -> sName_db}'";

	   $this->isSystem = (bool)(int)db_value($sQuery);
        
        //get page width
        $sQuery = "SELECT `PageWidth` FROM `{$this -> oParent -> sDBTable}` WHERE `Page` = '{$this -> sName_db}' LIMIT 1";
        $this -> iPageWidth = db_value( $sQuery );
        
        if (!$this -> iPageWidth)
            $this -> iPageWidth = '960px';
   
		$this -> _oMain -> _oDb -> updateMembersColumns(0);
		
	   //get columns widths
        $sQuery = "
            SELECT
                `Column`,
                `ColWidth`
            FROM `{$this -> oParent -> sDBTable}`
            WHERE
                `Page` = '{$this -> sName_db}' AND
                `Column` != 0
            GROUP BY `Column`
            ORDER BY `Column`
        ";
        $rColumns = db_res( $sQuery );
		
        while( $aColumn = mysql_fetch_assoc( $rColumns ) ) {
            $iColumn                       = (int)$aColumn['Column'];
            $this -> aColsWidths[$iColumn] = (float)$aColumn['ColWidth'];
            $this -> aBlocks[$iColumn]     = array();
                      
            $aBlocks = $this -> _oMain -> _oDb -> getColumnBlocks($iColumn, 0, AQB_PC_OWNER) ;
            
			
			foreach($aBlocks as $k => $v){
				$this -> aBlocks[$iColumn][ $k ] = _t( $v['Caption'] );
			}
            
		}

		// load minimal widths
        $sQuery = "SELECT `ID`, `MinWidth` FROM `{$this -> oParent -> sDBTable}` WHERE `MinWidth` > 0 AND `Page`= '{$this -> sName_db}'";
        $rBlocks = db_res( $sQuery );
        while( $aBlock = mysql_fetch_assoc( $rBlocks ) )
            $this -> aMinWidths[ (int)$aBlock['ID'] ] = (int)$aBlock['MinWidth'];
        
		$this -> loadInactiveBlocks();
	}
	
	function loadInactiveBlocks() {
		$aStandardInactive = $this -> _oMain -> _oDb -> getAllAvailableBlocks(0,'standard', 1, 100000);
		foreach($aStandardInactive['blocks'] as $key => $value )
			$this -> aBlocksInactive[$value['id']] = _t( $value['params']['Caption'] );
	
		$aCustomInactive = $this -> _oMain -> _oDb -> getAllAvailableBlocks(0,'shared', 1, 100000);
		
		foreach($aCustomInactive['blocks'] as $key => $value )
		   $this -> aBlocksInactive[ $value['id'] ] = _t( $value['params']['Caption'] );
	}
}

?>