<?php
namespace XproElementorAddons\Modules;

defined( 'ABSPATH' ) || die();

use Elementor\Core\Files\CSS\Post as Post_CSS;

class Xpro_Elementor_Duplicator {

	/**
	 * Request and nonce action name
	 */
	const ACTION = 'xpro_elementor_duplicate';

	/**
	 * Register hooks and initialize
	 */
	public static function init() {
		add_action( 'admin_action_' . self::ACTION, array( __CLASS__, 'duplicate' ) );
		add_filter( 'post_row_actions', array( __CLASS__, 'add_row_actions' ), 10, 2 );
		add_filter( 'page_row_actions', array( __CLASS__, 'add_row_actions' ), 10, 2 );
	}

	/**
	 * Check if current user can duplicate
	 *
	 * @return bool
	 */
	public static function can_duplicate() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Add duplicate link in row actions
	 *
	 * @param array $actions
	 * @param \WP_Post $post
	 * @return array
	 */
	public static function add_row_actions( $actions, $post ) {
		if ( self::can_duplicate() && post_type_supports( $post->post_type, 'elementor' ) ) {
			$actions[ self::ACTION ] = sprintf(
				'<a href="%1$s" title="%2$s"><span class="screen-reader-text">%2$s</span>%3$s</a>',
				esc_url( self::get_url( $post->ID, 'list' ) ),
				sprintf(
				/* translators: %s: Title */
					esc_attr__( 'Duplicate - %s', 'xpro-elementor-addons' ),
					esc_attr( $post->post_title )
				),
				esc_html__( 'Xpro Duplicator', 'xpro-elementor-addons' )
			);
		}

		return $actions;
	}

	/**
	 * Check if a post can be duplicated by the current user.
	 *
	 * @param int $post_id Post ID to check.
	 * @return bool
	 */


	private static function can_duplicate_post( $post_id ) {
		$post = get_post( $post_id );

		if ( ! $post ) {
			return false;
		}

		// Admins can duplicate anything
		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		// Must have permission to edit THIS post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		// Allow duplication of own posts
		if ( get_current_user_id() === (int) $post->post_author ) {
			return true;
		}

		// Allow users who can edit others' posts (Editors, Admins)
		if ( current_user_can( 'edit_others_posts' ) ) {
			return true;
		}

		return false;
	}
	
	
	/**
	 * Duplicate requested post
	 *
	 * @return void
	 */
	public static function duplicate() {

		if ( ! self::can_duplicate() ) {
			return;
		}

		$_uri = esc_url_raw( $_SERVER['REQUEST_URI'] );

		// Resolve finder duplicate request issue
		if ( stripos( $_uri, '&amp;' ) !== false ) {
			$_uri       = html_entity_decode( $_uri );
			$_uri       = wp_parse_url( $_uri, PHP_URL_QUERY );
			$valid_args = array( '_wpnonce', 'post_id', 'ref' );
			parse_str( $_uri, $args );

			if ( ! empty( $args ) && is_array( $args ) ) {
				foreach ( $args as $key => $val ) {
					if ( in_array( $key, $valid_args, true ) ) {
						$_GET[ $key ] = $val;
					}
				}
			}
		}

		// $nonce   = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
		$ref     = isset( $_GET['ref'] ) ? sanitize_text_field( $_GET['ref'] ) : '';

		if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
			return;
		}

		// Validate if the post can be duplicated
		if ( ! self::can_duplicate_post( $post_id ) ) {
			wp_die(esc_html__( 'You are not allowed to duplicate this post.', 'xpro-elementor-addons' ), 403 );
		}

		if ( is_null( ( $post = get_post( $post_id ) ) ) ) {
			return;
		}

		$post = get_post( $post_id );

		if ( ! $post || empty( $post->post_type ) ) {
			return;
		}

		$post = sanitize_post( $post, 'db' );
		$duplicated_post_id = self::duplicate_post( $post );
		$redirect           = add_query_arg( array( 'post_type' => $post->post_type ), admin_url( 'edit.php' ) );

		if ( ! is_wp_error( $duplicated_post_id ) ) {
			self::duplicate_taxonomies( $post, $duplicated_post_id );
			self::duplicate_meta_entries( $post, $duplicated_post_id );

			$css = Post_CSS::create( $duplicated_post_id );
			$css->update();

			if ( 'editor' === $ref ) {
				$document = \Elementor\Plugin::instance()->documents->get( $duplicated_post_id );
				$redirect = $document->get_edit_url();
			}
		}

		wp_safe_redirect( $redirect );
		die();
	}

	/**
	 * Get duplicate url with required query params
	 *
	 * @param $post_id
	 * @param string $ref
	 * @return string
	 */
	public static function get_url( $post_id, $ref = '' ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'action'  => self::ACTION,
					'post_id' => $post_id,
					'ref'     => $ref,
				),
				admin_url( 'admin.php' )
			),
			self::ACTION
		);
	}

	/**
	 * Duplicator post
	 *
	 * @param $old_post
	 * @return int $dulicated post id
	 */
	protected static function duplicate_post( $post ) {
		$current_user = wp_get_current_user();

		$duplicated_post_args = array(
			'post_status'    => 'draft',
			'to_ping'        => $post->to_ping,
			'post_type'      => $post->post_type,
			'menu_order'     => $post->menu_order,
			'post_author'    => $current_user->ID,
			'post_parent'    => $post->post_parent,
			'ping_status'    => $post->ping_status,
			'post_excerpt'   => $post->post_excerpt,
			'post_content'   => $post->post_content,
			'post_password'  => $post->post_password,
			'comment_status' => $post->comment_status,
			'post_title'     => sprintf(
				/* translators: %s: Title */
				__( '%1$s-[Copy #%2$d]', 'xpro-elementor-addons' ),
				$post->post_title,
				$post->ID
			)
		);

		return wp_insert_post( $duplicated_post_args );
	}

	/**
	 * Copy post taxonomies to duplicated post
	 *
	 * @param $post
	 * @param $duplicated_post_id
	 */
	protected static function duplicate_taxonomies( $post, $duplicated_post_id ) {
		$taxonomies = get_object_taxonomies( $post->post_type );
		if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $duplicated_post_id, $terms, $taxonomy, false );
			}
		}
	}

	/**
	 * Copy post meta entries to duplicated post
	 *
	 * @param $post
	 * @param $duplicated_post_id
	 */
	protected static function duplicate_meta_entries( $post, $duplicated_post_id ) {
		global $wpdb;

		// 1. Fetch all meta for the original post
		$entries = $wpdb->get_results(
			$wpdb->prepare( 
				"SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", 
				$post->ID 
			) 
		);

		if ( ! empty( $entries ) && is_array( $entries ) ) {
			foreach ( $entries as $entry ) {
				// 2. Use $wpdb->insert to safely add each meta row.
				// This replaces the manual query string and handles sanitization.
				$wpdb->insert(
					$wpdb->postmeta,
					array(
						'post_id'    => $duplicated_post_id,
						'meta_key'   => $entry->meta_key,
						'meta_value' => $entry->meta_value,
					),
					array(
						'%d', 
						'%s', 
						'%s',
					)
				);
			}

			// 3. Fix Elementor Template Type specifically
			// We fetch from the source and update the duplicate to ensure consistency
			$source_type = get_post_meta( $post->ID, '_elementor_template_type', true );
			if ( ! empty( $source_type ) ) {
				update_post_meta( $duplicated_post_id, '_elementor_template_type', $source_type );
			}
		}
	}

}

Xpro_Elementor_Duplicator::init();
