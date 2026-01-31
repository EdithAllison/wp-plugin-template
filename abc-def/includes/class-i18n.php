<?php

/**
 * The file that defines the internationalization functionality
 *
 * @link       https://agentur-allison.com/
 * @since      1.0.0
 */

namespace AGAL\MMM;
use AGAL\MMM\Core as Core;

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

		$core = new Core();

		load_plugin_textdomain(
			$core::$plugin_name,
			false,
			dirname( plugin_basename( __DIR__ ) ) . '/languages/'
		);

	}

}
