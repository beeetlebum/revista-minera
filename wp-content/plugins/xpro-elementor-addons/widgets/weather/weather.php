<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class weather extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-weather';
	}

	/**
	 * Get widget inner wrapper.
	 *
	 * Retrieve widget require the inner wrapper or not.
	 *
	 */
	public function has_widget_inner_wrapper(): bool {
		$has_wrapper = ! Plugin::$instance->experiments->is_feature_active('e_optimized_markup');
		return $has_wrapper;
	}
	
	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Weather', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-cloud xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'weather',  );
	}

	
	/**
	 * Register widget controls
	 */
	protected function register_controls() {

		// Content Section: Weather Settings
		$this->start_controls_section(
			'section_weather_settings',
			array(
				'label' => __( 'Weather Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'api_key',
			array(
				'label'       => __( 'Google Weather API Key', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => defined( 'GOOGLE_WEATHER_API_KEY' ) ? GOOGLE_WEATHER_API_KEY : '',
				'description' => __( 'Enter your Google Weather API key', 'xpro-elementor-addons' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'latitude',
			array(
				'label'       => __( 'Latitude', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '42.350299435480835',
				'description' => __( 'Enter location latitude', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'longitude',
			array(
				'label'       => __( 'Longitude', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '-71.05710485767234',
				'description' => __( 'Enter location longitude', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'location_name',
			array(
				'label'       => __( 'Location', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '745 Atlantic Ave 8th Fl',
				'description' => __( 'Enter location to display', 'xpro-elementor-addons' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'forecast_hours',
			array(
				'label'       => __( 'Number of Hours', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 12,
				'min'         => 1,
				'max'         => 48,
				'step'        => 1,
				'description' => __( 'Number of forecast hours to display', 'xpro-elementor-addons' ),
			)
		);


		$this->add_control(
			'widget_title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Weather', 'xpro-elementor-addons' ),
				'label_block' => true,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'widget_title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'DIV',
					'p' => 'p',
					'span' => 'SPAN',
				),
				'default' => 'h3',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Content Section: Layout Selection
		$this->start_controls_section(
			'section_layout_selection',
			array(
				'label' => __( 'Layout Selection', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'weather_layout',
			array(
				'label'   => __( 'Weather Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'layout-1' => __( 'Layout 1 - Minimal Horizontal', 'xpro-elementor-addons' ),
					'layout-2' => __( 'Layout 2 - Detailed Card', 'xpro-elementor-addons' ),
					'layout-3' => __( 'Layout 3 - Modern Vertical', 'xpro-elementor-addons' ),
					'layout-4' => __( 'Layout 4 - Forecast Focus', 'xpro-elementor-addons' ),
					'layout-5' => __( 'Layout 5 - Compact Grid', 'xpro-elementor-addons' ),
				),
				'default' => 'layout-1',
			)
		);

		$this->end_controls_section();

		// Content Section: Icon Settings
		$this->start_controls_section(
			'section_icon_settings',
			array(
				'label' => __( 'Icon Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'icon_source',
			array(
				'label'   => __( 'Icon Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'api'    => __( 'Use API Icons', 'xpro-elementor-addons' ),
					'custom' => __( 'Use Custom Icons', 'xpro-elementor-addons' ),
					'none'   => __( 'Hide Icons', 'xpro-elementor-addons' ),
				),
				'default' => 'custom',
			)
		);

		$this->add_control(
			'custom_sunny_icon',
			array(
				'label'     => __( 'Sunny / Clear Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
				   'url' => XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/weather/sunny.png',
				),
				'condition' => array(
					'icon_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_cloudy_icon',
			array(
				'label'     => __( 'Cloudy Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/weather/cloudy.png',
				),
				'condition' => array(
					'icon_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_partly_cloudy_icon',
			array(
				'label'     => __( 'Partly Cloudy Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/weather/partlyCloudy.png',
				),
				'condition' => array(
					'icon_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_rainy_icon',
			array(
				'label'     => __( 'Rainy Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/weather/rainy.png',
				),
				'condition' => array(
					'icon_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_storm_icon',
			array(
				'label'     => __( 'Thunderstorm Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => XPRO_ELEMENTOR_ADDONS_ASSETS . 'images/weather/thunderstorm.png',
				),
				'condition' => array(
					'icon_source' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .weather-icon, {{WRAPPER}} .weather-forecast-icon' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
				'condition'  => array(
					'icon_source!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		// Content Section: Display Settings
		$this->start_controls_section(
			'section_display_settings',
			array(
				'label' => __( 'Display Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title ', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'show_current_weather',
			array(
				'label'        => __( 'Show Current Weather', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_temperature',
			array(
				'label'        => __( 'Show Temperature', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'show_current_weather' => 'yes' ),
			)
		);

		$this->add_control(
			'show_feels_like',
			array(
				'label'        => __( 'Show Feels Like', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_current_weather' => 'yes',
					'weather_layout!'      => array('layout-5' ),
				),
			)
		);

		$this->add_control(
			'show_humidity',
			array(
				'label'        => __( 'Show Humidity', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'show_current_weather' => 'yes' ),
			)
		);

		$this->add_control(
			'show_wind',
			array(
				'label'        => __( 'Show Wind', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'show_current_weather' => 'yes' ),
			)
		);

		$this->add_control(
			'show_pressure',
			array(
				'label'        => __( 'Show Pressure', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_current_weather' => 'yes',
					'weather_layout!'      => array( 'layout-1' ),
				),
				
			)
		);

		$this->add_control(
			'show_visibility',
			array(
				'label'        => __( 'Show Visibility', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_current_weather' => 'yes',
					'weather_layout!'      => array( 'layout-1', 'layout-3', 'layout-5' ),
				),
			)
		);

		$this->add_control(
			'show_dew_point',
			array(
				'label'        => __( 'Show Dew Point', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_current_weather' => 'yes',
					'weather_layout!'      => array( 'layout-1', 'layout-3',  'layout-4', 'layout-5' ),
				),
			)
		);

		$this->add_control(
			'show_uv_index',
			array(
				'label'        => __( 'Show UV Index', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'show_current_weather' => 'yes',
					'weather_layout!'      => array( 'layout-1', 'layout-3', 'layout-4', 'layout-5' ),
				),
			)
		);

		$this->add_control(
			'show_forecast',
			array(
				'label'        => __( 'Show Hourly Forecast', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'temperature_unit',
			array(
				'label'   => __( 'Temperature Unit', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'C' => __( 'Celsius (°C)', 'xpro-elementor-addons' ),
					'F' => __( 'Fahrenheit (°F)', 'xpro-elementor-addons' ),
				),
				'default' => 'C',
			)
		);

		$this->end_controls_section();

	// Style Section: Layout 1 - Minimal Horizontal
		$this->start_controls_section(
			'section_style_layout1',
			array(
				'label'     => __( 'Layout 1: Minimal Horizontal', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'weather_layout' => 'layout-1','weather_layout!' => 'layout4', ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'  => 'layout1_background',
				'label' => __( 'Background', 'xpro-elementor-addons' ),
				'types' => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-1',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'default' => '#f5f5f5',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'layout1_border',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-1',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-1' => 'border-style: {{VALUE}} !important;',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-1' => 'border-color: {{VALUE}} !important;',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-1' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout1_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
					'unit'   => 'px',
				),
			)
		);

		$this->add_responsive_control(
			'layout1_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'weather_layout' => 'layout-1',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Layout 2 - Detailed Card
		$this->start_controls_section(
			'section_style_layout2',
			array(
				'label'     => __( 'Layout 2: Detailed Card', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'weather_layout' => 'layout-2' ),
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'layout2_background',
				'separator_before' => 'after',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-2',
			)
		);


		$this->add_control(
			'layout2_background_header_heading',
			array(
				'label'            => __( 'Header Background', 'xpro-elementor-addons' ),
				'type'             => Controls_Manager::HEADING,
			    'separator' => 'before', 
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'layout2_background_header',
				'separator_before' => 'after',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .xpro-weather-widget .layout2-header',
			)
		);

		$this->add_control(
			'layout2_background_details_heading',
			array(
				'label'    => __( 'Details Background', 'xpro-elementor-addons' ),
				'type'     => Controls_Manager::HEADING,
				'separator'=> 'before', 
				'condition'=> array(
					'weather_layout' => 'layout-2',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'layout2_background_details',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .xpro-weather-widget .layout2-details-grid  ',
				'separator' => 'after', 
			)
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'layout2_border',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-2',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'border-style: {{VALUE}} !important;',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'border-color: {{VALUE}} !important;',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout2_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '15',
					'right'  => '15',
					'bottom' => '15',
					'left'   => '15',
					'unit'   => 'px',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'layout2_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-2',
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'box-shadow: {{HORIZONTAL}} {{VERTICAL}} {{BLUR}} {{SPREAD}} {{COLOR}} {{box_shadow_position}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout2_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '25',
					'right'  => '25',
					'bottom' => '25',
					'left'   => '25',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Layout 3 - Modern Vertical
		$this->start_controls_section(
			'section_style_layout3',
			array(
				'label'     => __( 'Layout 3: Modern Vertical', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'weather_layout' => 'layout-3' ),
			)
		);


		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'layout3_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-3',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'layout3_border',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-3',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-3' => 'border-style: {{VALUE}} !important;',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-3' => 'border-color: {{VALUE}} !important;',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-3' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout3_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'layout3_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '30',
					'right'  => '20',
					'bottom' => '30',
					'left'   => '20',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Layout 4 - Forecast Focus
		$this->start_controls_section(
			'section_style_layout4',
			array(
				'label'     => __( 'Layout 4: Forecast Focus', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'weather_layout' => 'layout-4',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'layout4_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-4',
			)
		);

		$this->add_control(
			'layout4_text_color',
			array(
				'label'   => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-4,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .weather-widget-title,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 h4,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .current-temp,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .current-condition,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .feels-like,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .hour-time,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .hour-temp,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .hour-condition,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'layout4_border',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-4',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-4' => 'border-style: {{VALUE}} !important;',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-4' => 'border-color: {{VALUE}} !important;',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-4' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout4_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'layout4_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Layout 5 - Compact Grid
		$this->start_controls_section(
			'section_style_layout5',
			array(
				'label'     => __( 'Layout 5: Compact Grid', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'weather_layout' => 'layout-5' ),
			)
		);

		$this->add_control(
			'layout5_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'background-color: {{VALUE}} !important;',
				),
				'default'   => '#f8f9fa',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'layout5_border',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-layout-5',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'border-style: {{VALUE}} !important;',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'border-color: {{VALUE}} !important;',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'layout5_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'layout5_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-layout-5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '15',
					'right'  => '15',
					'bottom' => '15',
					'left'   => '15',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Title
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title'      => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-widget-title',
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'title_color',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-widget-title',
				'fields_options' => array(
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} .xpro-weather-widget .weather-widget-title' => 'color: {{VALUE}} !important;',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-widget-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '15',
					'left'   => '0',
					'unit'   => 'px',
				),
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'   => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default' => 'left',
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-widget-title' => 'text-align: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_location',
			array(
				'label'     => __( 'Location', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'weather_layout' => 'layout-2',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'location_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .layout2-location h3',
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'location_color',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .layout2-location h3',
			)
		);

		$this->add_control(
			'layout2_background_time_color',
			array(
				'label'    => __( 'Current Time', 'xpro-elementor-addons' ),
				'type'     => Controls_Manager::HEADING,
				'separator'=> 'before', 
				'condition'=> array(
					'weather_layout' => 'layout-2',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'layout2_location_typography',
				'selector'  => '{{WRAPPER}} .xpro-weather-widget  .current-time',
				'condition' => array(
					'weather_layout' => 'layout-2',
				),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'layout2_heading_color',
				'selector' => ' {{WRAPPER}} .xpro-weather-widget  .current-time',
				'condition' => array(
					'weather_layout' => 'layout-2',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Temperature
		$this->start_controls_section(
			'section_style_temperature',
			array(
				'label' => __( 'Temperature', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'temperature_typography',
				'label'    => __( 'Temperature Typography', 'xpro-elementor-addons' ),
				'selector' => '
					{{WRAPPER}} .xpro-weather-widget .weather-temp,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .current-info div,
					{{WRAPPER}} .xpro-weather-widget .layout-5-temperature
				',
			)
		);

		$this->add_control(
			'temperature_color',
			array(
				'label'   => __( 'Color', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::COLOR,
				// 'default' => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-temp,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .current-info div ,
					{{WRAPPER}} .xpro-weather-widget .layout-5-temperature .weather-temp' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'temperature_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-temp,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .current-info div,
					{{WRAPPER}} .xpro-weather-widget .layout-5-temperature .weather-temp' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Weather Description
		$this->start_controls_section(
			'section_style_description',
			array(
				'label' => __( 'Weather Description', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Description Typography', 'xpro-elementor-addons' ),
				'selector' => '
					{{WRAPPER}} .xpro-weather-widget .weather-desc-text,
					{{WRAPPER}} .xpro-weather-widget .weather-condition-text,
					{{WRAPPER}} .xpro-weather-widget .feels-like,
					{{WRAPPER}} .xpro-weather-widget .weather-condition
				',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-desc-text,
					{{WRAPPER}} .xpro-weather-widget .weather-condition-text,
					{{WRAPPER}} .xpro-weather-widget .feels-like,
					{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .feels-like,
					{{WRAPPER}} .xpro-weather-widget .weather-condition' => 'color: {{VALUE}} !important;',
				),
				// 'default'   => '#666666',
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-desc,
					{{WRAPPER}} .xpro-weather-widget .feels-like,
					{{WRAPPER}} .xpro-weather-widget .weather-condition' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Weather Details Grid
		$this->start_controls_section(
			'section_style_details',
			array(
				'label' => __( 'Weather Details', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'forecast_items_background',
				'label'    => __( 'Items Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .layout4-highlights .highlight,
				{{WRAPPER}} .xpro-weather-widget .layout5-stats div',
				'condition' => array(
					'weather_layout' => array( 'layout-4', 'layout-5' ),
				),
			)
		);

		$this->add_control(
			'details_label_color',
			array(
				'label'     => __( 'Label Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-detail-label,
					{{WRAPPER}} .xpro-weather-widget .detail-item,
					{{WRAPPER}} .xpro-weather-widget .detail-label,
				    {{WRAPPER}} .xpro-weather-widget .weather-layout-4 .highlight,
					{{WRAPPER}} .xpro-weather-widget .stat-label' => 'color: {{VALUE}} !important;',
				),
				'default'   => '#888888',
			)
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'details_label_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-detail-label,
								{{WRAPPER}} .xpro-weather-widget .detail-item,
								{{WRAPPER}} .xpro-weather-widget .detail-label,
								{{WRAPPER}} .xpro-weather-widget .weather-layout-4 .highlight,
								{{WRAPPER}} .xpro-weather-widget .stat-label',
			)
		);
		
        $this->add_control(
			'details_label_heading',
			array(
				'label'     => __( 'Weather Details Value', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'details_value_color',
			array(
				'label'     => __( 'Value Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .weather-detail-value,
					{{WRAPPER}} .xpro-weather-widget .detail-value,
					{{WRAPPER}} .xpro-weather-widget .stat span:not(.stat-label)' => 'color: {{VALUE}} !important;',
				),
				'default'   => '#333333',
				'condition' => array(
					'weather_layout' => array('layout-2', 'layout-5'),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'details_value_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .weather-detail-value,
				{{WRAPPER}} .xpro-weather-widget .detail-value,
				{{WRAPPER}} .xpro-weather-widget .stat span:not(.stat-label)',
				'condition' => array(
					'weather_layout' => array('layout-2', 'layout-5'),
				),
			)
		);
		
		$this->add_responsive_control(
			'details_gap',
			array(
				'label'      => __( 'Gap', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors'  => array(
					// '{{WRAPPER}} .xpro-weather-widget .weather-details,
					'{{WRAPPER}} .xpro-weather-widget .layout1-details div, .xpro-weather-widget .layout4-highlights ' => 'margin:0px {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .xpro-weather-widget .xpro-weather-widget .layout5-stats' => 'margin:0px {{SIZE}}{{UNIT}} !important;',

				),
				'condition' => array(
					'weather_layout!' => array('layout-2', 'layout-3', 'layout-5'),
				),
			)
		);

		$this->end_controls_section();

		// Style Section: Forecast Items
		$this->start_controls_section(
			'section_style_forecast',
			array(
				'label' => __( 'Forecast', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			
			)
		);

		$this->add_control(
			'forecast_title',
			array(
				'label'     => __( 'Forecast Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Hourly Forecast', 'xpro-elementor-addons' ),
				'condition' => array( 'weather_layout' => 'layout-2' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_title_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget  .layout2-forecast h4',
				'condition' => array( 'weather_layout' => 'layout-2' ),
			)
		);

		$this->add_control(
			'forecast_title_color',
			array(
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget  .layout2-forecast h4' => 'color: {{VALUE}};',
				),
				'condition' => array( 'weather_layout' => 'layout-2' ),
				'default'   => '#333333',
			)
		);

		$this->add_responsive_control(
			'forecast_title_margin',
			array(
				'label'      => __( 'Title Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-weather-widget  .layout2-forecast h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array( 'weather_layout' => 'layout-2' ),
			)
		);

		$this->add_responsive_control(
		'forecast_hour_width',
			array(
				'label'      => __( 'Forecast Item Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
						'{{WRAPPER}} .xpro-weather-widget .forecast-hour'  => 'min-width: {{SIZE}}{{UNIT}} !important;',
			            '{{WRAPPER}} .xpro-weather-widget .forecast-block' => 'min-width: {{SIZE}}{{UNIT}} !important;',
			            '{{WRAPPER}} .xpro-weather-widget .forecast-item'  => 'min-width: {{SIZE}}{{UNIT}} !important;',
						'{{WRAPPER}} .xpro-weather-widget .forecast-card'  => 'min-width: {{SIZE}}{{UNIT}} !important;',
						'{{WRAPPER}} .xpro-weather-widget .timeline-hour'  => 'min-width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'forecast_hour_background',
				'label'    => __( 'Forecast Item Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-weather-widget .forecast-item,
				{{WRAPPER}} .xpro-weather-widget .forecast-card,
				{{WRAPPER}} .xpro-weather-widget .forecast-timeline .timeline-hour,
				{{WRAPPER}} .xpro-weather-widget .forecast-block,
				{{WRAPPER}} .xpro-weather-widget .forecast-hour',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'default' => '#FFFFFF',
					),
				),
			)
		);

		$this->add_control(
			'forecast_time_color_heading',
			array(
				'label'     => __( 'Time', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'forecast_time_color',
			array(
				'label'     => __( 'Time Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .forecast-time,
					{{WRAPPER}} .xpro-weather-widget .forecast-timeline  .hour-time,
					{{WRAPPER}} .xpro-weather-widget .block-time' => 'color: {{VALUE}} !important;',
				),
				'default'   => '#666666',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_time_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .forecast-time,
				{{WRAPPER}} .xpro-weather-widget .forecast-timeline  .hour-time,
				{{WRAPPER}} .xpro-weather-widget .block-time',
			)
		);

		$this->add_control(
			'forecast_time_color_temp',
			array(
				'label'     => __( 'Forecast Temperature', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'forecast_temp_color',
			array(
				'label'   => __( 'Forecast Temperature Color', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .forecast-temp,
					{{WRAPPER}} .xpro-weather-widget .hour-temp,
					{{WRAPPER}} .xpro-weather-widget .block-temp' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_temp_typography',
				'selector' => '{{WRAPPER}} .xpro-weather-widget .forecast-temp,
				{{WRAPPER}} .xpro-weather-widget .hour-temp,
				{{WRAPPER}} .xpro-weather-widget .block-temp',
			)
		);

		$this->add_control(
			'forecast_time_color_desc',
			array(
				'label'     => __( 'Forecast Desc Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'weather_layout!' => array('layout-1', 'layout-5' )),
			)
		);

		$this->add_control(
			'forecast_desc_color',
			array(
				'label'     => __( 'Forecast Description Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .xpro-weather-widget .forecast-condition,
					{{WRAPPER}} .xpro-weather-widget .hour-condition,
					{{WRAPPER}} .xpro-weather-widget .forecast-desc' => 'color: {{VALUE}} !important;',
				),
				'condition' => array(
					'weather_layout!' => array( 'layout-1', 'layout-5' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'forecast_desc_typography',
				'selector'  => '{{WRAPPER}} .xpro-weather-widget .forecast-condition,
				{{WRAPPER}} .xpro-weather-widget .hour-condition,
				{{WRAPPER}} .xpro-weather-widget .forecast-desc',
				'condition' => array(
					'weather_layout!' => array( 'layout-1', 'layout-5' ),
				),
			)
		);

		
		$this->end_controls_section();
	}

	/**
	 * Convert Celsius to Fahrenheit
	 */
	private function convert_temperature( $celsius, $unit ) {
		if ( 'F' === $unit ) {
			return round( ( $celsius * 9/5 ) + 32, 1 );
		}
		return round( $celsius, 1 );
	}

	/**
	 * Get weather icon based on condition type
	 */
	private function get_weather_icon( $condition_type, $is_daytime = true, $settings = array() ) {
		$icon_source = isset( $settings['icon_source'] ) ? $settings['icon_source'] : 'api';
		
		if ( 'none' === $icon_source ) {
			return '';
		}
		
		if ( 'custom' === $icon_source ) {
			$icon_map = array(
				'CLEAR'          => 'custom_sunny_icon',
				'SUNNY'          => 'custom_sunny_icon',
				'MOSTLY_CLEAR'   => 'custom_sunny_icon',
				'PARTLY_CLOUDY'  => 'custom_partly_cloudy_icon',
				'MOSTLY_CLOUDY'  => 'custom_cloudy_icon',
				'CLOUDY'         => 'custom_cloudy_icon',
				'RAIN'           => 'custom_rainy_icon',
				'THUNDERSTORM'   => 'custom_storm_icon',
				'SNOW'           => 'custom_snow_icon',
			);
			
			$icon_key = isset( $icon_map[ $condition_type ] ) ? $icon_map[ $condition_type ] : 'custom_sunny_icon';
			
			if ( isset( $settings[ $icon_key ]['url'] ) && ! empty( $settings[ $icon_key ]['url'] ) ) {
				return esc_url( $settings[ $icon_key ]['url'] );
			}
		}
		
		return '';
	}

	/**
	 * Get API icon URL
	 */
	private function get_api_icon_url( $icon_base_uri, $is_daytime = true ) {
		if ( empty( $icon_base_uri ) ) {
			return '';
		}
		
		$suffix = $is_daytime ? '_day' : '_night';
		return $icon_base_uri . $suffix . '.svg';
	}

	/**
	 * Format time from displayDateTime
	 */
	private function format_time( $display_date_time ) {
		if ( ! is_array( $display_date_time ) ) {
			return '';
		}
		
		$hours = isset( $display_date_time['hours'] ) ? $display_date_time['hours'] : 0;
		$minutes = isset( $display_date_time['minutes'] ) ? $display_date_time['minutes'] : 0;
		
		$ampm = $hours >= 12 ? 'PM' : 'AM';
		$hours12 = $hours % 12;
		$hours12 = $hours12 ? $hours12 : 12;
		
		return sprintf( '%d:%02d %s', $hours12, $minutes, $ampm );
	}

	/**
	 * Get wind direction arrow
	 */
	private function get_wind_arrow( $degrees ) {
		if ( $degrees >= 337.5 || $degrees < 22.5 ) return '↓';
		if ( $degrees >= 22.5 && $degrees < 67.5 ) return '↙';
		if ( $degrees >= 67.5 && $degrees < 112.5 ) return '←';
		if ( $degrees >= 112.5 && $degrees < 157.5 ) return '↖';
		if ( $degrees >= 157.5 && $degrees < 202.5 ) return '↑';
		if ( $degrees >= 202.5 && $degrees < 247.5 ) return '↗';
		if ( $degrees >= 247.5 && $degrees < 292.5 ) return '→';
		if ( $degrees >= 292.5 && $degrees < 337.5 ) return '↘';
		return '↓';
	}

	/**
	 * Fetch weather data from API
	 */
	private function get_weather_data() {
		$settings = $this->get_settings_for_display();
		
		$api_key = ! empty( $settings['api_key'] ) ? $settings['api_key'] : 
				   ( defined( 'GOOGLE_WEATHER_API_KEY' ) ? GOOGLE_WEATHER_API_KEY : '' );
		
		if ( empty( $api_key ) ) {
			return array( 'error' => 'API key is missing' );
		}
		
		$lat = ! empty( $settings['latitude'] ) ? $settings['latitude'] : '32.0836';
		$lng = ! empty( $settings['longitude'] ) ? $settings['longitude'] : '72.6711';
		
		$url = add_query_arg(
			array(
				'key' => $api_key,
				'location.latitude'  => $lat,
				'location.longitude' => $lng,
			),
			'https://weather.googleapis.com/v1/forecast/hours:lookup'
		);
		
		$response = wp_remote_get( $url, array(
			'timeout' => 15,
		) );
		
		if ( is_wp_error( $response ) ) {
			return array( 'error' => $response->get_error_message() );
		}
		
		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		
		if ( $code !== 200 || empty( $body ) ) {
			return array( 'error' => "Weather data error ({$code})" );
		}
		
		$data = json_decode( $body, true );
		
		if ( ! is_array( $data ) ) {
			return array( 'error' => 'Invalid weather data format' );
		}
		
		return $data;
	}

	/**
	 * Get appropriate CSS class for weather condition (fallback if no icons)
	 */
	private function get_weather_icon_class( $condition_type ) {
		$icon_classes = array(
			'CLEAR' => 'xi-sun',
			'SUNNY' => 'xi-sun',
			'MOSTLY_CLEAR' => 'xi-sun-1',
			'PARTLY_CLOUDY' => 'xi-cloud-sun',
			'MOSTLY_CLOUDY' => 'xi-cloud',
			'CLOUDY' => 'xi-cloud',
			'RAIN' => 'xi-rain',
			'THUNDERSTORM' => 'xi-thunder',
			'THUNDERSTORMS' => 'xi-thunder',
			'SNOW' => 'xi-snow',
			'HAIL' => 'xi-snow',
			'FREEZING_RAIN' => 'xi-rain',
			'FOG' => 'xi-cloud-fog',
			'MIST' => 'xi-cloud-fog',
			'HAZE' => 'xi-cloud-fog',
		);
		
		return isset( $icon_classes[ $condition_type ] ) ? $icon_classes[ $condition_type ] : 'xi-cloud';
	}
	/**
	 * Render Layout 1: Minimal Horizontal
	 */
	private function render_layout_1( $current, $forecast_items, $settings, $temp_unit ) {
		?>
		<div class="weather-layout-1">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['widget_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['widget_title_tag'] ); ?> class="weather-widget-title">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php echo esc_attr( $settings['widget_title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>
				<div class="layout1-current">
					<div class="layout1-temp-section">

						<?php if ( 'yes' === $settings['show_temperature'] ) : ?>
							<div class="weather-temp">
								<?php echo esc_html( $this->convert_temperature( $current['temperature']['degrees'], $temp_unit ) ); ?>°<?php echo esc_html( $temp_unit ); ?>
							</div>
						<?php endif; ?>

						<div class="weather-desc">
							<?php
							if ( isset( $current['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
								$icon_url = $current['weatherCondition']['iconBaseUri'];

								if ( isset( $current['isDaytime'] ) && $current['isDaytime'] ) {
									$icon_url = rtrim( $icon_url, '/' ) . '_day.svg';
								} else {
									$icon_url = rtrim( $icon_url, '/' ) . '_night.svg';
								}

								if ( 'custom' === $settings['icon_source'] ) {
									$condition_type = isset( $current['weatherCondition']['type'] ) ? $current['weatherCondition']['type'] : '';
									$icon_url = $this->get_forecast_icon( $condition_type, $current['isDaytime'], $settings );

									if ( ! $icon_url ) {
										$icon_url = $current['weatherCondition']['iconBaseUri'] . ( $current['isDaytime'] ? '_day.svg' : '_night.svg' );
									}
								}
								?>
								<img
									src="<?php echo esc_url( $icon_url ); ?>"
									alt="<?php echo esc_attr( $current['weatherCondition']['description']['text'] ?? 'Weather icon' ); ?>"
									class="weather-icon"
								>
							<?php endif; ?>

							<span class="weather-desc-text">
								<?php echo esc_html( $current['weatherCondition']['description']['text'] ?? '' ); ?>
							</span>
						</div>
					</div>

					<div class="layout1-details">

						<?php if ( 'yes' === $settings['show_feels_like'] ) : ?>
							<div class="detail-item">
								Feels like <?php echo esc_html( $this->convert_temperature( $current['feelsLikeTemperature']['degrees'], $temp_unit ) ); ?>°
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_humidity'] ) : ?>
							<div class="detail-item">
								💧 <?php echo esc_html( $current['relativeHumidity'] ); ?>%
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_wind'] ) : ?>
							<div class="detail-item">
								💨 <?php echo esc_html( $current['wind']['speed']['value'] ); ?> km/h
							</div>
						<?php endif; ?>

					</div>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_forecast'] ) : ?>
				<div class="layout1-forecast">
					<?php
					$forecast_display = array_slice( $forecast_items, 0, 8 );

					foreach ( $forecast_display as $item ) :
						if ( $item['interval']['startTime'] === $current['interval']['startTime'] ) {
							continue;
						}
						?>
						<div class="forecast-hour">

							<div class="forecast-time">
								<?php echo esc_html( $this->format_time( $item['displayDateTime'] ) ); ?>
							</div>

							<?php
							if ( isset( $item['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
								$forecast_icon_url = $item['weatherCondition']['iconBaseUri'];
								$is_daytime        = isset( $item['isDaytime'] ) ? $item['isDaytime'] : true;

								if ( 'custom' === $settings['icon_source'] ) {
									$condition_type    = isset( $item['weatherCondition']['type'] ) ? $item['weatherCondition']['type'] : '';
									$forecast_icon_url = $this->get_forecast_icon( $condition_type, $is_daytime, $settings );

									if ( ! $forecast_icon_url ) {
										$forecast_icon_url = $item['weatherCondition']['iconBaseUri'] . ( $is_daytime ? '_day.svg' : '_night.svg' );
									}
								} else {
									$forecast_icon_url = rtrim( $forecast_icon_url, '/' ) . ( $is_daytime ? '_day.svg' : '_night.svg' );
								}
								?>
								<img
									src="<?php echo esc_url( $forecast_icon_url ); ?>"
									alt="Weather icon"
									class="weather-forecast-icon"
								>
							<?php endif; ?>

							<div class="forecast-temp">
								<?php echo esc_html( $this->convert_temperature( $item['temperature']['degrees'], $temp_unit ) ); ?>°
							</div>

						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Render Layout 2: Detailed Card
	 */
	private function render_layout_2( $current, $forecast_items, $settings, $temp_unit ) {
		?>
		<div class="weather-layout-2">

			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['widget_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['widget_title_tag'] ); ?> class="weather-widget-title" style="color: white;">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php echo esc_attr( $settings['widget_title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>

				<div class="layout2-header">

					<div class="layout2-location">
						<h3><?php echo esc_html( $settings['location_name'] ); ?></h3>
						<div class="current-time">
							<?php echo esc_html( $this->format_time( $current['displayDateTime'] ) ); ?>
						</div>
					</div>

					<div class="layout2-temp-large">

						<?php if ( 'yes' === $settings['show_temperature'] ) : ?>
							<div class="weather-temp">
								<?php echo esc_html( $this->convert_temperature( $current['temperature']['degrees'], $temp_unit ) ); ?>°<?php echo esc_html( $temp_unit ); ?>
							</div>
						<?php endif; ?>

						<div class="weather-condition">
							<?php echo esc_html( $current['weatherCondition']['description']['text'] ?? '' ); ?>
						</div>

						<?php if ( 'yes' === $settings['show_feels_like'] ) : ?>
							<div class="feels-like">
								Feels like <?php echo esc_html( $this->convert_temperature( $current['feelsLikeTemperature']['degrees'], $temp_unit ) ); ?>°
							</div>
						<?php endif; ?>

					</div>

					<?php
					if ( isset( $current['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
						$icon_url = $current['weatherCondition']['iconBaseUri'];

						if ( isset( $current['isDaytime'] ) && $current['isDaytime'] ) {
							$icon_url = rtrim( $icon_url, '/' ) . '_day.svg';
						} else {
							$icon_url = rtrim( $icon_url, '/' ) . '_night.svg';
						}

						if ( 'custom' === $settings['icon_source'] ) {
							$condition_type = isset( $current['weatherCondition']['type'] ) ? $current['weatherCondition']['type'] : '';
							$icon_url       = $this->get_forecast_icon( $condition_type, $current['isDaytime'], $settings );

							if ( ! $icon_url ) {
								$icon_url = $current['weatherCondition']['iconBaseUri'] . ( $current['isDaytime'] ? '_day.svg' : '_night.svg' );
							}
						}
						?>
						<img
							src="<?php echo esc_url( $icon_url ); ?>"
							alt="<?php echo esc_attr( $current['weatherCondition']['description']['text'] ?? 'Weather icon' ); ?>"
							class="weather-icon-large"
						>
					<?php endif; ?>

				</div>

				<div class="layout2-details-grid">

					<?php if ( 'yes' === $settings['show_humidity'] ) : ?>
						<div class="detail-card">
							<span class="detail-label">Humidity</span>
							<span class="detail-value">
								<?php echo esc_html( $current['relativeHumidity'] ); ?>%
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_wind'] ) : ?>
						<div class="detail-card">
							<span class="detail-label">Wind</span>
							<span class="detail-value">
								<?php echo esc_html( $current['wind']['speed']['value'] ); ?> km/h
								<?php echo esc_html( $this->get_wind_arrow( $current['wind']['direction']['degrees'] ) ); ?>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_visibility'] && isset( $current['visibility']['distance'] ) ) : ?>
						<div class="detail-card">
							<span class="detail-label">Visibility</span>
							<span class="detail-value">
								<?php echo esc_html( $current['visibility']['distance'] ); ?> km
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_pressure'] && isset( $current['airPressure']['meanSeaLevelMillibars'] ) ) : ?>
						<div class="detail-card">
							<span class="detail-label">Pressure</span>
							<span class="detail-value">
								<?php echo esc_html( $current['airPressure']['meanSeaLevelMillibars'] ); ?> mb
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_dew_point'] && isset( $current['dewPoint']['degrees'] ) ) : ?>
						<div class="detail-card">
							<span class="detail-label">Dew point</span>
							<span class="detail-value">
								<?php echo esc_html( $this->convert_temperature( $current['dewPoint']['degrees'], $temp_unit ) ); ?>°
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_uv_index'] && isset( $current['uvIndex'] ) ) : ?>
						<div class="detail-card">
							<span class="detail-label">UV Index</span>
							<span class="detail-value">
								<?php echo esc_html( $current['uvIndex'] ); ?>
							</span>
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_forecast'] ) : ?>

				<div class="layout2-forecast">

					<h4><?php echo esc_html( $settings['forecast_title'] ); ?></h4>

					<div class="forecast-scroll">

						<?php
						$forecast_display = array_slice( $forecast_items, 1, 12 );

						foreach ( $forecast_display as $item ) :
							?>

							<div class="forecast-item">

								<div class="forecast-time">
									<?php echo esc_html( $this->format_time( $item['displayDateTime'] ) ); ?>
								</div>

								<?php
								if ( isset( $item['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
									$forecast_icon_url = $item['weatherCondition']['iconBaseUri'];
									$is_daytime        = isset( $item['isDaytime'] ) ? $item['isDaytime'] : true;

									if ( 'custom' === $settings['icon_source'] ) {
										$condition_type    = isset( $item['weatherCondition']['type'] ) ? $item['weatherCondition']['type'] : '';
										$forecast_icon_url = $this->get_forecast_icon( $condition_type, $is_daytime, $settings );

										if ( ! $forecast_icon_url ) {
											$forecast_icon_url = $item['weatherCondition']['iconBaseUri'] . ( $is_daytime ? '_day.svg' : '_night.svg' );
										}
									} else {
										$forecast_icon_url = rtrim( $forecast_icon_url, '/' ) . ( $is_daytime ? '_day.svg' : '_night.svg' );
									}
									?>
									<img
										src="<?php echo esc_url( $forecast_icon_url ); ?>"
										alt="Weather icon"
										class="weather-forecast-icon"
									>
								<?php endif; ?>

								<div class="forecast-temp">
									<?php echo esc_html( $this->convert_temperature( $item['temperature']['degrees'], $temp_unit ) ); ?>°
								</div>

								<div class="forecast-desc">
									<?php echo esc_html( substr( $item['weatherCondition']['description']['text'] ?? '', 0, 10 ) ); ?>
								</div>

							</div>

						<?php endforeach; ?>

					</div>
				</div>

			<?php endif; ?>

		</div>
		<?php
	}

	
	/**
	 * Render Layout 3: Modern Vertical
	 */
	private function render_layout_3( $current, $forecast_items, $settings, $temp_unit ) {
		?>
		<div class="weather-layout-3">

			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['widget_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['widget_title_tag'] ); ?> class="weather-widget-title">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php echo esc_attr( $settings['widget_title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>

				<div class="layout3-current">

					<div class="layout3-main">

						<?php if ( 'yes' === $settings['show_temperature'] ) : ?>
							<div class="weather-temp">
								<?php echo esc_html( $this->convert_temperature( $current['temperature']['degrees'], $temp_unit ) ); ?>°<?php echo esc_html( $temp_unit ); ?>
							</div>
						<?php endif; ?>

						<?php
						if ( isset( $current['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
							$icon_url = $current['weatherCondition']['iconBaseUri'];

							if ( isset( $current['isDaytime'] ) && $current['isDaytime'] ) {
								$icon_url = rtrim( $icon_url, '/' ) . '_day.svg';
							} else {
								$icon_url = rtrim( $icon_url, '/' ) . '_night.svg';
							}

							if ( 'custom' === $settings['icon_source'] ) {
								$condition_type = isset( $current['weatherCondition']['type'] ) ? $current['weatherCondition']['type'] : '';
								$icon_url       = $this->get_forecast_icon( $condition_type, $current['isDaytime'], $settings );

								if ( ! $icon_url ) {
									$icon_url = $current['weatherCondition']['iconBaseUri'] . ( $current['isDaytime'] ? '_day.svg' : '_night.svg' );
								}
							}
							?>
							<img
								src="<?php echo esc_url( $icon_url ); ?>"
								alt="<?php echo esc_attr( $current['weatherCondition']['description']['text'] ?? 'Weather icon' ); ?>"
								class="weather-icon"
							>
						<?php endif; ?>

					</div>

					<div class="layout3-condition">

						<div class="weather-desc-text">
							<?php echo esc_html( $current['weatherCondition']['description']['text'] ?? '' ); ?>
						</div>

						<?php if ( 'yes' === $settings['show_feels_like'] ) : ?>
							<div class="feels-like">
								Feels like <?php echo esc_html( $this->convert_temperature( $current['feelsLikeTemperature']['degrees'], $temp_unit ) ); ?>°
							</div>
						<?php endif; ?>

					</div>

				</div>

				<div class="layout3-details">

					<?php if ( 'yes' === $settings['show_humidity'] ) : ?>
						<div class="detail-item">
							<span class="detail-icon">💧</span>
							<span>
								<?php echo esc_html( $current['relativeHumidity'] ); ?>% Humidity
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_wind'] ) : ?>
						<div class="detail-item">
							<span class="detail-icon">💨</span>
							<span>
								<?php echo esc_html( $current['wind']['speed']['value'] ); ?> km/h Wind
							</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_pressure'] && isset( $current['airPressure']['meanSeaLevelMillibars'] ) ) : ?>
						<div class="detail-item">
							<span class="detail-icon">📊</span>
							<span>
								<?php echo esc_html( $current['airPressure']['meanSeaLevelMillibars'] ); ?> mb Pressure
							</span>
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_forecast'] ) : ?>

				<div class="layout3-forecast">

					<h4>
						<?php echo esc_html( $settings['forecast_title'] ); ?>
					</h4>

					<div class="forecast-grid">

						<?php
						$forecast_display = array_slice( $forecast_items, 1, 6 );

						foreach ( $forecast_display as $item ) :
							?>

							<div class="forecast-card">

								<div class="forecast-time">
									<?php echo esc_html( $this->format_time( $item['displayDateTime'] ) ); ?>
								</div>

								<?php
								if ( isset( $item['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
									$forecast_icon_url = $item['weatherCondition']['iconBaseUri'];
									$is_daytime        = isset( $item['isDaytime'] ) ? $item['isDaytime'] : true;

									if ( 'custom' === $settings['icon_source'] ) {
										$condition_type    = isset( $item['weatherCondition']['type'] ) ? $item['weatherCondition']['type'] : '';
										$forecast_icon_url = $this->get_forecast_icon( $condition_type, $is_daytime, $settings );

										if ( ! $forecast_icon_url ) {
											$forecast_icon_url = $item['weatherCondition']['iconBaseUri'] . ( $is_daytime ? '_day.svg' : '_night.svg' );
										}
									} else {
										$forecast_icon_url = rtrim( $forecast_icon_url, '/' ) . ( $is_daytime ? '_day.svg' : '_night.svg' );
									}
									?>

									<img
										src="<?php echo esc_url( $forecast_icon_url ); ?>"
										alt="Weather icon"
										class="weather-forecast-icon"
									>

								<?php endif; ?>

								<div class="forecast-temp">
									<?php echo esc_html( $this->convert_temperature( $item['temperature']['degrees'], $temp_unit ) ); ?>°
								</div>

								<div class="forecast-condition">
									<?php echo esc_html( substr( $item['weatherCondition']['description']['text'] ?? '', 0, 8 ) ); ?>
								</div>

							</div>

						<?php endforeach; ?>

					</div>

				</div>

			<?php endif; ?>

		</div>
		<?php
	}


	/**
	 * Render Layout 4: Forecast Focus
	 */
	private function render_layout_4( $current, $forecast_items, $settings, $temp_unit ) {
		?>
		<div class="weather-layout-4">

			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['widget_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['widget_title_tag'] ); ?> class="weather-widget-title" style="color: white;">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php echo esc_attr( $settings['widget_title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>

				<div class="layout4-current-bar">

					<div class="current-info">

						<?php if ( 'yes' === $settings['show_temperature'] ) : ?>
							<div class="current-temp">
								<?php echo esc_html( $this->convert_temperature( $current['temperature']['degrees'], $temp_unit ) ); ?>°
							</div>
						<?php endif; ?>

						<div class="current-condition">
							<?php echo esc_html( $current['weatherCondition']['description']['text'] ?? '' ); ?>
						</div>

					</div>

					<?php if ( 'yes' === $settings['show_feels_like'] ) : ?>
						<div class="feels-like">
							Feels like <?php echo esc_html( $this->convert_temperature( $current['feelsLikeTemperature']['degrees'], $temp_unit ) ); ?>°
						</div>
					<?php endif; ?>

					<?php
					if ( isset( $current['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
						$icon_url = $current['weatherCondition']['iconBaseUri'];

						if ( isset( $current['isDaytime'] ) && $current['isDaytime'] ) {
							$icon_url = rtrim( $icon_url, '/' ) . '_day.svg';
						} else {
							$icon_url = rtrim( $icon_url, '/' ) . '_night.svg';
						}

						if ( 'custom' === $settings['icon_source'] ) {
							$condition_type = isset( $current['weatherCondition']['type'] ) ? $current['weatherCondition']['type'] : '';
							$icon_url       = $this->get_forecast_icon( $condition_type, $current['isDaytime'], $settings );

							if ( ! $icon_url ) {
								$icon_url = $current['weatherCondition']['iconBaseUri'] . ( $current['isDaytime'] ? '_day.svg' : '_night.svg' );
							}
						}
						?>
						<img
							src="<?php echo esc_url( $icon_url ); ?>"
							alt="<?php echo esc_attr( $current['weatherCondition']['description']['text'] ?? 'Weather icon' ); ?>"
							class="weather-icon"
							style="filter: brightness(0) invert(1);"
						>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_forecast'] ) : ?>

				<div class="layout4-forecast">

					<h4>
						<?php echo esc_html( $settings['forecast_title'] ); ?>
					</h4>

					<div class="forecast-timeline">

						<?php
						$forecast_display = array_slice( $forecast_items, 1, 24 );

						foreach ( $forecast_display as $index => $item ) :

							if ( $index % 2 == 0 ) :
								?>

								<div class="timeline-hour">

									<div class="hour-time">
										<?php echo esc_html( $this->format_time( $item['displayDateTime'] ) ); ?>
									</div>

									<?php
									if ( isset( $item['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
										$forecast_icon_url = $item['weatherCondition']['iconBaseUri'];
										$is_daytime        = isset( $item['isDaytime'] ) ? $item['isDaytime'] : true;

										if ( 'custom' === $settings['icon_source'] ) {
											$condition_type    = isset( $item['weatherCondition']['type'] ) ? $item['weatherCondition']['type'] : '';
											$forecast_icon_url = $this->get_forecast_icon( $condition_type, $is_daytime, $settings );

											if ( ! $forecast_icon_url ) {
												$forecast_icon_url = $item['weatherCondition']['iconBaseUri'] . ( $is_daytime ? '_day.svg' : '_night.svg' );
											}
										} else {
											$forecast_icon_url = rtrim( $forecast_icon_url, '/' ) . ( $is_daytime ? '_day.svg' : '_night.svg' );
										}
										?>

										<img
											src="<?php echo esc_url( $forecast_icon_url ); ?>"
											alt="Weather icon"
											class="weather-forecast-icon"
											style="filter: brightness(0) invert(1);"
										>

									<?php endif; ?>

									<div class="hour-temp">
										<?php echo esc_html( $this->convert_temperature( $item['temperature']['degrees'], $temp_unit ) ); ?>°
									</div>

									<div class="hour-condition">
										<?php echo esc_html( substr( $item['weatherCondition']['description']['text'] ?? '', 0, 5 ) ); ?>
									</div>

								</div>

							<?php
							endif;

						endforeach;
						?>

					</div>

				</div>

			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>

				<div class="layout4-highlights">

					<?php if ( 'yes' === $settings['show_humidity'] ) : ?>
						<div class="highlight" style="color: white;">
							💧 <?php echo esc_html( $current['relativeHumidity'] ); ?>%
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_wind'] ) : ?>
						<div class="highlight" style="color: white;">
							💨 <?php echo esc_html( $current['wind']['speed']['value'] ); ?> km/h
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_visibility'] && isset( $current['visibility']['distance'] ) ) : ?>
						<div class="highlight" style="color: white;">
							👁️ <?php echo esc_html( $current['visibility']['distance'] ); ?> km
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

		</div>
		<?php
	}


	/**
	 * Render Layout 5: Compact Grid
	 */
	private function render_layout_5( $current, $forecast_items, $settings, $temp_unit ) {
		?>
		<div class="weather-layout-5">

			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['widget_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['widget_title_tag'] ); ?> class="weather-widget-title">
					<?php echo esc_html( $settings['widget_title'] ); ?>
				</<?php echo esc_attr( $settings['widget_title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_current_weather'] ) : ?>

				<div class="layout5-current">

					<?php if ( 'yes' === $settings['show_temperature'] ) : ?>
						<div class="weather-temp">
							<?php echo esc_html( $this->convert_temperature( $current['temperature']['degrees'], $temp_unit ) ); ?>°<?php echo esc_html( $temp_unit ); ?>
						</div>
					<?php endif; ?>

					<?php
					if ( isset( $current['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
						$icon_url = $current['weatherCondition']['iconBaseUri'];

						if ( isset( $current['isDaytime'] ) && $current['isDaytime'] ) {
							$icon_url = rtrim( $icon_url, '/' ) . '_day.svg';
						} else {
							$icon_url = rtrim( $icon_url, '/' ) . '_night.svg';
						}

						if ( 'custom' === $settings['icon_source'] ) {
							$condition_type = isset( $current['weatherCondition']['type'] ) ? $current['weatherCondition']['type'] : '';
							$icon_url       = $this->get_forecast_icon( $condition_type, $current['isDaytime'], $settings );

							if ( ! $icon_url ) {
								$icon_url = $current['weatherCondition']['iconBaseUri'] . ( $current['isDaytime'] ? '_day.svg' : '_night.svg' );
							}
						}
						?>
						<img
							src="<?php echo esc_url( $icon_url ); ?>"
							alt="<?php echo esc_attr( $current['weatherCondition']['description']['text'] ?? 'Weather icon' ); ?>"
							class="weather-icon"
						>
					<?php endif; ?>

					<div class="weather-desc-text">
						<?php echo esc_html( $current['weatherCondition']['description']['text'] ?? '' ); ?>
					</div>

				</div>

				<div class="layout5-stats">

					<?php if ( 'yes' === $settings['show_humidity'] ) : ?>
						<div class="stat">
							<span class="stat-label">Humidity</span>
							<span><?php echo esc_html( $current['relativeHumidity'] ); ?>%</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_wind'] ) : ?>
						<div class="stat">
							<span class="stat-label">Wind</span>
							<span><?php echo esc_html( $current['wind']['speed']['value'] ); ?> km/h</span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_pressure'] ) : ?>
						<div class="stat">
							<span class="stat-label">Pressure</span>
							<span><?php echo esc_html( $current['airPressure']['meanSeaLevelMillibars'] ); ?> mb</span>
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_forecast'] ) : ?>

				<div class="layout5-forecast">

					<?php
					$forecast_display = array_slice( $forecast_items, 1, 8 );

					foreach ( $forecast_display as $item ) :
						?>

						<div class="forecast-block">

							<div class="block-time">
								<?php echo esc_html( $this->format_time( $item['displayDateTime'] ) ); ?>
							</div>

							<?php
							if ( isset( $item['weatherCondition']['iconBaseUri'] ) && 'none' !== $settings['icon_source'] ) :
								$forecast_icon_url = $item['weatherCondition']['iconBaseUri'];
								$is_daytime        = isset( $item['isDaytime'] ) ? $item['isDaytime'] : true;

								if ( 'custom' === $settings['icon_source'] ) {
									$condition_type    = isset( $item['weatherCondition']['type'] ) ? $item['weatherCondition']['type'] : '';
									$forecast_icon_url = $this->get_forecast_icon( $condition_type, $is_daytime, $settings );

									if ( ! $forecast_icon_url ) {
										$forecast_icon_url = $item['weatherCondition']['iconBaseUri'] . ( $is_daytime ? '_day.svg' : '_night.svg' );
									}
								} else {
									$forecast_icon_url = rtrim( $forecast_icon_url, '/' ) . ( $is_daytime ? '_day.svg' : '_night.svg' );
								}
								?>
								<img
									src="<?php echo esc_url( $forecast_icon_url ); ?>"
									alt="Weather icon"
									class="weather-forecast-icon"
								>
							<?php endif; ?>

							<div class="block-temp">
								<?php echo esc_html( $this->convert_temperature( $item['temperature']['degrees'], $temp_unit ) ); ?>°
							</div>

						</div>

					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Get weather icon for forecast items
	 */
	private function get_forecast_icon( $condition_type, $is_daytime = true, $settings = array() ) {
		$icon_source = isset( $settings['icon_source'] ) ? $settings['icon_source'] : 'api';
		
		if ( 'none' === $icon_source ) {
			return '';
		}
		
		if ( 'custom' === $icon_source ) {
			$icon_map = array(
				'CLEAR'          => 'custom_sunny_icon',
				'SUNNY'          => 'custom_sunny_icon',
				'MOSTLY_CLEAR'   => 'custom_sunny_icon',
				'PARTLY_CLOUDY'  => 'custom_partly_cloudy_icon',
				'MOSTLY_CLOUDY'  => 'custom_cloudy_icon',
				'CLOUDY'         => 'custom_cloudy_icon',
				'RAIN'           => 'custom_rainy_icon',
				'THUNDERSTORM'   => 'custom_storm_icon',
				'THUNDERSTORMS'  => 'custom_storm_icon',
				'SNOW'           => 'custom_snow_icon',
				'HAIL'           => 'custom_snow_icon',
				'FREEZING_RAIN'  => 'custom_rainy_icon',
				'FOG'            => 'custom_cloudy_icon',
				'MIST'           => 'custom_cloudy_icon',
				'HAZE'           => 'custom_cloudy_icon',
			);
			
			$icon_key = isset( $icon_map[ $condition_type ] ) ? $icon_map[ $condition_type ] : 'custom_sunny_icon';
			
			if ( isset( $settings[ $icon_key ]['url'] ) && ! empty( $settings[ $icon_key ]['url'] ) ) {
				return esc_url( $settings[ $icon_key ]['url'] );
			}
		}
		
		// For API icons, we'll return the base URI for forecast items
		return null;
	}

	/**
	 * Render weather widget
	 */
	public function render() {

		$settings = $this->get_settings_for_display();
		$data = $this->get_weather_data();
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'weather/layout/frontend.php';

	}
}