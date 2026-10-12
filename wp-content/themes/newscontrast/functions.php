<?php
/*This file is part of NewsContrast child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function newscontrast_enqueue_child_styles()
{
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

    $parent_style = 'covernews-style';
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'newscontrast',
        get_stylesheet_directory_uri() . '/style.css',
        array('bootstrap', $parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'newscontrast_enqueue_child_styles');



/**
 * Front-page main banner section layout
 */
if (!function_exists('newscontrast_front_page_main_section_selection')) {

    function newscontrast_front_page_main_section_selection()
    {

        $hide_on_blog = covernews_get_option('disable_main_banner_on_blog_archive');

        if ($hide_on_blog) {
            if (is_front_page()) {
                do_action('covernews_action_front_page_main_section_1');
            }

        } else {
            if (is_front_page() || is_home()) {
                do_action('covernews_action_front_page_main_section_1');
            }

        }
    }
}
add_action('newscontrast_action_front_page_main_section', 'newscontrast_front_page_main_section_selection');

function newscontrast_filter_default_theme_options($defaults)
{
    $defaults['site_title_font_size']    = 64;    
    $defaults['show_date_section'] = 1;
    $defaults['show_social_menu_section'] = 1;


    return $defaults;
}
add_filter('covernews_filter_default_theme_options', 'newscontrast_filter_default_theme_options', 1);