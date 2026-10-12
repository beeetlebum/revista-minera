<?php
/**
 * The template for displaying search forms in NewSum
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="newsum-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
		<?php esc_html_e( 'Search for:', 'newsum' ); ?>
	</label>
	<div class="search-form-group">
		<span class="search-icon-prefix" aria-hidden="true">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
			</svg>
		</span>
		<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search stories, topics, dispatches…', 'placeholder', 'newsum' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
		<button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x( 'Submit search', 'submit button', 'newsum' ); ?>">
			<span><?php esc_html_e( 'Search', 'newsum' ); ?></span>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<line x1="5" y1="12" x2="19" y2="12"></line>
				<polyline points="12 5 19 12 12 19"></polyline>
			</svg>
		</button>
	</div>
</form>
