<?php
$banner_editor_choice_query = new WP_Query( $banner_editor_choice_args );
if ( $banner_editor_choice_query->have_posts() ) {
	$section_title   = get_theme_mod( 'uncut_news_banner_editor_choice_title', __( 'Editor Choice', 'uncut-news' ) );
	$viewall_btn     = get_theme_mod( 'uncut_news_banner_editor_choice_view_all_button_label', __( 'View All', 'uncut-news' ) );
	$viewall_btn_url = get_theme_mod( 'uncut_news_banner_editor_choice_view_all_button_url', '#' );
	?>
	<div class="editor-choice-area">
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
		<div class="editor-choice-area-container">
			<div class="editor-choice-slider">
				<?php
				$i = 1 ;
				while ( $banner_editor_choice_query->have_posts() ) :
					$banner_editor_choice_query->the_post();
					?>
					<div class="edior-choice-slider-container">
						<div class="single-card-container list-card">
							<span class="counter"><?php printf( '%02d', absint( $i ) ); ?></span>
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
							</div>
						</div>
					</div>
					<?php
					$i++ ;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php require get_template_directory() . '/inc/frontpage-sections/banner/banner-featured-posts.php'; ?>
	</div>
	<?php
}
