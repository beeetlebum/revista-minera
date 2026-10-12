<?php
/**
 * Site header.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$options = novaflow_get_options();

?><!doctype html>
<html <?php language_attributes(); ?> class="nf-no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text nf-skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'novaflow' ); ?></a>
<div id="page" class="nf-site">
	<header class="nf-site-header nf-header-colormag">
		<?php if ( ! empty( $options['show_topbar'] ) ) : ?>
			<div class="nf-utilitybar nf-utilitybar-reference">
				<div class="nf-container nf-utilitybar-inner">
					<div class="nf-utilitybar-left">
						<span class="nf-utility-date">
							<?php novaflow_icon( 'calendar' ); ?>
							<time datetime="<?php echo esc_attr( wp_date( 'c' ) ); ?>"><?php echo esc_html( wp_date( 'j F Y l' ) ); ?></time>
						</span>

						<nav class="nf-utility-menu nf-utility-menu-reference" aria-label="<?php esc_attr_e( 'Utility menu', 'novaflow' ); ?>">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'utility',
									'container'      => false,
									'menu_class'     => 'nf-utility-menu-list',
									'depth'          => 1,
									'fallback_cb'    => 'novaflow_utility_menu_fallback',
								)
							);
							?>
						</nav>
					</div>

					<div class="nf-utilitybar-right">
						<?php if ( array_filter( array( $options['topbar_facebook_url'], $options['topbar_instagram_url'], $options['topbar_pinterest_url'], $options['topbar_youtube_url'], $options['topbar_x_url'], $options['topbar_tiktok_url'], $options['topbar_linkedin_url'] ) ) ) : ?>
						<span class="nf-utility-follow-label"><?php esc_html_e( 'Social networks', 'novaflow' ); ?></span>
						<div class="nf-utility-socials" role="group" aria-label="<?php esc_attr_e( 'Social links', 'novaflow' ); ?>">
							<?php
							$reference_socials = array(
								array(
									'key'   => 'topbar_facebook_url',
									'icon'  => 'facebook',
									'asset' => 'facebook.svg',
									'label' => 'Facebook',
								),
								array(
									'key'   => 'topbar_instagram_url',
									'icon'  => 'instagram',
									'asset' => 'instagram.svg',
									'label' => 'Instagram',
								),
								array(
									'key'   => 'topbar_pinterest_url',
									'icon'  => 'pinterest',
									'asset' => 'pinterest.svg',
									'label' => 'Pinterest',
								),
								array(
									'key'   => 'topbar_youtube_url',
									'icon'  => 'youtube',
									'asset' => 'youtube.svg',
									'label' => 'YouTube',
								),
								array(
									'key'   => 'topbar_tiktok_url',
									'icon'  => 'tiktok',
									'asset' => 'tiktok.svg',
									'label' => 'TikTok',
								),
								array(
									'key'   => 'topbar_linkedin_url',
									'icon'  => 'linkedin',
									'asset' => 'linkedin.svg',
									'label' => 'LinkedIn',
								),
								array(
									'key'   => 'topbar_x_url',
									'icon'  => 'x',
									'asset' => 'x.svg',
									'label' => 'X',
								),
							);
							foreach ( $reference_socials as $social ) :
								$social_url = isset( $options[ $social['key'] ] ) ? $options[ $social['key'] ] : '';
								if ( ! $social_url ) {
									continue;
								}
								/* translators: %s: Social network name. */
								$social_label = sprintf( __( '%s (opens in a new tab)', 'novaflow' ), $social['label'] );
								?>
								<a class="nf-social-link nf-social-<?php echo esc_attr( $social['icon'] ); ?>" href="<?php echo esc_url( $social_url ); ?>" target="_blank" rel="nofollow noopener noreferrer" title="<?php echo esc_attr( $social_label ); ?>" aria-label="<?php echo esc_attr( $social_label ); ?>">
									<img class="nf-social-brand-icon" src="<?php echo esc_url( get_theme_file_uri( 'assets/icons/social/' . $social['asset'] ) ); ?>" width="14" height="14" alt="" aria-hidden="true">
								</a>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<button class="nf-utility-mode" type="button" data-nf-color-mode aria-pressed="false" aria-label="<?php esc_attr_e( 'Change color mode', 'novaflow' ); ?>">
							<?php novaflow_icon( 'sun' ); ?>
							<span data-nf-color-mode-label><?php esc_html_e( 'Light Mode', 'novaflow' ); ?></span>
						</button>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$header_ad_id      = absint( $options['header_ad_image_id'] );
		$header_ad_url     = $options['header_ad_url'];
		$header_ad_alt     = $options['header_ad_alt'];
		$header_ad_target  = ! empty( $options['header_ad_new_tab'] ) ? '_blank' : '';
		$header_ad_rel     = 'noopener noreferrer';
		$header_ad_enabled = ! empty( $options['show_header_ad'] ) && ( $header_ad_id || is_active_sidebar( 'header-banner' ) );

		if ( $header_ad_id && ! $header_ad_alt ) {
			$header_ad_alt = get_post_meta( $header_ad_id, '_wp_attachment_image_alt', true );
		}
		if ( ! $header_ad_alt ) {
			$header_ad_alt = __( 'Header banner', 'novaflow' );
		}
		?>
		<div class="nf-brandbar">
			<div class="nf-container nf-brandbar-inner nf-brandbar-layout<?php echo esc_attr( $header_ad_enabled ? ' nf-brandbar-has-ad' : ' nf-brandbar-modern' ); ?>">
				<div class="nf-brand">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
					<a class="nf-color-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img
							class="nf-brand-wordmark"
							src="<?php echo esc_url( add_query_arg( 'ver', novaflow_asset_version( 'assets/images/novaflow-logo.webp' ), get_theme_file_uri( 'assets/images/novaflow-logo.webp' ) ) ); ?>"
							width="600"
							height="200"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							loading="eager"
							decoding="async"
						>
					</a>
					<?php endif; ?>
				</div>

				<?php if ( $header_ad_enabled ) : ?>
					<div class="nf-header-ad-slot">
						<?php if ( $header_ad_id ) : ?>
							<aside class="nf-header-ad" aria-label="<?php esc_attr_e( 'Header banner', 'novaflow' ); ?>">
								<?php if ( $header_ad_url ) : ?>
									<a href="<?php echo esc_url( $header_ad_url ); ?>" rel="<?php echo esc_attr( $header_ad_rel ); ?>"<?php echo $header_ad_target ? ' target="' . esc_attr( $header_ad_target ) . '"' : ''; ?>>
									<?php if ( $header_ad_target ) : ?>
										<span class="screen-reader-text"><?php esc_html_e( 'Opens in a new tab.', 'novaflow' ); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<?php
								echo wp_get_attachment_image(
									$header_ad_id,
									'novaflow-leaderboard',
									false,
									array(
										'class'         => 'nf-header-ad-image',
										'alt'           => $header_ad_alt,
										'loading'       => 'eager',
										'decoding'      => 'async',
										'fetchpriority' => 'auto',
									)
								); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated by wp_get_attachment_image().
								?>
								<?php if ( $header_ad_url ) : ?>
									</a>
								<?php endif; ?>
							</aside>
						<?php else : ?>
							<aside class="nf-header-ad nf-header-widget-area" aria-label="<?php esc_attr_e( 'Header banner', 'novaflow' ); ?>">
								<?php novaflow_dynamic_sidebar( 'header-banner' ); ?>
							</aside>
						<?php endif; ?>
					</div>
					<div class="nf-header-search" aria-label="<?php esc_attr_e( 'Header search', 'novaflow' ); ?>">
						<?php
						get_search_form(
							array(
								'aria_label'       => __( 'Header search', 'novaflow' ),
								'novaflow_variant' => 'header',
								'placeholder'      => __( 'Search the site…', 'novaflow' ),
							)
						);
						?>
					</div>
				<?php else : ?>
					<div class="nf-header-modern-center">
						<div class="nf-header-search nf-header-search-modern" aria-label="<?php esc_attr_e( 'Header search', 'novaflow' ); ?>">
							<?php
							get_search_form(
								array(
									'aria_label'       => __( 'Header search', 'novaflow' ),
									'novaflow_variant' => 'header-catalog',
									'placeholder'      => __( 'Search anything you are curious about…', 'novaflow' ),
								)
							);
							?>
						</div>

						<?php $shortcut_urls = novaflow_get_header_shortcut_urls(); ?>
						<nav class="nf-header-quicklinks" aria-label="<?php esc_attr_e( 'Header shortcuts', 'novaflow' ); ?>">
							<a class="nf-header-quicklink nf-header-quicklink-inspiration" href="<?php echo esc_url( $shortcut_urls['inspiration'] ); ?>">
								<span class="nf-header-quick-icon" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/header-icons/inspiration.svg' ) ); ?>" width="25" height="25" alt=""></span>
								<span class="nf-header-quick-copy"><strong><?php esc_html_e( 'Inspiring Ideas', 'novaflow' ); ?></strong><small><?php esc_html_e( 'Better Living', 'novaflow' ); ?></small></span>
							</a>
							<a class="nf-header-quicklink nf-header-quicklink-editor" href="<?php echo esc_url( $shortcut_urls['editor'] ); ?>">
								<span class="nf-header-quick-icon" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/header-icons/editor-pick.svg' ) ); ?>" width="25" height="25" alt=""></span>
								<span class="nf-header-quick-copy"><strong><?php esc_html_e( "Editor's selection", 'novaflow' ); ?></strong><small><?php esc_html_e( 'Featured', 'novaflow' ); ?></small></span>
							</a>
							<a class="nf-header-quicklink nf-header-quicklink-trends" href="<?php echo esc_url( $shortcut_urls['trends'] ); ?>">
								<span class="nf-header-quick-icon" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/header-icons/trending.svg' ) ); ?>" width="25" height="25" alt=""></span>
								<span class="nf-header-quick-copy"><strong><?php esc_html_e( 'Trends', 'novaflow' ); ?></strong><small><?php esc_html_e( 'Newest', 'novaflow' ); ?></small></span>
							</a>
						</nav>
					</div>
				<?php endif; ?>
			</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<div class="nf-navbar">
			<div class="nf-container nf-navbar-inner">
				<button type="button" class="nf-menu-toggle-review" aria-controls="site-navigation" aria-expanded="false">
					<?php novaflow_icon( 'menu' ); ?>
					<?php esc_html_e( 'Menu', 'novaflow' ); ?>
				</button>
				<nav id="site-navigation" class="nf-primary-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'novaflow' ); ?>">
					<button class="nf-icon-button nf-menu-close" type="button">
						<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'novaflow' ); ?></span>
						<?php novaflow_icon( 'close' ); ?>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'nf-nav-list nf-nav-ready',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
					?>
					<div class="nf-menu-extra" hidden>
						<button class="nf-menu-extra-toggle" type="button" aria-label="<?php esc_attr_e( 'More', 'novaflow' ); ?>" aria-expanded="false" aria-controls="nf-menu-extra-list">
							<span class="nf-menu-extra-icon" aria-hidden="true"><?php novaflow_icon( 'menu' ); ?></span>
						</button>
						<ul id="nf-menu-extra-list" class="nf-menu-extra-list" hidden></ul>
					</div>
				</nav>
			</div>
			<div class="nf-nav-backdrop" data-nf-menu-backdrop hidden aria-hidden="true"></div>
		</div>
		<?php endif; ?>
		</div>

		<?php if ( get_header_image() ) : ?>
			<div class="nf-custom-header-media">
				<div class="nf-container">
					<img src="<?php echo esc_url( get_header_image() ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $options['show_ticker'] ) ) : ?>
			<div class="nf-ticker nf-breaking-bar">
				<div class="nf-container nf-ticker-inner">
					<strong class="nf-ticker-label">
						<?php novaflow_icon( 'flash' ); ?>
						<span><?php esc_html_e( 'Breaking News', 'novaflow' ); ?></span>
					</strong>
					<div class="nf-ticker-viewport" data-ticker-viewport>
						<div class="nf-ticker-track" data-ticker-track>
							<?php
							$ticker_query_args = array(
								'post_type'           => 'post',
								'post_status'         => 'publish',
								'posts_per_page'      => 5,
								'ignore_sticky_posts' => true,
								'no_found_rows'       => true,
							);
							/**
							 * Filter the breaking-news ticker query.
							 *
							 * @param array<string,mixed> $ticker_query_args WP_Query arguments.
							 */
							$ticker_query_args = (array) apply_filters( 'novaflow_ticker_query_args', $ticker_query_args );
							$ticker_query      = new WP_Query(
								$ticker_query_args
							);

							if ( $ticker_query->have_posts() ) :
								while ( $ticker_query->have_posts() ) :
									$ticker_query->the_post();
									?>
									<a class="nf-ticker-item" href="<?php the_permalink(); ?>">
										<time class="nf-ticker-time" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_time( 'H:i' ) ); ?></time>
										<span class="nf-ticker-title"><?php echo esc_html( novaflow_get_post_display_title() ); ?></span>
									</a>
									<?php
								endwhile;
								wp_reset_postdata();
							else :
								?>
								<span class="nf-ticker-empty"><?php esc_html_e( 'Published posts will appear here as a breaking-news stream.', 'novaflow' ); ?></span>
								<?php
							endif;
							?>
						</div>
					</div>
					<div class="nf-ticker-controls" aria-label="<?php esc_attr_e( 'Breaking news controls', 'novaflow' ); ?>">
						<button type="button" class="nf-ticker-control nf-ticker-prev" data-ticker-prev aria-label="<?php esc_attr_e( 'Previous breaking news', 'novaflow' ); ?>"><span aria-hidden="true">‹</span></button>
						<button type="button" class="nf-ticker-control nf-ticker-next" data-ticker-next aria-label="<?php esc_attr_e( 'Next breaking news', 'novaflow' ); ?>"><span aria-hidden="true">›</span></button>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</header>
