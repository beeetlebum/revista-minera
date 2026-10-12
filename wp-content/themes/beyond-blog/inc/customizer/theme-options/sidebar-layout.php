<?php
/**
 * Sidebar Option
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_sidebar_option',
	array(
		'title' => esc_html__( 'Layout', 'beyond-blog' ),
		'panel' => 'beyond_blog_theme_options',
	)
);

// Sidebar Option - Global Sidebar Position.
$wp_customize->add_setting(
	'beyond_blog_sidebar_position',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'beyond_blog_sidebar_position',
	array(
		'label'   => esc_html__( 'Global Sidebar Position', 'beyond-blog' ),
		'section' => 'beyond_blog_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'beyond-blog' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'beyond-blog' ),
		),
	)
);

// Sidebar Option - Post Sidebar Position.
$wp_customize->add_setting(
	'beyond_blog_post_sidebar_position',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'beyond_blog_post_sidebar_position',
	array(
		'label'   => esc_html__( 'Post Sidebar Position', 'beyond-blog' ),
		'section' => 'beyond_blog_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'beyond-blog' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'beyond-blog' ),
		),
	)
);

// Sidebar Option - Page Sidebar Position.
$wp_customize->add_setting(
	'beyond_blog_page_sidebar_position',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'beyond_blog_page_sidebar_position',
	array(
		'label'   => esc_html__( 'Page Sidebar Position', 'beyond-blog' ),
		'section' => 'beyond_blog_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'beyond-blog' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'beyond-blog' ),
		),
	)
);
