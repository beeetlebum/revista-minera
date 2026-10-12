<?php
/**
 * Pagination setting
 */

// Pagination setting.
$wp_customize->add_section(
	'uncut_news_pagination',
	array(
		'title' => esc_html__( 'Pagination', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Pagination enable setting.
$wp_customize->add_setting(
	'uncut_news_pagination_enable',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_pagination_enable',
		array(
			'label'    => esc_html__( 'Enable Pagination.', 'uncut-news' ),
			'settings' => 'uncut_news_pagination_enable',
			'section'  => 'uncut_news_pagination',
			'type'     => 'checkbox',
		)
	)
);

// Pagination - Pagination Style.
$wp_customize->add_setting(
	'uncut_news_pagination_type',
	array(
		'default'           => 'numeric',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_pagination_type',
	array(
		'label'           => esc_html__( 'Pagination Style', 'uncut-news' ),
		'section'         => 'uncut_news_pagination',
		'type'            => 'select',
		'choices'         => array(
			'default' => __( 'Default (Older/Newer)', 'uncut-news' ),
			'numeric' => __( 'Numeric', 'uncut-news' ),
		),
		'active_callback' => 'uncut_news_pagination_enabled',
	)
);

/*========================Active Callback==============================*/
function uncut_news_pagination_enabled( $control ) {
	return $control->manager->get_setting( 'uncut_news_pagination_enable' )->value();
}
