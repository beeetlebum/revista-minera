<?php
/**
 * Front-page template.
 *
 * The template respects both WordPress Reading modes:
 * - Latest posts: the magazine feed uses its own paginated post query.
 * - Static page: the selected Page content is preserved and a magazine feed
 *   of published posts is displayed below it on the first page.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();

$options                         = novaflow_get_options();
$is_static_front                 = 'page' === get_option( 'show_on_front' );
$current_page                    = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );
$home_story_page                 = isset( $_GET['nf_story_page'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only pagination.
	? max( 1, absint( wp_unslash( $_GET['nf_story_page'] ) ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only public pagination parameter.
	: 1;
$home_story_per_page             = 8;
$home_featured_count             = 3;
$home_published_counts           = wp_count_posts( 'post' );
$home_published_total            = isset( $home_published_counts->publish ) ? absint( $home_published_counts->publish ) : 0;
$home_story_available            = max( 0, $home_published_total - $home_featured_count );
$home_story_total_pages          = max( 1, (int) ceil( $home_story_available / $home_story_per_page ) );
$home_story_page                 = min( $home_story_page, $home_story_total_pages );
$show_magazine_feed              = ! $is_static_front || 1 === $current_page;
$editor_query                    = null;
$displayed_post_ids              = array();
$uses_custom_query               = false;
$front_posts_query               = null;
$novaflow_front_page_has_heading = false;
$posts_page_id                   = absint( get_option( 'page_for_posts' ) );
$all_posts_url                   = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );

if ( ! $all_posts_url ) {
	$all_posts_url = home_url( '/' );
}

/*
 * Use the same independently paginated magazine query in both Reading modes.
 * The main query remains available for static Page content and WordPress hooks.
 */
if ( $show_magazine_feed ) {
	{
		$uses_custom_query = true;

		/*
		 * Keep the three Featured Stories stable while the eight-card Latest
		 * Stories grid follows nf_story_page. This deliberately separates the
		 * two groups so page 2 cannot fall back to the first eight cards.
		 */
		$front_featured_ids = get_posts(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'posts_per_page'         => $home_featured_count,
				'fields'                 => 'ids',
				'orderby'                => 'date',
				'order'                  => 'DESC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters'       => false,
			)
		);

		$front_latest_offset = $home_featured_count + ( ( $home_story_page - 1 ) * $home_story_per_page );
		$front_latest_ids    = get_posts(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'posts_per_page'         => $home_story_per_page,
				'offset'                 => $front_latest_offset,
				'fields'                 => 'ids',
				'orderby'                => 'date',
				'order'                  => 'DESC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters'       => false,
			)
		);

		$front_page_ids   = array_values( array_unique( array_merge( $front_featured_ids, $front_latest_ids ) ) );
		$front_query_args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => count( $front_page_ids ),
			'post__in'               => $front_page_ids,
			'orderby'                => 'post__in',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => true,
		);
		/**
		 * Filter the published posts used by the static magazine front page.
		 *
		 * @param array<string,mixed> $front_query_args WP_Query arguments.
		 */
		$front_query_args  = (array) apply_filters( 'novaflow_front_page_query_args', $front_query_args );
		$front_posts_query = $front_page_ids ? new WP_Query( $front_query_args ) : null;
		}
}
?>
<main id="primary" tabindex="-1" class="nf-main nf-home-main<?php echo esc_attr( $is_static_front ? ' nf-static-front-page' : '' ); ?>">
	<?php
	/*
	 * A static front page must display the selected Page. When the Page has no
	 * editor content or featured image, skip the otherwise empty title-only
	 * block so the magazine feed starts immediately.
	 */
	if ( $is_static_front && have_posts() ) {
		while ( have_posts() ) {
			the_post();

			$front_page_content = (string) get_post_field( 'post_content', get_the_ID() );
			$has_page_intro     = '' !== trim( $front_page_content ) || has_post_thumbnail();

			if ( $has_page_intro ) {
				$novaflow_front_page_has_heading = true;
				get_template_part(
					'template-parts/content',
					'page',
					array(
						'compact_front_page' => true,
					)
				);
			}
		}
	}
	?>

	<?php if ( ! $novaflow_front_page_has_heading ) : ?>
		<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
	<?php endif; ?>

	<?php if ( $show_magazine_feed ) : ?>
		<section id="latest" class="nf-content-section">
			<div class="nf-container nf-content-layout">
				<div class="nf-content-column">
					<?php if ( $front_posts_query instanceof WP_Query && $front_posts_query->have_posts() ) : ?>
						<div class="nf-home-feed">
							<div class="nf-featured-stories-box">
								<?php novaflow_section_heading( __( 'Featured stories', 'novaflow' ), '', 'flame', $all_posts_url ); ?>
								<div class="nf-headline-grid">
							<?php
							$latest_index = 0;

							while ( $front_posts_query->have_posts() ) {
								$front_posts_query->the_post();
								$displayed_post_ids[] = get_the_ID();
								++$latest_index;
								$story_classes = 'nf-story-card';

								if ( 1 === $latest_index ) {
									$story_classes .= ' nf-home-headline nf-story-lead';
								} elseif ( 2 === $latest_index ) {
									$story_classes .= ' nf-home-headline nf-story-side-top';
								} elseif ( 3 === $latest_index ) {
									$story_classes .= ' nf-home-headline nf-story-side-bottom';
								}

								if ( 4 === $latest_index ) {
									?>
									</div>
								</div>
									<?php novaflow_section_heading( __( 'Latest stories', 'novaflow' ), '', 'newspaper', $all_posts_url ); ?>
									<div class="nf-latest-grid">
									<?php
								}

								?>
								<article <?php post_class( $story_classes ); ?>
								<?php
								if ( $latest_index <= 3 ) :
									?>
									data-nf-headline<?php endif; ?>>
									<a class="nf-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label() ); ?>"></a>
									<?php novaflow_post_media( $latest_index <= 3 ? 'novaflow-hero' : 'novaflow-card', '', 1 === $latest_index ); ?>
									<div class="nf-story-body">
										<?php novaflow_category_badge(); ?>
										<h3><?php echo esc_html( novaflow_get_post_display_title() ); ?></h3>
										<p><?php echo esc_html( novaflow_get_post_card_excerpt( 18 ) ); ?></p>
										<?php novaflow_post_stats(); ?>
									</div>
								</article>
								<?php
							}
							?>
							</div>
							<?php if ( $latest_index < 4 ) : ?>
							</div>
							<?php endif; ?>
							<?php if ( $latest_index > 1 ) : ?>
								<?php $headline_dot_count = min( 3, $latest_index ); ?>
								<div class="nf-headline-controls" data-nf-headline-controls>
									<div class="nf-headline-dots" aria-label="<?php esc_attr_e( 'Headline selection', 'novaflow' ); ?>">
										<?php for ( $headline_dot = 0; $headline_dot < $headline_dot_count; $headline_dot++ ) : ?>
											<?php
											$headline_label = sprintf(
												/* translators: %s: Headline number. */
												__( 'Show headline %s', 'novaflow' ),
												number_format_i18n( $headline_dot + 1 )
											);
											?>
											<button type="button" data-nf-headline-dot="<?php echo esc_attr( $headline_dot ); ?>" aria-label="<?php echo esc_attr( $headline_label ); ?>"
											<?php
											if ( 0 === $headline_dot ) :
												?>
												aria-current="true"<?php endif; ?>></button>
										<?php endfor; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( 1 === $current_page ) : ?>
							<?php
							$home_list_per_page    = 6;
							$home_list_page        = 1;
							$published_post_count  = wp_count_posts( 'post' );
							$published_post_count  = isset( $published_post_count->publish ) ? absint( $published_post_count->publish ) : 0;
							$all_most_read_ids     = $published_post_count > 0 ? novaflow_get_most_read_post_ids( $home_list_per_page ) : array();
							$home_list_query       = null;
							$home_list_total_pages = $all_most_read_ids ? (int) ceil( count( $all_most_read_ids ) / $home_list_per_page ) : 1;

							if ( $home_list_page > $home_list_total_pages ) {
								$home_list_page = $home_list_total_pages;
							}

							$home_list_offset   = ( $home_list_page - 1 ) * $home_list_per_page;
							$home_list_page_ids = array_slice( $all_most_read_ids, $home_list_offset, $home_list_per_page );

							if ( $home_list_page_ids ) {
								$home_list_query = new WP_Query(
									array(
										'post_type'      => 'post',
										'post_status'    => 'publish',
										'posts_per_page' => count( $home_list_page_ids ),
										'post__in'       => $home_list_page_ids,
										'orderby'        => 'post__in',
										'ignore_sticky_posts' => true,
										'no_found_rows'  => true,
										'update_post_meta_cache' => true,
										'update_post_term_cache' => true,
									)
								);
							}
							?>

							<?php if ( $home_list_query instanceof WP_Query && $home_list_query->have_posts() ) : ?>
								<section id="nf-more-stories" class="nf-home-list-section" aria-label="<?php esc_attr_e( 'Most read stories', 'novaflow' ); ?>">
									<?php novaflow_section_heading( __( 'Most Read', 'novaflow' ), '', 'flame', $all_posts_url ); ?>
									<div class="nf-home-list">
										<?php while ( $home_list_query->have_posts() ) : ?>
											<?php $home_list_query->the_post(); ?>
											<article <?php post_class( 'nf-home-list-card' ); ?>>
												<a class="nf-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label() ); ?>"></a>
												<?php novaflow_post_media( 'novaflow-card' ); ?>
												<div class="nf-home-list-body">
													<div class="nf-home-list-topline">
														<?php novaflow_category_badge(); ?>
													</div>
													<h3><?php echo esc_html( novaflow_get_post_display_title() ); ?></h3>
													<div class="nf-home-list-bottomline">
														<?php novaflow_post_meta( false ); ?>
														<?php $home_list_views = novaflow_get_post_view_count(); ?>
														<?php if ( null !== $home_list_views ) : ?>
															<span class="nf-story-views">
																<?php
																printf(
																	/* translators: %s: Number of post views. */
																	esc_html( _n( '%s view', '%s views', $home_list_views, 'novaflow' ) ),
																	esc_html( number_format_i18n( $home_list_views ) )
																);
																?>
															</span>
														<?php endif; ?>
													</div>
												</div>
											</article>
										<?php endwhile; ?>
									</div>

								</section>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>


							<?php
							$home_pulse_query_args = array(
								'post_type'           => 'post',
								'post_status'         => 'publish',
								'posts_per_page'      => 3,
								'orderby'             => 'date',
								'order'               => 'DESC',
								'ignore_sticky_posts' => true,
								'no_found_rows'       => true,
							);
							/**
							 * Filter the posts displayed in the homepage newsroom-pulse cards.
							 *
							 * @param array<string,mixed> $home_pulse_query_args WP_Query arguments.
							 */
							$home_pulse_query_args = (array) apply_filters( 'novaflow_home_pulse_query_args', $home_pulse_query_args );
							$home_pulse_query      = new WP_Query( $home_pulse_query_args );
							?>

							<?php if ( $home_pulse_query->have_posts() ) : ?>
								<?php do_action( 'novaflow_before_home_pulse' ); ?>
								<section class="nf-home-pulse" aria-label="<?php esc_attr_e( 'Newsroom pulse', 'novaflow' ); ?>">
									<div class="nf-home-pulse-grid">
										<?php $home_pulse_index = 0; ?>
										<?php while ( $home_pulse_query->have_posts() ) : ?>
											<?php
											$home_pulse_query->the_post();
											++$home_pulse_index;
											$home_pulse_views    = novaflow_get_post_view_count();
											$home_pulse_comments = get_comments_number();
											?>
											<article <?php post_class( 'nf-home-pulse-card' ); ?>>
												<a class="nf-card-link nf-home-pulse-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label() ); ?>"></a>
												<div class="nf-home-pulse-topline">
													<span class="nf-home-pulse-number"><?php echo esc_html( zeroise( $home_pulse_index, 2 ) ); ?></span>
													<?php novaflow_category_badge(); ?>
												</div>
												<h3><?php echo esc_html( novaflow_get_post_display_title() ); ?></h3>
												<p><?php echo esc_html( novaflow_get_post_card_excerpt( 14 ) ); ?></p>
												<footer class="nf-home-pulse-meta">
													<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php novaflow_icon( 'calendar' ); ?><?php echo esc_html( get_the_date() ); ?></time>
													<a href="<?php echo esc_url( get_comments_link() ); ?>"><?php novaflow_icon( 'comment' ); ?><?php echo esc_html( number_format_i18n( $home_pulse_comments ) ); ?></a>
													<?php if ( null !== $home_pulse_views ) : ?>
													<span class="nf-home-pulse-views">
														<?php
														printf(
															/* translators: %s: Number of post views. */
															esc_html( _n( '%s view', '%s views', $home_pulse_views, 'novaflow' ) ),
															esc_html( number_format_i18n( $home_pulse_views ) )
														);
														?>
													</span>
												<?php endif; ?>
												</footer>
											</article>
										<?php endwhile; ?>
									</div>
								</section>
								<?php do_action( 'novaflow_after_home_pulse' ); ?>
							<?php endif; ?>
							<?php wp_reset_postdata(); ?>

							<?php if ( $home_story_total_pages > 1 ) : ?>
								<?php $home_story_pagination_base = remove_query_arg( 'nf_story_page', home_url( '/' ) ); ?>
								<nav class="nf-home-pagination nf-latest-stories-pagination" aria-label="<?php esc_attr_e( 'Latest stories', 'novaflow' ); ?>">
									<div class="nf-home-pagination-list">
										<?php if ( $home_story_page > 1 ) : ?>
											<a class="prev page-numbers" href="<?php echo esc_url( add_query_arg( 'nf_story_page', $home_story_page - 1, $home_story_pagination_base ) . '#latest' ); ?>"><?php esc_html_e( 'Previous', 'novaflow' ); ?></a>
										<?php endif; ?>

										<?php for ( $nf_page_number = 1; $nf_page_number <= $home_story_total_pages; $nf_page_number++ ) : ?>
											<?php if ( $nf_page_number === $home_story_page ) : ?>
												<span class="page-numbers current" aria-current="page"><?php echo esc_html( number_format_i18n( $nf_page_number ) ); ?></span>
											<?php else : ?>
												<a class="page-numbers" href="<?php echo esc_url( add_query_arg( 'nf_story_page', $nf_page_number, $home_story_pagination_base ) . '#latest' ); ?>"><?php echo esc_html( number_format_i18n( $nf_page_number ) ); ?></a>
											<?php endif; ?>
										<?php endfor; ?>

										<?php if ( $home_story_page < $home_story_total_pages ) : ?>
											<a class="next page-numbers" href="<?php echo esc_url( add_query_arg( 'nf_story_page', $home_story_page + 1, $home_story_pagination_base ) . '#latest' ); ?>"><?php esc_html_e( 'Next', 'novaflow' ); ?></a>
										<?php endif; ?>
									</div>
								</nav>
							<?php endif; ?>
						<?php endif; ?>
					<?php else : ?>
						<div class="nf-empty-card">
							<h2><?php esc_html_e( 'No stories yet', 'novaflow' ); ?></h2>
							<p><?php esc_html_e( 'Published posts will appear here in a flexible editorial grid.', 'novaflow' ); ?></p>
						</div>
					<?php endif; ?>

					<?php
					if ( $uses_custom_query ) {
						wp_reset_postdata();
					}
					?>
				</div>

				<aside class="nf-sidebar-column" aria-label="<?php esc_attr_e( 'Homepage sidebar', 'novaflow' ); ?>">
					<?php if ( ! empty( $options['show_trending'] ) ) : ?>
						<section class="nf-trending-card">
							<div class="nf-side-title">
								<span><?php novaflow_icon( 'flash' ); ?></span>
								<div>
									<small><?php esc_html_e( 'Readers are following', 'novaflow' ); ?></small>
									<h2><?php esc_html_e( 'Trending now', 'novaflow' ); ?></h2>
								</div>
							</div>
							<ol class="nf-trending-list">
								<?php
								$trending_query_args = array(
									'post_type'           => 'post',
									'post_status'         => 'publish',
									'posts_per_page'      => 5,
									'orderby'             => 'comment_count',
									'ignore_sticky_posts' => true,
									'no_found_rows'       => true,
								);
								/**
								 * Filter the homepage trending-post query.
								 *
								 * @param array<string,mixed> $trending_query_args WP_Query arguments.
								 */
								$trending_query_args = (array) apply_filters( 'novaflow_trending_query_args', $trending_query_args );
								$trending_query      = new WP_Query(
									$trending_query_args
								);

								if ( $trending_query->have_posts() ) {
									while ( $trending_query->have_posts() ) {
										$trending_query->the_post();
										?>
										<li>
											<a href="<?php the_permalink(); ?>">
												<span class="nf-trending-thumb">
													<?php if ( has_post_thumbnail() ) : ?>
														<?php the_post_thumbnail( 'novaflow-thumb' ); ?>
													<?php endif; ?>
												</span>
												<span>
													<strong><?php echo esc_html( novaflow_get_post_display_title() ); ?></strong>
													<span class="nf-trending-meta">
														<small class="nf-trending-date"><?php echo esc_html( get_the_date() ); ?></small>
														<?php $trending_views = novaflow_get_post_view_count(); ?>
													<?php if ( null !== $trending_views ) : ?>
														<small class="nf-trending-views">
															<?php
															printf(
																/* translators: %s: Number of post views. */
																esc_html( _n( '%s view', '%s views', $trending_views, 'novaflow' ) ),
																esc_html( number_format_i18n( $trending_views ) )
															);
															?>
														</small>
													<?php endif; ?>
													</span>
												</span>
											</a>
										</li>
										<?php
									}
									wp_reset_postdata();
								} else {
									for ( $placeholder_index = 1; $placeholder_index <= 4; $placeholder_index++ ) {
										?>
										<li class="nf-trending-placeholder" aria-hidden="true"><span></span><div><i></i><i></i></div></li>
										<?php
									}
								}
								?>
							</ol>
						</section>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
						<div class="nf-widget-stack">
							<?php novaflow_dynamic_sidebar( 'primary-sidebar' ); ?>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</section>

		<?php
		if ( 1 === $current_page && ! empty( $options['show_editor'] ) ) {
			$editor_excluded_ids = array_values( array_unique( array_map( 'absint', $displayed_post_ids ) ) );
			$editor_query_args   = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 6,
				'post__not_in'        => $editor_excluded_ids,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			);
			/**
			 * Filter the homepage editor-selection query.
			 *
			 * @param array<string,mixed> $editor_query_args WP_Query arguments.
			 */
			$editor_query_args = (array) apply_filters( 'novaflow_editor_query_args', $editor_query_args );
			$editor_query      = new WP_Query( $editor_query_args );

			if ( $editor_query->post_count < 6 && $editor_excluded_ids ) {
				$editor_shortfall                  = 6 - $editor_query->post_count;
				$editor_query_args['post__not_in'] = array_slice(
					$editor_excluded_ids,
					0,
					max( 0, count( $editor_excluded_ids ) - $editor_shortfall )
				);
				$editor_query                      = new WP_Query( $editor_query_args );
			}
		}
		?>

		<?php if ( $editor_query instanceof WP_Query && $editor_query->have_posts() ) : ?>
			<section id="nf-editor-selection" class="nf-editor-section" tabindex="-1">
				<div class="nf-container">
					<?php novaflow_section_heading( __( "Editor's selection", 'novaflow' ), '', 'crown', $all_posts_url ); ?>
					<div class="nf-editor-grid">
						<?php
						while ( $editor_query->have_posts() ) {
							$editor_query->the_post();
							?>
							<article <?php post_class( 'nf-editor-card' ); ?>>
								<a class="nf-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label() ); ?>"></a>
								<?php novaflow_post_media( 'novaflow-card' ); ?>
								<div class="nf-editor-card-body">
									<?php novaflow_category_badge(); ?>
									<h3><?php echo esc_html( novaflow_get_post_display_title() ); ?></h3>
									<span>
										<?php
										$reading_time = novaflow_reading_time();
										printf(
											/* translators: %s: Estimated reading time in minutes. */
											esc_html( _n( '%s minute read', '%s minutes read', $reading_time, 'novaflow' ) ),
											esc_html( number_format_i18n( $reading_time ) )
										);
										?>
									</span>
									<?php novaflow_post_stats(); ?>
								</div>
							</article>
							<?php
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	<?php endif; ?>
</main>
<?php
get_footer();
