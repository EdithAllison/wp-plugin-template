<?php

/**
 * The file that defines the Activation tasks
 *
 * @link       https://agentur-allison.com/
 * @since      1.0.0
 */

namespace AGAL\MMM;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Activator {

	/**
	 * Database version.
	 *
	 * @since 1.0.0
	 */
	const DB_VERSION = '1.0.0';

	/**
	 * Activate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide Whether to activate network-wide.
	 */
	public static function activate( $network_wide ) {

		global $wpdb;

		if ( is_multisite() && $network_wide ) {

			// Get all blogs in the network and activate plugin on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			foreach ( $blog_ids as $blog_id ) {

				switch_to_blog( $blog_id );

			//	self::create_table();

				restore_current_blog();
			}

		} else {

			// self::create_table();

		}

	}

	/**
	 * Create database tables.
	 *
	 * @since 1.0.0
	 */
	private static function create_table() {

		global $wpdb;

		$installed_ver = get_option( 'ab-cd_db_version' );

		if ( empty( $installed_ver ) || $installed_ver !== self::DB_VERSION ) {

			$table_name = $wpdb->prefix . 'mytable';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id bigint NOT NULL AUTO_INCREMENT,
				number varchar(255) NOT NULL,
				user bigint NOT NULL,
				text tinytext NOT NULL,
				textlong longtext,
				date_modified datetime DEFAULT NULL,
				date_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id),
				KEY user_id (user)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			dbDelta( $sql );

			update_option( 'ab-cd_db_version', self::DB_VERSION );

		}

	}


	/**
	 * On Multisite when new site is added.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Site $new_site The new site object.
	 */
	public static function add_blog( $new_site ) {

		if ( is_plugin_active_for_network( 'abc-def/abc-def.php' ) ) {

			switch_to_blog( $new_site->blog_id );

		//	self::create_table();

			restore_current_blog();

		}

	}



}