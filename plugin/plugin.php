<?php

/*
* Plugin Name: Plugin+Name
* Plugin URI:
* Description:
* Version: 1.0.0
* Author: Edith Allison
* Author URI: https://agentur-alllison.com
* Text Domain: agentur_allison
* Domain Path: /languages
*/

namespace AGAL\PLUGINNAMESPACE;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLUGINNAMESPACE_PLUGIN_PATH' , __DIR__ );

// Autoload classes
spl_autoload_register( function ( $class ) {

	// project-specific namespace prefix
	$prefix = 'AGAL\\PLUGINNAMESPACE\\';

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
	$folders   = strtolower( implode(DIRECTORY_SEPARATOR, $parts ) );
	$folders   = !empty( $folders ) ? $folders . DIRECTORY_SEPARATOR : $folders;
	$classpath = $base_dir . $folders . $class . '.php';

	if ( file_exists( $classpath ) ) {
		include_once $classpath;
	}

});

/**
* Plugins that must be active
*/
$dependecies = array(
	'WooCommerce' => 'woocommerce/woocommerce.php',
);
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$missing= array();
foreach( $dependecies as $name => $plugin ) {
	if( ! \is_plugin_active( $plugin ) ) {
		$missing[] = $name;
	}
}
define( 'PLUGINNAMESPACE_MISSING' , implode( ', ', $missing ) );
function dependency_warning() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e('Please enable ' . PLUGINNAMESPACE_MISSING, 'agentur_allison' ); ?></p>
	</div>
	<?php
}
if( !empty( $missing ) ) {
	add_action( 'admin_notices', 'dependency_warning' );
	return;
}

// Initiate the plugin
add_action( 'plugins_loaded',  __NAMESPACE__ . '\\init' );
function init() {
	$plugin = new Core();
	$plugin->run();
}