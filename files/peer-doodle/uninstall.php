<?php
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();
		
	global $wpdb;	
	$polls_table_name = $wpdb->prefix . 'peer_doodle';
	$wpdb->query( "DROP TABLE IF EXISTS $polls_table_name" );
        delete_option('peer_doodle_page_id');
	?>