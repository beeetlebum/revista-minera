<?php
/**
 * Reveal Effect Extension
 *
 * @package XproElementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Revealing_Effect {

	public static function init() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( __CLASS__, 'register' ), 10 );

		add_action( 'elementor/frontend/before_render', array( __CLASS__, 'should_enqueue' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'revealFx' );
		wp_enqueue_script( 'xpro-reveal-js', XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/reveal-effect/js/reveal-effect.js', null, XPRO_ELEMENTOR_ADDONS_VERSION, true );
	}

	public static function should_enqueue( Element_Base $element ) {
		if ( 'enabled' === $element->get_settings_for_display( 'enable_reveal_effect' ) ) {
			self::enqueue_scripts();
			remove_action( 'elementor/frontend/before_render', array( __CLASS__, 'should_enqueue' ) );
		}
	}

	public static function register( Element_Base $element ) {

		$element->start_controls_section(
			'section_xpro_reveal_effect',
			array(
				'label' => __( 'Reveal Effect', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'enable_reveal_effect',
			array(
				'label'              => __( 'Enable Reveal Effect', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'          => __( 'No', 'xpro-elementor-addons' ),
				'return_value'       => 'enabled',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'xpro-reveal-effect-',
			)
		);

		$element->add_control(
			'xpro_reveal_selector_type',
			array(
				'label'              => __( 'Target', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'default',
				'options'            => array(
					'default' => __( 'This Element (Default)', 'xpro-elementor-addons' ),
					'custom'  => __( 'Custom Selector', 'xpro-elementor-addons' ),
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_custom_selector',
			array(
				'label'              => __( 'CSS Selector', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'placeholder'        => __( '.class-name', 'xpro-elementor-addons' ),
				'condition'          => array(
					'enable_reveal_effect'      => 'enabled',
					'xpro_reveal_selector_type' => 'custom',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_direction',
			array(
				'label'              => __( 'Direction', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'lr',
				'options'            => array(
					'lr' => __( 'Left → Right', 'xpro-elementor-addons' ),
					'rl' => __( 'Right → Left', 'xpro-elementor-addons' ),
					'tb' => __( 'Top → Bottom', 'xpro-elementor-addons' ),
					'bt' => __( 'Bottom → Top', 'xpro-elementor-addons' ),
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_content_show',
			array(
				'label'              => __( 'Show Content During Reveal', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_cover_area',
			array(
				'label'              => __( 'Cover Area', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 1000,
				'default'            => 0,
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_layers',
			array(
				'label'              => __( 'Layers', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'xpro_reveal_overlay_color',
			array(
				'label'              => __( 'Color', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::COLOR,
				'default'            => '#111111',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_overlay_items',
			array(
				'label'              => __( 'Overlay Colors', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeater->get_controls(),
				'default'            => array(
					array(
						'xpro_reveal_overlay_color' => '#111111',
					),
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_easing',
			array(
				'label'              => __( 'Reveal Easing', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'easeOutExpo',
				'options'            => array(
					'default'        => 'Default',
					'linear'         => 'linear',
					'ease'           => 'ease',
					'easeInSine'     => 'easeInSine',
					'easeOutSine'    => 'easeOutSine',
					'easeInOutSine'  => 'easeInOutSine',
					'easeInQuad'     => 'easeInQuad',
					'easeOutQuad'    => 'easeOutQuad',
					'easeInOutQuad'  => 'easeInOutQuad',
					'easeInCubic'    => 'easeInCubic',
					'easeOutCubic'   => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInQuart'    => 'easeInQuart',
					'easeOutQuart'   => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInQuint'    => 'easeInQuint',
					'easeOutQuint'   => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInExpo'     => 'easeInExpo',
					'easeOutExpo'    => 'easeOutExpo',
					'easeInOutExpo'  => 'easeInOutExpo',
					'easeInCirc'     => 'easeInCirc',
					'easeOutCirc'    => 'easeOutCirc',
					'easeInOutCirc'  => 'easeInOutCirc',
					'easeInBack'     => 'easeInBack',
					'easeOutBack'    => 'easeOutBack',
					'easeInOutBack'  => 'easeInOutBack',
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_duration',
			array(
				'label'              => __( 'Duration (ms)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'unit' => 'px',
					'size' => 700,
				),
				'range'              => array(
					'px' => array(
						'min' => 200,
						'max' => 5000,
					),
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'xpro_reveal_delay',
			array(
				'label'              => __( 'Stagger Delay (ms)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'unit' => 'px',
					'size' => 120,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
				),
				'condition'          => array(
					'enable_reveal_effect' => 'enabled',
				),
				'frontend_available' => true,
			)
		);

		// $element->add_control(
		// 	'xpro_reveal_on_start',
		// 	array(
		// 		'label'              => __( 'On Start Callback', 'xpro-elementor-addons' ),
		// 		'type'               => Controls_Manager::TEXTAREA,
		// 		'description'        => __( 'JavaScript function to execute when reveal starts', 'xpro-elementor-addons' ),
		// 		'condition'          => array(
		// 			'enable_reveal_effect' => 'enabled',
		// 		),
		// 		'frontend_available' => true,
		// 	)
		// );

		// $element->add_control(
		// 	'xpro_reveal_on_halfway',
		// 	array(
		// 		'label'              => __( 'On Halfway Callback', 'xpro-elementor-addons' ),
		// 		'type'               => Controls_Manager::TEXTAREA,
		// 		'description'        => __( 'JavaScript function to execute when reveal is halfway', 'xpro-elementor-addons' ),
		// 		'condition'          => array(
		// 			'enable_reveal_effect' => 'enabled',
		// 		),
		// 		'frontend_available' => true,
		// 	)
		// );

		// $element->add_control(
		// 	'xpro_reveal_on_complete',
		// 	array(
		// 		'label'              => __( 'On Complete Callback', 'xpro-elementor-addons' ),
		// 		'type'               => Controls_Manager::TEXTAREA,
		// 		'description'        => __( 'JavaScript function to execute when reveal completes', 'xpro-elementor-addons' ),
		// 		'condition'          => array(
		// 			'enable_reveal_effect' => 'enabled',
		// 		),
		// 		'frontend_available' => true,
		// 	)
		// );

		$element->end_controls_section();
	}
}

Xpro_Elementor_Revealing_Effect::init();