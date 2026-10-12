<?php
/**
 * Grid Posts Section
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_grid_posts_section',
	array(
		'panel' => 'beyond_blog_front_page_options',
		'title' => esc_html__( 'Grid Posts Section', 'beyond-blog' ),
	)
);

// Grid Posts Section - Enable Section.
$wp_customize->add_setting(
	'beyond_blog_enable_grid_posts_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_enable_grid_posts_section',
		array(
			'label'    => esc_html__( 'Enable Grid Posts Section', 'beyond-blog' ),
			'section'  => 'beyond_blog_grid_posts_section',
			'settings' => 'beyond_blog_enable_grid_posts_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'beyond_blog_enable_grid_posts_section',
		array(
			'selector' => '#beyond_blog_grid_posts_section .section-link',
			'settings' => 'beyond_blog_enable_grid_posts_section',
		)
	);
}

// Grid Posts Section - Section Title.
$wp_customize->add_setting(
	'beyond_blog_grid_posts_section_title',
	array(
		'default'           => __( 'Grid Posts Section', 'beyond-blog' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'beyond_blog_grid_posts_section_title',
	array(
		'label'           => esc_html__( 'Section Title', 'beyond-blog' ),
		'section'         => 'beyond_blog_grid_posts_section',
		'settings'        => 'beyond_blog_grid_posts_section_title',
		'type'            => 'text',
		'active_callback' => 'beyond_blog_is_grid_posts_section_enabled',
	)
);

// Grid Posts Section - Content Type.
$wp_customize->add_setting(
	'beyond_blog_grid_posts_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_grid_posts_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'beyond-blog' ),
		'section'         => 'beyond_blog_grid_posts_section',
		'settings'        => 'beyond_blog_grid_posts_content_type',
		'type'            => 'select',
		'active_callback' => 'beyond_blog_is_grid_posts_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'beyond-blog' ),
			'category' => esc_html__( 'Category', 'beyond-blog' ),
		),
	)
);

// Grid Posts Section - Select Grid Posts Category.
$wp_customize->add_setting(
	'beyond_blog_grid_posts_content_category',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_grid_posts_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'beyond-blog' ),
		'section'         => 'beyond_blog_grid_posts_section',
		'settings'        => 'beyond_blog_grid_posts_content_category',
		'active_callback' => 'beyond_blog_is_grid_posts_section_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => beyond_blog_get_post_cat_choices(),
	)
);

for ( $i = 1; $i <= 2; $i++ ) {
	// Grid Posts Section - Select Post.
	$wp_customize->add_setting(
		'beyond_blog_grid_posts_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'beyond_blog_grid_posts_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'beyond-blog' ), $i ),
			'section'         => 'beyond_blog_grid_posts_section',
			'settings'        => 'beyond_blog_grid_posts_content_post_' . $i,
			'active_callback' => 'beyond_blog_is_grid_posts_section_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => beyond_blog_get_post_choices(),
		)
	);

}

// Grid Posts Section - View All Button.
$wp_customize->add_setting(
	'beyond_blog_grid_posts_view_all_button_label',
	array(
		'default'           => __( 'View All', 'beyond-blog' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'beyond_blog_grid_posts_view_all_button_label',
	array(
		'label'           => esc_html__( 'Button Label', 'beyond-blog' ),
		'section'         => 'beyond_blog_grid_posts_section',
		'settings'        => 'beyond_blog_grid_posts_view_all_button_label',
		'type'            => 'text',
		'active_callback' => 'beyond_blog_is_grid_posts_section_enabled',
	)
);

// Grid Posts Section - View All Button URL.
$wp_customize->add_setting(
	'beyond_blog_grid_posts_view_all_button_url',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'beyond_blog_grid_posts_view_all_button_url',
	array(
		'label'           => esc_html__( 'View All Button URL', 'beyond-blog' ),
		'section'         => 'beyond_blog_grid_posts_section',
		'settings'        => 'beyond_blog_grid_posts_view_all_button_url',
		'active_callback' => 'beyond_blog_is_grid_posts_section_enabled',
		'type'            => 'url',
	)
);
