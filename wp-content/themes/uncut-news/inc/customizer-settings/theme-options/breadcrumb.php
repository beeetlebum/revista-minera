<?php
/**
 * Breadcrumb settings
 */

$wp_customize->add_section(
	'uncut_news_breadcrumb_section',
	array(
		'title' => esc_html__( 'Breadcrumb Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Breadcrumb enable setting.
$wp_customize->add_setting(
	'uncut_news_breadcrumb_enable',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_breadcrumb_enable',
		array(
			'label'    => esc_html__( 'Enable breadcrumb.', 'uncut-news' ),
			'type'     => 'checkbox',
			'settings' => 'uncut_news_breadcrumb_enable',
			'section'  => 'uncut_news_breadcrumb_section',
		)
	)
);

// Breadcrumb - Separator.
$wp_customize->add_setting(
	'uncut_news_breadcrumb_separator',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '/',
	)
);

$wp_customize->add_control(
	'uncut_news_breadcrumb_separator',
	array(
		'label'           => esc_html__( 'Separator', 'uncut-news' ),
		'section'         => 'uncut_news_breadcrumb_section',
		'active_callback' => function( $control ) {
			return ( $control->manager->get_setting( 'uncut_news_breadcrumb_enable' )->value() );
		},
	)
);
