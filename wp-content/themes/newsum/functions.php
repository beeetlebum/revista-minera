<?php
/**
 * NewSum functions and definitions
 */

if ( ! defined( 'NEWSUM_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'NEWSUM_VERSION', '1.0.4' );
}

define( 'NEWSUM_DIR', get_template_directory() );
define( 'NEWSUM_URI', get_template_directory_uri() );

// Theme Dashboard & TGMPA Plugin Recommendation
require_once NEWSUM_DIR . '/inc/class-newsum-dashboard.php';
require_once NEWSUM_DIR . '/inc/newsum-tgmpa.php';
require_once NEWSUM_DIR . '/inc/wptt-webfont-loader.php';

new NewSum_Blog_Dashboard();

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function newsum_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'newsum_content_width', 1200 );
}
add_action( 'after_setup_theme', 'newsum_content_width', 0 );

/**
 * Check if NewSum Pro is active.
 */
if ( ! function_exists( 'newsum_is_pro_active' ) ) {
    function newsum_is_pro_active() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return is_plugin_active( 'newsum-pro/newsum-pro.php' );
    }
}

/**
 * Sanitize SVG strings for the customizer controls.
 */
if ( ! function_exists( 'newsum_sanitize_svg' ) ) {
    function newsum_sanitize_svg( $svg ) {
        return $svg;
    }
}

function newsum_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'newsum', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Wide and full alignments
	add_theme_support( 'align-wide' );

	// Responsive embedded content
	add_theme_support( 'responsive-embeds' );

	// Editor styles support
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );

	// Core Block Styles Support
	add_theme_support( 'wp-block-styles' );

	// Custom background support
	add_theme_support( 'custom-background', array(
		'default-color' => '0a0a0a',
	) );

	// Custom header support
	add_theme_support( 'custom-header', array(
		'default-image' => '',
		'width'         => 1200,
		'height'        => 400,
		'flex-height'   => true,
		'flex-width'    => true,
		'header-text'   => false,
	) );

	// Custom logo support
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Register navigation menus.
	register_nav_menus(
		array(
			'menu-1'      => esc_html__( 'Primary', 'newsum' ),
			'drawer-menu' => esc_html__( 'Drawer Menu (Desktop Secondary)', 'newsum' ),
			'footer-menu' => esc_html__( 'Footer Legal Menu', 'newsum' ),
		)
	);

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Selective refresh for widgets and customizer edit shortcuts.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action( 'after_setup_theme', 'newsum_setup' );

/**
 * Register custom block styles for Newsum.
 */
function newsum_register_block_styles() {
	if ( function_exists( 'register_block_style' ) ) {
		register_block_style(
			'core/quote',
			array(
				'name'  => 'newsum-editorial-quote',
				'label' => __( 'Editorial Highlight', 'newsum' ),
			)
		);
		register_block_style(
			'core/paragraph',
			array(
				'name'  => 'newsum-lead-paragraph',
				'label' => __( 'Lead Dispatch', 'newsum' ),
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'  => 'newsum-kicker-heading',
				'label' => __( 'Tech Kicker', 'newsum' ),
			)
		);
	}
}
add_action( 'init', 'newsum_register_block_styles' );

/**
 * Fallback callback for Primary Navigation Menu.
 * Outputs semantic <ul> structure matching wp_nav_menu.
 */
if ( ! function_exists( 'newsum_nav_fallback' ) ) {
	function newsum_nav_fallback() {
		echo '<ul id="primary-menu" class="nav-menu">';
		echo '<li class="menu-item' . ( is_front_page() ? ' current-menu-item' : '' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'newsum' ) . '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 2,
		) );
		echo '</ul>';
	}
}

/**
 * Enqueue scripts and styles.
 */
function newsum_scripts() {
	// Dynamically build Google Fonts URL
	$heading_font = get_theme_mod( 'newsum_heading_font', 'Playfair Display' );
	$body_font    = get_theme_mod( 'newsum_body_font', 'Inter' );

	$system_fonts = array( 'Georgia', 'Arial', 'Helvetica', 'Times New Roman', 'System / Uploaded Font' );

	$font_families = array( 'Fira+Code:wght@400;600' );

	if ( ! in_array( $heading_font, $system_fonts, true ) && ! empty( $heading_font ) ) {
		$font_families[] = str_replace( ' ', '+', $heading_font ) . ':ital,wght@0,400;0,500;0,700;1,500;1,700';
	}
	if ( ! in_array( $body_font, $system_fonts, true ) && ! empty( $body_font ) && $body_font !== $heading_font ) {
		$font_families[] = str_replace( ' ', '+', $body_font ) . ':wght@300;400;500;600;700';
	}

	$fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $font_families ) . '&display=swap';

	// Load local webfonts via WPTT webfont loader for GDPR & privacy compliance
	if ( function_exists( 'wptt_get_webfont_url' ) ) {
		wp_enqueue_style( 'newsum-google-fonts', wptt_get_webfont_url( $fonts_url ), array(), null );
	} else {
		wp_enqueue_style( 'newsum-google-fonts', $fonts_url, array(), null );
	}

	wp_enqueue_style( 'newsum-style', get_stylesheet_uri(), array(), NEWSUM_VERSION );
	
	wp_enqueue_script( 'newsum-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), NEWSUM_VERSION, true );
	wp_enqueue_script( 'newsum-main-js', get_template_directory_uri() . '/assets/js/main.js', array(), NEWSUM_VERSION, true );

	wp_localize_script( 'newsum-main-js', 'newsumAjax', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'newsum_ajax_nonce' ),
		'loading' => __( 'Loading dispatches...', 'newsum' ),
		'noMore'  => __( 'All stories loaded', 'newsum' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'newsum_scripts' );

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Filter body classes to conditionally enable Pro layout features.
 */
function newsum_body_classes( $classes ) {
	$show_desktop_drawer = get_theme_mod( 'newsum_header_show_drawer', true );
	if ( $show_desktop_drawer && function_exists( 'newsum_is_pro_active' ) && newsum_is_pro_active() ) {
		$classes[] = 'has-pro-drawer';
	}

	$header_width = get_theme_mod( 'newsum_header_width', 'contained' );
	$classes[]    = ( 'full' === $header_width ) ? 'header-width-full' : 'header-width-contained';

	return $classes;
}
add_filter( 'body_class', 'newsum_body_classes' );

/**
 * Output early theme mode initialization script to prevent FOUC (hooked to wp_head).
 */
function newsum_early_theme_mode_script() {
	$default_mode  = get_theme_mod( 'newsum_default_mode', 'light' );
	$is_customizer = is_customize_preview() ? 'true' : 'false';
	?>
	<script>
	(function() {
		try {
			var isCustomizer = <?php echo $is_customizer; ?>;
			var defaultMode = <?php echo wp_json_encode( $default_mode ); ?>;
			var storedTheme = isCustomizer ? null : (localStorage.getItem('newsum_theme') || localStorage.getItem('theme'));
			var activeTheme = storedTheme || defaultMode;

			if (activeTheme === 'auto') {
				activeTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) ? 'light' : 'dark';
			}

			if (activeTheme === 'light') {
				document.documentElement.classList.add('light-theme');
			} else {
				document.documentElement.classList.remove('light-theme');
			}
		} catch (e) {}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'newsum_early_theme_mode_script', 0 );

/**
 * Render dynamic or fallback social icons.
 *
 * @param string $context Context location ('topbar', 'drawer', 'footer').
 */
function newsum_render_social_icons( $context = 'topbar' ) {
	if ( function_exists( 'newsum_pro_render_social_icons' ) ) {
		newsum_pro_render_social_icons( $context );
		return;
	}

	$new_tab     = (bool) get_theme_mod( 'newsum_social_new_tab', true );
	$target_attr = $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';

	// Fallback for Free theme
	$facebook  = get_theme_mod( 'newsum_topbar_facebook', '#' );
	$instagram = get_theme_mod( 'newsum_topbar_instagram', '#' );
	$twitter   = get_theme_mod( 'newsum_topbar_twitter', '#' );
	$youtube   = get_theme_mod( 'newsum_topbar_youtube', '#' );

	if ( ! empty( $facebook ) ) :
		?>
		<a href="<?php echo esc_url( $facebook ); ?>" aria-label="Facebook"<?php echo $target_attr; ?>><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
		<?php
	endif;
	if ( ! empty( $instagram ) ) :
		?>
		<a href="<?php echo esc_url( $instagram ); ?>" aria-label="Instagram"<?php echo $target_attr; ?>><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 011.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772 4.915 4.915 0 01-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 011.153-1.772A4.897 4.897 0 015.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 5a5 5 0 100 10 5 5 0 000-10zm0 8.2a3.2 3.2 0 110-6.4 3.2 3.2 0 010 6.4zm4.204-7.854a1.14 1.14 0 11-2.28 0 1.14 1.14 0 012.28 0z"/></svg></a>
		<?php
	endif;
	if ( ! empty( $twitter ) ) :
		?>
		<a href="<?php echo esc_url( $twitter ); ?>" aria-label="X"<?php echo $target_attr; ?>><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg></a>
		<?php
	endif;
	if ( ! empty( $youtube ) ) :
		?>
		<a href="<?php echo esc_url( $youtube ); ?>" aria-label="YouTube"<?php echo $target_attr; ?>><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21.582 6.186a2.593 2.593 0 0 0-1.825-1.84C18.146 3.9 12 3.9 12 3.9s-6.146 0-7.757.446a2.593 2.593 0 0 0-1.825 1.84C2 7.828 2 12 2 12s0 4.172.418 5.814a2.593 2.593 0 0 0 1.825 1.84C5.854 20.1 12 20.1 12 20.1s6.146 0 7.757-.446a2.593 2.593 0 0 0 1.825-1.84C22 16.172 22 12 22 12s0-4.172-.418-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
		<?php
	endif;
}

/**
 * Calculate estimated reading time for a post.
 *
 * @param int $post_id Post ID.
 * @return int Minutes.
 */
function newsum_get_reading_time( $post_id = null ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return 2;
	}
	$words = str_word_count( wp_strip_all_tags( $post->post_content ) );
	$minutes = ceil( $words / 200 );
	return max( 1, (int) $minutes );
}

/**
 * Check if Reading List feature is active.
 * Gated to Pro version for now.
 *
 * @return bool
 */
function newsum_is_reading_list_enabled() {
	if ( function_exists( 'newsum_is_pro_active' ) && newsum_is_pro_active() ) {
		return (bool) get_theme_mod( 'newsum_enable_reading_list', true );
	}
	return false;
}

/**
 * Render bookmark button on post cards (Pro feature).
 *
 * @param int|string $post_id Post ID.
 * @param string     $title   Post Title.
 * @param string     $link    Post Link.
 */
function newsum_render_bookmark_button( $post_id, $title, $link ) {
	if ( ! newsum_is_reading_list_enabled() ) {
		return;
	}
	?>
	<button type="button" class="card-action-pill card-bookmark-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( $title ); ?>" data-post-link="<?php echo esc_url( $link ); ?>" aria-label="<?php esc_attr_e( 'Bookmark article', 'newsum' ); ?>">
		<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
	</button>
	<?php
}

/**
 * Get theme fallback image URI.
 *
 * @param int|string $index Image index or variant.
 * @return string
 */
function newsum_get_default_fallback_image( $index = 0 ) {
	$images = array(
		get_template_directory_uri() . '/assets/images/default-fallback.svg',
		get_template_directory_uri() . '/assets/images/fallback-1.svg',
	);
	$idx = absint( $index ) % count( $images );
	return $images[ $idx ];
}

/**
 * Render Header Top Bar (Selective Refresh partial & header template callback).
 */
function newsum_render_header_topbar() {
	$show_topbar = get_theme_mod( 'newsum_topbar_show', true );
	$topbar_text = get_theme_mod( 'newsum_topbar_text', '' );
	$show_date   = get_theme_mod( 'newsum_topbar_show_date', true );

	if ( empty( $topbar_text ) ) {
		$topbar_text = get_bloginfo( 'description' );
	}

	if ( ! $show_topbar ) {
		return;
	}
	?>
	<div class="header-super-top">
		<div class="header-inner utility-row">
			<?php if ( $show_date ) : ?>
			<div class="utility-date" id="currentDate"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $topbar_text ) ) : ?>
			<div class="utility-tagline"><?php echo esc_html( $topbar_text ); ?></div>
			<?php endif; ?>

			<div class="utility-socials">
				<?php newsum_render_social_icons( 'topbar' ); ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render Header Advertisement Slot (Selective Refresh partial & header template callback).
 */
function newsum_render_header_ad() {
	$show_header_ad   = (bool) get_theme_mod( 'newsum_header_ad_show', true );
	$header_ad_type   = get_theme_mod( 'newsum_header_ad_type', 'image' );
	$header_ad_image  = get_theme_mod( 'newsum_header_ad_image', '' );
	$header_ad_url    = get_theme_mod( 'newsum_header_ad_url', '#' );
	$header_ad_target = get_theme_mod( 'newsum_header_ad_target', true );
	$header_ad_code   = get_theme_mod( 'newsum_header_ad_code', '' );

	if ( ! $show_header_ad ) {
		return;
	}
	?>
	<div class="newsum-header-sponsor-frame">
		<?php if ( 'code' === $header_ad_type && ! empty( $header_ad_code ) ) : ?>
			<div class="newsum-sponsor-embed">
				<?php echo $header_ad_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php elseif ( 'image' === $header_ad_type && ! empty( $header_ad_image ) ) : ?>
			<a href="<?php echo esc_url( $header_ad_url ); ?>" class="newsum-sponsor-link"<?php echo $header_ad_target ? ' target="_blank" rel="noopener sponsored"' : ' rel="sponsored"'; ?>>
				<img src="<?php echo esc_url( $header_ad_image ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> Featured Sponsor" class="newsum-sponsor-media">
			</a>
		<?php else : ?>
			<div class="newsum-sponsor-spotlight">
				<span class="sponsor-tag"><?php esc_html_e( 'SPONSORSHIP', 'newsum' ); ?></span>
				<span class="sponsor-dims"><?php esc_html_e( '728 × 90 LEADERBOARD AVAILABLE', 'newsum' ); ?></span>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render Header Trending / Breaking News Ticker (Selective Refresh & Header callback).
 */
function newsum_render_trending_ticker() {
	$show_ticker  = get_theme_mod( 'newsum_header_show_ticker', true );
	$ticker_label = get_theme_mod( 'newsum_header_ticker_label', __( 'BREAKING', 'newsum' ) );
	$ticker_count = get_theme_mod( 'newsum_header_ticker_count', 5 );

	if ( ! $show_ticker ) {
		return;
	}
	?>
	<div class="trending-ticker" id="trendingTickerHeader">
		<div class="trending-ticker-inner">
			<span class="ticker-label"><?php echo esc_html( $ticker_label ); ?></span>
			<div class="ticker-track-wrapper" id="tickerWrapper">
				<div class="ticker-track" id="tickerTrack">
					<?php
					$ticker_posts = new WP_Query( array(
						'posts_per_page'      => absint( $ticker_count ),
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby'             => 'date',
						'order'               => 'DESC',
					) );
					if ( $ticker_posts->have_posts() ) :
						while ( $ticker_posts->have_posts() ) : $ticker_posts->the_post();
					?>
					<div class="ticker-item">
						<?php if ( has_post_thumbnail() ) : ?>
						<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ); ?>" class="ticker-thumb" alt="<?php the_title_attribute(); ?>">
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>"><span><?php the_title(); ?></span></a>
					</div>
					<span class="ticker-sep">•</span>
					<?php
						endwhile;
						wp_reset_postdata();
					else :
					?>
					<div class="ticker-item"><span><?php esc_html_e( 'No posts yet. Create your first post!', 'newsum' ); ?></span></div>
					<?php endif; ?>
				</div>
			</div>
			<button class="ticker-toggle" id="tickerToggle" aria-label="<?php esc_attr_e( 'Pause/Play Ticker', 'newsum' ); ?>">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
			</button>
		</div>
	</div>
	<?php
}

/**
 * Include Homepage Dynamic Sections & Selective Refresh Handlers.
 */
require get_template_directory() . '/inc/homepage-sections.php';

/**
 * Include Block Patterns.
 */
require get_template_directory() . '/inc/patterns.php';

/**
 * Include Native Breadcrumbs & Schema.org Structured Data.
 */
require_once get_template_directory() . '/inc/breadcrumbs.php';

/**
 * Include AJAX Archive Pagination & Card Rendering.
 */
require_once get_template_directory() . '/inc/ajax-pagination.php';

/**
 * Include Custom Widgets.
 */
require_once get_template_directory() . '/inc/widgets/class-newsum-author-widget.php';
require_once get_template_directory() . '/inc/widgets/class-newsum-trending-widget.php';

/**
 * Register Widget Areas and Custom Widgets.
 */
function newsum_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'newsum' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in the main publication sidebar across archives, blog posts, and single articles.', 'newsum' ),
		'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="sidebar-widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'newsum' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets to display in the publication footer.', 'newsum' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );

	// Register Custom Widgets
	register_widget( 'NewSum_Author_Profile_Widget' );
	register_widget( 'NewSum_Trending_Stories_Widget' );
}
add_action( 'widgets_init', 'newsum_widgets_init' );

/**
 * Automatically strip tech-specific leading slashes from all widget titles.
 */
function newsum_clean_widget_title( $title ) {
	return preg_replace( '/^\/\/\s*/', '', (string) $title );
}
add_filter( 'widget_title', 'newsum_clean_widget_title' );

