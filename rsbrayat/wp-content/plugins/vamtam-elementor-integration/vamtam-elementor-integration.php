<?php

/**
 * Plugin Name: VamTam Elementor Integration
 * Plugin URI: http://vamtam.com
 * Description: Extend the Elementor plugin with widgets and template builder.
 * Version: 1.0.12
 * Author: VamTam
 * Author URI: http://vamtam.com
 * Elementor tested up to: 3.2.4
 * Elementor Pro tested up to: 3.2.0
 */

class VamtamElementorIntregration {

	const PLUGIN_VERSION            = '1.0.12';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION       = '7.0';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        // Abort early if not valid Vamtam theme.
        if ( ! self::is_valid_theme() ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_invalid_theme' ] );
            return;
        }

        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // All checks passed, load the plugin.
        $this->load_plugin();
    }

    public function i18n() {
        load_plugin_textdomain( 'vamtam-elementor-integration' );
    }

    public function admin_notice_invalid_theme() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires an Elementor-based VamTam theme to be activated in order to work.', 'vamtam-elementor-integration' ),
            '<strong>' . esc_html__( 'Vamtam Elementor Integration', 'vamtam-elementor-integration' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'vamtam-elementor-integration' ),
            '<strong>' . esc_html__( 'Vamtam Elementor Integration', 'vamtam-elementor-integration' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'vamtam-elementor-integration' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'vamtam-elementor-integration' ),
            '<strong>' . esc_html__( 'Vamtam Elementor Integration', 'vamtam-elementor-integration' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'vamtam-elementor-integration' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function load_plugin() {
        if ( ! class_exists( 'Vamtam_Updates_3' ) ) {
            require 'vamtam-updates/class-vamtam-updates.php';
        }

        new Vamtam_Updates_3( __FILE__ );

        if ( ! defined( 'VAMTAM_ELEMENTOR_INT_URL' ) ) {
            define( 'VAMTAM_ELEMENTOR_INT_URL', plugin_dir_url( __FILE__ ) );
        }
        if ( ! defined( 'VAMTAM_ELEMENTOR_INT_DIR' ) ) {
            define( 'VAMTAM_ELEMENTOR_INT_DIR', plugin_dir_path( __FILE__ ) );
        }

        $this->add_actions();
    }

    public function add_actions() {
        // Customizer.
        add_action( 'customize_register', [ __CLASS__, 'customize_register' ], 20 );

        // Used to print the custom JS.
        add_action( 'wp_head', [ __CLASS__, 'late_wp_head' ], 100 );
        add_action( 'wp_body_open', [ __CLASS__, 'early_wp_body_open' ], 5 );
        add_action( 'wp_footer', [ __CLASS__, 'late_wp_footer' ], 100 );
        add_action( 'wp_footer', [ __CLASS__, 'late_wp_footer' ], 100 );

		// Elementor includes.
		$this->includes();
    }

    public function includes() {
		// Helpers.
        foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/helpers/*.php' ) as $helper ) {
			require_once $helper;
		}

        // All Elementor-related hooks.
        require_once VAMTAM_ELEMENTOR_INT_DIR . 'includes/vamtam-elementor-hooks.php';

        // Kits Overrides.
        foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/kits/**/*.php' ) as $kit ) {
            require_once $kit;
		}

		// Dynamic Tags.
        foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/dynamic-tags/*.php' ) as $dynamic_tag ) {
			require_once $dynamic_tag;
		}

		// Overrides.
        add_action( 'after_setup_theme', [ __CLASS__, 'after_theme_setup' ], 100 );
	}

	public static function after_theme_setup() {
        // Theme Overrides.
        foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/theme-overrides/*.php' ) as $override ) {
            require_once $override;
        }

		if ( current_theme_supports( 'vamtam-elementor-widgets' ) ) {
			if ( ! defined( 'VAMTAM_ELEMENTOR_STYLES_URI' ) ) {
				define( 'VAMTAM_ELEMENTOR_STYLES_URI', VAMTAM_CSS . 'dist/elementor/' );
			}
			if ( ! defined( 'VAMTAM_ELEMENTOR_STYLES_DIR' ) ) {
				define( 'VAMTAM_ELEMENTOR_STYLES_DIR', VAMTAM_CSS_DIR . 'dist/elementor/' );
			}

			// Widget Overrides.
			foreach( glob( VAMTAM_ELEMENTOR_INT_DIR . 'includes/widgets/*.php' ) as $widget ) {
				require_once $widget;
			}
		}
	}

    public static function is_valid_theme() {
        $theme = wp_get_theme();

        if ( $theme->parent() ) {
            $theme = $theme->parent();
        }

        $is_valid = ! empty( $theme ) && $theme->get('Author')  === 'VamTam';
        return $is_valid;
    }

    public static function late_wp_head() {
        $additional_js = get_option( 'vamtam_additional_js' );

        if ( $additional_js && isset( $additional_js['head'] ) ) {
            echo '<script>' . $additional_js['head'] . '</script>';
        }
    }

    public static function early_wp_body_open() {
        $additional_js = get_option( 'vamtam_additional_js' );

        if ( $additional_js && isset( $additional_js['body'] ) ) {
            echo '<script>' . $additional_js['body'] . '</script>';
        }
    }

    public static function late_wp_footer() {
        $additional_js = get_option( 'vamtam_additional_js' );

        if ( $additional_js && isset( $additional_js['footer'] ) ) {
            echo '<script>' . $additional_js['footer'] . '</script>';
        }
    }

    /**
     * Check if standalone Elementor Pro is active
     *
     * @return boolean
     */
    public static function is_elementor_pro_active() {
        return class_exists( 'ElementorPro\Plugin' );
    }

    /**
     * Check if it is the elementor pro plugin activation screen
     *
     * @return boolean
     */
    public static function is_elementor_pro_standalone_plugin_activation_screen() {
        if ( is_admin() && isset( $_GET['plugin'] ) && $_GET['plugin'] == 'elementor-pro/elementor-pro.php' ) {
            return true;
        }

        return false;
	}

	public static function theme_loaded() {
		return did_action( 'after_setup_theme' );
	}

    public static function customize_register( WP_Customize_Manager $wp_customize  ) {
        $wp_customize->add_section( 'vamtam_additional_js' , [
            'title'      => esc_html__( 'Additional JS', 'vamtam-elementor-integration' ),
            'priority'   => 200,
        ] );

        $wp_customize->add_setting(
            'vamtam_additional_js[head]',
            [
                'type'    => 'option',
                'default' => '',
            ]
        );

        $wp_customize->add_setting(
            'vamtam_additional_js[body]',
            [
                'type'    => 'option',
                'default' => '',
            ]
        );

        $wp_customize->add_setting(
            'vamtam_additional_js[footer]',
            [
                'type'    => 'option',
                'default' => '',
            ]
        );

        $wp_customize->add_control(
            new WP_Customize_Code_Editor_Control(
                $wp_customize,
                'vamtam_additional_js[head]',
                [
                    'label'      => __( 'Before </head>', 'vamtam-elementor-integration' ), // control labels are escaped, using esc_html here will encode it as &amp;lt;
                    'section'    => 'vamtam_additional_js',
                    'settings'   => 'vamtam_additional_js[head]',
                    'code_type'  => 'javascript',
                ]
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Code_Editor_Control(
                $wp_customize,
                'vamtam_additional_js[body]',
                [
                    'label'      => __( 'After <body>', 'vamtam-elementor-integration' ), // control labels are escaped, using esc_html here will encode it as &amp;lt;
                    'section'    => 'vamtam_additional_js',
                    'settings'   => 'vamtam_additional_js[body]',
                    'code_type'  => 'javascript',
                ]
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Code_Editor_Control(
                $wp_customize,
                'vamtam_additional_js[footer]',
                [
                    'label'      => __( 'Before </body>', 'vamtam-elementor-integration' ), // control labels are escaped, using esc_html here will encode it as &amp;lt;
                    'section'    => 'vamtam_additional_js',
                    'settings'   => 'vamtam_additional_js[footer]',
                    'code_type'  => 'javascript',
                ]
            )
        );
    }
}

VamtamElementorIntregration::instance();
