<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vamtam color control.
 *
 * A base control for creating color control. Displays a color picker field with
 * an alpha slider. Includes a customizable color palette that can be preset by
 * the user. Accepts a `scheme` argument that allows you to set a value from the
 * active color scheme as the default value returned by the control.
 *
 * @since 1.0.0
 */
class Vamtam_Control_Color extends \Elementor\Base_Data_Control {

	/**
	 * Get color control type.
	 *
	 * Retrieve the control type, in this case `color`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'vamtam-color';
	}

	/**
	 * Enqueue color control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the color
	 * control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
		$suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';

		wp_enqueue_style( 'vamtam-color-picker', VAMTAM_ELEMENTOR_INT_URL . '/assets/css/wp-color-picker/vamtam-color-picker.css', [ 'wp-color-picker' ], VamtamElementorIntregration::PLUGIN_VERSION );

		// Switch with our own custom Color Picker with accents support.
		wp_dequeue_script( 'wp-color-picker-alpha' );
		wp_deregister_script( 'wp-color-picker-alpha' );
		wp_register_script( 'wp-color-picker-alpha', VAMTAM_ELEMENTOR_INT_URL . '/assets/js/wp-color-picker/wp-color-picker-alpha' . $suffix . '.js', [ 'wp-color-picker' ], VamtamElementorIntregration::PLUGIN_VERSION, true );
		wp_enqueue_script( 'wp-color-picker-alpha' );

		global $vamtam_theme;

		wp_localize_script(
			'wp-color-picker-alpha', 'VamtamColorPickerStrings', array(
				'accents'             => isset( $vamtam_theme[ 'accent-color' ] ) ? array_filter( $vamtam_theme[ 'accent-color' ], function( $key ) {
					return is_numeric( $key );
				}, ARRAY_FILTER_USE_KEY ) : [],
			)
		);
	}

	/**
	 * Render color control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<# var defaultValue = '', dataAlpha = '';
			if ( data.default ) {
				defaultValue = ' data-default-color=' + data.default; // Quotes added automatically.
			}

			if ( data.alpha ) {
				dataAlpha = ' data-alpha=true';
			} #>
		<div class="elementor-control-field">
			<label class="elementor-control-title">
				<# if ( data.label ) { #>
					{{{ data.label }}}
				<# } #>
				<# if ( data.description ) { #>
					<span class="elementor-control-field-description">{{{ data.description }}}</span>
				<# } #>
			</label>
			<div class="elementor-control-input-wrapper">
				<input data-setting="{{ name }}" type="text" placeholder="<?php echo esc_attr( 'Hex/rgba', 'elementor' ); ?>" {{ defaultValue }}{{ dataAlpha }} />
			</div>
		</div>
		<?php
	}

	/**
	 * Get color control default settings.
	 *
	 * Retrieve the default settings of the color control. Used to return the default
	 * settings while initializing the color control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'alpha' => true,
			'scheme' => '',
		];
	}
}
