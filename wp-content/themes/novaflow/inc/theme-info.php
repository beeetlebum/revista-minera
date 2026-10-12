<?php
/**
 * Theme information screen.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register the Novaflow information screen under Appearance.
 *
 * @return void
 */
function novaflow_register_theme_info_page() {
	add_theme_page(
		__( 'About Novaflow', 'novaflow' ),
		__( 'About Novaflow', 'novaflow' ),
		'edit_theme_options',
		'novaflow-theme-info',
		'novaflow_render_theme_info_page'
	);
}
add_action( 'admin_menu', 'novaflow_register_theme_info_page' );

/**
 * Add styles only to the Novaflow information screen.
 *
 * @param string $hook_suffix Current admin page hook.
 * @return void
 */
function novaflow_theme_info_admin_styles( $hook_suffix ) {
	if ( 'appearance_page_novaflow-theme-info' !== $hook_suffix ) {
		return;
	}

	$css = '
		.novaflow-info{max-width:1120px;margin:24px 20px 40px 0;color:#1d2327}
		.novaflow-info__hero{padding:32px;background:#fff;border-left:4px solid #e91e63;box-shadow:0 1px 2px rgba(0,0,0,.06)}
		.novaflow-info__hero h1{margin:0 0 10px;font-size:32px;line-height:1.2}
		.novaflow-info__hero p{max-width:780px;margin:0;font-size:16px;line-height:1.65;color:#50575e}
		.novaflow-info__meta{display:flex;flex-wrap:wrap;gap:10px 22px;margin-top:20px;padding-top:18px;border-top:1px solid #dcdcde}
		.novaflow-info__meta span{font-size:14px}
		.novaflow-info__actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px}
		.novaflow-info__grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:16px}
		.novaflow-info__panel{padding:24px;background:#fff;border:1px solid #dcdcde}
		.novaflow-info__panel--wide{grid-column:1/-1}
		.novaflow-info__panel h2{margin:0 0 14px;font-size:20px}
		.novaflow-info__panel h3{margin:20px 0 8px;font-size:15px}
		.novaflow-info__panel p,.novaflow-info__panel li{font-size:14px;line-height:1.65}
		.novaflow-info__panel ul{margin:0 0 0 18px;list-style:disc}
		.novaflow-info__steps{margin:0;counter-reset:steps;list-style:none!important}
		.novaflow-info__steps li{position:relative;margin:0 0 12px;padding-left:34px;counter-increment:steps}
		.novaflow-info__steps li:before{content:counter(steps);position:absolute;left:0;top:0;width:24px;height:24px;border-radius:50%;background:#e91e63;color:#fff;text-align:center;font-weight:600;line-height:24px}
		.novaflow-info__status{display:inline-block;padding:3px 9px;border-radius:12px;background:#edfaef;color:#116329;font-weight:600}
		@media(max-width:782px){.novaflow-info{margin-right:10px}.novaflow-info__grid{grid-template-columns:1fr}.novaflow-info__panel--wide{grid-column:auto}}
	';

	wp_register_style( 'novaflow-theme-info', false, array(), NOVAFLOW_VERSION );
	wp_enqueue_style( 'novaflow-theme-info' );
	wp_add_inline_style( 'novaflow-theme-info', $css );
}
add_action( 'admin_enqueue_scripts', 'novaflow_theme_info_admin_styles' );

/**
 * Render the theme information screen.
 *
 * @return void
 */
function novaflow_render_theme_info_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$theme         = wp_get_theme();
	$theme_headers = get_file_data(
		get_stylesheet_directory() . '/style.css',
		array( 'tested_up_to' => 'Tested up to' )
	);
	?>
	<div class="wrap novaflow-info">
		<section class="novaflow-info__hero">
			<h1><?php esc_html_e( 'Welcome to Novaflow', 'novaflow' ); ?></h1>
			<p><?php esc_html_e( 'Novaflow is a responsive WordPress news and magazine theme built for clear editorial hierarchy, colorful category discovery, fast reading, and native WordPress publishing workflows.', 'novaflow' ); ?></p>
			<div class="novaflow-info__meta">
				<span><strong><?php esc_html_e( 'Version:', 'novaflow' ); ?></strong> <?php echo esc_html( $theme->get( 'Version' ) ); ?></span>
				<span><strong><?php esc_html_e( 'License:', 'novaflow' ); ?></strong> <?php echo esc_html( $theme->get( 'License' ) ); ?></span>
				<span><strong><?php esc_html_e( 'Text domain:', 'novaflow' ); ?></strong> novaflow</span>
				<span class="novaflow-info__status"><?php esc_html_e( 'Ready to publish', 'novaflow' ); ?></span>
			</div>
			<div class="novaflow-info__actions">
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Open Customizer', 'novaflow' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Manage Menus', 'novaflow' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Manage Widgets', 'novaflow' ); ?></a>
			</div>
		</section>

		<div class="novaflow-info__grid">
			<section class="novaflow-info__panel">
				<h2><?php esc_html_e( 'Getting Started', 'novaflow' ); ?></h2>
				<ol class="novaflow-info__steps">
					<li><?php esc_html_e( 'Add your site title or upload a custom logo in the Customizer.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Assign menus to the Primary, Utility, and Footer menu locations.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Add WordPress widgets to the primary sidebar, header banner, and footer columns.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Publish posts with featured images, categories, tags, and excerpts.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Choose the latest-posts homepage or a static front page under Reading settings.', 'novaflow' ); ?></li>
				</ol>
			</section>

			<section class="novaflow-info__panel">
				<h2><?php esc_html_e( 'Theme Features', 'novaflow' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Responsive editorial layouts with a right sidebar.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Featured images, category navigation, post tags, comments, archives, and search.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Three registered navigation locations and five widget areas.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Block styles, wide alignment, responsive embeds, and editor styles.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Custom logo, colors, background, header image, and advertisement controls.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Translation-ready source strings and right-to-left stylesheet support.', 'novaflow' ); ?></li>
				</ul>
			</section>

			<section class="novaflow-info__panel">
				<h2><?php esc_html_e( 'System Requirements', 'novaflow' ); ?></h2>
				<ul>
					<li>
						<?php
						/* translators: %s: Minimum supported WordPress version. */
						printf( esc_html__( 'WordPress %s or newer', 'novaflow' ), esc_html( $theme->get( 'RequiresWP' ) ) );
						?>
					</li>
					<li>
						<?php
						/* translators: %s: Minimum supported PHP version. */
						printf( esc_html__( 'PHP %s or newer', 'novaflow' ), esc_html( $theme->get( 'RequiresPHP' ) ) );
						?>
					</li>
					<li>
						<?php
						/* translators: %s: Latest tested WordPress version. */
						printf( esc_html__( 'Tested up to WordPress %s', 'novaflow' ), esc_html( $theme_headers['tested_up_to'] ) );
						?>
					</li>
				</ul>
				<h3><?php esc_html_e( 'Recommended content', 'novaflow' ); ?></h3>
				<p><?php esc_html_e( 'Use descriptive post titles, concise excerpts, optimized featured images, relevant categories, and focused tags. WordPress supplies canonical URLs, feeds, sitemaps, and semantic document titles through its native APIs.', 'novaflow' ); ?></p>
			</section>

			<section class="novaflow-info__panel">
				<h2><?php esc_html_e( 'Accessibility and Performance', 'novaflow' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Keyboard-accessible skip navigation and visible focus states.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Semantic headings, landmarks, labels, and native WordPress comment markup.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'Responsive image sizes, lazy loading for secondary images, and local theme assets.', 'novaflow' ); ?></li>
					<li><?php esc_html_e( 'No tracking, analytics, remote fonts, or remote asset requests are enabled by the theme.', 'novaflow' ); ?></li>
				</ul>
			</section>

			<section class="novaflow-info__panel novaflow-info__panel--wide">
				<h2><?php esc_html_e( 'License, Privacy, and Support', 'novaflow' ); ?></h2>
				<p><?php esc_html_e( 'Novaflow is distributed under the GNU General Public License version 2 or later. Bundled asset authorship and license details are documented in readme.txt and the license files included with the theme.', 'novaflow' ); ?></p>
				<p><?php esc_html_e( 'The theme does not collect personal information, perform telemetry, or contact external services by default. Site owners remain responsible for the privacy practices of their content, plugins, embedded media, analytics, and third-party services.', 'novaflow' ); ?></p>
				<p><?php esc_html_e( 'For troubleshooting, first confirm that WordPress and PHP meet the requirements above, temporarily disable plugins to check for conflicts, verify menu and widget assignments, and review the bundled readme.txt file.', 'novaflow' ); ?></p>
			</section>
		</div>
	</div>
	<?php
}
