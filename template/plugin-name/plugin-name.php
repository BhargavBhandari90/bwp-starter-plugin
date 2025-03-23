<?php
/**
 * Plugin Name:     PLUGIN_NAME
 * Description:     PLUGIN_DESCRIPTION
 * Author:          PLUGIN_AUTHOR
 * Author URI:      https://example.com/
 * Text Domain:     PLUGIN_SLUG
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         PLUGIN_PACKAGE
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main file, contains the plugin metadata and activation processes
 *
 * @package    PLUGIN_PACKAGE
 */
if ( ! defined( 'PLUGIN_CPREFIX_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'PLUGIN_CPREFIX_VERSION', '1.0.0' );
}

if ( ! defined( 'PLUGIN_CPREFIX_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'PLUGIN_CPREFIX_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_CPREFIX_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'PLUGIN_CPREFIX_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_CPREFIX_BASE_NAME' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'PLUGIN_CPREFIX_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_CPREFIX_MAIN_FILE' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'PLUGIN_CPREFIX_MAIN_FILE', __FILE__ );
}

/**
 * Include files.
 */
$files = array(
	'app/includes/common-functions',
	'app/main/class-main',
	'app/admin/class-admin-main',
);

if ( ! empty( $files ) ) {

	foreach ( $files as $file ) {

		// Include functions file.
		if ( file_exists( PLUGIN_CPREFIX_PATH . $file . '.php' ) ) {
			require PLUGIN_CPREFIX_PATH . $file . '.php';
		}
	}
}

/**
 * Plugin Setting page.
 *
 * @param array $links Array of plugin links.
 * @return array Array of plugin links.
 */
function PLUGIN_FPREFIX_settings_link( $links ) {

	$settings_link = sprintf(
		'<a href="%1$s">%2$s</a>',
		'admin.php?page=ai-audio-responder',
		esc_html__( 'Settings', 'bpai-core' )
	);

	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links_' . PLUGIN_CPREFIX_BASE_NAME, 'PLUGIN_FPREFIX_settings_link' );

/**
 * Apply translation file as per WP language.
 */
function PLUGIN_FPREFIX_text_domain_loader() {

	// Get mo file as per current locale.
	$mofile = PLUGIN_CPREFIX_PATH . 'languages/bp-ai-' . get_locale() . '.mo';

	// If file does not exists, then apply default mo.
	if ( ! file_exists( $mofile ) ) {
		$mofile = PLUGIN_CPREFIX_PATH . 'languages/default.mo';
	}

	if ( file_exists( $mofile ) ) {
		load_textdomain( 'PLUGIN_SLUG', $mofile );
	}
}

add_action( 'plugins_loaded', 'PLUGIN_FPREFIX_text_domain_loader' );
