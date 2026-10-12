<?php
if ( ! get_theme_mod( 'beyond_blog_enable_banner_section', false ) ) {
	return;
}

$slider_content_ids  = $editor_content_ids = array();
$slider_content_type = get_theme_mod( 'beyond_blog_banner_slider_content_type', 'post' );
$editor_content_type = get_theme_mod( 'beyond_blog_banner_editor_choice_content_type', 'post' );

if ( $slider_content_type === 'post' ) {
	for ( $i = 1; $i <= 4; $i++ ) {
		$slider_content_ids[] = get_theme_mod( 'beyond_blog_banner_slider_content_post_' . $i );
	}
	$slider_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 4 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $slider_content_ids ) ) ) {
		$slider_args['post__in'] = array_filter( $slider_content_ids );
		$slider_args['orderby']  = 'post__in';
	} else {
		$slider_args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'beyond_blog_banner_slider_content_category' );
	$slider_args    = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 4 ),
	);
}

$slider_args = apply_filters( 'beyond_blog_slider_section_args', $slider_args );

if ( $editor_content_type === 'post' ) {
	for ( $i = 1; $i <= 4; $i++ ) {
		$editor_content_ids[] = get_theme_mod( 'beyond_blog_banner_editor_choice_content_post_' . $i );
	}
	$editor_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 4 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $editor_content_ids ) ) ) {
		$editor_args['post__in'] = array_filter( $editor_content_ids );
		$editor_args['orderby']  = 'post__in';
	} else {
		$editor_args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'beyond_blog_banner_editor_choice_content_category' );
	$editor_args    = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 4 ),
	);
}

$editor_args = apply_filters( 'beyond_blog_editor_choice_section_args', $editor_args );

beyond_blog_render_slider_section( $slider_args, $editor_args );

/**
 * Render Banner Section.
 */
function beyond_blog_render_slider_section( $slider_args, $editor_args ) {

	?>

	<section id="beyond_blog_banner_section" class="banner-section section-splitter banner-style-1">
		<?php
		if ( is_customize_preview() ) :
			beyond_blog_section_link( 'beyond_blog_banner_section' );
		endif;
		?>
		<div class="section-wrapper">
			<div class="banner-container-wrapper">
				<div class="banner-slider-part">
					<?php
					$slider_query = new WP_Query( $slider_args );
					if ( $slider_query->have_posts() ) {
						?>
						<div class="banner-wrapper">
							<div class="banner-slider slick-button">
								<?php
								while ( $slider_query->have_posts() ) :
									$slider_query->the_post();
									?>
									<div class="blog-post-container tile-layout">
										<div class="blog-post-inner <?php echo esc_attr( has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail' ); ?>">
											<?php if ( has_post_thumbnail() ) : ?>
												<div class="blog-post-image">
													<a href="<?php the_permalink(); ?>">
														<?php the_post_thumbnail( 'post-thumbnail' ); ?>
													</a>
												</div>
											<?php endif; ?>
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
													<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
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
					<?php } ?>
				</div>
				<?php
				$editor_query = new WP_Query( $editor_args );
				if ( $editor_query->have_posts() ) {
					?>
					<div class="editors-choice">
						<div class="post-wrapper">
							<?php
							$i = 1;
							while ( $editor_query->have_posts() ) :
								$editor_query->the_post();
								?>
								<div class="blog-post-container tile-layout">
									<div class="blog-post-inner <?php echo esc_attr( has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail' ); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<div class="blog-post-image">
												<a href="<?php the_permalink(); ?>">
													<?php the_post_thumbnail( 'post-thumbnail' ); ?>
												</a>
											</div>
										<?php endif; ?>
										<div class="blog-post-detail">
											<h3 class="post-main-title">
												<span class="post-counter"><?php echo absint( $i ); ?></span>
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
										</div>
									</div>
								</div>
								<?php
								$i++;
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>


	<?php
}
