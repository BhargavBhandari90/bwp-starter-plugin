<?php
/**
 * Class for custom work.
 *
 * @package PLUGIN_PACKAGE
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'PLUGIN_CLASS_Core' ) ) {

	/**
	 * Class for fofc core.
	 */
	class PLUGIN_CLASS_Core {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			$files = array(
				'app/main/class-custom-actions-filters',
			);

			foreach ( $files as $file ) {
				// Include functions file.
				if ( file_exists( PLUGIN_CPREFIX_PATH . $file . '.php' ) ) {
					require PLUGIN_CPREFIX_PATH . $file . '.php';
				}
			}

			// Add custom style and script.
			add_action( 'wp_enqueue_scripts', array( $this, 'PLUGIN_FPREFIX_enqueue_style_script' ) );
		}


		public function PLUGIN_FPREFIX_enqueue_style_script() {

			// Custom plugin script.
			wp_enqueue_style(
				'plugin-name-style',
				PLUGIN_CPREFIX_URL . 'assets/css/plugin.min.css',
				'',
				PLUGIN_CPREFIX_VERSION
			);

			// Custom plugin script.
			wp_enqueue_script(
				'bpai-script',
				PLUGIN_CPREFIX_URL . 'assets/js/plugin.min.js',
				array( 'jquery' ),
				PLUGIN_CPREFIX_VERSION,
				true
			);
		}
	}

	new PLUGIN_CLASS_Core();
}
