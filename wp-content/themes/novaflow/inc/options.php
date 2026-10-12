<?php
/**
 * Theme option defaults and sanitization helpers.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Return theme defaults.
 *
 * @return array<string, mixed>
 */
function novaflow_get_defaults() {
	return array(
		'container_width'             => 1600,
		'header_inspiration_category' => 0,
		'header_editor_category'      => 0,
		'header_trends_category'      => 0,
		'card_radius'                 => 10,
		'base_font_size'              => 16,
		'heading_weight'              => 400,
		'show_topbar'                 => true,
		'show_ticker'                 => true,
		'show_trending'               => true,
		'show_editor'                 => true,
		'show_header_ad'              => false,
		'header_ad_image_id'          => 0,
		'header_ad_url'               => '',
		'header_ad_alt'               => '',
		'header_ad_new_tab'           => true,
		'topbar_facebook_url'         => 'https://www.facebook.com/',
		'topbar_x_url'                => 'https://x.com/',
		'topbar_instagram_url'        => 'https://www.instagram.com/',
		'topbar_youtube_url'          => 'https://www.youtube.com/',
		'topbar_tiktok_url'           => '',
		'topbar_linkedin_url'         => '',
		'topbar_pinterest_url'        => 'https://www.pinterest.com/',
		'accent_color'                => '#d81b60',
		'secondary_color'             => '#6c5ce7',
		'tertiary_color'              => '#00c2a8',
		'warning_color'               => '#ffb703',
		'ink_color'                   => '#172033',
		'surface_color'               => '#ffffff',
		'page_color'                  => '#f4f6fb',
		'dark_color'                  => '#111827',
	);
}

/**
 * Get merged theme options from one prefixed option array.
 *
 * @return array<string, mixed>
 */
function novaflow_get_options() {
	static $clean_options = null;

	if ( is_array( $clean_options ) ) {
		return $clean_options;
	}

	$defaults = novaflow_get_defaults();
	$saved    = get_option( 'novaflow_options', array() );
	$options  = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );
	$colors   = array(
		'accent_color',
		'secondary_color',
		'tertiary_color',
		'warning_color',
		'ink_color',
		'surface_color',
		'page_color',
		'dark_color',
	);

	$header_ad_image_id = novaflow_sanitize_image_id( $options['header_ad_image_id'] );

	$clean = array(
		'container_width'             => novaflow_sanitize_container_width( $options['container_width'] ),
		'header_inspiration_category' => absint( $options['header_inspiration_category'] ),
		'header_editor_category'      => absint( $options['header_editor_category'] ),
		'header_trends_category'      => absint( $options['header_trends_category'] ),
		'card_radius'                 => novaflow_sanitize_card_radius( $options['card_radius'] ),
		'base_font_size'              => novaflow_sanitize_base_font_size( $options['base_font_size'] ),
		'heading_weight'              => novaflow_sanitize_heading_weight( $options['heading_weight'] ),
		'show_topbar'                 => novaflow_sanitize_checkbox( $options['show_topbar'] ),
		'show_ticker'                 => novaflow_sanitize_checkbox( $options['show_ticker'] ),
		'show_trending'               => novaflow_sanitize_checkbox( $options['show_trending'] ),
		'show_editor'                 => novaflow_sanitize_checkbox( $options['show_editor'] ),
		'show_header_ad'              => novaflow_sanitize_checkbox( $options['show_header_ad'] ),
		'header_ad_image_id'          => $header_ad_image_id,
		'header_ad_url'               => novaflow_sanitize_external_url( $options['header_ad_url'] ),
		'header_ad_alt'               => sanitize_text_field( $options['header_ad_alt'] ),
		'header_ad_new_tab'           => novaflow_sanitize_checkbox( $options['header_ad_new_tab'] ),
		'topbar_facebook_url'         => novaflow_sanitize_external_url( $options['topbar_facebook_url'] ),
		'topbar_x_url'                => novaflow_sanitize_external_url( $options['topbar_x_url'] ),
		'topbar_instagram_url'        => novaflow_sanitize_external_url( $options['topbar_instagram_url'] ),
		'topbar_youtube_url'          => novaflow_sanitize_external_url( $options['topbar_youtube_url'] ),
		'topbar_tiktok_url'           => novaflow_sanitize_external_url( $options['topbar_tiktok_url'] ),
		'topbar_linkedin_url'         => novaflow_sanitize_external_url( $options['topbar_linkedin_url'] ),
		'topbar_pinterest_url'        => novaflow_sanitize_external_url( $options['topbar_pinterest_url'] ),
	);

	foreach ( $colors as $key ) {
		$clean[ $key ] = sanitize_hex_color( $options[ $key ] );
		if ( ! $clean[ $key ] ) {
			$clean[ $key ] = $defaults[ $key ];
		}
	}

	$clean_options = $clean;

	return $clean_options;
}

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function novaflow_sanitize_checkbox( $value ) {
	return 1 === absint( $value );
}


/**
 * Sanitize a Media Library image attachment ID.
 *
 * @param mixed $value Raw attachment ID.
 * @return int
 */
function novaflow_sanitize_image_id( $value ) {
	$image_id           = absint( $value );
	$allowed_mime_types = array(
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/webp',
	);

	if ( ! $image_id || 'attachment' !== get_post_type( $image_id ) ) {
		return 0;
	}

	if ( ! in_array( get_post_status( $image_id ), array( 'inherit', 'private', 'publish' ), true ) ) {
		return 0;
	}

	if ( ! wp_attachment_is_image( $image_id ) || ! in_array( get_post_mime_type( $image_id ), $allowed_mime_types, true ) ) {
		return 0;
	}

	return $image_id;
}

/**
 * Sanitize an external URL and allow only HTTP and HTTPS destinations.
 *
 * @param mixed $value Raw URL value.
 * @return string
 */
function novaflow_sanitize_external_url( $value ) {
	if ( ! is_string( $value ) ) {
		return '';
	}

	$url = esc_url_raw( trim( $value ), array( 'http', 'https' ) );
	if ( ! $url ) {
		return '';
	}

	$parts = wp_parse_url( $url );
	if (
		! is_array( $parts )
		|| empty( $parts['scheme'] )
		|| empty( $parts['host'] )
		|| ! in_array( strtolower( $parts['scheme'] ), array( 'http', 'https' ), true )
		|| isset( $parts['user'] )
		|| isset( $parts['pass'] )
	) {
		return '';
	}

	return $url;
}

/**
 * Sanitize the content width setting.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function novaflow_sanitize_container_width( $value ) {
	return min( 1600, max( 1080, absint( $value ) ) );
}

/**
 * Sanitize the card radius setting.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function novaflow_sanitize_card_radius( $value ) {
	return min( 20, absint( $value ) );
}

/**
 * Sanitize the base font-size setting.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function novaflow_sanitize_base_font_size( $value ) {
	return min( 20, max( 14, absint( $value ) ) );
}

/**
 * Sanitize the heading weight setting.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function novaflow_sanitize_heading_weight( $value ) {
	$value = absint( $value );

	return in_array( $value, array( 300, 400, 500 ), true ) ? $value : 400;
}
