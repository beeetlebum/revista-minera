<?php
namespace XproElementorAddons\Widget;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;
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
class Social_Chat extends Widget_Base {

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
		return 'xpro-social-chat';
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
		return __( 'Social Chat - All In One', 'xpro-elementor-addons' );
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
		return 'xi-chat-bubble xpro-widget-label';
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
		return array( 'whatsapp', 'chat', 'message', 'contact', 'social', 'messenger', 'telegram', 'viber', 'line', 'floating', 'widget' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_channels',
			array(
				'label' => __( 'Chat Channels', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'channel_type',
			array(
				'label'   => __( 'Channel Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'whatsapp'        => __( 'WhatsApp', 'xpro-elementor-addons' ),
					'messenger'       => __( 'Messenger', 'xpro-elementor-addons' ),
					'telegram'        => __( 'Telegram', 'xpro-elementor-addons' ),
					'line'            => __( 'Line Messenger', 'xpro-elementor-addons' ),
					'viber'           => __( 'Viber', 'xpro-elementor-addons' ),
					'wechat'          => __( 'WeChat', 'xpro-elementor-addons' ),
					'instagram'       => __( 'Instagram', 'xpro-elementor-addons' ),
					'twitter'         => __( 'Twitter', 'xpro-elementor-addons' ),
					'linkedin'        => __( 'LinkedIn', 'xpro-elementor-addons' ),
					'skype'           => __( 'Skype', 'xpro-elementor-addons' ),
					'call'            => __( 'Phone Call', 'xpro-elementor-addons' ),
					'sms'             => __( 'SMS', 'xpro-elementor-addons' ),
					'email'           => __( 'Email', 'xpro-elementor-addons' ),
					'custom'          => __( 'Custom URL', 'xpro-elementor-addons' ),
				),
				'default' => 'whatsapp',
			)
		);

		$repeater->add_control(
			'channel_label',
			array(
				'label'       => __( 'Tooltip Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'WhatsApp', 'xpro-elementor-addons' ),
				'placeholder' => __( 'WhatsApp', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'icon_type',
			array(
				'label'   => __( 'Icon Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'default' => array(
						'title' => __( 'Default Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star',
					),
					'icon' => array(
						'title' => __( 'Custom Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-icon-box',
					),
					'image' => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
				),
				'default' => 'default',
				'toggle'  => false,
			)
		);

		/**
		 * Show on Desktop
		 */
		$repeater->add_control(
			'show_on_desktop',
			array(
				'label'        => __( 'Show on Desktop', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes', // Enabled by default
			)
		);

		/**
		 * Show on Mobile
		 */
		$repeater->add_control(
			'show_on_mobile',
			array(
				'label'        => __( 'Show on Mobile', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes', // Enabled by default
			)
		);

		$repeater->add_control(
			'custom_icon',
			array(
				'label' => __( 'Select Icon', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::ICONS,
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'custom_image',
			array(
				'label' => __( 'Upload Image', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		// WhatsApp Specific Controls
		$repeater->add_control(
			'chat_type',
			array(
				'label'       => __( 'Chat Type', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'private' => __( 'Private', 'xpro-elementor-addons' ),
					'group'   => __( 'Group', 'xpro-elementor-addons' ),
				),
				'default'     => 'private',
				'label_block' => true,
				'condition'   => array(
					'channel_type' => 'whatsapp',
				),
			)
		);

		$repeater->add_control(
			'number',
			array(
				'label'       => __( 'Phone Number', 'xpro-elementor-addons' ),
				'description' => __( 'Example: +1123456789', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'channel_type' => array('whatsapp', 'call', 'sms', 'viber'),
					'chat_type' => 'private',
				),
			)
		);

		$repeater->add_control(
			'group_id',
			array(
				'label'       => __( 'Group ID', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Enter your WhatsApp group ID', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'condition'   => array(
					'channel_type' => 'whatsapp',
					'chat_type' => 'group',
				),
			)
		);

		// Messenger URL
		$repeater->add_control(
			'messenger_url',
			array(
				'label'       => __( 'Facebook Page URL', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-facebook-page.com', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => 'messenger',
				),
			)
		);

		// Telegram
		$repeater->add_control(
			'telegram_username',
			array(
				'label'       => __( 'Telegram Username', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '@username', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => 'telegram',
				),
			)
		);

		// Line
		$repeater->add_control(
			'line_url',
			array(
				'label'       => __( 'Line URL', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://line.me/ti/p/~your_id', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => 'line',
				),
			)
		);

		// Email
		$repeater->add_control(
			'email_address',
			array(
				'label'       => __( 'Email Address', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'your@email.com', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => 'email',
				),
			)
		);

		// Custom URL
		$repeater->add_control(
			'custom_url',
			array(
				'label'       => __( 'Custom URL', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-custom-link.com', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => 'custom',
				),
			)
		);

		// Social Media Usernames
		$repeater->add_control(
			'social_username',
			array(
				'label'       => __( 'Username', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'yourusername', 'xpro-elementor-addons' ),
				'condition'   => array(
					'channel_type' => array('instagram', 'twitter', 'linkedin'),
				),
			)
		);

		$repeater->add_control(
			'link_new_tab',
			array(
				'label'   => __( 'Open in New Tab', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'channels',
			array(
				'label'       => __( 'Chat Channels', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'channel_type' => 'messenger',
						'channel_label' => 'Messenger',
					),
					array(
						'channel_type' => 'whatsapp',
						'channel_label' => 'WhatsApp',
					),
					array(
						'channel_type' => 'call',
						'channel_label' => 'Call',
					),
				),
				'title_field' => '{{{ channel_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => __( 'Settings', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Main Button Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Contact Us', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Contact Us', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'xpro_fab_trigger',
			array(
				'label'   => __('Trigger Type', 'xpro-elementor-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'click',
				'frontend_available' => true,
				'options' => array(
					'click' => __('Click', 'xpro-elementor-addons'),
					'hover' => __('Hover', 'xpro-elementor-addons'),
				),
			)
		);

		$this->add_control(
			'position_horizontal',
			array(
				'label'   => __( 'Horizontal Position', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'  => __( 'Left', 'xpro-elementor-addons' ),
					'right' => __( 'Right', 'xpro-elementor-addons' ),
				),
				'default' => 'right',
			)
		);

		$this->add_responsive_control(
			'horizontal_offset',
			array(
				'label'      => __( 'Horizontal Offset', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-widget' => '{{position_horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position_vertical',
			array(
				'label'   => __( 'Vertical Position', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top'    => __( 'Top', 'xpro-elementor-addons' ),
					'bottom' => __( 'Bottom', 'xpro-elementor-addons' ),
				),
				'default' => 'bottom',
			)
		);

		$this->add_responsive_control(
			'vertical_offset',
			array(
				'label'      => __( 'Vertical Offset', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-widget' => '{{position_vertical.VALUE}}: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'enable_tooltip',
			array(
				'label'        => __( 'Enable Tooltip', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'    => __( 'No', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',

				'selectors_dictionary' => array(
					'yes' => 'flex',
					''    => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel .xpro-social-chat-tooltip' => 'display: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-chat-main-btn .xpro-social-chat-tooltip' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_placement',
			array(
				'label'     => __( 'Tooltip Placement', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'left'   => __( 'Left', 'xpro-elementor-addons' ),
					'right'  => __( 'Right', 'xpro-elementor-addons' ),
					'top'    => __( 'Top', 'xpro-elementor-addons' ),
					'bottom' => __( 'Bottom', 'xpro-elementor-addons' ),
				),
				'default'   => 'left',
				'condition' => array(
					'enable_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'more_options',
			array(
				'label' => esc_html__( 'Animations', 'xpro-elementor-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label'   => __( 'Hover Animation', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'                  => __( 'None', 'xpro-elementor-addons' ),
					'2d-transition'         => __( '2D', 'xpro-elementor-addons' ),
					'background-transition' => __( 'Background', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'hover_2d_css_animation',
			array(
				'label'     => __( 'Animation Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hvr-grow',
				'options'   => array(
					'hvr-grow'         => __( 'Grow', 'xpro-elementor-addons' ),
					'hvr-shrink'       => __( 'Shrink', 'xpro-elementor-addons' ),
					'hvr-pulse'        => __( 'Pulse', 'xpro-elementor-addons' ),
					'hvr-bounce-in'    => __( 'Bounce In', 'xpro-elementor-addons' ),
					'hvr-rotate'       => __( 'Rotate', 'xpro-elementor-addons' ),
					'hvr-float'        => __( 'Float', 'xpro-elementor-addons' ),
					'hvr-buzz'         => __( 'Buzz', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'hover_animation' => '2d-transition',
				),
			)
		);

		$this->add_control(
			'hover_background_css_animation',
			array(
				'label'     => __( 'Animation Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hvr-sweep-to-right',
				'options'   => array(
					'hvr-sweep-to-right' => __( 'Sweep To Right', 'xpro-elementor-addons' ),
					'hvr-sweep-to-left'  => __( 'Sweep To Left', 'xpro-elementor-addons' ),
					'hvr-radial-out'     => __( 'Radial Out', 'xpro-elementor-addons' ),
					'hvr-shutter-in-horizontal' => __( 'Shutter In Horizontal', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'hover_animation' => 'background-transition',
				),
			)
		);

		$this->add_control(
			'chat_opacity_duration',
			array(
				'label' => __( 'Opacity Duration (seconds)', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range' => array(
					's' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default' => array(
					'size' => 2,
					'unit' => 's',
				),
			)
		);

		$this->add_control(
			'chat_transform_duration',
			array(
				'label' => __( 'Transform Duration (seconds)', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range' => array(
					's' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default' => array(
					'size' => 3,
					'unit' => 's',
				),
			)
		);

		$this->add_control(
			'chat_visibility_duration',
			array(
				'label' => __( 'Visibility Duration (seconds)', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 's' ),
				'range' => array(
					's' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default' => array(
					'size' => 3,
					'unit' => 's',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel' =>
						'transition: opacity {{chat_opacity_duration.SIZE}}{{chat_opacity_duration.UNIT}} ease, ' .
						'transform {{chat_transform_duration.SIZE}}{{chat_transform_duration.UNIT}} ease, ' .
						'visibility {{chat_visibility_duration.SIZE}}{{chat_visibility_duration.UNIT}} ease;',
				),
			)
		);

		$this->end_controls_section();

		// Style: Main Button
		$this->start_controls_section(
			'section_main_button_style',
			array(
				'label' => __( 'Main Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'main_button_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
					'em' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					),
					'vw' => array(
						'min' => 1,
						'max' => 20,
					),
					'vh' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 55,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-main-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'main_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
					'em' => array(
						'min'  => 0.5,
						'max'  => 5,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0.5,
						'max'  => 5,
						'step' => 0.1,
					),
					'vw' => array(
						'min' => 0.5,
						'max' => 10,
					),
					'vh' => array(
						'min' => 0.5,
						'max' => 10,
					),
				),
				'default' => array(
					'size' => 23,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-main-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'main_button_tabs' );

		$this->start_controls_tab(
			'main_button_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'main_button_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-main-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_button_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-social-chat-main-btn',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'main_button_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'main_button_color_hover',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-main-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_button_background_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-social-chat-main-btn:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_button_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-social-chat-main-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'main_button_border',
				'selector'  => '{{WRAPPER}} .xpro-social-chat-main-btn',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'main_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-main-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Channel Buttons
		$this->start_controls_section(
			'section_channel_buttons_style',
			array(
				'label' => __( 'Channel Buttons', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'channel_button_size',
			array(
				'label'      => __( ' Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 55,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-channel' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'channel_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 23,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-channel i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-social-chat-channel svg' => 'width: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->add_control(
			'channel_button_icon_colors',
			array(
				'label'     => __( 'Icon Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffff',
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-chat-channel svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'channel_image_width',
			array(
				'label' => __( 'Image Width', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default' => array(
					'size' => 30,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel img' => 'width: {{SIZE}}{{UNIT}}; border-radius:50px;',
				),
			)
		);

		$this->add_responsive_control(
			'channel_image_height',
			array(
				'label' => __( 'Image Height', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default' => array(
					'size' => 30,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'channel_image_object_fit',
			array(
				'label' => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SELECT,
				'condition' => array(
					'channel_image_height[size]!' => '',
				),
				'options' => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-channel img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'channel_button_spacing',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-channel' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'channel_button_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-social-chat-channel',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'channel_button_border',
				'selector'  => '{{WRAPPER}} .xpro-social-chat-channel',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'channel_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-channel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Tooltip
		$this->start_controls_section(
			'section_tooltip_style',
			array(
				'label' => __( 'Tooltip', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_background',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				// 'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'background: {{VALUE}};',
					// '{{WRAPPER}} .xpro-social-chat-tooltip::after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				// 'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tooltip_typography',
				'selector' => '{{WRAPPER}} .xpro-social-chat-tooltip',
			)
		);

		$this->add_control(
			'tooltip_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Tooltip Width
		$this->add_responsive_control(
			'tooltip_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 90,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'width: {{SIZE}}{{UNIT}}; display:flex; justify-content:center; align-items:center;',
				),
			)
		);

		// Tooltip Height
		$this->add_responsive_control(
			'tooltip_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 34,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-chat-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get default icon for channel
	 */
	protected function get_default_icon($channel_type) {
		$icons = array(
			'whatsapp'        => 'fab fa-whatsapp',
			'messenger'       => 'fab fa-facebook-messenger',
			'telegram'        => 'fab fa-telegram',
			'line'            => 'fab fa-line',
			'viber'           => 'fab fa-viber',
			'wechat'          => 'fab fa-weixin',
			'instagram'       => 'fab fa-instagram',
			'twitter'         => 'fab fa-twitter',
			'linkedin'        => 'fab fa-linkedin',
			'skype'           => 'fab fa-skype',
			'call'            => 'fas fa-phone',
			'sms'             => 'fas fa-sms',
			'email'           => 'fas fa-envelope',
			'custom'          => 'fas fa-link',
		);

		return $icons[$channel_type] ?? $icons['whatsapp'];
	}

	/**
	 * Generate URL for the selected channel
	 */
	protected function get_channel_url($channel) {
		$channel_type = $channel['channel_type'];
		
		switch($channel_type) {
			case 'whatsapp':
				return $this->get_whatsapp_url($channel);
				
			case 'messenger':
				return $channel['messenger_url']['url'] ?? 'https://m.me/yourpage';
				
			case 'telegram':
				$username = $channel['telegram_username'] ?? '';
				return 'https://t.me/' . ltrim($username, '@');
				
			case 'line':
				return $channel['line_url']['url'] ?? 'https://line.me/ti/p/~your_id';
				
			case 'viber':
				$number = $channel['number'] ?? '';
				return 'viber://chat?number=' . preg_replace('/[^0-9+]/', '', $number);
				
			case 'wechat':
				return 'weixin://dl/chat';
				
			case 'instagram':
				$username = $channel['social_username'] ?? '';
				return 'https://instagram.com/' . $username;
				
			case 'twitter':
				$username = $channel['social_username'] ?? '';
				return 'https://twitter.com/' . $username;
				
			case 'linkedin':
				$username = $channel['social_username'] ?? '';
				return 'https://linkedin.com/in/' . $username;
				
			case 'skype':
				return 'skype:echo123?call';
				
			case 'call':
				$number = $channel['number'] ?? '';
				return 'tel:' . preg_replace('/[^0-9+]/', '', $number);
				
			case 'sms':
				$number = $channel['number'] ?? '';
				return 'sms:' . preg_replace('/[^0-9+]/', '', $number);
				
			case 'email':
				$email = $channel['email_address'] ?? '';
				return 'mailto:' . sanitize_email($email);
				
			case 'custom':
				return $channel['custom_url']['url'] ?? '#';
				
			default:
				return '#';
		}
	}

	/**
	 * Generate WhatsApp URL
	 */
	protected function get_whatsapp_url($channel) {

		$id = ( 'private' === $channel['chat_type'] ) ? $channel['number'] : $channel['group_id'];
		
		if (empty($id)) return '#';
		
		$is_mobile = wp_is_mobile();

		if ( ( 'private' === $channel['chat_type'] && ! $is_mobile ) || 'group' === $channel['chat_type'] ) {
			$browser = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'Firefox';
			$is_firefox = ( false !== strpos( $browser, 'Firefox' ) ) ? 'web' : 'chat';
			$prefix = ( 'private' === $channel['chat_type'] ) ? 'web' : $is_firefox;
			$suffix = ( 'private' === $channel['chat_type'] ) ? 'send?phone=' : '';
			return sprintf( 'https://%s.whatsapp.com/%s%s', $prefix, $suffix, $id );
		} else {
			$id = str_replace( '+', '', $id );
			return sprintf( 'https://wa.me/%s', $id );
		}

	}

	/**
	 * Render Social Chat widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'social-chat/layout/frontend.php';

	}
}