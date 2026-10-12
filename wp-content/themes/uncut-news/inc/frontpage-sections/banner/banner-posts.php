<?php
$banner_posts_query = new WP_Query( $banner_posts_args );
if ( $banner_posts_query->have_posts() ) {
	?>
	<div class="banner-posts-area">
		<div class="banner-posts">
			<?php
			while ( $banner_posts_query->have_posts() ) :
				$banner_posts_query->the_post();
				?>
				<div class="single-card-container grid-card">
					<div class="single-card-image">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
					</div>
					<div class="single-card-detail">
						<div class="card-categories">
							<?php uncut_news_categories_list(); ?>
						</div>
						<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="card-meta">
							<span class="post-author">
								<?php uncut_news_posted_by(); ?>										
							</span>
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
