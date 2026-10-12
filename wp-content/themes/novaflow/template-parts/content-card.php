<?php
/**
 * Archive-card template part.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>
<article <?php post_class( 'nf-archive-card' ); ?>>
	<a class="nf-card-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label() ); ?>"></a>
	<?php novaflow_post_media( 'novaflow-card' ); ?>
	<div class="nf-archive-card-body">
		<?php novaflow_category_badge(); ?>
		<h2><?php echo esc_html( novaflow_get_post_display_title() ); ?></h2>
		<p><?php echo esc_html( novaflow_get_post_card_excerpt( 20 ) ); ?></p>
		<?php novaflow_post_stats(); ?>
	</div>
</article>
