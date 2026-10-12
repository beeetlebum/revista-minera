<?php
/**
 * Uncut News Theme Customizer
 *
 * @package Uncut News
 */

// upgrade to pro.
require get_template_directory() . '/inc/customizer-settings/upgrade-to-pro/class-customize.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function uncut_news_customize_register( $wp_customize ) {

	// Custom Controls.
	require get_template_directory() . '/inc/customizer-settings/custom-controller.php';

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'uncut_news_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'uncut_news_customize_partial_blogdescription',
			)
		);
	}

	// Header text display setting.
	$wp_customize->add_setting(
		'uncut_news_header_text_display',
		array(
			'default'           => true,
			'sanitize_callback' => 'uncut_news_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'uncut_news_header_text_display',
		array(
			'section' => 'title_tagline',
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Display Site Title and Tagline', 'uncut-news' ),
		)
	);

	// Archive Page title.
	$wp_customize->add_setting(
		'uncut_news_archive_page_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'uncut_news_archive_page_title',
		array(
			'label'           => esc_html__( 'Archive Posts Title', 'uncut-news' ),
			'section'         => 'static_front_page',
			'active_callback' => 'uncut_news_is_latest_posts',
		)
	);

	// Abort if selective refresh is not available.
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'uncut_news_archive_page_title',
			array(
				'selector'            => '.home .site-main h3.section-title',
				'settings'            => 'uncut_news_archive_page_title',
				'container_inclusive' => false,
				'fallback_refresh'    => true,
				'render_callback'     => 'uncut_news_archive_page_title_text_partial',
			)
		);
	}

	/*========================Partial Refresh==============================*/
	if ( ! function_exists( 'uncut_news_archive_page_title_text_partial' ) ) :
		// Archive Page Title.
		function uncut_news_archive_page_title_text_partial() {
			return esc_html( get_theme_mod( 'uncut_news_archive_page_title' ) );
		}
	endif;

	// Enable Homepage Content.
	$wp_customize->add_setting(
		'uncut_news_enable_frontpage_content',
		array(
			'default'           => false,
			'sanitize_callback' => 'uncut_news_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'uncut_news_enable_frontpage_content',
		array(
			'label'           => esc_html__( 'Enable Homepage Content', 'uncut-news' ),
			'description'     => esc_html__( 'Check to enable the selected homepage content on the front page.', 'uncut-news' ),
			'section'         => 'static_front_page',
			'type'            => 'checkbox',
			'active_callback' => 'uncut_news_is_static_homepage_enabled',
		)
	);

	// Color Customizer Setting.
	require get_template_directory() . '/inc/customizer-settings/color.php';

	// frontpage customizer section.
	require get_template_directory() . '/inc/customizer-settings/frontpage-customizer/customizer-sections.php';

	// theme options customizer section.
	require get_template_directory() . '/inc/customizer-settings/theme-options/theme-options-sections.php';

}
add_action( 'customize_register', 'uncut_news_customize_register' );

// Sanitize callback.
require get_template_directory() . '/inc/sanitize-callback.php';

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function uncut_news_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function uncut_news_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function uncut_news_customize_preview_js() {
	wp_enqueue_script( 'uncut-news-customizer', get_template_directory_uri() . '/assets/js/customizer.min.js', array( 'customize-preview' ), UNCUT_NEWS_VERSION, true );
}
add_action( 'customize_preview_init', 'uncut_news_customize_preview_js' );

/**
 * Binds JS handlers for Customizer controls.
 */
function uncut_news_customize_control_js() {
	wp_enqueue_style( 'uncut-news-customize-style', get_template_directory_uri() . '/assets/css/customize-controls.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'news-flow-fontawesome-controls-css', get_template_directory_uri() . '/assets/css/fontawesome.min.css', array(), '6.7.2' );
	wp_enqueue_script( 'uncut-news-customize-control', get_template_directory_uri() . '/assets/js/customize-control.min.js', array( 'jquery', 'customize-controls' ), '1.0.0', true );
	$localized_data = array(
		'refresh_msg' => esc_html__( 'Refresh the page after Save and Publish.', 'uncut-news' ),
		'reset_msg'   => esc_html__( 'Warning!!! This will reset all the settings. Refresh the page after Save and Publish to reset all.', 'uncut-news' ),
	);
	wp_localize_script( 'uncut-news-customize-control', 'localized_data', $localized_data );
}
add_action( 'customize_controls_enqueue_scripts', 'uncut_news_customize_control_js' );
