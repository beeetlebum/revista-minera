<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Beyond Blog
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>
	<div id="page" class="site">

		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'beyond-blog' ); ?></a>

		<div id="loader" class="loader-3">
			<div class="loader-container">
				<div id="preloader">
				</div>
			</div>
		</div><!-- #loader -->

		<header id="masthead" class="site-header">
			<?php
			$topbar_section = get_theme_mod( 'beyond_blog_enable_topbar_section', true );
			if ( $topbar_section === true ) {
				?>
				<div class="beyound-blog-top-header">
					<div class="section-wrapper">
						<div class="top-header-container">
							<div class="top-header-right">
								<?php if ( has_nav_menu( 'social' ) ) { ?>
									<div class="header-social-icon-container">
										<?php
										wp_nav_menu(
											array(
												'container'      => 'ul',
												'menu_class'     => 'social-links',
												'theme_location' => 'social',
												'link_before'    => '<span class="screen-reader-text">',
												'link_after'     => '</span>',
											)
										);
										?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="beyound-blog-middle-header">
				<?php if ( get_header_image() ) : ?>
					<div class="header-bg-image">
						<img src="<?php echo esc_url( get_header_image() ); ?>" alt="<?php esc_attr_e( 'Header Image', 'beyond-blog' ); ?>">
					</div>
				<?php endif; ?>
				<div class="section-wrapper">
					<div class="middle-header-container">
						<div class="middle-header-left">
							<?php
							$custom_button     = get_theme_mod( 'beyond_blog_header_custom_button', __( 'Subscription', 'beyond-blog' ) );
							$custom_button_url = get_theme_mod( 'beyond_blog_header_custom_button_url', '' );
							if ( ! empty( $custom_button ) ) {
								?>
								<div class="header-custom-button">
									<a href="<?php echo esc_url( $custom_button_url ); ?>"><?php echo esc_html( $custom_button ); ?></a>
								</div>
							<?php } ?>
						</div>
						<div class="site-branding">
							<?php if ( has_custom_logo() ) { ?>
								<div class="site-logo" style="max-width: var(--logo-size-custom);">
									<?php the_custom_logo(); ?>
								</div>
							<?php } ?>
							<div class="site-identity">
								<?php if ( is_front_page() && is_home() ) : ?>
									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php else : ?>
									<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
									<?php
								endif;
								$beyond_blog_description = get_bloginfo( 'description', 'display' );
								if ( $beyond_blog_description || is_customize_preview() ) :
									?>
									<p class="site-description"><?php echo $beyond_blog_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
								<?php endif; ?>
							</div>
						</div>
						<div class="middle-header-right">
							<div class="beyond-blog-header-search">
								<div class="header-search-wrap">
									<a href="#" class="search-icon"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></a>
									<div class="header-search-form">
										<?php get_search_form(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="beyond-blog-header">
				<div class="navigation-outer-wrapper">
					<div class="beyond-blog-navigation">
						<div class="section-wrapper">
							<div class="navigation-wrapper">
								<div class="beyond-blog-navigation-container">
									<nav id="site-navigation" class="main-navigation">
										<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
											<span class="ham-icon"></span>
											<span class="ham-icon"></span>
											<span class="ham-icon"></span>
										</button>
										<div class="navigation-area">
											<?php
											if ( has_nav_menu( 'primary' ) ) {
												wp_nav_menu(
													array(
														'theme_location' => 'primary',
														'menu_id' => 'primary-menu',
													)
												);
											}
											?>
										</div>
									</nav><!-- #site-navigation -->
								</div>
								<div class="navigation-right-part">
									<?php
									$args              = array(
										'posts_per_page' => 1,
										'post_type'      => 'post',
										'ignore_sticky_posts' => true,
										'orderby'        => 'rand',
									);
									$random_post_query = new WP_Query( $args );
									if ( $random_post_query->have_posts() ) {
										while ( $random_post_query->have_posts() ) :
											$random_post_query->the_post();
											?>
											<a href="<?php the_permalink(); ?>" class="random-post"><i class="fa-solid fa-shuffle" title="<?php esc_attr_e( 'View Random Post', 'beyond-blog' ); ?>"></i></a>
											<?php
										endwhile;
										wp_reset_postdata();
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end of navigation -->
		</header><!-- #masthead -->

		<?php
		if ( ! is_front_page() || is_home() ) {
			if ( is_front_page() ) {

				require get_template_directory() . '/sections/sections.php';

			}
			?>
			<div class="beyond-blog-main-wrapper">
				<div class="section-wrapper">
					<div class="beyond-blog-container-wrapper">
					<?php } ?>