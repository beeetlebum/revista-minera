<?php
/**
 * Elementor Widget
 * @package ele-blog
 * @since 1.0.0
 */

namespace Elementor;
class Eleblog_Post_Selector_Widget_Two extends Widget_Base {

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
		return 'eleblog_post_selector_two';
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
		return esc_html__( 'Post Selector Two', 'ele-blog' );
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
				'label' => esc_html__( 'General Settings', 'ele-blog' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'select_post', [
				'label'   => esc_html__( 'Select Post', 'ele-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => eleblog_select_post(),
			]
		);


		$this->add_control(
			'select_post_two', [
				'label'     => esc_html__( 'Second Post Select', 'ele-blog' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => eleblog_select_post(),

			]
		);

		$this->add_control(
			'select_post_third', [
				'label'     => esc_html__( 'Third Post Select', 'ele-blog' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => eleblog_select_post(),

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


		// Title color
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .single-post-wrap.style-overlay .details .title a' => 'color: {{VALUE}} !important;',
				],
			]
		);

		// Title color
		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Title Hover Color', 'ele-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2979ff',
				'selectors' => [
					'{{WRAPPER}} .single-post-wrap.style-overlay .details .title a:hover' => 'color: {{VALUE}} !important;',
				],
			]
		);


		//title typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'title_typography',
				'label'          => esc_html__( 'Title Typography', 'ele-blog' ),
				'selector'       => '{{WRAPPER}} .single-post-wrap .details .title',
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

Plugin::instance()->widgets_manager->register_widget_type( new Eleblog_Post_Selector_Widget_Two() );