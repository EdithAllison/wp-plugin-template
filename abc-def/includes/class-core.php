<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://agentur-allison.com/
 * @since      1.0.0
 */

namespace AGAL\MMM;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Core {

	/**
	 * Full plugin name
	 * The unique identifier of this plugin.
	 * Used for translations
	 * matches the folder string
	 *
	 * @since    1.0.0
	 */
	public static $plugin_name;

	/**
	 * Short plugin name
	 * Used for JS & CSS
	 */
	public static $short_plugin_name;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		self::$plugin_name = 'abc-def';
		self::$short_plugin_name = 'ab-cd';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$i18n = new I18N();
		add_action( 'init', array( $i18n, 'load_plugin_textdomain' ) );

	}


	/**
	 * Register the stylesheets
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( self::$short_plugin_name, plugin_dir_url( __DIR__ ) . 'assets/build/css/ab-cd.css', '' , filemtime( plugin_dir_path( __DIR__ ) . 'assets/build/css/ab-cd.css' ), 'all' );

	}

	/**
	 * Register the JavaScript
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if( SCRIPT_DEBUG === true || wp_get_environment_type() === 'local' ) {
			wp_register_script( self::$short_plugin_name, plugin_dir_url( __DIR__ ) . 'assets/src/js/ab-cd.js', array( 'jquery' ), filemtime( plugin_dir_path( __DIR__ ) . 'assets/src/js/ab-cd.js' ), true );
		} else {
			wp_register_script( self::$short_plugin_name, plugin_dir_url( __DIR__ ) . 'assets/build/js/ab-cd.min.js', array( 'jquery' ), '1.0.0', true );
		}

		// Create any data in PHP that we may need to use in our JS file
		$params = array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'ab-cd_nonce' => wp_create_nonce( 'ab-cd' ),
		);

		// Assign that data to our script as an JS object
		wp_localize_script( self::$short_plugin_name, 'params', $params );
		wp_enqueue_script( self::$short_plugin_name );

	}

	/**
	 * Register the hooks & filters
	 *
	 * @since 1.0.0
	 */
	private function hooks_filters() {

		// enqueue styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );

	}

	/**
	* Register Ajax actions
	*
	* @since 1.0.0
	*/
	public function ajax_actions() {

		/* define Ajax events. Set TRUE if "nopriv" is required */
		$ajax_events = array(
		//	'event' => true,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {

			add_action( 'wp_ajax_ab-cd_' . $ajax_event, array( __NAMESPACE__ . '\AJAX', $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_ab-cd_' . $ajax_event, array( __NAMESPACE__ . '\AJAX' , $ajax_event ) );

			}
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->set_locale();
		$this->hooks_filters();
		$this->ajax_actions();

	}

}
