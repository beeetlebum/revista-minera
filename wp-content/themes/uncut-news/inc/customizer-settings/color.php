<?php

/**
 * Color Options
 */

// Site tagline color setting.
$wp_customize->add_setting(
	'uncut_news_header_tagline',
	array(
		'default'           => '#000000',
		'sanitize_callback' => 'uncut_news_sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'uncut_news_header_tagline',
		array(
			'label'   => esc_html__( 'Site tagline Color', 'uncut-news' ),
			'section' => 'colors',
		)
	)
);
