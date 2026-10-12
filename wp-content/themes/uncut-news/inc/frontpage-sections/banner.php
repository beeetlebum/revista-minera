<?php
/**
 * Frontpage Banner Section.
 *
 * @package Uncut News
 */

// Banner Section.
$banner_section = get_theme_mod( 'uncut_news_banner_section_enable', false );

if ( false === $banner_section ) {
	return;
}

$banner_posts_content_ids = $banner_editor_choice_content_ids = $banner_featured_posts_content_ids  = array();
$banner_posts_content_type          = get_theme_mod( 'uncut_news_banner_posts_content_type', 'post' );
$banner_editor_choice_content_type  = get_theme_mod( 'uncut_news_banner_editor_choice_content_type', 'post' );
$banner_featured_posts_content_type = get_theme_mod( 'uncut_news_banner_featured_posts_content_type', 'post' );

if ( $banner_featured_posts_content_type === 'post' ) {

	for ( $i = 1; $i <= 3; $i++ ) {
		$banner_featured_posts_content_ids[] = get_theme_mod( 'uncut_news_banner_featured_posts_' . $banner_featured_posts_content_type . '_' . $i );
	}

	$banner_featured_posts_args = array(
		'post_type'           => $banner_featured_posts_content_type,
		'posts_per_page'      => absint( 3 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $banner_featured_posts_content_ids ) ) ) {
		$banner_featured_posts_args['post__in'] = array_filter( $banner_featured_posts_content_ids );
		$banner_featured_posts_args['orderby']  = 'post__in';
	} else {
		$banner_featured_posts_args['orderby'] = 'date';
	}
} else {
	$cat_content_id             = get_theme_mod( 'uncut_news_banner_featured_posts_category' );
	$banner_featured_posts_args = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 3 ),
	);
}

if ( $banner_posts_content_type === 'post' ) {

	for ( $i = 1; $i <= 3; $i++ ) {
		$banner_posts_content_ids[] = get_theme_mod( 'uncut_news_banner_posts_' . $banner_posts_content_type . '_' . $i );
	}

	$banner_posts_args = array(
		'post_type'           => $banner_posts_content_type,
		'posts_per_page'      => absint( 3 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $banner_posts_content_ids ) ) ) {
		$banner_posts_args['post__in'] = array_filter( $banner_posts_content_ids );
		$banner_posts_args['orderby']  = 'post__in';
	} else {
		$banner_posts_args['orderby'] = 'date';
	}
} else {
	$cat_content_id    = get_theme_mod( 'uncut_news_banner_posts_category' );
	$banner_posts_args = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 3 ),
	);
}

if ( $banner_editor_choice_content_type === 'post' ) {

	for ( $i = 1; $i <= 4; $i++ ) {
		$banner_editor_choice_content_ids[] = get_theme_mod( 'uncut_news_banner_editor_choice_' . $banner_editor_choice_content_type . '_' . $i );
	}

	$banner_editor_choice_args = array(
		'post_type'           => $banner_editor_choice_content_type,
		'posts_per_page'      => absint( 4 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $banner_editor_choice_content_ids ) ) ) {
		$banner_editor_choice_args['post__in'] = array_filter( $banner_editor_choice_content_ids );
		$banner_editor_choice_args['orderby']  = 'post__in';
	} else {
		$banner_editor_choice_args['orderby'] = 'date';
	}
} else {
	$cat_content_id            = get_theme_mod( 'uncut_news_banner_editor_choice_category' );
	$banner_editor_choice_args = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 4 ),
	);
}

?>

<section id="uncut_news_banner_section" class="banner-section banner-layout-3">
	<div class="site-container-width">
		<div class="banner-section-wrapper">
			
			<?php

			require get_template_directory() . '/inc/frontpage-sections/banner/banner-posts.php';

			require get_template_directory() . '/inc/frontpage-sections/banner/banner-editor-choice.php';

			?>
			
		</div>
	</div>
</section>
