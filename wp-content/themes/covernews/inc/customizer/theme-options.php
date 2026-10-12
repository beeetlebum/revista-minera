<?php

/**
 * Option Panel
 *
 * @package CoverNews
 */

$default = covernews_get_default_theme_options();
/*theme option panel info*/
require get_template_directory() . '/inc/customizer/frontpage-options.php';

//selective refresh
require get_template_directory() . '/inc/customizer/customizer-refresh.php';



// Add Theme Options Panel.
$wp_customize->add_panel(
  'theme_option_panel',
  array(
    'title' => __('Theme Options', 'covernews'),
    'priority' => 70,
    'capability' => 'edit_theme_options',
  )
);


// Preloader Section.
$wp_customize->add_section(
  'site_preloader_settings',
  array(
    'title' => __('Preloader Options', 'covernews'),
    'priority' => 4,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - preloader.
$wp_customize->add_setting(
  'enable_site_preloader',
  array(
    'default' => $default['enable_site_preloader'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'enable_site_preloader',
  array(
    'label' => __('Enable preloader', 'covernews'),
    'section' => 'site_preloader_settings',
    'type' => 'checkbox',
    'priority' => 10,
  )
);


/**
 * Header section
 *
 * @package CoverNews
 */

// Front-page Section.
$wp_customize->add_section(
  'header_options_settings',
  array(
    'title' => __('Header Options', 'covernews'),
    'priority' => 49,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - show_site_title_section.
$wp_customize->add_setting(
  'show_date_section',
  array(
    'default' => $default['show_date_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);
$wp_customize->add_control(
  'show_date_section',
  array(
    'label' => __('Show date on top header', 'covernews'),
    'section' => 'header_builder',
    'settings' => 'show_date_section',
    'type' => 'checkbox',
    'priority' => 10,
    //'active_callback' => 'covernews_top_header_status'
  )
);



// Setting - show_site_title_section.
$wp_customize->add_setting(
  'show_social_menu_section',
  array(
    'default' => $default['show_social_menu_section'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'show_social_menu_section',
  array(
    'label' => __('Show social menu on top header', 'covernews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 11,
    //'active_callback' => 'covernews_top_header_status'
  )
);



// Setting - sticky_header_option.
$wp_customize->add_setting(
  'disable_sticky_header_option',
  array(
    'default' => $default['disable_sticky_header_option'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);
$wp_customize->add_control(
  'disable_sticky_header_option',
  array(
    'label' => __('Disable Sticky Header', 'covernews'),
    'section' => 'header_builder',
    'type' => 'checkbox',
    'priority' => 11,
    //'active_callback' => 'storecommerce_header_layout_status'
  )
);

// Breadcrumb Section.
$wp_customize->add_section(
  'site_breadcrumb_settings',
  array(
    'title' => __('Breadcrumb Options', 'covernews'),
    'priority' => 49,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - breadcrumb.
$wp_customize->add_setting(
  'enable_breadcrumb',
  array(
    'default' => $default['enable_breadcrumb'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'enable_breadcrumb',
  array(
    'label' => __('Show breadcrumbs', 'covernews'),
    'section' => 'site_breadcrumb_settings',
    'settings' => 'enable_breadcrumb',
    'type' => 'checkbox',
    'priority' => 10,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'select_breadcrumb_mode',
  array(
    'default' => $default['select_breadcrumb_mode'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'select_breadcrumb_mode',
  array(
    'label' => __('Select Breadcrumbs', 'covernews'),
    'description' => __("Please ensure that you have enabled the plugin's breadcrumbs before choosing other than Default", 'covernews'),
    'section' => 'site_breadcrumb_settings',
    'type' => 'select',
    'choices' => array(
      'default' => __('Default', 'covernews'),
      'yoast' => __('Yoast SEO', 'covernews'),
      'rankmath' => __('Rank Math', 'covernews'),
      'bcn' => __('NavXT', 'covernews'),
    ),
    'priority' => 100,
  )
);





/**
 * Layout options section
 *
 * @package CoverNews
 */

// Layout Section.
$wp_customize->add_section(
  'site_layout_settings',
  array(
    'title' => __('Global Settings', 'covernews'),
    'priority' => 9,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_font_family_type',
  array(
    'default' => $default['global_font_family_type'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_font_family_type',
  array(
    'label' => __('Global Fonts Family', 'covernews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'google' => __('Google Fonts', 'covernews'),
      'system' => __('System Fonts', 'covernews')
    ),
    'priority' => 100,
  )
);

// Setting - Disable Emoji Script.
$wp_customize->add_setting(
  'disable_wp_emoji',
  array(
    'default'           => $default['disable_wp_emoji'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'disable_wp_emoji',
  array(
    'label'    => __('Disable Emoji Script', 'covernews'),
    'description'       => __('GDPR friendly & better performance', 'covernews'),
    'section'  => 'site_layout_settings', // Use your preferred section.
    'type'     => 'checkbox',
    'priority' => 10,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'select_container_mode',
  array(
    'default'           => $default['select_container_mode'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'select_container_mode',
  array(
    'label'       => __('Conatiner Width', 'covernews'),
    'description' => __('Select Conatiner Width', 'covernews'),
    'section'     => 'site_layout_settings',
    'type'        => 'select',
    'choices'               => array(
      'default' => __('Default', 'covernews'),
      'wide' => __('Wide', 'covernews'),
      'full-width' => __('Full Width', 'covernews')
    ),
    'priority'    => 130,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_content_alignment',
  array(
    'default' => $default['global_content_alignment'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_content_alignment',
  array(
    'label' => __('Content Alignment', 'covernews'),
    'description' => __('Select global content alignment', 'covernews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'align-content-left' => __('Content - Primary sidebar', 'covernews'),
      'align-content-right' => __('Primary sidebar - Content', 'covernews'),
      'full-width-content' => __('Full width content', 'covernews')
    ),
    'priority' => 130,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_toggle_image_lazy_load_setting',
  array(
    'default' => $default['global_toggle_image_lazy_load_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_toggle_image_lazy_load_setting',
  array(
    'label' => __('Image Lazy Loading', 'covernews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'enable' => __('Enable ', 'covernews'),
      'disable' => __('Disable', 'covernews'),

    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_post_author_icon_setting',
  array(
    'default'           => $default['global_post_author_icon_setting'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_post_author_icon_setting',
  array(
    'label'       => __('Author Icon Options', 'covernews'),
    'description' => __('Select author display display format', 'covernews'),
    'section'     => 'site_layout_settings',
    'type'        => 'select',
    'choices'               => array(
      'show-author-icon' => __('Author name with icon', 'covernews'),
      'show-author-image' => __('Author name with image', 'covernews'),
      'show-author-only' => __('Author name only', 'covernews'),

    ),
    'priority'    => 130,
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_post_date_author_setting',
  array(
    'default' => $default['global_post_date_author_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_post_date_author_setting',
  array(
    'label' => __('Date and Author Display', 'covernews'),
    'description' => __('Select date and author display settings below post title', 'covernews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'show-date-author' => __('Show Date and Author', 'covernews'),
      'show-date-only' => __('Show Date Only', 'covernews'),
      'show-author-only' => __('Show Author Only', 'covernews'),
      'hide-date-author' => __('Hide All', 'covernews'),
    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_date_display_setting',
  array(
    'default' => $default['global_date_display_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control('global_date_display_setting',    array(
  'label' => __('Date Display Format', 'covernews'),
  'description' => __('Select date display display format', 'covernews'),
  'section' => 'site_layout_settings',
  'type' => 'select',
  'choices' => array(
    'theme-date' => __('Date Format by Theme', 'covernews'),
    'default-date' => __('WordPress Default Date Format', 'covernews'),

  ),
  'priority' => 130,
));






// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_show_comment_count',
  array(
    'default' => $default['global_show_comment_count'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_show_comment_count',
  array(
    'label' => __('Show Comment Count', 'covernews'),
    'section' => 'site_layout_settings',
    'type' => 'select',
    'choices' => array(
      'yes' => __('Enable', 'covernews'),
      'no' => __('Disable', 'covernews'),
    ),
    'priority' => 130,
  )
);



/**
 * Single Posts options section
 *
 * @package CoverNews
 */

// Single Posts Section.
$wp_customize->add_section(
  'site_single_posts_settings',
  array(
    'title' => __('Single Posts Settings', 'covernews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_show_featured_image',
  array(
    'default' => $default['single_show_featured_image'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_featured_image',
  array(
    'label' => __('Show Featured Image', 'covernews'),
    'section' => 'site_single_posts_settings',
    'settings' => 'single_show_featured_image',
    'type' => 'checkbox',
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_single_content_mode',
  array(
    'default' => $default['global_single_content_mode'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_single_content_mode',
  array(
    'label' => __('Global Content Mode', 'covernews'),
    'description' => __('Individual posts edit panel also have the option', 'covernews'),
    'section' => 'site_single_posts_settings',
    'type' => 'select',
    'choices' => array(
      'single-content-mode-default' => __('Default', 'covernews'),
      'single-content-mode-boxed' => __('Spacious', 'covernews'),
    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_single_post_tag_display',
  array(
    'default' => $default['global_single_post_tag_display'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_single_post_tag_display',
  array(
    'label' => __('Show Tags Below Content', 'covernews'),
    'section' => 'site_single_posts_settings',
    'type' => 'select',
    'choices' => array(
      'yes' => __('Yes', 'covernews'),
      'no' => __('No', 'covernews'),
    ),
    'priority' => 130,
  )
);

/**
 * Sidebar options section
 *
 * @package CoverNews
 */

// Sidebar Section.
$wp_customize->add_section(
  'site_sidebar_settings',
  array(
    'title' => __('Sidebar Settings', 'covernews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - frontpage_sticky_sidebar.
$wp_customize->add_setting(
  'frontpage_sticky_sidebar',
  array(
    'default' => $default['frontpage_sticky_sidebar'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'frontpage_sticky_sidebar',
  array(
    'label' => __('Make Sidebar Sticky', 'covernews'),
    'section' => 'site_sidebar_settings',
    'type' => 'checkbox',
    'priority' => 130,
    'active_callback' => 'frontpage_content_alignment_status'
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'frontpage_sticky_sidebar_position',
  array(
    'default' => $default['frontpage_sticky_sidebar_position'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'frontpage_sticky_sidebar_position',
  array(
    'label' => __('Sidebar Sticky Position', 'covernews'),
    'section' => 'site_sidebar_settings',
    'type' => 'select',
    'choices' => array(
      'sidebar-sticky-top' => __('Top', 'covernews'),
      'sidebar-sticky-bottom' => __('Bottom', 'covernews'),
    ),
    'priority' => 130,
    'active_callback' => 'frontpage_sticky_sidebar_status'
  )
);



/**
 * Archive options section
 *
 * @package CoverNews
 */

// Archive Section.
$wp_customize->add_section(
  'site_archive_settings',
  array(
    'title' => __('Archive Settings', 'covernews'),
    'priority' => 49,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

//Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_layout',
  array(
    'default' => $default['archive_layout'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_layout',
  array(
    'label' => __('Archive layout', 'covernews'),
    'description' => __('Select layout for archive', 'covernews'),
    'section' => 'site_archive_settings',
    'settings' => 'archive_layout',
    'type' => 'select',
    'choices' => array(
      'archive-layout-full' => __('Full', 'covernews'),
      'archive-layout-grid' => __('Grid', 'covernews'),
    ),
    'priority' => 130,
  )
);

// Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_image_alignment',
  array(
    'default' => $default['archive_image_alignment'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_image_alignment',
  array(
    'label' => __('Image alignment', 'covernews'),
    'description' => __('Select image alignment for archive', 'covernews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'archive-image-left' => __('Left', 'covernews'),
      'archive-image-right' => __('Right', 'covernews')
    ),
    'priority' => 130,
    'active_callback' => 'covernews_archive_image_status'
  )
);

//Setting - archive content view of news.
$wp_customize->add_setting(
  'archive_content_view',
  array(
    'default' => $default['archive_content_view'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'archive_content_view',
  array(
    'label' => __('Content view', 'covernews'),
    'description' => __('Select content view for archive', 'covernews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'archive-content-excerpt' => __('Post excerpt', 'covernews'),
      'archive-content-full' => __('Full Content', 'covernews'),
      'archive-content-none' => __('None', 'covernews'),

    ),
    'priority' => 130,
  )
);


// Setting - global content alignment of news.
$wp_customize->add_setting(
  'global_widget_excerpt_setting',
  array(
    'default' => $default['global_widget_excerpt_setting'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_select',
  )
);

$wp_customize->add_control(
  'global_widget_excerpt_setting',
  array(
    'label' => __('Excerpt Mode', 'covernews'),
    'description' => __('Select Excerpt display mode', 'covernews'),
    'section' => 'site_archive_settings',
    'type' => 'select',
    'choices' => array(
      'trimmed-content' => __('Trimmed Content', 'covernews'),
      'default-excerpt' => __('Default Excerpt', 'covernews'),

    ),
    'priority' => 130,
    'active_callback' => 'covernews_archive_content_view_status'
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'global_read_more_texts',
  array(
    'default'           => $default['global_read_more_texts'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'global_read_more_texts',
  array(
    'label'    => __('Read More', 'covernews'),
    'section'  => 'site_archive_settings',
    'type'     => 'text',
    'priority' => 130,
    'active_callback' => 'covernews_archive_content_view_status'
  )
);


//========== related posts  options ===============

// Single Section.
$wp_customize->add_section(
  'site_single_related_posts_settings',
  array(
    'title' => __('Related Posts', 'covernews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_show_related_posts',
  array(
    'default' => $default['single_show_related_posts'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'single_show_related_posts',
  array(
    'label' => __('Show on single posts', 'covernews'),
    'section' => 'site_single_related_posts_settings',
    'settings' => 'single_show_related_posts',
    'type' => 'checkbox',
    'priority' => 100,
  )
);

// Setting - related posts.
$wp_customize->add_setting(
  'single_related_posts_title',
  array(
    'default' => $default['single_related_posts_title'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'single_related_posts_title',
  array(
    'label' => __('Title', 'covernews'),
    'section' => 'site_single_related_posts_settings',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'covernews_related_posts_status'
  )
);




//========== footer latest blog carousel options ===============

// Footer Section.
$wp_customize->add_section(
  'frontpage_latest_posts_settings',
  array(
    'title' => __('Latest Posts Options', 'covernews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);
// Setting - latest blog carousel.
$wp_customize->add_setting(
  'frontpage_show_latest_posts',
  array(
    'default' => $default['frontpage_show_latest_posts'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'covernews_sanitize_checkbox',
  )
);

$wp_customize->add_control(
  'frontpage_show_latest_posts',
  array(
    'label' => __('Show Latest Posts Section above Footer', 'covernews'),
    'section' => 'frontpage_latest_posts_settings',
    'settings' => 'frontpage_show_latest_posts',
    'type' => 'checkbox',
    'priority' => 100,
  )
);


// Setting - featured_news_section_title.
$wp_customize->add_setting(
  'frontpage_latest_posts_section_title',
  array(
    'default' => $default['frontpage_latest_posts_section_title'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);
$wp_customize->add_control(
  'frontpage_latest_posts_section_title',
  array(
    'label' => __('Latest Posts Section Title', 'covernews'),
    'section' => 'frontpage_latest_posts_settings',
    'type' => 'text',
    'priority' => 100,
    'active_callback' => 'covernews_latest_news_section_status'

  )
);


//========== footer section options ===============
// Footer Section.
$wp_customize->add_section(
  'site_footer_settings',
  array(
    'title' => __('Footer', 'covernews'),
    'priority' => 50,
    'capability' => 'edit_theme_options',
    'panel' => 'theme_option_panel',
  )
);

// Setting - global content alignment of news.
$wp_customize->add_setting(
  'footer_copyright_text',
  array(
    'default' => $default['footer_copyright_text'],
    'capability' => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
  )
);

$wp_customize->add_control(
  'footer_copyright_text',
  array(
    'label' => __('Copyright Text', 'covernews'),
    'section' => 'footer_builder',
    'settings' => 'footer_copyright_text',
    'type' => 'text',
    'priority' => 100,
  )
);
