<?php
namespace VamtamElementor\Widgets\VamtamInstagramFeed;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( vamtam_theme_supports( 'vamtam-instagram-feed--disabled' ) ) {
	return;
}

class Vamtam_Instagram_Feed extends \Vamtam_Elementor_Widget {

	/**
	 * Instagram Access token.
	 *
	 * @since 2.2.4
	 * @var   string
	 */
	private $insta_access_token = null;

	/**
	 * Instagram API URL.
	 *
	 * @since 2.2.4
	 * @var   string
	 */
	private $insta_api_url = 'https://www.instagram.com/';

	/**
	 * Official Instagram API URL.
	 *
	 * @since 2.2.4
	 * @var   string
	 */
	private $insta_official_api_url = 'https://graph.instagram.com/';

	// Extend constructor.
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		$this->register_assets();
	}

	// Register the assets the widget depends on.
	public function register_assets() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'vamtam-instagram-feed',
			VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/vamtam-instagram-feed/vamtam-instagram-feed' . $suffix . '.js',
			[
				'elementor-frontend'
			],
			\VamtamElementorIntregration::PLUGIN_VERSION,
			true
		);

        wp_localize_script(
			'vamtam-instagram-feed',
			'vamtamInsta',
			array(
				'invalid_username' => __( 'The <b>username</b> added is not a valid Instagram Username. Check browser console for more details.', 'vamtam-elementor-integration' ),
				'private_account'  => __( 'This account is private.', 'vamtam-elementor-integration' ),
				'no_images'        => __( 'No <i>images</i> were found in the Instagram profile for <b>', 'vamtam-elementor-integration' ),
			)
		);

		wp_register_style(
			'vamtam-instagram-feed',
			VAMTAM_ELEMENTOR_INT_URL . '/assets/css/widgets/vamtam-instagram-feed/vamtam-instagram-feed' . $suffix . '.css',
			[
				'elementor-frontend'
			],
			\VamtamElementorIntregration::PLUGIN_VERSION,
			true
		);
	}

    /**
	 * Retrieve instagram feed widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'vamtam-instafeed';
    }

    /**
	 * Retrieve instagram feed widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'VamTam Instagram Feed', 'vamtam-elementor-integration' );
    }

    /**
	 * Retrieve instagram feed widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
		return 'fa fa-instagram';
    }

    /**
	 * Retrieve the list of scripts the instagram feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
			'imagesloaded',
            'magnific-popup',
            'vamtam-instagram-feed',
        ];
    }

	public function get_style_depends() {
		return [ 'vamtam-instagram-feed' ];
	}

	/**
	 * Register instagram feed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_controls();
	}

	/**
	 * Register FAQ widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab: Instagram Account */
		$this->register_content_instaaccount_controls();

		/* Content Tab: Feed Settings */
		//$this->register_content_feed_settings_controls();

		/* Content Tab: General Settings */
		$this->register_content_general_settings_controls();

		/* Content Tab: Carousel Settings */
		$this->register_content_carousel_settings_controls();

		/* Style Tab: Layout */
		$this->register_style_layout_controls();

		/* Style Tab: Images */
		$this->register_style_images_controls();

		/* Style Tab: Content */
		$this->register_style_content_controls();

		/* Style Tab: Overlay */
		$this->register_style_overlay_controls();

		/* Style Tab: Feed Title */
		$this->register_style_feed_title_controls();

		/* Style Tab: Arrows */
		$this->register_style_arrows_controls();

		/* Style Tab: Dots */
		$this->register_style_dots_controls();

		/* Style Tab: Fraction */
		$this->register_style_fraction_controls();

		/* Style Tab: Load More Button */
		//$this->register_style_load_more_button_controls();
	}

	/**
	 * Content Tab: Instagram Account
	 */
	protected function register_content_instaaccount_controls() {
		$this->start_controls_section(
			'section_instaaccount',
			array(
				'label' => __( 'Instagram Account', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'insta_display',
			[
				'label'     => __( 'Display', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'feed',
				'options'   => [
					'feed'  => __( 'My Feed', 'vamtam-elementor-integration' ),
					'tags'  => __( 'Hashtag Search', 'vamtam-elementor-integration' ),
				],
			]
		);

		$this->add_control(
			'insta_display_tags_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Hashtag search requires you to be logged in your Instagram account on the current browser. This is only needed when setting up the widget in the editor, and does <strong>not</strong> affect page visitors.', 'vamtam-elementor-integration' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'             => [
					'insta_display' => 'tags',
				],
			]
		);

		$this->add_control(
			'access_token',
			[
				'label'                 => __( 'Custom Access Token', 'vamtam-elementor-integration' ),
				'label_block'           => true,
				'type'                  => Controls_Manager::TEXT,
				'condition'             => [
					'insta_display' => 'feed',
				],
			]
		);

		$this->add_control(
			'insta_hashtag',
			[
				'label'                 => __( 'Hashtag', 'vamtam-elementor-integration' ),
				'description'           => __( 'Enter without the # symbol', 'vamtam-elementor-integration' ),
				'label_block'           => false,
				'type'                  => Controls_Manager::TEXT,
				'condition'             => [
					'insta_display' => 'tags',
				],
			]
		);

		$this->add_control(
			'cache_timeout',
			array(
				'label'   => esc_html__( 'Cache Timeout', 'vamtam-elementor-integration' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hour',
				'options' => array(
					'none'   => esc_html__( 'None', 'vamtam-elementor-integration' ),
					'minute' => esc_html__( 'Minute', 'vamtam-elementor-integration' ),
					'hour'   => esc_html__( 'Hour', 'vamtam-elementor-integration' ),
					'day'    => esc_html__( 'Day', 'vamtam-elementor-integration' ),
					'week'   => esc_html__( 'Week', 'vamtam-elementor-integration' ),
				),
			)
		);

		$this->add_control(
			'images_count',
			array(
				'label'      => __( 'Images Count', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 5 ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
			)
		);

		$this->add_control(
			'resolution',
			[
				'label'                 => __( 'Image Resolution', 'vamtam-elementor-integration' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'thumbnail'              => __( 'Thumbnail (150x150)', 'vamtam-elementor-integration' ),
					'low_resolution'         => __( 'Low Resolution (320x320)', 'vamtam-elementor-integration' ),
					'standard_resolution'    => __( 'Standard Resolution (640x640)', 'vamtam-elementor-integration' ),
					'high'                   => __( 'High Resolution (original)', 'vamtam-elementor-integration' ),
				],
				'default'               => 'low_resolution',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Feed Settings
	 */
	protected function register_content_feed_settings_controls() {
		$this->start_controls_section(
			'section_instafeed',
			array(
				'label' => __( 'Feed Settings', 'vamtam-elementor-integration' ),
			)
		);

		/* $this->add_control(
			'sort_by',
			[
				'label'                 => __( 'Sort By', 'vamtam-elementor-integration' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'none'               => __( 'None', 'vamtam-elementor-integration' ),
					'most-recent'        => __( 'Most Recent', 'vamtam-elementor-integration' ),
					'least-recent'       => __( 'Least Recent', 'vamtam-elementor-integration' ),
					'most-liked'         => __( 'Most Liked', 'vamtam-elementor-integration' ),
					'least-liked'        => __( 'Least Liked', 'vamtam-elementor-integration' ),
					'most-commented'     => __( 'Most Commented', 'vamtam-elementor-integration' ),
					'least-commented'    => __( 'Least Commented', 'vamtam-elementor-integration' ),
					'random'             => __( 'Random', 'vamtam-elementor-integration' ),
				],
				'default'               => 'none',
			]
		); */

		$this->end_controls_section();
	}

	/**
	 * Content Tab: General Settings
	 */
	protected function register_content_general_settings_controls() {
		$this->start_controls_section(
			'section_general_settings',
			array(
				'label' => __( 'General Settings', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'feed_layout',
			array(
				'label'              => __( 'Layout', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'grid',
				'options'            => array(
					'grid'     => __( 'Grid', 'vamtam-elementor-integration' ),
					'masonry'  => __( 'Masonry', 'vamtam-elementor-integration' ),
					'carousel' => __( 'Carousel', 'vamtam-elementor-integration' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'square_images',
			array(
				'label'        => __( 'Square Images', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => array( 'grid', 'carousel' ),
				),
			)
		);

		$this->add_responsive_control(
			'grid_cols',
			array(
				'label'          => __( 'Grid Columns', 'vamtam-elementor-integration' ),
				'type'           => Controls_Manager::SELECT,
				'label_block'    => false,
				'default'        => '5',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'1' => __( '1', 'vamtam-elementor-integration' ),
					'2' => __( '2', 'vamtam-elementor-integration' ),
					'3' => __( '3', 'vamtam-elementor-integration' ),
					'4' => __( '4', 'vamtam-elementor-integration' ),
					'5' => __( '5', 'vamtam-elementor-integration' ),
					'6' => __( '6', 'vamtam-elementor-integration' ),
					'7' => __( '7', 'vamtam-elementor-integration' ),
					'8' => __( '8', 'vamtam-elementor-integration' ),
				),
				'selectors'      => array(
					'{{WRAPPER}} .vamtam-instagram-feed-grid .vamtam-feed-item' => 'width: calc( 100% / {{VALUE}} )',
				),
				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'insta_likes',
			array(
				'label'              => __( 'Likes', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => __( 'Show', 'vamtam-elementor-integration' ),
				'label_off'          => __( 'Hide', 'vamtam-elementor-integration' ),
				'return_value'       => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'insta_comments',
			array(
				'label'              => __( 'Comments', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => __( 'Show', 'vamtam-elementor-integration' ),
				'label_off'          => __( 'Hide', 'vamtam-elementor-integration' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'insta_caption',
			array(
				'label'              => __( 'Caption', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => __( 'Show', 'vamtam-elementor-integration' ),
				'label_off'          => __( 'Hide', 'vamtam-elementor-integration' ),
				'return_value'       => 'yes',
			)
		);

		$this->add_control(
			'insta_caption_length',
			array(
				'label'   => __( 'Caption Length', 'vamtam-elementor-integration' ),
				'type'    => Controls_Manager::NUMBER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 30,
				'condition'             => [
					'insta_caption' => 'yes',
				],
			)
		);

		$this->add_control(
			'content_position',
			array(
				'label'      => __( 'Content Position', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'overlay',
				'options'    => array(
					'overlay' => __( 'Overlay', 'vamtam-elementor-integration' ),
					'above'  => __( 'Above Image', 'vamtam-elementor-integration' ),
					'below'  => __( 'Below Image', 'vamtam-elementor-integration' ),
				),
				'prefix_class' => 'vamtam-insta-content-',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_visibility',
			array(
				'label'      => __( 'Content Visibility', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'always',
				'options'    => array(
					'always' => __( 'Always', 'vamtam-elementor-integration' ),
					'hover'  => __( 'On Hover', 'vamtam-elementor-integration' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'insta_image_popup',
			array(
				'label'        => __( 'Lightbox', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'insta_image_link',
			array(
				'label'        => __( 'Image Link', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'insta_image_popup!' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_link',
			array(
				'label'        => __( 'Show Link to Instagram Profile?', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'insta_link_title',
			array(
				'label'     => __( 'Link Title', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Follow Us @ Instagram', 'vamtam-elementor-integration' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_url',
			array(
				'label'       => __( 'Instagram Profile URL', 'vamtam-elementor-integration' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_icon',
			array(
				'label'            => __( 'Title Icon', 'vamtam-elementor-integration' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'insta_title_icon',
				'recommended'      => array(
					'fa-brands' => array(
						'instagram',
					),
					'fa-regular' => array(
						'user',
						'user-circle',
					),
					'fa-solid'  => array(
						'user',
						'user-circle',
						'user-check',
						'user-graduate',
						'user-md',
						'user-plus',
						'user-tie',
					),
				),
				'default'          => array(
					'value'   => 'fab fa-instagram',
					'library' => 'fa-brands',
				),
				'condition'        => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_title_icon_position',
			array(
				'label'     => __( 'Icon Position', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'before_title' => __( 'Before Title', 'vamtam-elementor-integration' ),
					'after_title'  => __( 'After Title', 'vamtam-elementor-integration' ),
				),
				'default'   => 'before_title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		/* $this->add_control(
			'load_more_button',
			array(
				'label'        => __( 'Show Load More Button', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition'    => array(
					'feed_layout' => 'grid',
				),
			)
		);

		$this->add_control(
			'load_more_button_text',
			array(
				'label'     => __( 'Button Text', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Load More', 'vamtam-elementor-integration' ),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		); */

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Carousel Settings
	 */
	protected function register_content_carousel_settings_controls() {
		$this->start_controls_section(
			'section_carousel_settings',
			array(
				'label'     => __( 'Carousel Settings', 'vamtam-elementor-integration' ),
				'condition' => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'items',
			array(
				'label'      => __( 'Visible Items', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 3 ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Items Gap', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 10 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'slider_speed',
			array(
				'label'       => __( 'Slider Speed', 'vamtam-elementor-integration' ),
				'description' => __( 'Duration of transition between slides (in ms)', 'vamtam-elementor-integration' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array( 'size' => 600 ),
				'range'       => array(
					'px' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					),
				),
				'size_units'  => '',
				'separator'   => 'before',
				'condition'   => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'        => __( 'Pause on Interaction', 'vamtam-elementor-integration' ),
				'description'  => __( 'Disables autoplay completely on first interaction with the carousel.', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'autoplay'    => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 3000,
				'title'     => __( 'Enter carousel speed', 'vamtam-elementor-integration' ),
				'condition' => array(
					'autoplay'    => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'grab_cursor',
			array(
				'label'        => __( 'Grab Cursor', 'vamtam-elementor-integration' ),
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'Hide', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'navigation_heading',
			array(
				'label'     => __( 'Navigation', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => __( 'Arrows', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => __( 'Pagination', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'     => __( 'Pagination Type', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'  => __( 'Dots', 'vamtam-elementor-integration' ),
					'fraction' => __( 'Fraction', 'vamtam-elementor-integration' ),
				),
				'condition' => array(
					'dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'     => __( 'Direction', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Left', 'vamtam-elementor-integration' ),
					'right' => __( 'Right', 'vamtam-elementor-integration' ),
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}


	/*-----------------------------------------------------------------------------------*/
	/* STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {
		$this->start_controls_section(
			'section_layout_style',
			array(
				'label'     => __( 'Layout', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);$this->add_responsive_control(
			'columns_gap',
			array(
				'label'          => __( 'Columns Gap', 'vamtam-elementor-integration' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .vamtam-instafeed-grid .vamtam-feed-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .vamtam-instafeed-grid' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'          => __( 'Rows Gap', 'vamtam-elementor-integration' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .vamtam-instafeed-grid .vamtam-feed-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'render_type'    => 'template',
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Images
	 */
	protected function register_style_images_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Images', 'vamtam-elementor-integration' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_image_style' );

		$this->start_controls_tab(
			'tab_image_normal',
			array(
				'label' => __( 'Normal', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale',
			array(
				'label'        => __( 'Grayscale Image', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'images_border',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-instagram-feed .vamtam-if-img',
			)
		);

		$this->add_control(
			'images_border_radius',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-if-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			array(
				'label' => __( 'Hover', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale_hover',
			array(
				'label'        => __( 'Grayscale Image', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'vamtam-elementor-integration' ),
				'label_off'    => __( 'No', 'vamtam-elementor-integration' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'images_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-feed-item:hover .vamtam-if-img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => __( 'Content', 'vamtam-elementor-integration' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'content_typography',
				'label'      => __( 'Typography', 'vamtam-elementor-integration' ),
				'selector'   => '{{WRAPPER}} .vamtam-feed-item .vamtam-overlay-container',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'likes_comments_color',
			array(
				'label'      => __( 'Color', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-feed-item .vamtam-overlay-container' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_vertical_align',
			array(
				'label'                => __( 'Vertical Align', 'vamtam-elementor-integration' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Center', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .vamtam-overlay-container' => 'justify-content: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_horizontal_align',
			array(
				'label'                => __( 'Horizontal Align', 'vamtam-elementor-integration' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .vamtam-overlay-container' => 'align-items: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'text_align',
			array(
				'label'    => __( 'Text Align', 'vamtam-elementor-integration' ),
				'type'     => Controls_Manager::CHOOSE,
				'options'  => array(
					'left' => array(
						'title' => __( 'Left', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-overlay-container' => 'text-align: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-overlay-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'icons_heading',
			array(
				'label'      => __( 'Icons', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'icons_style',
			array(
				'label'              => __( 'Style', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::CHOOSE,
				'label_block'        => false,
				'toggle'             => false,
				'default'            => 'solid',
				'options'            => array(
					'solid'   => array(
						'title' => __( 'Solid', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-comment',
					),
					'outline' => array(
						'title' => __( 'Outline', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-comment-o',
					),
				),
				'frontend_available' => true,
				'conditions'         => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2.5,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-feed-item .vamtam-if-icon' => 'font-size: {{SIZE}}em;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_caption',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Overlay
	 */
	protected function register_style_overlay_controls() {
		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label' => __( 'Overlay', 'vamtam-elementor-integration' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'normal',
				'options'   => array(
					'normal'      => __( 'Normal', 'vamtam-elementor-integration' ),
					'multiply'    => __( 'Multiply', 'vamtam-elementor-integration' ),
					'screen'      => __( 'Screen', 'vamtam-elementor-integration' ),
					'overlay'     => __( 'Overlay', 'vamtam-elementor-integration' ),
					'darken'      => __( 'Darken', 'vamtam-elementor-integration' ),
					'lighten'     => __( 'Lighten', 'vamtam-elementor-integration' ),
					'color-dodge' => __( 'Color Dodge', 'vamtam-elementor-integration' ),
					'color'       => __( 'Color', 'vamtam-elementor-integration' ),
					'hue'         => __( 'Hue', 'vamtam-elementor-integration' ),
					'hard-light'  => __( 'Hard Light', 'vamtam-elementor-integration' ),
					'soft-light'  => __( 'Soft Light', 'vamtam-elementor-integration' ),
					'difference'  => __( 'Difference', 'vamtam-elementor-integration' ),
					'exclusion'   => __( 'Exclusion', 'vamtam-elementor-integration' ),
					'saturation'  => __( 'Saturation', 'vamtam-elementor-integration' ),
					'luminosity'  => __( 'Luminosity', 'vamtam-elementor-integration' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-overlay-container' => 'mix-blend-mode: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			array(
				'label' => __( 'Normal', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_normal',
				'label'    => __( 'Overlay', 'vamtam-elementor-integration' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .vamtam-instagram-feed .vamtam-overlay-container',
			)
		);

		$this->add_control(
			'overlay_margin_normal',
			array(
				'label'     => __( 'Margin', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-overlay-container' => 'top: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px; right: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'image_overlay_opacity_normal',
			array(
				'label'      => __( 'Overlay Opacity', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			array(
				'label' => __( 'Hover', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_hover',
				'label'    => __( 'Overlay', 'vamtam-elementor-integration' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .vamtam-instagram-feed .vamtam-feed-item:hover .vamtam-overlay-container',
			)
		);

		$this->add_control(
			'image_overlay_opacity_hover',
			array(
				'label'      => __( 'Overlay Opacity', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-feed-item:hover .vamtam-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Feed Title
	 */
	protected function register_style_feed_title_controls() {
		$this->start_controls_section(
			'section_feed_title_style',
			array(
				'label'     => __( 'Feed Title', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'feed_title_position',
			array(
				'label'        => __( 'Position', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'middle',
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'vamtam-elementor-integration' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'vamtam-insta-title-',
				'condition'    => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'feed_title_typography',
				'label'     => __( 'Typography', 'vamtam-elementor-integration' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .vamtam-instagram-feed-title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			array(
				'label'     => __( 'Normal', 'vamtam-elementor-integration' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_normal',
			array(
				'label'     => __( 'Text Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap .vamtam-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_normal',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-instagram-feed-title-wrap',
			)
		);

		$this->add_control(
			'title_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label'     => __( 'Hover', 'vamtam-elementor-integration' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Text Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap a:hover .vamtam-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_hover',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-instagram-feed-title-wrap:hover',
			)
		);

		$this->add_control(
			'title_border_radius_hover',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'title_icon_heading',
			array(
				'label'     => __( 'Icon', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 4 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-icon-before_title' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-icon-after_title' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Arrows
	 */
	protected function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => __( 'Arrows', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'arrows'      => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'arrow',
			array(
				'label'       => __( 'Choose Arrow', 'vamtam-elementor-integration' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => __( 'Angle', 'vamtam-elementor-integration' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'vamtam-elementor-integration' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'vamtam-elementor-integration' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'vamtam-elementor-integration' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'vamtam-elementor-integration' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'vamtam-elementor-integration' ),
					'fa fa-caret-right'          => __( 'Caret', 'vamtam-elementor-integration' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'vamtam-elementor-integration' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'vamtam-elementor-integration' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'vamtam-elementor-integration' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'vamtam-elementor-integration' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'vamtam-elementor-integration' ),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'left_arrow_position',
			array(
				'label'      => __( 'Align Left Arrow', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'right_arrow_position',
			array(
				'label'      => __( 'Align Right Arrow', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label' => __( 'Normal', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => __( 'Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button',
			)
		);

		$this->add_control(
			'arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label' => __( 'Hover', 'vamtam-elementor-integration' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => __( 'Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => __( 'Padding', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .vamtam-swiper-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Pagination: Dots
	 */
	protected function register_style_dots_controls() {
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => __( 'Pagination: Dots', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'        => __( 'Position', 'vamtam-elementor-integration' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inside'  => __( 'Inside', 'vamtam-elementor-integration' ),
					'outside' => __( 'Outside', 'vamtam-elementor-integration' ),
				),
				'default'      => 'outside',
				'prefix_class' => 'swiper-container-dots-',
				'condition'    => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => __( 'Size', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => __( 'Spacing', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => __( 'Normal', 'vamtam-elementor-integration' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => __( 'Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'active_dot_color_normal',
			array(
				'label'     => __( 'Active Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet',
				'condition'   => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => __( 'Margin', 'vamtam-elementor-integration' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => __( 'Hover', 'vamtam-elementor-integration' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => __( 'Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-instagram-feed .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Pagination: Fraction
	 * -------------------------------------------------
	 */
	protected function register_style_fraction_controls() {
		$this->start_controls_section(
			'section_fraction_style',
			array(
				'label'     => __( 'Pagination: Fraction', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_control(
			'fraction_text_color',
			array(
				'label'     => __( 'Text Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'fraction_typography',
				'label'     => __( 'Typography', 'vamtam-elementor-integration' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Load More Button
	 * -------------------------------------------------
	 */
	protected function register_style_load_more_button_controls() {
		$this->start_controls_section(
			'section_load_more_button_style',
			array(
				'label'     => __( 'Load More Button', 'vamtam-elementor-integration' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => __( 'Alignment', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'vamtam-elementor-integration' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_top_spacing',
			array(
				'label'      => __( 'Top Spacing', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 20 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-load-more-button-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'vamtam-elementor-integration' ),
					'sm' => __( 'Small', 'vamtam-elementor-integration' ),
					'md' => __( 'Medium', 'vamtam-elementor-integration' ),
					'lg' => __( 'Large', 'vamtam-elementor-integration' ),
					'xl' => __( 'Extra Large', 'vamtam-elementor-integration' ),
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'vamtam-elementor-integration' ),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'vamtam-elementor-integration' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .vamtam-load-more-button',
				'condition'   => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'vamtam-elementor-integration' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .vamtam-load-more-button',
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'vamtam-elementor-integration' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .vamtam-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .vamtam-load-more-button',
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'load_more_button_icon_heading',
			array(
				'label'     => __( 'Button Icon', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
					'button_icon!'     => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'       => __( 'Margin', 'vamtam-elementor-integration' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'condition'   => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
					'button_icon!'     => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .vamtam-info-box .vamtam-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'vamtam-elementor-integration' ),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .vamtam-load-more-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'vamtam-elementor-integration' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .vamtam-load-more-button:hover',
				'condition' => array(
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);
	}

	/**
	 * Slider Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$settings = $this->get_settings();

		$slider_options = array(
			'direction'     => 'horizontal',
			'speed'         => 400,
			'slidesPerView' => ( $settings['items']['size'] !== '' ) ? absint( $settings['items']['size'] ) : 3,
			'spaceBetween'  => ( $settings['margin']['size'] !== '' ) ? $settings['margin']['size'] : 10,
			'grabCursor'    => ( $settings['grab_cursor'] === 'yes' ),
			'autoHeight'    => true,
			'loop'          => ( $settings['infinite_loop'] === 'yes' ),
		);

		if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed'] ) ) {
			$autoplay_speed = $settings['autoplay_speed'];
		} else {
			$autoplay_speed = 999999;
		}

		$slider_options['autoplay'] = array(
			'delay' => $autoplay_speed,
		);

		if ( $settings['dots'] == 'yes' ) {
			$slider_options['pagination'] = array(
				'el'        => '.swiper-pagination-' . esc_attr( $this->get_id() ),
				'type'      => $settings['pagination_type'],
				'clickable' => true,
			);
		}

		if ( $settings['arrows'] == 'yes' ) {
			$slider_options['navigation'] = array(
				'nextEl' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
				'prevEl' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
			);
		}

		$elementor_bp_lg = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md = get_option( 'elementor_viewport_md' );
		$bp_desktop      = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet       = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile       = 320;

		$slider_options['breakpoints'] = array(
			$bp_desktop => array(
				'slidesPerView' => ( $settings['items']['size'] !== '' ) ? absint( $settings['items']['size'] ) : 2,
				'spaceBetween'  => ( $settings['margin']['size'] !== '' ) ? $settings['margin']['size'] : 10,
			),
			$bp_tablet  => array(
				'slidesPerView' => ( $settings['items_tablet']['size'] !== '' ) ? absint( $settings['items_tablet']['size'] ) : 2,
				'spaceBetween'  => ( $settings['margin_tablet']['size'] !== '' ) ? $settings['margin_tablet']['size'] : 10,
			),
			$bp_mobile  => array(
				'slidesPerView' => ( $settings['items_mobile']['size'] !== '' ) ? absint( $settings['items_mobile']['size'] ) : 1,
				'spaceBetween'  => ( $settings['margin_mobile']['size'] !== '' ) ? $settings['margin_mobile']['size'] : 10,
			),
		);

		return $slider_options;
	}

	/**
	 * Get Instagram access token.
	 *
	 * @since 2.2.4
	 * @return string
	 */
	public function get_insta_access_token() {
		$settings = $this->get_settings_for_display();

		if ( ! $this->insta_access_token ) {
			$custom_access_token = $settings['access_token'];

			if ( '' !== trim( $custom_access_token ) ) {
				$this->insta_access_token = $custom_access_token;
			} else {
				// $this->insta_access_token = $this->get_insta_global_access_token();
			}
		}

		return $this->insta_access_token;
	}

	/**
	 * Retrieve a URL for own photos.
	 *
	 * @since  2.2.4
	 * @return string
	 */
	public function get_feed_endpoint() {
		return $this->insta_official_api_url . 'me/media/';
	}

	/**
	 * Retrieve a URL for photos by hashtag.
	 *
	 * @since  2.2.4
	 * @return string
	 */
	public function get_tags_endpoint() {
		return $this->insta_api_url . 'explore/tags/%s/';
	}

	public function get_user_endpoint() {
		return $this->insta_official_api_url . 'me/';
	}

	public function get_user_media_endpoint() {
		return $this->insta_official_api_url . '%s/media/';
	}

	public function get_media_endpoint() {
		return $this->insta_official_api_url . '%s/';
	}

	public function get_user_url() {
		$url = $this->get_user_endpoint();
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			// 'fields' => 'media.limit(10){comments_count,like_count,likes,likes_count,media_url,permalink,caption}',
		], $url );

		return $url;
	}

	public function get_user_media_url( $user_id ) {
		$url = sprintf( $this->get_user_media_endpoint(), $user_id );
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			'fields' => 'id,like_count',
		], $url );

		return $url;
	}

	public function get_media_url( $media_id ) {
		$url = sprintf( $this->get_media_endpoint(), $media_id );
		$url = add_query_arg( [
			'access_token' => $this->get_insta_access_token(),
			'fields' => 'id,media_type,media_url,timestamp,like_count',
		], $url );

		return $url;
	}

	public function get_insta_user_id() {
		$result = $this->get_insta_remote( $this->get_user_url() );
		return $result;
	}

	public function get_insta_user_media( $user_id ) {
		$result = $this->get_insta_remote( $this->get_user_media_url( $user_id ) );

		return $result;
	}

	public function get_insta_media( $media_id ) {
		$result = $this->get_insta_remote( $this->get_media_url( $media_id ) );

		return $result;
	}

	/**
	 * Retrieve a grab URL.
	 *
	 * @since  2.2.4
	 * @return string
	 */
	public function get_fetch_url() {
		$settings = $this->get_settings();

		if ( 'tags' === $settings['insta_display'] ) {
			$url = sprintf( $this->get_tags_endpoint(), $settings['insta_hashtag'] );
			$url = add_query_arg( array( '__a' => 1 ), $url );

		} elseif ( 'feed' === $settings['insta_display'] ) {
			$url = $this->get_feed_endpoint();
			$url = add_query_arg( [
				'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption,likes_count,likes',
				'access_token' => $this->get_insta_access_token(),
			], $url );
		}

		return $url;
	}

	/**
	 * Get thumbnail data from response data
	 *
	 * @param $post
	 * @since 2.2.4
	 *
	 * @return array
	 */
	public function get_insta_feed_thumbnail_data( $post ) {
		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( ! empty( $post['images'] ) && is_array( $post['images'] ) ) {
			$data = $post['images'];

			$thumbnail['thumbnail'] = [
				'src'           => $data['thumbnail']['url'],
				'config_width'  => $data['thumbnail']['width'],
				'config_height' => $data['thumbnail']['height'],
			];

			$thumbnail['low'] = [
				'src'           => $data['low_resolution']['url'],
				'config_width'  => $data['low_resolution']['width'],
				'config_height' => $data['low_resolution']['height'],
			];

			$thumbnail['standard'] = [
				'src'           => $data['standard_resolution']['url'],
				'config_width'  => $data['standard_resolution']['width'],
				'config_height' => $data['standard_resolution']['height'],
			];

			$thumbnail['high'] = $thumbnail['standard'];
		}

		return $thumbnail;
	}

	/**
	 * Get data from response
	 *
	 * @param  $response
	 * @since  2.2.4
	 *
	 * @return array
	 */
	public function get_insta_feed_response_data( $response ) {
		$settings = $this->get_settings();

		if ( ! array_key_exists( 'data', $response ) ) { // Avoid PHP notices
			return;
		}

		$response_posts = $response['data'];

		if ( empty( $response_posts ) ) {
			return array();
		}

		$return_data  = array();
		$images_count = ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : 5;
		$posts = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post              = array();

			$_post['id']        = $post['id'];
			$_post['link']      = $post['permalink'];
			$_post['caption']   = '';
			$_post['image']     = 'VIDEO' === $post['media_type'] ? $post['thumbnail_url'] : $post['media_url'];
			$_post['comments']  = ! empty( $post['comments_count'] ) ? $post['comments_count'] : 0;
			$_post['likes']     = ! empty( $post['likes_count'] ) ? $post['likes_count'] : 0;

			$_post['thumbnail'] = $this->get_insta_feed_thumbnail_data( $post );

			if ( ! empty( $post['caption'] ) ) {
				$_post['caption'] = wp_html_excerpt( $post['caption'], $this->get_settings( 'insta_caption_length' ), '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Get data from response
	 *
	 * @param  $response
	 * @since  2.2.4
	 *
	 * @return array
	 */
	public function get_insta_tags_response_data( $response ) {
		$settings = $this->get_settings();
		$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_media']['edges'];

		$insta_caption_length = ( $settings['insta_caption_length'] ) ? $settings['insta_caption_length'] : 30;

		if ( empty( $response_posts ) ) {
			$response_posts = $response['graphql']['hashtag']['edge_hashtag_to_top_posts']['edges'];
		}

		$return_data  = array();
		$images_count = ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : 5;
		$posts = array_slice( $response_posts, 0, $images_count, true );

		foreach ( $posts as $post ) {
			$_post              = array();

			$_post['link']      = sprintf( $this->insta_api_url . 'p/%s/', $post['node']['shortcode'] );
			$_post['caption']   = '';
			$_post['comments']  = $post['node']['edge_media_to_comment']['count'];
			$_post['likes']     = $post['node']['edge_liked_by']['count'];
			$_post['thumbnail'] = $this->get_insta_tags_thumbnail_data( $post );

			if ( isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$_post['caption'] = wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $insta_caption_length, '&hellip;' );
			}

			$return_data[] = $_post;
		}

		return $return_data;
	}

	/**
	 * Generate thumbnail resources.
	 *
	 * @since 2.2.4
	 * @param $post_data
	 *
	 * @return array
	 */
	public function get_insta_tags_thumbnail_data( $post ) {
		$post = $post['node'];

		$thumbnail = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'		=> false,
		);

		if ( is_array( $post['thumbnail_resources'] ) && ! empty( $post['thumbnail_resources'] ) ) {
			foreach ( $post['thumbnail_resources'] as $key => $resources_data ) {

				if ( 150 === $resources_data['config_width'] ) {
					$thumbnail['thumbnail'] = $resources_data;
					continue;
				}

				if ( 320 === $resources_data['config_width'] ) {
					$thumbnail['low'] = $resources_data;
					continue;
				}

				if ( 640 === $resources_data['config_width'] ) {
					$thumbnail['standard'] = $resources_data;
					continue;
				}
			}
		}

		if ( ! empty( $post['display_url'] ) ) {
			$thumbnail['high'] = array(
				'src'           => $post['display_url'],
				'config_width'  => $post['dimensions']['width'],
				'config_height' => $post['dimensions']['height'],
			) ;
		}

		return $thumbnail;
	}

	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  2.2.4
	 * @return string   The url of the instagram post image
	 */
	protected function get_insta_image_size() {
		$settings = $this->get_settings();

		$size = $settings['resolution'];

		switch ( $size ) {
			case 'thumbnail':
				return 'thumbnail';
			case 'low_resolution':
				return 'low';
			case 'standard_resolution':
				return 'standard';
			default:
				return 'low';
		}
	}

	/**
	 * Retrieve response from API
	 *
	 * @since  2.2.4
	 * @return array|WP_Error
	 */
	public function get_insta_remote( $url ) {
		$response       = wp_remote_get( $url, array(
			'timeout'   => 60,
			'sslverify' => false,
		) );

		$response_code  = wp_remote_retrieve_response_code( $response );
		$result         = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {
			$message = is_array( $result ) && isset( $result['error']['message'] ) ? $result['error']['message'] : __( 'No posts found', 'vamtam-elementor-integration' );

			return new \WP_Error( $response_code, $message );
		}

		if ( ! is_array( $result ) ) {
			return new \WP_Error( 'error', __( 'Data Error', 'vamtam-elementor-integration' ) );
		}

		return $result;
	}

	/**
	 * Sanitize endpoint.
	 *
	 * @since  2.2.4
	 * @return string
	 */
	public function sanitize_endpoint() {
		$settings = $this->get_settings();

		return in_array( $settings['insta_display'] , array( 'feed', 'tags' ) ) ? $settings['insta_display'] : 'tags';
	}

	/**
	 * Get transient key.
	 *
	 * @since  2.2.4
	 * @return string
	 */
	public function get_transient_key() {
		$settings = $this->get_settings();

		$endpoint = $this->sanitize_endpoint();
		$target = ( 'tags' === $endpoint ) ? sanitize_text_field( $settings['insta_hashtag'] ) : 'users';
		$insta_caption_length = ( $settings['insta_caption_length'] ) ? $settings['insta_caption_length'] : 30;
		$images_count = $settings['images_count']['size'];

		return sprintf( 'ppe_instagram_%s_%s_posts_count_%s_caption_%s',
			$endpoint,
			$target,
			$images_count,
			$insta_caption_length
		);
	}

	/**
	 * Render Instagram profile link.
	 *
	 * @since  2.2.4
	 * @param  array $settings
	 * @return array
	 */
	public function get_insta_profile_link() {
		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['insta_title_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['insta_title_icon'] = 'fa fa-instagram';
		}

		$has_icon = ! empty( $settings['insta_title_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['insta_title_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['title_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['title_icon'] );
		$is_new   = ! isset( $settings['insta_title_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'title-icon', 'class', 'vamtam-icon vamtam-icon-' . $settings['insta_title_icon_position'] );

		if ( 'yes' === $settings['insta_profile_link'] && $settings['insta_link_title'] ) { ?>
			<span class="vamtam-instagram-feed-title-wrap">
				<a <?php echo $this->get_render_attribute_string( 'instagram-profile-link' ); ?>>
					<span class="vamtam-instagram-feed-title">
						<?php if ( 'before_title' === $settings['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>

						<?php echo esc_attr( $settings['insta_link_title'] ); ?>

						<?php if ( 'after_title' === $settings['insta_title_icon_position'] && $has_icon ) { ?>
						<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
								<?php
							}
							?>
						</span>
						<?php } ?>
					</span>
				</a>
			</span>
		<?php }
	}

	/**
	 * Retrieve Instagram posts.
	 *
	 * @since  2.2.4
	 * @param  array $settings
	 * @return array
	 */
	public function get_insta_posts( $settings ) {
		$settings = $this->get_settings();

		$transient_key = md5( $this->get_transient_key() );

		$data = get_transient( $transient_key );

		if ( ! empty( $data ) && 1 !== $settings['cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
			return $data;
		}

		// $user = $this->get_insta_user_id();
		// $user_media = $this->get_insta_user_media( $user['id'] );

		// foreach( $user_media['data'] as $media ) {
		// 	$media_object = $this->get_insta_media( $media['id'] );
		// }

		$response = $this->get_insta_remote( $this->get_fetch_url() );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$data = ( 'tags' === $settings['insta_display'] ) ? $this->get_insta_tags_response_data( $response ) : $this->get_insta_feed_response_data( $response );

		if ( empty( $data ) ) {
			return array();
		}

		set_transient( $transient_key, $data, $settings['cache_timeout'] );

		return $data;
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( 'carousel' === $settings['feed_layout'] ) {
			$layout = 'carousel';
		} else {
			$layout = 'grid';
		}

		$this->add_render_attribute(
			'insta-feed-wrap',
			'class',
			array(
				'vamtam-instagram-feed',
				'clearfix',
				'vamtam-instagram-feed-' . $layout,
				'vamtam-instagram-feed-' . $settings['content_visibility'],
			)
		);

		if ( ( 'grid' === $settings['feed_layout'] || 'masonry' === $settings['feed_layout'] ) && $settings['grid_cols'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'vamtam-instagram-feed-grid-' . $settings['grid_cols'] );
		}

		if ( 'yes' === $settings['insta_image_grayscale'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'vamtam-instagram-feed-gray' );
		}

		if ( 'yes' === $settings['insta_image_grayscale_hover'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'vamtam-instagram-feed-hover-gray' );
		}

		if ( 'masonry' !== $settings['feed_layout'] && 'yes' === $settings['square_images'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'vamtam-if-square-images' );
		}

		$this->add_render_attribute( 'insta-feed-container', 'class', 'vamtam-instafeed' );

		$this->add_render_attribute(
			'insta-feed',
			array(
				'id'    => 'vamtam-instafeed-' . esc_attr( $this->get_id() ),
				'class' => 'vamtam-instafeed-grid',
			)
		);

		$this->add_render_attribute( 'insta-feed-inner', 'class', 'vamtam-insta-feed-inner' );

		if ( 'carousel' === $settings['feed_layout'] ) {
			$this->add_render_attribute(
				array(
					'insta-feed-inner'     => array(
						'class' => array(
							'swiper-container-wrap',
							'vamtam-insta-feed-carousel-wrap',
						),
					),
					'insta-feed-container' => array(
						'class' => array(
							'swiper-container',
							'swiper-container-' . esc_attr( $this->get_id() ),
						),
					),
					'insta-feed'           => array(
						'class' => array(
							'swiper-wrapper',
						),
					),
				)
			);

			$slider_options = $this->slider_settings();

			$this->add_render_attribute(
				'insta-feed-container',
				array(
					'data-slider-settings' => wp_json_encode( $slider_options ),
				)
			);

			if ( 'right' === $settings['direction'] ) {
				$this->add_render_attribute( 'insta-feed-container', 'dir', 'rtl' );
			}
		}

		if ( ! empty( $settings['insta_profile_url']['url'] ) ) {
			$this->add_link_attributes( 'instagram-profile-link', $settings['insta_profile_url'] );
		}

		$this->render_api_images();
	}

	/**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  2.2.4
	 * @access protected
	 */
	protected function render_api_images() {
		$settings = $this->get_settings();

		$gallery = $this->get_insta_posts( $settings );

		if ( empty( $gallery ) || is_wp_error( $gallery ) ) {
			$message = is_wp_error( $gallery ) ? $gallery->get_error_message() : esc_html__( 'No Posts Found', 'vamtam-elementor-integration' );

			echo $message;
			return;
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'insta-feed-wrap' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'insta-feed-inner' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'insta-feed-container' ); ?>>
					<?php $this->get_insta_profile_link(); ?>
					<div <?php echo $this->get_render_attribute_string( 'insta-feed' ); ?>>
						<?php
						foreach ( $gallery as $index => $item ) {
							$item_key = $this->get_repeater_setting_key( 'item', 'insta_images', $index );
							$this->add_render_attribute( $item_key, 'class', 'vamtam-feed-item' );

							if ( 'carousel' === $settings['feed_layout'] ) {
								$this->add_render_attribute( $item_key, 'class', 'swiper-slide' );
							}
							?>
							<div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
								<div class="vamtam-feed-item-inner">
								<?php $this->render_image_thumbnail( $item, $index ); ?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
				$this->render_dots();

				$this->render_arrows();
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Image Thumbnail
	 *
	 * @since  2.2.4
	 * @return void
	 */
	protected function render_image_thumbnail( $item, $index ) {
		$settings        = $this->get_settings();
		$thumbnail_url   = $this->get_insta_image_url( $item, $this->get_insta_image_size() );
		$thumbnail_alt   = $item['caption'];
		$thumbnail_title = $item['caption'];
		$likes           = $item['likes'];
		$comments        = $item['comments'];
		$image_key       = $this->get_repeater_setting_key( 'image', 'insta', $index );
		$link_key        = $this->get_repeater_setting_key( 'link', 'image', $index );
		$item_link       = '';

		$this->add_render_attribute( $image_key, 'src', $thumbnail_url );

		if ( '' !== $thumbnail_alt ) {
			$this->add_render_attribute( $image_key, 'alt', $thumbnail_alt );
		}

		if ( '' !== $thumbnail_title ) {
			$this->add_render_attribute( $image_key, 'title', $thumbnail_title );
		}

		if ( 'yes' === $settings['insta_image_popup'] ) {

			$item_link = $this->get_insta_image_url( $item, 'high' );

			$this->add_render_attribute( $link_key, [
				'data-elementor-open-lightbox'      => 'yes',
				'data-elementor-lightbox-title'     => $thumbnail_alt,
				'data-elementor-lightbox-slideshow' => 'vamtam-ig-' . $this->get_id(),
			] );

			/*if ( $this->_is_edit_mode ) {
				$this->add_render_attribute( $link_key, 'class', 'elementor-clickable' );
			}*/

		} elseif ( 'yes' === $settings['insta_image_link'] ) {
			$item_link = $item['link'];

			$this->add_render_attribute( $link_key, 'target', '_blank' );
		}

		$this->add_render_attribute( $link_key, 'href', $item_link );

		$image_html = '<div class="vamtam-if-img">';
		$image_html .= '<div class="vamtam-overlay-container vamtam-media-overlay">';
		if ( 'yes' === $settings['insta_caption'] ) {
			$image_html .= '<div class="vamtam-insta-caption">' . $thumbnail_alt . '</div>';
		}
		if ( 'yes' === $settings['insta_comments'] || 'yes' === $settings['insta_likes'] ) {
			$image_html .= '<div class="vamtam-insta-icons">';
			if ( 'yes' === $settings['insta_comments'] ) {
				$image_html .= '<span class="comments"><i class="vamtam-if-icon fa fa-comment"></i> ' . $comments . '</span>';
			}
			if ( 'yes' === $settings['insta_likes'] ) {
				$image_html .= '<span class="likes"><i class="vamtam-if-icon fa fa-heart"></i> ' . $likes . '</span>';
			}
			$image_html .= '</div>';
		}
		$image_html .= '</div>';
		$image_html .= '<img ' . $this->get_render_attribute_string( $image_key ) . '/>';
		$image_html .= '</div>';

		if ( 'yes' === $settings['insta_image_popup'] || 'yes' === $settings['insta_image_link'] ) {
			$image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a>';
		}

		echo $image_html;
	}

	/**
	 * Get Insta Thumbnail Image URL
	 *
	 * @since  2.2.4
	 * @return string   The url of the instagram post image
	 */
	protected function get_insta_image_url( $item, $size = 'high' ) {
		$thumbnail  = $item['thumbnail'];

		if ( ! empty( $thumbnail[ $size ] ) ) {
			$image_url = $thumbnail[ $size ]['src'];
		} else {
			$image_url = isset( $item['image'] ) ? $item['image'] : '';
		}

		return $image_url;
	}

	/**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_load_more_button() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'load-more-button', 'class', [
			'vamtam-load-more-button',
			'elementor-button',
			'elementor-size-' . $settings['button_size'],
		] );

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'load-more-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

		if ( 'grid' === $settings['feed_layout'] && 'yes' === $settings['load_more_button'] ) {
			?>
			<div class="vamtam-load-more-button-wrap">
				<div <?php echo $this->get_render_attribute_string( 'load-more-button' ); ?>>
					<span class="vamtam-button-loader"></span>
					<span class="vamtam-load-more-button-text">
						<?php echo $settings['load_more_button_text']; ?>
					</span>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render insta feed carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$settings = $this->get_settings();

		if ( 'carousel' === $settings['feed_layout'] && 'yes' === $settings['dots'] ) {
			?>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
			<?php
		}
	}

	/**
	 * Render insta feed carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		$settings = $this->get_settings();

		if ( 'carousel' === $settings['feed_layout'] && 'yes' === $settings['arrows'] ) {
			?>
			<?php
			if ( $settings['arrow'] ) {
				$next_arrow = $settings['arrow'];
				$prev_arrow = str_replace( 'right', 'left', $settings['arrow'] );
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}
			?>
			<!-- Add Arrows -->
			<div class="vamtam-swiper-button vamtam-swiper-button-prev vamtam-slider-arrow vamtam-arrow-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $prev_arrow ); ?>"></i>
			</div>
			<div class="vamtam-swiper-button vamtam-swiper-button-next vamtam-slider-arrow vamtam-arrow-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $next_arrow ); ?>"></i>
			</div>
			<?php
		}
	}

	protected function content_template() {}

}

$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
$widgets_manager->register_widget_type( new Vamtam_Instagram_Feed );
