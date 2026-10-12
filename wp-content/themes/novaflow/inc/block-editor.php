<?php
/**
 * Block editor styles and patterns.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Register optional block styles and a starter editorial pattern.
 *
 * @return void
 */
function novaflow_register_block_editor_features() {
	if ( function_exists( 'register_block_style' ) ) {
		register_block_style(
			'core/image',
			array(
				'name'         => 'novaflow-framed',
				'label'        => __( 'Novaflow Frame', 'novaflow' ),
				'inline_style' => '.wp-block-image.is-style-novaflow-framed img{border-radius:12px;box-shadow:0 14px 34px rgba(15,23,42,.12)}',
			)
		);

		register_block_style(
			'core/quote',
			array(
				'name'         => 'novaflow-accent',
				'label'        => __( 'Novaflow Accent', 'novaflow' ),
				'inline_style' => '.wp-block-quote.is-style-novaflow-accent{border-inline-start:4px solid var(--wp--preset--color--accent,#d81b60);padding:1.25rem 1.5rem;background:rgba(216,27,96,.06);border-radius:0 12px 12px 0}',
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'         => 'novaflow-soft-card',
				'label'        => __( 'Novaflow Soft Card', 'novaflow' ),
				'inline_style' => '.wp-block-group.is-style-novaflow-soft-card{padding:clamp(1.25rem,3vw,2rem);background:#fff;border:1px solid rgba(15,23,42,.1);border-radius:12px;box-shadow:0 12px 30px rgba(15,23,42,.06)}',
			)
		);
	}

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'novaflow',
			array(
				'label' => __( 'Novaflow', 'novaflow' ),
			)
		);
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		$pattern_content = sprintf(
			'<!-- wp:group {"className":"is-style-novaflow-soft-card","layout":{"type":"constrained"}} --><div class="wp-block-group is-style-novaflow-soft-card"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">%1$s</h3><!-- /wp:heading --><!-- wp:paragraph --><p>%2$s</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			esc_html__( 'Featured story', 'novaflow' ),
			esc_html__( 'Use this editorial callout to highlight an important article or announcement.', 'novaflow' )
		);

		register_block_pattern(
			'novaflow/editorial-callout',
			array(
				'title'       => __( 'Editorial callout', 'novaflow' ),
				'description' => __( 'A compact editorial callout with a heading and summary.', 'novaflow' ),
				'categories'  => array( 'novaflow', 'featured' ),
				'keywords'    => array( __( 'news', 'novaflow' ), __( 'featured', 'novaflow' ), __( 'callout', 'novaflow' ) ),
				'content'     => $pattern_content,
			)
		);
	}
}
add_action( 'init', 'novaflow_register_block_editor_features' );

/**
 * Return localized interface headings for explicitly bound Heading blocks.
 *
 * These are interface labels, not translations of user-authored content.
 *
 * @return array<string,string> Supported heading keys and labels.
 */
function novaflow_get_localized_headings() {
	return array(
		'latest-posts'    => __( 'Latest Posts', 'novaflow' ),
		'latest-comments' => __( 'Recent Comments', 'novaflow' ),
		'archives'        => __( 'Archives', 'novaflow' ),
		'categories'      => __( 'Categories', 'novaflow' ),
	);
}

/**
 * Resolve a heading through the native WordPress Block Bindings API.
 *
 * @param array    $source_args    Binding arguments.
 * @param WP_Block $block_instance Block being rendered.
 * @param string   $attribute_name Bound attribute.
 * @return string|null Escaped text, or null to preserve the saved fallback.
 */
function novaflow_get_bound_heading( $source_args, $block_instance, $attribute_name ) {
	if ( 'core/heading' !== $block_instance->name || 'content' !== $attribute_name ) {
		return null;
	}

	$key      = isset( $source_args['key'] ) && is_string( $source_args['key'] ) ? $source_args['key'] : '';
	$headings = novaflow_get_localized_headings();

	return isset( $headings[ $key ] ) ? esc_html( $headings[ $key ] ) : null;
}

/**
 * Register the optional, locale-aware heading source.
 *
 * Ordinary headings and all site content remain untouched. A Heading block
 * must explicitly bind its content to this source to use a localized label.
 *
 * @return void
 */
function novaflow_register_heading_bindings() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}

	register_block_bindings_source(
		'novaflow/section-heading',
		array(
			'label'              => __( 'Localized section headings', 'novaflow' ),
			'get_value_callback' => 'novaflow_get_bound_heading',
		)
	);
}
add_action( 'init', 'novaflow_register_heading_bindings' );

/**
 * Show the same translated labels in supported WordPress block editors.
 *
 * @return void
 */
function novaflow_enqueue_heading_bindings_editor() {
	wp_enqueue_script(
		'novaflow-heading-bindings',
		get_theme_file_uri( 'assets/js/heading-bindings.js' ),
		array( 'wp-blocks' ),
		novaflow_asset_version( 'assets/js/heading-bindings.js' ),
		true
	);
	wp_add_inline_script(
		'novaflow-heading-bindings',
		'window.novaflowHeadingLabels = ' . wp_json_encode( novaflow_get_localized_headings(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'novaflow_enqueue_heading_bindings_editor' );
