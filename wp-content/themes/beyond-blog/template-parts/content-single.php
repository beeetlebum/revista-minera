<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Beyond Blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'beyond_blog_breadcrumb' ); ?>
	<?php if ( is_singular() ) : ?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php
		if ( 'post' === get_post_type() ) {
			setup_postdata( get_post() );
			?>
			<div class="entry-meta">
				<?php
				beyond_blog_posted_on();
				beyond_blog_posted_by();
				?>
			</div><!-- .entry-meta -->
			<?php
		}
	endif;

	beyond_blog_post_thumbnail();
	$thumbnail_id      = get_post_thumbnail_id();
	$thumbnail_caption = wp_get_attachment_caption( $thumbnail_id );
	if ( ! empty( $thumbnail_caption ) ) {
		?>
		<p class="single-post-thumbnail-caption"><?php echo esc_html( $thumbnail_caption ); ?></p>
	<?php } ?>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'beyond-blog' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'beyond-blog' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		beyond_blog_categories_list();
		beyond_blog_entry_footer();
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
