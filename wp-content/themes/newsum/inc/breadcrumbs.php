<?php
/**
 * NewSum Native Breadcrumbs & Schema.org Structured Data
 *
 * Provides a modern, accessible breadcrumb navigation with Schema.org
 * BreadcrumbList microdata. Automatically integrates with Rank Math, Yoast SEO,
 * and Breadcrumb NavXT if active.
 *
 * @package NewSum
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'newsum_breadcrumbs' ) ) {
	/**
	 * Render breadcrumb navigation.
	 *
	 * @param array $args Optional custom arguments.
	 */
	function newsum_breadcrumbs( $args = array() ) {
		// Check context-specific theme mods
		if ( is_single() ) {
			if ( ! get_theme_mod( 'newsum_single_show_breadcrumbs', true ) ) {
				return;
			}
		} elseif ( is_archive() || is_home() || is_search() ) {
			if ( ! get_theme_mod( 'newsum_archive_show_breadcrumbs', true ) ) {
				return;
			}
		} elseif ( is_page() ) {
			if ( ! get_theme_mod( 'newsum_page_show_breadcrumbs', true ) ) {
				return;
			}
		}

		// 1. Delegate to Rank Math if active
		if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
			return;
		}

		// 2. Delegate to Yoast SEO if active
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<nav class="newsum-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'newsum' ) . '">', '</nav>' );
			return;
		}

		// 3. Delegate to Breadcrumb NavXT if active
		if ( function_exists( 'bcn_display' ) ) {
			echo '<nav class="newsum-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'newsum' ) . '">';
			bcn_display();
			echo '</nav>';
			return;
		}

		// Do not display on front page if front page is a static page
		if ( is_front_page() ) {
			return;
		}

		$sep = '<span class="breadcrumb-sep" aria-hidden="true">/</span>';
		$position = 1;

		$items = array();

		// Home item
		$items[] = array(
			'url'  => home_url( '/' ),
			'text' => __( 'Home', 'newsum' ),
		);

		if ( is_home() ) {
			// Blog index
			$blog_page_id = get_option( 'page_for_posts' );
			$blog_title   = $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Dispatches', 'newsum' );
			$items[] = array(
				'url'  => '',
				'text' => $blog_title,
			);
		} elseif ( is_category() ) {
			$cat = get_queried_object();
			if ( $cat && $cat->parent ) {
				$parents = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
				foreach ( $parents as $parent_id ) {
					$parent = get_category( $parent_id );
					if ( $parent ) {
						$items[] = array(
							'url'  => get_category_link( $parent->term_id ),
							'text' => $parent->name,
						);
					}
				}
			}
			$items[] = array(
				'url'  => '',
				'text' => single_cat_title( '', false ),
			);
		} elseif ( is_tag() ) {
			$items[] = array(
				'url'  => '',
				'text' => sprintf( /* translators: %s: tag name */ __( 'Tag: %s', 'newsum' ), single_tag_title( '', false ) ),
			);
		} elseif ( is_author() ) {
			$author = get_queried_object();
			$items[] = array(
				'url'  => '',
				'text' => sprintf( /* translators: %s: author name */ __( 'Author: %s', 'newsum' ), $author ? $author->display_name : '' ),
			);
		} elseif ( is_day() ) {
			$items[] = array(
				'url'  => '',
				'text' => get_the_date(),
			);
		} elseif ( is_month() ) {
			$items[] = array(
				'url'  => '',
				'text' => get_the_date( 'F Y' ),
			);
		} elseif ( is_year() ) {
			$items[] = array(
				'url'  => '',
				'text' => get_the_date( 'Y' ),
			);
		} elseif ( is_search() ) {
			$items[] = array(
				'url'  => '',
				'text' => sprintf( /* translators: %s: search term */ __( 'Search: %s', 'newsum' ), get_search_query() ),
			);
		} elseif ( is_404() ) {
			$items[] = array(
				'url'  => '',
				'text' => __( 'Page Not Found', 'newsum' ),
			);
		} elseif ( is_page() ) {
			global $post;
			if ( $post && $post->post_parent ) {
				$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
				foreach ( $ancestors as $ancestor_id ) {
					$items[] = array(
						'url'  => get_permalink( $ancestor_id ),
						'text' => get_the_title( $ancestor_id ),
					);
				}
			}
			$items[] = array(
				'url'  => '',
				'text' => get_the_title(),
			);
		} elseif ( is_single() ) {
			$cats = get_the_category();
			if ( ! empty( $cats ) ) {
				$primary_cat = $cats[0];
				if ( $primary_cat->parent ) {
					$parents = array_reverse( get_ancestors( $primary_cat->term_id, 'category' ) );
					foreach ( $parents as $parent_id ) {
						$parent = get_category( $parent_id );
						if ( $parent ) {
							$items[] = array(
								'url'  => get_category_link( $parent->term_id ),
								'text' => $parent->name,
							);
						}
					}
				}
				$items[] = array(
					'url'  => get_category_link( $primary_cat->term_id ),
					'text' => $primary_cat->name,
				);
			}
			$items[] = array(
				'url'  => '',
				'text' => wp_trim_words( get_the_title(), 7, '…' ),
			);
		}

		if ( empty( $items ) ) {
			return;
		}

		echo '<nav class="newsum-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'newsum' ) . '" itemscope itemtype="https://schema.org/BreadcrumbList">';

		$total = count( $items );
		foreach ( $items as $index => $item ) {
			$is_last = ( $index === $total - 1 );
			$pos = $index + 1;

			if ( ! $is_last && ! empty( $item['url'] ) ) {
				echo '<span class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				echo '<a itemprop="item" href="' . esc_url( $item['url'] ) . '"><span itemprop="name">' . esc_html( $item['text'] ) . '</span></a>';
				echo '<meta itemprop="position" content="' . esc_attr( $pos ) . '" />';
				echo '</span>';
				echo ' ' . $sep . ' ';
			} else {
				echo '<span class="breadcrumb-item breadcrumb-current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
				echo '<span itemprop="name">' . esc_html( $item['text'] ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $pos ) . '" />';
				echo '</span>';
			}
		}

		echo '</nav>';
	}
}
