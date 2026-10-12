<?php
/**
 * Template part displayed when no content is found.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>
<section class="nf-empty-card">
	<span class="nf-eyebrow"><?php esc_html_e( 'Nothing here yet', 'novaflow' ); ?></span>
	<h2><?php esc_html_e( 'No matching stories were found.', 'novaflow' ); ?></h2>
	<p><?php esc_html_e( 'Try another search or publish new content.', 'novaflow' ); ?></p>
	<?php get_search_form(); ?>
</section>
