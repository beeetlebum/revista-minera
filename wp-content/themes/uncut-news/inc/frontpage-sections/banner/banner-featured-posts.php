<?php
$banner_featured_posts_query = new WP_Query( $banner_featured_posts_args );
if ( $banner_featured_posts_query->have_posts() ) {
	$section_title   = get_theme_mod( 'uncut_news_banner_featured_posts_title', __( 'Featured Posts', 'uncut-news' ) );
	$viewall_btn     = get_theme_mod( 'uncut_news_banner_featuerd_posts_view_all_button_label', __( 'View All', 'uncut-news' ) );
	$viewall_btn_url = get_theme_mod( 'uncut_news_banner_featured_posts_view_all_button_url', '#' );
	?>
	<div class="featured-posts-area">
		<?php if ( ! empty( $section_title || $viewall_btn ) ) : ?>
			<div class="header-title">
				<?php if ( ! empty( $section_title ) ) { ?>
					<h3 class="section-title"><?php echo esc_html( $section_title ); ?></h3>
					<?php
				}
				if ( ! empty( $viewall_btn ) ) :
					?>
					<a href="<?php echo esc_url( $viewall_btn_url ); ?>" class="view-all"><?php echo esc_html( $viewall_btn ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="featured-posts-area-container">
			<?php
			while ( $banner_featured_posts_query->have_posts() ) :
				$banner_featured_posts_query->the_post();
				?>
				<div class="single-card-container list-card">
					<div class="single-card-image">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
					</div>
					<div class="single-card-detail">
						<div class="card-categories">
							<?php uncut_news_categories_list(); ?>
						</div>
						<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="card-meta">
							<span class="post-date">
								<?php uncut_news_posted_on(); ?>
							</span>
						</div>
						<div class="post-exerpt">
							<p><?php echo wp_kses_post( wp_trim_words( get_the_content(), 20 ) ); ?></p>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
}
