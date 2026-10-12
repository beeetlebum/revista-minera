<?php
/**
 * NewSum Theme Dashboard
 *
 * @package NewSum
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'NewSum_Blog_Dashboard' ) ) :

    class NewSum_Blog_Dashboard {

        /**
         * Constructor
         */
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'admin_head', array( $this, 'admin_menu_highlight' ) );
            add_action( 'admin_notices', array( $this, 'theme_page_notice' ) );
            add_action( 'admin_init', array( $this, 'handle_notice_dismissal' ) );
            add_action( 'wp_ajax_newsum_dismiss_theme_notice', array( $this, 'ajax_dismiss_notice' ) );
            add_action( 'wp_ajax_newsum_install_activate_plugins', array( $this, 'ajax_install_activate_plugins' ) );
            add_action( 'wp_ajax_newsum_get_ocdi_nonce', array( $this, 'ajax_get_ocdi_nonce' ) );
            add_action( 'wp_ajax_newsum_save_import_branding', array( $this, 'ajax_save_import_branding' ) );
            add_action( 'pt-ocdi/after_import', array( $this, 'apply_import_branding' ), 99 );
        }


        /**
         * AJAX handler to install and activate recommended plugins (Matcha Extra and OCDI)
         */
        public function ajax_install_activate_plugins() {
            check_ajax_referer( 'newsum_install_plugins_nonce' );

            if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
                wp_send_json_error( [ 'message' => __( 'Permission denied.', 'newsum' ) ] );
            }

            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            if ( ! class_exists( 'Automatic_Upgrader_Skin' ) ) {
                include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
            }

            $plugins = [
                'one-click-demo-import' => 'one-click-demo-import/one-click-demo-import.php',
                'matcha-extra'          => 'matcha-extra/matcha-extra.php',
            ];

            foreach ( $plugins as $slug => $file ) {
                // If not installed, try to install from repo
                if ( ! file_exists( WP_PLUGIN_DIR . '/' . $file ) ) {
                    $api = plugins_api( 'plugin_information', [ 'slug' => $slug, 'fields' => [ 'sections' => false ] ] );
                    if ( ! is_wp_error( $api ) ) {
                        $upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
                        $upgrader->install( $api->download_link );
                    }
                }
                
                // If exists but inactive, activate it
                if ( file_exists( WP_PLUGIN_DIR . '/' . $file ) && ! is_plugin_active( $file ) ) {
                    activate_plugin( $file );
                }
            }

            wp_send_json_success();
        }

        /**
         * Return a fresh OCDI nonce so the dashboard JS can call OCDI AJAX directly.
         */
        public function ajax_get_ocdi_nonce() {
            check_ajax_referer( 'newsum_import_nonce', 'security' );
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'Permission denied.', 'newsum' ) ) );
            }
            wp_send_json_success( array(
                'nonce'    => wp_create_nonce( 'ocdi-ajax-verification' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) );
        }

        /**
         * AJAX handler to save temporary branding choices before demo import
         */
        public function ajax_save_import_branding() {
            check_ajax_referer( 'newsum_import_branding_nonce', 'security' );

            if ( ! current_user_can( 'edit_theme_options' ) ) {
                wp_send_json_error();
            }

            $branding = array(
                'palette'    => ! empty( $_POST['palette'] ) ? sanitize_key( $_POST['palette'] ) : 'crimson',
                'typography' => ! empty( $_POST['typography'] ) ? sanitize_key( $_POST['typography'] ) : 'modern',
            );

            update_option( 'newsum_temp_import_branding', $branding );
            wp_send_json_success( $branding );
        }

        /**
         * Apply customizer branding after OCDI import finishes
         */
        public function apply_import_branding() {
            $branding = get_option( 'newsum_temp_import_branding' );
            if ( ! empty( $branding ) && is_array( $branding ) ) {
                if ( ! empty( $branding['palette'] ) ) {
                    $palette = sanitize_key( $branding['palette'] );
                    set_theme_mod( 'newsum_color_palette', $palette );
                    
                    // Map palette to primary accent hex for consistency
                    $accent_map = array(
                        'crimson'    => '#ff2a2a',
                        'amber'      => '#f59e0b',
                        'emerald'    => '#10b981',
                        'violet'     => '#8b5cf6',
                        'cyan'       => '#06b6d4',
                        'monochrome' => '#ffffff',
                    );
                    if ( isset( $accent_map[ $palette ] ) ) {
                        set_theme_mod( 'newsum_accent_color', $accent_map[ $palette ] );
                    }
                }
                if ( ! empty( $branding['typography'] ) ) {
                    switch ( $branding['typography'] ) {
                        case 'modern':
                            set_theme_mod( 'newsum_heading_font', 'Outfit' );
                            set_theme_mod( 'newsum_body_font', 'Inter' );
                            break;
                        case 'editorial':
                            set_theme_mod( 'newsum_heading_font', 'Cinzel' );
                            set_theme_mod( 'newsum_body_font', 'Newsreader' );
                            break;
                        case 'tech':
                            set_theme_mod( 'newsum_heading_font', 'JetBrains Mono' );
                            set_theme_mod( 'newsum_body_font', 'Inter' );
                            break;
                        case 'system':
                            set_theme_mod( 'newsum_heading_font', 'System / Uploaded Font' );
                            set_theme_mod( 'newsum_body_font', 'Inter' );
                            break;
                    }
                }
                delete_option( 'newsum_temp_import_branding' );
            }
        }


        /**
         * Handle dismissal of the themes page welcome notice
         */
        public function handle_notice_dismissal() {
            if ( isset( $_GET['newsum_dismiss_notice'] ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'newsum_dismiss_notice_nonce' ) ) {
                update_user_meta( get_current_user_id(), 'newsum_theme_notice_dismissed', true );
                wp_safe_redirect( remove_query_arg( array( 'newsum_dismiss_notice', '_wpnonce' ) ) );
                exit;
            }
        }

        /**
         * AJAX handler to permanently dismiss the welcome notice
         */
        public function ajax_dismiss_notice() {
            check_ajax_referer( 'newsum_dismiss_notice_nonce', 'security' );
            if ( current_user_can( 'edit_theme_options' ) ) {
                update_user_meta( get_current_user_id(), 'newsum_theme_notice_dismissed', true );
                wp_send_json_success();
            }
            wp_send_json_error();
        }

        /**
         * Display a welcome banner on the native Appearance -> Themes screen
         */
        public function theme_page_notice() {
            global $pagenow;
            // Only show on themes.php grid (not when editing a theme or in customizer)
            if ( 'themes.php' !== $pagenow || isset( $_GET['page'] ) ) {
                return;
            }

            // Do not show if dismissed
            if ( get_user_meta( get_current_user_id(), 'newsum_theme_notice_dismissed', true ) ) {
                return;
            }

            $dashboard_url      = admin_url( 'themes.php?page=newsum-dashboard' );
            $starter_sites_url  = admin_url( 'themes.php?page=newsum-dashboard&tab=starter-sites' );
            $dismiss_url        = wp_nonce_url( add_query_arg( 'newsum_dismiss_notice', '1' ), 'newsum_dismiss_notice_nonce' );
            $dismiss_nonce      = wp_create_nonce( 'newsum_dismiss_notice_nonce' );
            ?>
            <div class="notice notice-info is-dismissible newsum-theme-notice" data-nonce="<?php echo esc_attr( $dismiss_nonce ); ?>">
                <p>
                    <strong><?php esc_html_e( 'Thank you for choosing NewSum!', 'newsum' ); ?></strong>
                    <?php esc_html_e( 'Get started immediately by importing a starter site, or visit the customizer to configure your publication.', 'newsum' ); ?>
                </p>
                <p>
                    <a href="<?php echo esc_url( $starter_sites_url ); ?>" class="button button-primary">
                        <?php esc_html_e( 'Import Starter Site', 'newsum' ); ?>
                    </a>
                    <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary">
                        <?php esc_html_e( 'Customize Theme', 'newsum' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $dashboard_url ); ?>" class="button button-secondary">
                        <?php esc_html_e( 'Theme Dashboard', 'newsum' ); ?>
                    </a>
                    <a href="<?php echo esc_url( $dismiss_url ); ?>" class="newsum-notice-dismiss-link" style="margin-left: 12px; vertical-align: middle; text-decoration: underline; color: #64748b;">
                        <?php esc_html_e( 'Permanently dismiss', 'newsum' ); ?>
                    </a>
                </p>
            </div>
            
            <script>
            (function($) {
                $(document).on('click', '.newsum-theme-notice .notice-dismiss', function() {
                    var nonce = $('.newsum-theme-notice').data('nonce');
                    $.post(ajaxurl, {
                        action: 'newsum_dismiss_theme_notice',
                        security: nonce
                    });
                });
            })(jQuery);
            </script>
            <?php
        }

        /**
         * Register the Dashboard page under Appearance
         */
        public function add_theme_page() {
            add_theme_page(
                esc_html__( 'NewSum', 'newsum' ),     // Page title
                esc_html__( 'NewSum', 'newsum' ),     // Menu title
                'edit_theme_options',                         // Capability
                'newsum-dashboard',                          // Menu slug
                array( $this, 'render_dashboard_page' )       // Callback
            );

            // "Starter Sites" shortcut — links to the same dashboard page with tab=starter-sites.
            add_theme_page(
                esc_html__( 'Starter Sites', 'newsum' ),
                esc_html__( 'Starter Sites', 'newsum' ),
                'edit_theme_options',
                'newsum-dashboard&tab=starter-sites',
                array( $this, 'render_dashboard_page' )
            );
        }

        /**
         * Highlight the NewSum Theme menu item in the admin menu
         */
        public function admin_menu_highlight() {
            ?>
            <style>
                #adminmenu a[href="themes.php?page=newsum-dashboard"] {
                    color: #fff !important;
                    font-weight: 600 !important;
                    background-color: #728c69 !important;
                    border-radius: 4px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    padding-left: 10px;
                    transition: background-color 0.15s ease-in-out;
                }
                #adminmenu a[href="themes.php?page=newsum-dashboard"]:hover,
                #adminmenu a[href="themes.php?page=newsum-dashboard"]:focus {
                    background-color: #5c7354 !important;
                    color: #fff !important;
                }
                #adminmenu a[href="themes.php?page=newsum-dashboard"]::after {
                    content: '⚡';
                    font-size: 13px;
                    margin-left: 6px;
                    vertical-align: middle;
                    color: rgba(255,255,255,0.9);
                }
            </style>
            <?php
        }

        /**
         * Enqueue scoped admin styles for the dashboard
         */
        public function enqueue_styles( $hook ) {
            // Only load on our specific dashboard page
            if ( 'appearance_page_newsum-dashboard' !== $hook ) {
                return;
            }

            $css = "
            /* Full width reset for the dashboard */
            #wpcontent { padding-left: 0; }
            .newsum-dashboard-app { background: #f0f0f1; min-height: calc(100vh - 32px); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif; }
            
            /* Top Navigation Bar */
            .newsum-dashboard-header { background: #fff; border-bottom: 1px solid #c3c4c7; display: flex; align-items: center; justify-content: flex-start; gap: 50px; padding: 0 40px; height: 70px; position: sticky; top: 32px; z-index: 10; }
            .newsum-dashboard-logo { display: flex; align-items: center; gap: 12px; }
            .newsum-dashboard-logo h3 { margin: 0; font-size: 22px; font-weight: 800; color: #1d2327; letter-spacing: -0.5px; }
            .newsum-dashboard-logo h3 span { font-weight: 400; color: #646970; }
            .newsum-version { background: #f0f0f1; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; color: #50575e; letter-spacing: 0.5px; }
            
            .newsum-dashboard-nav { display: flex; align-items: center; height: 100%; gap: 30px; }
            .newsum-dashboard-nav a { display: flex; align-items: center; height: 100%; text-decoration: none; color: #50575e; font-weight: 600; font-size: 15px; border-bottom: 3px solid transparent; transition: all 0.2s; padding: 0 5px; }
            .newsum-dashboard-nav a:hover { color: #1d2327; }
            .newsum-dashboard-nav a.active { color: #728c69; border-bottom-color: #728c69; }

            /* Main Content Area */
            .newsum-dashboard-content { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
            
            .newsum-dashboard-grid { display: grid; grid-template-columns: 2.2fr 1fr; gap: 40px; align-items: start; }
            @media (max-width: 1024px) { .newsum-dashboard-grid { grid-template-columns: 1fr; } }
            
            /* Why Upgrade Features */
            .newsum-upgrade-features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
            .newsum-feature-box { background: #fff; border: 1px solid #e2e8f0; padding: 25px 20px; border-radius: 12px; text-align: center; transition: transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
            .newsum-feature-box:hover { transform: translateY(-3px); border-color: #cbd5e1; box-shadow: 0 8px 16px rgba(0,0,0,0.06); }
            .newsum-feature-icon { width: 40px; height: 40px; background: rgba(114, 140, 105, 0.1); color: #728c69; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
            .newsum-feature-box h4 { margin: 0 0 10px; font-size: 1.1em; color: #0f172a; font-weight: 700; }
            .newsum-feature-box p { margin: 0; font-size: 0.9em; color: #64748b; line-height: 1.5; }

            /* Minimal Cards */
            .newsum-main-col { background: transparent; }
            .newsum-sidebar-col { display: flex; flex-direction: column; gap: 20px; }
            .newsum-sidebar-card { background: #fff; padding: 30px; text-align: left; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
            .newsum-sidebar-card h3 { font-size: 1.1em; color: #0f172a; margin: 0 0 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px; }
            .newsum-sidebar-card p { font-size: 0.95em; color: #64748b; margin: 0 0 20px; line-height: 1.6; }
            
            /* Minimal Table */
            .newsum-table-wrap { overflow-x: auto; background: #fff; padding: 40px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
            .newsum-comparison-title { font-size: 1.5em; color: #0f172a; margin: 0 0 25px; font-weight: 800; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
            .newsum-comparison-table { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
            .newsum-comparison-table th, .newsum-comparison-table td { padding: 18px 20px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 15px; }
            .newsum-comparison-table th { background: #f8fafc; font-weight: 700; font-size: 0.95em; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; }
            .newsum-comparison-table tr:last-child td { border-bottom: none; }
            .newsum-comparison-table .feature-col { width: 45%; }
            .newsum-comparison-table .free-col, .newsum-comparison-table .pro-col { width: 27.5%; text-align: center; }
            .newsum-comparison-table .pro-col { background: #f8fafc; }
            .newsum-comparison-table td.pro-col { font-weight: 600; color: #0f172a; }
            .newsum-icon-yes { color: #728c69; font-weight: bold; font-size: 1.4em; }
            .newsum-icon-no { color: #cbd5e1; font-weight: bold; font-size: 1.4em; }
            .newsum-table-subheader { background: #f1f5f9; font-weight: 700 !important; color: #334155; font-size: 0.85em; text-transform: uppercase; letter-spacing: 0.05em; padding-top: 16px !important; padding-bottom: 12px !important; }
            
            /* CTA & Buttons */
            .newsum-cta-wrap { text-align: center; padding: 40px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
            .newsum-cta-wrap h3 { font-size: 1.6em; color: #0f172a; margin: 0 0 10px; font-weight: 800; }
            .newsum-cta-wrap p { font-size: 1.1em; color: #475569; margin: 0 0 25px; }
            .newsum-btn { display: inline-block; padding: 12px 28px; font-size: 0.95em; font-weight: 600; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 6px; }
            .newsum-btn-primary { background: #728c69; color: #fff; }
            .newsum-btn-primary:hover { background: #5c7354; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(114, 140, 105, 0.3); }
            .newsum-btn-secondary { background: #fff; color: #0f172a; margin-right: 15px; border: 1px solid #cbd5e1; }
            .newsum-btn-secondary:hover { background: #f8fafc; color: #0f172a; border-color: #94a3b8; }
            .newsum-btn-outline { background: transparent; border: 1px solid #cbd5e1; color: #334155; padding: 10px 20px; font-size: 0.85em; width: 100%; text-align: center; box-sizing: border-box; }
            .newsum-btn-outline:hover { background: #f8fafc; color: #728c69; border-color: #728c69; }

            /* Starter Sites Gallery */
            .newsum-starter-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
            @media (max-width: 768px) { .newsum-starter-grid { grid-template-columns: 1fr; } }
            .newsum-starter-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
            .newsum-starter-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
            .newsum-starter-thumb { overflow: hidden; aspect-ratio: 4/3; background: #f0f0f1; position: relative; }
            .newsum-starter-thumb img { width: 100%; height: 100%; object-fit: cover; object-position: top; display: block; transition: transform 0.4s ease; }
            .newsum-starter-card:hover .newsum-starter-thumb img { transform: scale(1.03); }
            .newsum-starter-info { padding: 20px; }
            .newsum-starter-info h4 { margin: 0 0 8px; font-size: 1.1em; font-weight: 700; color: #0f172a; }
            .newsum-starter-desc { font-size: 13px; color: #64748b; line-height: 1.5; margin: 0 0 16px; min-height: 40px; }
            .newsum-lite-pill { display: inline-block; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.04em; margin-right: 6px; vertical-align: middle; }
            .newsum-starter-actions { display: flex; gap: 10px; }
            .newsum-starter-actions .newsum-btn { padding: 8px 18px; font-size: 0.85em; margin-right: 0; }
            .newsum-thumb-placeholder { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: #f0f0f1; color: #c3c4c7; }
            .newsum-thumb-placeholder .dashicons { font-size: 48px; width: 48px; height: 48px; }

            /* Locked Pro Cards */
            .newsum-lock-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.45); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #fff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }
            .newsum-lock-overlay .dashicons { font-size: 28px; width: 28px; height: 28px; }

            /* Badges */
            .newsum-free-badge, .newsum-pro-badge { position: absolute; top: 12px; right: 12px; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
            .newsum-free-badge { background: #728c69; color: #fff; }
            .newsum-pro-badge { background: #8b5cf6; color: #fff; }
            @keyframes newsum-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            ";
            
            wp_register_style( 'newsum-dashboard-style', false );
            wp_enqueue_style( 'newsum-dashboard-style' );
            wp_add_inline_style( 'newsum-dashboard-style', $css );
        }

        /**
         * Render the dashboard page content
         */
        public function render_dashboard_page() {
            $theme_version = wp_get_theme()->get('Version');
            $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'welcome';
            $pro_active = newsum_is_pro_active();
            ?>
            <div class="newsum-dashboard-app">
                <div class="newsum-dashboard-header">
                    <div class="newsum-dashboard-logo">
                        <h3>NewSum <span>Theme</span></h3>
                        <span class="newsum-version">v<?php echo esc_html( $theme_version ); ?></span>
                        <?php $pro_plugin_installed = defined( 'NEWSUM_PRO_VERSION' ); ?>
                        <?php if ( $pro_plugin_installed ) : ?>
                            <?php if ( get_option( 'NEWSUM_PRO_license_status' ) === 'valid' ) : ?>
                                <span style="background:#728c69; color:#fff; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;"><?php esc_html_e( 'Pro Active', 'newsum' ); ?></span>
                            <?php else : ?>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=newsum-pro' ) ); ?>" style="background:#dc3232; color:#fff; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none;"><?php esc_html_e( 'License Invalid', 'newsum' ); ?></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <a href="https://wpmatcha.com/wordpress-themes/newsum/" target="_blank" style="background:#f0f4ef; color:#728c69; border:1px solid #b5ccaf; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none;"><?php esc_html_e( 'Get Pro', 'newsum' ); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="newsum-dashboard-nav">
                        <a href="?page=newsum-dashboard&tab=welcome" class="<?php echo 'welcome' === $active_tab ? 'active' : ''; ?>"><?php esc_html_e( 'Welcome', 'newsum' ); ?></a>
                        <a href="?page=newsum-dashboard&tab=starter-sites" class="<?php echo 'starter-sites' === $active_tab ? 'active' : ''; ?>"><?php esc_html_e( 'Starter Sites', 'newsum' ); ?></a>
                        <a href="?page=newsum-dashboard&tab=patterns" class="<?php echo 'patterns' === $active_tab ? 'active' : ''; ?>"><?php esc_html_e( 'Page Layouts', 'newsum' ); ?></a>
                        <a href="?page=newsum-dashboard&tab=shortcodes" class="<?php echo 'shortcodes' === $active_tab ? 'active' : ''; ?>"><?php esc_html_e( 'Tools & Shortcodes', 'newsum' ); ?></a>
                    </div>
                </div>

                <div class="newsum-dashboard-content">
                    <?php if ( 'patterns' === $active_tab ) : ?>
                        <!-- Page Layouts Tab -->
                        <div class="newsum-cta-wrap" style="margin-top:0;">
                            <h3><?php esc_html_e( 'Build Custom Homepages', 'newsum' ); ?></h3>
                            <p><?php esc_html_e( 'Want to build a Creator Studio or Business layout? We have designed stunning block patterns for you.', 'newsum' ); ?></p>
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:8px; display:inline-block; text-align:left; margin-bottom:20px;">
                                <ol style="margin:0; padding-left:20px; font-size:15px; color:#334155; line-height: 1.6;">
                                    <li>Go to <strong>Pages > Add New</strong>.</li>
                                    <li>When the "Choose a Pattern" popup appears, select the <strong>NewSum Patterns</strong> category.</li>
                                    <li>Click on the <strong>Creator Studio Homepage</strong> layout to insert it instantly!</li>
                                </ol>
                            </div>
                            <br>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>" class="newsum-btn newsum-btn-primary"><?php esc_html_e( 'Create a Page Now', 'newsum' ); ?></a>
                        </div>
                    <?php elseif ( 'starter-sites' === $active_tab ) : ?>
                        <!-- Starter Sites Tab -->
                        <?php
                        $ocdi_active    = is_plugin_active( 'one-click-demo-import/one-click-demo-import.php' );
                        $matcha_active  = is_plugin_active( 'matcha-extra/matcha-extra.php' );
                        $pro_active     = newsum_is_pro_active();
                        $base_ready     = ( $ocdi_active && $matcha_active ) || ( $ocdi_active && $pro_active );

                        // Demo definitions — hardcoded so they show even without newsum-pro active.
                        // Preview images are stored in the theme so they display for both free and pro users.
                        $preview_base = get_template_directory_uri() . '/assets/images/demos/';

                        $fallback_svg_1 = get_template_directory_uri() . '/assets/images/default-fallback.svg';
                        $fallback_svg_2 = get_template_directory_uri() . '/assets/images/fallback-1.svg';

                        $starter_demos = array(
                            array(
                                'name'        => 'NewSum Tech (Default Bento)',
                                'preview_url' => 'https://newsum.wpmatcha.com/',
                                'image_url'   => $preview_base . 'default.jpg',
                                'is_pro'      => false,
                                'coming_soon' => false,
                                'import_index'=> 0,
                            ),
                            array(
                                'name'        => 'Classic Broadsheet (The Tribune)',
                                'preview_url' => 'https://newsum.wpmatcha.com/',
                                'image_url'   => $preview_base . 'newspaper.jpg',
                                'is_pro'      => false,
                                'coming_soon' => false,
                                'import_index'=> 1,
                            ),
                            array(
                                'name'        => 'Terminal Wire (Dark Edition)',
                                'preview_url' => '',
                                'image_url'   => $fallback_svg_1,
                                'is_pro'      => true,
                                'coming_soon' => true,
                                'import_index'=> 2,
                            ),
                            array(
                                'name'        => 'Newsroom Grid (AI & Data)',
                                'preview_url' => '',
                                'image_url'   => $fallback_svg_2,
                                'is_pro'      => true,
                                'coming_soon' => true,
                                'import_index'=> 3,
                            ),
                            array(
                                'name'        => 'Modern Minimalist (Magazine Split)',
                                'preview_url' => '',
                                'image_url'   => $fallback_svg_1,
                                'is_pro'      => true,
                                'coming_soon' => true,
                                'import_index'=> 4,
                            ),
                        );

                        // Grab real preview images from the OCDI filter for ready demos
                        $ocdi_demos = apply_filters( 'pt-ocdi/import_files', array() );
                        foreach ( $starter_demos as $k => $starter ) {
                            $import_id = $starter['import_index'];
                            if ( empty( $starter['coming_soon'] ) && isset( $ocdi_demos[ $import_id ] ) && ! empty( $ocdi_demos[ $import_id ]['import_preview_image_url'] ) ) {
                                $starter_demos[ $k ]['image_url'] = $ocdi_demos[ $import_id ]['import_preview_image_url'];
                            }
                        }
                        ?>

                        <?php if ( $base_ready ) : ?>
                            <!-- Plugins active — show demo gallery -->
                            <div class="newsum-starter-grid">
                                <?php foreach ( $starter_demos as $demo ) :
                                    $is_locked = $demo['is_pro'] && ! $pro_active;
                                ?>
                                    <div class="newsum-starter-card<?php echo $is_locked ? ' newsum-card-locked' : ''; ?>">
                                        <div class="newsum-starter-thumb">
                                            <?php if ( ! empty( $demo['image_url'] ) ) : ?>
                                                <img src="<?php echo esc_url( $demo['image_url'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>" />
                                            <?php else : ?>
                                                <div class="newsum-thumb-placeholder">
                                                    <span class="dashicons dashicons-format-image"></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ( $is_locked ) : ?>
                                                <div class="newsum-lock-overlay">
                                                    <span class="dashicons dashicons-lock"></span>
                                                    <span><?php esc_html_e( 'Pro', 'newsum' ); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ( $demo['is_pro'] ) : ?>
                                                <span class="newsum-pro-badge"><?php esc_html_e( 'Pro', 'newsum' ); ?></span>
                                            <?php else : ?>
                                                <span class="newsum-free-badge"><?php esc_html_e( 'Free', 'newsum' ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="newsum-starter-info">
                                            <h4><?php echo esc_html( $demo['name'] ); ?></h4>
                                            <p class="newsum-starter-desc">
                                                <?php if ( ! $demo['is_pro'] ) : ?>
                                                    <?php if ( $pro_active ) : ?>
                                                        <?php esc_html_e( 'Pro Edition active — imports complete layout suite, sample posts, and premium modules.', 'newsum' ); ?>
                                                    <?php else : ?>
                                                        <span class="newsum-lite-pill"><?php esc_html_e( 'Free Lite', 'newsum' ); ?></span>
                                                        <?php esc_html_e( 'Free to import. Imports core layout and demo posts. Advanced widgets and pro features unlock with NewSum Pro.', 'newsum' ); ?>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <?php esc_html_e( 'Exclusive layout featuring specialized editorial blocks and custom styling.', 'newsum' ); ?>
                                                <?php endif; ?>
                                            </p>
                                            <div class="newsum-starter-actions">
                                                <?php if ( ! empty( $demo['preview_url'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="newsum-btn newsum-btn-secondary" target="_blank" rel="noopener"><?php esc_html_e( 'Preview', 'newsum' ); ?></a>
                                                <?php endif; ?>
                                                <?php if ( ! empty( $demo['coming_soon'] ) ) : ?>
                                                    <span class="newsum-btn newsum-btn-secondary" style="opacity:0.75;cursor:default;font-weight:600;"><?php esc_html_e( 'Coming Soon', 'newsum' ); ?></span>
                                                <?php elseif ( $is_locked ) : ?>
                                                    <a href="https://wpmatcha.com/wordpress-themes/newsum/" class="newsum-btn newsum-btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Unlock Pro', 'newsum' ); ?></a>
                                                <?php elseif ( isset( $ocdi_demos[ $demo['import_index'] ] ) && ! empty( $ocdi_demos[ $demo['import_index'] ]['local_import_file'] ) && file_exists( $ocdi_demos[ $demo['import_index'] ]['local_import_file'] ) ) : ?>
                                                    <button type="button" class="newsum-btn newsum-btn-primary newsum-direct-import-btn" data-index="<?php echo esc_attr( $demo['import_index'] ); ?>" data-name="<?php echo esc_attr( $demo['name'] ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'newsum_import_nonce' ) ); ?>"><?php esc_html_e( 'Import', 'newsum' ); ?></button>
                                                <?php else : ?>
                                                    <span class="newsum-btn newsum-btn-secondary" style="opacity:0.5;cursor:default;"><?php esc_html_e( 'Coming Soon', 'newsum' ); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php
                            $i18n_import = array(
                                'no_close'    => __( 'Please do not close this window.', 'newsum' ),
                                'step1_title' => __( 'Importing content…', 'newsum' ),
                                'step1'       => __( 'Step 1 of 3 — Content', 'newsum' ),
                                'step2_title' => __( 'Importing customizer settings…', 'newsum' ),
                                'step2_sub'   => __( 'Almost there…', 'newsum' ),
                                'step2'       => __( 'Step 2 of 3 — Customizer', 'newsum' ),
                                'step3_title' => __( 'Running after-import setup…', 'newsum' ),
                                'step3_sub'   => __( 'Finishing up…', 'newsum' ),
                                'step3'       => __( 'Step 3 of 3 — Finalizing', 'newsum' ),
                                'done_title'  => __( 'Import complete!', 'newsum' ),
                                'done_sub'    => __( 'Your site is ready. Redirecting…', 'newsum' ),
                                'err_content' => __( 'Content import failed.', 'newsum' ),
                                'err_nonce'   => __( 'Could not get nonce.', 'newsum' ),
                                'err_prefix'  => __( 'Import error: ', 'newsum' ),
                                'confirm_pre' => __( 'Import the', 'newsum' ),
                                'confirm_suf' => __( 'demo? This will import content, widgets, and customizer settings.', 'newsum' ),
                                'home_url'    => home_url( '/' ),
                                'ajax_url'    => admin_url( 'admin-ajax.php' ),
                            );
                            ?>

                            <!-- Pre-Import Branding Wizard Modal (Botiga-style UX) -->
                            <div id="newsum-branding-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.65);z-index:999999;align-items:center;justify-content:center;">
                              <div style="background:#fff;border-radius:16px;padding:36px;max-width:640px;width:95%;max-height:90vh;overflow-y:auto;text-align:left;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);position:relative;">
                                <button type="button" id="newsum-branding-close" style="position:absolute;top:20px;right:20px;background:none;border:none;font-size:24px;cursor:pointer;color:#64748b;">&times;</button>
                                
                                <span style="display:inline-block;background:#e2e8f0;color:#334155;font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;"><?php esc_html_e( 'Step 1 of 2: Publication Branding', 'newsum' ); ?></span>
                                <h3 style="margin:0 0 8px;font-size:22px;color:#0f172a;font-weight:700;">⚡ <?php esc_html_e( 'Customize Your Publication', 'newsum' ); ?></h3>
                                <p style="color:#64748b;margin:0 0 24px;font-size:14px;line-height:1.5;"><?php esc_html_e( 'Select your editorial color scheme and typography pairing. We will automatically apply these settings to your imported site.', 'newsum' ); ?></p>

                                <?php if ( ! $pro_active ) : ?>
                                  <div style="background:#f8fafc;border-left:4px solid #728c69;padding:12px 14px;margin-bottom:20px;border-radius:6px;font-size:13px;color:#475569;line-height:1.5;">
                                    <strong style="color:#0f172a;"><?php esc_html_e( 'Free Lite Import:', 'newsum' ); ?></strong>
                                    <?php esc_html_e( 'You are importing the free Lite edition. Core layouts, pages, and sample posts are included. Advanced widgets, advertisement managers, and off-canvas drawers require NewSum Pro.', 'newsum' ); ?>
                                  </div>
                                <?php endif; ?>

                                <!-- Section 1: Color Palette Swatches -->
                                <div style="margin-bottom:28px;">
                                  <label style="display:block;font-size:14px;font-weight:700;color:#1e293b;margin-bottom:12px;"><?php esc_html_e( '1. Choose Color Accent', 'newsum' ); ?></label>
                                  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
                                    <label class="newsum-branding-card" style="border:2px solid #ff2a2a;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff5f5;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="crimson" checked style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Cyber Crimson (Default)', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#ff2a2a;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#09090b;display:inline-block;"></span>
                                      </div>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="amber" style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Cyber Amber', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#09090b;display:inline-block;"></span>
                                      </div>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="emerald" style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Matrix Emerald', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#064e3b;display:inline-block;"></span>
                                      </div>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="violet" style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Electric Violet', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#8b5cf6;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#2e1065;display:inline-block;"></span>
                                      </div>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="cyan" style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Data Cyan', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#06b6d4;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#083344;display:inline-block;"></span>
                                      </div>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;background:#fff;">
                                      <div>
                                        <input type="radio" name="newsum-branding-palette" value="monochrome" style="margin-right:6px;">
                                        <span style="font-weight:600;font-size:13px;color:#1e293b;"><?php esc_html_e( 'Monochrome Slate', 'newsum' ); ?></span>
                                      </div>
                                      <div style="display:flex;gap:4px;">
                                        <span style="width:16px;height:16px;border-radius:50%;background:#ffffff;border:1px solid #cbd5e1;display:inline-block;"></span>
                                        <span style="width:16px;height:16px;border-radius:50%;background:#09090b;display:inline-block;"></span>
                                      </div>
                                    </label>
                                  </div>
                                </div>

                                <!-- Section 2: Typography Pairing -->
                                <div style="margin-bottom:32px;">
                                  <label style="display:block;font-size:14px;font-weight:700;color:#1e293b;margin-bottom:12px;"><?php esc_html_e( '2. Choose Editorial Typography', 'newsum' ); ?></label>
                                  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">
                                    <label class="newsum-branding-card" style="border:2px solid #ff2a2a;border-radius:10px;padding:14px;cursor:pointer;background:#fff5f5;">
                                      <div style="display:flex;align-items:center;margin-bottom:6px;">
                                        <input type="radio" name="newsum-branding-typography" value="modern" checked style="margin-right:8px;">
                                        <strong style="font-size:14px;color:#0f172a;font-family:sans-serif;"><?php esc_html_e( 'Modern Tech (Outfit + Inter)', 'newsum' ); ?></strong>
                                      </div>
                                      <p style="margin:0 0 0 24px;font-size:12px;color:#64748b;font-family:sans-serif;"><?php esc_html_e( 'High-impact geometric sans-serif for frontier tech & news.', 'newsum' ); ?></p>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;background:#fff;">
                                      <div style="display:flex;align-items:center;margin-bottom:6px;">
                                        <input type="radio" name="newsum-branding-typography" value="editorial" style="margin-right:8px;">
                                        <strong style="font-size:14px;color:#0f172a;font-family:serif;"><?php esc_html_e( 'Classic Broadsheet (Cinzel + Newsreader)', 'newsum' ); ?></strong>
                                      </div>
                                      <p style="margin:0 0 0 24px;font-size:12px;color:#64748b;font-family:serif;"><?php esc_html_e( 'Authoritative traditional newsroom and editorial tribune.', 'newsum' ); ?></p>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;background:#fff;">
                                      <div style="display:flex;align-items:center;margin-bottom:6px;">
                                        <input type="radio" name="newsum-branding-typography" value="tech" style="margin-right:8px;">
                                        <strong style="font-size:14px;color:#0f172a;font-family:monospace;"><?php esc_html_e( 'Terminal Monospace (JetBrains Mono + Inter)', 'newsum' ); ?></strong>
                                      </div>
                                      <p style="margin:0 0 0 24px;font-size:12px;color:#64748b;font-family:sans-serif;"><?php esc_html_e( 'Cyberpunk data terminal feel with hacker aesthetic.', 'newsum' ); ?></p>
                                    </label>
                                    <label class="newsum-branding-card" style="border:1px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;background:#fff;">
                                      <div style="display:flex;align-items:center;margin-bottom:6px;">
                                        <input type="radio" name="newsum-branding-typography" value="system" style="margin-right:8px;">
                                        <strong style="font-size:14px;color:#0f172a;font-family:system-ui,sans-serif;"><?php esc_html_e( 'High-Speed Neutral (System UI + Inter)', 'newsum' ); ?></strong>
                                      </div>
                                      <p style="margin:0 0 0 24px;font-size:12px;color:#64748b;font-family:system-ui,sans-serif;"><?php esc_html_e( 'Instant zero-lag loading with system native typography.', 'newsum' ); ?></p>
                                    </label>
                                  </div>
                                </div>

                                <!-- Footer Actions -->
                                <div style="display:flex;justify-content:flex-end;gap:12px;border-top:1px solid #e2e8f0;padding-top:20px;">
                                  <button type="button" id="newsum-branding-cancel" class="newsum-btn newsum-btn-outline" style="padding:10px 20px;height:auto;line-height:1.4;"><?php esc_html_e( 'Cancel', 'newsum' ); ?></button>
                                  <button type="button" id="newsum-branding-submit" class="newsum-btn newsum-btn-primary" style="padding:10px 24px;height:auto;line-height:1.4;font-size:15px;background:#ff2a2a;">
                                    <span class="dashicons dashicons-rocket" style="vertical-align:middle;margin-top:-2px;margin-right:4px;"></span>
                                    <span style="vertical-align:middle;"><?php esc_html_e( 'Build My Publication', 'newsum' ); ?></span>
                                  </button>
                                </div>
                              </div>
                            </div>

                            <!-- One-Click Import Modal -->
                            <div id="newsum-import-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.65);z-index:999999;align-items:center;justify-content:center;">
                              <div style="background:#fff;border-radius:10px;padding:40px;max-width:480px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
                                <div id="newsum-import-icon" style="font-size:40px;margin-bottom:12px;">&#9203;</div>
                                <h3 id="newsum-import-title" style="margin:0 0 8px;font-size:20px;color:#1e293b;"><?php esc_html_e( 'Preparing import…', 'newsum' ); ?></h3>
                                <p id="newsum-import-status" style="color:#64748b;margin:0 0 20px;font-size:14px;"><?php esc_html_e( 'Please do not close this window.', 'newsum' ); ?></p>
                                <div style="background:#f1f5f9;border-radius:99px;height:8px;overflow:hidden;margin-bottom:10px;">
                                  <div id="newsum-import-bar" style="background:#ff2a2a;height:100%;width:0%;border-radius:99px;transition:width 0.5s ease;"></div>
                                </div>
                                <p id="newsum-import-step" style="font-size:12px;color:#94a3b8;margin:0;"></p>
                              </div>
                            </div>

                            <script>
                            (function(){
                                var cfg = <?php echo wp_json_encode( $i18n_import ); ?>;
                                var activeIdx = null;
                                var activeName = null;
                                var activeSec = null;

                                function sp(pct,t,s,st){
                                    document.getElementById('newsum-import-bar').style.width=pct+'%';
                                    if(t!==undefined)document.getElementById('newsum-import-title').textContent=t;
                                    if(s!==undefined)document.getElementById('newsum-import-status').textContent=s;
                                    if(st!==undefined)document.getElementById('newsum-import-step').textContent=st;
                                }
                                function showM(){document.getElementById('newsum-import-modal').style.display='flex';}
                                function hideM(){document.getElementById('newsum-import-modal').style.display='none';}
                                function showBrandingM(){document.getElementById('newsum-branding-modal').style.display='flex';}
                                function hideBrandingM(){document.getElementById('newsum-branding-modal').style.display='none';}

                                function ajax(action,data){
                                    var fd=new FormData();
                                    fd.append('action',action);
                                    Object.keys(data).forEach(function(k){fd.append(k,data[k]);});
                                    return fetch(cfg.ajax_url,{method:'POST',body:fd}).then(function(r){return r.json();});
                                }

                                function runContent(idx, nonce) {
                                    return ajax('ocdi_import_demo_data', {security: nonce, selected: idx}).then(function(r) {
                                        if (r && r.status === 'newAJAX') {
                                            return runContent(idx, nonce);
                                        }
                                        if (r && (r.status === 'customizerAJAX' || r.status === 'afterAllImportAJAX' || r.message)) {
                                            return r;
                                        }
                                        throw new Error(r && r.message ? r.message : (typeof r === 'string' ? r : cfg.err_content));
                                    });
                                }

                                function runCustomizer(r, idx, nonce) {
                                    if (r && r.status === 'customizerAJAX') {
                                        sp(55, cfg.step2_title, cfg.step2_sub, cfg.step2);
                                        return ajax('ocdi_import_customizer_data', {security: nonce, selected: idx}).then(function(res) {
                                            if (res && (res.status === 'afterAllImportAJAX' || res.message)) {
                                                return res;
                                            }
                                            throw new Error(res && res.message ? res.message : cfg.err_customizer);
                                        });
                                    }
                                    return r;
                                }

                                function runAfterImport(r, idx, nonce) {
                                    if (r && (r.status === 'afterAllImportAJAX' || !r.status)) {
                                        sp(80, cfg.step3_title, cfg.step3_sub, cfg.step3);
                                        return ajax('ocdi_after_import_data', {security: nonce, selected: idx}).then(function(res) {
                                            if (res && (res.message || res.status || typeof res === 'object')) {
                                                return res;
                                            }
                                            throw new Error(res && res.message ? res.message : cfg.err_after);
                                        });
                                    }
                                    return r;
                                }

                                function runImport(idx, nonce){
                                    showM();
                                    sp(10, cfg.step1_title, cfg.no_close, cfg.step1);
                                    runContent(idx, nonce)
                                    .then(function(r) {
                                        return runCustomizer(r, idx, nonce);
                                    })
                                    .then(function(r) {
                                        return runAfterImport(r, idx, nonce);
                                    })
                                    .then(function(){
                                        sp(100, cfg.done_title, cfg.done_sub, '');
                                        document.getElementById('newsum-import-icon').innerHTML='&#127881;';
                                        setTimeout(function(){window.location.href=cfg.home_url;},2500);
                                    })
                                    .catch(function(e){
                                        hideM();
                                        alert(cfg.err_prefix + (e.message || String(e)));
                                    });
                                }

                                document.addEventListener('DOMContentLoaded',function(){
                                    // Handle card border highlighting in branding modal
                                    document.querySelectorAll('.newsum-branding-card input[type="radio"]').forEach(function(radio){
                                        radio.addEventListener('change',function(){
                                            var name = radio.getAttribute('name');
                                            document.querySelectorAll('input[name="'+name+'"]').forEach(function(r){
                                                var card = r.closest('.newsum-branding-card');
                                                if(r.checked){
                                                    card.style.borderColor = '#728c69';
                                                    card.style.borderWidth = '2px';
                                                } else {
                                                    card.style.borderColor = '#e2e8f0';
                                                    card.style.borderWidth = '1px';
                                                }
                                            });
                                        });
                                    });

                                    // Close branding modal
                                    var closeBtn = document.getElementById('newsum-branding-close');
                                    var cancelBtn = document.getElementById('newsum-branding-cancel');
                                    if(closeBtn) closeBtn.addEventListener('click', hideBrandingM);
                                    if(cancelBtn) cancelBtn.addEventListener('click', hideBrandingM);

                                    // Open branding modal on Import button click
                                    document.querySelectorAll('.newsum-direct-import-btn').forEach(function(btn){
                                        btn.addEventListener('click',function(){
                                            activeIdx = btn.getAttribute('data-index');
                                            activeName = btn.getAttribute('data-name');
                                            activeSec = btn.getAttribute('data-nonce');
                                            showBrandingM();
                                        });
                                    });

                                    // Submit branding & launch import
                                    var submitBtn = document.getElementById('newsum-branding-submit');
                                    if(submitBtn){
                                        submitBtn.addEventListener('click', function(){
                                            var selPalette = document.querySelector('input[name="newsum-branding-palette"]:checked');
                                            var selTypo = document.querySelector('input[name="newsum-branding-typography"]:checked');
                                            var paletteVal = selPalette ? selPalette.value : 'sage';
                                            var typoVal = selTypo ? selTypo.value : 'classic';

                                            hideBrandingM();
                                            showM();
                                            sp(5, 'Saving branding styles…', cfg.no_close, 'Applying color palette & typography…');

                                            var fdBranding = new FormData();
                                            fdBranding.append('action', 'newsum_save_import_branding');
                                            fdBranding.append('security', '<?php echo esc_js( wp_create_nonce( 'newsum_import_branding_nonce' ) ); ?>');
                                            fdBranding.append('palette', paletteVal);
                                            fdBranding.append('typography', typoVal);

                                            fetch(cfg.ajax_url, {method: 'POST', body: fdBranding})
                                            .then(function(r){ return r.json(); })
                                            .then(function(){
                                                var fd = new FormData();
                                                fd.append('action', 'newsum_get_ocdi_nonce');
                                                fd.append('security', activeSec);
                                                return fetch(cfg.ajax_url, {method: 'POST', body: fd});
                                            })
                                            .then(function(r){ return r.json(); })
                                            .then(function(r){
                                                if(!r.success) throw new Error(cfg.err_nonce);
                                                runImport(parseInt(activeIdx, 10), r.data.nonce);
                                            }).catch(function(e){
                                                hideM();
                                                alert(cfg.err_prefix + (e.message || String(e)));
                                            });
                                        });
                                    }
                                });
                            })();
                            </script>

                        <?php else : ?>
                            <!-- Plugins not active — show install CTA -->
                            <div class="newsum-cta-wrap">
                                <h3><?php esc_html_e( 'Unlock Starter Sites', 'newsum' ); ?></h3>
                                <p><?php esc_html_e( 'Install and activate our recommended plugins (Matcha Extra & One Click Demo Import) to access the library of professional starter sites.', 'newsum' ); ?></p>
                                <a href="#" class="newsum-btn newsum-btn-primary newsum-install-plugins-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'newsum_install_plugins_nonce' ) ); ?>">
                                    <span class="text"><?php esc_html_e( 'Install & Activate Plugins', 'newsum' ); ?></span>
                                </a>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var btn = document.querySelector('.newsum-install-plugins-btn');
                                if (btn) {
                                    btn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        if (btn.classList.contains('processing')) return;
                                        btn.classList.add('processing');
                                        
                                        var nonce = btn.getAttribute('data-nonce');
                                        btn.innerHTML = '<?php esc_html_e( 'Processing...', 'newsum' ); ?> <span class="dashicons dashicons-update newsum-spin" style="animation: newsum-spin 2s infinite linear; vertical-align: middle;"></span>';
                                        btn.style.opacity = '0.8';
                                        btn.style.pointerEvents = 'none';

                                        var formData = new FormData();
                                        formData.append('action', 'newsum_install_activate_plugins');
                                        formData.append('_ajax_nonce', nonce);

                                        fetch(ajaxurl, { method: 'POST', body: formData })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                btn.innerHTML = '<?php esc_html_e( 'Redirecting...', 'newsum' ); ?>';
                                                window.location.reload();
                                            } else {
                                                alert('<?php esc_html_e( 'Failed to install automatically. Please use the Appearance -> Install Plugins menu.', 'newsum' ); ?>');
                                                btn.innerHTML = '<?php esc_html_e( 'Install & Activate Plugins', 'newsum' ); ?>';
                                                btn.classList.remove('processing');
                                                btn.style.opacity = '1';
                                                btn.style.pointerEvents = 'auto';
                                            }
                                        }).catch(err => {
                                            alert('<?php esc_html_e( 'A network error occurred.', 'newsum' ); ?>');
                                            btn.innerHTML = '<?php esc_html_e( 'Install & Activate Plugins', 'newsum' ); ?>';
                                            btn.classList.remove('processing');
                                            btn.style.opacity = '1';
                                            btn.style.pointerEvents = 'auto';
                                        });
                                    });
                                }
                            });
                            </script>

                        <?php endif; ?>


                    <?php elseif ( 'shortcodes' === $active_tab ) : ?>

                        <!-- Tools & Shortcodes Tab -->
                        <div class="newsum-dashboard-grid" style="grid-template-columns: 1fr;">
                            <div class="newsum-main-col">
                                <div class="newsum-table-wrap">
                                    <h2 class="newsum-comparison-title"><?php esc_html_e( 'Tools, Widgets & Shortcodes', 'newsum' ); ?></h2>
                                    <p style="color: #64748b; font-size: 1.1em; margin-top: -15px; margin-bottom: 30px;">
                                        <?php esc_html_e( 'Discover all the custom widgets and shortcodes included with NewSum to build powerful pages.', 'newsum' ); ?>
                                    </p>

                                    <?php if ( ! $pro_active ) : ?>
                                    <div style="background: #fff8e6; border-left: 4px solid #f59e0b; padding: 15px 20px; border-radius: 4px; margin-bottom: 30px;">
                                        <p style="margin: 0; color: #92400e; font-weight: 500;">
                                            <strong style="color: #b45309;">Heads up:</strong> Some of these tools are part of <strong>NewSum Pro</strong>. <a href="https://wpmatcha.com/wordpress-themes/newsum/" target="_blank" style="color: #b45309; text-decoration: underline;">Upgrade to Pro</a> to unlock everything!
                                        </p>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Widgets Section -->
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                                        <h3 style="font-size: 1.25em; color: #0f172a; margin: 0 0 15px; display: flex; align-items: center; gap: 10px;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                            Custom Sidebar Widgets
                                        </h3>
                                        <p style="color: #475569; margin: 0 0 20px; line-height: 1.6;">
                                            NewSum comes bundled with a suite of custom widgets. Head over to <strong>Appearance > Widgets</strong> (or use the Customizer) to add these to your sidebars or footer areas.
                                        </p>
                                        
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                                            <!-- Free Widgets -->
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">NewSum: Author Profile <span style="font-size: 10px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #475569;">FREE</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Display a stylish author bio card.</p>
                                            </div>
                                            <!-- Free Widgets -->
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">NewSum: Author Profile <span style="font-size: 10px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #475569;">FREE</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Display an author or lead correspondent bio card with social channels.</p>
                                            </div>
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">NewSum: Trending (Ranked) <span style="font-size: 10px; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #475569;">FREE</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Showcase ranked trending dispatches (01–05) with category badges.</p>
                                            </div>
                                            
                                            <!-- Pro Widgets -->
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">Table of Contents <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Sticky interactive article outline widget.</p>
                                            </div>
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">Editorial Newsroom Team <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Showcase your newsroom's correspondents & editors.</p>
                                            </div>
                                            <div style="background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                                <h4 style="margin: 0 0 5px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">Off-Canvas Drawer Widgets <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span></h4>
                                                <p style="margin: 0; font-size: 0.85em; color: #64748b;">Inject custom widgets into the slide-out navigation drawer.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop Off-Canvas Drawer -->
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                                        <h3 style="font-size: 1.25em; color: #0f172a; margin: 0 0 10px; display: flex; align-items: center; gap: 10px;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                                            Desktop Off-Canvas Editorial Drawer <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span>
                                        </h3>
                                        <p style="color: #475569; margin: 0 0 20px; line-height: 1.6;">
                                            NewSum Pro includes an editorial drawer with two display modes: <strong>Slide-out Sidebar (Off-Canvas)</strong> and <strong>Dropdown Tray (On-Canvas Overlay)</strong>. It features live topic post counts, secondary edition navigation, and dedicated widget slots.
                                        </p>
                                        <p style="color: #475569; margin: 0; line-height: 1.6;">
                                            <em>Configure via <strong>Customizer &gt; Header &amp; Navigation &gt; Off-Canvas &amp; Drawer Settings (Pro)</strong>.</em>
                                        </p>
                                    </div>

                                    <!-- Mega Menu -->
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                                        <h3 style="font-size: 1.25em; color: #0f172a; margin: 0 0 10px; display: flex; align-items: center; gap: 10px;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                                            Mega Menu <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span>
                                        </h3>
                                        <p style="color: #475569; margin: 0 0 20px; line-height: 1.6;">
                                            NewSum Pro comes with a built-in, CSS-only Mega Menu for your main navigation. It automatically converts standard dropdowns into a multi-column editorial layout.
                                        </p>

                                        <h4 style="font-size: 1em; color: #0f172a; margin: 0 0 10px;">How to use it</h4>
                                        <ul style="color: #475569; padding-left: 20px; margin: 0; line-height: 1.6;">
                                            <li>Go to <strong>Appearance &gt; Menus</strong>.</li>
                                            <li>At the top right of the screen, click <strong>Screen Options</strong> and ensure <strong>CSS Classes</strong> is checked.</li>
                                            <li>Click the down arrow on a <strong>top-level menu item</strong> that has sub-items.</li>
                                            <li>In the "CSS Classes (optional)" field, enter exactly: <code>newsum-mega-menu</code></li>
                                            <li>Save the menu. That item's dropdown will now render as a full-width Mega Menu!</li>
                                        </ul>
                                    </div>

                                    <!-- Strategic Ad Placement -->
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                                        <h3 style="font-size: 1.25em; color: #0f172a; margin: 0 0 10px; display: flex; align-items: center; gap: 10px;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                            Strategic Ad Placements <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span>
                                        </h3>
                                        <p style="color: #475569; margin: 0 0 20px; line-height: 1.6;">
                                            Monetize your publication with dedicated ad slots: Header leaderboard banner, post-hero breaking sponsor, and in-feed sponsored articles. Supports Google AdSense, scripts, and custom HTML/image banners.
                                        </p>
                                    </div>

                                    <!-- Auto TOC -->
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                                        <h3 style="font-size: 1.25em; color: #0f172a; margin: 0 0 10px; display: flex; align-items: center; gap: 10px;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                            Auto Table of Contents (TOC) <span style="font-size: 10px; background: #728c69; color: #fff; padding: 2px 6px; border-radius: 4px;">PRO</span>
                                        </h3>
                                        <p style="color: #475569; margin: 0 0 20px; line-height: 1.6;">
                                            The sticky Table of Contents is fully automatic! It automatically parses <code>H2</code> and <code>H3</code> heading tags in single articles and generates a smooth-scrolling outline.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php else : ?>

                        <!-- Welcome Tab -->
                        <div class="newsum-dashboard-grid">
                            
                            <!-- Left Column: Table & Upgrade -->
                            <div class="newsum-main-col">
                            
                            <!-- Why Upgrade Grid -->
                            <div class="newsum-upgrade-features">
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'Pro Layouts', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Unlock advanced header styles, hero sections, and custom typography.', 'newsum' ); ?></p>
                                </div>
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'WooCommerce Sync', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Deep integration, AJAX cart, quick view, and off-canvas filtering.', 'newsum' ); ?></p>
                                </div>
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'Priority Support', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Get VIP 1-on-1 assistance directly from the NewSum development team.', 'newsum' ); ?></p>
                                </div>
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'Advanced Ad Engine', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Build unlimited responsive ad banners, inject Adsense, and set device visibility rules.', 'newsum' ); ?></p>
                                </div>
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'Shop The Look', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Upload images and drop interactive, clickable affiliate hotspots directly on them.', 'newsum' ); ?></p>
                                </div>
                                <div class="newsum-feature-box">
                                    <div class="newsum-feature-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                    </div>
                                    <h4><?php esc_html_e( 'TOC & Interludes', 'newsum' ); ?></h4>
                                    <p><?php esc_html_e( 'Insert video break-out blocks and keep readers engaged with an auto Table of Contents.', 'newsum' ); ?></p>
                                </div>
                            </div>

                            <div class="newsum-table-wrap">
                                <h2 class="newsum-comparison-title"><?php esc_html_e( 'Free vs Pro Comparison', 'newsum' ); ?></h2>
                                <table class="newsum-comparison-table">
                                    <thead>
                                        <tr>
                                            <th class="feature-col"><?php esc_html_e( 'Features', 'newsum' ); ?></th>
                                            <th class="free-col"><?php esc_html_e( 'NewSum Lite', 'newsum' ); ?></th>
                                            <th class="pro-col"><?php esc_html_e( 'NewSum Pro', 'newsum' ); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Group: Theme Core & Layouts -->
                                        <tr>
                                            <td colspan="3" class="newsum-table-subheader"><?php esc_html_e( 'Theme Core & Layouts', 'newsum' ); ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Core Theme Design & Responsiveness', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-yes">&check;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( '1-Click Demo Import', 'newsum' ); ?></td>
                                            <td class="free-col"><?php esc_html_e( 'Basic', 'newsum' ); ?></td>
                                            <td class="pro-col"><strong><?php esc_html_e( 'Pro Demos', 'newsum' ); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Multiple Header & Footer Layouts', 'newsum' ); ?></td>
                                            <td class="free-col"><?php esc_html_e( 'Basic', 'newsum' ); ?></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Sticky Header & Transparent Menus', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>

                                        <!-- Group: Customization & E-Commerce -->
                                        <tr>
                                            <td colspan="3" class="newsum-table-subheader"><?php esc_html_e( 'Customization & WooCommerce', 'newsum' ); ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Advanced Color & Typography Controls', 'newsum' ); ?></td>
                                            <td class="free-col"><?php esc_html_e( 'Basic', 'newsum' ); ?></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'WooCommerce Deep Integration', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-yes">&check;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Off-Canvas Floating Cart', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Product Quick View & Advanced Filters', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>

                                        <!-- Group: Monetization & Engagement -->
                                        <tr>
                                            <td colspan="3" class="newsum-table-subheader"><?php esc_html_e( 'Monetization & Engagement', 'newsum' ); ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Advanced Ad Engine (7 Zones & Layouts)', 'newsum' ); ?></td>
                                            <td class="free-col"><?php esc_html_e( 'Basic', 'newsum' ); ?></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Shop The Look (Interactive Hotspots)', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Auto Table of Contents & Interlude Videos', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>

                                        <!-- Group: Support & Add-ons -->
                                        <tr>
                                            <td colspan="3" class="newsum-table-subheader"><?php esc_html_e( 'Support & Licensing', 'newsum' ); ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Remove Promo / Author Footer Links', 'newsum' ); ?></td>
                                            <td class="free-col"><span class="newsum-icon-no">&times;</span></td>
                                            <td class="pro-col"><span class="newsum-icon-yes">&check;</span></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'VIP Priority Support & Updates', 'newsum' ); ?></td>
                                            <td class="free-col"><?php esc_html_e( 'Forum Only', 'newsum' ); ?></td>
                                            <td class="pro-col"><strong><?php esc_html_e( '1-on-1 Support', 'newsum' ); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ( isset( $pro_plugin_installed ) && $pro_plugin_installed ) : ?>
                                <?php if ( get_option( 'NEWSUM_PRO_license_status' ) === 'valid' ) : ?>
                                <!-- Pro is Active - show a success banner instead of upgrade CTA -->
                                <div class="newsum-cta-wrap" style="background: linear-gradient(135deg, #f0f5ee 0%, #e8f0e5 100%); border-color: #b5ccaf;">
                                    <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:12px;">
                                        <span style="background:#728c69; color:#fff; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">✓</span>
                                        <h3 style="color:#2d4a26; margin:0;"><?php esc_html_e( 'NewSum Pro is Active!', 'newsum' ); ?></h3>
                                    </div>
                                    <p style="color:#4a6644;"><?php esc_html_e( 'You have full access to all Pro layouts, starter sites, and premium features. Thank you for your support!', 'newsum' ); ?></p>
                                    <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="newsum-btn newsum-btn-secondary"><?php esc_html_e( 'Open Customizer', 'newsum' ); ?></a>
                                    <a href="<?php echo esc_url( admin_url( 'themes.php?page=newsum-dashboard&tab=starter-sites' ) ); ?>" class="newsum-btn newsum-btn-primary"><?php esc_html_e( 'Import a Starter Site', 'newsum' ); ?></a>
                                </div>
                                <?php else : ?>
                                <!-- Pro is Active but License Invalid -->
                                <div class="newsum-cta-wrap" style="background: linear-gradient(135deg, #fdf4f4 0%, #f9ecec 100%); border-color: #dc3232;">
                                    <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:12px;">
                                        <span style="background:#dc3232; color:#fff; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; font-weight:bold;">!</span>
                                        <h3 style="color:#b32d2e; margin:0;"><?php esc_html_e( 'Action Required: License Invalid', 'newsum' ); ?></h3>
                                    </div>
                                    <p style="color:#b32d2e;"><?php esc_html_e( 'NewSum Pro is active, but your license key is missing or expired. Please activate your license to unlock Pro layouts and features.', 'newsum' ); ?></p>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=newsum-pro' ) ); ?>" class="newsum-btn newsum-btn-primary" style="background:#dc3232; border-color:#dc3232;"><?php esc_html_e( 'Activate License', 'newsum' ); ?></a>
                                </div>
                                <?php endif; ?>
                            <?php else : ?>
                            <div class="newsum-cta-wrap">
                                <h3><?php esc_html_e( 'Ready to unlock everything?', 'newsum' ); ?></h3>
                                <p><?php esc_html_e( 'Upgrade to NewSum Pro and get all advanced layouts, WooCommerce sync, and premium support.', 'newsum' ); ?></p>
                                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="newsum-btn newsum-btn-secondary"><?php esc_html_e( 'Go to Customizer', 'newsum' ); ?></a>
                                <a href="https://wpmatcha.com/wordpress-themes/newsum/" target="_blank" class="newsum-btn newsum-btn-primary"><?php esc_html_e( 'Upgrade to Pro Today', 'newsum' ); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Column: Sidebar Networking -->
                        <div class="newsum-sidebar-col">
                            
                            <!-- Rating -->
                            <div class="newsum-sidebar-card">
                                <h3><?php esc_html_e( 'Leave a Review', 'newsum' ); ?></h3>
                                <p><?php esc_html_e( 'Are you enjoying the NewSum theme? We would love it if you could take a minute to leave us a 5-star rating on WordPress.org to help us grow!', 'newsum' ); ?></p>
                                <a href="https://wordpress.org/support/theme/newsum/reviews/#new-post" target="_blank" class="newsum-btn newsum-btn-outline"><?php esc_html_e( 'Rate NewSum', 'newsum' ); ?></a>
                            </div>

                            <!-- Docs -->
                            <div class="newsum-sidebar-card">
                                <h3><?php esc_html_e( 'Documentation', 'newsum' ); ?></h3>
                                <p><?php esc_html_e( 'Need help setting up your homepage, importing demos, or changing colors? Check out our extensive theme documentation.', 'newsum' ); ?></p>
                                <a href="https://wpmatcha.com/wordpress-themes/newsum/" target="_blank" class="newsum-btn newsum-btn-outline"><?php esc_html_e( 'Read Documentation', 'newsum' ); ?></a>
                            </div>

                            <!-- Support -->
                            <div class="newsum-sidebar-card">
                                <h3><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#728c69" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> <?php esc_html_e( 'Need Priority Support?', 'newsum' ); ?></h3>
                                <p><?php esc_html_e( 'Stuck on something? NewSum Pro users receive instant, 1-on-1 priority support direct from our core development team.', 'newsum' ); ?></p>
                                <a href="https://wpmatcha.com/contact/" target="_blank" class="newsum-btn newsum-btn-outline"><?php esc_html_e( 'Get Pro Support', 'newsum' ); ?></a>
                            </div>
                        </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    }
endif;
