<?php

/***************************************************************************
* Date				: Sun August 1, 2010
* Copywrite			: (c) 2009, 2010 by Dean J. Bassett Jr.
* Website			: http://www.deanbassett.com
*
* Product Name		: Deanos Tools
* Product Version	: 1.8
*
* IMPORTANT: This is a commercial product made by Dean Bassett Jr.
* and cannot be modified other than personal use.
*  
* This product cannot be redistributed for free or a fee without written
* permission from Dean Bassett Jr.
*
***************************************************************************/


$aConfig = array(
	/**
	 * Main Section.
	 */
	'title' => 'Deanos Tools',
	'version' => '1.8.0',
	'vendor' => 'Deano',
	'update_url' => '',
	'compatible_with' => array(
		'7.x.x'
	),

    /**
	 * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
	 */
	'home_dir' => 'deano/deanos_tools/',
	'home_uri' => 'deanos_tools',
	
	'db_prefix' => 'bx_DeanosTools_',
	'class_prefix' => 'BxDeanosTools',
	/**
	 * Installation/Uninstallation Section.
	 */
	'install' => array(
		'show_introduction' => 1,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_global_paramaters' => 1,
		'recompile_main_menu' => 0,
		'recompile_member_menu' => 0,
		'recompile_site_stats' => 0,
		'recompile_page_builder' => 0,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 0,
		'recompile_member_actions' => 0,
		'recompile_tags' => 0,
		'recompile_votes' => 0,
		'recompile_categories' => 0,
		'recompile_search' => 0,
		'recompile_injections' => 1,
		'recompile_permalinks' => 0,
		'recompile_alerts' => 1,
		'clear_db_cache' => 0,
		'show_conclusion' => 1
	),
	'uninstall' => array (
		'show_introduction' => 1,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_global_paramaters' => 1,
		'recompile_main_menu' => 0,
		'recompile_member_menu' => 0,
		'recompile_site_stats' => 0,
		'recompile_page_builder' => 0,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 0,
		'recompile_member_actions' => 0,
		'recompile_tags' => 0,
		'recompile_votes' => 0,
		'recompile_categories' => 0,
		'recompile_search' => 0,
		'recompile_injections' => 1,
		'recompile_permalinks' => 0,
		'recompile_alerts' => 1,
		'clear_db_cache' => 0,
		'show_conclusion' => 1
	),
	/**
	* Dependencies Section
	*/
	'dependencies' => array(),
	/**
	 * Category for language keys.
	 */
	'language_category' => 'Deano',
	/**
	 * Permissions Section
	 */
	'install_permissions' => array(),
    'uninstall_permissions' => array(),
	/**
	 * Introduction and Conclusion Section.
	 */
	'install_info' => array(
		'introduction' => 'inst_intro.html',
		'conclusion' => 'inst_concl.html'
	),
	'uninstall_info' => array(
		'introduction' => 'uninst_intro.html',
		'conclusion' => 'uninst_concl.html'
	)
);
?>
