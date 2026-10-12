<?php

// Home Page Customizer panel.
$wp_customize->add_panel(
	'uncut_news_frontpage_panel',
	array(
		'title'    => esc_html__( 'Frontpage Sections', 'uncut-news' ),
		'priority' => 140,
	)
);

$customizer_settings = array( 'highlights-news', 'banner' );

foreach ( $customizer_settings as $customizer ) {

	require get_template_directory() . '/inc/customizer-settings/frontpage-customizer/' . $customizer . '.php';

}
