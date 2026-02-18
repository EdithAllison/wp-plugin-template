<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// global $wpdb;
//
// if ( is_multisite() ) {
//
// 	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
//
// 	foreach ( $blog_ids as $blog_id ) {
// 		switch_to_blog( $blog_id );
// 		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );
// 		delete_option( 'ab-cd_db_version' );
// 		restore_current_blog();
// 	}
//
// } else {
//
// 	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );
// 	delete_option( 'ab-cd_db_version' );
//
// }