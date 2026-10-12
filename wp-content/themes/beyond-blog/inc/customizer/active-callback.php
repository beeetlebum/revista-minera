<?php

/**
 * Active Callbacks
 *
 * @package Beyond Blog
 */

// Theme Options.
function beyond_blog_is_pagination_enabled( $control ) {
	return ( $control->manager->get_setting( 'beyond_blog_enable_pagination' )->value() );
}
function beyond_blog_is_breadcrumb_enabled( $control ) {
	return ( $control->manager->get_setting( 'beyond_blog_enable_breadcrumb' )->value() );
}

// Banner Section.
function beyond_blog_is_banner_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'beyond_blog_enable_banner_section' )->value() );
}
// Banner Slider Section.
function beyond_blog_is_banner_slider_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_banner_slider_content_type' )->value();
	return ( beyond_blog_is_banner_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function beyond_blog_is_banner_slider_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_banner_slider_content_type' )->value();
	return ( beyond_blog_is_banner_section_enabled( $control ) && ( 'category' === $content_type ) );
}
// Banner Editor Choice Section.
function beyond_blog_is_banner_editor_choice_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_banner_editor_choice_content_type' )->value();
	return ( beyond_blog_is_banner_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function beyond_blog_is_banner_editor_choice_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_banner_editor_choice_content_type' )->value();
	return ( beyond_blog_is_banner_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// Categories Section.
function beyond_blog_is_categories_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'beyond_blog_enable_categories_section' )->value() );
}

// Grid Posts Section.
function beyond_blog_is_grid_posts_section_enabled( $control ) {
	return ( $control->manager->get_setting( 'beyond_blog_enable_grid_posts_section' )->value() );
}
function beyond_blog_is_grid_posts_section_and_content_type_post_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_grid_posts_content_type' )->value();
	return ( beyond_blog_is_grid_posts_section_enabled( $control ) && ( 'post' === $content_type ) );
}
function beyond_blog_is_grid_posts_section_and_content_type_category_enabled( $control ) {
	$content_type = $control->manager->get_setting( 'beyond_blog_grid_posts_content_type' )->value();
	return ( beyond_blog_is_grid_posts_section_enabled( $control ) && ( 'category' === $content_type ) );
}

// Check if static home page is enabled.
function beyond_blog_is_static_homepage_enabled( $control ) {
	return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
