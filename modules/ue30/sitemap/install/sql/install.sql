

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Sitemap Generator', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('ue30_sg_index', 'on', @iCategId, 'Index Site in Sitemap.xml', 'checkbox', '', '', '1', ''),
('ue30_sg_index_freq', 'daily', @iCategId, 'Index changefreq', 'select', '', '', '2', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_index_prio', '1.0', @iCategId, 'Index priority', 'select', '', '', '3', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_ads', '', @iCategId, 'Ads in Sitemap.xml', 'checkbox', '', '', '4', ''),
('ue30_sg_ads_freq', 'weekly', @iCategId, 'Ads changefreq', 'select', '', '', '5', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_ads_prio', '0.5', @iCategId, 'Ads priority', 'select', '', '', '6', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_articles', '', @iCategId, 'Articles in Sitemap.xml', 'checkbox', '', '', '7', ''),
('ue30_sg_articles_freq', 'weekly', @iCategId, 'Articles changefreq', 'select', '', '', '8', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_articles_prio', '0.5', @iCategId, 'Articles priority', 'select', '', '', '9', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_blogs', '', @iCategId, 'Blogs in Sitemap.xml', 'checkbox', '', '', '10', ''),
('ue30_sg_blogs_freq', 'weekly', @iCategId, 'Blogs changefreq', 'select', '', '', '11', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_blogs_prio', '0.5', @iCategId, 'Blogs priority', 'select', '', '', '12', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_events', '', @iCategId, 'Events in Sitemap.xml', 'checkbox', '', '', '13', ''),
('ue30_sg_events_freq', 'weekly', @iCategId, 'Events changefreq', 'select', '', '', '14', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_events_prio', '0.5', @iCategId, 'Events priority', 'select', '', '', '15', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_groups', '', @iCategId, 'Groups in Sitemap.xml', 'checkbox', '', '', '16', ''),
('ue30_sg_groups_freq', 'weekly', @iCategId, 'Groups changefreq', 'select', '', '', '17', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_groups_prio', '0.5', @iCategId, 'Groups priority', 'select', '', '', '18', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_members', '', @iCategId, 'Members in Sitemap.xml', 'checkbox', '', '', '19', ''),
('ue30_sg_members_freq', 'weekly', @iCategId, 'Members changefreq', 'select', '', '', '20', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_members_prio', '0.5', @iCategId, 'Members priority', 'select', '', '', '21', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_music', '', @iCategId, 'Music in Sitemap.xml', 'checkbox', '', '', '22', ''),
('ue30_sg_music_freq', 'weekly', @iCategId, 'Music changefreq', 'select', '', '', '23', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_music_prio', '0.5', @iCategId, 'Music priority', 'select', '', '', '24', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_news', '', @iCategId, 'News in Sitemap.xml', 'checkbox', '', '', '25', ''),
('ue30_sg_news_freq', 'weekly', @iCategId, 'News changefreq', 'select', '', '', '26', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_news_prio', '0.5', @iCategId, 'News priority', 'select', '', '', '27', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_photos', '', @iCategId, 'Photos in Sitemap.xml', 'checkbox', '', '', '28', ''),
('ue30_sg_photos_freq', 'weekly', @iCategId, 'Photos changefreq', 'select', '', '', '29', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_photos_prio', '0.5', @iCategId, 'Photos priority', 'select', '', '', '30', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_sites', '', @iCategId, 'Sites in Sitemap.xml', 'checkbox', '', '', '31', ''),
('ue30_sg_sites_freq', 'weekly', @iCategId, 'Sites changefreq', 'select', '', '', '32', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_sites_prio', '0.5', @iCategId, 'Sites priority', 'select', '', '', '33', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_store', '', @iCategId, 'Store in Sitemap.xml', 'checkbox', '', '', '34', ''),
('ue30_sg_store_freq', 'weekly', @iCategId, 'Store changefreq', 'select', '', '', '35', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_store_prio', '0.5', @iCategId, 'Store priority', 'select', '', '', '36', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_topics', '', @iCategId, 'Topics in Sitemap.xml', 'checkbox', '', '', '37', ''),
('ue30_sg_topics_freq', 'weekly', @iCategId, 'Topics changefreq', 'select', '', '', '38', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_topics_prio', '0.5', @iCategId, 'Topics priority', 'select', '', '', '39', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_videos', '', @iCategId, 'Videos in Sitemap.xml', 'checkbox', '', '', '40', ''),
('ue30_sg_videos_freq', 'weekly', @iCategId, 'Videos changefreq', 'select', '', '', '41', 'always,hourly,daily,weekly,monthly,yearly,never'),
('ue30_sg_videos_prio', '0.5', @iCategId, 'Videos priority', 'select', '', '', '42', '0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'),
('ue30_sg_sitemap_gzip', 'on', @iCategId, 'Write compressed sitemap.xml.gz', 'checkbox', '', '', '43', ''),
('ue30_sg_limit_blocks', '100', @iCategId, 'Max Number shown in each Block on Sitemap Page', 'digit', '', '', '44', '');

-- permalinks
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ue30sg/', 'm/ue30sg/', 'ue30_sg_permalinks');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'ue30_sg', '_ue30_sg', '{siteUrl}modules/?r=ue30sg/administration/', 'Sitemap Generator', 'modules/ue30/sitemap/|icon.png', @iMax+1);

SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('sitemap', 'Sitemap', @iMaxOrder);

SET @iMaxOrder = (SELECT `Order` + 1 FROM `sys_page_compose` WHERE `Page` = 'sitemap' AND `Column` = 1 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
('sitemap', '998px', 'Sitemap Ads', '_sitemap_bcaption_ads', '0', '0', 'SitemapAds', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Articles', '_sitemap_bcaption_articles', '0', '0', 'SitemapArticles', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Blogs', '_sitemap_bcaption_blogs', '0', '0', 'SitemapBlogs', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Events', '_sitemap_bcaption_events', '0', '0', 'SitemapEvents', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Groups', '_sitemap_bcaption_groups', '0', '0', 'SitemapGroups', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Music', '_sitemap_bcaption_music', '0', '0', 'SitemapMusic', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap News', '_sitemap_bcaption_news', '0', '0', 'SitemapNews', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Sites', '_sitemap_bcaption_sites', '0', '0', 'SitemapSites', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Store', '_sitemap_bcaption_store', '0', '0', 'SitemapStore', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Topics', '_sitemap_bcaption_topics', '0', '0', 'SitemapTopics', '', '1', '100', 'non,memb', '0'),
('sitemap', '998px', 'Sitemap Videos', '_sitemap_bcaption_videos', '0', '0', 'SitemapVideos', '', '1', '100', 'non,memb', '0');

SET @iSBOrder = (SELECT `Order` + 1 FROM `sys_menu_bottom` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_menu_bottom` (`ID`, `Caption`, `Name`, `Icon`, `Link`, `Script`, `Order`, `Target`) VALUES
('', '_ue30_sitemap', 'Sitemap', '', 'sitemap.php', '', @iSBOrder, '');

