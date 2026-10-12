<?php
/**
 * Dynamic theme CSS.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Build safe CSS custom properties from theme options.
 *
 * @return string
 */
function novaflow_get_dynamic_css() {
	$options = novaflow_get_options();
	$colors  = array(
		'accent_color',
		'secondary_color',
		'tertiary_color',
		'warning_color',
		'ink_color',
		'surface_color',
		'page_color',
		'dark_color',
	);

	$valid    = array();
	$defaults = novaflow_get_defaults();
	foreach ( $colors as $key ) {
		$valid[ $key ] = sanitize_hex_color( $options[ $key ] );
		if ( ! $valid[ $key ] ) {
			$valid[ $key ] = $defaults[ $key ];
		}
	}

	return sprintf(
		':root{--nf-container:%1$dpx;--nf-radius:%2$dpx;--nf-font-size:%3$dpx;--nf-heading-weight:%4$d;--nf-accent:%5$s;--nf-secondary:%6$s;--nf-tertiary:%7$s;--nf-warning:%8$s;--nf-ink:%9$s;--nf-surface:%10$s;--nf-page:%11$s;--nf-dark:%12$s;}',
		novaflow_sanitize_container_width( $options['container_width'] ),
		novaflow_sanitize_card_radius( $options['card_radius'] ),
		novaflow_sanitize_base_font_size( $options['base_font_size'] ),
		novaflow_sanitize_heading_weight( $options['heading_weight'] ),
		$valid['accent_color'],
		$valid['secondary_color'],
		$valid['tertiary_color'],
		$valid['warning_color'],
		$valid['ink_color'],
		$valid['surface_color'],
		$valid['page_color'],
		$valid['dark_color']
	);
}
