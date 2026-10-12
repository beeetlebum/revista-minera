<?php
/**
 * Elementor Widget
 * @package ele blog
 * @since 1.0.0
 */

namespace Elementor;
class Eleblog_banner_widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Elementor widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'eleblog_banner_widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Elementor widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Magazine Banner', 'ele-blog' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Elementor widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eleblog-icon eicon-image-bold';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Elementor widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'ele-blog' ];
	}

	/**
	 * Register Elementor widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'settings_sticky',
			[
				'label' => esc_html__( 'Sticky Settings', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_sticky',
			[
				'label'        => esc_html__( 'Enable Sticky Post', 'ele-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ele-blog' ),
				'label_off'    => esc_html__( 'Hide', 'ele-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'select_post', [
				'label'     => esc_html__( 'Select Post', 'ele-blog' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => eleblog_select_post(),
				'condition' => [
					'enable_sticky' => [ 'yes' ],
				],
			]
		);

		$this->end_controls_section();

		/*
		* Display post
		*/
		$this->start_controls_section(
			'settings_display_post',
			[
				'label' => esc_html__( 'Display Post', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ppr', [
				'label'   => esc_html__( 'Amount of item to display', 'ele-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 4
			]
		);

		$this->add_control(
			'layout', [
				'label'   => esc_html__( 'layout', 'ele-blog' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'2' => '6',
					'3' => '4',
					'4' => '3',
					'6' => '2',
				),
				'default' => 3

			]
		);

		$this->add_control(
			'orderby', [
				'label'   => esc_html__( 'Order by', 'ele-blog' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'author' => esc_html__( 'Author', 'ele-blog' ),
					'title'  => esc_html__( 'Title', 'ele-blog' ),
					'date'   => esc_html__( 'Date', 'ele-blog' ),
					'rand'   => esc_html__( 'Random', 'ele-blog' ),
				),
				'default' => 'date'

			]
		);

		$this->add_control(
			'order', [
				'label'   => esc_html__( 'Order', 'ele-blog' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'desc' => esc_html__( 'DESC', 'ele-blog' ),
					'asc'  => esc_html__( 'ASC', 'ele-blog' ),
				),
				'default' => 'desc'

			]
		);

		$this->add_control(
			'select_cat', [
				'label'    => esc_html__( 'Select Category', 'ele-blog' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => ele_blog_post_category(),

			]
		);


		$this->add_control(
			'exclude_cat', [
				'label'    => esc_html__( 'Exclude Category', 'ele-blog' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => ele_blog_post_category(),
			]
		);

		$this->add_control(
			'select_tag', [
				'label'    => esc_html__( 'Select tag', 'ele-blog' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => ele_blog_post_tag(),
			]
		);

		$this->end_controls_section();


		//sticky style
		$this->start_controls_section(
			'sticky_style',
			[
				'label' => esc_html__( 'Sticky ', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//sticky background color
		$this->add_control(
			'sticky_bg_color',
			[
				'label'     => esc_html__( 'Image Background Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffbe00',
				'selectors' => [
					'{{WRAPPER}} .banner-inner .thumb.after-left-top:after' => 'background: {{VALUE}};',
				],
			]
		);


		//title typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'title_typography',
				'label'          => esc_html__( 'Title Typography', 'ele-blog' ),
				'selector'       => '{{WRAPPER}} h2',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_size'   => [ 'default' => [ 'size' => 40 ] ],
					'font_weight' => [ 'default' => 500 ],
					'line_height' => [ 'default' => [ 'size' => 54 ] ],
				],
			]
		);

		//content typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'content_typography',
				'label'          => esc_html__( 'Content Typography', 'ele-blog' ),
				'selector'       => '{{WRAPPER}} .banner-details p',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_size'   => [ 'default' => [ 'size' => 16 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 27 ] ],
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//title color
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} h2 a, .single-post-wrap.style-white .details .title' => 'color: {{VALUE}};',
				],
			]
		);

		//content color
		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Content Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .banner-inner .banner-details p' => 'color: {{VALUE}};',
				],
			]
		);

		//dater color
		$this->add_control(
			'date_color',
			[
				'label'     => esc_html__( 'Date Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .date, .single-post-wrap.style-white .post-meta-single li' => 'color: {{VALUE}};',
				],
			]
		);

		//title typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'common_title_typography',
				'label'          => esc_html__( 'Title Typography', 'ele-blog' ),
				'selector'       => '{{WRAPPER}} h6',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_size'   => [ 'default' => [ 'size' => 18 ] ],
					'font_weight' => [ 'default' => 500 ],
					'line_height' => [ 'default' => [ 'size' => 24 ] ],
				],
			]
		);

		$this->end_controls_section();

		//Backgroud
		$this->start_controls_section(
			'section_background',
			[
				'label' => esc_html__( 'Backgroud', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background',
				'label'    => __( 'Background', 'ele-blog' ),
				'types'    => [ 'classic' ],
				'selector' => '{{WRAPPER}} .bg-black',
			]
		);

		$this->end_controls_section();

		//button style
		$this->start_controls_section(
			'button_style',
			[
				'label' => esc_html__( 'Button Style', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//button color
		$this->add_control(
			'ele-blog-btn_color',
			[
				'label'     => esc_html__( 'Button Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .ele-blog-btn-blue' => 'color: {{VALUE}};',
				],
			]
		);

		//button bg color
		$this->add_control(
			'ele-blog-btn_bg_color',
			[
				'label'     => esc_html__( 'Button Background Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#283f62',
				'selectors' => [
					'{{WRAPPER}} .ele-blog-btn-blue' => 'background-color: {{VALUE}};',
				],
			]
		);

		//hover bg color
		$this->add_control(
			'ele-blog-btn_hover_bg_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#097bed',
				'selectors' => [
					'{{WRAPPER}} .ele-blog-btn-blue:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		 // more
		 $this->start_controls_section(
            'morefeautes_settings',
            [
                'label' => esc_html__( 'Go Pro ', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                <a target="_blank" href="https://1.envato.market/ele-blog">Go Pro</a>
               ',
            ]
        );

        $this->end_controls_section(); // More 

	}

	/**
	 * Render Elementor widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="text-center">
				<h2>This Widget Only Available In <a href="https://1.envato.market/ele-blog" target="_blank" style="text-decoration:underline;color:#222">Pro Version</a></h2>
			</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Eleblog_banner_widget() );