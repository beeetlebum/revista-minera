<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
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
class Interactive_Circle extends Widget_Base
{

    /**
     * Get widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     */
    public function get_name()
    {
        return 'xpro-interactive-circle';
    }

    /**
     * Get widget inner wrapper.
     *
     * @return bool
     */
    public function has_widget_inner_wrapper(): bool
    {
        $has_wrapper = !Plugin::$instance->experiments->is_feature_active('e_optimized_markup');
        return $has_wrapper;
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     */
    public function get_title()
    {
        return __('Interactive Circle', 'xpro-elementor-addons');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     */
    public function get_icon()
    {
        return 'xi-circles xpro-widget-label';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
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
     */
    public function get_keywords()
    {
        return array('Interactive Circle', 'circle', 'Interactive', 'pie chart', 'donut chart');
    }

    /**
     * Get style dependencies.
     *
     * @return array Widget style dependencies.
     * @since 1.0.0
     * @access public
     */
    public function get_style_depends()
    {
        return array();
    }

    /**
     * Get script dependencies.
     *
     * @return array Widget script dependencies.
     * @since 1.0.0
     * @access public
     */
    public function get_script_depends()
    {
        return array('xpro-chartjs');
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        // ==================== CONTENT SECTION ====================
        $this->start_controls_section(
            'section_content_items',
            array(
                'label' => __('Circle Items', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            array(
                'label'       => __('Title', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Item Title', 'xpro-elementor-addons'),
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'item_description',
            array(
                'label'       => __('Description', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => __('Present your content in an attractive Circle layout. You can highlight key information with click or hover effects.', 'xpro-elementor-addons'),
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label'       => __('Icon', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'default'     => array(
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ),
            )
        );

        $repeater->add_control(
            'item_percentage',
            array(
                'label'       => __('Percentage / Value', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 25,
                'min'         => 0,
                'max'         => 100,
            )
        );

        $repeater->add_control(
            'item_color',
            array(
                'label'       => __('Accent Color', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#667eea',
            )
        );

        $repeater->add_control(
            'item_link',
            array(
                'label'       => __('Link (Optional)', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            )
        );

        // Center Info for each item
        $repeater->add_control(
            'item_center_title',
            array(
                'label'       => __('Center Title (Optional)', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
                'description' => __('Custom title to show in center when this item is active. Leave empty to use default.', 'xpro-elementor-addons'),
            )
        );

        $repeater->add_control(
            'item_center_subtitle',
            array(
                'label'       => __('Center Subtitle (Optional)', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
                'description' => __('Custom subtitle to show in center when this item is active. Leave empty to use default.', 'xpro-elementor-addons'),
            )
        );

        $this->add_control(
            'circle_items',
            array(
                'label'       => __('Circle Items', 'xpro-elementor-addons'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array('item_title' => __('Emotional', 'xpro-elementor-addons'), 'item_description' => __('Emotional wellness involves understanding and managing your feelings.', 'xpro-elementor-addons'), 'item_percentage' => 25, 'item_color' => '#FF6B6B', 'item_icon' => array('value' => 'fas fa-heart'), 'item_center_title' => 'Emotional Wellness', 'item_center_subtitle' => 'Feel better every day'),
                    array('item_title' => __('Spiritual', 'xpro-elementor-addons'), 'item_description' => __('Spiritual wellness means finding purpose and meaning in life.', 'xpro-elementor-addons'), 'item_percentage' => 20, 'item_color' => '#4ECDC4', 'item_icon' => array('value' => 'fas fa-feather-alt'), 'item_center_title' => 'Spiritual Growth', 'item_center_subtitle' => 'Find your purpose'),
                    array('item_title' => __('Physical', 'xpro-elementor-addons'), 'item_description' => __('Physical wellness focuses on maintaining a healthy body.', 'xpro-elementor-addons'), 'item_percentage' => 15, 'item_color' => '#45B7D1', 'item_icon' => array('value' => 'fas fa-dumbbell'), 'item_center_title' => 'Physical Health', 'item_center_subtitle' => 'Stay active'),
                    array('item_title' => __('Psychological', 'xpro-elementor-addons'), 'item_description' => __('Psychological wellness includes mental health and cognitive function.', 'xpro-elementor-addons'), 'item_percentage' => 10, 'item_color' => '#96CEB4', 'item_icon' => array('value' => 'fas fa-brain'), 'item_center_title' => 'Mental Balance', 'item_center_subtitle' => 'Clear mind'),
                    array('item_title' => __('Personal', 'xpro-elementor-addons'), 'item_description' => __('Personal wellness involves self-improvement and goal setting.', 'xpro-elementor-addons'), 'item_percentage' => 5, 'item_color' => '#FFEAA7', 'item_icon' => array('value' => 'fas fa-user'), 'item_center_title' => 'Personal Growth', 'item_center_subtitle' => 'Be your best'),
                    array('item_title' => __('Professional', 'xpro-elementor-addons'), 'item_description' => __('Professional wellness means finding satisfaction in your career.', 'xpro-elementor-addons'), 'item_percentage' => 10, 'item_color' => '#DDA0DD', 'item_icon' => array('value' => 'fas fa-briefcase'), 'item_center_title' => 'Career Success', 'item_center_subtitle' => 'Thrive at work'),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->end_controls_section();

        // ==================== STYLE LAYOUT SELECTION ====================
        $this->start_controls_section(
            'section_style_layout',
            array(
                'label' => __('Style Layout', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'style_layout',
            array(
                'label'              => esc_html__('Select Layout Style', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'default',
                'options'            => array(
                    'default' => esc_html__('Default', 'xpro-elementor-addons'),
                    'pie'     => esc_html__('Interactive Circle 1', 'xpro-elementor-addons'),
                    'donut'   => esc_html__('Interactive Circle 2', 'xpro-elementor-addons'),
                ),
                'frontend_available' => true,
                'description'        => __('Default: Custom interactive circle with points. Pie/Donut: Chart.js rendered charts with interactive points.', 'xpro-elementor-addons'),
            )
        );

        $this->add_control(
            'cutout_percentage',
            array(
                'label'      => esc_html__('Donut Cutout (%)', 'xpro-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('%'),
                'range'      => array(
                    '%' => array('min' => 0, 'max' => 90, 'step' => 5),
                ),
                'default'    => array('unit' => '%', 'size' => 50),
                'condition'  => array(
                    'style_layout' => 'donut',
                ),
            )
        );

        $this->end_controls_section();

        // ==================== DISPLAY CONTROLS ====================
        $this->start_controls_section(
            'section_display_controls',
            array(
                'label' => __('Display Controls', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'show_center_content',
            array(
                'label'              => esc_html__('Show Center Content', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'show_center_info_from_items',
            array(
                'label'              => esc_html__('Show Center Info from Items', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
                'description'        => __('Display custom center title and subtitle from each circle item', 'xpro-elementor-addons'),
                'condition'          => array(
                    'show_center_content' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_info_panel',
            array(
                'label'              => esc_html__('Show Info Panel', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'show_progress_circle',
            array(
                'label'              => esc_html__('Show Progress Circle', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
                'condition'          => array(
                    'style_layout' => 'default',
                ),
            )
        );

        $this->add_control(
            'show_point_labels',
            array(
                'label'              => esc_html__('Show Point Labels (Icons)', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
            )
        );

        $this->end_controls_section();

        // ==================== INFO PANEL SETTINGS ====================
        $this->start_controls_section(
            'section_info_panel',
            array(
                'label'     => __('Info Panel Settings', 'xpro-elementor-addons'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'show_info_panel' => 'yes',
                ),
            )
        );

        $this->add_control(
            'info_panel_position',
            array(
                'label'              => esc_html__('Info Panel Position', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'right',
                'options'            => array(
                    'right'  => esc_html__('Right', 'xpro-elementor-addons'),
                    'left'   => esc_html__('Left', 'xpro-elementor-addons'),
                    'bottom' => esc_html__('Bottom', 'xpro-elementor-addons'),
                ),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'show_percentage',
            array(
                'label'   => esc_html__('Show Percentage', 'xpro-elementor-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'show_button',
            array(
                'label'   => esc_html__('Show Action Button', 'xpro-elementor-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'     => __('Button Text', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Learn More →', 'xpro-elementor-addons'),
                'condition' => array('show_button' => 'yes'),
            )
        );

        $this->add_control(
            'info_panel_title_tag',
            array(
                'label'   => esc_html__('Title HTML Tag', 'xpro-elementor-addons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                    'p'   => 'p',
                ),
            )
        );

        $this->add_responsive_control(
            'info_panel_width',
            array(
                'label'      => esc_html__('Info Panel Width', 'xpro-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'rem'),
                'range'      => array(
                    'px' => array('min' => 200, 'max' => 600),
                    '%'  => array('min' => 10, 'max' => 100),
                ),
                'mobile_default' => array(
                    'unit' => 'px',
                    'size' => 300,
                ),
                'default'    => array('unit' => 'px', 'size' => 360),
                'selectors'  => array(
                    '{{WRAPPER}} .xpro-info-panel' => 'max-width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'show_info_panel' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'info_panel_img_width',
            array(
                'label'      => esc_html__('Info Icon Size', 'xpro-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'rem'),
                'range'      => array(
                    'px' => array('min' => 10, 'max' => 100),
                ),
                'default'    => array('unit' => 'px', 'size' => 30),
                'selectors'  => array(
                    '{{WRAPPER}} .xpro-info-title svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .xpro-info-title i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                ),
                'render_type' => 'template',
                'condition'  => array(
                    'show_info_panel' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // ==================== INTERACTION SETTINGS ====================
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => __('Interaction Settings', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_responsive_control(
            'interaction_mode',
            array(
                'label'              => esc_html__('Interaction Mode', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'auto',
                'options'            => array(
                    'click' => esc_html__('On Click', 'xpro-elementor-addons'),
                    'hover' => esc_html__('On Hover', 'xpro-elementor-addons'),
                    'auto'  => esc_html__('Auto Rotate', 'xpro-elementor-addons'),
                ),
                'frontend_available' => true,
            )
        );

        $this->add_responsive_control(
            'circle_size',
            array(
                'label'      => esc_html__( 'Circle Size', 'xpro-elementor-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'vw' ),
                'range'      => array(
                    'px' => array( 'min' => 100, 'max' => 800, 'step' => 10 ),
                    '%'  => array( 'min' => 20, 'max' => 100, 'step' => 5 ),
                    'vw' => array( 'min' => 20, 'max' => 80, 'step' => 5 ),
                ),
                'desktop_default' => array( 'unit' => 'px', 'size' => 500 ),
                'tablet_default'  => array( 'unit' => 'px', 'size' => 400 ),
                'mobile_default'  => array( 'unit' => 'px', 'size' => 300 ),
                'render_type'        => 'template',
                'frontend_available' => true,
                'selectors'  => array(
                    '{{WRAPPER}} .xpro-circle-container' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .xpro-circle-container .xpro-nav-points-container' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

     $this->add_responsive_control(
            'point_size',
            array(
                'label'   => esc_html__('Point Size (px)', 'xpro-elementor-addons'),
                'type'    => Controls_Manager::SLIDER,
                'range'   => array('px' => array('min' => 10, 'max' => 200, 'step' => 2)),
                'default' => array('size' => 55),
                'render_type' => 'template',
			    'frontend_available' => true,
                'selectors'  => array(
				'{{WRAPPER}} .xpro-nav-point' => 'width: {{SIZE}}{{UNIT}} !important;',
                '{{WRAPPER}} .xpro-nav-point' => 'height: {{SIZE}}{{UNIT}} !important;',

			),
            )
        );

        $this->add_control(
            'auto_rotate_speed',
            array(
                'label'              => esc_html__('Auto Rotate Speed (ms)', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 3000,
                'min'                => 1000,
                'max'                => 10000,
                'condition'          => array('interaction_mode' => 'auto'),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'animation_duration',
            array(
                'label'              => esc_html__('Animation Duration (s)', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SLIDER,
                'range'              => array('px' => array('min' => 0.2, 'max' => 2, 'step' => 0.1)),
                'default'            => array('size' => 0.5),
                'frontend_available' => true,
            )
        );

        $this->end_controls_section();

        // ==================== STYLE SECTION - CIRCLE ====================
        $this->start_controls_section(
            'section_style_circle',
            array(
                'label' => __('Circle Style', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition'  => array(
                    'style_layout' => 'pie',
                ),
            )
        );

        $this->add_control(
            'circle_bg_color',
            array(
                'label'     => __('Circle Background', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f0f0f0',
                'selectors' => array('{{WRAPPER}} .xpro-circle-bg' => 'stroke: {{VALUE}};'),
            )
        );

        $this->add_control(
            'circle_progress_color',
            array(
                'label'     => __('Progress Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#667eea',
                'selectors' => array('{{WRAPPER}} .xpro-circle-progress' => 'stroke: {{VALUE}};'),
            )
        );

        $this->add_control(
            'circle_stroke_width',
            array(
                'label'     => esc_html__('Stroke Width', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array('px' => array('min' => 1, 'max' => 10)),
                'default'   => array('size' => 3),
                'selectors' => array(
                    '{{WRAPPER}} .xpro-circle-bg, {{WRAPPER}} .xpro-circle-progress' => 'stroke-width: {{SIZE}}px;',
                ),
            )
        );

        $this->end_controls_section();

        // ==================== STYLE SECTION - POINTS ====================
        $this->start_controls_section(
            'section_style_points',
            array(
                'label' => __('Points Style', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'point_text_color',
            array(
                'label'     => __('Icon Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array('{{WRAPPER}} .xpro-nav-point svg' => 'color: {{VALUE}} !important;'),
            )
        );

        $this->add_control(
            'point_shadow',
            array(
                'label'   => esc_html__('Enable Shadow', 'xpro-elementor-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->end_controls_section();

        // ==================== STYLE SECTION - INFO PANEL ====================
            $this->start_controls_section(
                'section_style_info',
                array(
                    'label'     => __('Info Panel Style', 'xpro-elementor-addons'),
                    'tab'       => Controls_Manager::TAB_STYLE,
                    'condition' => array(
                        'show_info_panel' => 'yes',
                    ),
                )
            );

            /*
            * =========================
            * Info Panel
            * =========================
            */
            $this->add_control(
                'info_panel_heading',
                array(
                    'label'     => esc_html__('Info Panel', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_control(
                'info_panel_bg',
                array(
                    'label'     => __('Background Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-card' => 'background: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                'info_panel_border_radius',
                array(
                    'label'      => esc_html__('Border Radius', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'default'    => array(
                        'top'    => 24,
                        'right'  => 24,
                        'bottom' => 24,
                        'left'   => 24,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-card' =>
                            'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'info_panel_shadow',
                    'selector' => '{{WRAPPER}} .xpro-info-card',
                )
            );

            $this->add_responsive_control(
                'info_panel_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'default'    => array(
                        'top'    => 24,
                        'right'  => 24,
                        'bottom' => 24,
                        'left'   => 24,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-card' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Badge / Subtitle
            * =========================
            */
            $this->add_control(
                'info_badge_heading',
                array(
                    'label'     => esc_html__('Badge / Subtitle', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_badge_typography',
                    'selector' => '{{WRAPPER}} .xpro-info-badge',
                )
            );

            $this->add_control(
                'info_badge_color',
                array(
                    'label'     => esc_html__('Text Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#555555',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-badge' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                'info_badge_bg',
                array(
                    'label'     => esc_html__('Background Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#eeeeee',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-badge' => 'background: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_badge_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'default'    => array(
                        'top'    => 6,
                        'right'  => 14,
                        'bottom' => 6,
                        'left'   => 14,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-badge' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_control(
                'info_badge_border_radius',
                array(
                    'label'      => esc_html__('Border Radius', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'default'    => array(
                        'top'    => 20,
                        'right'  => 20,
                        'bottom' => 20,
                        'left'   => 20,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-badge' =>
                            'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Title
            * =========================
            */
            $this->add_control(
                'info_title_heading',
                array(
                    'label'     => esc_html__('Title', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_title_typography',
                    'selector' => '{{WRAPPER}} .xpro-info-title',
                )
            );

            $this->add_control(
                'info_title_color',
                array(
                    'label'     => esc_html__('Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#111111',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-title' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .xpro-info-title svg' => 'fill: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_title_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-title' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Description
            * =========================
            */
            $this->add_control(
                'info_description_heading',
                array(
                    'label'     => esc_html__('Description', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_description_typography',
                    'selector' => '{{WRAPPER}} .xpro-info-description',
                )
            );

            $this->add_control(
                'info_description_color',
                array(
                    'label'     => esc_html__('Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#555555',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-description' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_description_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-description' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Stats
            * =========================
            */
            $this->add_control(
                'info_stats_heading',
                array(
                    'label'     => esc_html__('Stats', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_control(
                'info_stats_value_heading',
                array(
                    'label'     => esc_html__('Value', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'none',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_stats_value_typography',
                    'selector' => '{{WRAPPER}} .xpro-stat-value',
                )
            );

            $this->add_control(
                'info_stats_value_color',
                array(
                    'label'     => esc_html__('Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#667eea',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-stat-value' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_stats_value_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-stat-value' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Stats Label
            * =========================
            */
            $this->add_control(
                'info_stats_label_heading',
                array(
                    'label'     => esc_html__('Label', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'none',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_stats_label_typography',
                    'selector' => '{{WRAPPER}} .xpro-stat-label',
                )
            );

            $this->add_control(
                'info_stats_label_color',
                array(
                    'label'     => esc_html__('Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#777777',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-stat-label' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_stats_label_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-stat-label' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Stats Container
            * =========================
            */
            $this->add_control(
                'info_stats_container_heading',
                array(
                    'label'     => esc_html__('Container', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'none',
                )
            );

            $this->add_responsive_control(
                'info_stats_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-stats' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );


            /*
            * =========================
            * Button
            * =========================
            */
            $this->add_control(
                'info_button_heading',
                array(
                    'label'     => esc_html__('Button', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'     => 'info_button_typography',
                    'selector' => '{{WRAPPER}} .xpro-info-button',
                )
            );

            $this->add_control(
                'info_button_color',
                array(
                    'label'     => esc_html__('Text Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-button' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                'info_button_bg',
                array(
                    'label'     => esc_html__('Background Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#667eea',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-button' => 'background: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                'info_button_hover_color',
                array(
                    'label'     => esc_html__('Hover Text Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-button:hover' => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                'info_button_hover_bg',
                array(
                    'label'     => esc_html__('Hover Background Color', 'xpro-elementor-addons'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#764ba2',
                    'selectors' => array(
                        '{{WRAPPER}} .xpro-info-button:hover' => 'background: {{VALUE}};',
                    ),
                )
            );

            $this->add_responsive_control(
                'info_button_padding',
                array(
                    'label'      => esc_html__('Padding', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%', 'em'),
                    'default'    => array(
                        'top'    => 12,
                        'right'  => 24,
                        'bottom' => 12,
                        'left'   => 24,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-button' =>
                            'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_control(
                'info_button_border_radius',
                array(
                    'label'      => esc_html__('Border Radius', 'xpro-elementor-addons'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'default'    => array(
                        'top'    => 30,
                        'right'  => 30,
                        'bottom' => 30,
                        'left'   => 30,
                        'unit'   => 'px',
                    ),
                    'selectors'  => array(
                        '{{WRAPPER}} .xpro-info-button' =>
                            'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'info_button_shadow',
                    'selector' => '{{WRAPPER}} .xpro-info-button',
                )
            );

            // $this->add_group_control(
            //     Group_Control_Box_Shadow::get_type(),
            //     array(
            //         'name'     => 'info_button_hover_shadow',
            //         'selector' => '{{WRAPPER}} .xpro-info-button:hover',
            //     )
            // );


            $this->end_controls_section();

        // ==================== STYLE SECTION - CENTER CONTENT ====================
        $this->start_controls_section(
            'section_style_center',
            array(
                'label'     => __('Center Content Style', 'xpro-elementor-addons'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_center_content' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'center_content_width',
            array(
                'label'      => esc_html__('Center Content Width', 'xpro-elementor-addons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', '%'),
                'range'      => array(
                    'px' => array('min' => 80, 'max' => 200),
                    '%'  => array('min' => 10, 'max' => 50),
                ),
                'default'    => array('unit' => 'px', 'size' => 180),
                'selectors'  => array(
                    '{{WRAPPER}} .xpro-center-content' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'center_bg_color',
            array(
                'label'     => __('Background Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array('{{WRAPPER}} .xpro-center-content' => 'background: {{VALUE}};'),
            )
        );

        $this->add_control(
            'center_icon_color',
            array(
                'label'     => __('Icon Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#667eea',
                'selectors' => array(
                    '{{WRAPPER}} .xpro-center-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .xpro-center-icon svg' => 'fill: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'center_title_color',
            array(
                'label'     => __('Title Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => array('{{WRAPPER}} .xpro-center-title' => 'color: {{VALUE}};'),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'center_title_typography',
                'selector' => '{{WRAPPER}} .xpro-center-title',
            )
        );

        $this->add_control(
            'center_subtitle_color',
            array(
                'label'     => __('Subtitle Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#999999',
                'selectors' => array('{{WRAPPER}} .xpro-center-subtitle' => 'color: {{VALUE}};'),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'center_subtitle_typography',
                'selector' => '{{WRAPPER}} .xpro-center-subtitle',
            )
        );

        $this->end_controls_section();

        // ==================== STYLE SECTION - CHART.JS ====================
        $this->start_controls_section(
            'section_style_chartjs',
            array(
                'label' => __('Interactive Circle Style', 'xpro-elementor-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'style_layout' => array('pie', 'donut'),
                ),
            )
        );

        $this->add_control(
            'chartjs_border_width',
            array(
                'label'     => esc_html__('Border Width', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array('px' => array('min' => 0, 'max' => 10)),
                'default'   => array('size' => 2),
            )
        );

        $this->add_control(
            'chartjs_border_color',
            array(
                'label'     => __('Border Color', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
            )
        );

        $this->add_control(
            'chartjs_hover_offset',
            array(
                'label'     => esc_html__('Hover Offset', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array('px' => array('min' => 0, 'max' => 20)),
                'default'   => array('size' => 10),
            )
        );

        $this->add_control(
            'chartjs_animation',
            array(
                'label'              => esc_html__('Enable Animation', 'xpro-elementor-addons'),
                'type'               => Controls_Manager::SWITCHER,
                'default'            => 'yes',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'chartjs_animation_duration',
            array(
                'label'     => esc_html__('Animation Duration (ms)', 'xpro-elementor-addons'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1000,
                'min'       => 0,
                'max'       => 5000,
                'condition' => array(
                    'chartjs_animation' => 'yes',
                ),
            )
        );

        $this->end_controls_section();
    }


    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render(){
        $settings = $this->get_settings_for_display();
        $style_layout = $settings['style_layout'];

        // Route to appropriate render method based on style layout
        if ($style_layout === 'default') {
            $this->render_default_style($settings);
        } else {
            $this->render_chartjs_style($settings);
        }
    }
    /**
     * Render Default Interactive Circle Style
     */
    protected function render_default_style($settings){
        $items = $settings['circle_items'];
        $total = count($items);
        $unique_id = 'xpro-circle-' . $this->get_id();
        $circle_size = $settings['circle_size']['size'] ?? 500;
        $point_size = $settings['point_size']['size'] ?? 55;
        $radius = ($circle_size / 2) - ($point_size / 2) - 15;

        // Prepare items data with center info
        $items_data = array();
        foreach ($items as $index => $item) {
            ob_start();
            if (!empty($item['item_icon']['value'])) {
                \Elementor\Icons_Manager::render_icon($item['item_icon'], array('aria-hidden' => 'true'));
            }
            $icon_html = ob_get_clean();
            $link = '';
            if ( ! empty( $item['item_link']['url'] ) ) {
                $raw_link = trim( (string) $item['item_link']['url'] );

                // Remove control characters and whitespace.
                $raw_link = preg_replace( '/[\x00-\x20\x7F]/', '', $raw_link );

                // Extract the protocol.
                $protocol = wp_parse_url( $raw_link, PHP_URL_SCHEME );

                // Only allow safe protocols.
                $allowed_protocols = array( 'http', 'https', 'mailto', 'tel' );

                if ( empty( $protocol ) || in_array( strtolower( $protocol ), $allowed_protocols, true ) ) {
                    $link = esc_url(
                        $raw_link,
                        $allowed_protocols
                    );
                }
            }

            $items_data[] = array(
                'id'              => $index,
                'title'           => $item['item_title'],
                'description'     => $item['item_description'],
                'icon'            => $icon_html,
                'percentage'      => $item['item_percentage'],
                'color'           => $item['item_color'],
                'link'            => $link,
                'link_target'     => $item['item_link']['is_external'] ? '_blank' : '_self',
                'center_title'    => $item['item_center_title'] ?? '',
                'center_subtitle' => $item['item_center_subtitle'] ?? '',
            );
        }

        // Center icon (if custom center icon is set in settings)
        $center_icon_html = '';
        if ('yes' === ($settings['show_center_content'] ?? 'yes') && !empty($settings['center_icon']['value'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['center_icon'], array('aria-hidden' => 'true'));
            $center_icon_html = ob_get_clean();
        }

        // Default center content (if no custom center icon)
        if (empty($center_icon_html) && !empty($items_data[0]['icon'])) {
            $center_icon_html = $items_data[0]['icon'];
        }

        // Info panel position class
        $info_panel_class = 'xpro-info-panel-' . ($settings['info_panel_position'] ?? 'right');
        $title_tag = $settings['info_panel_title_tag'] ?? 'h3';
        ?>
        
        <div class="xpro-interactive-circle-wrap <?php echo esc_attr($info_panel_class); ?>" 
            id="<?php echo esc_attr($unique_id); ?>"
            data-total="<?php echo esc_attr($total); ?>"
            data-radius="<?php echo esc_attr($radius); ?>"
            data-point-size="<?php echo esc_attr($point_size); ?>"
            data-center-x="<?php echo esc_attr($circle_size / 2); ?>"
            data-center-y="<?php echo esc_attr($circle_size / 2); ?>"
            data-circle-size="<?php echo esc_attr($circle_size); ?>"
            data-interaction="<?php echo esc_attr($settings['interaction_mode'] ?? 'click'); ?>"
            data-auto-speed="<?php echo esc_attr($settings['auto_rotate_speed'] ?? 3000); ?>"
            data-animation-duration="<?php echo esc_attr($settings['animation_duration']['size'] ?? 0.5); ?>"
            data-show-percentage="<?php echo esc_attr($settings['show_percentage'] ?? 'yes'); ?>"
            data-show-button="<?php echo esc_attr($settings['show_button'] ?? 'yes'); ?>"
            data-show-center="<?php echo esc_attr($settings['show_center_content'] ?? 'yes'); ?>"
            data-show-info-panel="<?php echo esc_attr($settings['show_info_panel'] ?? 'yes'); ?>"
            data-show-progress="<?php echo esc_attr($settings['show_progress_circle'] ?? 'yes'); ?>"
            data-show-point-labels="<?php echo esc_attr($settings['show_point_labels'] ?? 'yes'); ?>"
            data-show-center-info-from-items="<?php echo esc_attr($settings['show_center_info_from_items'] ?? 'yes'); ?>"
            data-center-title-static="<?php echo esc_attr($settings['center_title'] ?? ''); ?>"
            data-center-subtitle-static="<?php echo esc_attr($settings['center_subtitle'] ?? ''); ?>"
            data-button-text="<?php echo esc_attr($settings['button_text'] ?? 'Learn More →'); ?>"
            data-title-tag="<?php echo esc_attr($title_tag); ?>"
            data-point-shadow="<?php echo esc_attr($settings['point_shadow'] ?? 'yes'); ?>"
            data-items='<?php echo esc_attr(json_encode($items_data)); ?>'>
            
            <div class="xpro-circle-main">
                <div class="xpro-circle-container" style="width: <?php echo esc_attr($circle_size); ?>px; height: <?php echo esc_attr($circle_size); ?>px;">
                    
                    <?php if ('yes' === ($settings['show_progress_circle'] ?? 'yes')) : ?>
                    <svg class="xpro-circle-svg" viewBox="0 0 <?php echo esc_attr($circle_size); ?> <?php echo esc_attr($circle_size); ?>">
                        <circle class="xpro-circle-bg" cx="<?php echo esc_attr($circle_size / 2); ?>" cy="<?php echo esc_attr($circle_size / 2); ?>" r="<?php echo esc_attr($radius + 10); ?>" fill="none" />
                        <circle class="xpro-circle-progress" cx="<?php echo esc_attr($circle_size / 2); ?>" cy="<?php echo esc_attr($circle_size / 2); ?>" r="<?php echo esc_attr($radius + 10); ?>" fill="none" />
                    </svg>
                    <?php endif; ?>
                    
                    <?php if ('yes' === ($settings['show_center_content'] ?? 'yes')) : ?>
                    <div class="xpro-center-content">
                        <div class="xpro-center-icon"><?php echo wp_kses_post( $center_icon_html ); ?></div>
                        <div class="xpro-center-title"><?php echo esc_html($settings['center_title'] ?? ($items[0]['item_title'] ?? '')); ?></div>
                        <div class="xpro-center-subtitle"><?php echo esc_html($settings['center_subtitle'] ?? ''); ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="xpro-nav-points-container"></div>
                </div>
            </div>
            
            <?php if ('yes' === ($settings['show_info_panel'] ?? 'yes')) : ?>
            <div class="xpro-info-panel">
                <div class="xpro-info-card">
                    <div class="xpro-info-badge"></div>
                    <<?php echo esc_attr($title_tag); ?> class="xpro-info-title"></<?php echo esc_attr($title_tag); ?>>
                    <p class="xpro-info-description"></p>
                    <?php if ('yes' === ($settings['show_percentage'] ?? 'yes')) : ?>
                    <div class="xpro-info-stats">
                        <div class="xpro-stat">
                            <span class="xpro-stat-value"></span>
                            <span class="xpro-stat-label"><?php esc_html_e('Value', 'xpro-elementor-addons'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ('yes' === ($settings['show_button'] ?? 'yes')) : ?>
                    <button class="xpro-info-button"><?php echo esc_html($settings['button_text'] ?? 'Learn More →'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
        .xpro-interactive-circle-wrap{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:40px;position:relative;width:100%}.xpro-interactive-circle-wrap.xpro-info-panel-left{flex-direction:row-reverse}.xpro-interactive-circle-wrap.xpro-info-panel-bottom{flex-direction:column}.xpro-circle-main{flex-shrink:0}.xpro-circle-container{position:relative;margin:0 auto}.xpro-circle-svg{position:absolute;top:0;left:0;width:100%;height:100%;transform:rotate(-90deg)}.xpro-circle-bg{fill:none;stroke:<?php echo esc_attr($settings['circle_bg_color'] ?? '#f0f0f0');?>;stroke-width:<?php echo esc_attr($settings['circle_stroke_width']['size'] ?? 3);?>px}.xpro-circle-progress{fill:none;stroke:<?php echo esc_attr($settings['circle_progress_color'] ?? '#667eea');?>;stroke-width:<?php echo esc_attr($settings['circle_stroke_width']['size'] ?? 3);?>px;stroke-linecap:round;transition:stroke-dashoffset 0.5s ease}.xpro-center-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;z-index:15;background:<?php echo esc_attr($settings['center_bg_color'] ?? '#ffffff');?>;border-radius:50%;display:flex;flex-direction:column;justify-content:center;align-items:center;cursor:pointer;transition:all 0.3s ease;box-shadow:0 10px 30px rgb(0 0 0 / .1);width:<?php echo esc_attr($settings['center_content_width']['size'] ?? 180);?>px;height:<?php echo esc_attr($settings['center_content_width']['size'] ?? 180);?>px}.xpro-center-content:hover{transform:translate(-50%,-50%) scale(1.05)}.xpro-center-icon{font-size:34px;margin-bottom:8px;color:<?php echo esc_attr($settings['center_icon_color'] ?? '#667eea');?>}.xpro-center-icon svg{width:34px;height:34px;fill:<?php echo esc_attr($settings['center_icon_color'] ?? '#667eea');?>}.xpro-center-title{font-size:16px;font-weight:700;color:<?php echo esc_attr($settings['center_title_color'] ?? '#333333');?>}.xpro-center-subtitle{font-size:10px;color:<?php echo esc_attr($settings['center_subtitle_color'] ?? '#999999');?>;margin-top:4px}.xpro-nav-points-container{position:relative;width:100%;height:100%}.xpro-nav-point{position:absolute;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s cubic-bezier(.4,0,.2,1);z-index:20;font-size:20px;border-radius:50%;border:3px solid #fff;background-size:cover;color:<?php echo esc_attr($settings['point_text_color'] ?? '#ffffff');?>}.xpro-nav-point:hover{transform:scale(1.15)!important;z-index:30}.xpro-nav-point.active{border-color:#fff;transform:scale(1.08)}.xpro-nav-point i,.xpro-nav-point svg{pointer-events:none}.xpro-nav-point svg{width:22px;height:22px;fill:currentColor}.xpro-info-panel{flex:1;min-width:260px}.xpro-info-card{background:<?php echo esc_attr($settings['info_panel_bg'] ?? '#ffffff');?>;border-radius:<?php echo esc_attr($settings['info_panel_border_radius']['top'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['right'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['bottom'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['left'] ?? 24);?>px;padding:25px;transition:all 0.3s ease;border:1px solid rgb(0 0 0 / .05)}.xpro-info-badge{display:inline-block;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:15px}.xpro-info-title{display:flex;align-items:center;gap:12px;font-size:22px;margin-bottom:12px;color:#1a1a2e}.xpro-info-title svg{width:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;height:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;display:inline-block;vertical-align:middle}.xpro-info-title i{font-size:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;width:auto;height:auto}.xpro-info-description{font-size:14px;line-height:1.6;color:#666;margin-bottom:20px}.xpro-info-stats{margin-top:15px;padding-top:15px;border-top:1px solid #eee}.xpro-stat-value{font-size:28px;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;background-clip:text;color:#fff0}.xpro-stat-label{font-size:12px;color:#999;margin-left:8px}.xpro-info-button{width:100%;margin-top:20px;padding:12px 20px;border:none;font-weight:600;cursor:pointer;transition:all 0.3s ease;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:30px;font-size:14px}.xpro-info-button:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgb(102 126 234 / .3)}
        </style>

        <script>
            (function(){var container=document.getElementById('<?php echo esc_js($unique_id); ?>');if(!container)return;var items=JSON.parse(container.dataset.items);var total=parseInt(container.dataset.total);var radius=parseFloat(container.dataset.radius);var pointSize=parseFloat(container.dataset.pointSize);var centerX=parseFloat(container.dataset.centerX);var centerY=parseFloat(container.dataset.centerY);var interactionMode=container.dataset.interaction;var autoSpeed=parseInt(container.dataset.autoSpeed);var animationDuration=parseFloat(container.dataset.animationDuration)||0.5;var showPercentage=container.dataset.showPercentage==='yes';var showButton=container.dataset.showButton==='yes';var showCenter=container.dataset.showCenter==='yes';var showInfoPanel=container.dataset.showInfoPanel==='yes';var showProgress=container.dataset.showProgress==='yes';var showPointLabels=container.dataset.showPointLabels==='yes';var showCenterInfoFromItems=container.dataset.showCenterInfoFromItems==='yes';var centerTitleStatic=container.dataset.centerTitleStatic;var centerSubtitleStatic=container.dataset.centerSubtitleStatic;var buttonText=container.dataset.buttonText;var titleTag=container.dataset.titleTag;var pointShadow=container.dataset.pointShadow==='yes';var activeIndex=0;var angleStep=(Math.PI*2)/total;var startAngle=-Math.PI/2;var autoTimer=null;var isInteracting=!1;var isAnimating=!1;var pointsContainer=container.querySelector('.xpro-nav-points-container');var progressCircle=container.querySelector('.xpro-circle-progress');var infoBadge=container.querySelector('.xpro-info-badge');var infoTitle=container.querySelector('.xpro-info-title');var infoDesc=container.querySelector('.xpro-info-description');var infoStatValue=container.querySelector('.xpro-stat-value');var infoButton=container.querySelector('.xpro-info-button');var centerContent=container.querySelector('.xpro-center-content');var circleRadius=radius+10;var circumference=2*Math.PI*circleRadius;if(progressCircle&&showProgress){progressCircle.style.strokeDasharray=circumference}
            if(!pointShadow){var style=document.createElement('style');style.textContent='#<?php echo esc_js($unique_id); ?> .xpro-nav-point { box-shadow: none !important; }';document.head.appendChild(style)}
            function calculatePosition(index){var angle=startAngle+(index*angleStep);var x=centerX+radius*Math.cos(angle);var y=centerY+radius*Math.sin(angle);return{x:x,y:y,angle:angle}}
            function createPoints(){if(!pointsContainer)return;pointsContainer.innerHTML='';items.forEach(function(item,idx){var pos=calculatePosition(idx);var point=document.createElement('div');point.className='xpro-nav-point';if(idx===activeIndex)point.classList.add('active');point.setAttribute('data-idx',idx);point.style.width=pointSize+'px';point.style.height=pointSize+'px';point.style.left=(pos.x-pointSize/2)+'px';point.style.top=(pos.y-pointSize/2)+'px';point.innerHTML=showPointLabels?item.icon:'';point.style.background=item.color;if(interactionMode==='hover'){point.addEventListener('mouseenter',function(){if(!isAnimating)handleItemChange(idx);})}else{point.addEventListener('click',function(e){e.stopPropagation();if(!isAnimating)handleItemChange(idx);})}
            pointsContainer.appendChild(point)})}
            function updatePointsActive(){if(!pointsContainer)return;var points=pointsContainer.querySelectorAll('.xpro-nav-point');points.forEach(function(point){var idx=parseInt(point.getAttribute('data-idx'));if(idx===activeIndex){point.classList.add('active')}else{point.classList.remove('active')}})}
            function updateProgress(){if(!progressCircle||!showProgress)return;var progress=(activeIndex+1)/total;var offset=circumference*(1-progress);progressCircle.style.transition='stroke-dashoffset '+animationDuration+'s ease';progressCircle.style.strokeDashoffset=offset}
            function updateInfoPanel(){if(!items[activeIndex])return;if(!showInfoPanel)return;var item=items[activeIndex];if(infoBadge){infoBadge.innerHTML=item.title;infoBadge.style.background='linear-gradient(135deg, '+item.color+'20 0%, '+item.color+'40 100%)'}
            if(infoTitle)infoTitle.innerHTML=item.icon+' '+item.title;if(infoDesc)infoDesc.innerHTML=item.description;if(showPercentage&&infoStatValue){infoStatValue.innerHTML=item.percentage+'%'}
            if(showButton&&infoButton){infoButton.innerHTML=buttonText;infoButton.onclick=function(e){e.preventDefault();e.stopPropagation();if(item.link){window.open(item.link,item.link_target||'_self')}else{alert('Explore '+item.title)}}}}
            function updateCenterContent(){if(!showCenter||!centerContent)return;var item=items[activeIndex];var centerIcon=centerContent.querySelector('.xpro-center-icon');var centerTitleSpan=centerContent.querySelector('.xpro-center-title');var centerSubtitleSpan=centerContent.querySelector('.xpro-center-subtitle');if(centerIcon&&showPointLabels){centerIcon.innerHTML=item.icon}
            if(centerTitleSpan){if(showCenterInfoFromItems&&item.center_title&&item.center_title!==''){centerTitleSpan.innerHTML=item.center_title}else if(centerTitleStatic&&centerTitleStatic!==''){centerTitleSpan.innerHTML=centerTitleStatic}else{centerTitleSpan.innerHTML=item.title}}
            if(centerSubtitleSpan){if(showCenterInfoFromItems&&item.center_subtitle&&item.center_subtitle!==''){centerSubtitleSpan.innerHTML=item.center_subtitle}else if(centerSubtitleStatic&&centerSubtitleStatic!==''){centerSubtitleSpan.innerHTML=centerSubtitleStatic}else{centerSubtitleSpan.innerHTML=item.percentage+'% Complete'}}}
            function handleItemChange(index){if(isAnimating)return;if(index===activeIndex&&interactionMode!=='auto')return;isAnimating=!0;activeIndex=index;updatePointsActive();updateProgress();updateInfoPanel();updateCenterContent();var activePoint=pointsContainer?pointsContainer.querySelector('.xpro-nav-point.active'):null;if(activePoint){activePoint.style.transform='scale(0.9)';setTimeout(function(){if(activePoint)activePoint.style.transform='';setTimeout(function(){isAnimating=!1},100)},150)}else{setTimeout(function(){isAnimating=!1},150)}}
            function handleNextItem(){if(!isInteracting&&interactionMode==='auto'&&!isAnimating){var nextIndex=(activeIndex+1)%total;handleItemChange(nextIndex)}}
            function startAutoRotate(){if(autoTimer)clearInterval(autoTimer);if(interactionMode!=='auto')return;autoTimer=setInterval(handleNextItem,autoSpeed)}
            function stopAutoRotate(){if(autoTimer){clearInterval(autoTimer);autoTimer=null}}
            function handleCenterClick(){if(!showCenter)return;var nextIndex=(activeIndex+1)%total;handleItemChange(nextIndex)}
            function init(){createPoints();if(showProgress)updateProgress();if(showInfoPanel)updateInfoPanel();if(showCenter)updateCenterContent();updatePointsActive();if(interactionMode==='auto'){startAutoRotate();container.addEventListener('mouseenter',function(){isInteracting=!0;stopAutoRotate()});container.addEventListener('mouseleave',function(){isInteracting=!1;startAutoRotate()})}
            if(centerContent&&showCenter){centerContent.addEventListener('click',handleCenterClick)}}
            init()})();
        </script>
        <?php
    }

    /**
     * Render Chart.js Style (Pie or Donut with Interactive Points)
     */
    protected function render_chartjs_style($settings){
        $items = $settings['circle_items'];
        $total = count($items);
        $unique_id = 'xpro-chart-circle-' . $this->get_id();
        $circle_size = $settings['circle_size']['size'] ?? 500;
        $point_size = $settings['point_size']['size'] ?? 55;
        $style_layout = $settings['style_layout'];
        $cutout = ($style_layout === 'donut') ? ($settings['cutout_percentage']['size'] ?? 50) : 0;
        
        // Prepare data for Chart.js and points
        $labels = array();
        $data_values = array();
        $background_colors = array();
        $items_data = array();
        
        foreach ($items as $index => $item) {
            $labels[] = $item['item_title'];
            $data_values[] = floatval($item['item_percentage']);
            $background_colors[] = $item['item_color'];
            
            ob_start();
            if (!empty($item['item_icon']['value'])) {
                \Elementor\Icons_Manager::render_icon($item['item_icon'], array('aria-hidden' => 'true'));
            }
            $icon_html = ob_get_clean();
            $link = '';
            if ( ! empty( $item['item_link']['url'] ) ) {
                $raw_link = trim( (string) $item['item_link']['url'] );

                // Remove control characters and whitespace.
                $raw_link = preg_replace( '/[\x00-\x20\x7F]/', '', $raw_link );

                // Extract the protocol.
                $protocol = wp_parse_url( $raw_link, PHP_URL_SCHEME );

                // Only allow safe protocols.
                $allowed_protocols = array( 'http', 'https', 'mailto', 'tel' );

                if ( empty( $protocol ) || in_array( strtolower( $protocol ), $allowed_protocols, true ) ) {
                    $link = esc_url(
                        $raw_link,
                        $allowed_protocols
                    );
                }
            }
            
            $items_data[] = array(
                'id'              => $index,
                'title'           => $item['item_title'],
                'description'     => $item['item_description'],
                'icon'            => $icon_html,
                'percentage'      => $item['item_percentage'],
                'color'           => $item['item_color'],
                'link'            => $link,
                'link_target'     => $item['item_link']['is_external'] ? '_blank' : '_self',
                'center_title'    => $item['item_center_title'] ?? '',
                'center_subtitle' => $item['item_center_subtitle'] ?? '',
            );
        }
        
        // Center icon
        $center_icon_html = '';
        if ('yes' === ($settings['show_center_content'] ?? 'yes') && !empty($settings['center_icon']['value'])) {
            ob_start();
            \Elementor\Icons_Manager::render_icon($settings['center_icon'], array('aria-hidden' => 'true'));
            $center_icon_html = ob_get_clean();
        }
        
        if (empty($center_icon_html) && !empty($items_data[0]['icon'])) {
            $center_icon_html = $items_data[0]['icon'];
        }
        
        // Info panel position class
        $info_panel_class = 'xpro-info-panel-' . ($settings['info_panel_position'] ?? 'right');
        $title_tag = $settings['info_panel_title_tag'] ?? 'h3';
        ?>
        
        <div class="xpro-interactive-circle-wrap xpro-chartjs-style <?php echo esc_attr($info_panel_class); ?>" 
            id="<?php echo esc_attr($unique_id); ?>"
            data-total="<?php echo esc_attr($total); ?>"
            data-circle-size="<?php echo esc_attr($circle_size); ?>"
            data-point-size="<?php echo esc_attr($point_size); ?>"
            data-style-layout="<?php echo esc_attr($style_layout); ?>"
            data-cutout="<?php echo esc_attr($cutout); ?>"
            data-interaction="<?php echo esc_attr($settings['interaction_mode'] ?? 'click'); ?>"
            data-auto-speed="<?php echo esc_attr($settings['auto_rotate_speed'] ?? 3000); ?>"
            data-show-percentage="<?php echo esc_attr($settings['show_percentage'] ?? 'yes'); ?>"
            data-show-button="<?php echo esc_attr($settings['show_button'] ?? 'yes'); ?>"
            data-show-center="<?php echo esc_attr($settings['show_center_content'] ?? 'yes'); ?>"
            data-show-info-panel="<?php echo esc_attr($settings['show_info_panel'] ?? 'yes'); ?>"
            data-show-point-labels="<?php echo esc_attr($settings['show_point_labels'] ?? 'yes'); ?>"
            data-show-center-info-from-items="<?php echo esc_attr($settings['show_center_info_from_items'] ?? 'yes'); ?>"
            data-center-title-static="<?php echo esc_attr($settings['center_title'] ?? ''); ?>"
            data-center-subtitle-static="<?php echo esc_attr($settings['center_subtitle'] ?? ''); ?>"
            data-button-text="<?php echo esc_attr($settings['button_text'] ?? 'Learn More →'); ?>"
            data-title-tag="<?php echo esc_attr($title_tag); ?>"
            data-point-shadow="<?php echo esc_attr($settings['point_shadow'] ?? 'yes'); ?>"
            data-items='<?php echo esc_attr(json_encode($items_data)); ?>'
            data-chart-labels='<?php echo esc_attr(json_encode($labels)); ?>'
            data-chart-values='<?php echo esc_attr(json_encode($data_values)); ?>'
            data-chart-colors='<?php echo esc_attr(json_encode($background_colors)); ?>'
            data-border-width="<?php echo esc_attr($settings['chartjs_border_width']['size'] ?? 2); ?>"
            data-border-color="<?php echo esc_attr($settings['chartjs_border_color'] ?? '#ffffff'); ?>"
            data-hover-offset="<?php echo esc_attr($settings['chartjs_hover_offset']['size'] ?? 10); ?>"
            data-animation-enabled="<?php echo esc_attr($settings['chartjs_animation'] ?? 'yes'); ?>"
            data-animation-duration="<?php echo esc_attr($settings['chartjs_animation_duration'] ?? 1000); ?>">
            
            <div class="xpro-circle-main">
                <div class="xpro-circle-container" style="width: <?php echo esc_attr($circle_size); ?>px; height: <?php echo esc_attr($circle_size); ?>px;">
                    <canvas id="<?php echo esc_attr($unique_id); ?>-canvas" width="<?php echo esc_attr($circle_size); ?>" height="<?php echo esc_attr($circle_size); ?>"></canvas>
                    
                    <?php if ('yes' === ($settings['show_center_content'] ?? 'yes')) : ?>
                    <div class="xpro-center-content">
                        <div class="xpro-center-icon"><?php echo wp_kses_post( $center_icon_html); ?></div>
                        <div class="xpro-center-title"><?php echo esc_html($settings['center_title'] ?? ($items[0]['item_title'] ?? '')); ?></div>
                        <div class="xpro-center-subtitle"><?php echo esc_html($settings['center_subtitle'] ?? ''); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="xpro-nav-points-container"></div>
                </div>
            </div>
            
            <?php if ('yes' === ($settings['show_info_panel'] ?? 'yes')) : ?>
            <div class="xpro-info-panel">
                <div class="xpro-info-card">
                    <div class="xpro-info-badge"></div>
                    <<?php echo esc_attr($title_tag); ?> class="xpro-info-title"></<?php echo esc_attr($title_tag); ?>>
                    <p class="xpro-info-description"></p>
                    <?php if ('yes' === ($settings['show_percentage'] ?? 'yes')) : ?>
                    <div class="xpro-info-stats">
                        <div class="xpro-stat">
                            <span class="xpro-stat-value"></span>
                            <span class="xpro-stat-label"><?php esc_html_e('Percentage', 'xpro-elementor-addons'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ('yes' === ($settings['show_button'] ?? 'yes')) : ?>
                    <button class="xpro-info-button"><?php echo esc_html($settings['button_text'] ?? 'Learn More →'); ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <style>
            .xpro-interactive-circle-wrap{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:40px;position:relative;width:100%}.xpro-interactive-circle-wrap.xpro-info-panel-left{flex-direction:row-reverse}.xpro-interactive-circle-wrap.xpro-info-panel-bottom{flex-direction:column}.xpro-circle-main{flex-shrink:0}.xpro-circle-container{position:relative;margin:0 auto}.xpro-circle-container canvas{position:absolute;top:0;left:0;width:100%!important;height:100%!important}.xpro-center-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;z-index:15;background:<?php echo esc_attr($settings['center_bg_color'] ?? '#ffffff');?>;border-radius:50%;display:flex;flex-direction:column;justify-content:center;align-items:center;cursor:pointer;transition:all 0.3s ease;box-shadow:0 10px 30px rgb(0 0 0 / .1);width:<?php echo esc_attr($settings['center_content_width']['size'] ?? 180);?>px;height:<?php echo esc_attr($settings['center_content_width']['size'] ?? 180);?>px}.xpro-center-content:hover{transform:translate(-50%,-50%) scale(1.05)}.xpro-center-icon{font-size:34px;margin-bottom:8px;color:<?php echo esc_attr($settings['center_icon_color'] ?? '#667eea');?>}.xpro-center-icon svg{width:34px;height:34px;fill:<?php echo esc_attr($settings['center_icon_color'] ?? '#667eea');?>}.xpro-center-title{font-size:16px;font-weight:700;color:<?php echo esc_attr($settings['center_title_color'] ?? '#333333');?>}.xpro-center-subtitle{font-size:10px;color:<?php echo esc_attr($settings['center_subtitle_color'] ?? '#999999');?>;margin-top:4px}.xpro-nav-points-container{position:relative;width:100%;height:100%}.xpro-nav-point{position:absolute;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s cubic-bezier(.4,0,.2,1);z-index:20;font-size:20px;border-radius:50%;border:3px solid #fff;background-size:cover;color:<?php echo esc_attr($settings['point_text_color'] ?? '#ffffff');?>}.xpro-nav-point:hover{transform:scale(1.15)!important;z-index:30}.xpro-nav-point.active{border-color:#fff;transform:scale(1.08)}.xpro-nav-point i,.xpro-nav-point svg{pointer-events:none}.xpro-nav-point svg{width:22px;height:22px;fill:currentColor}.xpro-info-panel{flex:1;min-width:260px}.xpro-info-card{background:<?php echo esc_attr($settings['info_panel_bg'] ?? '#ffffff');?>;border-radius:<?php echo esc_attr($settings['info_panel_border_radius']['top'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['right'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['bottom'] ?? 24);?>px <?php echo esc_attr($settings['info_panel_border_radius']['left'] ?? 24);?>px;padding:25px;transition:all 0.3s ease;border:1px solid rgb(0 0 0 / .05)}.xpro-info-badge{display:inline-block;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:15px}.xpro-info-title{display:flex;align-items:center;gap:12px;font-size:22px;margin-bottom:12px;color:#1a1a2e}.xpro-info-title svg{width:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;height:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;display:inline-block;vertical-align:middle}.xpro-info-title i{font-size:<?php echo esc_attr($settings['info_panel_img_width']['size'] ?? 30);?>px;width:auto;height:auto}.xpro-info-description{font-size:14px;line-height:1.6;color:#666;margin-bottom:20px}.xpro-info-stats{margin-top:15px;padding-top:15px;border-top:1px solid #eee}.xpro-stat-value{font-size:28px;font-weight:700;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;background-clip:text;color:#fff0}.xpro-stat-label{font-size:12px;color:#999;margin-left:8px}.xpro-info-button{width:100%;margin-top:20px;padding:12px 20px;border:none;font-weight:600;cursor:pointer;transition:all 0.3s ease;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:30px;font-size:14px}.xpro-info-button:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgb(102 126 234 / .3)}
        </style>
        
        <script>
            (function(){var container=document.getElementById('<?php echo esc_js($unique_id); ?>');if(!container)return;var items=JSON.parse(container.dataset.items);var total=parseInt(container.dataset.total);var circleSize=parseFloat(container.dataset.circleSize);var pointSize=parseFloat(container.dataset.pointSize);var styleLayout=container.dataset.styleLayout;var cutout=parseFloat(container.dataset.cutout);var interactionMode=container.dataset.interaction;var autoSpeed=parseInt(container.dataset.autoSpeed);var showPercentage=container.dataset.showPercentage==='yes';var showButton=container.dataset.showButton==='yes';var showCenter=container.dataset.showCenter==='yes';var showInfoPanel=container.dataset.showInfoPanel==='yes';var showPointLabels=container.dataset.showPointLabels==='yes';var showCenterInfoFromItems=container.dataset.showCenterInfoFromItems==='yes';var centerTitleStatic=container.dataset.centerTitleStatic;var centerSubtitleStatic=container.dataset.centerSubtitleStatic;var buttonText=container.dataset.buttonText;var titleTag=container.dataset.titleTag;var pointShadow=container.dataset.pointShadow==='yes';var chartLabels=JSON.parse(container.dataset.chartLabels);var chartValues=JSON.parse(container.dataset.chartValues);var chartColors=JSON.parse(container.dataset.chartColors);var borderWidth=parseInt(container.dataset.borderWidth);var borderColor=container.dataset.borderColor;var hoverOffset=parseInt(container.dataset.hoverOffset);var animationEnabled=container.dataset.animationEnabled==='yes';var animationDuration=parseInt(container.dataset.animationDuration);var activeIndex=0;var autoTimer=null;var isInteracting=!1;var isAnimating=!1;var chart=null;var pointsContainer=container.querySelector('.xpro-nav-points-container');var infoBadge=container.querySelector('.xpro-info-badge');var infoTitle=container.querySelector('.xpro-info-title');var infoDesc=container.querySelector('.xpro-info-description');var infoStatValue=container.querySelector('.xpro-stat-value');var infoButton=container.querySelector('.xpro-info-button');var centerContent=container.querySelector('.xpro-center-content');var canvas=document.getElementById('<?php echo esc_js($unique_id); ?>-canvas');if(!pointShadow){var style=document.createElement('style');style.textContent='#<?php echo esc_js($unique_id); ?> .xpro-nav-point { box-shadow: none !important; }';document.head.appendChild(style)}
            function calculatePointPositions(){var positions=[];var centerX=circleSize/2;var centerY=circleSize/2;var radius=(circleSize/2)-(pointSize/2)-10;var totalValue=0;var values=[];items.forEach(function(item){totalValue+=item.percentage;values.push(item.percentage)});if(totalValue===0)return positions;var startAngle=-Math.PI/2;var currentAngle=startAngle;items.forEach(function(item,idx){var angleSpan=(values[idx]/totalValue)*Math.PI*2;var midAngle=currentAngle+(angleSpan/2);var x=centerX+radius*Math.cos(midAngle);var y=centerY+radius*Math.sin(midAngle);positions.push({x:x,y:y,angle:midAngle});currentAngle+=angleSpan});return positions}
            function createPoints(){if(!pointsContainer)return;pointsContainer.innerHTML='';var positions=calculatePointPositions();items.forEach(function(item,idx){var pos=positions[idx];if(!pos)return;var point=document.createElement('div');point.className='xpro-nav-point';if(idx===activeIndex)point.classList.add('active');point.setAttribute('data-idx',idx);point.style.width=pointSize+'px';point.style.height=pointSize+'px';point.style.left=(pos.x-pointSize/2)+'px';point.style.top=(pos.y-pointSize/2)+'px';point.innerHTML=showPointLabels?item.icon:'';point.style.background=item.color;if(interactionMode==='hover'){point.addEventListener('mouseenter',function(){if(!isAnimating)handleItemChange(idx);})}else{point.addEventListener('click',function(e){e.stopPropagation();if(!isAnimating)handleItemChange(idx);})}
            pointsContainer.appendChild(point)})}
            function updatePointsActive(){if(!pointsContainer)return;var points=pointsContainer.querySelectorAll('.xpro-nav-point');points.forEach(function(point,idx){if(idx===activeIndex){point.classList.add('active')}else{point.classList.remove('active')}})}
            function updateInfoPanel(){if(!items[activeIndex])return;if(!showInfoPanel)return;var item=items[activeIndex];if(infoBadge){infoBadge.innerHTML=item.title;infoBadge.style.background='linear-gradient(135deg, '+item.color+'20 0%, '+item.color+'40 100%)'}
            if(infoTitle){infoTitle.innerHTML=item.icon+' '+item.title}
            if(infoDesc)infoDesc.innerHTML=item.description;if(showPercentage&&infoStatValue){infoStatValue.innerHTML=item.percentage+'%'}
            if(showButton&&infoButton){infoButton.innerHTML=buttonText;infoButton.onclick=function(e){e.preventDefault();e.stopPropagation();if(item.link){window.open(item.link,item.link_target||'_self')}else{alert('Explore '+item.title)}}}}
            function updateCenterContent(){if(!showCenter||!centerContent)return;var item=items[activeIndex];var centerIcon=centerContent.querySelector('.xpro-center-icon');var centerTitleSpan=centerContent.querySelector('.xpro-center-title');var centerSubtitleSpan=centerContent.querySelector('.xpro-center-subtitle');if(centerIcon&&showPointLabels){centerIcon.innerHTML=item.icon}
            if(centerTitleSpan){if(showCenterInfoFromItems&&item.center_title&&item.center_title!==''){centerTitleSpan.innerHTML=item.center_title}else if(centerTitleStatic&&centerTitleStatic!==''){centerTitleSpan.innerHTML=centerTitleStatic}else{centerTitleSpan.innerHTML=item.title}}
            if(centerSubtitleSpan){if(showCenterInfoFromItems&&item.center_subtitle&&item.center_subtitle!==''){centerSubtitleSpan.innerHTML=item.center_subtitle}else if(centerSubtitleStatic&&centerSubtitleStatic!==''){centerSubtitleSpan.innerHTML=centerSubtitleStatic}else{centerSubtitleSpan.innerHTML=item.percentage+'%'}}}
            function handleItemChange(index){if(isAnimating)return;if(index===activeIndex&&interactionMode!=='auto')return;isAnimating=!0;activeIndex=index;updatePointsActive();updateInfoPanel();updateCenterContent();if(chart&&chart.setActiveElements){try{chart.setActiveElements([{datasetIndex:0,index:activeIndex}]);chart.update()}catch(e){}}
            var activePoint=pointsContainer?pointsContainer.querySelector('.xpro-nav-point.active'):null;if(activePoint){activePoint.style.transform='scale(0.9)';setTimeout(function(){if(activePoint)activePoint.style.transform='';setTimeout(function(){isAnimating=!1},100)},150)}else{setTimeout(function(){isAnimating=!1},150)}}
            function handleNextItem(){if(!isInteracting&&interactionMode==='auto'&&!isAnimating){var nextIndex=(activeIndex+1)%total;handleItemChange(nextIndex)}}
            function startAutoRotate(){if(autoTimer)clearInterval(autoTimer);if(interactionMode!=='auto')return;autoTimer=setInterval(handleNextItem,autoSpeed)}
            function stopAutoRotate(){if(autoTimer){clearInterval(autoTimer);autoTimer=null}}
            function handleCenterClick(){if(!showCenter)return;var nextIndex=(activeIndex+1)%total;handleItemChange(nextIndex)}
            function initChart(){if(typeof Chart==='undefined'){console.log('Waiting for Chart.js to load...');setTimeout(initChart,100);return}
            if(!canvas)return;var ctx=canvas.getContext('2d');var config={type:'doughnut',data:{labels:chartLabels,datasets:[{data:chartValues,backgroundColor:chartColors,borderColor:borderColor,borderWidth:borderWidth,hoverOffset:hoverOffset,}]},options:{responsive:!0,maintainAspectRatio:!0,cutout:cutout+'%',plugins:{legend:{display:!1},tooltip:{enabled:!1}},animation:animationEnabled?{duration:animationDuration,easing:'easeOutQuart',}:!1,onClick:function(event,activeElements){if(activeElements.length>0){var index=activeElements[0].index;handleItemChange(index)}}}};chart=new Chart(ctx,config);setTimeout(function(){createPoints();updatePointsActive();if(showInfoPanel)updateInfoPanel();if(showCenter)updateCenterContent();},100)}
            function init(){initChart();if(interactionMode==='auto'){startAutoRotate();container.addEventListener('mouseenter',function(){isInteracting=!0;stopAutoRotate()});container.addEventListener('mouseleave',function(){isInteracting=!1;startAutoRotate()})}
            if(centerContent&&showCenter){centerContent.addEventListener('click',handleCenterClick)}}
            init()})();
        </script>
        <?php
    }
}