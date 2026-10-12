<?php
/**
 * Pagination
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_pagination',
	array(
		'panel' => 'beyond_blog_theme_options',
		'title' => esc_html__( 'Pagination', 'beyond-blog' ),
	)
);

// Pagination - Enable Pagination.
$wp_customize->add_setting(
	'beyond_blog_enable_pagination',
	array(
		'default'           => true,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_enable_pagination',
		array(
			'label'    => esc_html__( 'Enable Pagination', 'beyond-blog' ),
			'section'  => 'beyond_blog_pagination',
			'settings' => 'beyond_blog_enable_pagination',
			'type'     => 'checkbox',
		)
	)
);

// Pagination - Pagination Type.
$wp_customize->add_setting(
	'beyond_blog_pagination_type',
	array(
		'default'           => 'numeric',
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_pagination_type',
	array(
		'label'           => esc_html__( 'Pagination Type', 'beyond-blog' ),
		'section'         => 'beyond_blog_pagination',
		'settings'        => 'beyond_blog_pagination_type',
		'active_callback' => 'beyond_blog_is_pagination_enabled',
		'type'            => 'select',
		'choices'         => array(
			'default' => __( 'Default (Older/Newer)', 'beyond-blog' ),
			'numeric' => __( 'Numeric', 'beyond-blog' ),
		),
	)
);
