<?php
/**
 * Footer copyright
 */

// Footer copyright
$wp_customize->add_section(
	'uncut_news_footer_section',
	array(
		'title' => esc_html__( 'Footer Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

$copyright_default = sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'uncut-news' ), '[the-year]', '[site-link]' );

// Footer copyright setting.
$wp_customize->add_setting(
	'uncut_news_copyright_txt',
	array(
		'default'           => $copyright_default,
		'sanitize_callback' => 'uncut_news_sanitize_html',
	)
);

$wp_customize->add_control(
	'uncut_news_copyright_txt',
	array(
		'label'   => esc_html__( 'Copyright text', 'uncut-news' ),
		'section' => 'uncut_news_footer_section',
		'type'    => 'textarea',
	)
);
