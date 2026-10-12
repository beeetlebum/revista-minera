<?php
/**
 * Sidebar template.
 *
 * Uses the assigned Primary Sidebar widgets when available. When the widget
 * area is empty, a small fallback sidebar is built only from real WordPress
 * content: search, published posts, and non-empty categories.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$novaflow_recent_posts = array();
$novaflow_categories   = array();

if ( ! is_active_sidebar( 'primary-sidebar' ) ) {
	$novaflow_recent_args = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 5,
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	if ( is_singular( 'post' ) ) {
		$novaflow_recent_args['post__not_in'] = array( get_queried_object_id() );
	}

	$novaflow_recent_posts = get_posts( $novaflow_recent_args );
	$novaflow_categories   = get_categories(
		array(
			'hide_empty' => true,
			'number'     => 8,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( is_wp_error( $novaflow_categories ) ) {
		$novaflow_categories = array();
	}
}
?>
<aside class="nf-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'novaflow' ); ?>">
	<?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
		<?php novaflow_dynamic_sidebar( 'primary-sidebar' ); ?>
	<?php else : ?>
		<?php if ( $novaflow_recent_posts ) : ?>
			<section class="widget nf-widget nf-default-sidebar-recent">
				<h2 class="widget-title"><?php esc_html_e( 'Latest Posts', 'novaflow' ); ?></h2>
				<ul class="nf-default-recent-list">
					<?php foreach ( $novaflow_recent_posts as $novaflow_recent_post ) : ?>
						<?php $novaflow_has_recent_thumb = has_post_thumbnail( $novaflow_recent_post ); ?>
						<li class="<?php echo esc_attr( $novaflow_has_recent_thumb ? 'has-post-thumbnail' : 'no-post-thumbnail' ); ?>">
							<a href="<?php echo esc_url( get_permalink( $novaflow_recent_post ) ); ?>">
								<?php if ( $novaflow_has_recent_thumb ) : ?>
									<span class="nf-default-recent-thumb">
										<?php
										echo get_the_post_thumbnail(
											$novaflow_recent_post,
											'novaflow-thumb',
											array(
												'loading'  => is_singular( 'post' ) ? 'eager' : 'lazy',
												'decoding' => is_singular( 'post' ) ? 'sync' : 'async',
											)
										); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated image markup. 
										?>
									</span>
								<?php endif; ?>
								<span class="nf-default-recent-copy">
									<strong><?php echo esc_html( novaflow_get_post_display_title( $novaflow_recent_post->ID ) ); ?></strong>
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $novaflow_recent_post ) ); ?>"><?php echo esc_html( get_the_date( '', $novaflow_recent_post ) ); ?></time>
								</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php if ( $novaflow_categories ) : ?>
			<section class="widget nf-widget nf-default-sidebar-categories">
				<h2 class="widget-title"><?php esc_html_e( 'Categories', 'novaflow' ); ?></h2>
				<ul class="nf-default-category-list">
					<?php foreach ( $novaflow_categories as $novaflow_category ) : ?>
						<li>
							<a href="<?php echo esc_url( novaflow_get_category_url( $novaflow_category->term_id ) ); ?>">
								<span><?php echo esc_html( novaflow_localize_theme_label( $novaflow_category->name ) ); ?></span>
								<small><?php echo esc_html( number_format_i18n( $novaflow_category->count ) ); ?></small>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! is_active_sidebar( 'primary-sidebar' ) ) : ?>
		<?php novaflow_popular_posts_widget(); ?>
		<?php novaflow_popular_tags_widget(); ?>
	<?php endif; ?>
</aside>
