<?php
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
$aConfig = array(
	/**
	 * Main Section.
	 */	
	'title' => 'Dolphin Subscriptions', 
    'version' => '2.1.0',
	'vendor' => 'Harvest-Media.com', 
	'update_url' => '', 
	
	'compatible_with' => array( // module compatibility
        '7.0.x'
    ),

    /**
	 * 'home_dir' and 'home_uri' 
	 */
	'home_dir' => 'harvest/memberships/', 
	'home_uri' => 'memberships', 
	
	'db_prefix' => 'dol_subs_', 
    'class_prefix' => 'HmSubs', 

	'install' => array(
        'check_dependencies' => 1,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 1,
        'recompile_site_stats' => 0,		
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 1,
		'recompile_comments' => 0,
		'recompile_member_actions' => 1,
		'recompile_tags' => 0,
		'recompile_votes' => 0,
		'recompile_categories' => 0,
		'recompile_search' => 0,
		'recompile_injections' => 1,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 1,
		'show_conclusion' => 1,
	),
	'uninstall' => array (
        'check_dependencies' => 0,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 1,
		'recompile_site_stats' => 0,
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 1,
		'recompile_comments' => 0,
		'recompile_member_actions' => 1,
		'recompile_tags' => 0,
		'recompile_votes' => 0,
		'recompile_categories' => 0,
		'recompile_search' => 0,
		'recompile_injections' => 1,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 1,
		'show_conclusion' => 1,
    ),

	/**
	 * Dependencies Section
	 */
    'dependencies' => array(

	),

	/**
	 * Category for language keys, all language keys will be places to this category, but it is still good practive to name each language key with module prefix, to avoid conflicts with other mods.
	 */
	'language_category' => 'Dolphin Subscriptions',

	/**
	 * Permissions Section, 
	 */
	'install_permissions' => array(
	   'writable' => array('images/icons')
	),
    'uninstall_permissions' => array(),

	/**
	 * Introduction and Conclusion Section, reclare files with info here, see examples in other BoonEx modules
	 */
	'install_info' => array(
		'introduction' => '',
		'conclusion' => ''
	),
	'uninstall_info' => array(
		'introduction' => '',
		'conclusion' => ''
	)
);

?>
