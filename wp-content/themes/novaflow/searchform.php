<?php
/**
 * Search form template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$novaflow_search_id   = wp_unique_id( 'nf-search-field-' );
$novaflow_args        = isset( $args ) && is_array( $args ) ? $args : array();
$novaflow_variant     = isset( $novaflow_args['novaflow_variant'] ) ? sanitize_html_class( $novaflow_args['novaflow_variant'] ) : '';
$novaflow_header      = in_array( $novaflow_variant, array( 'header', 'header-catalog' ), true );
$novaflow_catalog     = 'header-catalog' === $novaflow_variant;
$novaflow_class       = 'nf-search-form' . ( $novaflow_header ? ' nf-search-form-header' : '' ) . ( $novaflow_catalog ? ' nf-search-form-catalog' : '' );
$novaflow_label       = isset( $novaflow_args['aria_label'] ) ? sanitize_text_field( $novaflow_args['aria_label'] ) : __( 'Search for:', 'novaflow' );
$novaflow_placeholder = isset( $novaflow_args['placeholder'] )
	? sanitize_text_field( $novaflow_args['placeholder'] )
	: __( 'Search stories…', 'novaflow' );
?>
<form role="search" method="get" class="<?php echo esc_attr( $novaflow_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $novaflow_label ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $novaflow_search_id ); ?>"><?php esc_html_e( 'Search for:', 'novaflow' ); ?></label>
	<?php novaflow_icon( 'search' ); ?>
	<input id="<?php echo esc_attr( $novaflow_search_id ); ?>" type="search" placeholder="<?php echo esc_attr( $novaflow_placeholder ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<?php if ( $novaflow_header ) : ?>
		<?php if ( $novaflow_catalog ) : ?>
			<button type="submit" aria-label="<?php esc_attr_e( 'Search site', 'novaflow' ); ?>">
				<span class="nf-header-search-submit-label"><?php esc_html_e( 'Search', 'novaflow' ); ?></span>
			</button>
		<?php else : ?>
			<span class="nf-header-search-shortcut" aria-hidden="true">⌘K</span>
			<button type="submit" aria-label="<?php esc_attr_e( 'Search site', 'novaflow' ); ?>">
				<?php novaflow_icon( 'search' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'novaflow' ); ?></span>
			</button>
		<?php endif; ?>
	<?php else : ?>
		<button type="submit"><?php esc_html_e( 'Search', 'novaflow' ); ?></button>
	<?php endif; ?>
</form>
