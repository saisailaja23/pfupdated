<?php

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
bx_import('BxDolPageView');



class BxSitemapPageView extends BxDolPageView {
function BxSitemapPageView() {
      parent::BxDolPageView('sitemap'); 
  }
    
	function getBlockCode_SitemapAds() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapAds = "
		SELECT `bx_ads_main`. *		
		FROM `bx_ads_main`
		WHERE `bx_ads_main`.`status` = 'active'
		ORDER BY `DateTime` DESC Limit {$sLimit}
		";

		$rSitemapAds = db_res( $sQuerySitemapAds );
		
		$iIndex = 0;
		
		$sSitemapAds = '';
			while( $aSitemapAds = mysql_fetch_assoc( $rSitemapAds ) ) {
			$sTitle = $aSitemapAds['Subject'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'ads/entry/' . $aSitemapAds['EntryUri'];
			$sStart = getLocaleDate($aSitemapAds['DateTime'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapAds .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapAds .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapAds == '') $sSitemapAds = MsgBox(_t('_Empty'));
		return $sSitemapAds;
    }
	
	function getBlockCode_SitemapArticles() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapArticles = "
		SELECT `bx_arl_entries`. *		
		FROM `bx_arl_entries`
		WHERE `bx_arl_entries`.`status` = '0'
		ORDER BY `date` DESC Limit {$sLimit}
		";

		$rSitemapArticles = db_res( $sQuerySitemapArticles );
		
		$iIndex = 0;
		
		$sSitemapArticles = '';
			while( $aSitemapArticles = mysql_fetch_assoc( $rSitemapArticles ) ) {
			$sTitle = $aSitemapArticles['caption'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/articles/view/' . $aSitemapArticles['uri'];
			$sStart = getLocaleDate($aSitemapArticles['date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapArticles .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapArticles .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapArticles == '') $sSitemapArticles = MsgBox(_t('_Empty'));
		return $sSitemapArticles;
    }
	
   function getBlockCode_SitemapNews() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapNews = "
		SELECT `bx_news_entries`. *		
		FROM `bx_news_entries`
		WHERE `bx_news_entries`.`status` = '0'
		ORDER BY `Date` DESC Limit {$sLimit}
		";

		$rSitemapNews = db_res( $sQuerySitemapNews );
		
		$iIndex = 0;
		
		$sSitemapNews = '';
			while( $aSitemapNews = mysql_fetch_assoc( $rSitemapNews ) ) {
			$sTitle = $aSitemapNews['caption'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/news/view/' . $aSitemapNews['uri'];
			$sStart = getLocaleDate($aSitemapNews['date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapNews .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapNews .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapNews == '') $sSitemapNews = MsgBox(_t('_Empty'));
		return $sSitemapNews;
    }
	function getBlockCode_SitemapEvents() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapEvents = "
		SELECT `bx_events_main`. *		
		FROM `bx_events_main`
		WHERE `bx_events_main`.`Status` = 'approved'
		ORDER BY `Date` DESC Limit {$sLimit}
		";

		$rSitemapEvents = db_res( $sQuerySitemapEvents );
		
		$iIndex = 0;
		
		$sSitemapEvents = '';
			while( $aSitemapEvents = mysql_fetch_assoc( $rSitemapEvents ) ) {
			$sTitle = $aSitemapEvents['Title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/events/view/' . $aSitemapEvents['EntryUri'];
			$sStart = getLocaleDate($aSitemapEvents['Date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapEvents .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapEvents .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapEvents == '') $sSitemapEvents = MsgBox(_t('_Empty'));
		return $sSitemapEvents;
    }
	function getBlockCode_SitemapGroups() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapGroups = "
		SELECT `bx_groups_main`. *		
		FROM `bx_groups_main`
		WHERE `bx_groups_main`.`Status` = 'approved'
		ORDER BY `created` DESC Limit {$sLimit}
		";

		$rSitemapGroups = db_res( $sQuerySitemapGroups );
		
		$iIndex = 0;
		
		$sSitemapGroups = '';
			while( $aSitemapGroups = mysql_fetch_assoc( $rSitemapGroups ) ) {
			$sTitle = $aSitemapGroups['title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/groups/view/' . $aSitemapGroups['uri'];
			$sStart = getLocaleDate($aSitemapGroups['created'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapGroups .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapGroups .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapGroups == '') $sSitemapGroups = MsgBox(_t('_Empty'));
		return $sSitemapGroups;
    }
	function getBlockCode_SitemapMusic() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapMusic = "
		SELECT `RayMp3Files`. *		
		FROM `RayMp3Files`
		WHERE `RayMp3Files`.`Status` = 'approved'
		ORDER BY `Date` DESC Limit {$sLimit}
		";

		$rSitemapMusic = db_res( $sQuerySitemapMusic );
		
		$iIndex = 0;
		
		$sSitemapMusic = '';
			while( $aSitemapMusic = mysql_fetch_assoc( $rSitemapMusic ) ) {
			$sTitle = $aSitemapMusic['Title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/sounds/view/' . $aSitemapMusic['Uri'];
			$sStart = getLocaleDate($aSitemapMusic['Date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapMusic .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapMusic .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapMusic == '') $sSitemapMusic = MsgBox(_t('_Empty'));
		return $sSitemapMusic;
    }
	function getBlockCode_SitemapSites() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapSites = "
		SELECT `bx_sites_main`. *		
		FROM `bx_sites_main`
		WHERE `bx_sites_main`.`Status` = 'approved'
		ORDER BY `date` DESC Limit {$sLimit}
		";

		$rSitemapSites = db_res( $sQuerySitemapSites );
		
		$iIndex = 0;
		
		$sSitemapSites = '';
			while( $aSitemapSites = mysql_fetch_assoc( $rSitemapSites ) ) {
			$sTitle = $aSitemapSites['title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/sites/view/' . $aSitemapSites['entryUri'];
			$sStart = getLocaleDate($aSitemapSites['date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapSites .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapSites .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapSites == '') $sSitemapSites = MsgBox(_t('_Empty'));
		return $sSitemapSites;
    }
	function getBlockCode_SitemapStore() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapStore = "
		SELECT `bx_store_products`. *		
		FROM `bx_store_products`
		WHERE `bx_store_products`.`status` = 'approved'
		ORDER BY `created` DESC Limit {$sLimit}
		";

		$rSitemapStore = db_res( $sQuerySitemapStore );
		
		$iIndex = 0;
		
		$sSitemapStore = '';
			while( $aSitemapStore = mysql_fetch_assoc( $rSitemapStore ) ) {
			$sTitle = $aSitemapStore['title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/store/view/' . $aSitemapStore['uri'];
			$sStart = getLocaleDate($aSitemapStore['created'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapStore .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapStore .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapStore == '') $sSitemapStore = MsgBox(_t('_Empty'));
		return $sSitemapStore;
    }
	function getBlockCode_SitemapVideos() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapVideos = "
		SELECT `RayVideoFiles`. *		
		FROM `RayVideoFiles`
		WHERE `RayVideoFiles`.`Status` = 'approved'
		ORDER BY `Date` DESC Limit {$sLimit}
		";

		$rSitemapVideos = db_res( $sQuerySitemapVideos );
		
		$iIndex = 0;
		
		$sSitemapVideos = '';
			while( $aSitemapVideos = mysql_fetch_assoc( $rSitemapVideos ) ) {
			$sTitle = $aSitemapVideos['Title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/videos/view/' . $aSitemapVideos['Uri'];
			$sStart = getLocaleDate($aSitemapVideos['Date'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
			
		if ( !($iIndex % 2)  ) {	
		$sSitemapVideos .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapVideos .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapVideos == '') $sSitemapVideos = MsgBox(_t('_Empty'));
		return $sSitemapVideos;
    }
	function getBlockCode_SitemapBlogs() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapBlogs = "
		SELECT `bx_blogs_posts`. *		
		FROM `bx_blogs_posts`
		WHERE `bx_blogs_posts`.`PostStatus` = 'approval'
		ORDER BY `PostDate` DESC Limit {$sLimit}
		";

		$rSitemapBlogs = db_res( $sQuerySitemapBlogs );
		
		$iIndex = 0;
		
		$sSitemapBlogs = '';
			while( $aSitemapBlogs = mysql_fetch_assoc( $rSitemapBlogs ) ) {
			$sTitle = $aSitemapBlogs['PostCaption'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'blogs/entry/' . $aSitemapBlogs['PostUri'];
			$sStart = getLocaleDate($aSitemapBlogs['PostDate'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapBlogs .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapBlogs .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapBlogs == '') $sSitemapBlogs = MsgBox(_t('_Empty'));
		return $sSitemapBlogs;
    }
	function getBlockCode_SitemapTopics() {
	
        $sLimit = getParam('ue30_sg_limit_blocks');
		
		$sQuerySitemapTopics = "
		SELECT `bx_forum_topic`. *		
		FROM `bx_forum_topic`
		ORDER BY `when` DESC Limit {$sLimit}
		";

		$rSitemapTopics = db_res( $sQuerySitemapTopics );
		
		$iIndex = 0;
		
		$sSitemapTopics = '';
			while( $aSitemapTopics = mysql_fetch_assoc( $rSitemapTopics ) ) {
			$sTitle = $aSitemapTopics['topic_title'];
			$sUrl = '' . BX_DOL_URL_ROOT . 'forum/topic/' . $aSitemapTopics['topic_uri'] . '.htm';
			$sStart = getLocaleDate($aSitemapTopics['when'], BX_DOL_LOCALE_DATE_SHORT);
			$sIcon = '' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/templates/base/images/icons/article.png';
					
		if ( !($iIndex % 2)  ) {	
		$sSitemapTopics .= <<<EOF
<ul>
	<li class="ue30-sitemap">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
	} else {
		$sSitemapTopics .= <<<EOF
<ul>
	<li class="ue30-sitemap-filled">
		{$sStart} <a href="{$sUrl}" title="{$sTitle}">{$sTitle}</a>
	</li>
</ul>
EOF;
		}

$iIndex++;
		}
		
		if ($sSitemapTopics == '') $sSitemapTopics = MsgBox(_t('_Empty'));
		return $sSitemapTopics;
    }
	
 }

$_page['name_index']	= 7; // choose your own index of template or leave if in doubt
$_page['header'] = 'Sitemap';
$_ni = $_page['name_index'];
$_page['css_name'] = array('ue30_sitemap.css');

$oEPV = new BxSitemapPageView();
$_page_cont[$_ni]['page_main_code'] = $oEPV->getCode();

PageCode();

?>