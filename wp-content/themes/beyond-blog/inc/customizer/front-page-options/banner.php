<?php
/**
 * Banner Section
 *
 * @package Beyond Blog
 */

$wp_customize->add_section(
	'beyond_blog_banner_section',
	array(
		'panel' => 'beyond_blog_front_page_options',
		'title' => esc_html__( 'Banner Section', 'beyond-blog' ),
	)
);

// Banner Section - Enable Section.
$wp_customize->add_setting(
	'beyond_blog_enable_banner_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'beyond_blog_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Toggle_Switch_Custom_Control(
		$wp_customize,
		'beyond_blog_enable_banner_section',
		array(
			'label'    => esc_html__( 'Enable Banner Section', 'beyond-blog' ),
			'section'  => 'beyond_blog_banner_section',
			'settings' => 'beyond_blog_enable_banner_section',
		)
	)
);

if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'beyond_blog_enable_banner_section',
		array(
			'selector' => '#beyond_blog_banner_section .section-link',
			'settings' => 'beyond_blog_enable_banner_section',
		)
	);
}

// Banner Section Main - Heading.
$wp_customize->add_setting(
	'beyond_blog_main_banner_area',
	array(
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Title_Control(
		$wp_customize,
		'beyond_blog_main_banner_area',
		array(
			'label'           => __( 'Main Banner Settings', 'beyond-blog' ),
			'section'         => 'beyond_blog_banner_section',
			'settings'        => 'beyond_blog_main_banner_area',
			'active_callback' => 'beyond_blog_is_banner_section_enabled',
		)
	)
);

// Banner Section - Slider Content Type.
$wp_customize->add_setting(
	'beyond_blog_banner_slider_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_banner_slider_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'beyond-blog' ),
		'section'         => 'beyond_blog_banner_section',
		'settings'        => 'beyond_blog_banner_slider_content_type',
		'type'            => 'select',
		'active_callback' => 'beyond_blog_is_banner_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'beyond-blog' ),
			'category' => esc_html__( 'Category', 'beyond-blog' ),
		),
	)
);

// Banner Section - Select Banner Category.
$wp_customize->add_setting(
	'beyond_blog_banner_slider_content_category',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_banner_slider_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'beyond-blog' ),
		'section'         => 'beyond_blog_banner_section',
		'settings'        => 'beyond_blog_banner_slider_content_category',
		'active_callback' => 'beyond_blog_is_banner_slider_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => beyond_blog_get_post_cat_choices(),
	)
);

for ( $i = 1; $i <= 4; $i++ ) {

	// Banner Section - Select Banner Post.
	$wp_customize->add_setting(
		'beyond_blog_banner_slider_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'beyond_blog_banner_slider_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'beyond-blog' ), $i ),
			'section'         => 'beyond_blog_banner_section',
			'settings'        => 'beyond_blog_banner_slider_content_post_' . $i,
			'active_callback' => 'beyond_blog_is_banner_slider_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => beyond_blog_get_post_choices(),
		)
	);

}

// Banner Section Main - Heading.
$wp_customize->add_setting(
	'beyond_blog_banner_section_area',
	array(
		'sanitize_callback' => 'wp_kses_post',
	)
);

$wp_customize->add_control(
	new Beyond_Blog_Title_Control(
		$wp_customize,
		'beyond_blog_banner_section_area',
		array(
			'label'           => __( 'Banner Editor Choice Settings', 'beyond-blog' ),
			'section'         => 'beyond_blog_banner_section',
			'settings'        => 'beyond_blog_banner_section_area',
			'active_callback' => 'beyond_blog_is_banner_section_enabled',
		)
	)
);

// Banner Section - Editor Choice Content Type.
$wp_customize->add_setting(
	'beyond_blog_banner_editor_choice_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_banner_editor_choice_content_type',
	array(
		'label'           => esc_html__( 'Select Content Type', 'beyond-blog' ),
		'section'         => 'beyond_blog_banner_section',
		'settings'        => 'beyond_blog_banner_editor_choice_content_type',
		'type'            => 'select',
		'active_callback' => 'beyond_blog_is_banner_section_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'beyond-blog' ),
			'category' => esc_html__( 'Category', 'beyond-blog' ),
		),
	)
);

// Banner Section - Select Editor Choice Category.
$wp_customize->add_setting(
	'beyond_blog_banner_editor_choice_content_category',
	array(
		'sanitize_callback' => 'beyond_blog_sanitize_select',
	)
);

$wp_customize->add_control(
	'beyond_blog_banner_editor_choice_content_category',
	array(
		'label'           => esc_html__( 'Select Category', 'beyond-blog' ),
		'section'         => 'beyond_blog_banner_section',
		'settings'        => 'beyond_blog_banner_editor_choice_content_category',
		'active_callback' => 'beyond_blog_is_banner_editor_choice_and_content_type_category_enabled',
		'type'            => 'select',
		'choices'         => beyond_blog_get_post_cat_choices(),
	)
);

for ( $i = 1; $i <= 4; $i++ ) {

	// Banner Section - Select Banner Editor Choice Post.
	$wp_customize->add_setting(
		'beyond_blog_banner_editor_choice_content_post_' . $i,
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'beyond_blog_banner_editor_choice_content_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Select Post %d', 'beyond-blog' ), $i ),
			'section'         => 'beyond_blog_banner_section',
			'settings'        => 'beyond_blog_banner_editor_choice_content_post_' . $i,
			'active_callback' => 'beyond_blog_is_banner_editor_choice_and_content_type_post_enabled',
			'type'            => 'select',
			'choices'         => beyond_blog_get_post_choices(),
		)
	);

}
