<?php
/**
 * Page content template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>
<?php
$page_article_classes = 'nf-page-article';
if ( empty( $args['inside_sidebar_layout'] ) ) {
	$page_article_classes = 'nf-container ' . $page_article_classes;
}
if ( ! empty( $args['compact_front_page'] ) ) {
	$page_article_classes .= ' nf-front-page-intro';
}
?>
<article <?php post_class( $page_article_classes ); ?>>
	<header class="nf-page-header">
		<span class="nf-eyebrow"><?php esc_html_e( 'Page', 'novaflow' ); ?></span>
		<h1><?php echo esc_html( novaflow_get_post_display_title( get_the_ID(), __( 'Untitled page', 'novaflow' ) ) ); ?></h1>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="nf-page-featured">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>
</article>
