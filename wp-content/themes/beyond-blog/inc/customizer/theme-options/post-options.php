<?php
/**
 * Post Options
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_post_options',
	array(
		'title' => esc_html__( 'Post Options', 'beyond-blog' ),
		'panel' => 'beyond_blog_theme_options',
	)
);

// Post Options - Hide Date.
$wp_customize->add_setting(
	'beyond_blog_post_hide_date',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_post_hide_date',
		array(
			'label'   => esc_html__( 'Hide Date', 'beyond-blog' ),
			'section' => 'beyond_blog_post_options',
		)
	)
);

// Post Options - Hide Author.
$wp_customize->add_setting(
	'beyond_blog_post_hide_author',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_post_hide_author',
		array(
			'label'   => esc_html__( 'Hide Author', 'beyond-blog' ),
			'section' => 'beyond_blog_post_options',
		)
	)
);

// Post Options - Hide Category.
$wp_customize->add_setting(
	'beyond_blog_post_hide_category',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_post_hide_category',
		array(
			'label'   => esc_html__( 'Hide Category', 'beyond-blog' ),
			'section' => 'beyond_blog_post_options',
		)
	)
);

// Post Options - Hide Tag.
$wp_customize->add_setting(
	'beyond_blog_post_hide_tags',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_post_hide_tags',
		array(
			'label'   => esc_html__( 'Hide Tag', 'beyond-blog' ),
			'section' => 'beyond_blog_post_options',
		)
	)
);

// Post Options - Related Post Label.
$wp_customize->add_setting(
	'beyond_blog_post_related_post_label',
	array(
		'default'           => __( 'Related Posts', 'beyond-blog' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'beyond_blog_post_related_post_label',
	array(
		'label'    => esc_html__( 'Related Posts Label', 'beyond-blog' ),
		'section'  => 'beyond_blog_post_options',
		'settings' => 'beyond_blog_post_related_post_label',
		'type'     => 'text',
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'beyond_blog_post_related_post_label',
		array(
			'selector' => '.related-posts h2',
			'settings' => 'beyond_blog_post_related_post_label',
		)
	);
}
