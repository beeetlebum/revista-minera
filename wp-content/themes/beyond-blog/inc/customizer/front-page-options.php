<?php
/**
 * Front Page Options
 *
 * @package Beyond Blog
 */

$wp_customize->add_panel(
	'beyond_blog_front_page_options',
	array(
		'title'    => esc_html__( 'Front Page Options', 'beyond-blog' ),
		'priority' => 130,
	)
);

// Banner Section.
require get_template_directory() . '/inc/customizer/front-page-options/banner.php';

// Categories Section.
require get_template_directory() . '/inc/customizer/front-page-options/categories.php';

// Grid Posts Section.
require get_template_directory() . '/inc/customizer/front-page-options/grid-posts.php';
