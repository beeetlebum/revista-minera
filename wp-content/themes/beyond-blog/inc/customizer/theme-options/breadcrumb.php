<?php
/**
 * Breadcrumb
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_breadcrumb',
	array(
		'title' => esc_html__( 'Breadcrumb', 'beyond-blog' ),
		'panel' => 'beyond_blog_theme_options',
	)
);

// Breadcrumb - Enable Breadcrumb.
$wp_customize->add_setting(
	'beyond_blog_enable_breadcrumb',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_enable_breadcrumb',
		array(
			'label'   => esc_html__( 'Enable Breadcrumb', 'beyond-blog' ),
			'section' => 'beyond_blog_breadcrumb',
		)
	)
);

// Breadcrumb - Separator.
$wp_customize->add_setting(
	'beyond_blog_breadcrumb_separator',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '/',
	)
);

$wp_customize->add_control(
	'beyond_blog_breadcrumb_separator',
	array(
		'label'           => esc_html__( 'Separator', 'beyond-blog' ),
		'active_callback' => 'beyond_blog_is_breadcrumb_enabled',
		'section'         => 'beyond_blog_breadcrumb',
	)
);
