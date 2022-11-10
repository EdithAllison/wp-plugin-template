<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://agentur-allison.com/
 * @since      1.0.0
 */

namespace AGAL\PLUGINNAMESPACE;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Core {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 */
	protected $plugin_name;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'plugin-name';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$i18n = new I18N();
		add_action( 'plugins_loaded', array( $i18n, 'load_plugin_textdomain' ) );

	}


	/**
	 * Register the stylesheets
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __DIR__ ) . 'assets/css/plugin-name.css', '' , filemtime( plugin_dir_path( __DIR__ ) . 'assets/css/plugin-name.css' ), 'all' );

	}

	/**
	 * Register the JavaScript
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$suffix = SCRIPT_DEBUG === true ? '' : '.min';

		wp_register_script( $this->plugin_name, plugin_dir_url( __DIR__ ) . 'assets/js/plugin-name' . $suffix . '.js', array( 'jquery' ), filemtime( plugin_dir_path( __DIR__ ) . 'assets/js/plugin-name' . $suffix . '.js' ), true );

		// Create any data in PHP that we may need to use in our JS file
		$params = array(
			'ajax_url'     => admin_url( 'admin-ajax.php' ),
			'plugin_name_nonce'   => wp_create_nonce( 'plugin_name' ),
		);

		// Assign that data to our script as an JS object
		wp_localize_script( $this->plugin_name, 'params', $params );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Register the hooks
	 *
	 * @since 1.0.0
	 */
	private function hooks() {

		// enqueue styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );

	}

	/**
	* Register filters
	*
	* since 1.0.0
	*/
	private function filters() {

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

			add_action( 'wp_ajax_plugin_name_' . $ajax_event, array( __NAMESPACE__ . '\AJAX', $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_plugin_name_' . $ajax_event, array( __NAMESPACE__ . '\AJAX' , $ajax_event ) );

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
		$this->hooks();
		$this->filters();
		$this->ajax_actions();

	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 */
	public function get_plugin_name() {

		return $this->plugin_name;

	}

}
