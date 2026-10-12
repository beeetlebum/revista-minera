<?php
/**
 * Archive template.
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
			<span class="nf-eyebrow"><?php esc_html_e( 'Archive', 'novaflow' ); ?></span>
			<?php the_archive_title( '<h1>', '</h1>' ); ?>
			<?php the_archive_description( '<div class="nf-archive-description">', '</div>' ); ?>
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
