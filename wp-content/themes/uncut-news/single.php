<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Uncut News
 */

get_header();

?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'single' );

		the_post_navigation(
			array(
				'prev_text' => '</span> <span class="nav-title">%title</span>',
				'next_text' => '</span> <span class="nav-title">%title</span>',
			)
		);

		if ( is_singular( 'post' ) ) {
			$cat_content_id = get_the_category( $post->ID )[0]->term_id;
			$args           = array(
				'cat'            => $cat_content_id,
				'posts_per_page' => absint( 3 ),
				'post__not_in'   => array( $post->ID ),
				'orderby'        => 'rand',
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) :
				$related_title = get_theme_mod( 'uncut_news_related_posts_title', __( 'Related Posts', 'uncut-news' ) );
				?>
				<div class="related-posts">
					<?php if ( ! empty( $related_title ) ) : ?>
						<h2 class="related-title"><?php echo esc_html( $related_title ); ?></h2>
					<?php endif; ?>
					<div class="related-post-container">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="single-card-container grid-card">
									<div class="single-card-image">
										<a href="<?php the_permalink(); ?>"><?php uncut_news_post_thumbnail(); ?></a>
									</div>
									<div class="single-card-detail">
										<?php
										the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
										?>
										<div class="post-exerpt">
											<p><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), get_theme_mod( 'uncut_news_excerpt_length', 15 ) ) ); ?></p>
										</div><!-- post-exerpt -->
										<div class="card-meta">
											<span class="post-date"><?php uncut_news_posted_on(); ?></span>
										</div>
									</div>
								</div>
							</article>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
				<?php
			endif;
		}

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

		endwhile; // End of the loop.
	?>

	</main><!-- #main -->

	<?php

	if ( uncut_news_is_sidebar_enabled() ) {
		get_sidebar();
	}

	get_footer();
