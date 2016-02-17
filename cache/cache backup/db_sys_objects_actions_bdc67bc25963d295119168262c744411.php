<?php $mixedData=array (
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
  'ProfileTitle' => 
  array (
    0 => 
    array (
      'Caption' => '{cpt_am_friend_add}',
      'Icon' => 'plus',
      'Url' => '',
      'Script' => '{evalResult}',
      'Eval' => 'return $GLOBALS[\'oTopMenu\']->getScriptFriendAdd({ID}, {member_id}, false);',
      'bDisplayInSubMenuHeader' => '1',
    ),
    1 => 
    array (
      'Caption' => '{cpt_am_friend_accept}',
      'Icon' => 'plus',
      'Url' => '',
      'Script' => '{evalResult}',
      'Eval' => 'return $GLOBALS[\'oTopMenu\']->getScriptFriendAccept({ID}, {member_id}, false);',
      'bDisplayInSubMenuHeader' => '1',
    ),
    2 => 
    array (
      'Caption' => '{cpt_am_friend_cancel}',
      'Icon' => 'minus',
      'Url' => '',
      'Script' => '{evalResult}',
      'Eval' => 'return $GLOBALS[\'oTopMenu\']->getScriptFriendCancel({ID}, {member_id}, false);',
      'bDisplayInSubMenuHeader' => '1',
    ),
    3 => 
    array (
      'Caption' => '{cpt_am_profile_message}',
      'Icon' => 'envelope',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'oTopMenu\']->getUrlProfileMessage({ID});',
      'bDisplayInSubMenuHeader' => '1',
    ),
    4 => 
    array (
      'Caption' => '{cpt_am_profile_account_page}',
      'Icon' => 'dashboard',
      'Url' => '{evalResult}',
      'Script' => '',
      'Eval' => 'return $GLOBALS[\'oTopMenu\']->getUrlAccountPage({ID});',
      'bDisplayInSubMenuHeader' => '1',
    ),
  ),
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
      'Icon' => 'asterisk',
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
      'Caption' => '{TitleAvatar}',
      'Icon' => 'user',
      'Url' => '{evalResult}&make_avatar_from_shared_photo={ID}',
      'Script' => '',
      'Eval' => 'bx_import(\'BxDolPermalinks\');
$o = new BxDolPermalinks();
return $o->permalink(\'modules/?r=avatar/\');',
      'bDisplayInSubMenuHeader' => '0',
    ),
  ),
); ?>