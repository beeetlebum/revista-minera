<?php

/**
 * Header Options
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_header_options',
	array(
		'panel' => 'beyond_blog_theme_options',
		'title' => esc_html__( 'Header Options', 'beyond-blog' ),
	)
);

// Header Section - Enable Topbar Section.
$wp_customize->add_setting(
	'beyond_blog_enable_topbar_section',
	array(
		'default'           => true,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_enable_topbar_section',
		array(
			'label'    => esc_html__( 'Enable Topbar Section', 'beyond-blog' ),
			'section'  => 'beyond_blog_header_options',
			'settings' => 'beyond_blog_enable_topbar_section',
		)
	)
);

// Topbar Options - Custom Button.
$wp_customize->add_setting(
	'beyond_blog_header_custom_button',
	array(
		'default'           => __( 'Subscription', 'beyond-blog' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'beyond_blog_header_custom_button',
	array(
		'label'    => esc_html__( 'Custom Button', 'beyond-blog' ),
		'section'  => 'beyond_blog_header_options',
		'settings' => 'beyond_blog_header_custom_button',
		'type'     => 'text',
	)
);

// Topbar Options - Custom Button URL.
$wp_customize->add_setting(
	'beyond_blog_header_custom_button_url',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'beyond_blog_header_custom_button_url',
	array(
		'label'    => esc_html__( 'Custom Button URL', 'beyond-blog' ),
		'section'  => 'beyond_blog_header_options',
		'settings' => 'beyond_blog_header_custom_button_url',
		'type'     => 'url',
	)
);
