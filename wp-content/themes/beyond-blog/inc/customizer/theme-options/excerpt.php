<?php
/**
 * Excerpt
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_excerpt_options',
	array(
		'panel' => 'beyond_blog_theme_options',
		'title' => esc_html__( 'Excerpt', 'beyond-blog' ),
	)
);

// Excerpt - Excerpt Length.
$wp_customize->add_setting(
	'beyond_blog_excerpt_length',
	array(
		'default'           => 20,
		'sanitize_callback' => 'beyond_blog_sanitize_number_range',
		'validate_callback' => 'beyond_blog_validate_excerpt_length',
	)
);

$wp_customize->add_control(
	'beyond_blog_excerpt_length',
	array(
		'label'       => esc_html__( 'Excerpt Length (no. of words)', 'beyond-blog' ),
		'description' => esc_html__( 'Note: Min 1 & Max 100. Please input the valid number and save. Then refresh the page to see the change.', 'beyond-blog' ),
		'section'     => 'beyond_blog_excerpt_options',
		'settings'    => 'beyond_blog_excerpt_length',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 200,
			'step' => 1,
		),
	)
);
