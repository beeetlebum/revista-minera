<?php
/**
 * Elementor Widget
 * @package eleblog
 * @since 1.0.0
 */

namespace Elementor;
class Eleblog_Category_Image extends Widget_Base {

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
		return 'eleblog_cateagory_image';
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
		return esc_html__( 'Category Image', 'ele-blog' );
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
			'section_title', [
				'label'   => esc_html__( 'Section Title', 'ele-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Category', 'ele-blog' )
			]
		);

		$this->add_control(
			'ppr', [
				'label'   => esc_html__( 'Amount Of Category To Display', 'ele-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 6
			]
		);

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
		$settings = $this->get_settings_for_display();


		?>

		<div class="text-center">
				<h2>This Widget Only Available In <a href="https://1.envato.market/ele-blog" target="_blank" style="text-decoration:underline;color:#222">Pro Version</a></h2>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Eleblog_Category_Image() );