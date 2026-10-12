<?php

if ( ! get_theme_mod( 'beyond_blog_enable_grid_posts_section', false ) ) {
	return;
}

$grid_posts_content_ids  = array();
$grid_posts_content_type = get_theme_mod( 'beyond_blog_grid_posts_content_type', 'post' );

if ( $grid_posts_content_type === 'post' ) {
	for ( $i = 1; $i <= 2; $i++ ) {
		$grid_posts_content_ids[] = get_theme_mod( 'beyond_blog_grid_posts_content_post_' . $i );
	}
	$grid_posts_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 2 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $grid_posts_content_ids ) ) ) {
		$grid_posts_args['post__in'] = array_filter( $grid_posts_content_ids );
		$grid_posts_args['orderby']  = 'post__in';
	} else {
		$grid_posts_args['orderby'] = 'date';
	}
} else {
	$cat_content_id  = get_theme_mod( 'beyond_blog_grid_posts_content_category' );
	$grid_posts_args = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 2 ),
	);
}
$grid_posts_args = apply_filters( 'beyond_blog_grid_posts_section_args', $grid_posts_args );

beyond_blog_render_grid_posts_section( $grid_posts_args );

/**
 * Render Grid Posts Section.
 */
function beyond_blog_render_grid_posts_section( $grid_posts_args ) {
	$query = new WP_Query( $grid_posts_args );
	if ( $query->have_posts() ) {
		$grid_posts_title      = get_theme_mod( 'beyond_blog_grid_posts_section_title', __( 'Grid Posts Section', 'beyond-blog' ) );
		$grid_posts_button     = get_theme_mod( 'beyond_blog_grid_posts_view_all_button_label', __( 'View All', 'beyond-blog' ) );
		$grid_posts_button_url = get_theme_mod( 'beyond_blog_grid_posts_view_all_button_url', '' );
		?>
		<section id="beyond_blog_grid_posts_section" class="grid-section section-splitter grid-style-1">
			<?php
			if ( is_customize_preview() ) :
				beyond_blog_section_link( 'beyond_blog_grid_posts_section' );
			endif;
			?>
			<div class="section-wrapper">
				<?php if ( ! empty( $grid_posts_title || $grid_posts_button ) ) : ?>
					<div class="section-title">
						<div class="title-heading">
							<h3 class="main-title"><?php echo esc_html( $grid_posts_title ); ?></h3>
						</div>
						<span class="heading-dash"></span>
						<?php if ( ! empty( $grid_posts_button ) ) : ?>
							<a href="<?php echo esc_url( $grid_posts_button_url ); ?>" class="view-all"><?php echo esc_html( $grid_posts_button ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="blog-post-container-wrapper">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<div class="blog-post-container grid-layout">
							<div class="blog-post-inner">
								<?php if ( has_post_thumbnail() ) { ?>
									<div class="blog-post-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'post-thumbnail' ); ?>
										</a>
									</div>
								<?php } ?>
								<div class="blog-post-detail">
									<ul class="post-categories">
										<?php beyond_blog_categories_list(); ?>
									</ul>
									<h3 class="post-main-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<div class="post-meta">
										<?php
										beyond_blog_posted_by();
										beyond_blog_posted_on();
										?>
										<span class="read-time">
											<?php
											echo beyond_blog_reading_time( get_the_content() );
											echo esc_html__( ' min read', 'beyond-blog' );
											?>
										</span>
									</div>
									<p class="post-excerpt">
										<?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
									</p>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	}
}
