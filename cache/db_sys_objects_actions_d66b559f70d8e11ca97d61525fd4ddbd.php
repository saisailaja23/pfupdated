<?php $mixedData=array (
  'bx_blogs' => 
  array (
    0 => 
    array (
      'Caption' => '_Add Post',
      'Icon' => 'plus',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'if ({only_menu} == 1)
    return (getParam(\'permalinks_blogs\') == \'on\') ? \'blogs/my_page/add/\' : \'modules/boonex/blogs/blogs.php?action=my_page&mode=add\';
else
    return null;',
      'bDisplayInSubMenuHeader' => '1',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'book',
      'Url' => '{blog_owner_link}',
      'Script' => '',
      'Eval' => 'if ({only_menu} == 1)
return _t(\'_bx_blog_My_blog\');
else
return null;',
      'bDisplayInSubMenuHeader' => '1',
    ),
    2 => 
    array (
      'Caption' => '_bx_blog_Blogs_Home',
      'Icon' => 'book',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'if ({only_menu} == 1)
    return (getParam(\'permalinks_blogs\') == \'on\') ? \'blogs/home/\' : \'modules/boonex/blogs/blogs.php?action=home\';
else
    return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    3 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'star-empty',
      'Url' => '{post_entry_url}&do=cfs&id={post_id}',
      'Script' => '',
      'Eval' => '$iPostFeature = (int)\'{post_featured}\';
if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode} == true) {
return ($iPostFeature==1) ? _t(\'_De-Feature it\') : _t(\'_Feature it\');
}
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    4 => 
    array (
      'Caption' => '_Edit',
      'Icon' => 'edit',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode} == true || {edit_allowed}) {
    return (getParam(\'permalinks_blogs\') == \'on\') ? \'blogs/my_page/edit/{post_id}\' : \'modules/boonex/blogs/blogs.php?action=edit_post&EditPostID={post_id}\';
}
else
    return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    5 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'remove',
      'Url' => '',
      'Script' => 'iDelPostID = {post_id}; sWorkUrl = \'{work_url}\'; if (confirm(\'{sure_label}\')) { window.open (sWorkUrl+\'?action=delete_post&DeletePostID=\'+iDelPostID,\'_self\'); }',
      'Eval' => '$oModule = BxDolModule::getInstance(\'BxBlogsModule\');
 if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode} == true || $oModule->isAllowedPostDelete({owner_id})) {
return _t(\'_Delete\');
}
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    6 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'ok-circle',
      'Url' => '{post_inside_entry_url}&sa={sSAAction}',
      'Script' => '',
      'Eval' => '$sButAct = \'{sSACaption}\';
if ({admin_mode} == true || {allow_approve}) {
return $sButAct;
}
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    7 => 
    array (
      'Caption' => '{sbs_blogs_title}',
      'Icon' => 'paper-clip',
      'Url' => '',
      'Script' => '{sbs_blogs_script}',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    8 => 
    array (
      'Caption' => '{TitleShare}',
      'Icon' => 'share',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{base_url}blogs.php?action=share_post&post_id={post_id}\');',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    9 => 
    array (
      'Caption' => '_bx_blog_Back_to_Blog',
      'Icon' => 'book',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'return \'{blog_owner_link}\';
',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_photos' => 
  array (
    0 => 
    array (
      'Caption' => '_bx_photos_action_view_original',
      'Icon' => 'download-alt',
      'Url' => '',
      'Script' => 'window.open(\'{moduleUrl}get_image/original/{fileKey}.{fileExt}\')',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{shareCpt}',
      'Icon' => 'share',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{moduleUrl}share/{fileUri}\')',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    2 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'exclamation-sign',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{moduleUrl}report/{fileUri}\')',
      'Eval' => 'if ({iViewer}!=0)
return _t(\'_bx_photos_action_report\');
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    3 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => '',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}favorite/{ID}\', false, \'post\'); return false;',
      'Eval' => 'if ({iViewer}==0)
return false;
$sMessage = \'{favorited}\'==\'\' ? \'fave\':\'unfave\';
return _t(\'_bx_photos_action_\' . $sMessage); ',
      'bDisplayInSubMenuHeader' => '0',
    ),
    4 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'edit',
      'Url' => '',
      'Script' => 'oBxDolFiles.edit({ID})',
      'Eval' => '$sTitle = _t(\'_Edit\');
if ({Owner} == {iViewer})
return $sTitle;
$mixedCheck = BxDolService::call(\'photos\', \'check_action\', array(\'edit\',\'{ID}\'));
if ($mixedCheck !== false)
return $sTitle;
else
 return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    5 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'remove',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}delete/{ID}/{AlbumUri}/{OwnerName}\', false, \'post\', true); return false;',
      'Eval' => '$sTitle = _t(\'_Delete\');
if ({Owner} == {iViewer})
return $sTitle;
$mixedCheck = BxDolService::call(\'photos\', \'check_delete\', array({ID}));
if ($mixedCheck !== false)
return $sTitle;
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    6 => 
    array (
      'Caption' => '{featuredCpt}',
      'Icon' => 'star-empty',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}feature/{ID}/{featured}\', false, \'post\'); return false;',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    7 => 
    array (
      'Caption' => '{sbs_bx_photos_title}',
      'Icon' => 'paper-clip',
      'Url' => '',
      'Script' => '{sbs_bx_photos_script}',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    8 => 
    array (
      'Caption' => '',
      'Icon' => 'user',
      'Url' => '{evalResult}&make_avatar_from_shared_photo={ID}',
      'Script' => '',
      'Eval' => 'bx_import(\'BxDolPermalinks\');
$o = new BxDolPermalinks();
return $o->permalink(\'modules/?r=avatar/\');',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_photos_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{BaseUri}upload\');',
      'Eval' => 'return (getLoggedId() && BxDolModule::getInstance(\'BxPhotosModule\')->isAllowedAdd()) ? _t(\'_sys_upload\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'picture',
      'Url' => '{BaseUri}albums/my/main/',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_photos_albums_my\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_videos_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{BaseUri}upload\');',
      'Eval' => 'return (getLoggedId() && BxDolModule::getInstance(\'BxVideosModule\')->isAllowedAdd()) ? _t(\'_sys_upload\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'film',
      'Url' => '{BaseUri}albums/my/main/',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_videos_albums_my\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_forum_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => 'javascript:void(0);',
      'Script' => 'return f.newTopic(\'0\')',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_forums_new_topic\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_videos' => 
  array (
    0 => 
    array (
      'Caption' => '{shareCpt}',
      'Icon' => 'share',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{moduleUrl}share/{fileUri}\')',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'exclamation-sign',
      'Url' => '',
      'Script' => 'showPopupAnyHtml(\'{moduleUrl}report/{fileUri}\')',
      'Eval' => 'if ({iViewer}!=0)
return _t(\'_bx_videos_action_report\');
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    2 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'asterisk',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}favorite/{ID}\', false, \'post\'); return false;',
      'Eval' => 'if ({iViewer}==0)
return false;
$sMessage = \'{favorited}\'==\'\' ? \'fave\':\'unfave\';
return _t(\'_bx_videos_action_\' . $sMessage); ',
      'bDisplayInSubMenuHeader' => '0',
    ),
    3 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'edit',
      'Url' => '',
      'Script' => 'oBxDolFiles.edit({ID})',
      'Eval' => '$sTitle = _t(\'_Edit\');
if ({Owner} == {iViewer})
return $sTitle;
$mixedCheck = BxDolService::call(\'videos\', \'check_action\', array(\'edit\',\'{ID}\'));
if ($mixedCheck !== false)
return $sTitle;
else
 return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    4 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'remove',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}delete/{ID}/{AlbumUri}/{OwnerName}\', false, \'post\', true); return false;',
      'Eval' => '$sTitle = _t(\'_Delete\');
if ({Owner} == {iViewer})
return $sTitle;
$mixedCheck = BxDolService::call(\'videos\', \'check_delete\', array({ID}));
if ($mixedCheck !== false)
return $sTitle;
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    5 => 
    array (
      'Caption' => '{featuredCpt}',
      'Icon' => 'star-empty',
      'Url' => '',
      'Script' => 'getHtmlData(\'ajaxy_popup_result_div_{ID}\', \'{moduleUrl}feature/{ID}/{featured}\', false, \'post\'); return false;',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    6 => 
    array (
      'Caption' => '{sbs_bx_videos_title}',
      'Icon' => 'paper-clip',
      'Url' => '',
      'Script' => '{sbs_bx_videos_script}',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_blogs_m' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'rss',
      'Url' => '{site_url}rss_factory.php?action=blogs&pid={owner_id}',
      'Script' => '',
      'Eval' => 'return _t(\'_bx_blog_RSS\');',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '_bx_blog_Back_to_Blog',
      'Icon' => 'book',
      'Url' => '{blog_owner_link}',
      'Script' => '',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    2 => 
    array (
      'Caption' => '_Add Post',
      'Icon' => 'plus',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode}==true)
return (getParam(\'permalinks_blogs\') == \'on\') ? \'blogs/my_page/add/\' : \'modules/boonex/blogs/blogs.php?action=my_page&mode=add\';
else
return null;',
      'bDisplayInSubMenuHeader' => '1',
    ),
    3 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'edit',
      'Url' => '',
      'Script' => 'PushEditAtBlogOverview(\'{blog_id}\', \'{blog_description_js}\', \'{owner_id}\');',
      'Eval' => 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode}==true)
return _t(\'_bx_blog_Edit_blog\');
else
return null;',
      'bDisplayInSubMenuHeader' => '1',
    ),
    4 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'remove',
      'Url' => '',
      'Script' => 'if (confirm(\'{sure_label}\')) window.open (\'{work_url}?action=delete_blog&DeleteBlogID={blog_id}\',\'_self\');',
      'Eval' => 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode}==true)
return _t(\'_bx_blog_Delete_blog\');
else
return null;',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_groups_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '{BaseUri}browse/my&bx_groups_filter=add_group',
      'Script' => '',
      'Eval' => 'return ($GLOBALS[\'logged\'][\'member\'] && BxDolModule::getInstance(\'BxGroupsModule\')->isAllowedAdd()) || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_groups_action_add_group\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'group',
      'Url' => '{BaseUri}browse/my',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_groups_action_my_groups\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_store_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '{BaseUri}browse/my&bx_store_filter=add_product',
      'Script' => '',
      'Eval' => 'return ($GLOBALS[\'logged\'][\'member\'] && BxDolModule::getInstance(\'BxStoreModule\')->isAllowedAdd()) || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_store_action_add_product\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'shopping-cart',
      'Url' => '{BaseUri}browse/my',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_store_action_my_products\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_sites_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '{BaseUri}browse/my/add',
      'Script' => '',
      'Eval' => 'if (($GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\']) && {isAllowedAdd} == 1) return _t(\'_bx_sites_action_add_site\'); return;',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'link',
      'Url' => '{BaseUri}browse/my',
      'Script' => '',
      'Eval' => 'if ($GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\']) return _t(\'_bx_sites_action_my_sites\'); return;',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'modzzz_listing_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'modules/modzzz/listing/|listing_create.png',
      'Url' => '{BaseUri}browse/my&modzzz_listing_filter=add_listing',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_modzzz_listing_action_add_listing\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'modules/modzzz/listing/|listing.png',
      'Url' => '{BaseUri}browse/my',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_modzzz_listing_action_my_listing\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    2 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'modules/modzzz/listing/|listing.png',
      'Url' => '{BaseUri}',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_modzzz_listing_action_listing_home\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_events_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'plus',
      'Url' => '{BaseUri}browse/my&bx_events_filter=add_event',
      'Script' => '',
      'Eval' => 'return ($GLOBALS[\'logged\'][\'member\'] && BxDolModule::getInstance(\'BxEventsModule\')->isAllowedAdd()) || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_events_action_create_event\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'calendar',
      'Url' => '{BaseUri}browse/my',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_bx_events_action_my_events\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'modzzz_FAQ_title' => 
  array (
    0 => 
    array (
      'Caption' => '{evalResult}',
      'Icon' => 'question-sign',
      'Url' => '{BaseUri}',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'logged\'][\'member\'] || $GLOBALS[\'logged\'][\'admin\'] ? _t(\'_modzzz_faq_action_faq_home\') : \'\';',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
  'bx_news' => 
  array (
    0 => 
    array (
      'Caption' => '{sbs_news_title}',
      'Icon' => 'paper-clip',
      'Url' => '',
      'Script' => '{sbs_news_script}',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
    1 => 
    array (
      'Caption' => '{del_news_title}',
      'Icon' => 'remove',
      'Url' => '',
      'Script' => '{del_news_script}',
      'Eval' => '',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
); ?>