<?php
/**
 * Template helper functions.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}


/**
 * Preserve site-owner labels without translating or rewriting WordPress content.
 *
 * @param string $label User-managed label.
 * @return string
 */
function novaflow_localize_theme_label( $label ) {
	return (string) $label;
}

/**
 * Translate the explicitly marked primary Home item without rewriting menus.
 *
 * Only the theme's highlighted custom homepage link uses this interface label.
 * Other menu titles, destinations and saved WordPress content are preserved.
 *
 * @param string   $title Menu item title.
 * @param WP_Post  $item  Menu item object.
 * @param stdClass $args  Menu rendering arguments.
 * @return string
 */
function novaflow_localize_primary_home_label( $title, $item, $args ) {
	if ( 'primary' !== ( $args->theme_location ?? '' )
		|| 'custom' !== ( $item->type ?? '' )
		|| ! in_array( 'nf-menu-home-highlight', (array) ( $item->classes ?? array() ), true )
		|| untrailingslashit( (string) ( $item->url ?? '' ) ) !== untrailingslashit( home_url( '/' ) )
	) {
		return $title;
	}

	return esc_html__( 'Home', 'novaflow' );
}
add_filter( 'nav_menu_item_title', 'novaflow_localize_primary_home_label', 10, 3 );

/**
 * Return a safe category archive URL.
 *
 * @param int    $term_id  Category term ID.
 * @param string $fallback Optional fallback URL.
 * @return string
 */
function novaflow_get_category_url( $term_id, $fallback = '' ) {
	$term_id = absint( $term_id );
	$term    = $term_id ? get_term( $term_id, 'category' ) : null;
	if ( ! $term instanceof WP_Term ) {
		return $fallback ? $fallback : home_url( '/' );
	}
	$url = get_category_link( $term_id );

	if ( is_wp_error( $url ) || ! is_string( $url ) || '' === $url ) {
		return $fallback ? $fallback : home_url( '/' );
	}

	return $url;
}

/**
 * Resolve owner-selected shortcut categories, with real core destinations.
 *
 * No site-specific category names or IDs are embedded in the theme.
 *
 * @return array<string,string> Header shortcut URLs.
 */
function novaflow_get_header_shortcut_urls() {
	$options       = novaflow_get_options();
	$posts_page_id = absint( get_option( 'page_for_posts' ) );
	$posts_url     = ( $posts_page_id && 'page' === get_option( 'show_on_front' ) && 'publish' === get_post_status( $posts_page_id ) ) ? get_permalink( $posts_page_id ) : home_url( '/' ) . '#latest';
	$posts_url     = $posts_url ? $posts_url : home_url( '/' ) . '#latest';

	return array(
		'inspiration' => novaflow_get_category_url( $options['header_inspiration_category'], $posts_url ),
		'editor'      => novaflow_get_category_url( $options['header_editor_category'], $posts_url ),
		'trends'      => novaflow_get_category_url( $options['header_trends_category'], $posts_url ),
	);
}

/**
 * Render a small inline SVG icon from an allowlist.
 *
 * @param string $name Icon name.
 * @param string $css_class Optional class.
 * @return void
 */
function novaflow_icon( $name, $css_class = '' ) {
	$icons = array(
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'search'    => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
		'arrow'     => '<path d="M5 12h14M14 7l5 5-5 5"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'flash'     => '<path d="m13 2-8 12h7l-1 8 8-12h-7l1-8Z"/>',
		'flame'     => '<path d="M12 22c4.4 0 8-3.1 8-7.4 0-3-1.7-5.7-5-8.6.1 2-1 3.7-2.2 4.7.2-3.6-1.7-6.5-5-8.7.3 3.2-1.3 5.5-2.7 7.5C3.8 11.2 3 13 3 15c0 3.9 4 7 9 7Z"/><path d="M9.5 17.5c0 1.4 1.1 2.5 2.5 2.5s2.5-1.1 2.5-2.5c0-1.2-.7-2.3-2.2-3.6.1 1-.4 1.7-1 2.2-.1-1.2-.7-2.1-1.6-2.8.1 1.7-.2 2.6-.2 4.2Z"/>',
		'star'      => '<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>',
		'crown'     => '<path d="m3 6 4 4 5-7 5 7 4-4-2 12H5L3 6Z"/><path d="M5 21h14"/>',
		'trend'     => '<path d="M3 17 9 11l4 4 8-9"/><path d="M15 6h6v6"/>',
		'newspaper' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h4v4H7zM14 8h3M14 12h3M7 16h10"/>',
		'comment'   => '<path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"/>',
		'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
		'tag'       => '<path d="M20 13 13 20 4 11V4h7l9 9Z"/><circle cx="8.5" cy="8.5" r="1.5"/>',
		'heart'     => '<path d="M12 21s-6.7-4.35-9-8.18C.7 9.21 2.37 5 6.31 5c2.02 0 3.25 1.14 3.99 2.28C11.04 6.14 12.27 5 14.29 5c3.94 0 5.61 4.21 3.31 7.82C18.7 14.6 12 21 12 21Z"/>',
		'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15.3 15.3 0 0 1 0 18"/><path d="M12 3a15.3 15.3 0 0 0 0 18"/>',
		'link'      => '<path d="M10 13a5 5 0 0 0 7.5.5l2-2a5 5 0 0 0-7-7l-1.15 1.15"/><path d="M14 11a5 5 0 0 0-7.5-.5l-2 2a5 5 0 0 0 7 7l1.15-1.15"/>',
		'facebook'  => '<path d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v6h4v-6h3l1-4h-4V9c0-.6.4-1 1-1Z"/>',
		'x'         => '<path d="M5 4l14 16M19 4 5 20"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
		'youtube'   => '<path d="M22 12s0-4-1-6c-.5-1-1.5-1.5-2.5-1.7C16 4 12 4 12 4s-4 0-6.5.3C4.5 4.5 3.5 5 3 6c-1 2-1 6-1 6s0 4 1 6c.5 1 1.5 1.5 2.5 1.7C8 20 12 20s4 0 6.5-.3c1-.2 2-.7 2.5-1.7 1-2 1-6 1-6Z"/><path d="m10 9 5 3-5 3Z" fill="currentColor" stroke="none"/>',
		'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return;
	}

	printf(
		'<svg class="nf-icon %1$s" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%2$s</svg>',
		esc_attr( $css_class ),
		$icons[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static allowlisted SVG paths.
	);
}

/**
 * Return a display-safe post title with an accessible fallback.
 *
 * @param int    $post_id Post ID.
 * @param string $fallback Optional fallback title.
 * @return string
 */
function novaflow_get_post_display_title( $post_id = 0, $fallback = '' ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$title   = get_the_title( $post_id );

	if ( '' === trim( wp_strip_all_tags( $title ) ) ) {
		if ( $fallback ) {
			$title = $fallback;
		} elseif ( 'page' === get_post_type( $post_id ) ) {
			$title = __( 'Untitled page', 'novaflow' );
		} else {
			$title = __( 'Untitled post', 'novaflow' );
		}
	}

	return $title;
}

/**
 * Return an accessible label for a post card link.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novaflow_get_post_link_label( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$title   = novaflow_get_post_display_title( $post_id );

	return sprintf(
		/* translators: %s: Post title. */
		__( 'Read “%s”', 'novaflow' ),
		$title
	);
}

/**
 * Return a current, content-backed summary for post cards.
 *
 * WordPress gives a manually entered excerpt permanent priority over the post
 * body. That is useful on archive screens, but it can make editorial homepage
 * cards look stale after the body is updated. Novaflow therefore derives card
 * copy from the current post content and retains the core excerpt as a fallback.
 * Child themes and plugins can override the final value with the filter below.
 *
 * @param int $word_count Maximum number of words.
 * @param int $post_id    Post ID.
 * @return string
 */
function novaflow_get_post_card_excerpt( $word_count = 20, $post_id = 0 ) {
	$post_id    = $post_id ? absint( $post_id ) : get_the_ID();
	$post       = get_post( $post_id );
	$word_count = max( 1, absint( $word_count ) );
	$excerpt    = '';

	if ( $post instanceof WP_Post && '' !== trim( (string) $post->post_content ) ) {
		$content = excerpt_remove_blocks( (string) $post->post_content );
		$content = strip_shortcodes( $content );
		$excerpt = wp_strip_all_tags( $content, true );
	}

	if ( '' === trim( $excerpt ) ) {
		$excerpt = get_the_excerpt( $post_id );
	}

	$normalized_excerpt = preg_replace( '/\s+/u', ' ', trim( $excerpt ) );
	if ( ! is_string( $normalized_excerpt ) ) {
		$normalized_excerpt = trim( $excerpt );
	}
	$excerpt = wp_trim_words( $normalized_excerpt, $word_count, '...' );

	/**
	 * Filter the summary displayed on Novaflow post cards.
	 *
	 * @param string $excerpt    Content-backed card summary.
	 * @param int    $post_id    Post ID.
	 * @param int    $word_count Maximum number of words.
	 */
	$filtered_excerpt = apply_filters( 'novaflow_post_card_excerpt', $excerpt, $post_id, $word_count );

	return is_scalar( $filtered_excerpt ) || null === $filtered_excerpt
		? (string) $filtered_excerpt
		: $excerpt;
}

/**
 * Return a post category badge.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function novaflow_category_badge( $post_id = 0 ) {
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return;
	}

	$category     = $categories[0];
	$palette      = array( '#e11d48', '#7c3aed', '#2563eb', '#0f766e', '#ea580c', '#16a34a', '#db2777', '#ca8a04', '#0891b2', '#9333ea' );
	$color        = $palette[ $category->term_id % count( $palette ) ];
	$category_url = novaflow_get_category_url( $category->term_id );

	printf(
		'<a class="nf-category-badge nf-category-badge--%3$s" href="%1$s" style="--nf-category-color:%4$s;">%2$s</a>',
		esc_url( $category_url ),
		esc_html( novaflow_localize_theme_label( $category->name ) ),
		sanitize_html_class( $category->slug ),
		esc_attr( $color )
	);
}

/**
 * Render post meta.
 *
 * @param bool $show_author Show author.
 * @return void
 */
function novaflow_post_meta( $show_author = true ) {
	echo '<div class="nf-post-meta">';
	if ( $show_author ) {
		novaflow_icon( 'user' );
		printf(
			'<span class="nf-post-author"><a href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
	novaflow_icon( 'clock' );
	printf( '<time datetime="%1$s">%2$s</time>', esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date() ) );
	echo '</div>';
}

/**
 * Return a read-only post view count supplied by a plugin.
 *
 * Novaflow does not collect, persist, or calculate view statistics. A companion
 * plugin may provide a value through the novaflow_post_view_count filter.
 *
 * @param int $post_id Post ID.
 * @return int|null View count, or null when no plugin provides one.
 */
function novaflow_get_post_view_count( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	/**
	 * Filter the optional post view count displayed by Novaflow.
	 *
	 * @param int|null $view_count Plugin-provided view count. Null hides the count.
	 * @param int      $post_id    Post ID.
	 */
	$view_count = apply_filters( 'novaflow_post_view_count', null, $post_id );

	if ( null === $view_count || false === $view_count || '' === $view_count || ! is_numeric( $view_count ) ) {
		return null;
	}

	return absint( $view_count );
}


/**
 * Return the most-read published post IDs using the optional view-count provider.
 *
 * Novaflow itself does not collect analytics. When a plugin supplies view counts
 * through novaflow_post_view_count, posts are ranked by that value. If no view
 * provider is available, the newest posts are used as a safe fallback.
 *
 * Results are cached briefly to avoid recalculating the ranking on every request.
 *
 * @param int $limit Number of post IDs to return.
 * @return array<int,int> Post IDs ordered from most read to least read.
 */
function novaflow_get_most_read_post_ids( $limit = 6 ) {
	$limit = max( 1, absint( $limit ) );

	$has_view_provider = (bool) has_filter( 'novaflow_post_view_count' );
	$cache_key         = 'novaflow_most_read_' . $limit . '_' . ( $has_view_provider ? 'views' : 'recent' );
	$cached            = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		return array_values( array_filter( array_map( 'absint', $cached ) ) );
	}

	$candidate_limit = $limit;
	if ( $has_view_provider ) {
		$candidate_limit = (int) apply_filters( 'novaflow_most_read_candidate_limit', 500, $limit );
		$candidate_limit = max( $limit, min( 2000, $candidate_limit ) );
	}

	$candidate_ids = get_posts(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => $candidate_limit,
			'fields'                 => 'ids',
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( empty( $candidate_ids ) ) {
		return array();
	}

	$ranked        = array();
	$has_view_data = false;

	foreach ( array_values( $candidate_ids ) as $position => $post_id ) {
		$post_id    = absint( $post_id );
		$view_count = $has_view_provider ? novaflow_get_post_view_count( $post_id ) : null;

		if ( null !== $view_count ) {
			$has_view_data = true;
		}

		$ranked[] = array(
			'id'       => $post_id,
			'views'    => null === $view_count ? -1 : absint( $view_count ),
			'position' => absint( $position ),
		);
	}

	if ( $has_view_data ) {
		usort(
			$ranked,
			static function ( $a, $b ) {
				if ( $a['views'] === $b['views'] ) {
					return $a['position'] <=> $b['position'];
				}

				return $b['views'] <=> $a['views'];
			}
		);
	}

	$popular_ids = array_slice( wp_list_pluck( $ranked, 'id' ), 0, $limit );

	/**
	 * Filter the post IDs displayed in the homepage most-read section.
	 *
	 * @param array<int,int>                        $popular_ids Ranked post IDs.
	 * @param array<int,array<string,int>>          $ranked      Ranking data.
	 * @param int                                   $limit       Requested item count.
	 */
	$popular_ids = (array) apply_filters( 'novaflow_home_most_read_post_ids', $popular_ids, $ranked, $limit );
	$popular_ids = array_slice( array_values( array_unique( array_filter( array_map( 'absint', $popular_ids ) ) ) ), 0, $limit );

	$cache_ttl = (int) apply_filters( 'novaflow_most_read_cache_ttl', 5 * MINUTE_IN_SECONDS );
	if ( $cache_ttl > 0 ) {
		set_transient( $cache_key, $popular_ids, $cache_ttl );
	}

	return $popular_ids;
}

/**
 * Choose a stable icon from the Latest Posts icon library for a post.
 *
 * The icon is selected deterministically from WordPress term IDs so it remains
 * stable without assuming any category name, slug, language, or site structure.
 *
 * @param int $post_id Post ID.
 * @return int Icon index.
 */
function novaflow_get_latest_post_icon_index( $post_id ) {
	$categories   = get_the_category( $post_id );
	$category_ids = wp_list_pluck( $categories, 'term_id' );
	$category_ids = array_map( 'absint', $category_ids );
	$stable_key   = $category_ids ? implode( '-', $category_ids ) : (string) absint( $post_id );
	$hash         = hexdec( substr( md5( $stable_key ), 0, 8 ) );

	return ( $hash % 22 ) + 1;
}

/**
 * Add category icons, dates, and optional plugin-provided view counts to Latest Posts blocks.
 *
 * @param string $block_content Rendered block markup.
 * @param array  $block         Parsed block data.
 * @return string
 */
function novaflow_enhance_latest_posts_block( $block_content, $block ) {
	if ( ( isset( $block['blockName'] ) && 'core/latest-posts' !== $block['blockName'] ) || empty( $block_content ) || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	$post_data = array();
	$item_data = array();

	while ( $processor->next_tag( 'a' ) ) {
		$class_name = (string) $processor->get_attribute( 'class' );

		if ( ! preg_match( '/(?:^|\s)wp-block-latest-posts__post-title(?:\s|$)/', $class_name ) ) {
			continue;
		}

		$post_url = (string) $processor->get_attribute( 'href' );
		$post_id  = url_to_postid( $post_url );

		if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
			continue;
		}

		if ( ! isset( $post_data[ $post_id ] ) ) {
			$view_count = novaflow_get_post_view_count( $post_id );
			$categories = get_the_category( $post_id );
			$view_label = '';

			if ( null !== $view_count ) {
				$view_label = sprintf(
					/* translators: %s: Number of post views. */
					esc_html( _n( '%s view', '%s views', $view_count, 'novaflow' ) ),
					esc_html( number_format_i18n( $view_count ) )
				);
			}

			$post_data[ $post_id ] = array(
				'icon'     => novaflow_get_latest_post_icon_index( $post_id ),
				'category' => ! empty( $categories ) ? sanitize_html_class( $categories[0]->slug ) : 'uncategorized',
				'date'     => get_the_date( get_option( 'date_format' ), $post_id ),
				'views'    => $view_label,
			);
		}

		$data       = $post_data[ $post_id ];
		$class_name = trim(
			$class_name . ' nf-latest-icon-' . absint( $data['icon'] ) . ' nf-latest-category-' . $data['category']
		);

		$processor->set_attribute( 'class', $class_name );
		$item_data[] = $data;
	}

	$processor  = new WP_HTML_Tag_Processor( $processor->get_updated_html() );
	$item_index = 0;

	while ( $processor->next_tag( 'li' ) && isset( $item_data[ $item_index ] ) ) {
		$processor->set_attribute( 'data-nf-date', $item_data[ $item_index ]['date'] );
		if ( '' !== $item_data[ $item_index ]['views'] ) {
			$processor->set_attribute( 'data-nf-views', $item_data[ $item_index ]['views'] );
		}
		++$item_index;
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/latest-posts', 'novaflow_enhance_latest_posts_block', 10, 2 );


/**
 * Force the Latest Comments block to show six items on the frontend.
 *
 * @param array<string,mixed> $parsed_block Parsed block data.
 * @return array<string,mixed>
 */
function novaflow_adjust_latest_comments_block( $parsed_block ) {
	if ( is_admin() ) {
		return $parsed_block;
	}

	if ( empty( $parsed_block['blockName'] ) || 'core/latest-comments' !== $parsed_block['blockName'] ) {
		return $parsed_block;
	}

	if ( empty( $parsed_block['attrs'] ) || ! is_array( $parsed_block['attrs'] ) ) {
		$parsed_block['attrs'] = array();
	}

	$parsed_block['attrs']['commentsToShow'] = 6;
	return $parsed_block;
}
add_filter( 'render_block_data', 'novaflow_adjust_latest_comments_block', 10, 2 );

/**
 * Return WordPress taxonomy terms for card keyword displays.
 *
 * @param int $post_id Post ID.
 * @return array<int, WP_Term>
 */
function novaflow_get_post_keywords( $post_id = 0 ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$keywords = wp_get_post_tags( $post_id );
	if ( is_wp_error( $keywords ) ) {
		$keywords = array();
	}

	/**
	 * Filter the WordPress tags displayed as card keywords.
	 *
	 * @param array<int, WP_Term> $keywords WordPress post tags.
	 * @param int                 $post_id  Post ID.
	 */
	$filtered_keywords = apply_filters( 'novaflow_post_keywords', $keywords, $post_id );
	if ( ! is_array( $filtered_keywords ) ) {
		return $keywords;
	}

	return array_values(
		array_filter(
			$filtered_keywords,
			static function ( $keyword ) {
				return $keyword instanceof WP_Term;
			}
		)
	);
}

/**
 * Render the shared date, optional keywords, and optional plugin-provided view count used by post cards.
 *
 * @param array<int, WP_Term> $keywords  Optional WordPress post tags.
 * @param bool                $show_date Whether to render the date in this row.
 * @return void
 */
function novaflow_post_stats( $keywords = array(), $show_date = true ) {
	$view_count = novaflow_get_post_view_count();
	?>
	<div class="nf-story-meta-row">
		<?php if ( $show_date ) : ?>
			<?php novaflow_post_meta( false ); ?>
		<?php endif; ?>
		<?php if ( ! empty( $keywords ) ) : ?>
			<div class="nf-home-list-keywords" aria-label="<?php esc_attr_e( 'Keywords', 'novaflow' ); ?>">
				<?php foreach ( array_slice( $keywords, 0, 3 ) as $keyword ) : ?>
					<?php if ( ! $keyword instanceof WP_Term ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<?php $keyword_link = get_term_link( $keyword ); ?>
					<?php if ( ! is_wp_error( $keyword_link ) ) : ?>
						<a href="<?php echo esc_url( $keyword_link ); ?>"><?php echo esc_html( '#' . $keyword->name ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( null !== $view_count ) : ?>
			<span class="nf-story-views">
				<?php
				printf(
					/* translators: %s: Number of post views. */
					esc_html( _n( '%s view', '%s views', $view_count, 'novaflow' ) ),
					esc_html( number_format_i18n( $view_count ) )
				);
				?>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Get a thumbnail or decorative fallback.
 *
 * @param string $size Image size.
 * @param string $css_class     CSS class.
 * @param bool   $high_priority Whether this image is the page's primary visual.
 * @return void
 */
function novaflow_post_media( $size = 'novaflow-card', $css_class = '', $high_priority = false ) {
	echo '<div class="nf-post-media ' . esc_attr( $css_class ) . '">';
	if ( has_post_thumbnail() ) {
		$image_attributes = array(
			'loading'  => is_singular( 'post' ) ? 'eager' : 'lazy',
			'decoding' => is_singular( 'post' ) ? 'sync' : 'async',
		);

		if ( 'novaflow-hero' === $size ) {
			$image_attributes['loading'] = 'eager';

			if ( $high_priority ) {
				$image_attributes['fetchpriority'] = 'high';
			}
		}

		the_post_thumbnail( $size, $image_attributes );
	} else {
		echo '<div class="nf-image-fallback" aria-hidden="true"><span></span><span></span><span></span></div>';
	}
	echo '</div>';
}

/**
 * Estimate reading time.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function novaflow_reading_time( $post_id = 0 ) {
	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() ) );
	$words   = preg_match_all( "/[\p{L}\p{N}]+(?:[’'-][\p{L}\p{N}]+)*/u", $content, $matches );

	if ( false === $words ) {
		$words = str_word_count( $content );
	}

	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * Return short, standard utility links backed by real WordPress destinations.
 *
 * The theme does not create pages and does not list every published page.
 * Missing destinations are omitted instead of producing dead links.
 *
 * @return array<int,array{label:string,url:string}>
 */
function novaflow_get_standard_utility_links() {
	$links = array(
		array(
			'label' => __( 'Home', 'novaflow' ),
			'url'   => home_url( '/' ),
		),
	);

	$posts_page_id = absint( get_option( 'page_for_posts' ) );
	if ( $posts_page_id && 'publish' === get_post_status( $posts_page_id ) ) {
		$links[] = array(
			'label' => __( 'Blog', 'novaflow' ),
			'url'   => get_permalink( $posts_page_id ),
		);
	}

	$privacy_page_id = absint( get_option( 'wp_page_for_privacy_policy' ) );
	if ( $privacy_page_id && 'publish' === get_post_status( $privacy_page_id ) ) {
		$links[] = array(
			'label' => __( 'Privacy Policy', 'novaflow' ),
			'url'   => get_permalink( $privacy_page_id ),
		);
	}

	return $links;
}

/**
 * Render the standard utility-link fallback.
 *
 * @param string $menu_class CSS class for the generated list.
 * @return void
 */
function novaflow_utility_menu_fallback( $menu_class = 'nf-utility-menu-list' ) {
	if ( is_object( $menu_class ) && isset( $menu_class->menu_class ) ) {
		$menu_class = $menu_class->menu_class;
	} elseif ( is_array( $menu_class ) && isset( $menu_class['menu_class'] ) ) {
		$menu_class = $menu_class['menu_class'];
	}

	$menu_class = is_string( $menu_class ) && $menu_class ? $menu_class : 'nf-utility-menu-list';
	$links      = novaflow_get_standard_utility_links();

	if ( empty( $links ) ) {
		return;
	}

	printf( '<ul class="%s">', esc_attr( $menu_class ) );
	foreach ( $links as $link ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $link['url'] ),
			esc_html( $link['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Render the requested visible footer credit through the theme action.
 *
 * The owner's link text remains literal; the surrounding interface is translatable.
 *
 * @return void
 */
function novaflow_footer_credit_link() {
	echo '<span class="nf-footer-credit-text">© ' . (int) wp_date( 'Y' ) . ' <a class="nf-footer-credit-link" href="https://narya.tr/" target="_blank" rel="noopener noreferrer">köpek maması &amp; kedi maması</a>. ' . esc_html__( 'All rights reserved.', 'novaflow' ) . '</span>';
}
add_action( 'novaflow_footer_credit', 'novaflow_footer_credit_link' );

/**
 * Fallback primary menu.
 */
function novaflow_menu_fallback() {
	$pages = get_pages(
		array(
			'parent'      => 0,
			'sort_column' => 'menu_order,post_title',
			'post_status' => 'publish',
		)
	);

	echo '<ul class="nf-nav-list">';
	foreach ( $pages as $page ) {
		printf(
			'<li class="page_item page-item-%1$d"><a href="%2$s">%3$s</a></li>',
			absint( $page->ID ),
			esc_url( get_permalink( $page ) ),
			esc_html( novaflow_localize_theme_label( get_the_title( $page ) ) )
		);
	}
	echo '</ul>';
}


/**
 * Render a registered widget area.
 *
 * @param string $sidebar_id Registered sidebar ID.
 * @return bool Whether the sidebar produced output.
 */
function novaflow_dynamic_sidebar( $sidebar_id ) {
	if ( ! is_active_sidebar( $sidebar_id ) ) {
		return false;
	}

	if ( 'primary-sidebar' !== $sidebar_id ) {
		return dynamic_sidebar( $sidebar_id );
	}

	add_action( 'dynamic_sidebar', 'novaflow_render_sidebar_companion_widgets' );
	$result = dynamic_sidebar( $sidebar_id );
	remove_action( 'dynamic_sidebar', 'novaflow_render_sidebar_companion_widgets' );

	return $result;
}

/**
 * Place theme discovery widgets alongside their related core block widgets.
 *
 * @param array $widget Registered widget data for the widget just rendered.
 * @return void
 */
function novaflow_render_sidebar_companion_widgets( $widget ) {
	static $render_popular_next    = false;
	static $editor_choice_rendered = false;

	$widget_id = $widget['id'] ?? '';
	if ( ! preg_match( '/^block-(\d+)$/', $widget_id, $matches ) ) {
		return;
	}

	$block_widgets = get_option( 'widget_block', array() );
	$content       = $block_widgets[ absint( $matches[1] ) ]['content'] ?? '';

	if ( $render_popular_next ) {
		novaflow_popular_posts_widget();
		$render_popular_next = false;
	}

	if ( has_block( 'core/categories', $content ) ) {
		$render_popular_next = true;
	} elseif ( has_block( 'core/archives', $content ) ) {
		novaflow_popular_tags_widget();
	}

	if ( ! $editor_choice_rendered && has_block( 'core/latest-comments', $content ) ) {
		novaflow_sidebar_editor_choice_widget();
		$editor_choice_rendered = true;
	}
}

/**
 * Render a compact four-card Editor's Selection widget below Latest Comments.
 *
 * Sticky posts are treated as editorial picks. When fewer than four sticky
 * posts are available, the remaining slots are filled with recent posts.
 * Plugins may override the initial query through
 * novaflow_sidebar_editor_choice_query_args.
 *
 * @return void
 */
function novaflow_sidebar_editor_choice_widget() {
	$sticky_ids = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );

	$query_args = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 4,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => true,
	);

	if ( $sticky_ids ) {
		$query_args['post__in'] = $sticky_ids;
	}

	/**
	 * Filter the sidebar Editor's Selection query.
	 *
	 * @param array<string,mixed> $query_args WP_Query arguments.
	 */
	$query_args = (array) apply_filters( 'novaflow_sidebar_editor_choice_query_args', $query_args );
	$posts      = get_posts( $query_args );

	if ( count( $posts ) < 4 ) {
		$exclude_ids = array_values( array_filter( array_map( 'absint', wp_list_pluck( $posts, 'ID' ) ) ) );
		$fallback    = get_posts(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'posts_per_page'         => 4 - count( $posts ),
				'post__not_in'           => $exclude_ids,
				'orderby'                => 'date',
				'order'                  => 'DESC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => true,
			)
		);

		$posts = array_merge( $posts, $fallback );
	}

	$posts = array_slice( $posts, 0, 4 );
	if ( ! $posts ) {
		return;
	}

	$posts_page_id = absint( get_option( 'page_for_posts' ) );
	$all_posts_url = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
	if ( ! $all_posts_url ) {
		$all_posts_url = home_url( '/' );
	}
	?>
	<section class="widget nf-widget nf-sidebar-editor-choice">
		<header class="nf-sidebar-editor-choice-head">
			<h2 class="nf-sidebar-editor-choice-title"><span class="nf-sidebar-editor-choice-spark" aria-hidden="true">✦</span><?php esc_html_e( "Editor's selection", 'novaflow' ); ?></h2>
			<a class="nf-sidebar-editor-choice-more" href="<?php echo esc_url( $all_posts_url ); ?>"><?php esc_html_e( 'View all', 'novaflow' ); ?><span aria-hidden="true">→</span></a>
		</header>
		<div class="nf-sidebar-editor-choice-grid">
			<?php foreach ( $posts as $editor_post ) : ?>
				<?php
				$post_id    = absint( $editor_post->ID );
				$categories = get_the_category( $post_id );
				$category   = $categories ? $categories[0] : null;
				$view_count = novaflow_get_post_view_count( $post_id );
				?>
				<article class="nf-sidebar-editor-choice-card">
					<a class="nf-sidebar-editor-choice-media" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php echo esc_attr( novaflow_get_post_link_label( $post_id ) ); ?>">
						<?php if ( has_post_thumbnail( $post_id ) ) : ?>
							<?php
							echo get_the_post_thumbnail(
								$post_id,
								'novaflow-card',
								array(
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?>
						<?php else : ?>
							<span class="nf-sidebar-editor-choice-fallback" aria-hidden="true"></span>
						<?php endif; ?>
						<?php if ( $category instanceof WP_Term ) : ?>
							<span class="nf-sidebar-editor-choice-category"><?php echo esc_html( novaflow_localize_theme_label( $category->name ) ); ?></span>
						<?php endif; ?>
					</a>
					<div class="nf-sidebar-editor-choice-body">
						<h3><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( novaflow_get_post_display_title( $post_id ) ); ?></a></h3>
						<div class="nf-sidebar-editor-choice-meta">
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>"><?php novaflow_icon( 'clock' ); ?><?php echo esc_html( get_the_date( get_option( 'date_format' ), $post_id ) ); ?></time>
							<?php if ( null !== $view_count ) : ?>
								<span class="nf-sidebar-editor-choice-views"><?php echo esc_html( number_format_i18n( $view_count ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the most-used WordPress post tags as a compact discovery widget.
 *
 * @return void
 */
function novaflow_popular_tags_widget() {
	$tags = get_terms(
		array(
			'taxonomy'   => 'post_tag',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 12,
		)
	);

	if ( is_wp_error( $tags ) || empty( $tags ) ) {
		return;
	}
	?>
	<section class="widget nf-widget nf-popular-tags-widget">
		<h2 class="widget-title"><?php esc_html_e( 'Popular Tags', 'novaflow' ); ?></h2>
		<div class="nf-popular-tags-list">
			<?php foreach ( $tags as $tag ) : ?>
				<?php $tag_link = get_term_link( $tag ); ?>
				<?php if ( ! is_wp_error( $tag_link ) ) : ?>
					<a href="<?php echo esc_url( $tag_link ); ?>">
						<span aria-hidden="true">#</span><?php echo esc_html( $tag->name ); ?>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/**
 * Render a compact recent-posts list.
 *
 * Plugins may alter the query through novaflow_popular_posts_query_args if they
 * provide their own popularity data. The theme itself performs no statistics.
 *
 * @return void
 */
function novaflow_popular_posts_widget() {
	$query_args = array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 5,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	/**
	 * Filter the sidebar post-list query.
	 *
	 * A plugin may supply its own ordering when it owns the underlying data.
	 *
	 * @param array<string,mixed> $query_args WP_Query arguments.
	 */
	$query_args    = (array) apply_filters( 'novaflow_popular_posts_query_args', $query_args );
	$popular_posts = get_posts( $query_args );

	if ( empty( $popular_posts ) ) {
		return;
	}
	?>
	<section class="widget nf-widget nf-popular-posts-widget">
		<h2 class="widget-title"><?php esc_html_e( 'Latest Posts', 'novaflow' ); ?></h2>
		<ol class="nf-popular-posts-list">
			<?php foreach ( $popular_posts as $index => $popular_post ) : ?>
				<li>
					<span class="nf-popular-post-rank" aria-hidden="true"><?php echo esc_html( zeroise( $index + 1, 2 ) ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $popular_post ) ); ?>">
						<?php echo esc_html( novaflow_get_post_display_title( $popular_post->ID ) ); ?>
					</a>
					<span class="nf-popular-post-views">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $popular_post ) ); ?>"><?php echo esc_html( get_the_date( get_option( 'date_format' ), $popular_post ) ); ?></time>
					</span>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php
}

/**
 * Output a standard section title.
 *
 * @param string $title Title.
 * @param string $eyebrow Eyebrow.
 * @param string $icon Icon name.
 * @param string $url Optional section link.
 */
function novaflow_section_heading( $title, $eyebrow = '', $icon = 'flash', $url = '' ) {
	echo '<header class="nf-section-heading">';
	if ( $icon ) {
		echo '<span class="nf-section-heading-icon" aria-hidden="true">';
		novaflow_icon( $icon );
		echo '</span>';
	}
	echo '<div class="nf-section-heading-copy">';
	if ( $eyebrow ) {
		echo '<span class="nf-eyebrow">' . esc_html( $eyebrow ) . '</span>';
	}
	echo '<h2>' . esc_html( $title ) . '</h2>';
	echo '</div>';
	if ( $url ) {
		$section_more_text = esc_html__( 'View all', 'novaflow' );
		$section_more_text = str_replace( array( '→', '➜', '➔', '➡', '&rarr;' ), '', $section_more_text );
		echo '<a class="nf-section-more" href="' . esc_url( $url ) . '">' . esc_html( trim( $section_more_text ) ) . '</a>';
	}
	echo '</header>';
}


/**
 * Render the Latest Comments block with exactly six approved comments.
 *
 * Keeps the standard WordPress block class names so the theme's existing
 * sidebar design continues to apply.
 *
 * @param string $block_content Rendered block HTML.
 * @return string
 */
function novaflow_render_six_latest_comments( $block_content ) {
	if ( is_admin() ) {
		return $block_content;
	}

	$comments = get_comments(
		array(
			'number'  => 6,
			'status'  => 'approve',
			'orderby' => 'comment_date_gmt',
			'order'   => 'DESC',
			'type'    => 'comment',
		)
	);

	if ( empty( $comments ) ) {
		return $block_content;
	}

	$comments = array_slice( array_values( $comments ), 0, 6 );

	$classes = array( 'wp-block-latest-comments', 'has-dates', 'has-excerpts', 'nf-latest-comments-stacked' );

	$html = '<ol class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	foreach ( $comments as $comment ) {
		$author      = get_comment_author( $comment );
		$comment_url = get_comment_link( $comment );
		$excerpt     = wp_trim_words( wp_strip_all_tags( $comment->comment_content ), 18, '…' );

		$html .= '<li class="wp-block-latest-comments__comment">';
		$html .= '<a class="wp-block-latest-comments__comment-wrap" href="' . esc_url( $comment_url ) . '">';
		$html .= '<article>';
		$html .= '<footer class="wp-block-latest-comments__comment-meta">';
		$html .= '<span class="wp-block-latest-comments__comment-author">' . esc_html( $author ) . '</span>';
		$html .= '<time class="wp-block-latest-comments__comment-date" datetime="' . esc_attr( get_comment_date( DATE_W3C, $comment ) ) . '">' . esc_html( get_comment_date( '', $comment ) ) . '</time>';
		$html .= '</footer>';
		$html .= '<div class="wp-block-latest-comments__comment-excerpt"><p>' . esc_html( $excerpt ) . '</p></div>';

		$html .= '</article>';
		$html .= '</a>';
		$html .= '</li>';
	}

	$html .= '</ol>';
	return $html;
}
add_filter( 'render_block_core/latest-comments', 'novaflow_render_six_latest_comments', 20, 2 );
