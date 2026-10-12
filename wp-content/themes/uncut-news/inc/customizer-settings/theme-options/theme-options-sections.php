<?php

/**
 * Theme Options.
 */

$wp_customize->add_panel(
	'uncut_news_theme_options_panel',
	array(
		'title'    => esc_html__( 'Theme Options', 'uncut-news' ),
		'priority' => 150,
	)
);

$theme_options = array( 'header-options', 'font-options', 'breadcrumb', 'archive-options', 'sidebar-layout', 'pagination', 'single-post', 'footer' );

foreach ( $theme_options as $customizer ) {
	require get_template_directory() . '/inc/customizer-settings/theme-options/' . $customizer . '.php';

}
