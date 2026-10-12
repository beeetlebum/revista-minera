<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

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
class Image_stack_group extends Widget_Base {

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
		return 'xpro-image-stack-group';
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
		return __( 'Image Stack Group', 'xpro-elementor-addons' );
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
		return 'xi-image xpro-widget-label';
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
		return array( 'xpro', 'image', 'image-stack-group', 'image-stack' );
	}

	protected function sanitize_tooltip_text($text) {
		return sanitize_text_field($text);
	}
	
	protected function sanitize_url($url) {
		return esc_url_raw($url);
	}

	protected function register_controls() {

		$this->start_controls_section(
			'xpro_section_items',
			array(
				'label' => esc_html__('Items', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'media_type',
			array(
				'label'   => esc_html__('Media Type', 'xpro-elementor-addons'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'img' => array(
						'title' => esc_html__('Image', 'xpro-elementor-addons'),
						'icon'  => 'eicon-image',
					),
					'icon' => array(
						'title' => esc_html__('Icon', 'xpro-elementor-addons'),
						'icon'  => 'eicon-star',
					),
				),
				'default' => 'img',
				'toggle' => false,
			)
		);

		$repeater->add_control(
			'selected_icon',
			array(
				'label'            => esc_html__('Icon', 'xpro-elementor-addons'),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'label_block'      => false,
				'skin'             => 'inline',
				'exclude_inline_options' => array('svg'),
				'default'          => array(
					'value'   => 'fas fa-user',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'media_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'type'      => Controls_Manager::MEDIA,
				'label'     => esc_html__('Image', 'xpro-elementor-addons'),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'media_type' => 'img',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'tooltip',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'label'       => esc_html__('Tooltip', 'xpro-elementor-addons'),
				'placeholder' => esc_html__('Type title here', 'xpro-elementor-addons'),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__('Link', 'xpro-elementor-addons'),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$placeholder = array(
			'image' => array(
				'url' => Utils::get_placeholder_image_src(),
			),
		);

		$this->add_control(
			'images',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '<# print(tooltip || "Image Group Item"); #>',
				'default'     => array_fill(0, 4, $placeholder),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'   => esc_html__('Alignment', 'xpro-elementor-addons'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__('Left', 'xpro-elementor-addons'),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__('Center', 'xpro-elementor-addons'),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__('Right', 'xpro-elementor-addons'),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'   => true,
				'default'  => 'center',
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-group' => 'display:flex !important; justify-content: {{VALUE}};',
				),
			)
		);

		// ADD: Group layout style
		$this->add_control(
			'group_layout_style',
			array(
				'label'   => esc_html__('Group Layout Style', 'xpro-elementor-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'overlap',
				'options' => array(
					'overlap'    => esc_html__('Overlap', 'xpro-elementor-addons'),
					'grid'       => esc_html__('Grid', 'xpro-elementor-addons'),
					'inline'     => esc_html__('Inline', 'xpro-elementor-addons'),
					'circle'     => esc_html__('Circle', 'xpro-elementor-addons'),
			),
			)
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------------
		| Style Controls
		|--------------------------------------------------------------------------
		*/

		$this->start_controls_section(
			'xpro_section_style_items',
			array(
				'label' => esc_html__('Image / Icon', 'xpro-elementor-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_icon',
			array(
				'label' => esc_html__( 'Icon', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		
		$this->add_responsive_control(
			'icon_size',
			array(
				'label' => esc_html__('Icon Size', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 200,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-image-stacked-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'global_icon_color',
			array(
				'label' => esc_html__('Icon Color', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item i'               => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-image-stacked-item svg'               => 'fill: {{VALUE}};',
					'{{WRAPPER}} .xpro-image-stacked-item .xpro-svg-wrap' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'icon_background',
				'label'    => esc_html__('Background', 'xpro-elementor-addons'),
				'types'    => array('classic', 'gradient'),
				'exclude'  => array('image'),
				'selector' => '{{WRAPPER}} .xpro-image-stacked-item .xpro-image-stacked-icon, {{WRAPPER}} .xpro-image-stacked-item i',
			)
			
		);

		$this->add_responsive_control(
			'border_size',
			array(
				'label' => esc_html__('Border Size', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'default' => array(
					'unit' => 'px',
					'size' => 3,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item i,
					{{WRAPPER}} .xpro-image-stacked-item img,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-svg-wrap,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-image-stacked-icon' =>
					'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item .xpro-image-stacked-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_responsive_control(
			'item_size',
			array(
				'label' => esc_html__('Item Size', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::SLIDER,
			    'separator' => 'before',
				'size_units' => array('px'),
				'range' => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 60,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item i,
					{{WRAPPER}} .xpro-image-stacked-item img,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-svg-wrap,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-image-stacked-icon' =>
					'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => -2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-image-stacked-group.overlap .xpro-image-stacked-item:not(:first-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-image-stacked-group.inline .xpro-image-stacked-item'                   => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .xpro-image-stacked-group.grid'                                               => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label' => esc_html__('Border Radius', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'default' => array(
					'top' => '50',
					'right' => '50',
					'bottom' => '50',
					'left' => '50',
					'unit' => '%',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item,
					{{WRAPPER}} .xpro-image-stacked-item i,
					{{WRAPPER}} .xpro-image-stacked-item img,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-svg-wrap,
					{{WRAPPER}} .xpro-image-stacked-item .xpro-image-stacked-icon' =>
					'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label'   => esc_html__('Hover Animation', 'xpro-elementor-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lift',
				'options' => array(
					'none'   => esc_html__('None', 'xpro-elementor-addons'),
					'lift'   => esc_html__('Lift', 'xpro-elementor-addons'),
					'scale'  => esc_html__('Scale', 'xpro-elementor-addons'),
					'rotate' => esc_html__('Rotate', 'xpro-elementor-addons'),
					'glow'   => esc_html__('Glow', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'hover_scale',
			array(
				'label' => esc_html__('Hover Scale', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1.5,
						'step' => 0.01,
					),
				),
				'default' => array(
					'size' => 1.1,
				),
				'condition' => array(
					'hover_animation' => array('scale', 'lift'),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item:hover' => 'transform: scale({{SIZE}});',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'label'    => esc_html__('Box Shadow', 'xpro-elementor-addons'),
				'selector' => '{{WRAPPER}} .xpro-image-stacked-item',
			)
		);

		// ADD: Gradient Border
		$this->add_control(
			'gradient_border',
			array(
				'label' => esc_html__('Gradient Border', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'gradient_border_start',
			array(
				'label' => esc_html__('Gradient Start', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::COLOR,
				'condition' => array(
					'gradient_border' => 'yes',
				),
			)
		);

		$this->add_control(
			'gradient_border_end',
			array(
				'label' => esc_html__('Gradient End', 'xpro-elementor-addons'),
				'type'  => Controls_Manager::COLOR,
				'condition' => array(
					'gradient_border' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------------
		| Tooltip Style
		|--------------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'xpro_section_tooltip_style',
			array(
				'label' => esc_html__( 'Tooltip', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tooltip_global_enable',
			array(
				'label'   => esc_html__( 'Enable Tooltips', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'tooltip_global_position',
			array(
				'label'   => esc_html__( 'Default Position', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'up' => array(
						'title' => esc_html__( 'Up', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'down' => array(
						'title' => esc_html__( 'Down', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'up',
				'toggle'    => false,
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => esc_html__( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 14,
					'bottom' => 8,
					'left'   => 14,
					'unit'   => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after' =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
					'unit'   => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after' =>
						'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tooltip_typography',
				'selector'  => '{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after',
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => esc_html__( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_background',
			array(
				'label'     => esc_html__( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111111',
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::before' => '--caret-color: {{VALUE}};',
				),
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_width',
			array(
				'label' => esc_html__( 'Tooltip Width', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after' =>
						'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; white-space: normal; text-align:center;',
				),
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'tooltip_box_shadow',
				'selector'  => '{{WRAPPER}} .xpro-image-stacked-item[data-tooltip]::after',
				'condition' => array(
					'tooltip_global_enable' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'image-stack-group/layout/frontend.php';

	}
}