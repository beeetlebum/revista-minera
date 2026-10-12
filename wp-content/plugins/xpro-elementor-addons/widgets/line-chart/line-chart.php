<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
	exit; 
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Line_Chart extends Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name()
	{
		return 'xpro-line-chart';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title()
	{
		return __('Line Chart', 'xpro-elementor-addons');
	}

	/**
	 * Get widget inner wrapper.
	 *
	 */
	public function has_widget_inner_wrapper(): bool
	{
		$has_wrapper = !Plugin::$instance->experiments->is_feature_active('e_optimized_markup');
		return $has_wrapper;
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon()
	{
		return 'xi-chart xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories()
	{
		return array('xpro-widgets');
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords()
	{
		return array('line', 'chart', 'graph', 'data', 'visualization', 'trend');
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends()
	{
		return array('xpro-chartjs');
	}

	protected function is_dynamic_content(): bool
	{
		return false;
	}
	
	/**s
	 * Register Controls
	 */
	protected function register_controls()
	{
		// Chart Data Section
		$this->start_controls_section(
			'xpro_section_chart',
			array(
				'label' => __('Line Chart', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'xpro_chart_position',
			array(
				'label' => __('Orientation', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __('Horizontal Lines', 'xpro-elementor-addons'),
					'vertical'   => __('Vertical Lines', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_labels',
			array(
				'label' => __('Labels', 'xpro-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('January, February, March, April, May', 'xpro-elementor-addons'),
				'description' => __('Write multiple labels separated by commas (,). Example: Jan, Feb, Mar', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater = new Repeater();

		$xpro_repeater->start_controls_tabs('xpro_line_tabs');

		$xpro_repeater->start_controls_tab(
			'xpro_line_tab_content',
			array(
				'label' => __('Content', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->add_control(
			'xpro_label',
			array(
				'label' => __('Label', 'xpro-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$xpro_repeater->add_control(
			'xpro_data',
			array(
				'label' => __('Data', 'xpro-elementor-addons'),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'description' => __('Write data values separated by commas (,). Example: 4, 2, 6, 8, 5', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->add_control(
			'xpro_order',
			array(
				'label' => __('Drawing Order', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 100,
				'description' => __('The drawing order of dataset. Also affects order for tooltip and legend.', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->end_controls_tab();

		$xpro_repeater->start_controls_tab(
			'xpro_line_tab_style',
			array(
				'label' => __('Style', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->add_control(
			'xpro_background_color',
			array(
				'label' => __('Fill Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'description' => __('Color for area under the line (if fill is enabled)', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->add_control(
			'xpro_border_color',
			array(
				'label' => __('Line Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
			)
		);

		$xpro_repeater->add_control(
			'xpro_border_width',
			array(
				'label' => __('Line Width', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 0,
				'max' => 20,
			)
		);

		$xpro_repeater->add_control(
			'xpro_border_dash',
			array(
				'label' => __('Border Dash', 'xpro-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '5, 5',
				'description' => __('Comma separated values for dash length and spacing.', 'xpro-elementor-addons'),
			)
		);

		$xpro_repeater->add_control(
			'xpro_point_background_color',
			array(
				'label' => __('Point Background Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
			)
		);

		$xpro_repeater->add_control(
			'xpro_point_border_color',
			array(
				'label' => __('Point Border Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
			)
		);

		$xpro_repeater->add_control(
			'xpro_point_radius',
			array(
				'label' => __('Point Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'max' => 50,
			)
		);

		$xpro_repeater->add_control(
			'xpro_point_hover_radius',
			array(
				'label' => __('Point Hover Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 0,
				'max' => 50,
			)
		);

		$xpro_repeater->add_control(
			'xpro_point_style',
			array(
				'label' => __('Point Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle' => __('Circle', 'xpro-elementor-addons'),
					'rect' => __('Rectangle', 'xpro-elementor-addons'),
					'rectRounded' => __('Rounded Rectangle', 'xpro-elementor-addons'),
					'rectRot' => __('Rotated Rectangle', 'xpro-elementor-addons'),
					'triangle' => __('Triangle', 'xpro-elementor-addons'),
				),
			)
		);

		$xpro_repeater->add_control(
			'xpro_tension',
			array(
				'label' => __('Line Tension', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					),
				),
				'default' => array(
					'size' => 0.4,
				),
			)
		);

		$xpro_repeater->end_controls_tab();

		$xpro_repeater->end_controls_tabs();

		$this->add_control(
			'xpro_chart_data',
			array(
				'type' => Controls_Manager::REPEATER,
				'fields' => $xpro_repeater->get_controls(),
				'title_field' => '{{{ xpro_label }}}',
				'default' => array(
					array(
						'xpro_label' => __('Xpro Elementor Addons', 'xpro-elementor-addons'),
						'xpro_data' => __('2, 4, 5, 7, 6', 'xpro-elementor-addons'),
						'xpro_border_color' => '#562dd4',
						'xpro_background_color' => 'rgba(86, 45, 212, 0.1)',
						'xpro_point_background_color' => '#562dd4',
						'xpro_point_border_color' => '#ffffff',
					),
					array(
						'xpro_label' => __('Xpro Elementor Addons Pro', 'xpro-elementor-addons'),
						'xpro_data' => __('1, 6, 8, 5, 9', 'xpro-elementor-addons'),
						'xpro_border_color' => '#e2498a',
						'xpro_background_color' => 'rgba(226, 73, 138, 0.1)',
						'xpro_point_background_color' => '#e2498a',
						'xpro_point_border_color' => '#ffffff',
					),
					array(
						'xpro_label' => __('Xpro Theme Builder', 'xpro-elementor-addons'),
						'xpro_data' => __('1, 4, 9, 7, 2', 'xpro-elementor-addons'),
						'xpro_border_color' => '#12c479',
						'xpro_background_color' => 'rgba(18, 196, 121, 0.1)',
						'xpro_point_background_color' => '#12c479',
						'xpro_point_border_color' => '#ffffff',
					),
				),
			)
		);

		$this->end_controls_section();

		// Settings Section
		$this->start_controls_section(
			'xpro_settings',
			array(
				'label' => __('Settings', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'xpro_chart_height',
			array(
				'label' => __('Chart Height', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 50,
						'max' => 1500,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-chart-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'xpro_fill_area',
			array(
				'label' => __('Fill Area Under Lines', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'description' => __('Enable to fill the area under the line charts', 'xpro-elementor-addons'),
			)
		);

		$this->add_control(
			'xpro_show_points',
			array(
				'label' => __('Show Data Points', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_stepped_line',
			array(
				'label' => __('Stepped Line', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => array(
					'false'  => __('Off', 'xpro-elementor-addons'),
					'before' => __('Step Before', 'xpro-elementor-addons'),
					'after'  => __('Step After', 'xpro-elementor-addons'),
					'middle' => __('Step Middle', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_xaxes_grid_display',
			array(
				'label' => __('X Axes Grid Lines', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_yaxes_grid_display',
			array(
				'label' => __('Y Axes Grid Lines', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_xaxes_labels_display',
			array(
				'label' => __('Show X Axes Labels', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_yaxes_labels_display',
			array(
				'label' => __('Show Y Axes Labels', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_tooltip_display',
			array(
				'label' => __('Show Tooltips', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_title_display',
			array(
				'label' => __('Show Title', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_chart_title',
			array(
				'label' => __('Title', 'xpro-elementor-addons'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __('Xpro Line Chart', 'xpro-elementor-addons'),
				'condition' => array(
					'xpro_title_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_axis_range',
			array(
				'label' => __('Scale Axis Range', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'description' => __('Maximum number for the scale. Leave empty for auto.', 'xpro-elementor-addons'),
			)
		);

		$this->add_control(
			'xpro_step_size',
			array(
				'label' => __('Step Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'step' => 1,
				'description' => __('Step size for the scale. Leave empty for auto.', 'xpro-elementor-addons'),
			)
		);

		$this->add_control(
			'xpro_begin_zero',
			array(
				'label' => __('Begin At Zero', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_chart_stacked',
			array(
				'label' => __('Stacked Area Chart', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'description' => __('Stack the areas on top of each other', 'xpro-elementor-addons'),
			)
		);

		$this->end_controls_section();

		// Legend Section
		$this->start_controls_section(
			'xpro_section_legend',
			array(
				'label' => __('Legend', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'xpro_legend_display',
			array(
				'label' => __('Show Legend', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'xpro_legend_position',
			array(
				'label' => __('Position', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top' => __('Top', 'xpro-elementor-addons'),
					'left' => __('Left', 'xpro-elementor-addons'),
					'bottom' => __('Bottom', 'xpro-elementor-addons'),
					'right' => __('Right', 'xpro-elementor-addons'),
				),
				'condition' => array(
					'xpro_legend_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_legend_point_style',
			array(
				'label' => __('Legend Point Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle' => __('Circle', 'xpro-elementor-addons'),
					// 'cross' => __('Cross', 'xpro-elementor-addons'),
					// 'crossRot' => __('CrossRot', 'xpro-elementor-addons'),
					// 'dash' => __('Dash', 'xpro-elementor-addons'),
					// 'line' => __('Line', 'xpro-elementor-addons'),
					'rect' => __('Rect', 'xpro-elementor-addons'),
					'rectRounded' => __('RectRounded', 'xpro-elementor-addons'),
					'rectRot' => __('RectRot', 'xpro-elementor-addons'),
					// 'star' => __('Star', 'xpro-elementor-addons'),
					'triangle' => __('Triangle', 'xpro-elementor-addons'),
				),
				'condition' => array(
					'xpro_legend_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_legend_reverse',
			array(
				'label' => __('Reverse', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => array(
					'xpro_legend_display' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Animation Section
		$this->start_controls_section(
			'xpro_section_animation',
			array(
				'label' => __('Animation', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'xpro_chart_animation_duration',
			array(
				'label' => __('Duration (ms)', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
			)
		);

		$this->add_control(
			'xpro_animation_options',
			array(
				'label' => __('Easing', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'linear',
				'options' => array(
					'linear' => __('Linear', 'xpro-elementor-addons'),
					'easeInCubic' => __('Ease In Cubic', 'xpro-elementor-addons'),
					'easeOutCubic' => __('Ease Out Cubic', 'xpro-elementor-addons'),
					'easeInOutCubic' => __('Ease In Out Cubic', 'xpro-elementor-addons'),
					'easeInCirc' => __('Ease In Circ', 'xpro-elementor-addons'),
					'easeOutCirc' => __('Ease Out Circ', 'xpro-elementor-addons'),
					'easeInBounce' => __('Ease In Bounce', 'xpro-elementor-addons'),
					'easeOutBounce' => __('Ease Out Bounce', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_enable_animation_loop',
			array(
				'label' => __('Enable Loop', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		// Style Section - Canvas
		$this->start_controls_section(
			'xpro_section_style_canvas',
			array(
				'label' => __('Canvas', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'xpro_chart_width',
			array(
				'label' => __('Width', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array('px', '%'),
				'default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-chart-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'xpro_layout_padding',
			array(
				'label' => __('Chart Padding', 'xpro-elementor-addons'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
			)
		);
		$this->end_controls_section();

		// Style Section - Common
		$this->start_controls_section(
			'_section_style_common',
			array(
				'label' => __('Common', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'xpro_grid_color',
			array(
				'label' => __('Grid Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#e0e0e0',
			)
		);

		$this->end_controls_section();

		// Style Section - Title
		$this->start_controls_section(
			'_section_style_title',
			array(
				'label' => __('Title', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'xpro_title_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_title_font_size',
			array(
				'label' => __('Font Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 16,
				),
			)
		);

		$this->add_control(
			'xpro_title_font_family',
			array(
				'label' => __('Font Family', 'xpro-elementor-addons'),
				'type' => Controls_Manager::FONT,
				'default' => 'sans-serif',
			)
		);

		$this->add_control(
			'xpro_title_font_weight',
			array(
				'label' => __('Font Weight', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'bold' => __('Bold', 'xpro-elementor-addons'),
				),
				'default' => 'bold',
			)
		);

		$this->add_control(
			'xpro_title_font_style',
			array(
				'label' => __('Font Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'italic' => __('Italic', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_title_font_color',
			array(
				'label' => __('Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
			)
		);

		$this->end_controls_section();

		// Style Section - Legend
		$this->start_controls_section(
			'_section_style_legend',
			array(
				'label' => __('Legend', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'xpro_legend_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_legend_font_size',
			array(
				'label' => __('Legend Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'default' => array(
					'size' => 12,
				),
			)
		);

		$this->add_control(
			'xpro_legend_font_family',
			array(
				'label' => __('Font Family', 'xpro-elementor-addons'),
				'type' => Controls_Manager::FONT,
				'default' => 'sans-serif',
			)
		);

		$this->add_control(
			'xpro_legend_font_weight',
			array(
				'label' => __('Font Weight', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'bold' => __('Bold', 'xpro-elementor-addons'),
				),
				'default' => 'normal',
			)
		);

		$this->add_control(
			'xpro_legend_font_style',
			array(
				'label' => __('Font Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'italic' => __('Italic', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_legend_font_color',
			array(
				'label' => __('Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
			)
		);

		$this->end_controls_section();

		// Style Section - X Axes Labels
		$this->start_controls_section(
			'_section_style_xaxes_label',
			array(
				'label' => __('X Axes Labels', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'xpro_xaxes_labels_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_font_size',
			array(
				'label' => __('Font Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'default' => array(
					'size' => 12,
				),
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_font_family',
			array(
				'label' => __('Font Family', 'xpro-elementor-addons'),
				'type' => Controls_Manager::FONT,
				'default' => 'sans-serif',
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_font_weight',
			array(
				'label' => __('Font Weight', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'bold' => __('Bold', 'xpro-elementor-addons'),
				),
				'default' => 'normal',
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_font_style',
			array(
				'label' => __('Font Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'italic' => __('Italic', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_font_color',
			array(
				'label' => __('Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
			)
		);

		$this->add_control(
			'xpro_labels_xaxes_rotation',
			array(
				'label' => __('Label Rotation (degrees)', 'xpro-elementor-addons'),
				'type' => Controls_Manager::NUMBER,
				'min' => -90,
				'max' => 90,
				'default' => 0,
			)
		);

		$this->end_controls_section();

		// Style Section - Y Axes Labels
		$this->start_controls_section(
			'_section_style_yaxes_label',
			array(
				'label' => __('Y Axes Labels', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'xpro_yaxes_labels_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_labels_yaxes_font_size',
			array(
				'label' => __('Font Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'default' => array(
					'size' => 12,
				),
			)
		);

		$this->add_control(
			'xpro_labels_yaxes_font_family',
			array(
				'label' => __('Font Family', 'xpro-elementor-addons'),
				'type' => Controls_Manager::FONT,
				'default' => 'sans-serif',
			)
		);

		$this->add_control(
			'xpro_labels_yaxes_font_weight',
			array(
				'label' => __('Font Weight', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'bold' => __('Bold', 'xpro-elementor-addons'),
				),
				'default' => 'normal',
			)
		);

		$this->add_control(
			'xpro_labels_yaxes_font_style',
			array(
				'label' => __('Font Style', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __('Default', 'xpro-elementor-addons'),
					'normal' => __('Normal', 'xpro-elementor-addons'),
					'italic' => __('Italic', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_labels_yaxes_font_color',
			array(
				'label' => __('Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#666666',
			)
		);

		$this->end_controls_section();

		// Style Section - Tooltip
		$this->start_controls_section(
			'_section_style_tooltip',
			array(
				'label' => __('Tooltip', 'xpro-elementor-addons'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'xpro_tooltip_display' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_background_color',
			array(
				'label' => __('Background Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0, 0, 0, 0.8)',
			)
		);

		$this->add_control(
			'xpro_tooltip_border_color',
			array(
				'label' => __('Border Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$this->add_control(
			'xpro_tooltip_border_width',
			array(
				'label' => __('Border Width', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default' => array(
					'size' => 0,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_border_radius',
			array(
				'label' => __('Border Radius', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 6,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_padding',
			array(
				'label' => __('Padding', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 6,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_caret_size',
			array(
				'label' => __('Caret Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 5,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_mode',
			array(
				'label' => __('Mode', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'nearest',
				'options' => array(
					'nearest' => __('Nearest', 'xpro-elementor-addons'),
					'index' => __('Index', 'xpro-elementor-addons'),
					'x' => __('X', 'xpro-elementor-addons'),
					'y' => __('Y', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_colors_heading_title',
			array(
				'label' => __( 'Tooltip Title', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'xpro_tooltip_title_font_size',
			array(
				'label' => __('Title Font Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 14,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_title_font_color',
			array(
				'label' => __('Title Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$this->add_control(
			'xpro_tooltip_colors_heading',
			array(
				'label' => __( 'Tooltip Body', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'xpro_tooltip_body_font_size',
			array(
				'label' => __('Body Font Size', 'xpro-elementor-addons'),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 18,
					),
				),
				'default' => array(
					'size' => 12,
				),
			)
		);

		$this->add_control(
			'xpro_tooltip_body_font_color',
			array(
				'label' => __('Body Color', 'xpro-elementor-addons'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Widget
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// Parse labels
		$labels = array_map(
			'sanitize_text_field',
			array_map( 'trim', explode( ',', $settings['xpro_labels'] ) )
		);

		$datasets = array();

		foreach ($settings['xpro_chart_data'] as $dataset) {

	// Parse values
	$values = array_map('floatval', explode(',', $dataset['xpro_data']));

	// Build dataset configuration
	$dataset_config = array(
		'label' => $dataset['xpro_label'],
		'data'  => $values,
		'fill'  => ('yes' === $settings['xpro_fill_area']),
		'order' => (int) ($dataset['xpro_order'] ?? 0),
	);

	// Background Color
	if (!empty($dataset['xpro_background_color'])) {
		$dataset_config['backgroundColor'] = $dataset['xpro_background_color'];
	}

	// Border Color
	// Line Width (Dataset > Global)
	$dataset_config['borderWidth'] =
	(isset($dataset['xpro_border_width']) && $dataset['xpro_border_width'] !== '')
	? (float) $dataset['xpro_border_width']
	: (float) ($settings['xpro_line_border_width']['size'] ?? 4);

	// Border Dash
	if (!empty($dataset['xpro_border_dash'])) {
		$dataset_config['borderDash'] = array_map(
			'intval',
			explode(',', $dataset['xpro_border_dash'])
		);
	}

	// ===========================================
	// Point Styling
	// ===========================================

	if ('yes' === $settings['xpro_show_points']) {

		if (!empty($dataset['xpro_point_background_color'])) {
			$dataset_config['pointBackgroundColor'] = $dataset['xpro_point_background_color'];
		}

		if (!empty($dataset['xpro_point_border_color'])) {
			$dataset_config['pointBorderColor'] = $dataset['xpro_point_border_color'];
		}
		if (isset($dataset['xpro_point_hover_radius']) && $dataset['xpro_point_hover_radius'] !== '') {
			$dataset_config['pointHoverRadius'] = (float) $dataset['xpro_point_hover_radius'];
		}

		if (!empty($dataset['xpro_point_style'])) {
			$dataset_config['pointStyle'] = $dataset['xpro_point_style'];
		}
		$dataset_config['borderColor'] =
		!empty($dataset['xpro_border_color'])
			? $dataset['xpro_border_color']
			: '#36A2EB';
		$dataset_config['pointRadius'] = (float) ($dataset['xpro_point_radius'] ?? 4);

	} else {

		$dataset_config['pointRadius'] = 0;
		$dataset_config['pointHoverRadius'] = 0;
	}

	// Tension
	if (isset($dataset['xpro_tension']['size']) && $dataset['xpro_tension']['size'] !== '') {
		$dataset_config['tension'] = (float) $dataset['xpro_tension']['size'];
	}

	$datasets[] = $dataset_config;
}

		// Determine axis orientation
		$indexAxis = ('vertical' === $settings['xpro_chart_position']) ? 'y' : 'x';
		$category_axis = ('y' === $indexAxis) ? 'y' : 'x';
		$value_axis    = ('y' === $indexAxis) ? 'x' : 'y';

		// Build options array
		$options = array(
			'responsive' => true,
			'maintainAspectRatio' => false,
			'indexAxis' => $indexAxis,
			'plugins' => array(
				'legend' => array(
					'display'  => ('yes' === $settings['xpro_legend_display']),
					'position' => $settings['xpro_legend_position'] ?? 'top',
					'reverse'  => ('yes' === $settings['xpro_legend_reverse']),

					'labels' => array(
						// 'boxWidth'      => (int) ($settings['xpro_legend_box_width']['size'] ?? 40),
						'usePointStyle' => true,
						'pointStyle'    => $settings['xpro_legend_point_style'] ?? 'circle',

						'font' => array(
							'size'   => (int) ($settings['xpro_legend_font_size']['size'] ?? 12),
							'family' => $settings['xpro_legend_font_family'] ?? 'sans-serif',
							'weight' => $settings['xpro_legend_font_weight'] ?? 'normal',
							'style'  => $settings['xpro_legend_font_style'] ?? 'normal',
						),

						'color' => $settings['xpro_legend_font_color'] ?? '#333333',
					),
				),
				'tooltip' => array(
					'enabled'         => ('yes' === $settings['xpro_tooltip_display']),
					'mode'            => $settings['xpro_tooltip_mode'] ?? 'nearest',
					'backgroundColor' => $settings['xpro_tooltip_background_color'] ?? 'rgba(0,0,0,0.8)',
					'borderColor'     => $settings['xpro_tooltip_border_color'] ?? '#ffffff',
					'borderWidth'     => (int) ($settings['xpro_tooltip_border_width']['size'] ?? 0),
					'borderRadius'    => (int) ($settings['xpro_tooltip_border_radius']['size'] ?? 6),
					'padding'         => (int) ($settings['xpro_tooltip_padding']['size'] ?? 6),
					'caretSize'       => (int) ($settings['xpro_tooltip_caret_size']['size'] ?? 5),

					'titleColor' => $settings['xpro_tooltip_title_font_color'] ?? '#ffffff',
					'bodyColor'  => $settings['xpro_tooltip_body_font_color'] ?? '#ffffff',

					'titleFont' => array(
						'size' => (int) ($settings['xpro_tooltip_title_font_size']['size'] ?? 14),
					),

					'bodyFont' => array(
						'size' => (int) ($settings['xpro_tooltip_body_font_size']['size'] ?? 12),
					),
				),
			),
			'scales' => array(
				'x' => array(
					'grid' => array(
						'display' => ('yes' === $settings['xpro_xaxes_grid_display']),
						'color' => $settings['xpro_grid_color'] ?? '#e0e0e0',
					),
					'ticks' => array(
						'display' => ('yes' === $settings['xpro_xaxes_labels_display']),
					),
				),
				'y' => array(
					'beginAtZero' => ('yes' === $settings['xpro_begin_zero']),
					'grid' => array(
						'display' => ('yes' === $settings['xpro_yaxes_grid_display']),
						'color' => $settings['xpro_grid_color'] ?? '#e0e0e0',
					),
					'ticks' => array(
						'display' => ('yes' === $settings['xpro_yaxes_labels_display']),
					),
				),
			),
			'animation' => array(
				'duration' => (int) ($settings['xpro_chart_animation_duration'] ?? 1000),
				'easing' => $settings['xpro_animation_options'] ?? 'linear',
				'loop'=> ('yes' === ($settings['xpro_enable_animation_loop'] ?? 'no')),
			),
		);

		// Add stacked configuration
		if ('yes' === $settings['xpro_chart_stacked']) {
			$options['scales']['x']['stacked'] = true;
			$options['scales']['y']['stacked'] = true;
		}

		// Add stepped line
		if ('false' !== $settings['xpro_stepped_line']) {
			$options['elements'] = array(
				'line' => array(
					'stepped' => $settings['xpro_stepped_line'],
				),
			);
		}

		// Add title if enabled
		if ('yes' === $settings['xpro_title_display'] && !empty($settings['xpro_chart_title'])) {
			$options['plugins']['title'] = array(
				'display' => true,
				'text' => $settings['xpro_chart_title'],
				'font' => array(
					'size' => (int) ($settings['xpro_title_font_size']['size'] ?? 16),
					'family' => $settings['xpro_title_font_family'] ?? 'sans-serif',
					'weight' => $settings['xpro_title_font_weight'] ?? 'bold',
					'style' => $settings['xpro_title_font_style'] ?? 'normal',
				),
				'color' => $settings['xpro_title_font_color'] ?? '#333333',
			);
		}

		// Add axis label styles
		if ('yes' === $settings['xpro_xaxes_labels_display']) {
				$options['scales'][$category_axis]['ticks']['font'] = array(
				'size'   => (int) ($settings['xpro_labels_xaxes_font_size']['size'] ?? 12),
				'family' => $settings['xpro_labels_xaxes_font_family'] ?? 'sans-serif',
				'weight' => $settings['xpro_labels_xaxes_font_weight'] ?? 'normal',
				'style'  => $settings['xpro_labels_xaxes_font_style'] ?? 'normal',
			);

			$options['scales'][$category_axis]['ticks']['color'] =
				$settings['xpro_labels_xaxes_font_color'] ?? '#666666';

			$options['scales'][$category_axis]['ticks']['maxRotation'] =
				(int) ($settings['xpro_labels_xaxes_rotation'] ?? 0);

			$options['scales'][$category_axis]['ticks']['minRotation'] =
				(int) ($settings['xpro_labels_xaxes_rotation'] ?? 0);
		}

		if ('yes' === $settings['xpro_yaxes_labels_display']) {
			$options['scales'][$value_axis]['ticks']['font'] = array(
				'size'   => (int) ($settings['xpro_labels_yaxes_font_size']['size'] ?? 12),
				'family' => $settings['xpro_labels_yaxes_font_family'] ?? 'sans-serif',
				'weight' => $settings['xpro_labels_yaxes_font_weight'] ?? 'normal',
				'style'  => $settings['xpro_labels_yaxes_font_style'] ?? 'normal',
			);

			$options['scales'][$value_axis]['ticks']['color'] =
			$settings['xpro_labels_yaxes_font_color'] ?? '#666666';
		}

		if (!empty($settings['xpro_axis_range'])) {
			$options['scales']['y']['max'] = (float) $settings['xpro_axis_range'];
		}

		// Add step size if set
		if (!empty($settings['xpro_step_size'])) {
			$options['scales']['y']['ticks']['stepSize'] = (float) $settings['xpro_step_size'];
		}

		// Add layout padding if set
		if (!empty($settings['xpro_layout_padding'])) {
			$options['layout'] = array(
				'padding' => array(
					'top' => (int) $settings['xpro_layout_padding']['top'],
					'right' => (int) $settings['xpro_layout_padding']['right'],
					'bottom' => (int) $settings['xpro_layout_padding']['bottom'],
					'left' => (int) $settings['xpro_layout_padding']['left'],
				),
			);
		}

		$chart_data = array(
			'type' => 'line',
			'data' => array(
				'labels' => $labels,
				'datasets' => $datasets,
			),
			'options' => $options,
		);

		$chart_id = 'xpro-line-chart-' . $this->get_id();
	    require XPRO_ELEMENTOR_ADDONS_WIDGET . 'line-chart/layout/frontend.php';
	}
}