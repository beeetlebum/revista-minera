<?php
/**
 * Theme setup and asset loading.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Configure theme supports.
 *
 * @return void
 */
function novaflow_setup() {
	load_theme_textdomain( 'novaflow', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-header',
		array(
			'default-image' => '',
			'width'         => 1600,
			'height'        => 420,
			'flex-width'    => true,
			'flex-height'   => true,
			'uploads'       => true,
			'header-text'   => false,
		)
	);
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'f4f5fb',
			'default-image' => '',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'novaflow' ),
			'utility' => __( 'Utility Menu', 'novaflow' ),
			'footer'  => __( 'Footer Menu', 'novaflow' ),
		)
	);

	add_image_size( 'novaflow-hero', 1100, 760, true );
	add_image_size( 'novaflow-card', 720, 480, true );
	add_image_size( 'novaflow-thumb', 320, 240, true );
	add_image_size( 'novaflow-leaderboard', 728, 90, true );
}
add_action( 'after_setup_theme', 'novaflow_setup' );

/**
 * Set the maximum content width.
 *
 * @return void
 */
function novaflow_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'novaflow_content_width', 860 );
}
add_action( 'after_setup_theme', 'novaflow_content_width', 0 );


/**
 * Keep category archive pagination at ten posts per page.
 *
 * @param WP_Query $query WordPress query instance.
 * @return void
 */
function novaflow_category_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_category() ) {
		return;
	}

	$query->set( 'posts_per_page', 10 );
}
add_action( 'pre_get_posts', 'novaflow_category_posts_per_page' );

/**
 * Register widget areas.
 *
 * @return void
 */
function novaflow_widgets_init() {
	$sidebars = array(
		'primary-sidebar' => __( 'Primary Sidebar', 'novaflow' ),
		'header-banner'   => __( 'Header Banner', 'novaflow' ),
		'footer-one'      => __( 'Footer Column One', 'novaflow' ),
		'footer-two'      => __( 'Footer Column Two', 'novaflow' ),
		'footer-three'    => __( 'Footer Column Three', 'novaflow' ),
	);

	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<section id="%1$s" class="widget nf-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'novaflow_widgets_init' );

/**
 * Keep search widgets out of the primary sidebar frontend.
 *
 * The widget remains registered and editable in WordPress; only its public
 * sidebar output is suppressed.
 *
 * @param array|false $instance Widget settings, or false to hide it.
 * @param WP_Widget   $widget   Widget object.
 * @param array       $args     Sidebar arguments.
 * @return array|false
 */
function novaflow_hide_primary_sidebar_search( $instance, $widget, $args ) {
	if ( 'primary-sidebar' !== ( $args['id'] ?? '' ) ) {
		return $instance;
	}

	$is_search_widget = 'search' === $widget->id_base;
	$is_search_block  = 'block' === $widget->id_base
		&& ! empty( $instance['content'] )
		&& has_block( 'core/search', $instance['content'] );

	return ( $is_search_widget || $is_search_block ) ? false : $instance;
}
add_filter( 'widget_display_callback', 'novaflow_hide_primary_sidebar_search', 10, 3 );

/**
 * Keep categories directly above archives in the primary sidebar.
 *
 * The widget IDs remain untouched so WordPress can continue managing both
 * blocks normally; only their public display order is adjusted.
 *
 * @param array $sidebars Registered sidebar widget IDs.
 * @return array
 */
function novaflow_order_primary_sidebar_sections( $sidebars ) {
	if ( is_admin() || empty( $sidebars['primary-sidebar'] ) || ! is_array( $sidebars['primary-sidebar'] ) ) {
		return $sidebars;
	}

	$block_widgets = get_option( 'widget_block', array() );
	$categories    = array();
	$archives      = array();

	foreach ( $sidebars['primary-sidebar'] as $widget_id ) {
		if ( ! preg_match( '/^block-(\d+)$/', $widget_id, $matches ) ) {
			continue;
		}

		$content = $block_widgets[ absint( $matches[1] ) ]['content'] ?? '';
		if ( has_block( 'core/categories', $content ) ) {
			$categories[] = $widget_id;
		} elseif ( has_block( 'core/archives', $content ) ) {
			$archives[] = $widget_id;
		}
	}

	if ( empty( $categories ) || empty( $archives ) ) {
		return $sidebars;
	}

	$moved   = array_merge( $categories, $archives );
	$ordered = $categories;

	foreach ( $sidebars['primary-sidebar'] as $widget_id ) {
		if ( in_array( $widget_id, $moved, true ) ) {
			continue;
		}

		$ordered[] = $widget_id;
	}

	$ordered = array_merge( $ordered, $archives );

	$sidebars['primary-sidebar'] = $ordered;
	return $sidebars;
}
add_filter( 'sidebars_widgets', 'novaflow_order_primary_sidebar_sections' );

/**
 * Return a file modification time for cache busting.
 *
 * @param string $relative_path Theme-relative file path.
 * @return string
 */
function novaflow_asset_version( $relative_path ) {
	$file = get_theme_file_path( $relative_path );

	return file_exists( $file ) ? (string) filemtime( $file ) : NOVAFLOW_VERSION;
}

/**
 * Load public assets.
 *
 * @return void
 */
function novaflow_enqueue_assets() {
	wp_register_script( 'novaflow-head', false, array(), NOVAFLOW_VERSION, false );
	wp_enqueue_script( 'novaflow-head' );
	wp_add_inline_script(
		'novaflow-head',
		"document.documentElement.classList.remove('nf-no-js');document.documentElement.classList.add('nf-js','nf-menu-pending');window.setTimeout(function(){document.documentElement.classList.remove('nf-menu-pending');},4000);try{if(window.localStorage.getItem('novaflow-color-mode')==='dark'){document.documentElement.classList.add('nf-dark-mode');}}catch(e){}if('scrollRestoration' in window.history){window.history.scrollRestoration='auto';}"
	);
	wp_enqueue_style(
		'novaflow-style',
		get_stylesheet_uri(),
		array(),
		novaflow_asset_version( 'style.css' )
	);
	wp_enqueue_style(
		'novaflow-main',
		get_theme_file_uri( 'assets/css/main.css' ),
		array( 'novaflow-style' ),
		novaflow_asset_version( 'assets/css/main.css' )
	);
	wp_enqueue_style(
		'novaflow-ui',
		get_theme_file_uri( 'assets/css/ui.css' ),
		array( 'novaflow-main', 'wp-block-library' ),
		novaflow_asset_version( 'assets/css/ui.css' )
	);
	wp_add_inline_style( 'novaflow-ui', novaflow_get_dynamic_css() );
	wp_enqueue_style(
		'novaflow-review-fixes',
		get_theme_file_uri( 'assets/css/review-fixes.css' ),
		array( 'novaflow-ui' ),
		novaflow_asset_version( 'assets/css/review-fixes.css' )
	);

	if ( is_rtl() ) {
		wp_enqueue_style(
			'novaflow-rtl',
			get_theme_file_uri( 'rtl.css' ),
			array( 'novaflow-ui' ),
			novaflow_asset_version( 'rtl.css' )
		);
	}

	wp_enqueue_script(
		'novaflow-main',
		get_theme_file_uri( 'assets/js/main.js' ),
		array(),
		novaflow_asset_version( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'novaflow-main',
		'novaflowData',
		array(
			'lightMode' => __( 'Light Mode', 'novaflow' ),
			'darkMode'  => __( 'Dark Mode', 'novaflow' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'novaflow_enqueue_assets' );
