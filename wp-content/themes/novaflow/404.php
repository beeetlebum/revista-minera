<?php
/**
 * The template for displaying 404 pages.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();
?>
<main id="primary" tabindex="-1" class="nf-main">
	<div class="nf-container nf-not-found">
		<span aria-hidden="true">404</span>
		<h1><?php esc_html_e( 'This story moved off the page.', 'novaflow' ); ?></h1>
		<p><?php esc_html_e( 'Try a search or return to the homepage to continue exploring.', 'novaflow' ); ?></p>
		<?php get_search_form(); ?>
		<a class="nf-button nf-button-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Back to homepage', 'novaflow' ); ?>
			<?php novaflow_icon( 'arrow' ); ?>
		</a>
	</div>
</main>
<?php
get_footer();
