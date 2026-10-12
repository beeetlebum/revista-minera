<?php
/**
 * Main fallback template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();

$page_title = single_post_title( '', false );
?>
<main id="primary" tabindex="-1" class="nf-main nf-archive-main">
	<div class="nf-container">
		<header class="nf-page-header">
			<span class="nf-eyebrow"><?php esc_html_e( 'Newsroom', 'novaflow' ); ?></span>
			<h1><?php echo esc_html( $page_title ? $page_title : __( 'Latest Posts', 'novaflow' ) ); ?></h1>
			<p><?php esc_html_e( 'The latest reporting, ideas and conversations.', 'novaflow' ); ?></p>
		</header>

		<div class="nf-archive-layout">
			<div class="nf-archive-grid">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'card' );
					endwhile;
					the_posts_pagination();
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php
get_footer();
