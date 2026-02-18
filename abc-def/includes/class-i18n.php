<?php

/**
 * The file that defines the internationalization functionality
 *
 * @link       https://agentur-allison.com/
 * @since      1.0.0
 */

namespace AGAL\MMM;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class I18N {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			Core::PLUGIN_NAME,
			false,
			dirname( plugin_basename( __DIR__ ) ) . '/languages/'
		);

	}

}
