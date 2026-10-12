<?php
/**
 * @package Nextpage
 * @sicne 1.0.0
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ele_Blog_Init' ) ) {

	class Ele_Blog_Init {

		//instance
		protected static $instance;

		public function __construct() {
			//plugin_assets
			add_action( 'wp_enqueue_scripts', array( $this, 'plugin_assets' ), 99 );

			//load plugin dependency files
			$this->load_plugin_dependency_files();
		}



		/**
		 * plugin_assets()
		 * @since 1.0.0
		 * */
		public function plugin_assets() {
			$this->load_plugin_css();
			$this->load_plugin_js();
		}

		/*
		*ele blog default font
		*/
		public static function ele_blog_fonts_url() {
			$font_url = '';
			if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'ele-blog' ) ) :
				$font_url = add_query_arg(
					array(
						'family' => urlencode( 'Inter:300,400,500,600,700&display=swa"' ),
					), "//fonts.googleapis.com/css" );
			endif;

			return apply_filters( 'ele_blog_font_url', esc_url( $font_url ) );
		}


		/**
		 * load plugin css
		 * @since 1.0.0
		 * */
		public function load_plugin_css() {

			wp_enqueue_style( 'ele-blog-font', self::ele_blog_fonts_url(), array(), ELE_BLOG_VERSION, 'all');
			wp_enqueue_style( 'ele-blog-grid', ELE_BLOG_CSS.'/ele-blog-grid.css',array(), ELE_BLOG_VERSION, 'all');
		    wp_enqueue_style( 'eblog-global', ELE_BLOG_CSS.'/ele-blog-global.css',array(), ELE_BLOG_VERSION, 'all');

		    wp_enqueue_style( 'owl', ELE_BLOG_CSS.'/owl.min.css',array(), ELE_BLOG_VERSION, 'all');

		    wp_enqueue_style( 'ele-blog-main', ELE_BLOG_CSS.'/ele-blog-styles.css',array(), ELE_BLOG_VERSION, 'all');

		}

		/**
		 * load plugin js
		 * @since 1.0.0
		 * */
		public function load_plugin_js() {

			wp_enqueue_script( 'owl-carousel', ELE_BLOG_JS.'/owl.carousel.min.js',array(), ELE_BLOG_VERSION, 'all');

		    wp_enqueue_script( 'isotope', ELE_BLOG_JS.'/isotope.min.js',array(), ELE_BLOG_VERSION, 'all');
		    wp_enqueue_script( 'ele-blog-elementor-script', ELE_BLOG_JS.'/elementor-script.js',array( 'jquery' ), ELE_BLOG_VERSION, 'all');


		}


		/**
		 * load_plugin_dependency_files()
		 * @since 1.0.0
		 * */
		public function load_plugin_dependency_files() {

			$includes_files = array(
				array(
					'file-name' => 'functions',
					'file-path' => ELE_BLOG_INC
				),					
				array(
					'file-name' => 'image-resizer',
					'file-path' => ELE_BLOG_INC
				),				
				array(
					'file-name' => 'class-ele-blog-quick-feedback',
					'file-path' => ELE_BLOG_INC
				),							
				array(
					'file-name' => 'ele-blog-elementor-widgets-init',
					'file-path' => ELE_BLOG_ELEMENTOR
				),
			);
			if ( is_array( $includes_files ) && ! empty( $includes_files ) ) {
				foreach ( $includes_files as $file ) {
					if ( file_exists( $file['file-path'] . '/' . $file['file-name'] . '.php' ) ) {
						require_once $file['file-path'] . '/' . $file['file-name'] . '.php';
					}
				}
			}

		}

	}//end class

	new Ele_Blog_Init();
}

