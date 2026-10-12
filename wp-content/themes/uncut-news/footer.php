<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Uncut News
 */

?>

</div>

<?php if ( ! is_front_page() || is_home() ) { ?>
</div>
</div><!-- #content -->

<?php

}

?>

<footer id="colophon" class="site-footer">
	<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
	<div class="upper-footer">
		<div class="site-container-width">
			<div class="upper-footer-container">

				<?php for ( $i = 1; $i <= 4; $i++ ) { ?>
					<div class="footer-widget-block">
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
<?php endif; ?>

<?php
$uncut_news_search = array( '[the-year]', '[site-link]' );
$replace           = array( date( 'Y' ), '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' );
$copyright_default = sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'uncut-news' ), '[the-year]', '[site-link]' );
$copyright_text    = get_theme_mod( 'uncut_news_copyright_txt', $copyright_default );
$copyright_text    = str_replace( $uncut_news_search, $replace, $copyright_text );
	?>
	<div class="lower-footer">
		<div class="site-container-width">
			<div class="lower-footer-info">
				<div class="site-info">
					<span>
						<?php echo wp_kses_post( $copyright_text ); ?>
						<?php echo sprintf( esc_html__( 'Theme: %1$s By %2$s.', 'uncut-news' ), wp_get_theme()->get( 'Name' ), '<a href="' . wp_get_theme()->get( 'AuthorURI' ) . '">' . wp_get_theme()->get( 'Author' ) . '</a>' ); ?>
					</span>	
				</div><!-- .site-info -->
			</div>
		</div>
	</div>

</footer><!-- #colophon -->

<a href="#" id="scroll-to-top" class="uncut-news-scroll-to-top"><i class="fas fa-chevron-up"></i></a>		

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
