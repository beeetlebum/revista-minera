<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
  exit;
}
/**
 * Define Theme Constants.
 */

defined('ESHF_COMPATIBILITY_TMPL') or define('ESHF_COMPATIBILITY_TMPL', 'covernews');

/**
 * CoverNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CoverNews
 */

if (!function_exists('covernews_setup')) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  /**
   *
   */
  /**
   *
   */
  function covernews_setup()
  {
    /*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CoverNews, use a find and replace
	 * to change 'covernews' to the name of your theme in all the template files.
	 */
    load_theme_textdomain('covernews', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
    add_theme_support('title-tag');

    /*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
    add_theme_support('post-thumbnails');

    // Add featured image sizes   
    
    add_image_size('covernews-featured', 1024, 0, false); // width, height, crop
    add_image_size('covernews-medium', 540, 340, true); // width, height, crop
    

    /*
     * Enable support for Post Formats on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/post-formats/
     */
    add_theme_support('post-formats', array('image', 'video', 'gallery'));

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'aft-primary-nav' => esc_html__('Primary Menu', 'covernews'),
      'aft-top-nav' => esc_html__('Top Menu', 'covernews'),
      'aft-social-nav' => esc_html__('Social Menu', 'covernews'),
      'aft-footer-nav' => esc_html__('Footer Menu', 'covernews'),
    ));

    /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('covernews_custom_background_args', array(
      'default-color' => 'f7f7f7',
      'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', array(
      'flex-width'  => true,
      'flex-height' => true,
    ));

    /** 
     * Add theme support for gutenberg block
     */
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('appearance-tools');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');
    add_theme_support('custom-line-height');
    add_theme_support('border');
    add_theme_support('link-color');
  }
endif;
add_action('after_setup_theme', 'covernews_setup');

/**
 * Enqueue local fonts based on theme options and translation settings.
 * Fully GDPR compliant and optimized to serve assets directly from the theme folder.
 *
 * @since 1.0.0
 */
function covernews_enqueue_local_fonts() {
	// Check if the user has selected Google Fonts (now served locally)
	$global_font_family_type = covernews_get_option( 'global_font_family_type' );
	if ( 'google' !== $global_font_family_type ) {
		return; // Don't load anything if using system fonts
	}

	// Use theme version for automatic cache-busting on updates
	$theme_version = wp_get_theme()->get( 'Version' );

	// Enqueue Source Sans CSS if not disabled by translators
	if ( 'off' !== _x( 'on', 'Source Sans Pro font: on or off', 'covernews' ) ) {
		wp_enqueue_style(
			'covernews-source-sans',
			get_theme_file_uri( '/assets/fonts/css/source-sans.css' ),
			array(),
			$theme_version,
			'all'
		);
	}

	// Enqueue Lato CSS if not disabled by translators
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'covernews' ) ) {
		wp_enqueue_style(
			'covernews-lato',
			get_theme_file_uri( '/assets/fonts/css/lato.css' ),
			array(),
			$theme_version,
			'all'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'covernews_enqueue_local_fonts' );



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function covernews_content_width()
{
  $GLOBALS['content_width'] = apply_filters('covernews_content_width', 640);
}
add_action('after_setup_theme', 'covernews_content_width', 0);


/**
 * Load Init for Hook files.
 */
require get_template_directory() . '/inc/custom-style.php';

/**
 * Enqueue scripts and styles.
 */
function covernews_scripts()
{

  $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
  // $fonts_url = covernews_fonts_url();
  $covernews_version =  wp_get_theme()->get('Version');

  wp_enqueue_style('covernews-icons', get_template_directory_uri() . '/assets/icons/style.css');
  wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');


  // if (!empty($fonts_url)) {
  //     wp_enqueue_style('covernews-google-fonts', $fonts_url, array(), null);
  // }

  if (class_exists('WooCommerce')) {
    wp_enqueue_style('covernews-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css');
  }

  wp_enqueue_style('covernews-style', get_template_directory_uri() . '/style' . $min . '.css', array(), $covernews_version);
  $global_font_family_type = covernews_get_option('global_font_family_type');
  if ($global_font_family_type == 'system') {
    wp_add_inline_style('covernews-style', covernews_custom_style());
  }

  wp_enqueue_script('covernews-navigation', get_template_directory_uri() . '/js/navigation.js', array(),  $covernews_version, true);
  wp_enqueue_script('covernews-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(),  $covernews_version, true);



  wp_enqueue_script('matchheight', get_template_directory_uri() . '/assets/jquery-match-height/jquery.matchHeight' . $min . '.js', array('jquery'), $covernews_version, true);



  $disable_sticky_header_option = covernews_get_option('disable_sticky_header_option');
  if ($disable_sticky_header_option == false) {
    wp_enqueue_script('covernews-fixed-header-script', get_template_directory_uri() . '/assets/fixed-header-script.js', array('jquery'),  $covernews_version, 1);
  }

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  if (is_page_template('tmpl-front-page.php') || is_front_page() || is_home()) {

    $show_main_news_section = covernews_get_option('show_main_news_section');
    $show_flash_news_section = covernews_get_option('show_flash_news_section');

    if ($show_main_news_section) {
      wp_enqueue_style('slick', get_template_directory_uri() . '/assets/slick/css/slick.css', array(), $covernews_version);
      wp_enqueue_script('slick', get_template_directory_uri() . '/assets/slick/js/slick' . $min . '.js', array('jquery'), $covernews_version, true);
      wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), $covernews_version, true);
    }

    if ($show_flash_news_section) {
      wp_enqueue_script('marquee', get_template_directory_uri() . '/assets/marquee/jquery.marquee.js', array('jquery'), $covernews_version, true);
    }
  }

  wp_enqueue_script('covernews-script', get_template_directory_uri() . '/admin-dashboard/dist/covernews_scripts.build.js', array('jquery'),  $covernews_version, 1);
  wp_register_script('jquery-cookie', get_template_directory_uri() . '/assets/jquery.cookie.js');
  wp_register_script('covernews-toggle-script', get_template_directory_uri() . '/assets/toggle-script.js',  array('jquery-cookie'), '', true);
  if (is_rtl() && is_child_theme()) {
    wp_enqueue_style(
      'covernews-rtl',
      get_template_directory_uri() . '/rtl.css',
      array(), // Load after other styles
      $covernews_version
    );
  }

  $show_footer_checkbox = covernews_get_option('athfb_show_checkbox_footer');
  $show_header_checkbox = covernews_get_option('athfb_show_checkbox_header');

  if ($show_header_checkbox) {
    wp_register_style(
      'covernews_header_builder',
      get_template_directory_uri() . '/assets/css/header-builder.css',
      array(),
      null,
      'all'
    );
    wp_enqueue_style('covernews_header_builder');
  }
  if ($show_footer_checkbox) {
    wp_register_style(
      'covernews_footer_builder',
      get_template_directory_uri() . '/assets/css/footer-builder.css',
      array(),
      null,
      'all'
    );
    wp_enqueue_style('covernews_footer_builder');
  }
}
add_action('wp_enqueue_scripts', 'covernews_scripts');



/**
 * Enqueue admin scripts and styles.
 *
 * @since CoverNews 1.0.0
 */
function covernews__admin_scripts($hook)
{
  if ('widgets.php' === $hook) {
    wp_enqueue_media();
    wp_enqueue_script('covernews-widgets', get_template_directory_uri() . '/assets/widgets.js', array('jquery'), '4.0.0', true);
  }

  wp_enqueue_style('covernews-notice', get_template_directory_uri() . '/assets/css/notice.css');
}
add_action('admin_enqueue_scripts', 'covernews__admin_scripts');



/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom Multi Author tags for this theme.
 */
require get_template_directory() . '/inc/multi-author.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-images.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/init.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/ocdi.php';



/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Descriptions on Header Menu
 * @author AF themes
 * @param string $item_output, HTML outputp for the menu item
 * @param object $item, menu item object
 * @param int $depth, depth in menu structure
 * @param object $args, arguments passed to wp_nav_menu()
 * @return string $item_output
 */
function covernews_header_menu_desc($item_output, $item, $depth, $args)
{

  if ('aft-primary-nav' == $args->theme_location  && $item->description)
    $item_output = str_replace('</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output);

  return $item_output;
}
add_filter('walker_nav_menu_start_el', 'covernews_header_menu_desc', 10, 4);

add_action('init', 'covernews_transltion_init');

function covernews_transltion_init()
{
  load_theme_textdomain('covernews', get_template_directory()  . '/languages');
}



//Header Footer builder
require_once get_template_directory() . '/inc/customizer/builder/options.php';
function athfb_load_files()
{
  // Only load in admin or customizer context
  if (!is_admin() && !is_customize_preview()) {
    return;
  }

  // Include files in the correct order
  require_once get_template_directory() . '/inc/customizer/builder/class-header-footer-builder.php';
  require_once get_template_directory() . '/inc/customizer/builder/class-header-footer-builder-control.php';
  require_once get_template_directory() . '/inc/customizer/builder/class-block-toggle.php';
}

// Load files when WordPress is ready and customizer classes are available
add_action('customize_register', 'athfb_load_files', 1);
function athfb_loadFiles()
{

  require_once get_template_directory() . '/inc/customizer/builder/builder-structure.php';
  require_once get_template_directory() . '/inc/customizer/builder/header-builder-structure.php';
  require_once get_template_directory() . '/inc/customizer/builder/footer-builder-structure.php';
}


add_action('init', 'athfb_loadFiles');
