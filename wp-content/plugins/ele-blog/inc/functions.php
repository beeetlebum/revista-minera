<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}
/*
 * @package ele blog
 * since 1.0.0
 * 
*/

//select category
if (!function_exists('ele_blog_post_category')) :
  function ele_blog_post_category()
  {

    $terms = get_terms(array(
      'taxonomy'       => 'category',
      'hide_empty'     => false,
      'posts_per_page' => -1,
    ));

    $category_list = [];
    foreach ($terms as $post) {
      $category_list[$post->term_id] = [$post->name];
    }

    return $category_list;
  }
endif;


//select tag

if (!function_exists('ele_blog_post_tag')) :
  function ele_blog_post_tag()
  {

    $terms = get_terms(array(
      'taxonomy'       => 'post_tag',
      'hide_empty'     => false,
      'posts_per_page' => -1,
    ));

    $tag_list = [];
    foreach ($terms as $post) {
      $tag_list[$post->term_id] = [$post->name];
    }

    return $tag_list;
  }
endif;

if (!function_exists('eleblog_get_cat_slug')) :
  // category slug
  function eleblog_get_cat_slug($cat_id)
  {
    $cat_id   = (int) $cat_id;
    $category = get_term($cat_id, 'category');

    if (!$category || is_wp_error($category)) {
      return '';
    }

    return $category->slug;
  }
endif;

if (!function_exists('eleblog_select_post')) :
  //select post
  function eleblog_select_post()
  {

    $args       = array('post_type' => 'post', 'posts_per_page' => -1);
    $post_lists = [];

    if ($postlists = get_posts($args)) {
      foreach ($postlists as $postlist) {
        (int) $post_lists[$postlist->ID] = $postlist->post_title;
      }
    } else {
      (int) $post_lists['0'] = esc_html__('No Post Found', 'ele-blog');
    }

    return $post_lists;
  }

endif;

if (!function_exists('eleblog_get_template')) {

  function eleblog_get_template($style = 'style-one')
  {
    include ELE_BLOG_ELEMENTOR . '/templates/' . $style . '.php';
  }
}


$eleblog_installation_date = get_option('eleblog_active_date');
$eleblog_today_date = gmdate('Y-m-d H:i:s');

$eleblog_install_date = new DateTime($eleblog_installation_date);
$eleblog_current_date = new DateTime($eleblog_today_date);
$eleblog_difference = $eleblog_install_date->diff($eleblog_current_date);
$eleblog_diff_days = $eleblog_difference->days;



if (isset($eleblog_diff_days) && $eleblog_diff_days >= 3) {
  add_action('admin_notices', 'ele_blog_notice');
}

//admin notice

function ele_blog_notice()
{
  $user_id = get_current_user_id();
  if (!get_user_meta($user_id, 'ele_blog_notice_dismissed')) {
    $dismiss_url = wp_nonce_url( add_query_arg( 'ele_blog-dismissed', '1' ), 'eleblog_dismiss_' . $user_id );
    echo '<div class="notice-warning notice"><a style="text-decoration:none;float:right;padding-top:5px;" href="' . esc_url( $dismiss_url ) . '">Dismiss</a><p>Dear Eleblog user, Thank you for using Eleblog plugin. We expect a  rating from you.</p> Please <a href="https://wordpress.org/support/plugin/ele-blog/reviews/#new-post">Rate Now! &#9733;&#9733;&#9733;&#9733;&#9733; </a>
         <p>Any Question ? Or Need any support related WordPress ? Fell Free To Contact Us at <b>solverwp21@gmail.com</b></p>
      </div>';
  }
}

function ele_blog_notice_dismissed()
{
  $user_id = get_current_user_id();
  if (isset($_GET['ele_blog-dismissed']) && isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'eleblog_dismiss_' . $user_id)) {
    add_user_meta($user_id, 'ele_blog_notice_dismissed', 'true', true);
  }
}
add_action('admin_init', 'ele_blog_notice_dismissed');

add_action('wp_footer', 'eleblog_footer');

function eleblog_footer()
{ ?>
  <h1 style="position: absolute;left: 9999999px;overflow: hidden;width: 0"><a href="https://solverwp.com/">Solverwp- WordPress Theme and Plugin</a></h1>
<?php }
