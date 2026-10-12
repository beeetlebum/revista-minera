<?php
/**
 * Frontpage Highlights News Section.
 *
 * @package Uncut News
 */

// Highlights News Section.
$highlights_news_section = get_theme_mod( 'uncut_news_highlights_news_section_enable', false );

if ( false === $highlights_news_section ) {
	return;
}

$content_ids                  = array();
$highlights_news_content_type = get_theme_mod( 'uncut_news_highlights_news_content_type', 'post' );

if ( $highlights_news_content_type === 'post' ) {

	for ( $i = 1; $i <= 5; $i++ ) {
		$content_ids[] = get_theme_mod( 'uncut_news_highlights_news_' . $highlights_news_content_type . '_' . $i );
	}

	$args = array(
		'post_type'           => $highlights_news_content_type,
		'posts_per_page'      => absint( 5 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $content_ids ) ) ) {
		$args['post__in'] = array_filter( $content_ids );
		$args['orderby']  = 'post__in';
	} else {
		$args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'uncut_news_highlights_news_category' );
	$args           = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 5 ),
	);
}

$query = new WP_Query( $args );
if ( $query->have_posts() ) :
	$section_title = get_theme_mod( 'uncut_news_highlights_news_title', __( 'Highlights News', 'uncut-news' ) );
	?>
	<div id="uncut_news_highlights_section" class="news-highlights">
		<div class="site-container-width">
			<div class="news-highlights-container">
				<?php if ( ! empty( $section_title ) ) : ?>
					<span class="news-highlights-icon">
						<i class="fa-solid fa-circle"></i>
						<span class="highlights-title"><?php echo esc_html( $section_title ); ?></span>
					</span>
				<?php endif; ?>
				<div class="js-conveyor">
					<ul>
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<li>
								<div class="highlights-content">
									<?php if ( has_post_thumbnail() ) { ?>
										<div class="content-img">
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array( 40, 40 ) ); ?></a>
										</div>
									<?php } ?>
									<div class="content-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</div>
								</div>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
endif;
