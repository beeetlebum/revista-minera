<?php
/**
 * NewSum Customizer Notify
 *
 * @package NewSum
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * NewSum Customizer Notify Class
 */
if (!class_exists('NewSum_Customizer_Notify')) {

    class NewSum_Customizer_Notify {

        /**
         * Constructor
         */
        public function __construct() {
            add_action('customize_register', array($this, 'newsum_customizer_notify_register'));
            add_action('customize_controls_enqueue_scripts', array($this, 'newsum_customizer_notify_scripts'));
            add_action('customize_controls_print_styles', array($this, 'newsum_customizer_notify_styles'));
        }

        /**
         * Register the customizer notify section
         */
        public function newsum_customizer_notify_register($wp_customize) {
            
            // Include the notify section class
            require_once get_template_directory() . '/inc/customizer-notice/class-newsum-customizer-notify-section.php';

            // Register the section type
            $wp_customize->register_section_type('NewSum_Customizer_Notify_Section');

            // Detect Pro plugin state
            $pro_plugin_active  = newsum_is_pro_active();

            if ( $pro_plugin_active ) {
                // Pro is active — show "Pro Active" + link to manage license
                $section_title = esc_html__( 'NewSum Pro ✓', 'newsum' );
                $pro_btn_text  = esc_html__( 'Manage License', 'newsum' );
                $pro_btn_url   = admin_url( 'themes.php?page=newsum-dashboard' );
                $demo_btn_text = esc_html__( 'Pro Demo', 'newsum' );
                $demo_btn_url  = 'https://newsum.wpmatcha.com/';
            } else {
                // Free — show upgrade prompt
                $section_title = esc_html__( 'NewSum Pro', 'newsum' );
                $pro_btn_text  = esc_html__( 'Upgrade to Pro', 'newsum' );
                $pro_btn_url   = 'https://wpmatcha.com/wordpress-themes/newsum/';
                $demo_btn_text = esc_html__( 'Pro Demo', 'newsum' );
                $demo_btn_url  = 'https://newsum.wpmatcha.com/';
            }

            // Add the notify section
            $wp_customize->add_section(
                new NewSum_Customizer_Notify_Section(
                    $wp_customize,
                    'newsum-customizer-notify-section',
                    array(
                        'title'      => $section_title,
                        'pro_text'   => $pro_btn_text,
                        'pro_url'    => $pro_btn_url,
                        'demo_text'  => $demo_btn_text,
                        'demo_url'   => $demo_btn_url,
                        'priority'   => 1,
                    )
                )
            );
        }

        /**
         * Enqueue customizer notify scripts
         */
        public function newsum_customizer_notify_scripts() {
            wp_enqueue_script(
                'newsum-customizer-notify',
                get_template_directory_uri() . '/inc/customizer-notice/customizer-notify.js',
                array('jquery', 'customize-controls'),
                NEWSUM_VERSION,
                true
            );
        }

        /**
         * Print customizer notify styles
         */
        public function newsum_customizer_notify_styles() {
            ?>
            <style type="text/css" id="newsum-customizer-notify-styles-v2">
                <?php include get_template_directory() . '/inc/customizer-notice/customizer-notify.css'; ?>
            </style>
            <?php
        }
    }

    // Initialize the class
    new NewSum_Customizer_Notify();
}
?>
