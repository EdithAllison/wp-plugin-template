<?php

/**
 * Plugin Name: NNN
 * Plugin URI: https://codeable.io
 * Description: PPP
 * Version: 1.0.0
 * Author: Edith Allison for Codeable
 * Author URI: https://agentur-allison.com
 * Text Domain: abc-def
 * Domain Path: /languages
 *
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 */

namespace AGAL\MMM;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MMM_PLUGIN_PATH', __DIR__ );
define( 'MMM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Autoload classes
spl_autoload_register( function ( $class ) {

	// project-specific namespace prefix
	$prefix = 'AGAL\\MMM\\';

	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/includes/';

	// does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( 0 !== strncmp( $prefix, $class, $len ) ) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = substr( $class, $len );

	// add WP standard "class-" and replace _ with -
	$parts     = explode( '\\', $relative_class );
	$file      = str_replace( '_', '-', strtolower( array_pop( $parts ) ) );
	$class     = 'class-' . $file;
	$folders   = strtolower( implode( DIRECTORY_SEPARATOR, $parts ) );
	$folders   = ! empty( $folders ) ? $folders . DIRECTORY_SEPARATOR : $folders;
	$classpath = $base_dir . $folders . $class . '.php';

	if ( file_exists( $classpath ) ) {
		include_once $classpath;
	}

});

/**
 * Activation
 */
function activate_ab_cd( $network_wide ) {
	Activator::activate( $network_wide );
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_ab_cd' );

/**
 * Plugins that must be active
 */
$dependencies = array(
	'WooCommerce' => 'woocommerce/woocommerce.php',
);
include_once ABSPATH . 'wp-admin/includes/plugin.php';
$missing = array();
foreach ( $dependencies as $name => $plugin ) {
	if ( ! \is_plugin_active( $plugin ) ) {
		$missing[] = $name;
	}
}
define( 'MMM_MISSING', implode( ', ', $missing ) );
function dependency_warning() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php /* translators: %s Missing Plugins */ printf( esc_html__( 'Please enable %s', 'abc-def' ), esc_html( MMM_MISSING ) ); ?></p>
	</div>
	<?php
}
if ( ! empty( $missing ) ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\\dependency_warning' );
	deactivate_plugins( 'abc-def/abc-def.php' );
	return;
}

// Initiate the plugin
add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );
function init() {
	$plugin = new Core();
	$plugin->run();
}

// HPOS compatibility
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );