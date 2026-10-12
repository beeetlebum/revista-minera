<?php
/**
 * Highlights Section
 */

$wp_customize->add_section(
	'uncut_news_highlights_section',
	array(
		'title' => esc_html__( 'Highlights Section', 'uncut-news' ),
		'panel' => 'uncut_news_frontpage_panel',
	)
);

// Highlights News section enable settings.
$wp_customize->add_setting(
	'uncut_news_highlights_news_section_enable',
	array(
		'default'           => false,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_highlights_news_section_enable',
		array(
			'label'    => esc_html__( 'Enable Highlights News Section', 'uncut-news' ),
			'type'     => 'checkbox',
			'settings' => 'uncut_news_highlights_news_section_enable',
			'section'  => 'uncut_news_highlights_section',
		)
	)
);

// Highlights News title settings.
$wp_customize->add_setting(
	'uncut_news_highlights_news_title',
	array(
		'default'           => __( 'Highlights News', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_highlights_news_title',
	array(
		'label'           => esc_html__( 'Section Title', 'uncut-news' ),
		'section'         => 'uncut_news_highlights_section',
		'active_callback' => 'uncut_news_if_highlights_news_enabled',
	)
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'uncut_news_highlights_news_title',
		array(
			'selector'            => '.news-highlights span.highlights-title',
			'settings'            => 'uncut_news_highlights_news_title',
			'container_inclusive' => false,
			'fallback_refresh'    => true,
			'render_callback'     => 'uncut_news_highlights_news_title_text_partial',
		)
	);
}

// Highlights news content type settings.
$wp_customize->add_setting(
	'uncut_news_highlights_news_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_highlights_news_content_type',
	array(
		'label'           => esc_html__( 'Content type:', 'uncut-news' ),
		'description'     => esc_html__( 'Choose where you want to render the content from.', 'uncut-news' ),
		'section'         => 'uncut_news_highlights_section',
		'type'            => 'select',
		'active_callback' => 'uncut_news_if_highlights_news_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'uncut-news' ),
			'category' => esc_html__( 'Category', 'uncut-news' ),
		),
	)
);

for ( $i = 1; $i <= 5; $i++ ) {
	// Highlights news post setting.
	$wp_customize->add_setting(
		'uncut_news_highlights_news_post_' . $i,
		array(
			'sanitize_callback' => 'uncut_news_sanitize_dropdown_pages',
		)
	);

	$wp_customize->add_control(
		'uncut_news_highlights_news_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Post %d', 'uncut-news' ), $i ),
			'section'         => 'uncut_news_highlights_section',
			'type'            => 'select',
			'choices'         => uncut_news_get_post_choices(),
			'active_callback' => 'uncut_news_highlights_news_section_content_type_post_enabled',
		)
	);

}

// Highlights news category setting.
$wp_customize->add_setting(
	'uncut_news_highlights_news_category',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_highlights_news_category',
	array(
		'label'           => esc_html__( 'Category', 'uncut-news' ),
		'section'         => 'uncut_news_highlights_section',
		'type'            => 'select',
		'choices'         => uncut_news_get_post_cat_choices(),
		'active_callback' => 'uncut_news_highlights_news_section_content_type_category_enabled',
	)
);

/*========================Active Callback==============================*/
function uncut_news_if_highlights_news_enabled( $control ) {
	return $control->manager->get_setting( 'uncut_news_highlights_news_section_enable' )->value();
}
function uncut_news_highlights_news_section_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_highlights_news_content_type' )->value();
	return uncut_news_if_highlights_news_enabled( $control ) && ( 'post' === $content_type );
}
function uncut_news_highlights_news_section_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_highlights_news_content_type' )->value();
	return uncut_news_if_highlights_news_enabled( $control ) && ( 'category' === $content_type );
}

/*========================Partial Refresh==============================*/
if ( ! function_exists( 'uncut_news_highlights_news_title_text_partial' ) ) :
	// Title.
	function uncut_news_highlights_news_title_text_partial() {
		return esc_html( get_theme_mod( 'uncut_news_highlights_news_title' ) );
	}
endif;
