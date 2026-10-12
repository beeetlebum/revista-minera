<?php
/**
 * Single Post Options
 */

$wp_customize->add_section(
	'uncut_news_single_page_options',
	array(
		'title' => esc_html__( 'Single Post Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Enable single post category setting.
$wp_customize->add_setting(
	'uncut_news_enable_single_category',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_single_category',
		array(
			'label'    => esc_html__( 'Enable Category', 'uncut-news' ),
			'settings' => 'uncut_news_enable_single_category',
			'section'  => 'uncut_news_single_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Enable single post author setting.
$wp_customize->add_setting(
	'uncut_news_enable_single_author',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_single_author',
		array(
			'label'    => esc_html__( 'Enable Author', 'uncut-news' ),
			'settings' => 'uncut_news_enable_single_author',
			'section'  => 'uncut_news_single_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Enable single post date setting.
$wp_customize->add_setting(
	'uncut_news_enable_single_date',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_single_date',
		array(
			'label'    => esc_html__( 'Enable Date', 'uncut-news' ),
			'settings' => 'uncut_news_enable_single_date',
			'section'  => 'uncut_news_single_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Enable single post tag setting.
$wp_customize->add_setting(
	'uncut_news_enable_single_tag',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_single_tag',
		array(
			'label'    => esc_html__( 'Enable Post Tag', 'uncut-news' ),
			'settings' => 'uncut_news_enable_single_tag',
			'section'  => 'uncut_news_single_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Single post related Posts title label.
$wp_customize->add_setting(
	'uncut_news_related_posts_title',
	array(
		'default'           => __( 'Related Posts', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_related_posts_title',
	array(
		'label'    => esc_html__( 'Related Posts Title', 'uncut-news' ),
		'section'  => 'uncut_news_single_page_options',
		'settings' => 'uncut_news_related_posts_title',
	)
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'uncut_news_related_posts_title',
		array(
			'selector'            => '.theme-wrapper h2.related-title',
			'settings'            => 'uncut_news_related_posts_title',
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		)
	);
}
