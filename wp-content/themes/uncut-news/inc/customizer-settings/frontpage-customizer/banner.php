<?php
/**
 * Frontpage Customizer Settings
 *
 * @package Uncut News
 *
 * Banner Section
 */

$wp_customize->add_section(
	'uncut_news_banner_section',
	array(
		'title' => esc_html__( 'Banner Section', 'uncut-news' ),
		'panel' => 'uncut_news_frontpage_panel',
	)
);

// Banner section enable settings.
$wp_customize->add_setting(
	'uncut_news_banner_section_enable',
	array(
		'default'           => false,
		'sanitize_callback' => 'uncut_news_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Uncut_News_Toggle_Checkbox_Custom_control(
		$wp_customize,
		'uncut_news_banner_section_enable',
		array(
			'label'    => esc_html__( 'Enable Banner Section', 'uncut-news' ),
			'type'     => 'checkbox',
			'settings' => 'uncut_news_banner_section_enable',
			'section'  => 'uncut_news_banner_section',
		)
	)
);


// Banner Posts Sub Heading.
$wp_customize->add_setting(
	'uncut_news_banner_posts_sub_heading',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Uncut_News_Section_Sub_Heading_Control(
		$wp_customize,
		'uncut_news_banner_posts_sub_heading',
		array(
			'label'           => esc_html__( 'Banner Posts Section', 'uncut-news' ),
			'settings'        => 'uncut_news_banner_posts_sub_heading',
			'section'         => 'uncut_news_banner_section',
			'active_callback' => 'uncut_news_if_banner_enabled',
		)
	)
);


// banner content type settings.
$wp_customize->add_setting(
	'uncut_news_banner_posts_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_posts_content_type',
	array(
		'label'           => esc_html__( 'Content type:', 'uncut-news' ),
		'description'     => esc_html__( 'Choose where you want to render the content from.', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'active_callback' => 'uncut_news_if_banner_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'uncut-news' ),
			'category' => esc_html__( 'Category', 'uncut-news' ),
		),
	)
);

for ( $i = 1; $i <= 3; $i++ ) {
	// banner post setting.
	$wp_customize->add_setting(
		'uncut_news_banner_posts_post_' . $i,
		array(
			'sanitize_callback' => 'uncut_news_sanitize_dropdown_pages',
		)
	);

	$wp_customize->add_control(
		'uncut_news_banner_posts_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Post %d', 'uncut-news' ), $i ),
			'section'         => 'uncut_news_banner_section',
			'type'            => 'select',
			'choices'         => uncut_news_get_post_choices(),
			'active_callback' => 'uncut_news_banner_posts_content_type_post_enabled',
		)
	);

}

// banner category setting.
$wp_customize->add_setting(
	'uncut_news_banner_posts_category',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_posts_category',
	array(
		'label'           => esc_html__( 'Category', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'choices'         => uncut_news_get_post_cat_choices(),
		'active_callback' => 'uncut_news_banner_posts_content_type_category_enabled',
	)
);

// Banner Editor Choice Sub Heading.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_sub_heading',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Uncut_News_Section_Sub_Heading_Control(
		$wp_customize,
		'uncut_news_banner_editor_choice_sub_heading',
		array(
			'label'           => esc_html__( 'Banner Editor Choice Section', 'uncut-news' ),
			'settings'        => 'uncut_news_banner_editor_choice_sub_heading',
			'section'         => 'uncut_news_banner_section',
			'active_callback' => 'uncut_news_if_banner_enabled',
		)
	)
);

// Banner Editor Choice title settings.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_title',
	array(
		'default'           => __( 'Editor Choice', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_editor_choice_title',
	array(
		'label'           => esc_html__( 'Section Title', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'uncut_news_banner_editor_choice_title',
		array(
			'selector'            => '.banner-section .editor-choice-area h3.section-title',
			'settings'            => 'uncut_news_banner_editor_choice_title',
			'container_inclusive' => false,
			'fallback_refresh'    => true,
			'render_callback'     => 'uncut_news_banner_editor_choice_title_text_partial',
		)
	);
}

// View All button label setting.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_view_all_button_label',
	array(
		'default'           => __( 'View All', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_editor_choice_view_all_button_label',
	array(
		'label'           => esc_html__( 'View All Button Label', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'settings'        => 'uncut_news_banner_editor_choice_view_all_button_label',
		'type'            => 'text',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// View All button URL setting.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_view_all_button_url',
	array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_editor_choice_view_all_button_url',
	array(
		'label'           => esc_html__( 'View All Button Link', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'settings'        => 'uncut_news_banner_editor_choice_view_all_button_url',
		'type'            => 'url',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// banner content type settings.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_editor_choice_content_type',
	array(
		'label'           => esc_html__( 'Content type:', 'uncut-news' ),
		'description'     => esc_html__( 'Choose where you want to render the content from.', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'active_callback' => 'uncut_news_if_banner_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'uncut-news' ),
			'category' => esc_html__( 'Category', 'uncut-news' ),
		),
	)
);

for ( $i = 1; $i <= 4; $i++ ) {
	// banner post setting.
	$wp_customize->add_setting(
		'uncut_news_banner_editor_choice_post_' . $i,
		array(
			'sanitize_callback' => 'uncut_news_sanitize_dropdown_pages',
		)
	);

	$wp_customize->add_control(
		'uncut_news_banner_editor_choice_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Post %d', 'uncut-news' ), $i ),
			'section'         => 'uncut_news_banner_section',
			'type'            => 'select',
			'choices'         => uncut_news_get_post_choices(),
			'active_callback' => 'uncut_news_banner_editor_choice_content_type_post_enabled',
		)
	);

}

// banner category setting.
$wp_customize->add_setting(
	'uncut_news_banner_editor_choice_category',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_editor_choice_category',
	array(
		'label'           => esc_html__( 'Category', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'choices'         => uncut_news_get_post_cat_choices(),
		'active_callback' => 'uncut_news_banner_editor_choice_content_type_category_enabled',
	)
);

// Banner Featured Posts Sub Heading.
$wp_customize->add_setting(
	'uncut_news_banner_featured_posts_sub_heading',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Uncut_News_Section_Sub_Heading_Control(
		$wp_customize,
		'uncut_news_banner_featured_posts_sub_heading',
		array(
			'label'           => esc_html__( 'Banner Featured Posts Section', 'uncut-news' ),
			'settings'        => 'uncut_news_banner_featured_posts_sub_heading',
			'section'         => 'uncut_news_banner_section',
			'active_callback' => 'uncut_news_if_banner_enabled',
		)
	)
);

// Banner Featured Posts title settings.
$wp_customize->add_setting(
	'uncut_news_banner_featured_posts_title',
	array(
		'default'           => __( 'Featured Posts', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_featured_posts_title',
	array(
		'label'           => esc_html__( 'Section Title', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'uncut_news_banner_featured_posts_title',
		array(
			'selector'            => '.banner-section .featured-posts-area  h3.section-title',
			'settings'            => 'uncut_news_banner_featured_posts_title',
			'container_inclusive' => false,
			'fallback_refresh'    => true,
			'render_callback'     => 'uncut_news_banner_featured_posts_title_text_partial',
		)
	);
}

// View All button label setting.
$wp_customize->add_setting(
	'uncut_news_banner_featuerd_posts_view_all_button_label',
	array(
		'default'           => __( 'View All', 'uncut-news' ),
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_featuerd_posts_view_all_button_label',
	array(
		'label'           => esc_html__( 'View All Button Label', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'settings'        => 'uncut_news_banner_featuerd_posts_view_all_button_label',
		'type'            => 'text',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// View All button URL setting.
$wp_customize->add_setting(
	'uncut_news_banner_featured_posts_view_all_button_url',
	array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_featured_posts_view_all_button_url',
	array(
		'label'           => esc_html__( 'View All Button Link', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'settings'        => 'uncut_news_banner_featured_posts_view_all_button_url',
		'type'            => 'url',
		'active_callback' => 'uncut_news_if_banner_enabled',
	)
);

// banner content type settings.
$wp_customize->add_setting(
	'uncut_news_banner_featured_posts_content_type',
	array(
		'default'           => 'post',
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_featured_posts_content_type',
	array(
		'label'           => esc_html__( 'Content type:', 'uncut-news' ),
		'description'     => esc_html__( 'Choose where you want to render the content from.', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'active_callback' => 'uncut_news_if_banner_enabled',
		'choices'         => array(
			'post'     => esc_html__( 'Post', 'uncut-news' ),
			'category' => esc_html__( 'Category', 'uncut-news' ),
		),
	)
);

for ( $i = 1; $i <= 3; $i++ ) {
	// banner post setting.
	$wp_customize->add_setting(
		'uncut_news_banner_featured_posts_post_' . $i,
		array(
			'sanitize_callback' => 'uncut_news_sanitize_dropdown_pages',
		)
	);

	$wp_customize->add_control(
		'uncut_news_banner_featured_posts_post_' . $i,
		array(
			'label'           => sprintf( esc_html__( 'Post %d', 'uncut-news' ), $i ),
			'section'         => 'uncut_news_banner_section',
			'type'            => 'select',
			'choices'         => uncut_news_get_post_choices(),
			'active_callback' => 'uncut_news_banner_featured_posts_content_type_post_enabled',
		)
	);

}

// banner category setting.
$wp_customize->add_setting(
	'uncut_news_banner_featured_posts_category',
	array(
		'sanitize_callback' => 'uncut_news_sanitize_select',
	)
);

$wp_customize->add_control(
	'uncut_news_banner_featured_posts_category',
	array(
		'label'           => esc_html__( 'Category', 'uncut-news' ),
		'section'         => 'uncut_news_banner_section',
		'type'            => 'select',
		'choices'         => uncut_news_get_post_cat_choices(),
		'active_callback' => 'uncut_news_banner_featured_posts_content_type_category_enabled',
	)
);

/*========================Active Callback==============================*/
function uncut_news_if_banner_enabled( $control ) {
	return $control->manager->get_setting( 'uncut_news_banner_section_enable' )->value();
}

//Banner Posts.
function uncut_news_banner_posts_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_posts_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'post' === $content_type );
}
function uncut_news_banner_posts_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_posts_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'category' === $content_type );
}

//Banner Editor Choice.
function uncut_news_banner_editor_choice_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_editor_choice_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'post' === $content_type );
}
function uncut_news_banner_editor_choice_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_editor_choice_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'category' === $content_type );
}

//Banner Featured Posts.
function uncut_news_banner_featured_posts_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_featured_posts_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'post' === $content_type );
}
function uncut_news_banner_featured_posts_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'uncut_news_banner_featured_posts_content_type' )->value();
	return uncut_news_if_banner_enabled( $control ) && ( 'category' === $content_type );
}
