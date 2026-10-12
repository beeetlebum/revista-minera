<?php
/**
 * Sidebar settings
 */

$wp_customize->add_section(
	'uncut_news_sidebar_option',
	array(
		'title' => esc_html__( 'Sidebar Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Sidebar Option - Archive Sidebar Position.
$wp_customize->add_setting(
	'uncut_news_archive_sidebar_position',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'uncut_news_archive_sidebar_position',
	array(
		'label'   => esc_html__( 'Archive Sidebar Position', 'uncut-news' ),
		'section' => 'uncut_news_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'uncut-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'uncut-news' ),
		),
	)
);

// Sidebar Option - Post Sidebar Position.
$wp_customize->add_setting(
	'uncut_news_post_sidebar_position',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'uncut_news_post_sidebar_position',
	array(
		'label'   => esc_html__( 'Post Sidebar Position', 'uncut-news' ),
		'section' => 'uncut_news_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'uncut-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'uncut-news' ),
		),
	)
);

// Sidebar Option - Page Sidebar Position.
$wp_customize->add_setting(
	'uncut_news_page_sidebar_position',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'uncut_news_page_sidebar_position',
	array(
		'label'   => esc_html__( 'Page Sidebar Position', 'uncut-news' ),
		'section' => 'uncut_news_sidebar_option',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'uncut-news' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'uncut-news' ),
		),
	)
);
