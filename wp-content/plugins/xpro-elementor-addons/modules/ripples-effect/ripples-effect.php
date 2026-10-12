<?php
/**
 * Ripples Effect Extension
 *
 * @package XproElementorAddons
 */

namespace XproElementorAddons\Module;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Ripples_Effect {

	/**
	 * Init
	 */
	public static function init() {

		add_action( 'elementor/element/section/section_advanced/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( __CLASS__, 'register' ), 10 );

        add_action( 'elementor/frontend/before_render', array( __CLASS__, 'should_enqueue' ) );
		add_action( 'elementor/frontend/after_render', array( __CLASS__, 'should_enqueue' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

	}

	/**
	 * Enqueue Scripts
	 */
	public static function enqueue_scripts() {

		wp_enqueue_script( 'ripples' );
		wp_enqueue_script( 'xpro-ripples-js', XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/ripples-effect/js/ripples-effect.js', array('jquery'), 	XPRO_ELEMENTOR_ADDONS_VERSION, true );
	}

	/**
	 * Enqueue Only When Needed
	 */
	public static function should_enqueue( Element_Base $element ) {
		if ( 'enabled' === $element->get_settings_for_display( 'xpro_ripples_enable' ) ) {
			self::enqueue_scripts();
			remove_action( 'elementor/frontend/before_render', array( __CLASS__, 'should_enqueue' ) );
		}
	}

	/**
	 * Register Controls
	 */
	public static function register( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_ripples_effect',
			array(
				'label' => __( 'Ripples Effect', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'xpro_ripples_enable',
			array(
				'label'              => __( 'Enable Ripples', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'          => __( 'No', 'xpro-elementor-addons' ),
				'return_value'       => 'enabled',
				'default'            => '',
				'frontend_available' => true,
				'render_type'        => 'template',
				'prefix_class'       => 'xpro-ripples-effect-',
			)
		);

		$element->add_control(
			'xpro_ripples_resolution',
			array(
				'label'       => __( 'Resolution', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Width/height of WebGL texture.', 'xpro-elementor-addons' ),
				'range'       => array(
					'px' => array(
						'min'  => 64,
						'max'  => 1024,
						'step' => 32,
					),
				),
				'default'     => array(
					'size' => 256,
					'unit' => 'px',
				),
				'condition'   => array(
					'xpro_ripples_enable' => 'enabled',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->add_control(
			'xpro_ripples_drop_radius',
			array(
				'label'       => __( 'Drop Radius', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Size of drops in pixels.', 'xpro-elementor-addons' ),
				'range'       => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'     => array(
					'size' => 20,
					'unit' => 'px',
				),
				'condition'   => array(
					'xpro_ripples_enable' => 'enabled',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->add_control(
			'xpro_ripples_perturbance',
			array(
				'label'       => __( 'Perturbance', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Amount of refraction.', 'xpro-elementor-addons' ),
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 0.5,
						'step' => 0.01,
					),
				),
				'default'     => array(
					'size' => 0.03,
				),
				'condition'   => array(
					'xpro_ripples_enable' => 'enabled',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->add_control(
			'xpro_ripples_interactive',
			array(
				'label'              => __( 'Interactive', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'          => __( 'No', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'condition'          => array(
					'xpro_ripples_enable' => 'enabled',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->add_control(
			'xpro_ripples_auto_drops',
			array(
				'label'              => __( 'Auto Drops', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'          => __( 'No', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => '',
				'condition'          => array(
					'xpro_ripples_enable' => 'enabled',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->add_control(
			'xpro_ripples_auto_drops_interval',
			array(
				'label'              => __( 'Auto Drops Interval (ms)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1000,
				'min'                => 100,
				'max'                => 10000,
				'step'               => 100,
				'condition'          => array(
					'xpro_ripples_enable'      => 'enabled',
					'xpro_ripples_auto_drops'  => 'yes',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$element->end_controls_section();
	}
}

Xpro_Elementor_Ripples_Effect::init();