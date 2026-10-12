<?php
/**
 * Search-results template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();
?>
<main id="primary" tabindex="-1" class="nf-main nf-archive-main">
	<div class="nf-container">
		<header class="nf-page-header">
			<span class="nf-eyebrow"><?php esc_html_e( 'Search results', 'novaflow' ); ?></span>
			<h1>
				<?php
				printf(
					/* translators: %s: Search query. */
					esc_html__( 'Results for “%s”', 'novaflow' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
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
