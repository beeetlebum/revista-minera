<?php
/**
 * Single post template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();

while ( have_posts() ) :
	the_post();

	$novaflow_post_id = get_the_ID();
	$post_title       = novaflow_get_post_display_title( $novaflow_post_id );
	$categories       = get_the_category( $novaflow_post_id );
	$author_id        = (int) get_the_author_meta( 'ID' );
	$author_name      = get_the_author();
	$author_url       = get_author_posts_url( $author_id );
	$comment_count    = (int) get_comments_number( $novaflow_post_id );
	$reading_time     = novaflow_reading_time( $novaflow_post_id );
	$title_words      = preg_split( '/\s+/u', trim( $post_title ) );
	$title_words      = is_array( $title_words ) ? $title_words : array( $post_title );
	$accent_count     = count( $title_words ) > 3 ? 2 : 1;
	$accent_words     = array_splice( $title_words, -$accent_count );
	$title_base       = implode( ' ', $title_words );
	$title_accent     = implode( ' ', $accent_words );
	$article_deck     = novaflow_get_post_card_excerpt( 32, $novaflow_post_id );
	/**
	 * Filter the content-backed introduction shown below a single-post title.
	 *
	 * @param string $article_deck Article introduction.
	 * @param int    $novaflow_post_id      Post ID.
	 */
	$filtered_article_deck = apply_filters( 'novaflow_article_deck', $article_deck, $novaflow_post_id );
	$article_deck          = is_scalar( $filtered_article_deck ) || null === $filtered_article_deck
		? (string) $filtered_article_deck
		: $article_deck;

	$related_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 6,
		'post__not_in'        => array( $novaflow_post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( $categories ) {
		$related_args['category__in'] = wp_list_pluck( $categories, 'term_id' );
	}

	/**
	 * Filter the related-post query for a single article.
	 *
	 * @param array<string,mixed> $related_args Related-post query arguments.
	 * @param int                 $novaflow_post_id      Current post ID.
	 */
	$related_args = (array) apply_filters( 'novaflow_related_posts_query_args', $related_args, $novaflow_post_id );

	$related_posts = get_posts( $related_args );

	if ( count( $related_posts ) < 6 ) {
		$excluded_ids  = array_merge( array( $novaflow_post_id ), wp_list_pluck( $related_posts, 'ID' ) );
		$extra_posts   = get_posts(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 6 - count( $related_posts ),
				'post__not_in'        => $excluded_ids,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		$related_posts = array_merge( $related_posts, $extra_posts );
	}

	?>
	<main id="primary" tabindex="-1" class="nf-main nf-article-page nf-single-modern">
		<div class="nf-container nf-article-shell nf-article-layout">
			<article <?php post_class( 'nf-article-card nf-modern-article' ); ?>>
				<header class="nf-modern-header">
					<h1 class="nf-article-title">
						<?php if ( $title_base ) : ?>
							<span><?php echo esc_html( $title_base ); ?></span>
						<?php endif; ?>
						<span class="nf-title-accent"><?php echo esc_html( $title_accent ); ?></span>
					</h1>
					<span class="nf-title-rule" aria-hidden="true"></span>

					<?php if ( $article_deck ) : ?>
						<p class="nf-article-deck"><?php echo esc_html( $article_deck ); ?></p>
					<?php endif; ?>

					<div class="nf-modern-meta">
						<div class="nf-modern-author">
							<a href="<?php echo esc_url( $author_url ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: Author name. */ __( 'View all posts by %s', 'novaflow' ), $author_name ) ); ?>">
								<?php
								echo wp_kses_post(
									get_avatar(
										$author_id,
										37,
										'',
										$author_name,
										array(
											'class'    => 'nf-modern-avatar',
											'loading'  => 'eager',
											'decoding' => 'sync',
										)
									)
								);
								?>
							</a>
							<strong><a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></strong>
							<span class="nf-author-verified" title="<?php esc_attr_e( 'Verified author', 'novaflow' ); ?>" aria-label="<?php esc_attr_e( 'Verified author', 'novaflow' ); ?>">&#10003;</span>
						</div>

						<span class="nf-meta-separator" aria-hidden="true"></span>
						<time class="nf-modern-meta-item" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php novaflow_icon( 'calendar' ); ?>
							<?php echo esc_html( get_the_date() ); ?>
						</time>
						<span class="nf-meta-separator" aria-hidden="true"></span>
						<span class="nf-modern-meta-item">
							<?php novaflow_icon( 'clock' ); ?>
							<?php
							printf(
								/* translators: %s: Estimated reading time in minutes. */
								esc_html( _n( '%s minute read', '%s minutes read', $reading_time, 'novaflow' ) ),
								esc_html( number_format_i18n( $reading_time ) )
							);
							?>
						</span>
						<span class="nf-meta-separator" aria-hidden="true"></span>
						<a class="nf-modern-meta-item" href="<?php echo esc_url( get_comments_link( $novaflow_post_id ) ); ?>">
							<?php novaflow_icon( 'comment' ); ?>
							<?php
							printf(
								/* translators: %s: Number of comments. */
								esc_html( _n( '%s comment', '%s comments', $comment_count, 'novaflow' ) ),
								esc_html( number_format_i18n( $comment_count ) )
							);
							?>
						</a>

					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="nf-article-hero">
						<?php
						the_post_thumbnail(
							'full',
							array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'decoding'      => 'sync',
							)
						);
						?>
						<?php
						$caption = get_the_post_thumbnail_caption();
						if ( $caption ) :
							?>
							<figcaption><?php echo wp_kses_post( $caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<div class="nf-article-content entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Post pages', 'novaflow' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<?php if ( has_tag() ) : ?>
					<footer class="nf-modern-tags" aria-label="<?php esc_attr_e( 'Article tags', 'novaflow' ); ?>">
						<strong><?php novaflow_icon( 'tag' ); ?><?php esc_html_e( 'Tags:', 'novaflow' ); ?></strong>
						<?php the_tags( '<div class="nf-modern-tag-list">', '', '</div>' ); ?>
					</footer>
				<?php endif; ?>

				<?php if ( $related_posts ) : ?>
					<section class="nf-related-posts" aria-labelledby="nf-related-title">
						<h2 id="nf-related-title"><?php esc_html_e( 'Related Posts', 'novaflow' ); ?></h2>
						<div class="nf-related-grid">
							<?php foreach ( $related_posts as $related_post ) : ?>
								<?php
								$related_categories = get_the_category( $related_post->ID );
								$related_category   = $related_categories ? $related_categories[0] : null;
								?>
								<article class="nf-related-card">
									<a class="nf-related-media" href="<?php echo esc_url( get_permalink( $related_post ) ); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label( $related_post->ID ) ); ?>">
										<?php if ( has_post_thumbnail( $related_post ) ) : ?>
											<?php
											echo get_the_post_thumbnail(
												$related_post,
												'novaflow-thumb',
												array(
													'loading'  => 'lazy',
													'decoding' => 'async',
												)
											); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated by WordPress. 
											?>
										<?php else : ?>
											<span class="nf-related-fallback" aria-hidden="true"></span>
										<?php endif; ?>
										<?php if ( $related_category ) : ?>
											<span class="nf-related-badge"><?php echo esc_html( novaflow_localize_theme_label( $related_category->name ) ); ?></span>
										<?php endif; ?>
									</a>
									<div class="nf-related-body">
										<h3><a href="<?php echo esc_url( get_permalink( $related_post ) ); ?>"><?php echo esc_html( novaflow_get_post_display_title( $related_post->ID ) ); ?></a></h3>
										<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $related_post ) ); ?>"><?php novaflow_icon( 'clock' ); ?><?php echo esc_html( get_the_date( '', $related_post ) ); ?></time>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>
			</article>
			<?php get_sidebar(); ?>
		</div>
	</main>
	<?php
endwhile;

get_footer();
