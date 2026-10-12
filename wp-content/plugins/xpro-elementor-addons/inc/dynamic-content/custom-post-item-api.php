<?php

namespace XproElementorAddons;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Post_Item_Api extends Core\Xpro_Elementor_Handler_Api {

	public function config() {
		$this->prefix = 'dynamic-content';
		$this->param  = '/(?P<type>\w+)/(?P<key>\w+(|[-]\w+))/';
	}

	public function get_content_editor() {

	   	if ( ! current_user_can( 'edit_posts' ) ) {
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
				return new \WP_Error(
					'rest_forbidden',
					__( 'You do not have permission to access this endpoint.', 'xpro-elementor-addons' ),
					array( 'status' => 403 )
				);
			}
			wp_die( 'You do not have permission to access this endpoint.', 403 );
		}

		$content_key  = sanitize_text_field( $this->request['key'] );
		$content_type = sanitize_text_field( $this->request['type'] );


		$builder_post_title = 'dynamic-content-' . $content_type . '-' . $content_key;
		$builder_post_id    = xpro_get_page_by_title( $builder_post_title, OBJECT, 'xpro_content' );

		if ( ! isset( $builder_post_id ) ) {
			$builder_post = get_posts(
				array(
					'post_type'  => 'xpro_content',
					'meta_key'   => 'xpro_dynamic_template_id',
					'meta_value' => $builder_post_title,
				)
			);
			if ( isset( $builder_post ) && isset( $builder_post[0] ) ) {
				$builder_post_id = $builder_post[0];
			}
		}

		if ( is_null( $builder_post_id ) ) {
			$defaults        = array(
				'post_content' => '',
				'post_title'   => $builder_post_title,
				'post_status'  => 'publish',
				'post_type'    => 'xpro_content',
			);
			$builder_post_id = wp_insert_post( $defaults );

			update_post_meta( $builder_post_id, '_wp_page_template', 'elementor_canvas' );
			update_post_meta( $builder_post_id, 'xpro_dynamic_template_id', $builder_post_title );

		} else {
			$builder_post_id = $builder_post_id->ID;
		}

		$url = admin_url(
			'post.php?post=' . absint( $builder_post_id ) . '&action=elementor' );

		wp_safe_redirect( $url );
		exit;
		
	}
}

new Xpro_Elementor_Post_Item_Api();
