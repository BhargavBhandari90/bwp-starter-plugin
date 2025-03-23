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
if ( ! class_exists( 'PLUGIN_CLASS_Admin_Core' ) ) {

	/**
	 * Class for fofc core.
	 */
	class PLUGIN_CLASS_Admin_Core {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Plugin's setting page.
			add_action( 'admin_menu', array( $this, 'PLUGIN_FPREFIX_settings_page' ) );

			// Register settings fields.
			add_action( 'admin_init', array( $this, 'PLUGIN_FPREFIX_register_settings' ) );

		}

		/**
		 * Add settings page.
		 *
		 * @return void
		 */
		public function PLUGIN_FPREFIX_settings_page() {
			add_menu_page(
				esc_html__( 'PLUGIN_NAME Settings', 'PLUGIN_SLUG' ),
				esc_html__( 'PLUGIN_NAME', 'PLUGIN_SLUG' ),
				'manage_options',
				'PLUGIN_SLUG',
				array( $this, 'PLUGIN_FPREFIX_admin_settings' ),
				'dashicons-controls-volumeon',
				80
			);
		}

		/**
		 * Settings fields.
		 *
		 * @return void
		 */
		public function PLUGIN_FPREFIX_admin_settings() {
			?>
			<div class="wrap">
				<h1><?php echo __( 'Plugin Name Settings', 'PLUGIN_SLUG' ); ?></h1>
				<form method="post" action="options.php" novalidate="novalidate">
					<?php settings_fields( 'plugin_name_settings' ); ?>
					<table class="form-table" role="presentation">
					<?php do_settings_sections( 'PLUGIN_SLUG' ); ?>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}

		/**
		 * Register setting fields.
		 *
		 * @return void
		 */
		public function PLUGIN_FPREFIX_register_settings() {

			register_setting( 'plugin_name_settings', 'pn_setting' );

			// register a new section in the "reading" page
			add_settings_section(
				'plugin_name_settings_section',
				esc_html__( 'Section Name', 'PLUGIN_SLUG' ),
				array( $this, 'PLUGIN_FPREFIX_setting_section_cb' ),
				'PLUGIN_SLUG',
				array(
					'description' => esc_html__( 'Section Description.', 'PLUGIN_SLUG' ),
				)
			);

			add_settings_field(
				'field_key',
				esc_html__( 'Field One', 'PLUGIN_SLUG' ),
				array( $this, 'PLUGIN_FPREFIX_setting_field_callback' ),
				'PLUGIN_SLUG',
				'plugin_name_settings_section',
				array(
					'name'        => 'field_key',
					'class'       => 'regular-text',
					'type'        => 'text',
					'description' => 'Field Description',
				)
			);
		}

		/**
		 * Settings description.
		 *
		 * @param  array $args array of settings parameters.
		 * @return void
		 */
		public function PLUGIN_FPREFIX_setting_section_cb( $args ) {
			echo wp_sprintf(
				'<p>%1$s</p>',
				wp_kses_post( $args['description'] )
			);
		}

		/**
		 * Display fields.
		 *
		 * @param array $args array of settings.
		 * @return void
		 */
		public function PLUGIN_FPREFIX_setting_field_callback( $args ) {

			$field_name = $args['name'];
			$settings   = get_option( 'pn_setting' );
			$value      = isset( $settings[ $field_name ] ) ? $settings[ $field_name ] : '';
			$class      = $args['class'];

			switch ( $args['type'] ) {

				case 'text':
					echo wp_sprintf(
						'<input type="text" name="pn_setting[%s]" value="%s" class="%s" /><p class="description">%s</p>',
						esc_attr( $field_name ),
						esc_attr( $value ),
						esc_attr( $class ),
						wp_kses_post( $args['description'] )
					);
					break;

				case 'textarea':
					echo wp_sprintf(
						'<textarea name="pn_setting[%s]" class="%s" rows="5" cols="50">%s</textarea><p class="description">%s</p>',
						esc_attr( $field_name ),
						esc_attr( $class ),
						esc_textarea( $value ),
						wp_kses_post( $args['description'] )
					);
					break;
			}
		}
	}

	new PLUGIN_CLASS_Admin_Core();
}
