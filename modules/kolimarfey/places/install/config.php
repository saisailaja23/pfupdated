<?

$aConfig = array(
	/**
	 * Main Section.
	 */
	'title' => 'Kolimarfey Places',
	'version' => '2.1.0',
	'vendor' => 'kolimarfey',
	'update_url' => '',
	
	'compatible_with' => array(
        '7.0.x'        
    ),

    /**
	 * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
	 */
	'home_dir' => 'kolimarfey/places/',
	'home_uri' => 'places',
	
	'db_prefix' => 'places_',
    'class_prefix' => 'KPlaces',

	/**
	 * Installation/Uninstallation Section.
	 */
    'install' => array(
        'check_dependencies' => 1,
		'show_introduction' => 0,
		'change_permissions' => 1,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 0,
        'recompile_site_stats' => 1,		
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 1,
		'recompile_member_actions' => 1,
		'recompile_tags' => 1,
		'recompile_votes' => 1,
		'recompile_categories' => 0,
		'recompile_search' => 1,
		'recompile_injections' => 0,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 0,
        'clear_db_cache' => 1,
		'show_conclusion' => 0,
	),
    'uninstall' => array (
        'check_dependencies' => 0,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 0,
		'recompile_site_stats' => 1,
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 1,
		'recompile_member_actions' => 1,
		'recompile_tags' => 1,
		'recompile_votes' => 1,
		'recompile_categories' => 0,
		'recompile_search' => 1,
		'recompile_injections' => 0,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 0,
        'clear_db_cache' => 1,
		'show_conclusion' => 0,
    ),

	/**
	 * Dependencies Section
	 */
    'dependencies' => array(
	),    

	/**
	 * Category for language keys.
	 */
	'language_category' => 'Places',

	/**
	 * Permissions Section
	 */
    'install_permissions' => array(
        'writable' => array(
            'application/photos/',
            'application/photos/big/',
            'application/photos/real/',
            'application/videos/',
            'application/kml/',
            'application/icons/',
        ),    
    ),
    'uninstall_permissions' => array(),

	/**
	 * Introduction and Conclusion Section.
	 */
	'install_info' => array(
		'introduction' => '',
		'conclusion' => '',
	),
	'uninstall_info' => array(
		'introduction' => '',
		'conclusion' => '',
	)
);
?>
