<?php
namespace VamtamElementor\ElementorHooks;

// Elementor actions.
add_action( 'elementor/widgets/widgets_registered',      __NAMESPACE__ . '\register_widgets', 999999 );
add_action( 'elementor/controls/controls_registered',    __NAMESPACE__ . '\register_controls' );
add_action( 'elementor/frontend/after_enqueue_styles',   __NAMESPACE__ . '\enqueue_frontend_styles' );
add_action( 'elementor/editor/before_enqueue_scripts',   __NAMESPACE__ . '\enqueue_editor_scripts' );
add_action( 'elementor/frontend/before_enqueue_scripts', __NAMESPACE__ . '\frontend_before_enqueue_scripts' );


// TODO: To be removed when https://github.com/elementor/elementor/issues/9907 is fixed by Elementor.
add_action( 'elementor/frontend/after_enqueue_styles', __NAMESPACE__ . '\force_enqueue_fa4_icons', -10 );

// TODO: To be removed when https://github.com/elementor/elementor/issues/10649 is fixed by Elementor.
add_action( 'elementor/frontend/after_enqueue_styles',   __NAMESPACE__ . '\change_elementor_frontend_css' );

// add_action( 'elementor/preview/enqueue_styles',          'Vamtam_Elementor_Widgets_Handler::enqueue_theme_editor_styles' );

// Elementor filters
add_filter( 'elementor/controls/animations/additional_animations', __NAMESPACE__ . '\vamtam_elementor_additional_animations' );
add_filter( 'elementor/controls/hover_animations/additional_animations', __NAMESPACE__ . '\vamtam_elementor_additional_hover_animations' );

function vamtam_elementor_additional_animations( $additional_anims ) {
	if ( current_theme_supports( 'vamtam-elementor-widgets', 'widgets--horizontal-grow-anims' ) ) {
		if ( ! isset( $additional_anims[ 'Vamtam' ] ) ) {
			$additional_anims[ 'Vamtam' ] = [];
		}
		$additional_anims[ 'Vamtam' ] = $additional_anims[ 'Vamtam' ] + [
			'growFromLeft' => __( 'Grow From Left', 'vamtam-elementor-integration' ),
			'growFromRight' => __( 'Grow From Right', 'vamtam-elementor-integration' ),
		];
	}
	if ( current_theme_supports( 'vamtam-elementor-widgets', 'widgets--horizontal-grow-scroll-based-anims' ) ) {
		if ( ! isset( $additional_anims[ 'Vamtam' ] ) ) {
			$additional_anims[ 'Vamtam' ] = [];
		}
		$additional_anims[ 'Vamtam' ] = $additional_anims[ 'Vamtam' ] + [
			'growFromLeftScroll' => __( 'Grow From Left (Scroll Based)', 'vamtam-elementor-integration' ),
			'growFromRightScroll' => __( 'Grow From Right (Scroll Based)', 'vamtam-elementor-integration' ),
		];
	}
	if ( current_theme_supports( 'vamtam-elementor-widgets', 'image--grow-with-scale-anims' ) ) {
		if ( ! isset( $additional_anims[ 'Vamtam' ] ) ) {
			$additional_anims[ 'Vamtam' ] = [];
		}
		$additional_anims[ 'Vamtam' ] = $additional_anims[ 'Vamtam' ] + [
			'imageGrowWithScaleLeft' => __( 'Image - Grow With Scale (Left)', 'vamtam-elementor-integration' ),
			'imageGrowWithScaleRight' => __( 'Image - Grow With Scale (Right)', 'vamtam-elementor-integration' ),
			'imageGrowWithScaleTop' => __( 'Image - Grow With Scale (Top)', 'vamtam-elementor-integration' ),
			'imageGrowWithScaleBottom' => __( 'Image - Grow With Scale (Bottom)', 'vamtam-elementor-integration' ),
		];
	}
	return $additional_anims;
}

function vamtam_elementor_additional_hover_animations( $additional_hover_anims ) {
	if ( current_theme_supports( 'vamtam-elementor-widgets', 'form--prefix-grow-hover-anims' ) ) {
		$additional_hover_anims = $additional_hover_anims + [
			'prefix-grow' => __( 'Prefix Grow', 'vamtam-elementor-integration' ),
			'prefix-grow-alt' => __( 'Prefix Grow Alt', 'vamtam-elementor-integration' ),
		];
	}
	return $additional_hover_anims;
}

function register_widgets() {
	\Vamtam_Elementor_Widgets_Handler::instance();
}

function register_controls() {
	foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/controls/*.php' ) as $control ) {
		require_once $control;
	}

	$controls_manager = \Elementor\Plugin::$instance->controls_manager;
	$controls_manager->register_control( 'vamtam-color', new \Vamtam_Control_Color() );
}

function frontend_before_enqueue_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Enqueue JS for Elementor (frontend).
	wp_enqueue_script(
		'vamtam-elementor-frontend',
		VAMTAM_ELEMENTOR_INT_URL . '/assets/js/vamtam-elementor-frontend' . $suffix . '.js',
		[
			'elementor-frontend', // dependency
		],
		\VamtamElementorIntregration::PLUGIN_VERSION,
		true //in footer
	);
}

function enqueue_frontend_styles() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	// Vamtam Instagram Feed css.
	if ( ! vamtam_theme_supports( 'vamtam-instagram-feed--disabled' ) ) {
		wp_enqueue_style(
			'vamtam-instagram-feed',
			VAMTAM_ELEMENTOR_INT_URL . '/assets/css/widgets/vamtam-instagram-feed/vamtam-instagram-feed' . $suffix . '.css',
			[],
			\VamtamElementorIntregration::PLUGIN_VERSION
		);
	}
}

function enqueue_editor_scripts() {
	// Enqueue CSS for Elementor editor.
	wp_enqueue_style( 'vamtam-elementor', VAMTAM_ELEMENTOR_INT_URL . '/assets/css/vamtam-elementor.css', [], \VamtamElementorIntregration::PLUGIN_VERSION );

	// Export theme options (css vars) to Elementor editor.
	\VamtamEnqueues::print_theme_options();

	// Enqueue JS for Elementor editor.
	wp_enqueue_script( 'vamtam-elementor', VAMTAM_ELEMENTOR_INT_URL . '/assets/js/vamtam-elementor.js', [], \VamtamElementorIntregration::PLUGIN_VERSION, true );

	global $vamtam_theme;

	wp_localize_script(
		'vamtam-elementor-integration', 'VamtamColorPickerStrings', array(
			'accents'             => isset( $vamtam_theme[ 'accent-color' ] ) ? array_filter( $vamtam_theme[ 'accent-color' ], function( $key ) {
				return is_numeric( $key );
			}, ARRAY_FILTER_USE_KEY ) : [],
		)
	);
}

function force_enqueue_fa4_icons() {
	if ( empty( get_option( 'elementor_load_fa4_shim', false ) ) ) {
		update_option( 'elementor_load_fa4_shim', 'yes' );
	}
}

/*
	TODO: Maybe remove after v3 is widely adopted.
	Patch by: https://github.com/elementor/elementor/issues/10649#issuecomment-604813342
*/
function change_elementor_frontend_css() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) || \VamtamElementorBridge::elementor_is_v3_or_greater() ) {
		return;
	}

	global $wp_styles;

	$path = $_SERVER["DOCUMENT_ROOT"] . parse_url( $wp_styles->registered[ 'elementor-frontend' ]->src )["path"];
	$css  = file_get_contents( $path );
	$css  = str_replace( ".elementor-widget-heading .elementor-heading-title{padding:0;margin:0;line-height:1}", '.elementor-widget-heading .elementor-heading-title{padding:0;margin:0;}', $css, $count );

	if ( $count ) {
		file_put_contents( $path, $css );
	}
}
