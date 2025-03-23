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
if ( ! class_exists( 'PLUGIN_CLASS_Actions_Filters' ) ) {

	/**
	 * Class for custom actions and filters.
	 */
	class PLUGIN_CLASS_Actions_Filters {

		/**
		 * Constructor for class.
		 */
		public function __construct() {
			// Hook goes here.
		}
	}

	new PLUGIN_CLASS_Actions_Filters();
}
