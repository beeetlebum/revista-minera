<?php
/**
 * Footer Options
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_footer_options',
	array(
		'panel' => 'beyond_blog_theme_options',
		'title' => esc_html__( 'Footer Options', 'beyond-blog' ),
	)
);

// Footer Options - Copyright Text.
/* translators: 1: Year, 2: Site Title with home URL. */
$copyright_default = sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'beyond-blog' ), '[the-year]', '[site-link]' );
$wp_customize->add_setting(
	'beyond_blog_footer_copyright_text',
	array(
		'default'           => $copyright_default,
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	'beyond_blog_footer_copyright_text',
	array(
		'label'    => esc_html__( 'Copyright Text', 'beyond-blog' ),
		'section'  => 'beyond_blog_footer_options',
		'settings' => 'beyond_blog_footer_copyright_text',
		'type'     => 'textarea',
	)
);
