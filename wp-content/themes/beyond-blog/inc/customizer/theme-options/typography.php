<?php
/**
 * Typography
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_typography',
	array(
		'panel' => 'beyond_blog_theme_options',
		'title' => esc_html__( 'Typography', 'beyond-blog' ),
	)
);

// Typography - Site Title Font.
$wp_customize->add_setting(
	'beyond_blog_site_title_font',
	array(
		'default'           => 'Work Sans',
		'sanitize_callback' => 'beyond_blog_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'beyond_blog_site_title_font',
	array(
		'label'    => esc_html__( 'Site Title Font Family', 'beyond-blog' ),
		'section'  => 'beyond_blog_typography',
		'settings' => 'beyond_blog_site_title_font',
		'type'     => 'select',
		'choices'  => beyond_blog_get_all_google_font_families(),
	)
);

// Typography - Site Description Font.
$wp_customize->add_setting(
	'beyond_blog_site_description_font',
	array(
		'default'           => 'Public Sans',
		'sanitize_callback' => 'beyond_blog_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'beyond_blog_site_description_font',
	array(
		'label'    => esc_html__( 'Site Description Font Family', 'beyond-blog' ),
		'section'  => 'beyond_blog_typography',
		'settings' => 'beyond_blog_site_description_font',
		'type'     => 'select',
		'choices'  => beyond_blog_get_all_google_font_families(),
	)
);

// Typography - Header Font.
$wp_customize->add_setting(
	'beyond_blog_header_font',
	array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'beyond_blog_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'beyond_blog_header_font',
	array(
		'label'    => esc_html__( 'Header Font Family', 'beyond-blog' ),
		'section'  => 'beyond_blog_typography',
		'settings' => 'beyond_blog_header_font',
		'type'     => 'select',
		'choices'  => beyond_blog_get_all_google_font_families(),
	)
);

// Typography - Body Font.
$wp_customize->add_setting(
	'beyond_blog_body_font',
	array(
		'default'           => 'Public Sans',
		'sanitize_callback' => 'beyond_blog_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'beyond_blog_body_font',
	array(
		'label'    => esc_html__( 'Body Font Family', 'beyond-blog' ),
		'section'  => 'beyond_blog_typography',
		'settings' => 'beyond_blog_body_font',
		'type'     => 'select',
		'choices'  => beyond_blog_get_all_google_font_families(),
	)
);
