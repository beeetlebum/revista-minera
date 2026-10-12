<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Beyond Blog
 */

if ( ! is_front_page() || is_home() ) {
	?>
</div>
</div>
</div>
	<?php
}
if ( is_front_page() ) {
	require get_template_directory() . '/sections/above-footer-widgets.php';
}
?>

<footer class="site-footer">
	<div class="section-wrapper">
		<?php
		if ( is_active_sidebar( 'footer-widget' ) || is_active_sidebar( 'footer-widget-2' ) || is_active_sidebar( 'footer-widget-3' ) ) :
			?>
			<div class="beyond-blog-middle-footer">
				<div class="middle-footer-wrapper three-column-1">
					<?php for ( $i = 1; $i <= 3; $i++ ) { ?>
						<div class="footer-container-wrapper">
							<div class="footer-content-inside">
								<?php dynamic_sidebar( 'footer-widget-' . $i ); ?>
							</div>
						</div>
					<?php } ?>
				</div>	
			</div>
		<?php endif; ?>
		<div class="beyond-blog-bottom-footer">
			<div class="bottom-footer-content no-social-menu">
				<?php

				/**
				 * Hook: beyond_blog_footer_copyright.
				 *
				 * @hooked - beyond_blog_output_footer_copyright_content - 10
				 */
				do_action( 'beyond_blog_footer_copyright' );

				?>

			</div>
		</div>
	</div>
</footer>

<a href="#" class="scroll-to-top"></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
