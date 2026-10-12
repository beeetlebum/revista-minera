<?php

/**
 * Uncut News functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Uncut News
 */

if ( !defined( 'UNCUT_NEWS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'UNCUT_NEWS_VERSION', '1.0.0' );
}
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function uncut_news_setup() {
	/*
	* Make theme available for translation.
	* Translations can be filed in the /languages/ directory.
	* If you're building a theme based on Uncut News, use a find and replace
	* to change 'uncut-news' to the name of your theme in all the template files.
	*/
	load_theme_textdomain( 'uncut-news', get_template_directory() . '/languages' );
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'register_block_style' );
	add_theme_support( 'register_block_pattern' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
	/*
	* Enable support for Post Thumbnails on posts and pages.
	*
	* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	*/
	add_theme_support( 'post-thumbnails' );
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'uncut-news' ),
		'social'  => esc_html__( 'Social Menu', 'uncut-news' ),
	) );
	/*
	* Switch default core markup for search form, comment form, and comments
	* to output valid HTML5.
	*/
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script'
	) );
	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'uncut_news_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'woocommerce' );
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, '3.0.0', '>=' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => esc_html__( 'small', 'uncut-news' ),
			'shortName' => esc_html__( 'S', 'uncut-news' ),
			'size'      => 12,
			'slug'      => 'small',
		),
		array(
			'name'      => esc_html__( 'regular', 'uncut-news' ),
			'shortName' => esc_html__( 'M', 'uncut-news' ),
			'size'      => 16,
			'slug'      => 'regular',
		),
		array(
			'name'      => esc_html__( 'larger', 'uncut-news' ),
			'shortName' => esc_html__( 'L', 'uncut-news' ),
			'size'      => 36,
			'slug'      => 'larger',
		),
		array(
			'name'      => esc_html__( 'huge', 'uncut-news' ),
			'shortName' => esc_html__( 'XL', 'uncut-news' ),
			'size'      => 48,
			'slug'      => 'huge',
		)
	) );
}

add_action( 'after_setup_theme', 'uncut_news_setup' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function uncut_news_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'uncut_news_content_width', 640 );
}

add_action( 'after_setup_theme', 'uncut_news_content_width', 0 );
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function uncut_news_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'uncut-news' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'uncut-news' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header Widgets Area', 'uncut-news' ),
		'id'            => 'header-widgets-area',
		'description'   => esc_html__( 'Add header widgets here.', 'uncut-news' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Widgets Area', 'uncut-news' ),
		'id'            => 'primary-widgets-area',
		'description'   => esc_html__( 'Add primary widgets here.', 'uncut-news' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Secondary Widgets Area', 'uncut-news' ),
		'id'            => 'secondary-widgets-area',
		'description'   => esc_html__( 'Add secondary widgets here.', 'uncut-news' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Above Footer Widgets Area', 'uncut-news' ),
		'id'            => 'above-footer-widgets-area',
		'description'   => esc_html__( 'Add above footer widgets here.', 'uncut-news' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	for ($i = 1; $i <= 4; $i++) {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area ', 'uncut-news' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => esc_html__( 'Add widgets here.', 'uncut-news' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}

add_action( 'widgets_init', 'uncut_news_widgets_init' );
// Fonts Register
require get_template_directory() . '/inc/custom-fonts.php';
/**
 * Enqueue scripts and styles.
 */
function uncut_news_scripts() {
	require get_template_directory() . '/inc/enqueue.php';
}

add_action( 'wp_enqueue_scripts', 'uncut_news_scripts' );
require get_template_directory() . '/inc/require.php';