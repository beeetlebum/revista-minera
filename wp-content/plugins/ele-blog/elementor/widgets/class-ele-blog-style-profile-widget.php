<?php
/**
 * Elementor Widget
 * @package eleblog
 * @since 1.0.0
 */

namespace Elementor;
class Eleblog_Social_Profile_Widget extends Widget_Base {

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
		return 'eleblog_social_profile';
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
		return esc_html__( 'Social Profile', 'ele-blog' );
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
				'label' => esc_html__( 'Generel Settings', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_title', [
				'label'   => esc_html__( 'Section Titlte', 'ele-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Join With Us', 'ele-blog' )
			]
		);

		$this->add_control( 'social_profile', [
			'label'       => esc_html__( 'Content', 'ele-blog' ),
			'type'        => Controls_Manager::REPEATER,
			'default'     => [
				[
					'icon'     => 'fa fa-facebook',
					'url'      => '#',
					'counter'  => '12000',
					'liketext' => esc_html__( 'Followers', 'ele-blog' ),
					'bg'       => '#3b5998',
				]
			],
			'fields'      => [
				[
					'name'        => 'icon',
					'label'       => esc_html__( 'Pick Your Social Icon ', 'ele-blog' ),
					'type'        => Controls_Manager::ICON,
					'default'     => 'fa fa-facebook',
					'label_block' => true,
				],
				array(
					'name'    => 'url',
					'label'   => esc_html__( 'Social Icon URL ', 'ele-blog' ),
					'type'    => Controls_Manager::URL,
					'default' => array(
						'url' => '#'
					),
				),
				[
					'name'    => 'counter',
					'label'   => esc_html__( 'Like/Followers Count', 'ele-blog' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '12000'
				],
				[
					'name'    => 'liketext',
					'label'   => esc_html__( 'Like/Followers Text', 'ele-blog' ),
					'type'    => Controls_Manager::TEXT,
					'default' => esc_html__( 'Followers', 'ele-blog' )
				],
				[
					'name'    => 'bg',
					'label'   => esc_html__( 'Icon Background Color', 'ele-blog' ),
					'type'    => Controls_Manager::COLOR,
					/*'selectors' => [
						'{{WRAPPER}} .social-area-list ul li .facebook i' => 'background-color: {{VALUE}};',
					],*/
					'default' => '#3b5998'
				],


			],
			'title_field' => "{{name}}"
		] );

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'style',
			[
				'label' => esc_html__( 'Style', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//section Title color
		$this->add_control(
			'section_title_color',
			[
				'label'     => esc_html__( 'Section Title Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#201654',
				'selectors' => [
					'{{WRAPPER}} .section-title .title' => 'color: {{VALUE}};',
				],
			]
		);

		//section typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'Section_title_typography',
				'label'          => esc_html__( 'Section Title Typography', 'ele-blog' ),
				'selector'       => '{{WRAPPER}} .section-title .title',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_size'   => [ 'default' => [ 'size' => 20 ] ],
					'font_weight' => [ 'default' => 500 ],
					'line_height' => [ 'default' => [ 'size' => 27 ] ],
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
		$settings       = $this->get_settings_for_display();
		?>
		<div class="text-center">
				<h2>This Widget Only Available In <a href="https://1.envato.market/ele-blog" target="_blank" style="text-decoration:underline;color:#222">Pro Version</a></h2>
			</div>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Eleblog_Social_Profile_Widget() );