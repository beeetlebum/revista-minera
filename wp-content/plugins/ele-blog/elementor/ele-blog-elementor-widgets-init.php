<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Kontakt Addon Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'Ele_Blog_Elementor_Widgets_Init' ) ){
final class Ele_Blog_Elementor_Widgets_Init {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.4';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Kontakt_elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Kontakt_elementor An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ] );


	}



	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Elementor Editor Style
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_icon' ] );

		add_action( 'elementor/elements/categories_registered', array($this,'_widget_categories') );

		//elementor widget registered
		add_action('elementor/widgets/widgets_registered',array($this,'_widget_registered'));



	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$Check_ELementor = 'elementor/elementor.php';
		$Is_Installed_Elementor = get_plugins();

		if ( isset($Is_Installed_Elementor[ $Check_ELementor ]) ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $Check_ELementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $Check_ELementor );

			/* translators: 1: Plugin name 2: Required plugin name */
			$message = sprintf('<p>' . esc_html__( '"%1$s" requires "%2$s" to be activate.', 'ele-blog' ) . '</p>', '<strong>' . esc_html__( 'Eleblog ', 'ele-blog' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'ele-blog' ) . '</strong>');

			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor', 'ele-blog' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			/* translators: 1: Plugin name 2: Required plugin name */
			$message = sprintf('<p>' . esc_html__( '"%1$s" requires "%2$s" to be install.', 'ele-blog' ) . '</p>', '<strong>' . esc_html__( 'Eleblog ', 'ele-blog' ) . '</strong>', '<strong>' . esc_html__( 'Elementor', 'ele-blog' ) . '</strong>');

			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor', 'ele-blog' ) ) . '</p>';
		}

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ele-blog' ),
			'<strong>' . esc_html__( 'Eleblog Addon', 'ele-blog' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'ele-blog' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ele-blog' ),
			'<strong>' . esc_html__( 'ELEBLOG', 'ele-blog' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ele-blog' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );

	}


	/**
	 * _widget_categories()
	 * @since 1.0.0
	 * */

		public function _widget_categories($elements_manager){
			$elements_manager->add_category(
				'ele-blog',
				[
					'title' => esc_html__( 'ELE BLOG', 'ele-blog' ),
					'icon' => 'fa fa-plug',
				]
			);
		}


		/*
		*
		* icon support
		*/

		public function editor_icon() {
            wp_enqueue_style('eleblog-editor-icon', ELE_BLOG_CSS.'/eleblog-editor-icon.css', '1.0' );
        }



		/**
		 * _widget_registered()
		 * @since 1.0.0
		 * */
		public function _widget_registered(){

				if ( ! class_exists( 'Elementor\Widget_Base' ) ) {
				return;
			}

			$elementor_widgets = array(
				'one',
				'six',
				'seven',
				'two',
				'three',
				'four',
				'five',
				'banner',
				'trending-post',
				'post-slider',
				'what-is-new',
				'post-selector',
				'post-selector-two',
				'grid-view',
				'latest-post',
				'category-image',
				'profile-widget',
				'video-grid',
				'ads',
			);
			$elementor_widgets = apply_filters( 'eleblog_elementor_widget', $elementor_widgets );

			if ( is_array( $elementor_widgets ) && ! empty( $elementor_widgets ) ) {

				foreach ( $elementor_widgets as $widget ) {

					$template_file = ELE_BLOG_ELEMENTOR . '/widgets/class-ele-blog-style-' . $widget . '.php';
					
					if ( $template_file && is_readable( $template_file ) ) {
						include_once $template_file;
					}
				}
			}
				

		}
}

	Ele_Blog_Elementor_Widgets_Init::instance();

}