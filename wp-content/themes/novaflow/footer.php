<?php
/**
 * Site footer.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$footer_categories = array();
$footer_posts      = array();

if ( ! is_active_sidebar( 'footer-one' ) ) {
	$footer_categories = get_categories(
		array(
			'number'  => 6,
			'orderby' => 'count',
			'order'   => 'DESC',
		)
	);

	if ( is_wp_error( $footer_categories ) ) {
		$footer_categories = array();
	}
}

if ( ! is_active_sidebar( 'footer-two' ) ) {
	$footer_posts = get_posts(
		array(
			'numberposts'         => 3,
			'ignore_sticky_posts' => true,
		)
	);
}
?>
	<footer class="nf-site-footer">
		<div class="nf-container">
			<div class="nf-footer-top">
				<section id="site-information" class="nf-footer-brand" aria-label="<?php esc_attr_e( 'Site information', 'novaflow' ); ?>">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
					<a class="nf-footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img
							class="nf-brand-wordmark nf-footer-brand-wordmark"
							src="<?php echo esc_url( add_query_arg( 'ver', novaflow_asset_version( 'assets/images/novaflow-logo.png' ), get_theme_file_uri( 'assets/images/novaflow-logo.png' ) ) ); ?>"
							width="600"
							height="200"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							loading="lazy"
							decoding="async"
						>
					</a>
					<?php endif; ?>
					<a class="nf-footer-home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Back to homepage', 'novaflow' ); ?>
						<?php novaflow_icon( 'arrow' ); ?>
					</a>
				</section>

				<section class="nf-footer-column">
					<?php if ( is_active_sidebar( 'footer-one' ) ) : ?>
						<?php novaflow_dynamic_sidebar( 'footer-one' ); ?>
					<?php else : ?>
						<h2><?php esc_html_e( 'Categories', 'novaflow' ); ?></h2>
						<?php if ( $footer_categories ) : ?>
							<ul class="nf-footer-list">
								<?php foreach ( $footer_categories as $footer_category ) : ?>
									<li><a href="<?php echo esc_url( novaflow_get_category_url( $footer_category->term_id ) ); ?>"><?php echo esc_html( novaflow_localize_theme_label( $footer_category->name ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					<?php endif; ?>
				</section>

				<section class="nf-footer-column">
					<?php if ( is_active_sidebar( 'footer-two' ) ) : ?>
						<?php novaflow_dynamic_sidebar( 'footer-two' ); ?>
					<?php else : ?>
						<h2><?php esc_html_e( 'Latest Posts', 'novaflow' ); ?></h2>
						<?php if ( $footer_posts ) : ?>
							<ul class="nf-footer-posts">
								<?php foreach ( $footer_posts as $footer_post ) : ?>
									<li>
										<a href="<?php echo esc_url( get_permalink( $footer_post ) ); ?>"><?php echo esc_html( novaflow_get_post_display_title( $footer_post->ID ) ); ?></a>
										<small><?php echo esc_html( get_the_date( '', $footer_post ) ); ?></small>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					<?php endif; ?>
				</section>

				<section class="nf-footer-column">
					<?php if ( is_active_sidebar( 'footer-three' ) ) : ?>
						<?php novaflow_dynamic_sidebar( 'footer-three' ); ?>
					<?php else : ?>
						<h2><?php esc_html_e( 'Quick Links', 'novaflow' ); ?></h2>
						<?php
						if ( has_nav_menu( 'footer' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'footer',
									'container'      => false,
									'menu_class'     => 'nf-footer-list',
									'depth'          => 1,
								)
							);
						} else {
							novaflow_utility_menu_fallback( 'nf-footer-list' );
						}
						?>
					<?php endif; ?>
				</section>
			</div>

			<div class="nf-footer-bottom">
				<p class="nf-footer-copyright">
					<?php do_action( 'novaflow_footer_credit' ); ?>
				</p>
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
