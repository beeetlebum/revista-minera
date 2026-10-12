<?php

/**
 * Register custom fonts.
 */
function uncut_news_fonts_url() {
	$fonts_url     = '';
	$font_families = array();
	$subsets       = 'latin,latin-ext';

	if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'uncut-news' ) ) {
		$font_families[] = 'Roboto:400,600,700';
	}

	if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'uncut-news' ) ) {
		$font_families[] = 'Poppins:400,600,700';
	}

	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'uncut-news' ) ) {
		$font_families[] = 'Noto Sans:400,600,700';
	}

	if ( ! empty( get_theme_mod( 'uncut_news_site_title_font' ) ) ) {
		$font_families[] = str_replace( '+', ' ', get_theme_mod( 'uncut_news_site_title_font' ) );
	}

	if ( ! empty( get_theme_mod( 'uncut_news_site_description_font' ) ) ) {
		$font_families[] = str_replace( '+', ' ', get_theme_mod( 'uncut_news_site_description_font' ) );
	}

	if ( ! empty( get_theme_mod( 'uncut_news_header_font' ) ) ) {
		$font_families[] = str_replace( '+', ' ', get_theme_mod( 'uncut_news_header_font' ) );
	}

	if ( ! empty( get_theme_mod( 'uncut_news_body_font' ) ) ) {
		$font_families[] = str_replace( '+', ' ', get_theme_mod( 'uncut_news_body_font' ) );
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( $subsets ),
	);

	if ( $font_families ) {

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	}

	return esc_url_raw( $fonts_url );
}