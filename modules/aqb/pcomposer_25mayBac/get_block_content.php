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
require_once('../../../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

$oMain = BxDolModule::getInstance("AqbPCModule");
$sCSS=<<<EOF
		 <link type="text/css" rel="stylesheet" href="{$site['url']}templates/tmpl_{$tmpl}/css/common.css">
EOF;
$sJs =<<<EOF
		  <script src="{$site['url']}inc/js/functions.js" type="text/javascript" language="javascript"></script>
		  <script src="{$site['plugins']}jquery/jquery.js" type="text/javascript" language="javascript"></script>
		  <script src="{$site['plugins']}jquery/jquery.jfeed.js" type="text/javascript" language="javascript"></script>
		  <script src="{$site['url']}inc/js/jquery.dolRSSFeed.js" type="text/javascript" language="javascript"></script>
		  <script language="javascript" type="text/javascript">
		  var aDolImages = {'more': '{$site['base']}images/icons/more.png', 'loading': '{$site['url']}templates/tmpl_{$tmpl}/images/loading.gif'};
		  var site_url = '{$site['url']}';
		  $(document).ready( function() {
				$( 'div.RSSAggrCont' ).dolRSSFeed();
		  });
		  </script>	
EOF;
echo $sCSS . $sJs . $oMain -> _oTemplate -> getBlockContent($_GET['id']);  
?>