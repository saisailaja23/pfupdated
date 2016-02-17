<?php
$aConfig = array(
	/**
	 * Main Section.
	 */	
	'title' => 'Tour Guide',
    'version' => '7.01',
	'vendor' => 'YouNet Company',
	'update_url' => '',
	
	'compatible_with' => array(
		'7.0.1', '7.0.2', '7.0.3', '7.0.4', '7.0.5', '7.0.6', '7.0.7', '7.0.8', '7.0.9'
    ),

    /**
	 * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
	 */
	'home_dir' => 'younet/tour_guide/',
	'home_uri' => 'tour_guide',
	
	'db_prefix' => 'yn_tour_',
	'class_prefix' => 'YnTourGuide',
    
	/**
	 * Installation/Uninstallation Section.
	 */
    'install' => array(
        'check_dependencies' => 0,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
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
		'recompile_permalinks' => 1,
		'recompile_alerts' => 0,
        'clear_db_cache' => 0,
		'show_conclusion' => 1,
	),
    'uninstall' => array (
        'check_dependencies' => 0,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
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
		'recompile_permalinks' => 1,
		'recompile_alerts' => 0,
        'clear_db_cache' => 0,
		'show_conclusion' => 1,
    ),

	/**
	 * Dependencies Section
	 */
    'dependencies' => array(
	),

	/**
	 * Category for language keys.
	 */
	'language_category' => 'Younet Tour Guide',

	/**
	 * Permissions Section
	 */
	'install_permissions' => array(),
    'uninstall_permissions' => array(),

	/**
	 * Introduction and Conclusion Section.
	 */
	'install_info' => array(
		'introduction' => '',
		'conclusion' => 'inst_concl.html'
	),
	'uninstall_info' => array(
		'introduction' => '',
		'conclusion' => 'uninst_concl.html'
	)
);