<?php
/**
 * Blog / Archive Options
 */

$wp_customize->add_section(
	'uncut_news_archive_page_options',
	array(
		'title' => esc_html__( 'Blog / Archive Pages Options', 'uncut-news' ),
		'panel' => 'uncut_news_theme_options_panel',
	)
);

// Excerpt - Excerpt Length.
$wp_customize->add_setting(
	'uncut_news_excerpt_length',
	array(
		'default'           => 15,
		'sanitize_callback' => 'uncut_news_sanitize_number_range',
	)
);

$wp_customize->add_control(
	'uncut_news_excerpt_length',
	array(
		'label'       => esc_html__( 'Excerpt Length (no. of words)', 'uncut-news' ),
		'section'     => 'uncut_news_archive_page_options',
		'settings'    => 'uncut_news_excerpt_length',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 5,
			'max'  => 200,
			'step' => 1,
		),
	)
);

// Archive Column layout options.
$wp_customize->add_setting(
	'uncut_news_archive_grid_column_layout',
	array(
		'default'           => 'double-column',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_archive_grid_column_layout',
	array(
		'label'   => esc_html__( 'Column Layout', 'uncut-news' ),
		'section' => 'uncut_news_archive_page_options',
		'type'    => 'select',
		'choices' => array(
			'double-column' => __( 'Column 2', 'uncut-news' ),
			'triple-column' => __( 'Column 3', 'uncut-news' ),
		),
	)
);

// Enable archive page category setting.
$wp_customize->add_setting(
	'uncut_news_enable_archive_category',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_archive_category',
		array(
			'label'    => esc_html__( 'Enable Category', 'uncut-news' ),
			'settings' => 'uncut_news_enable_archive_category',
			'section'  => 'uncut_news_archive_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Enable archive page author setting.
$wp_customize->add_setting(
	'uncut_news_enable_archive_author',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_archive_author',
		array(
			'label'    => esc_html__( 'Enable Author', 'uncut-news' ),
			'settings' => 'uncut_news_enable_archive_author',
			'section'  => 'uncut_news_archive_page_options',
			'type'     => 'checkbox',
		)
	)
);

// Enable archive page date setting.
$wp_customize->add_setting(
	'uncut_news_enable_archive_date',
	array(
		'default'           => true,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);

$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_enable_archive_date',
		array(
			'label'    => esc_html__( 'Enable Date', 'uncut-news' ),
			'settings' => 'uncut_news_enable_archive_date',
			'section'  => 'uncut_news_archive_page_options',
			'type'     => 'checkbox',
		)
	)
);
