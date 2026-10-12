<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package CoverNews
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function covernews_body_classes($classes)
{
  // Adds a class of hfeed to non-singular pages.
  if (!is_singular()) {
    $classes[] = 'hfeed';
  }

  global $post;

  $global_layout = covernews_get_option('global_content_layout');
  if (!empty($global_layout)) {
    $classes[] = $global_layout;
  }

  $archive_class = covernews_get_option('archive_layout');
  if (!empty($archive_class)) {
    $classes[] = $archive_class;
  }

  $sticky_header = covernews_get_option('disable_sticky_header_option');
  if ($sticky_header ==  false) {
    $sticky_header_class = covernews_get_option('sticky_header_direction');
    $classes[] = $sticky_header_class . ' aft-sticky-header';
  }

  $sticky_sidebar = covernews_get_option('frontpage_sticky_sidebar');
  if ($sticky_sidebar) {
    $classes[] = 'aft-sticky-sidebar';
  }

  $global_site_mode = covernews_get_option('global_site_mode');


  if (isset($_COOKIE["covernews-stored-site-mode"])) {

    $classes[] = $_COOKIE["covernews-stored-site-mode"];
  } else {
    if (!empty($global_site_mode)) {

      $classes[] = $global_site_mode;
    }
  }
  //$classes[] = $global_site_mode;


  $global_alignment = covernews_get_option('global_content_alignment');
  $page_layout = $global_alignment;
  $disable_class = '';
  $frontpage_content_status = covernews_get_option('frontpage_content_status');
  if (1 != $frontpage_content_status) {
    $disable_class = 'disable-default-home-content';
  }

  // Check if single.
  if (is_singular()) {
    $post_options = get_post_meta($post->ID, 'covernews-meta-content-alignment', true);
    if (!empty($post_options)) {
      $page_layout = $post_options;
    } else {
      $page_layout = $global_alignment;
    }
  }

  $container_mode = covernews_get_option('select_container_mode');
  if ($container_mode) {
    $classes[] = 'aft-container-' . $container_mode;
  }

  $section_mode = covernews_get_option('select_main_banner_section_mode');
  if ($section_mode) {
    $classes[] = 'aft-main-banner-' . $section_mode;
  }

  if (is_front_page()) {
    $frontpage_layout = covernews_get_option('frontpage_content_alignment');
    if (!empty($frontpage_layout)) {
      $page_layout = $frontpage_layout;
    }
  }

  if (!is_front_page() && is_home()) {
    $page_layout = $global_alignment;
  }

  // Check if single.
  if ($post && is_singular()) {
    $global_single_content_mode = covernews_get_option('global_single_content_mode');
    $post_single_content_mode = get_post_meta($post->ID, 'covernews-meta-content-mode', true);
    if (!empty($post_single_content_mode)) {
      $classes[] = $post_single_content_mode;
    } else {
      $classes[] = $global_single_content_mode;
    }
  }


  $select_header_image_mode = covernews_get_option('select_header_image_mode');
  if ($select_header_image_mode == 'full') {
    $classes[] = 'header-image-full';
  } else {
    $classes[] = 'header-image-default';
  }


  if ($page_layout == 'align-content-right') {
    if (is_front_page() && !is_home()) {
      if (is_page_template('tmpl-front-page.php')) {
        if (is_active_sidebar('home-sidebar-widgets')) {
          $classes[] = 'align-content-right';
        } else {
          $classes[] = 'full-width-content';
        }
      } else {
        if (is_active_sidebar('sidebar-1')) {
          $classes[] = 'align-content-right';
        } else {
          $classes[] = 'full-width-content';
        }
      }
    } else {
      if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'align-content-right';
      } else {
        $classes[] = 'full-width-content';
      }
    }
  } elseif ($page_layout == 'full-width-content') {
    $classes[] = 'full-width-content';
  } else {
    if (is_front_page() && !is_home()) {
      if (is_page_template('tmpl-front-page.php')) {
        if (is_active_sidebar('home-sidebar-widgets')) {
          $classes[] = 'align-content-left';
        } else {
          $classes[] = 'full-width-content';
        }
      } else {
        if (is_active_sidebar('sidebar-1')) {
          $classes[] = 'align-content-left ';
        } else {
          $classes[] = 'full-width-content';
        }
      }
    } else {
      if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'align-content-left aft-and';
      } else {
        $classes[] = 'full-width-content';
      }
    }
  }
  return $classes;
}

add_filter('body_class', 'covernews_body_classes');


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function covernews_pingback_header()
{
  if (is_singular() && pings_open()) {
    echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
  }
}

add_action('wp_head', 'covernews_pingback_header');


/**
 * Returns no image url.
 *
 * @since  CoverNews 1.0.0
 */
if (!function_exists('covernews_post_format')) :
  function covernews_post_format($post_id)
  {
    $post_format = get_post_format($post_id);
    switch ($post_format) {
      case "image":
        echo "<div class='em-post-format'><i class='fas fa-camera'></i></div>";
        break;
      case "video":
        echo "<div class='em-post-format'><i class='fas fa-video'></i></div>";

        break;
      case "gallery":
        echo "<div class='em-post-format'><i class='fas fa-images'></i></div>";
        break;
      default:
        echo "";
    }
  }

endif;


if (!function_exists('covernews_get_block')) :
  /**
   *
   * @since CoverNews 1.0.0
   *
   * @param null
   * @return null
   *
   */
  function covernews_get_block($block = 'grid')
  {

    get_template_part('inc/hooks/blocks/block-post', $block);
  }
endif;

if (!function_exists('covernews_archive_title')) :
  /**
   *
   * @since CoverNews 1.0.0
   *
   * @param null
   * @return null
   *
   */

  function covernews_archive_title($title)
  {
    if (is_category()) {
      $title = single_cat_title('', false);
    } elseif (is_tag()) {
      $title = single_tag_title('', false);
    } elseif (is_author()) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
      $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
      $title = single_term_title('', false);
    }

    return $title;
  }

endif;
add_filter('get_the_archive_title', 'covernews_archive_title');

/* Display Breadcrumbs */
if (!function_exists('covernews_get_breadcrumb')) :

  /**
   * Simple breadcrumb.
   *
   * @since 1.0.0
   */
  function covernews_get_breadcrumb()
  {

    $enable_breadcrumbs = covernews_get_option('enable_breadcrumb');

    if (1 != $enable_breadcrumbs) {
      return;
    }
    // Bail if Home Page.
    if (is_front_page() || is_home()) {
      return;
    }

    $select_breadcrumbs = covernews_get_option('select_breadcrumb_mode');

?>
    <div class="em-breadcrumbs font-family-1 covernews-breadcrumbs">
      <div class="row">
        <?php
        if ((function_exists('yoast_breadcrumb')) && ($select_breadcrumbs == 'yoast')) {
          yoast_breadcrumb();
        } elseif ((function_exists('rank_math_the_breadcrumbs')) && ($select_breadcrumbs == 'rankmath')) {
          rank_math_the_breadcrumbs();
        } elseif ((function_exists('bcn_display')) && ($select_breadcrumbs == 'bcn')) {
          bcn_display();
        } else {
          covernews_get_breadcrumb_trail();
        }
        ?>
      </div>
    </div>
<?php


  }

endif;
add_action('covernews_action_get_breadcrumb', 'covernews_get_breadcrumb');

/* Display Breadcrumbs */
if (!function_exists('covernews_get_breadcrumb_trail')) :

  /**
   * Simple excerpt length.
   *
   * @since 1.0.0
   */

  function covernews_get_breadcrumb_trail()
  {

    if (!function_exists('breadcrumb_trail')) {

      /**
       * Load libraries.
       */

      require_once get_template_directory() . '/lib/breadcrumb-trail/breadcrumb-trail.php';
    }

    $breadcrumb_args = array(
      'container' => 'div',
      'show_browse' => false,
    );

    breadcrumb_trail($breadcrumb_args);
  }

endif;

/**
 * Front-page main banner section layout
 */
if (!function_exists('covernews_front_page_main_section')) {

  function covernews_front_page_main_section()
  {

    $hide_on_blog = covernews_get_option('disable_main_banner_on_blog_archive');

    if ($hide_on_blog) {
      if (is_front_page() && !is_home()) {
        do_action('covernews_action_front_page_main_section_1');
      }
    } else {
      if (is_front_page() || is_home()) {
        do_action('covernews_action_front_page_main_section_1');
      }
    }
  }
}
add_action('covernews_action_front_page_main_section', 'covernews_front_page_main_section');



/* Display Breadcrumbs */
if (!function_exists('covernews_excerpt_length')) :

  /**
   * Simple excerpt length.
   *
   * @since 1.0.0
   */

  function covernews_excerpt_length($length)
  {

    if (is_admin()) {
      return $length;
    }

    return 15;
  }

endif;
add_filter('excerpt_length', 'covernews_excerpt_length', 999);


/* Display Breadcrumbs */
if (!function_exists('covernews_excerpt_more')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function covernews_excerpt_more($more)
  {
    return '...';
  }

endif;

add_filter('excerpt_more', 'covernews_excerpt_more');


/* Display Pagination */
if (!function_exists('covernews_numeric_pagination')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function covernews_numeric_pagination()
  {
    the_posts_pagination(array(
      'mid_size' => 3,
      'prev_text' => __('Previous', 'covernews'),
      'next_text' => __('Next', 'covernews'),
    ));
  }

endif;




/* Display Breadcrumbs */
if (!function_exists('covernews_toggle_lazy_load')) :

  /**
   * Simple excerpt more.
   *
   * @since 1.0.0
   */
  function covernews_toggle_lazy_load()
  {
    $covernews_lazy_load = covernews_get_option('global_toggle_image_lazy_load_setting');
    if ($covernews_lazy_load == 'disable') {
      add_filter('wp_lazy_loading_enabled', '__return_false');
    }
  }

endif;

add_action('wp_loaded', 'covernews_toggle_lazy_load');

add_action('init', 'covernews_disable_wp_emojis');

function covernews_disable_wp_emojis()
{
  $disable_emoji = covernews_get_option('disable_wp_emoji');
  if ($disable_emoji) {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'covernews_disable_emojis_tinymce');
    add_filter('wp_resource_hints', 'covernews_disable_emojis_remove_dns_prefetch', 10, 2);
  }
}

function covernews_disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, array('wpemoji'));
  }
  return array();
}

function covernews_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
  if ('dns-prefetch' === $relation_type) {
    $emoji_svg_url = 'https://s.w.org/images/core/emoji/';
    foreach ($urls as $key => $url) {
      if (strpos($url, $emoji_svg_url) !== false) {
        unset($urls[$key]);
      }
    }
  }
  return $urls;
}

if (!function_exists('athfb_add_custom_admin_menu')) {

  function athfb_add_custom_admin_menu($wp_admin_bar)
  {
    // Show only for admins (change capability if needed)
    if (!current_user_can('manage_options')) {
      return;
    }

    // Parent menu icon (optional)
    $afthemes_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px" height="50px" viewBox="0 0 50 49" version="1.1">
    <g id="surface1">
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 22.332031 2.984375 C 18.230469 3.53125 14.289062 5.273438 11.003906 7.976562 C 7.398438 10.933594 4.695312 15.402344 3.71875 20.03125 C 3.273438 22.113281 3.089844 24.902344 3.308594 26.105469 L 3.398438 26.570312 L 4.136719 25.5 C 5.496094 23.511719 6.90625 22.234375 8.808594 21.25 C 12.152344 19.507812 16.191406 19.433594 19.675781 21.042969 C 21.144531 21.722656 22.132812 22.417969 23.28125 23.550781 C 24.800781 25.058594 25.839844 26.851562 26.457031 29.078125 C 26.71875 29.980469 26.730469 30.34375 26.789062 37.324219 C 26.863281 45.164062 26.851562 44.992188 27.496094 45.554688 C 28.175781 46.136719 29.519531 46.34375 30.238281 45.980469 C 30.695312 45.75 31.199219 45.1875 31.386719 44.75 C 31.484375 44.46875 31.523438 40.671875 31.496094 29.394531 C 31.484375 15.804688 31.496094 14.332031 31.683594 13.625 C 32.621094 10.070312 36.253906 8.023438 39.882812 9.011719 C 40.378906 9.15625 40.824219 9.230469 40.871094 9.179688 C 41.007812 9.035156 38.996094 7.292969 37.683594 6.429688 C 35.574219 5.042969 33.496094 4.128906 30.941406 3.46875 C 28.410156 2.824219 24.976562 2.628906 22.332031 2.984375 Z M 22.332031 2.984375 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 36.375 12.859375 C 35.820312 13.140625 35.4375 13.527344 35.191406 14.0625 C 34.980469 14.515625 34.957031 14.988281 34.957031 21.152344 L 34.957031 27.761719 L 37.859375 27.761719 C 41.019531 27.761719 41.21875 27.800781 41.738281 28.445312 C 42.082031 28.882812 42.269531 29.613281 42.167969 30.164062 C 42.058594 30.734375 41.355469 31.5 40.785156 31.660156 C 40.527344 31.722656 39.144531 31.78125 37.710938 31.78125 L 35.078125 31.78125 L 35.078125 44.082031 L 35.796875 43.726562 C 36.191406 43.53125 37.019531 43.046875 37.625 42.632812 C 42.785156 39.234375 46.183594 33.546875 46.949219 27.03125 C 47.257812 24.328125 46.925781 20.738281 46.121094 18.339844 C 45.554688 16.644531 44.34375 14.125 44.082031 14.125 C 44.035156 14.125 44.046875 14.332031 44.109375 14.574219 C 44.171875 14.832031 44.21875 15.367188 44.21875 15.78125 C 44.21875 16.476562 44.195312 16.546875 43.761719 16.960938 C 43.21875 17.511719 42.578125 17.707031 41.871094 17.546875 C 40.785156 17.304688 40.402344 16.804688 40.007812 15.125 C 39.796875 14.175781 39.675781 13.894531 39.304688 13.492188 C 39.070312 13.222656 38.710938 12.933594 38.523438 12.835938 C 38.066406 12.601562 36.84375 12.617188 36.375 12.859375 Z M 36.375 12.859375 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 13.464844 23.878906 C 10.585938 24.160156 7.917969 26.082031 6.78125 28.714844 C 6.152344 30.136719 5.964844 32.535156 6.359375 34.035156 C 6.964844 36.359375 8.609375 38.355469 10.746094 39.367188 C 12.128906 40.027344 12.945312 40.207031 14.5 40.195312 C 17.859375 40.195312 20.675781 38.441406 22.046875 35.496094 C 22.628906 34.265625 22.789062 33.488281 22.789062 31.964844 C 22.777344 29.6875 22 27.800781 20.441406 26.242188 C 18.625 24.414062 16.230469 23.609375 13.464844 23.878906 Z M 13.464844 23.878906 "/>
    <path style=" stroke:none;fill-rule:nonzero;fill:currentColor;fill-opacity:1;" d="M 22 41.644531 C 20.527344 42.730469 18.949219 43.449219 16.871094 43.957031 L 15.527344 44.289062 L 16.378906 44.675781 C 18.847656 45.820312 23.097656 46.808594 23.097656 46.246094 C 23.097656 45.992188 22.714844 41.339844 22.691406 41.242188 C 22.679688 41.195312 22.367188 41.378906 22 41.644531 Z M 22 41.644531 "/>
    </g>
    </svg>';

    $parent_title  = $afthemes_icon . esc_html__('CoverNews Options', 'covernews');

    // Add parent menu
    $wp_admin_bar->add_menu(array(
      'id'    => 'covernews-menu',
      'title' => $parent_title,
      'href'  => admin_url('admin.php?page=covernews-pro'),
      'meta'  => array(
        'title'  => esc_attr__('CoverNews Options', 'covernews'), // Tooltip
        // 'target' => '_blank', // Open in new tab
      ),
    ));

    // Define submenu items
    $submenu_items = array(

      
      array(
        'id'    => 'starter-sites-submenu',
        'title' => __('Starter Sites', 'covernews'),
        'href'  => admin_url('admin.php?page=starter-sites'),
      ),
      array(
        'id'    => 'header-submenu',
        'title' => __('Header Builder', 'covernews'),
        'href'  => admin_url('customize.php?autofocus[section]=header_builder'),
      ),
      array(
        'id'    => 'banner-submenu',
        'title' => __('Front-page Banner', 'covernews'),
        'href'  => admin_url('customize.php?autofocus[section]=frontpage_main_banner_section_settings'),
      ),
      array(
        'id'    => 'footer-submenu',
        'title' => __('Footer Builder', 'covernews'),
        'href'  => admin_url('customize.php?autofocus[section]=footer_builder'),
      ),
      array(
        'id'    => 'af-speed-submenu',
        'title' => __('Speed Booster', 'covernews'),
        'href'  => admin_url('admin.php?page=af-speed'),
      ),
      array(
        'id'    => 'af-growth-submenu',
        'title' => __('Growth Tools', 'covernews'),
        'href'  => admin_url('admin.php?page=af-growth'),
      ),
      array(
        'id'    => 'upgrade-submenu',
        'title' => __('Upgrade to Pro', 'covernews'),
        'href'  => "https://afthemes.com/products/covernews-pro",
      )
    );

    // Loop and add submenu items
    foreach ($submenu_items as $item) {
      $wp_admin_bar->add_menu(array(
        'id'     => $item['id'],
        'title'  => esc_html($item['title']),
        'href'   => $item['href'],
        'parent' => 'covernews-menu',
        'meta'   => array(
          'title'  => $item['title'],
          // 'target' => '_blank', // Open in new tab
        ),
      ));
    }
  }

  // Hook into admin bar menu
  add_action('admin_bar_menu', 'athfb_add_custom_admin_menu', 100);
  add_action('admin_enqueue_scripts', 'covernews_admin_bar_styling');
  add_action('wp_enqueue_scripts', 'covernews_admin_bar_styling'); // Also in frontend if admin bar visible

  function covernews_admin_bar_styling()
  {
    if (is_admin_bar_showing()) {
      wp_add_inline_style(
        'admin-bar',
        '
        /* Base parent menu style */
        #wpadminbar #wp-admin-bar-covernews-menu > .ab-item {
            background-color: #007ACC !important;
            color: #fff !important;
            font-weight: bold;
            // border-radius: 3px;
            padding: 0 6px;
        }

        /* Hover, focus, active, and "hover" class from WP */
        #wpadminbar #wp-admin-bar-covernews-menu > .ab-item:hover,
        #wpadminbar #wp-admin-bar-covernews-menu.hover > .ab-item,
        #wpadminbar #wp-admin-bar-covernews-menu > .ab-item:focus,
        #wpadminbar #wp-admin-bar-covernews-menu > .ab-item:active {
            background-color: #006eb8 !important;
            color: #fff !important;
        }

        /* Visited state (rarely used in admin bar) */
        #wpadminbar #wp-admin-bar-covernews-menu > .ab-item:visited {
            color: #fff !important;
        }

        /* Icon alignment */
        // #wpadminbar #wp-admin-bar-covernews-menu img {
        //     vertical-align: middle;
        //     margin-right: 4px;
        //     color: #fff !important;
        // }
        #wpadminbar #wp-admin-bar-covernews-menu svg {
          height:20px;
          width: 20px;
          fill: #fff;
          color: #fff;
          vertical-align: middle;
          margin-right: 5px;
      }
      #wpadminbar #wp-admin-bar-covernews-menu:hover svg {
          fill: #ffcc00;
      }
          #wpadminbar ul li#wp-admin-bar-upgrade-submenu{
            background-color: #039562;
            margin-bottom: 0;
        }

        #wpadminbar ul li#wp-admin-bar-upgrade-submenu a{
            color:#ffffff;
            text-transform: uppercase;
            padding: 3px 10px;
        }
      
        '
      );
    }
  }
}

function covernews_wp_page_menu_custom_fallback($args) {
  // 1. Fetch the default pages list
  $pages = wp_list_pages(array(
      'title_li' => '',
      'echo'     => 0,
  ));

  // 2. Build the exact same structure as your assigned menu
  $output =  '<' . $args['container'] . ' class="' . $args['container_class'] . '">';
  $output .= '<ul id="' . $args['menu_id'] . '" class="menu" >';
  $output .= $pages;
  $output .= '</ul>';
  $output .= '</' . $args['container'] . '>';

  echo $output;
}
