<?php

if (!function_exists('athfb_render_header_builder')) {
  function athfb_render_header_builder()
  {

    $header_data = get_option('header_builder_data', athfb_get_default_header_structure());
    $header_structure = json_decode($header_data, true);
    if (!$header_structure) {
      return;
    }

    wp_enqueue_style('covernews_builder');
?>
    <div class="header-layout-1">
      <header id="masthead" class="site-header">
        <?php

        athfb_render_builder_structure($header_structure, 'header');
        ?>
      </header>
    </div>
  <?php
  }
}

/**
 * Render Footer Builder
 */
if (!function_exists('athfb_render_footer_builder')) {

  function athfb_render_footer_builder()
  {

    $footer_data = get_option('footer_builder_data', athfb_get_default_footer_structure());

    $footer_structure = json_decode($footer_data, true);
    if (!$footer_structure) {
      return;
    } ?>
    <footer class="site-footer">

      <?php athfb_render_footer_structure($footer_structure, 'footer');
      ?>
    </footer>
  <?php
  }
}


if (!function_exists('athfb_render_element')) {
  function athfb_render_element($element, $context)
  {
    if (!isset($element['type']) || !isset($element['id'])) {
      return;
    }

    $element_type = $element['type'];
    $element_id = $element['id'];


    switch ($element_type) {
      case 'header_logo':
        athfb_render_logo_element($element_id, $context);
        break;
      case 'header_navigation':
        athfb_render_navigation_element($element_id, $context);
        break;

      case 'header_promotion':
        athfb_render_promotion_element($element_id, $context);
        break;
      case 'header_off_canvas':
        athfb_render_header_off_canvas_element($element_id, $context);
        break;
      case 'header_date';
        athfb_render_header_date_element($element_id, $context);
        break;

      case 'header_top_navigation';
        athfb_render_top_menu_element($element_id, $context);
        break;

      case 'header_site_mode';
        athfb_render_header_site_mode_element($element_id, $context);
        break;

      case 'header_html';
        athfb_render_header_html_element($element_id, $context);
        break;
      case 'header_search':

        athfb_render_search_element($element_id, $context);
        break;
      case 'header_button':

        athfb_render_button_element($element_id, $context);
        break;

      case 'header_widget_1':
        athfb_render_widget_element($element_id, $context, 1);
        break;

      case 'header_widget_2':
        athfb_render_widget_element($element_id, $context, 2);
        break;

      case 'header_widget_3':
        athfb_render_widget_element($element_id, $context, 3);
        break;
      case 'header_social_icons':
        athfb_render_social_icons_element($element_id, $context);
        break;
      //Footer Part
      case 'footer_navigation':
        athfb_render_footer_navigation_element($element_id, $context);
        break;

      case 'footer_date';
        athfb_render_header_date_element($element_id, $context);
        break;
      case 'footer_site_mode';
        athfb_render_header_site_mode_element($element_id, $context);
        break;

      case 'footer_html';
        athfb_render_header_html_element($element_id, $context);
        break;

      case 'footer_search':
        athfb_render_search_element($element_id, $context);
        break;

      case 'footer_button':

        athfb_render_button_element($element_id, $context);
        break;

      case 'footer_social_icons': // Added specific case for footer social icons
        athfb_render_social_icons_element($element_id, $context); // Re-use existing render function if logic is same
        break;
      case 'footer_copyright':
        athfb_render_copyright_element($element_id);
        break;
      case 'footer_widget_1':
        athfb_render_footer_widget_element($element_id, $context, 1);
        break;
      case 'footer_widget_2':
        athfb_render_footer_widget_element($element_id, $context, 2);
        break;
      case 'footer_widget_3':
        athfb_render_footer_widget_element($element_id, $context, 3);
        break;
        //
    }
  }
}


/**
 * Render Logo Element
 */
if (!function_exists('athfb_render_logo_element')) {
  function athfb_render_logo_element($element_id)
  { ?>

    <div class="site-branding">
      <?php
      the_custom_logo();
      if (is_front_page() || is_home()) : ?>
        <h1 class="site-title font-family-1">
          <a href="<?php echo esc_url(home_url('/')); ?>"
            rel="home"><?php bloginfo('name'); ?></a>
        </h1>
      <?php else : ?>
        <p class="site-title font-family-1">
          <a href="<?php echo esc_url(home_url('/')); ?>"
            rel="home"><?php bloginfo('name'); ?></a>
        </p>
      <?php endif; ?>

      <?php
      $description = get_bloginfo('description', 'display');
      if ($description || is_customize_preview()) : ?>
        <p class="site-description"><?php echo esc_html($description); ?></p>
      <?php
      endif; ?>
    </div>
    <?php }
}

/**
 * Render Offcanvas
 */

if (!function_exists('athfb_render_header_off_canvas_element')) {
  function athfb_render_header_off_canvas_element($element_id, $context)
  {
    //if (!covernews_is_amp()) {
    if (is_active_sidebar('express-off-canvas-panel')) :
      wp_enqueue_style('sidr');
      wp_enqueue_script('sidr');
    ?>
      <div class="off-canvas-panel">

        <span class="offcanvas">
          <a href="#" class="offcanvas-nav" role="button" aria-label="Open off-canvas menu" aria-expanded="false" aria-controls="offcanvas-menu">
            <div class="offcanvas-menu">
              <span class="mbtn-top"></span>
              <span class="mbtn-mid"></span>
              <span class="mbtn-bot"></span>
            </div>
          </a>
        </span>
      </div>
    <?php
    endif;
  }
}

/**
 * Render Promotion
 */
if (!function_exists('athfb_render_promotion_element')) {
  function athfb_render_promotion_element($element_id, $context)
  {
    $advertisement_scope = covernews_get_option('banner_advertisement_scope');
    if ($advertisement_scope == 'site-wide') {

      do_action('covernews_action_banner_advertisement');
    } else {

      if (is_front_page() || is_home()) {
        do_action('covernews_action_banner_advertisement');
      }
    }
  }
}
/**
 * Render Navigation Element
 */
if (!function_exists('athfb_render_navigation_element')) {
  function athfb_render_navigation_element($element_id, $context)
  { ?>


    <div class="navigation-container">
      <div class="main-navigation">
        <span class="toggle-menu" aria-controls="primary-menu" aria-expanded="false">
          <a
            href="#"
            class="aft-void-menu"
            role="button"
            aria-label="<?php esc_attr_e('Toggle Primary Menu', 'covernews'); ?>"
            aria-controls="primary-menu" aria-expanded="false" ">
            <span class=" screen-reader-text"><?php esc_html_e('Primary Menu', 'covernews'); ?></span>
        <i class="ham" aria-hidden="true"></i>
        </a>
        </span>
        <span class="af-mobile-site-title-wrap">
          <?php the_custom_logo(); ?>
          <p class="site-title font-family-1">
            <a href="<?php echo esc_url(home_url('/')); ?>"
              rel="home"><?php bloginfo('name'); ?></a>
          </p>
        </span>
        <?php
        wp_nav_menu(array(
          'theme_location' => 'aft-primary-nav',
          'menu_id' => 'primary-menu',
          'container' => 'div',
          'container_class' => 'menu main-menu'
        )); ?>
      </div>
    </div>
    <?php

  }
}

if (!function_exists('athfb_render_footer_navigation_element')) {
  function athfb_render_footer_navigation_element($element, $context)
  {
    //$menu_id = get_option("athfb_{$context}_navigation_menu_id", '');
    $menu_id = 'aft-footer-nav';
    $has_menu_items = athfb_has_menu_items($menu_id);

    //var_dump($has_menu_items);
    if ($has_menu_items) {
      wp_nav_menu(array(
        'theme_location' => $menu_id,
        'menu_id' => 'footer-menu',
        'depth' => 1,
        'container' => 'div',
        'container_class' => 'footer-navigation footer-nav-wrapper'
      ));
    }
  }
}

/**
 * Render Search Element
 */
if (!function_exists('athfb_render_search_element')) {
  function athfb_render_search_element($element_id, $contex)
  {


    if ($contex === 'header') { ?>
      <div class="cart-search">
        <div class="af-search-wrap">
          <div class="search-overlay">
            <a
              title="Search"
              class="search-icon"
              role="button"
              aria-label="Open search"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="covernews-icon-search" aria-hidden="true"></i>
            </a>
            <div class="af-search-form">
              <?php get_search_form(); ?>
            </div>
          </div>
        </div>
      </div>

    <?php } else {
      get_search_form();
    }
  }
}


/**
 * Render Button Element
 */
if (!function_exists('athfb_render_button_element')) {
  function athfb_render_button_element($element_id, $context)
  {

    $covernews_aft_custom_link = covernews_get_option('aft_custom_link');
    $covernews_aft_custom_link = !empty($covernews_aft_custom_link) ? $covernews_aft_custom_link : '#';
    $covernews_aft_custom_link_new_tab = covernews_get_option('aft_custom_link_new_tab');
    $covernews_aft_custom_icon_mode = covernews_get_option('aft_custom_icon_mode');
    $covernews_aft_custom_icon = 'fas fa-play';
    $covernews_aft_custom_title = covernews_get_option('aft_custom_title');
    if ($covernews_aft_custom_icon_mode === 'aft-custom-fa-icon') {
      $covernews_aft_custom_icon = 'fas fa-user';
    }
    //var_dump($covernews_aft_custom_link_new_tab);
    $covernes_new_tab = '_self';
    if ($covernews_aft_custom_link_new_tab) {
      $covernes_new_tab = '_blank';
    }
    if (!empty($covernews_aft_custom_title)) :
    ?>
      <div class="custom-menu-link <?php echo esc_attr($covernews_aft_custom_icon_mode); ?>">
        <a href="<?php echo esc_url($covernews_aft_custom_link); ?>" target="<?php echo esc_attr($covernes_new_tab); ?>" aria-label="<?php echo esc_attr('View ' . $covernews_aft_custom_title); ?>">

          <?php if (!empty($covernews_aft_custom_icon)) : ?>
            <i class="<?php echo esc_attr($covernews_aft_custom_icon); ?>"></i>
          <?php endif; ?>

          <?php echo esc_html($covernews_aft_custom_title); ?>
        </a>
      </div>
    <?php endif; ?>


    <?php
  }
}

/**
 * Render Social Icons Element (used for both generic and footer-specific social icons)
 */
if (!function_exists('athfb_render_social_icons_element')) {
  function athfb_render_social_icons_element($element_id, $context)
  {
    $menu_id = 'aft-social-nav';

    $has_menu_items = athfb_has_menu_items($menu_id);

    if ($has_menu_items) {
      if ($context === 'footer') {
    ?>
        <div class="footer-social-wrapper">
        <?php
      }
      if ($menu_id) {
        wp_nav_menu(array(
          'theme_location' => 'aft-social-nav',
          'menu_id' => 'social-menu',
          'link_before' => '<span class="screen-reader-text">',
          'link_after' => '</span>',
          'container' => 'div',
          'container_class' => 'social-navigation'
        ));
      }
      if ($context === 'footer') {
        ?>

        </div>
      <?php
      }
    } else { ?>
      <div class="top-navigation clearfix">
        <?php

        wp_nav_menu(array(
          'theme_location' => 'primary-menu',
          'menu_id' => 'top-navigation',
          'menu_class' => 'menu menu-mobile',
          'container' => 'div',
          'container_class' => 'menu main-menu menu-desktop show-menu-border'

        ));
        ?>
      </div>
    <?php
    }
  }
}

/**
 * Render Copyright Element
 */
if (!function_exists('athfb_render_copyright_element')) {
  function athfb_render_copyright_element($element_id)
  {
    // Get the user's copyright text option
    $covernews_copy_right = covernews_get_option('footer_copyright_text');

    // Get the current year based on WordPress date settings
    $current_year = date_i18n('Y');

    // Replace {year} placeholder with the current year
    $covernews_copy_right = str_replace('{year}', $current_year, $covernews_copy_right);

    ?>
    <div class="athfb-copyright">
      <?php
      // Output the text if it is not empty
      if (!empty($covernews_copy_right)) {
        echo esc_html($covernews_copy_right);
      }
      ?>
      <?php $covernews_theme_credits = covernews_get_option('hide_footer_copyright_credits'); ?>
      <?php if ($covernews_theme_credits != 1) : ?>

        <span class="sep"> | </span>
        <?php
        /* translators: 1: Theme name, 2: Theme author. */
        printf(esc_html__('%1$s by %2$s.', 'covernews'), '<a href="https://afthemes.com/products/covernews/" target="_blank">covernews</a>', 'AF themes');
        ?>

      <?php endif;
      ?>
    </div>
  <?php
  }
}

/**
 * Render Mobile Menu Element
 */
if (!function_exists('athfb_render_top_menu_element')) {
  function athfb_render_top_menu_element($element_id, $context)
  {
    $topnav =  get_option("athfb_{$context}_top_navigation_menu_id", '');

    if (has_nav_menu('aft-top-nav')) :
      wp_nav_menu(array(
        'theme_location' => 'aft-top-nav',
        'container' => 'div',
        'depth' => 1,
        'container_class' => 'top-navigation'
      ));
    endif;
  }
}

/**
 * Render header date
 */
if (!function_exists('athfb_render_header_date_element')) {
  function athfb_render_header_date_element($element_id, $context)
  {
    $date =   get_option('date_format', '');


    if (empty($date)) {
      $date = get_option('date_format', 'F j, Y');
    }



  ?>

    <span class="topbar-date"><?php echo esc_html(date_i18n($date)); ?></span>



  <?php

  }
}

/**
 * Render Site mode
 */
if (!function_exists('athfb_render_header_site_mode_element')) {
  function athfb_render_header_site_mode_element($element_id, $context)
  {
    wp_enqueue_script('covernews-toggle-script');
    $covernews_global_site_mode_setting = covernews_get_option('global_site_mode');

    if (isset($_COOKIE["covernews-stored-site-mode"])) {
      $covernews_global_site_mode_setting = $_COOKIE["covernews-stored-site-mode"];
    } else {
      if (!empty($covernews_global_site_mode_setting)) {
        $covernews_global_site_mode_setting = $covernews_global_site_mode_setting;
      }
    }
  ?>
    <div id="aft-dark-light-mode-wrap">
      <a href="javascript:void(0)" class="<?php echo esc_attr($covernews_global_site_mode_setting); ?>" data-site-mode="<?php echo esc_attr($covernews_global_site_mode_setting); ?>" id="aft-dark-light-mode-btn">
        <span class="aft-icon-circle"><?php esc_html_e('Light/Dark Button', 'covernews'); ?></span>
      </a>
    </div>
  <?php
  }
}
/**
 * Render custom html
 */

if (!function_exists('athfb_render_header_html_element')) {
  function athfb_render_header_html_element($element_id, $context)
  {
    $html = get_option("athfb_{$context}_html_custom_html", '');

    if (empty($html)) {
      return;
    }

  ?>
    <span class="aft-<?php echo esc_attr($context) ?>-custom-html">
      <?php echo do_shortcode($html); ?>
    </span>
<?php
  }
}
/**
 * 
 * Render Widget
 */
if (!function_exists('athfb_render_widget_element')) {
  function athfb_render_widget_element($element_id, $context, $widget_number)
  {


    $selected_sidebar_id = get_option("athfb_{$context}_widget_{$widget_number}_widget", '');
    if (empty($selected_sidebar_id)) {
      if (empty($selected_sidebar_id)) {
        // Default sidebars for widget numbers 1, 2, or 3
        $default_sidebars = [
          1 => 'header-1-widgets',
          2 => 'header-2-widgets',
          3 => 'header-3-widgets',
        ];
        $selected_sidebar_id = $default_sidebars[$widget_number] ?? '';
      }
    }
    if (! empty($selected_sidebar_id) && is_active_sidebar($selected_sidebar_id)) {

      dynamic_sidebar($selected_sidebar_id);
    }
  }
}

/**
 * 
 * Render Widget
 */
if (!function_exists('athfb_render_footer_widget_element')) {
  function athfb_render_footer_widget_element($element_id, $context, $widget_number)
  {

    $selected_sidebar_id = get_option("athfb_{$context}_widget_{$widget_number}_widget", '');

    if (empty($selected_sidebar_id)) {
      // Default sidebars for widget numbers 1, 2, or 3
      $default_sidebars = [
        1 => 'footer-first-widgets-section',
        2 => 'footer-second-widgets-section',
        3 => 'footer-third-widgets-section',
      ];
      $selected_sidebar_id = $default_sidebars[$widget_number] ?? '';
    }


    if (! empty($selected_sidebar_id) && is_active_sidebar($selected_sidebar_id)) {
      dynamic_sidebar($selected_sidebar_id);
    }
  }
}

if (!function_exists('athfb_has_menu_items')) {
  function athfb_has_menu_items($menu_location)
  {

    $locations = get_nav_menu_locations();

    $has_menu_items = false;

    if (isset($locations[$menu_location])) {
      $menu_id = $locations[$menu_location];
      // Get all menu items for the menu ID
      $menu_items = wp_get_nav_menu_items($menu_id);

      if (!empty($menu_items)) {
        $has_menu_items = true;
      }
    }
    return  $has_menu_items;
  }
}
