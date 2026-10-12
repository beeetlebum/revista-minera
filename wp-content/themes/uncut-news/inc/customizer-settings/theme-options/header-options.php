<?php
/**
 * Header Options
 */

// Header Options.
$wp_customize->add_section(
	'uncut_news_header_section',
	array(
		'title' => esc_html__( 'Header Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Topbar Section enable setting.
$wp_customize->add_setting(
	'uncut_news_enable_topbar_Section',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_topbar_Section',
		array(
			'label'    => esc_html__( 'Enable Topbar Section.', 'uncut-news' ),
			'settings' => 'uncut_news_enable_topbar_Section',
			'section'  => 'uncut_news_header_section',
			'type'     => 'checkbox',
		)
	)
);

// Header Button label setting.
$wp_customize->add_setting(
	'uncut_news_header_button_label',
	array(
		'default'           => __( 'Sign In', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_header_button_label',
	array(
		'label'    => esc_html__( 'Header Button Label', 'uncut-news' ),
		'section'  => 'uncut_news_header_section',
		'settings' => 'uncut_news_header_button_label',
		'type'     => 'text',
	)
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'uncut_news_header_button_label',
		array(
			'selector'            => '.header-button a',
			'settings'            => 'uncut_news_header_button_label',
			'container_inclusive' => false,
			'fallback_refresh'    => true,
		)
	);
}

// Header Button URL setting.
$wp_customize->add_setting(
	'uncut_news_header_button_url',
	array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'uncut_news_header_button_url',
	array(
		'label'    => esc_html__( 'Header Button Link', 'uncut-news' ),
		'section'  => 'uncut_news_header_section',
		'settings' => 'uncut_news_header_button_url',
		'type'     => 'url',
	)
);
