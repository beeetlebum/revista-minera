<?php
/**
 * SoundJS Effect Extension
 *
 * @package XproElementorAddons
 */

namespace XproElementorAddons\Module;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Soundjs_Effect {

	public static function init() {
		add_action( 'elementor/frontend/before_register_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( __CLASS__, 'register' ) );
		add_action( 'elementor/frontend/before_render', array( __CLASS__, 'add_attributes' ) );
	}

	public static function register_scripts() {
		wp_register_script(
			'xpro-soundjs-js',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/sound-effect/js/sound-effect.js',
			array( 'jquery', 'soundjs' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'soundjs' );
		wp_enqueue_script( 'xpro-soundjs-js' );
	}

	public static function add_attributes( Element_Base $element ) {
		$settings = $element->get_settings_for_display();

		if ( 'yes' !== ( $settings['xpro_soundjs_enable'] ?? '' ) ) {
			return;
		}

		$payload = array(
			'enable'        => true,
			'trigger'       => $settings['xpro_soundjs_trigger'] ?? 'click',
			'triggerClass'  => $settings['xpro_soundjs_trigger_class'] ?? '',
			'volume'        => ! empty( $settings['xpro_soundjs_volume']['size'] ) ? floatval( $settings['xpro_soundjs_volume']['size'] ) : 0.7,
			'hoverDelay'    => intval( $settings['xpro_soundjs_hover_delay'] ?? 0 ),
			'allowMultiple' => ( 'yes' === ( $settings['xpro_soundjs_allow_multiple'] ?? '' ) ),
		);

		$element->add_render_attribute(
			'_wrapper',
			array(
				'class'                => 'xpro-soundjs-effect-yes',
				'data-soundjs-enabled' => 'yes',
				'data-soundjs-settings'=> wp_json_encode( $payload ),
			)
		);
	}

	public static function register( Element_Base $element ) {
		$element->start_controls_section(
			'section_xpro_soundjs_effect',
			array(
				'label' => __( 'Sound Effect', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'xpro_soundjs_enable',
			array(
				'label'              => __( 'Enable Sound Effect', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'          => __( 'No', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'render_type'        => 'template',
				'prefix_class'       => 'xpro-soundjs-effect-',
			)
		);

		$element->add_control(
			'xpro_soundjs_audio',
			array(
				'label'              => __( 'Sound File', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'audio',
				// 'default'            => array(),
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'xpro_soundjs_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_soundjs_trigger',
			array(
				'label'              => __( 'Play Sound On', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'click',
				'frontend_available' => true,
				'render_type'        => 'template',
				'options'            => array(
					'click' => __( 'Click', 'xpro-elementor-addons' ),
					'hover' => __( 'Hover', 'xpro-elementor-addons' ),
					// 'load'  => __( 'Page Load', 'xpro-elementor-addons' ),
					'class' => __( 'Custom CSS Class', 'xpro-elementor-addons' ),
				),
				'condition'          => array(
					'xpro_soundjs_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_soundjs_trigger_class',
			array(
				'label'              => __( 'Trigger Class', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'placeholder'        => '.play-sound',
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'xpro_soundjs_enable'  => 'yes',
					'xpro_soundjs_trigger' => 'class',
				),
			)
		);

		$element->add_control(
			'xpro_soundjs_volume',
			array(
				'label'              => __( 'Volume', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'render_type'        => 'template',
				'range'              => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'default'            => array(
					'size' => 0.7,
				),
				'condition'          => array(
					'xpro_soundjs_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_soundjs_hover_delay',
			array(
				'label'              => __( 'Hover Delay (ms)', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'min'                => 0,
				'max'                => 5000,
				'step'               => 50,
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'xpro_soundjs_enable'  => 'yes',
					'xpro_soundjs_trigger' => 'hover',
				),
			)
		);

		$element->end_controls_section();
	}
}

Xpro_Elementor_Soundjs_Effect::init();