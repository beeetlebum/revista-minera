<?php
/**
 * Page template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

get_header();
?>
<main id="primary" tabindex="-1" class="nf-main nf-page-main">
	<div class="nf-container nf-page-layout">
		<div class="nf-page-content-column">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part(
					'template-parts/content',
					'page',
					array(
						'inside_sidebar_layout' => true,
					)
				);
			endwhile;
			?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main>
<?php
get_footer();
