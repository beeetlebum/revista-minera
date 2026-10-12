<?php
/**
 * Blogsum functions and definitions.
 *
 * Child theme for Newsum.
 *
 * @package Blogsum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BLOGSUM_VERSION' ) ) {
	define( 'BLOGSUM_VERSION', '1.0.3' );
}

/**
 * Setup child theme services & translation.
 */
function blogsum_setup() {
	// Make child theme available for translation.
	load_child_theme_textdomain( 'blogsum', get_stylesheet_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'blogsum_setup' );

/**
 * Enqueue parent and child stylesheets.
 */
function blogsum_enqueue_scripts() {
	// 1. Parent theme stylesheet
	wp_enqueue_style(
		'newsum-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		defined( 'NEWSUM_VERSION' ) ? NEWSUM_VERSION : '1.0.0'
	);

	// 2. Blogsum child theme stylesheet
	wp_enqueue_style(
		'blogsum-style',
		get_stylesheet_uri(),
		array( 'newsum-parent-style' ),
		BLOGSUM_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'blogsum_enqueue_scripts', 15 );

/**
 * Set Classic Newspaper (Tribune) as the default hero layout for Blogsum.
 *
 * If the user has not explicitly configured the hero layout in the Customizer,
 * default to the 'newspaper' (Tribune) layout.
 *
 * @param string|false $layout Saved theme mod value or parent default.
 * @return string
 */
function blogsum_default_hero_layout( $layout ) {
	$mods = get_theme_mods();
	if ( empty( $mods ) || ! isset( $mods['newsum_hero_layout'] ) ) {
		return 'newspaper';
	}
	return $layout;
}
add_filter( 'theme_mod_newsum_hero_layout', 'blogsum_default_hero_layout' );

/**
 * Adjust Customizer setting defaults for the Blogsum child theme.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function blogsum_customize_register( $wp_customize ) {
	$hero_setting = $wp_customize->get_setting( 'newsum_hero_layout' );
	if ( $hero_setting ) {
		$hero_setting->default = 'newspaper';
	}
}
add_action( 'customize_register', 'blogsum_customize_register', 25 );

/**
 * Register widget area for Blogsum.
 *
 * Ensures sidebar registration is declared and satisfies Theme Check requirements.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogsum_widgets_init() {
	if ( ! function_exists( 'newsum_widgets_init' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'blogsum' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here to appear in the sidebar.', 'blogsum' ),
				'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="sidebar-widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'blogsum_widgets_init' );

