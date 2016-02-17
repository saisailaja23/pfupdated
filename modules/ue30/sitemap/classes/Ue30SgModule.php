<?php
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolModule');

class Ue30SgModule extends BxDolModule {

    function Ue30SgModule(&$aModule) {        
        parent::BxDolModule($aModule);
          
    }
    	

    function actionAdministration () {

        if (!$GLOBALS['logged']['admin']) { // check access to the page
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart(); // all the code below will be wrapped by the admin design

	    $iId = $this->_oDb->getSettingsCategory(); // get our setting category id
	    if(empty($iId)) { // if category is not found display page not found
            echo MsgBox(_t('_sys_request_page_not_found_cpt'));
            $this->_oTemplate->pageCodeAdmin (_t('_ue30_sg'));
            return;
        }
		
		$aVars = array (
            'module_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
			'root_url' => BX_DOL_URL_ROOT,
        );
		$sContent = $this->_oTemplate->parseHtmlByName ('admin_links',$aVars);
        echo $this->_oTemplate->adminBlock ($sContent, _t('_ue30_sg_admin_links'));

        bx_import('BxDolAdminSettings'); // import class

        $mixedResult = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) { // save settings
	        $oSettings = new BxDolAdminSettings($iId);
            $mixedResult = $oSettings->saveChanges($_POST);
        }

        $oSettings = new BxDolAdminSettings($iId); // get display form code
        $sResult = $oSettings->getForm();
        	       
        if($mixedResult !== true && !empty($mixedResult)) // attach any resulted messages at the form beginning
            $sResult = $mixedResult . $sResult;

        echo DesignBoxAdmin (_t('_ue30_sg'), $sResult); // dsiplay box
        
        $this->_oTemplate->pageCodeAdmin (_t('_ue30_sg')); // output is completed, admin page will be displaed here
    } 

	function actionWriteSitemap() {
	
        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;            
        }
		
		$sAds = getParam('ue30_sg_ads');
		if ($sAds == 'on') {
		$sQuerySitemapAds = "
		SELECT `bx_ads_main`. *		
		FROM `bx_ads_main`
		WHERE `bx_ads_main`.`status` = 'active'
		ORDER BY `DateTime` DESC
		";

		$rSitemapAds = db_res( $sQuerySitemapAds );
		$sAdsFreq = getParam('ue30_sg_ads_freq');
		$sAdsPrio = getParam('ue30_sg_ads_prio');
		
		$sSitemapAds = '';
			while( $aSitemapAds = mysql_fetch_assoc( $rSitemapAds ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'ads/entry/' . $aSitemapAds['EntryUri'];
			$sStart = date("Y-m-d", $aSitemapAds['DateTime']);

					
			
		$sSitemapAds .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sAdsFreq}</changefreq>
	<priority>{$sAdsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sArticles = getParam('ue30_sg_articles');
		if ($sArticles == 'on') {
		$sQuerySitemapArticles = "
		SELECT `bx_arl_entries`. *		
		FROM `bx_arl_entries`
		WHERE `bx_arl_entries`.`status` = '0'
		ORDER BY `date` DESC
		";

		$rSitemapArticles = db_res( $sQuerySitemapArticles );
		$sArticlesFreq = getParam('ue30_sg_articles_freq');
		$sArticlesPrio = getParam('ue30_sg_articles_prio');
		
		$sSitemapArticles = '';
			while( $aSitemapArticles = mysql_fetch_assoc( $rSitemapArticles ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/articles/view/' . $aSitemapArticles['uri'];
			$sStart = date("Y-m-d", $aSitemapArticles['date']);

					
			
		$sSitemapArticles .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sArticlesFreq}</changefreq>
	<priority>{$sArticlesPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sEvents = getParam('ue30_sg_events');
		if ($sEvents == 'on') {
		$sQuerySitemapEvents = "
		SELECT `bx_events_main`. *		
		FROM `bx_events_main`
		WHERE `bx_events_main`.`Status` = 'approved'
		ORDER BY `Date` DESC
		";

		$rSitemapEvents = db_res( $sQuerySitemapEvents );
		$sEventsFreq = getParam('ue30_sg_events_freq');
		$sEventsPrio = getParam('ue30_sg_events_prio');
		
		$sSitemapEvents = '';
			while( $aSitemapEvents = mysql_fetch_assoc( $rSitemapEvents ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/events/view/' . $aSitemapEvents['EntryUri'];
			$sStart = date("Y-m-d", $aSitemapEvents['Date']);

					
			
		$sSitemapEvents .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sEventsFreq}</changefreq>
	<priority>{$sEventsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sGroups = getParam('ue30_sg_groups');
		if ($sGroups == 'on') {
		$sQuerySitemapGroups = "
		SELECT `bx_groups_main`. *		
		FROM `bx_groups_main`
		WHERE `bx_groups_main`.`Status` = 'approved'
		ORDER BY `created` DESC
		";

		$rSitemapGroups = db_res( $sQuerySitemapGroups );
		$sGroupsFreq = getParam('ue30_sg_groups_freq');
		$sGroupsPrio = getParam('ue30_sg_groups_prio');
		
		$sSitemapGroups = '';
			while( $aSitemapGroups = mysql_fetch_assoc( $rSitemapGroups ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/groups/view/' . $aSitemapGroups['uri'];
			$sStart = date("Y-m-d", $aSitemapGroups['created']);

					
			
		$sSitemapGroups .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sGroupsFreq}</changefreq>
	<priority>{$sGroupsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sMusic = getParam('ue30_sg_music');
		if ($sMusic == 'on') {
		$sQuerySitemapMusic = "
		SELECT `RayMp3Files`. *		
		FROM `RayMp3Files`
		WHERE `RayMp3Files`.`Status` = 'approved'
		ORDER BY `Date` DESC
		";

		$rSitemapMusic = db_res( $sQuerySitemapMusic );
		$sMusicFreq = getParam('ue30_sg_music_freq');
		$sMusicPrio = getParam('ue30_sg_music_prio');
		
		$sSitemapMusic = '';
			while( $aSitemapMusic = mysql_fetch_assoc( $rSitemapMusic ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/sounds/view/' . $aSitemapMusic['Uri'];
			$sStart = date("Y-m-d", $aSitemapMusic['Date']);

					
			
		$sSitemapMusic .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sMusicFreq}</changefreq>
	<priority>{$sMusicPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sNews = getParam('ue30_sg_news');
		if ($sNews == 'on') {
		$sQuerySitemapNews = "
		SELECT `bx_news_entries`. *		
		FROM `bx_news_entries`
		WHERE `bx_news_entries`.`status` = '0'
		ORDER BY `Date` DESC
		";

		$rSitemapNews = db_res( $sQuerySitemapNews );
		$sNewsFreq = getParam('ue30_sg_news_freq');
		$sNewsPrio = getParam('ue30_sg_news_prio');
		
		$sSitemapNews = '';
			while( $aSitemapNews = mysql_fetch_assoc( $rSitemapNews ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/news/view/' . $aSitemapNews['uri'];
			$sStart = date("Y-m-d", $aSitemapNews['date']);

					
			
		$sSitemapNews .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sNewsFreq}</changefreq>
	<priority>{$sNewsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sBlogs = getParam('ue30_sg_blogs');
		if ($sBlogs == 'on') {
		$sQuerySitemapBlogs = "
		SELECT `bx_blogs_posts`. *		
		FROM `bx_blogs_posts`
		WHERE `bx_blogs_posts`.`PostStatus` = 'approval'
		ORDER BY `PostDate` DESC
		";

		$rSitemapBlogs = db_res( $sQuerySitemapBlogs );
		$sBlogsFreq = getParam('ue30_sg_blogs_freq');
		$sBlogsPrio = getParam('ue30_sg_blogs_prio');
		
		$sSitemapBlogs = '';
			while( $aSitemapBlogs = mysql_fetch_assoc( $rSitemapBlogs ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'blogs/entry/' . $aSitemapBlogs['PostUri'];
			$sStart = date("Y-m-d", $aSitemapBlogs['PostDate']);
					
			
		$sSitemapBlogs .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
	<lastmod>{$sStart}</lastmod>
	<changefreq>{$sBlogsFreq}</changefreq>
	<priority>{$sBlogsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sVideos = getParam('ue30_sg_videos');
		if ($sVideos == 'on') {
		$sQuerySitemapVideos = "
		SELECT `RayVideoFiles`. *		
		FROM `RayVideoFiles`
		WHERE `RayVideoFiles`.`Status` = 'approved'
		ORDER BY `Date` DESC
		";

		$rSitemapVideos = db_res( $sQuerySitemapVideos );
		$sVideosFreq = getParam('ue30_sg_videos_freq');
		$sVideosPrio = getParam('ue30_sg_videos_prio');
		
		$sSitemapVideos = '';
			while( $aSitemapVideos = mysql_fetch_assoc( $rSitemapVideos ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/videos/view/' . $aSitemapVideos['Uri'];
			$sStart = date("Y-m-d", $aSitemapVideos['Date']);
					
			
		$sSitemapVideos .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sVideosFreq}</changefreq>
    <priority>{$sVideosPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sPhotos = getParam('ue30_sg_photos');
		if ($sPhotos == 'on') {
		$sQuerySitemapPhotos = "
		SELECT `bx_photos_main`. *		
		FROM `bx_photos_main`
		WHERE `bx_photos_main`.`Status` = 'approved'
		ORDER BY `Date` DESC
		";

		$rSitemapPhotos = db_res( $sQuerySitemapPhotos );
		$sPhotosFreq = getParam('ue30_sg_photos_freq');
		$sPhotosPrio = getParam('ue30_sg_photos_prio');
		
		$sSitemapPhotos = '';
			while( $aSitemapPhotos = mysql_fetch_assoc( $rSitemapPhotos ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/photos/view/' . $aSitemapPhotos['Uri'];
			$sStart = date("Y-m-d", $aSitemapPhotos['Date']);
					
			
		$sSitemapPhotos .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sPhotosFreq}</changefreq>
    <priority>{$sPhotosPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sSites = getParam('ue30_sg_sites');
		if ($sSites == 'on') {
		$sQuerySitemapSites = "
		SELECT `bx_sites_main`. *		
		FROM `bx_sites_main`
		WHERE `bx_sites_main`.`Status` = 'approved'
		ORDER BY `date` DESC
		";

		$rSitemapSites = db_res( $sQuerySitemapSites );
		$sSitesFreq = getParam('ue30_sg_sites_freq');
		$sSitesPrio = getParam('ue30_sg_sites_prio');
		
		$sSitemapSites = '';
			while( $aSitemapSites = mysql_fetch_assoc( $rSitemapSites ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/sites/view/' . $aSitemapSites['entryUri'];
			$sStart = date("Y-m-d", $aSitemapSites['date']);
					
			
		$sSitemapSites .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sSitesFreq}</changefreq>
    <priority>{$sSitesPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sStore = getParam('ue30_sg_store');
		if ($sStore == 'on') {
		$sQuerySitemapStore = "
		SELECT `bx_store_products`. *		
		FROM `bx_store_products`
		WHERE `bx_store_products`.`status` = 'approved'
		ORDER BY `created` DESC
		";

		$rSitemapStore = db_res( $sQuerySitemapStore );
		$sStoreFreq = getParam('ue30_sg_store_freq');
		$sStorePrio = getParam('ue30_sg_store_prio');
		
		$sSitemapStore = '';
			while( $aSitemapStore = mysql_fetch_assoc( $rSitemapStore ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'm/store/view/' . $aSitemapStore['uri'];
			$sStart = date("Y-m-d", $aSitemapStore['created']);
					
			
		$sSitemapStore .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sStoreFreq}</changefreq>
    <priority>{$sStorePrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sMembers = getParam('ue30_sg_members');
		if ($sMembers == 'on') {
		$sQuerySitemapMembers = "
		SELECT `Profiles`. *,
		UNIX_TIMESTAMP(`DateReg`) AS 'DateReg_UTS'		
		FROM `Profiles`
		WHERE `Profiles`.`Status` = 'Active'
		ORDER BY `DateReg` DESC
		";

		$rSitemapMembers = db_res( $sQuerySitemapMembers );
		$sMembersFreq = getParam('ue30_sg_members_freq');
		$sMembersPrio = getParam('ue30_sg_members_prio');
		
		$sSitemapMembers = '';
			while( $aSitemapMembers = mysql_fetch_assoc( $rSitemapMembers ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . '' . $aSitemapMembers['NickName'];
			
			$sStart = date("Y-m-d", $aSitemapMembers['DateReg_UTS']);
					
			
		$sSitemapMembers .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sMembersFreq}</changefreq>
    <priority>{$sMembersPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sTopics = getParam('ue30_sg_topics');
		if ($sTopics == 'on') {
		$sQuerySitemapTopics = "
		SELECT `bx_forum_topic`. *		
		FROM `bx_forum_topic`
		ORDER BY `last_post_when` DESC
		";

		$rSitemapTopics = db_res( $sQuerySitemapTopics );
		$sTopicsFreq = getParam('ue30_sg_topics_freq');
		$sTopicsPrio = getParam('ue30_sg_topics_prio');
		
		$sSitemapTopics = '';
			while( $aSitemapTopics = mysql_fetch_assoc( $rSitemapTopics ) ) {
			$sUrl = '' . BX_DOL_URL_ROOT . 'forum/topic/' . $aSitemapTopics['topic_uri'] . '.htm';
			$sStart = date("Y-m-d", $aSitemapTopics['last_post_when']);
					
			
		$sSitemapTopics .= <<<EOF
	<url>
    <loc>{$sUrl}</loc>
    <lastmod>{$sStart}</lastmod>
    <changefreq>{$sTopicsFreq}</changefreq>
    <priority>{$sTopicsPrio}</priority>
	</url> 

EOF;
		}
		}
		
		$sIndex = getParam('ue30_sg_index');
		if ($sIndex == 'on') {
		
		$sIndexFreq = getParam('ue30_sg_index_freq');
		$sIndexPrio = getParam('ue30_sg_index_prio');
		$sDate = date('Y-m-d');
		$sIndexUrl = '' . BX_DOL_URL_ROOT . '';
		
		$sSitemapIndex = '';	
			
		$sSitemapIndex .= <<<EOF
	<url>
    <loc>{$sIndexUrl}</loc>
	<lastmod>{$sDate}</lastmod>
	<changefreq>{$sIndexFreq}</changefreq>
	<priority>{$sIndexPrio}</priority>
	</url>

EOF;
		}
		
		$sSitemapheading = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="' . BX_DOL_URL_ROOT . 'modules/ue30/sitemap/sitemap.xsl"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
		
$fh=fopen('../sitemap.xml',"w");
fwrite($fh,$sSitemapheading);
fwrite($fh,$sSitemapIndex);	
fwrite($fh,$sSitemapAds);
fwrite($fh,$sSitemapArticles);
fwrite($fh,$sSitemapBlogs);
fwrite($fh,$sSitemapEvents);
fwrite($fh,$sSitemapGroups);
fwrite($fh,$sSitemapMembers);
fwrite($fh,$sSitemapMusic);
fwrite($fh,$sSitemapNews);
fwrite($fh,$sSitemapPhotos);
fwrite($fh,$sSitemapSites);
fwrite($fh,$sSitemapStore);
fwrite($fh,$sSitemapTopics);
fwrite($fh,$sSitemapVideos);
fwrite($fh,'</urlset>');
fclose($fh);

$sSitemapGzip = getParam('ue30_sg_sitemap_gzip');
		if ($sSitemapGzip == 'on') {
$fh=gzopen('../sitemap.xml.gz',"w");
gzwrite($fh,$sSitemapheading);
gzwrite($fh,$sSitemapIndex);	
gzwrite($fh,$sSitemapAds);
gzwrite($fh,$sSitemapArticles);
gzwrite($fh,$sSitemapBlogs);
gzwrite($fh,$sSitemapEvents);
gzwrite($fh,$sSitemapGroups);
gzwrite($fh,$sSitemapMembers);
gzwrite($fh,$sSitemapMusic);
gzwrite($fh,$sSitemapNews);
gzwrite($fh,$sSitemapPhotos);
gzwrite($fh,$sSitemapSites);
gzwrite($fh,$sSitemapStore);
gzwrite($fh,$sSitemapTopics);
gzwrite($fh,$sSitemapVideos);
gzwrite($fh,'</urlset>');
gzclose($fh);
}
       

        echo $this->_oTemplate->displayMsg('XML Sitemap has been written');
        
    }
	
	function isAdmin () {
        return $GLOBALS['logged']['admin'] || $GLOBALS['logged']['moderator'];
    } 
	
}

?>
