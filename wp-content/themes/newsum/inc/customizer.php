<?php
/**
 * Customizer Settings & Functions
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: returns true when Matcha Pro is active AND the license key is valid.
 * Falls back to plugin-active-only check for dev environments.
 */
if ( ! function_exists( 'newsum_pro_is_active' ) ) {
    function newsum_pro_is_active() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        if ( ! newsum_is_pro_active() ) {
            return false;
        }
        // Also check license validity if the LicenseManager class exists.
        $license_status = get_option( 'matcha_pro_license_status', '' );
        if ( $license_status && 'valid' !== $license_status ) {
            return false;
        }
        return true;
    }
}

/**
 * Custom Palette Swatch Control for Customizer
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class NewSum_Palette_Control extends WP_Customize_Control {
        public $type = 'newsum_palette';
        public $palettes = array();

        public function render_content() {
            if ( empty( $this->palettes ) ) {
                return;
            }
            ?>
            <div class="newsum-control-wrapper newsum-palette-control-wrapper">
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
                
                <div class="newsum-palette-swatches">
                <?php foreach ( $this->palettes as $key => $data ) : ?>
                    <label class="newsum-palette-option" title="<?php echo esc_attr( $data['label'] ); ?>">
                        <input type="radio" name="<?php echo esc_attr( '_customize-radio-' . $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), $key ); ?> />
                        <span class="newsum-palette-color" style="background:<?php echo esc_attr( $data['color'] ); ?>;"></span>
                    </label>
                <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( class_exists( 'WP_Customize_Control' ) ) {
    class NewSum_Heading_Control extends WP_Customize_Control {
        public $type = 'newsum_heading';
        public function render_content() {
            ?>
            <div class="newsum-heading-banner">
                <span class="newsum-heading-title"><?php echo esc_html( $this->label ); ?></span>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="newsum-heading-desc"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}

if ( class_exists( 'WP_Customize_Section' ) ) {
    class NewSum_Buttonset_Control extends WP_Customize_Control {
        public $type = 'newsum-buttonset';

        public function render_content() {
            if ( empty( $this->choices ) ) return;
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
            <div class="newsum-buttonset">
                <?php foreach ( $this->choices as $value => $label ) : ?>
                    <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ) . '-' . esc_attr( $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                    <label for="<?php echo esc_attr( $name ) . '-' . esc_attr( $value ); ?>"><?php echo wp_kses_post( $label ); ?></label>
                <?php endforeach; ?>
            </div>
            <style>
                .newsum-buttonset { display: flex; background: #f0f0f1; border-radius: 6px; padding: 3px; margin-top: 8px; border: 1px solid #dcdcde; align-items: center; justify-content: space-between; }
                .newsum-buttonset input[type="radio"] { display: none; }
                .newsum-buttonset label { flex: 1; text-align: center; padding: 6px 4px; margin: 0; cursor: pointer; font-size: 12px; color: #50575e; transition: all 0.2s ease; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 4px; font-weight: 500; }
                .newsum-buttonset label:hover { color: #1d2327; }
                .newsum-buttonset input[type="radio"]:checked + label { background: #fff; color: #2271b1; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
                .newsum-buttonset svg { width: 14px; height: 14px; fill: currentColor; opacity: 0.8; }
            </style>
            <?php
        }
    }

    class NewSum_Customize_Upsell_Section extends WP_Customize_Section {
        public $type = 'newsum_upsell_section';
        public $upsell_text = '';
        public $upsell_url = '';

        public function json() {
            $json = parent::json();
            $json['upsell_text'] = $this->upsell_text;
            $json['upsell_url']  = esc_url( $this->upsell_url );
            return $json;
        }

        protected function render_template() {
            ?>
            <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand" style="padding: 20px 20px 0; border-top: none; background: transparent; margin-bottom: 20px;">
                <div class="newsum-customizer-upsell" style="padding: 16px; background: #fff; border-left: 4px solid var(--sage, #728c69); box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 3px;">
                    <span style="display:block; font-size:13px; color:#3c434a; line-height: 1.5;">
                        {{ data.title }}
                        <# if ( data.upsell_text && data.upsell_url ) { #>
                            <a href="{{ data.upsell_url }}" target="_blank" style="display:block; margin-top:8px; text-decoration:none; color:var(--sage, #728c69); font-weight:600;">
                                {{ data.upsell_text }} &rarr;
                            </a>
                        <# } #>
                    </span>
                </div>
            </li>
            <?php
        }
    }
}

if ( class_exists( 'WP_Customize_Control' ) ) {
    class NewSum_Customize_Upsell_Control extends WP_Customize_Control {
        public $type = 'newsum-upsell';
        public $upsell_text = '';
        public $upsell_url = '';

        public function render_content() {
            ?>
            <div class="newsum-customizer-upsell" style="margin-top: 24px; padding: 16px; background: #fff; border-left: 4px solid var(--sage, #728c69); box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 3px;">
                <span style="display:block; font-size:13px; color:#3c434a; line-height: 1.5;">
                    <?php echo esc_html( $this->label ); ?>
                    <?php if ( ! empty( $this->description ) ) : ?>
                        <br><span class="description" style="margin-top:4px; display:block; font-style:italic; font-size:12px; color:#646970;"><?php echo wp_kses_post( $this->description ); ?></span>
                    <?php endif; ?>
                    <?php if ( $this->upsell_text && $this->upsell_url ) : ?>
                        <a href="<?php echo esc_url( $this->upsell_url ); ?>" target="_blank" style="display:block; margin-top:8px; text-decoration:none; color:var(--sage, #728c69); font-weight:600;">
                            <?php echo esc_html( $this->upsell_text ); ?> &rarr;
                        </a>
                    <?php endif; ?>
                </span>
            </div>
            <?php
        }
    }

    class NewSum_Message_Control extends WP_Customize_Control {
        public $type = 'newsum_message';
        public $message_type = 'warning';
        public function render_content() {
            $is_info = ( 'info' === $this->message_type );
            $bg      = $is_info ? '#f0f6fc' : '#fff8e5';
            $border  = $is_info ? '#2271b1' : '#f0b849';
            $color   = $is_info ? '#135e96' : '#d63638';
            $icon    = $is_info ? 'dashicons-info' : 'dashicons-warning';
            ?>
            <div class="newsum-customizer-message" style="padding:12px;background:<?php echo esc_attr( $bg ); ?>;border-left:4px solid <?php echo esc_attr( $border ); ?>;border-radius:3px;margin-bottom:16px;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <span class="customize-control-title" style="margin-bottom:6px;color:<?php echo esc_attr( $color ); ?>;font-weight:600;display:flex;align-items:center;gap:6px;">
                    <span class="dashicons <?php echo esc_attr( $icon ); ?>" style="font-size:16px;width:16px;height:16px;"></span>
                    <?php echo esc_html( $this->label ); ?>
                </span>
                <p style="margin:0;font-size:12px;line-height:1.4;color:#50575e;"><?php echo wp_kses_post( $this->description ); ?></p>
            </div>
            <?php
        }
    }
}

/**
 * Customizer Active Callbacks
 */
function newsum_is_ad_header_enabled( $control ) { $s = $control->manager->get_setting( 'newsum_ad_header_enable' ); return $s ? $s->value() : false; }

function newsum_is_ad_before_enabled( $control ) { $s = $control->manager->get_setting( 'newsum_ad_before_enable' ); return $s ? $s->value() : false; }
function newsum_is_ad_after_enabled( $control ) { $s = $control->manager->get_setting( 'newsum_ad_after_enable' ); return $s ? $s->value() : false; }

/**
 * Customizer Settings
 */
function newsum_customize_register( $wp_customize ) {

    // --- Active Callbacks ---
    if ( ! function_exists( 'newsum_is_hero_standard' ) ) {
        function newsum_is_hero_standard( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' ); return $s ? $s->value() === 'standard' : false;
        }
    }
    if ( ! function_exists( 'newsum_is_hero_split' ) ) {
        function newsum_is_hero_split( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' ); return $s ? $s->value() === 'split' : false;
        }
    }
    if ( ! function_exists( 'newsum_is_hero_custom_source' ) ) {
        function newsum_is_hero_custom_source( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_source' ); return $s ? $s->value() === 'custom' : false;
        }
    }
    if ( ! function_exists( 'newsum_is_editor_picks_toggle' ) ) {
        function newsum_is_editor_picks_toggle( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' ); return $s ? $s->value() === 'standard' : false;
        }
    }
    if ( ! function_exists( 'newsum_is_editor_picks_custom_cat' ) ) {
        function newsum_is_editor_picks_custom_cat( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            if ( ! $s ) return false;
            $layout = $s->value();
            if ( $layout !== 'standard' && $layout !== 'grid' ) return false;
            $show = $control->manager->get_setting( 'newsum_editor_picks_show' );
            if ( $layout === 'standard' && ( ! $show || ! $show->value() ) ) return false;
            $src = $control->manager->get_setting( 'newsum_editor_picks_source' );
            return $src ? $src->value() === 'category' : false;
        }
    }
    if ( ! function_exists( 'newsum_is_editor_picks_shown' ) ) {
        function newsum_is_editor_picks_shown( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            if ( ! $s ) return false;
            $layout = $s->value();
            if ( $layout === 'grid' ) return true;
            if ( $layout !== 'standard' ) return false;
            $show = $control->manager->get_setting( 'newsum_editor_picks_show' );
            return $show ? $show->value() == true : false;
        }
    }
    if ( ! function_exists( 'newsum_is_editor_picks_title' ) ) {
        function newsum_is_editor_picks_title( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            if ( ! $s ) return false;
            $layout = $s->value();
            if ( $layout !== 'standard' ) return false;
            $show = $control->manager->get_setting( 'newsum_editor_picks_show' );
            return $show ? $show->value() == true : false;
        }
    }
    // Require custom controls
    require_once get_theme_file_path( '/inc/customizer/controls/ToggleControl.php' );
    require_once get_theme_file_path( '/inc/customizer/controls/RadioImageControl.php' );

    // ── Register Panels ───────────────────────────────────────
    $wp_customize->add_panel( 'newsum_global_panel', array(
        'title'       => __( 'Global Settings', 'newsum' ),
        'priority'    => 20,
    ) );
    $wp_customize->add_panel( 'newsum_header_footer_panel', array(
        'title'       => __( 'Header & Marketing', 'newsum' ),
        'priority'    => 21,
    ) );
    $wp_customize->add_panel( 'newsum_homepage_panel', array(
        'title'       => __( 'Homepage Settings', 'newsum' ),
        'description' => __( '✨ <strong>Pro Feature:</strong> Want to drag-and-drop to reorder these sections? <a href="https://wpmatcha.com" target="_blank">Unlock the Homepage Layout Manager in NewSum Pro</a>.', 'newsum' ),
        'priority'    => 22,
    ) );
    $wp_customize->add_panel( 'newsum_templates_panel', array(
        'title'       => __( 'Blog & Page Templates', 'newsum' ),
        'priority'    => 23,
    ) );
    // Move Native Sections
    $wp_customize->get_section( 'colors' )->panel = 'newsum_global_panel';

    // ══════════════════════════════════════════════════════════════
    // ✨ QUICK START PRESETS — visible to ALL users (free & pro)
    //    Free presets: Standard, Classic Newspaper — fully clickable
    //    Pro presets:  Magazine, News, Bento — show locked state
    // ══════════════════════════════════════════════════════════════
    if ( ! newsum_is_pro_active() ) {
        // Only register here when newsum-pro is NOT active.
        
        // Homepage Layout Manager Upsell at bottom of Panel
        $wp_customize->register_section_type( 'NewSum_Customize_Upsell_Section' );
        $wp_customize->add_section( new NewSum_Customize_Upsell_Section( $wp_customize, 'newsum_layout_upsell_section', array(
            'title'       => __( 'Want to reorder these homepage sections with drag-and-drop?', 'newsum' ),
            'panel'       => 'newsum_homepage_panel',
            'priority'    => 999,
            'upsell_text' => __( 'Unlock Drag & Drop Homepage Sections in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com',
        ) ) );
        
        // Dummy control to ensure the section is not hidden by WP Customizer JS
        $wp_customize->add_setting( 'newsum_layout_upsell_dummy', array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'newsum_layout_upsell_dummy', array(
            'section' => 'newsum_layout_upsell_section',
            'type'    => 'hidden',
        ) );

        // White-Label Branding Upsell at bottom of Panel
        $wp_customize->add_section( new NewSum_Customize_Upsell_Section( $wp_customize, 'newsum_whitelabel_upsell_section', array(
            'title'       => __( 'Want to rebrand this theme?', 'newsum' ),
            'panel'       => 'newsum_global_panel',
            'priority'    => 999,
            'upsell_text' => __( 'Unlock White-Label Branding in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
        ) ) );
        $wp_customize->add_setting( 'newsum_whitelabel_upsell_dummy', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( 'newsum_whitelabel_upsell_dummy', array(
            'section' => 'newsum_whitelabel_upsell_section',
            'type'    => 'hidden',
        ) );

    }

    // ══════════════════════════════════════════════════════════════
    // ✨ QUICK START PRESETS — visible to ALL users (free & pro)
    //    Free presets: Standard, Classic Newspaper — fully clickable
    //    Pro presets:  Hover-Reveal Deck, Live Terminal Wall — unlocked in Pro
    // ══════════════════════════════════════════════════════════════
    $is_pro = newsum_is_pro_active();

    $wp_customize->add_section( 'newsum_global_presets_section', array(
        'title'    => __( '✨ Quick Start Presets', 'newsum' ),
        'priority' => 1,
    ) );

    $wp_customize->add_setting( 'newsum_global_preset', array(
        'default'           => 'standard',
        'sanitize_callback' => 'sanitize_key',
    ) );

    if ( class_exists( 'NewSum_Radio_Image_Control' ) ) {
        $preset_choices = array(
            'standard'  => array(
                'name' => __( 'Standard (Bento + Wire)', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
            ),
            'newspaper' => array(
                'name' => __( 'Classic Newspaper (Tribune)', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 8h18M8 21V8M16 21V8"/></svg>',
            ),
            'magazine'  => array(
                'name' => $is_pro ? __( 'Hover-Reveal Deck (Pro)', 'newsum' ) : __( 'Hover-Reveal Deck 🔒', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 3v18"/></svg>',
            ),
            'terminal'  => array(
                'name' => $is_pro ? __( 'Live Terminal Wall (Pro)', 'newsum' ) : __( 'Live Terminal Wall 🔒', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="8" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/><rect x="13" y="13" width="8" height="8" rx="1"/></svg>',
            ),
        );

        $wp_customize->add_control( new NewSum_Radio_Image_Control( $wp_customize, 'newsum_global_preset', array(
            'label'       => __( 'Select a Theme Style', 'newsum' ),
            'description' => __( 'Clicking a preset will instantly configure your Hero, Feed, Sidebar, Typography, and Mode.', 'newsum' ),
            'section'     => 'newsum_global_presets_section',
            'choices'     => $preset_choices,
            'priority'    => 5,
        ) ) );
    }

    // Inline CSS + JS for presets
    add_action( 'customize_controls_print_footer_scripts', function() use ( $is_pro ) {
        ?>
        <style>
        <?php if ( ! $is_pro ) : ?>
        /* ── Locked Pro preset cards in Free ── */
        #customize-control-newsum_global_preset .newsum-radio-image-option[data-value="magazine"],
        #customize-control-newsum_global_preset .newsum-radio-image-option[data-value="terminal"] {
            opacity: 0.45;
            cursor: not-allowed;
            user-select: none;
        }
        /* ── Static Pro upgrade notice ── */
        #newsum-preset-pro-notice {
            margin-top: 12px;
            padding: 10px 12px;
            border-left: 3px solid #c78c3e;
            background: #fdfaf5;
            font-size: 12px;
            line-height: 1.6;
            border-radius: 0 4px 4px 0;
        }
        #newsum-preset-pro-notice strong {
            display: block;
            color: #3c3c3c;
            margin-bottom: 3px;
        }
        #newsum-preset-pro-notice a {
            color: #c78c3e;
            text-decoration: none;
            font-weight: 600;
        }
        #newsum-preset-pro-notice a:hover { text-decoration: underline; }
        <?php endif; ?>
        </style>
        <script>
        (function($) {
            var isPro = <?php echo $is_pro ? 'true' : 'false'; ?>;
            var proPresets = ['magazine', 'terminal'];

            var presetMap = {
                'standard': {
                    'newsum_hero_layout'         : 'bento',
                    'newsum_color_palette'       : 'crimson',
                    'newsum_default_mode'        : 'dark',
                    'newsum_heading_font'        : 'Playfair Display',
                    'newsum_body_font'           : 'Inter',
                    'newsum_global_image_radius' : 0,
                    'newsum_layout_density'      : 'standard',
                    'newsum_content_width'       : 1800
                },
                'newspaper': {
                    'newsum_hero_layout'         : 'newspaper',
                    'newsum_color_palette'       : 'cobalt',
                    'newsum_default_mode'        : 'light',
                    'newsum_heading_font'        : 'Georgia',
                    'newsum_body_font'           : 'Inter',
                    'newsum_global_image_radius' : 0,
                    'newsum_layout_density'      : 'compact',
                    'newsum_content_width'       : 1440
                },
                'magazine': {
                    'newsum_hero_layout'         : 'hover_reveal',
                    'newsum_color_palette'       : 'amber',
                    'newsum_default_mode'        : 'dark',
                    'newsum_heading_font'        : 'Playfair Display',
                    'newsum_body_font'           : 'Inter',
                    'newsum_global_image_radius' : 4,
                    'newsum_layout_density'      : 'relaxed',
                    'newsum_content_width'       : 1600
                },
                'terminal': {
                    'newsum_hero_layout'         : 'live_wall',
                    'newsum_color_palette'       : 'violet',
                    'newsum_default_mode'        : 'dark',
                    'newsum_heading_font'        : 'Outfit',
                    'newsum_body_font'           : 'Inter',
                    'newsum_global_image_radius' : 0,
                    'newsum_layout_density'      : 'compact',
                    'newsum_content_width'       : 1800
                }
            };

            wp.customize.bind('ready', function() {

                <?php if ( ! $is_pro ) : ?>
                // Inject static upgrade notice below the preset control in Free
                var control = document.getElementById('customize-control-newsum_global_preset');
                if (control) {
                    var notice = document.createElement('div');
                    notice.id  = 'newsum-preset-pro-notice';
                    notice.innerHTML =
                        '<strong><?php echo esc_js( __( '🔒 Some layouts require NewSum Pro', 'newsum' ) ); ?></strong>' +
                        '<?php echo esc_js( __( 'Upgrade to unlock all Pro layouts with one-click import.', 'newsum' ) ); ?> ' +
                        '<a href="https://wpmatcha.com/wordpress-themes/newsum/" target="_blank" rel="noopener">' +
                        '<?php echo esc_js( __( 'Upgrade to Pro →', 'newsum' ) ); ?>' +
                        '</a>';
                    control.appendChild(notice);
                }
                <?php endif; ?>

                var setting = wp.customize('newsum_global_preset');
                if (!setting) return;

                setting.bind(function(newVal) {
                    if (!isPro && proPresets.indexOf(newVal) !== -1) {
                        // Silently revert locked preset in Free version
                        var prev = setting.get();
                        setting.set( (presetMap[prev] ? prev : 'standard') );
                        return;
                    }

                    if (!presetMap[newVal]) return;

                    // Apply settings to Customizer state and live preview
                    $.each(presetMap[newVal], function(settingId, settingValue) {
                        var s = wp.customize(settingId);
                        if (s) {
                            s.set(settingValue);
                        }
                        var ctrl = wp.customize.control(settingId);
                        if (ctrl && ctrl.container) {
                            ctrl.container.find('input[type="radio"], input[type="checkbox"]').each(function() {
                                if ($(this).val() == settingValue) {
                                    $(this).prop('checked', true).trigger('change');
                                }
                            });
                            ctrl.container.find('select, input[type="text"], input[type="range"], input[type="number"]').val(settingValue).trigger('change');
                        }
                    });
                });

            });
        }(jQuery));
        </script>
        <?php
    } );


    // ── Static Homepage Warning Section ────────────
    $wp_customize->add_section( 'newsum_static_warning_section', array(
        'title'           => __( '⚠️ Homepage Notice', 'newsum' ),
        'panel'           => 'newsum_homepage_panel',
        'priority'        => 0, // Put it at the very top
        'active_callback' => function() { return get_option( 'show_on_front' ) === 'page'; }
    ) );

    $wp_customize->add_setting( 'newsum_homepage_static_warning', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Message_Control( $wp_customize, 'newsum_homepage_static_warning', array(
        'label'           => __( 'Static Page Detected', 'newsum' ),
        'description'     => __( 'Your site is set to display a <strong>Static Page</strong> on the front page.<br><br>The sections below (Hero, Trending, etc.) are designed for the default Blog layout (Your latest posts) and will not appear on a custom static page.<br><br><strong>Want to build a custom homepage?</strong><br>Exit the Customizer and edit your homepage in the Block Editor. Click the <strong>"+"</strong> icon, go to the <strong>Patterns</strong> tab, and select the <strong>NewSum Pro</strong> category to drop in our pre-designed homepage layouts!', 'newsum' ),
        'section'         => 'newsum_static_warning_section',
        'priority'        => 0,
    ) ) );

    // ── Story Highlights Section (Pro) ───────────────────────
    $stories_section_title = newsum_is_pro_active() ? __( 'Story Highlights Reel', 'newsum' ) : __( 'Story Highlights Reel (Pro) 🔒', 'newsum' );
    $wp_customize->add_section( 'newsum_stories_section', array(
        'title'    => $stories_section_title,
        'panel'    => 'newsum_homepage_panel',
        'priority' => 25,
    ) );

    if ( ! newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_stories_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_stories_upsell', array(
            'label'       => __( 'Story Highlights Reel is a Pro Feature', 'newsum' ),
            'description' => __( 'Display circular avatar story briefings with live pulse indicators and infinite carousel scrolling.', 'newsum' ),
            'section'     => 'newsum_stories_section',
            'upsell_text' => __( 'Unlock Story Highlights in Pro &rarr;', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 1,
        ) ) );
    }

    $wp_customize->add_setting( 'newsum_stories_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_stories_show', array(
        'label'   => __( 'Show Story Highlights Reel', 'newsum' ),
        'section' => 'newsum_stories_section',
        'priority' => 2,
    ) ) );

    $wp_customize->add_setting( 'newsum_stories_title', array(
        'default'           => __( 'DAILY BRIEFINGS', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_stories_title', array(
        'label'   => __( 'Section Title', 'newsum' ),
        'section' => 'newsum_stories_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_stories_source', array(
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_stories_source', array(
        'label'   => __( 'Stories Source', 'newsum' ),
        'section' => 'newsum_stories_section',
        'type'    => 'select',
        'choices' => array(
            'latest'   => __( 'Latest Posts', 'newsum' ),
            'sticky'   => __( 'Sticky / Featured Posts', 'newsum' ),
            'category' => __( 'Specific Category', 'newsum' ),
        ),
    ) );

    $wp_customize->add_setting( 'newsum_stories_category', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_stories_category', array(
        'label'           => __( 'Select Category', 'newsum' ),
        'section'         => 'newsum_stories_section',
        'type'            => 'dropdown-categories',
    ) );

    $wp_customize->add_setting( 'newsum_stories_count', array(
        'default'           => 8,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_stories_count', array(
        'label'       => __( 'Number of Stories', 'newsum' ),
        'section'     => 'newsum_stories_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 50,
            'step' => 1,
        ),
    ) );

    // Show Carousel Navigation Arrows Toggle
    $wp_customize->add_setting( 'newsum_stories_show_nav', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_stories_show_nav', array(
        'label'       => __( 'Show Carousel Navigation Arrows', 'newsum' ),
        'description' => __( 'Display the left & right scroll arrow buttons in the section header.', 'newsum' ),
        'section'     => 'newsum_stories_section',
    ) ) );

    // Show Modal Next/Previous Navigation Buttons Toggle
    $wp_customize->add_setting( 'newsum_stories_show_modal_nav', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_stories_show_modal_nav', array(
        'label'       => __( 'Show Modal Next / Previous Arrows', 'newsum' ),
        'description' => __( 'Display the floating next and previous arrow buttons inside the story quick-read lightbox.', 'newsum' ),
        'section'     => 'newsum_stories_section',
    ) ) );

    $wp_customize->add_section( 'newsum_hero_section', array(
        'title'    => __( 'Hero Section', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'newsum_hero_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_hero_show', array(
        'label'   => __( 'Show Hero Section on Homepage', 'newsum' ),
        'section' => 'newsum_hero_section',
        'priority' => 1,
    ) ) );

    $wp_customize->add_setting( 'newsum_hero_hide_mobile', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_hero_hide_mobile', array(
        'label'       => __( 'Hide Hero Section on Mobile', 'newsum' ),
        'description' => __( 'Switch off the Hero Section on smartphones and small screens (<= 768px).', 'newsum' ),
        'section'     => 'newsum_hero_section',
        'priority'    => 2,
    ) ) );

    // Hero Layout Switcher
    $wp_customize->add_setting( 'newsum_hero_layout', array(
        'default'           => 'bento',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'refresh',
    ) );
    $hero_layout_choices = array(
        'bento'     => __( '3-Column Bento Slider', 'newsum' ),
        'newspaper' => __( 'Classic Newspaper (Tribune)', 'newsum' ),
    );
    if ( function_exists( 'newsum_is_pro_active' ) && newsum_is_pro_active() ) {
        $hero_layout_choices['hover_reveal'] = __( 'Hover-Reveal Typography Matrix (Pro)', 'newsum' );
        $hero_layout_choices['live_wall']    = __( 'Live Dynamic Rotation Wall (Pro)', 'newsum' );
    }

    $wp_customize->add_control( 'newsum_hero_layout', array(
        'label'       => __( 'Hero Layout Style', 'newsum' ),
        'section'     => 'newsum_hero_section',
        'type'        => 'radio',
        'choices'     => $hero_layout_choices,
        'priority'    => 2,
    ) );

    // Hero Source Heading
    $wp_customize->add_setting( 'newsum_hero_general_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_hero_general_heading', array(
        'label'   => __( 'General Settings', 'newsum' ),
        'section' => 'newsum_hero_section',
    ) ) );

    // Hero Source
    $wp_customize->add_setting( 'newsum_hero_source', array(
        'default'           => 'latest',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_hero_source', array(
        'label'       => __( 'Hero Content Source', 'newsum' ),
        'description' => __( 'The Hero Live Wall pulls 7 posts dynamically.', 'newsum' ),
        'section'     => 'newsum_hero_section',
        'type'        => 'select',
        'choices'     => array(
            'latest'   => __( 'Latest Blog Posts', 'newsum' ),
            'category' => __( 'Specific Category', 'newsum' ),
            'tag'      => __( 'Specific Tag', 'newsum' ),
        ),
    ) );

    // Hero Category
    $wp_customize->add_setting( 'newsum_hero_category', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_hero_category', array(
        'label'           => __( 'Hero Category', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'type'            => 'dropdown-categories',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_source' );
            if ( ! $s ) return false;
            return $s->value() === 'category';
        },
    ) );

    // Hero Tag
    $wp_customize->add_setting( 'newsum_hero_tag', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_hero_tag', array(
        'label'           => __( 'Hero Tag Slug', 'newsum' ),
        'description'     => __( 'Enter the slug of the tag (e.g., "featured").', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_source' );
            if ( ! $s ) return false;
            return $s->value() === 'tag';
        },
    ) );

    // Slider Auto-play
    $wp_customize->add_setting( 'newsum_hero_slider_autoplay', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_hero_slider_autoplay', array(
        'label'           => __( 'Enable Slider Auto-Play', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return ! $s || $s->value() === 'bento';
        },
    ) ) );

    // Side Cards Post Rotation Toggle
    $wp_customize->add_setting( 'newsum_hero_side_cards_rotate', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_hero_side_cards_rotate', array(
        'label'           => __( 'Rotate Side Cards Posts', 'newsum' ),
        'description'     => __( 'Turn off to keep the small tiles around the slider static.', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return ! $s || $s->value() === 'bento';
        },
    ) ) );

    // Grid Card Animations (Pro Only)
    if ( newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_hero_animation', array(
            'default'           => 'none',
            'sanitize_callback' => 'sanitize_key',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( 'newsum_hero_animation', array(
            'label'           => __( 'Card Content Rotation Effect', 'newsum' ),
            'section'         => 'newsum_hero_section',
            'type'            => 'select',
            'choices'         => array(
                'none'         => __( 'None (Static)', 'newsum' ),
                'random'       => __( 'Random Effects', 'newsum' ),
                'update-fade'  => __( 'Fade Transition', 'newsum' ),
                'update-slide' => __( 'Slide Transition', 'newsum' ),
                'update-flash' => __( 'Flash Transition', 'newsum' ),
            ),
            'active_callback' => function( $control ) {
                $s = $control->manager->get_setting( 'newsum_hero_layout' );
                $r = $control->manager->get_setting( 'newsum_hero_side_cards_rotate' );
                return ( ! $s || $s->value() === 'bento' ) && ( $r && $r->value() );
            },
        ) );
    } else {
        $wp_customize->add_setting( 'newsum_hero_animation_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_hero_animation_upsell', array(
            'label'           => __( 'Advanced Card Effects?', 'newsum' ),
            'section'         => 'newsum_hero_section',
            'upsell_text'     => __( 'Unlock slide, flash, and 3D flip card transition effects in NewSum Pro.', 'newsum' ),
            'upsell_url'      => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'active_callback' => function( $control ) {
                $s = $control->manager->get_setting( 'newsum_hero_layout' );
                return ! $s || $s->value() === 'bento';
            },
        ) ) );
    }

    // ── Newspaper (Tribune) Layout Text Settings ──
    $wp_customize->add_setting( 'newsum_newspaper_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_newspaper_heading', array(
        'label'           => __( 'Newspaper (Tribune) Text Settings', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return $s && $s->value() === 'newspaper';
        },
    ) ) );

    // Left Column Kicker (THE DISPATCH)
    $wp_customize->add_setting( 'newsum_newspaper_left_kicker', array(
        'default'           => __( 'THE DISPATCH', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_newspaper_left_kicker', array(
        'label'           => __( 'Left Column Heading', 'newsum' ),
        'description'     => __( 'Heading label above left story stack (leave blank to hide)', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return $s && $s->value() === 'newspaper';
        },
    ) );

    // Left Column Show Date Toggle
    $wp_customize->add_setting( 'newsum_newspaper_left_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_newspaper_left_show_date', array(
        'label'           => __( 'Show Date in Left Column Header', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return $s && $s->value() === 'newspaper';
        },
    ) ) );

    // Right Column Kicker (PERSPECTIVES)
    $wp_customize->add_setting( 'newsum_newspaper_right_kicker', array(
        'default'           => __( 'PERSPECTIVES', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_newspaper_right_kicker', array(
        'label'           => __( 'Right Column Heading (Perspectives)', 'newsum' ),
        'description'     => __( 'Main heading label above right column (leave blank to hide)', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return $s && $s->value() === 'newspaper';
        },
    ) );

    // Right Column Show Badge Toggle
    $wp_customize->add_setting( 'newsum_newspaper_right_show_badge', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_newspaper_right_show_badge', array(
        'label'           => __( 'Show Sub-Heading / Badge in Right Header', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            return $s && $s->value() === 'newspaper';
        },
    ) ) );

    // Right Column Badge (Top Stories)
    $wp_customize->add_setting( 'newsum_newspaper_right_badge', array(
        'default'           => __( 'Top Stories', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_newspaper_right_badge', array(
        'label'           => __( 'Right Column Sub-Heading (Top Stories)', 'newsum' ),
        'description'     => __( 'Badge label next to right column heading', 'newsum' ),
        'section'         => 'newsum_hero_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_hero_layout' );
            $b = $control->manager->get_setting( 'newsum_newspaper_right_show_badge' );
            return $s && $s->value() === 'newspaper' && ( ! $b || $b->value() );
        },
    ) );



    // ── Latest Stories Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_feed_section', array(
        'title'    => __( 'Latest Stories (Feed)', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 38,
    ) );

    $wp_customize->add_setting( 'newsum_feed_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_feed_show', array(
        'label'   => __( 'Show Latest Stories Section', 'newsum' ),
        'section' => 'newsum_feed_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_latest_stories_title', array(
        'default'           => 'Latest Stories',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_latest_stories_title', array(
        'label'           => __( 'Section Title', 'newsum' ),
        'section'         => 'newsum_feed_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            return $control->manager->get_setting( 'newsum_feed_show' )->value() == true;
        },
    ) );

    // ── Home Categories Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_home_categories_section', array(
        'title'    => __( 'Home Categories', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'newsum_home_categories_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_home_categories_show', array(
        'label'   => __( 'Show Category Cards', 'newsum' ),
        'section' => 'newsum_home_categories_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_home_categories_title', array(
        'default'           => __( 'Trending Topics', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_home_categories_title', array(
        'label'   => __( 'Section Title', 'newsum' ),
        'section' => 'newsum_home_categories_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_home_categories_count', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_home_categories_count', array(
        'label'       => __( 'Number of Categories', 'newsum' ),
        'section'     => 'newsum_home_categories_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 2, 'max' => 12 ),
    ) );

    // ── Interlude Section ──────────────────────────────────────
    $wp_customize->add_section( 'newsum_interlude_section', array(
        'title'    => __( 'Interlude Quote', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 35,
    ) );

    $wp_customize->add_setting( 'newsum_quote_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_quote_show', array(
        'label'   => __( 'Show Interlude Quote', 'newsum' ),
        'section' => 'newsum_interlude_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_quote_text', array(
        'default'           => __( 'The universe is full of magical things patiently waiting for our wits to grow sharper.', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_quote_text', array(
        'label'   => __( 'Quote Text', 'newsum' ),
        'section' => 'newsum_interlude_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_quote_author', array(
        'default'           => __( 'Eden Phillpotts', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_quote_author', array(
        'label'   => __( 'Quote Author', 'newsum' ),
        'section' => 'newsum_interlude_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_quote_bg_color', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'newsum_quote_bg_color', array(
        'label'   => __( 'Background Color', 'newsum' ),
        'section' => 'newsum_interlude_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_quote_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'newsum_quote_bg_image', array(
        'label'   => __( 'Background Image', 'newsum' ),
        'section' => 'newsum_interlude_section',
    ) ) );

    if ( ! newsum_pro_is_active() ) {
        $wp_customize->add_setting( 'newsum_quote_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_quote_upsell', array(
            'label'       => __( 'Want an eye-catching video background?', 'newsum' ),
            'section'     => 'newsum_interlude_section',
            'upsell_text' => __( 'Unlock YouTube & MP4 Video Backgrounds in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 10,
        ) ) );
    }

    // ── Featured Grid (2-up) Section ───────────────────────────────
    $wp_customize->add_section( 'newsum_featured_grid_section', array(
        'title'    => __( 'Featured Grid (2-Up)', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 33,
    ) );

    $wp_customize->add_setting( 'newsum_featured_grid_show', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_featured_grid_show', array( 'label' => __( 'Show Section', 'newsum' ), 'section' => 'newsum_featured_grid_section' ) ) );

    $wp_customize->add_setting( 'newsum_featured_grid_title', array( 'default' => __( 'Editor\'s Picks', 'newsum' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_featured_grid_title', array( 'label' => __( 'Section Title', 'newsum' ), 'section' => 'newsum_featured_grid_section', 'type' => 'text' ) );

    // ── More Stories (Masonry) Section ───────────────────────────────
    $wp_customize->add_section( 'newsum_masonry_section', array(
        'title'    => __( 'The Wire / Masonry Feed', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 34,
    ) );

    $wp_customize->add_setting( 'newsum_masonry_show', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_masonry_show', array( 'label' => __( 'Show Section', 'newsum' ), 'section' => 'newsum_masonry_section' ) ) );

    $wp_customize->add_setting( 'newsum_masonry_title', array( 'default' => __( 'The Wire', 'newsum' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_masonry_title', array( 'label' => __( 'Section Heading', 'newsum' ), 'section' => 'newsum_masonry_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_masonry_sidebar', array(
        'default'           => 'right',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( new NewSum_Radio_Image_Control( $wp_customize, 'newsum_masonry_sidebar', array(
        'label'   => __( 'Sidebar Layout', 'newsum' ),
        'section' => 'newsum_masonry_section',
        'choices' => array(
            'right' => array(
                'name' => __( 'Right Sidebar', 'newsum' ),
                'icon' => '<svg width="40" height="30" viewBox="0 0 40 30" fill="none"><rect width="26" height="30" fill="#3b82f6" rx="2"/><rect x="28" width="12" height="30" fill="#94a3b8" rx="2"/></svg>',
            ),
            'left'  => array(
                'name' => __( 'Left Sidebar', 'newsum' ),
                'icon' => '<svg width="40" height="30" viewBox="0 0 40 30" fill="none"><rect width="12" height="30" fill="#94a3b8" rx="2"/><rect x="14" width="26" height="30" fill="#3b82f6" rx="2"/></svg>',
            ),
            'none'  => array(
                'name' => __( 'No Sidebar (Full Width)', 'newsum' ),
                'icon' => '<svg width="40" height="30" viewBox="0 0 40 30" fill="none"><rect width="40" height="30" fill="#3b82f6" rx="2"/></svg>',
            ),
        ),
    ) ) );

    $wp_customize->add_setting( 'newsum_masonry_list_count', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_masonry_list_count', array(
        'label'       => __( 'Number of Side List Stories', 'newsum' ),
        'description' => __( 'Choose how many list stories appear alongside the big card (3 to 10).', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 10,
            'step' => 1,
        ),
    ) );

    // ── The Wire: Sidebar & Widgets Settings ───────────────────
    $categories_choices = array( '0' => __( 'All Topics / Latest Dispatches', 'newsum' ) );
    $all_cats = get_categories( array( 'hide_empty' => false ) );
    if ( ! empty( $all_cats ) && ! is_wp_error( $all_cats ) ) {
        foreach ( $all_cats as $cat_item ) {
            $categories_choices[ (string) $cat_item->term_id ] = $cat_item->name . ' (' . (int) $cat_item->count . ')';
        }
    }

    $wp_customize->add_setting( 'newsum_featured_grid_title', array(
        'default'           => __( "Editor's Choice", 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_featured_grid_title', array(
        'label'       => __( "Editor's Choice: Title", 'newsum' ),
        'description' => __( "Customize the title of the ranked stories widget on the homepage sidebar.", 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_editors_choice_cat', array(
        'default'           => '0',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_editors_choice_cat', array(
        'label'       => __( "Editor's Choice: Topic Category", 'newsum' ),
        'description' => __( "Filter stories displayed in the ranked Editor's Choice widget.", 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'select',
        'choices'     => $categories_choices,
    ) );

    $wp_customize->add_setting( 'newsum_editors_choice_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_editors_choice_count', array(
        'label'       => __( "Editor's Choice: Story Count", 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 1, 'max' => 6 ),
    ) );

    // Newsletter Box Controls
    $wp_customize->add_setting( 'newsum_show_newsletter_box', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_show_newsletter_box', array(
        'label'       => __( 'Show Newsletter Terminal Box', 'newsum' ),
        'description' => __( 'Toggle the sidebar newsletter subscription card on/off.', 'newsum' ),
        'section'     => 'newsum_masonry_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_newsletter_title', array(
        'default'           => __( 'NewSum Terminal', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_title', array(
        'label'       => __( 'Newsletter Box: Title', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_desc', array(
        'default'           => __( 'Get curated deep-tech analysis delivered directly to your inbox every Thursday.', 'newsum' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_desc', array(
        'label'       => __( 'Newsletter Box: Description', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_placeholder', array(
        'default'           => 'reader@example.com',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_placeholder', array(
        'label'       => __( 'Newsletter Box: Input Placeholder', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_btn_text', array(
        'default'           => __( 'Subscribe', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_btn_text', array(
        'label'       => __( 'Newsletter Box: Button Label', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_action', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_action', array(
        'label'       => __( 'Newsletter Form Action URL or Shortcode', 'newsum' ),
        'description' => __( 'Paste your Mailchimp / form action URL, or a plugin shortcode (e.g. [mc4wp_form]).', 'newsum' ),
        'section'     => 'newsum_masonry_section',
        'type'        => 'text',
    ) );

    // ── Marquee Strip Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_marquee_section', array(
        'title'    => __( 'Marquee Strip', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 36,
    ) );

    $wp_customize->add_setting( 'newsum_marquee_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_marquee_show', array(
        'label'   => __( 'Show Marquee Strip', 'newsum' ),
        'section' => 'newsum_marquee_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_marquee_source', array(
        'default'           => 'categories',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_marquee_source', array(
        'label'   => __( 'Marquee Content Source', 'newsum' ),
        'section' => 'newsum_marquee_section',
        'type'    => 'select',
        'choices' => array(
            'categories' => __( 'Top Categories', 'newsum' ),
            'custom'     => __( 'Custom Text', 'newsum' ),
        ),
    ) );

    $wp_customize->add_setting( 'newsum_marquee_text', array(
        'default'           => 'LIFESTYLE • TECHNOLOGY • BUSINESS • CULTURE • DESIGN • WELLNESS',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_marquee_text', array(
        'label'   => __( 'Custom Marquee Text', 'newsum' ),
        'section' => 'newsum_marquee_section',
        'type'    => 'text',
        'description' => __( 'Use • or | to separate items visually.', 'newsum' ),
    ) );

    $wp_customize->add_setting( 'newsum_marquee_count', array(
        'default'           => 10,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_marquee_count', array(
        'label'   => __( 'Number of Categories (If Source is Categories)', 'newsum' ),
        'section' => 'newsum_marquee_section',
        'type'    => 'number',
        'input_attrs' => array( 'min' => 1, 'max' => 30 ),
    ) );

    if ( ! newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_marquee_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_marquee_upsell', array(
            'label'       => __( 'Need More Marquee Options?', 'newsum' ),
            'section'     => 'newsum_marquee_section',
            'upsell_text' => __( 'Unlock Speed Controls, Categories & WooCommerce in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 99,
        ) ) );
    }

    // ── Multimedia & Podcasts Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_media_section', array(
        'title'    => __( 'Multimedia & Podcasts', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 38,
    ) );

    $wp_customize->add_setting( 'newsum_media_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_media_show', array(
        'label'   => __( 'Show Multimedia Section', 'newsum' ),
        'section' => 'newsum_media_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_media_title', array(
        'default'           => __( 'Multimedia & Podcasts', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_media_title', array(
        'label'   => __( 'Section Title', 'newsum' ),
        'section' => 'newsum_media_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_media_link_text', array(
        'default'           => __( 'All Audio/Video →', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_media_link_text', array(
        'label'   => __( 'View All Link Text', 'newsum' ),
        'section' => 'newsum_media_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_media_link_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'newsum_media_link_url', array(
        'label'   => __( 'View All Link URL', 'newsum' ),
        'section' => 'newsum_media_section',
        'type'    => 'url',
    ) );

    // ── Layout Options Section ───────────────────────────────
    $wp_customize->add_section( 'newsum_layout_section', array(
        'title'       => __( 'Site Layout', 'newsum' ),
        'panel'       => 'newsum_global_panel',
        'priority'    => 20,
    ) );

    // Container Layout Style
    $wp_customize->add_setting( 'newsum_container_style', array(
        'default'           => 'contained',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_container_style', array(
        'label'    => __( 'Container Layout Style', 'newsum' ),
        'section'  => 'newsum_layout_section',
        'type'     => 'select',
        'choices'  => array(
            'contained' => __( 'Contained (Custom Max-Width)', 'newsum' ),
            'fluid'     => __( 'Full-Width Fluid (100% Edge-to-Edge)', 'newsum' ),
        ),
        'priority' => 1,
    ) );

    $wp_customize->add_setting( 'newsum_content_width', array(
        'default'           => 1800,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_content_width', array(
        'label'           => __( 'Container Max Width (px)', 'newsum' ),
        'description'     => __( 'The maximum width of the main site container when Contained mode is active.', 'newsum' ),
        'section'         => 'newsum_layout_section',
        'type'            => 'range',
        'input_attrs'     => array(
            'min'  => 1200,
            'max'  => 2200,
            'step' => 20,
        ),
        'priority'        => 2,
        'active_callback' => function( $control ) {
            $style = $control->manager->get_setting( 'newsum_container_style' );
            return ! $style || 'contained' === $style->value();
        },
    ) );

    // No redundant setting here. Moved content_width below.
    
    if ( ! newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_layout_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_layout_upsell', array(
            'label'       => __( 'Want AJAX Load More & Infinite Scroll?', 'newsum' ),
            'section'     => 'newsum_layout_section',
            'upsell_text' => __( 'Unlock AJAX Load More & Infinite Scroll in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 1,
        ) ) );
    }

    $wp_customize->add_setting( 'newsum_global_image_radius', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'newsum_global_image_radius', array(
        'label'       => __( 'Global Image Radius (px)', 'newsum' ),
        'description' => __( 'Applies rounded corners to all images across the site.', 'newsum' ),
        'section'     => 'newsum_layout_section',
        'type'        => 'number',
        'priority'    => 5,
    ) );

    // Single Post Sidebar
    $wp_customize->add_setting( 'newsum_single_sidebar', array(
        'default'           => 'none',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( new NewSum_Radio_Image_Control( $wp_customize, 'newsum_single_sidebar', array(
        'label'   => __( 'Single Post Sidebar', 'newsum' ),
        'section' => 'newsum_single_section',
        'choices' => array(
            'none'  => array(
                'name' => __( 'No Sidebar', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>',
            ),
            'left'  => array(
                'name' => __( 'Left Sidebar', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18"/></svg>',
            ),
            'right' => array(
                'name' => __( 'Right Sidebar', 'newsum' ),
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M15 3v18"/></svg>',
            ),
        ),
    ) ) );

    // ── Colors Section ──────────────────────────────────────
    if ( ! newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_colors_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_colors_upsell', array(
            'label'       => __( 'Need Advanced Dark Mode Colors?', 'newsum' ),
            'section'     => 'colors',
            'upsell_text' => __( 'Unlock Dark Mode Customization & Gradients in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 1,
        ) ) );
    }
    
    $wp_customize->add_setting( 'newsum_default_mode', array(
        'default'           => 'light',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_default_mode', array(
        'label'    => __( 'Default Theme Mode', 'newsum' ),
        'section'  => 'colors',
        'type'     => 'select',
        'priority' => 1,
        'choices'  => array(
            'light' => __( 'Light Mode', 'newsum' ),
            'dark'  => __( 'Dark Mode', 'newsum' ),
            'auto'  => __( 'Auto (OS Preference)', 'newsum' ),
        ),
    ) );

    $wp_customize->add_setting( 'newsum_color_palette', array(
        'default'           => 'crimson',
        'sanitize_callback' => 'sanitize_key',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Palette_Control( $wp_customize, 'newsum_color_palette', array(
        'label'    => __( 'Color Palette', 'newsum' ),
        'section'  => 'colors',
        'priority' => 1,
        'palettes' => array(
            'crimson' => array( 'color' => '#ff2a2a', 'label' => __( 'Crimson (Default)', 'newsum' ) ),
            'cobalt'  => array( 'color' => '#2563eb', 'label' => __( 'Cobalt Blue', 'newsum' ) ),
            'emerald' => array( 'color' => '#059669', 'label' => __( 'Emerald Green', 'newsum' ) ),
            'amber'   => array( 'color' => '#d97706', 'label' => __( 'Amber Gold', 'newsum' ) ),
            'violet'  => array( 'color' => '#7c3aed', 'label' => __( 'Cyber Violet', 'newsum' ) ),
            'slate'   => array( 'color' => '#475569', 'label' => __( 'Slate Gray', 'newsum' ) ),
            'custom'  => array( 'color' => 'linear-gradient(135deg,#ff2a2a,#f59e0b,#10b981,#3b82f6)', 'label' => __( 'Custom', 'newsum' ) ),
        ),
    ) ) );

    $wp_customize->add_setting( 'newsum_accent_color', array(
        'default'           => '#ff2a2a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'newsum_accent_color', array(
        'label'       => __( 'Custom Accent Color', 'newsum' ),
        'description' => __( 'Only used when "Custom" palette is selected above.', 'newsum' ),
        'section'     => 'colors',
        'priority'    => 2,
    ) ) );

    // ── Top Bar Section ──────────────────────────────────────
    $wp_customize->add_section( 'newsum_topbar_section', array(
        'title'    => __( 'Top Bar', 'newsum' ),
        'panel'    => 'newsum_header_footer_panel',
        'priority' => 25,
    ) );

    $wp_customize->add_setting( 'newsum_topbar_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_topbar_show', array(
        'label'   => __( 'Show Top Bar', 'newsum' ),
        'section' => 'newsum_topbar_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_topbar_text', array(
        'default'           => 'Stories worth reading. Perspectives worth sharing.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_topbar_text', array(
        'label'       => __( 'Tagline Text', 'newsum' ),
        'description' => __( 'Leave empty to show the site tagline.', 'newsum' ),
        'section'     => 'newsum_topbar_section',
        'type'        => 'text',
    ) );

    if ( ! newsum_pro_is_active() ) {
        $wp_customize->add_setting( 'newsum_topbar_twitter', array(
            'default'           => '#',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'newsum_topbar_twitter', array(
            'label'   => __( 'X (Twitter) URL', 'newsum' ),
            'section' => 'newsum_topbar_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'newsum_topbar_instagram', array(
            'default'           => '#',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'newsum_topbar_instagram', array(
            'label'   => __( 'Instagram URL', 'newsum' ),
            'section' => 'newsum_topbar_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'newsum_topbar_facebook', array(
            'default'           => '#',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'newsum_topbar_facebook', array(
            'label'   => __( 'Facebook URL', 'newsum' ),
            'section' => 'newsum_topbar_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'newsum_topbar_youtube', array(
            'default'           => '#',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'newsum_topbar_youtube', array(
            'label'   => __( 'YouTube URL', 'newsum' ),
            'section' => 'newsum_topbar_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'newsum_social_new_tab', array(
            'default'           => true,
            'sanitize_callback' => 'newsum_sanitize_checkbox',
        ) );
        $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_social_new_tab', array(
            'label'       => __( 'Open Social Links in New Tab', 'newsum' ),
            'section'     => 'newsum_topbar_section',
        ) ) );

        $wp_customize->add_setting( 'newsum_social_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_social_upsell', array(
            'label'       => __( 'Need TikTok, Threads, or Discord?', 'newsum' ),
            'section'     => 'newsum_topbar_section',
            'upsell_text' => __( 'Unlock 20+ Dynamic Social Networks in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
        ) ) );
    }

    $wp_customize->add_setting( 'newsum_topbar_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_topbar_show_date', array(
        'label'   => __( 'Show Date', 'newsum' ),
        'section' => 'newsum_topbar_section',
    ) ) );

    // ── About Page Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_about_section', array(
        'title'       => __( 'About Page & Home Services', 'newsum' ),
        'description' => __( 'Configure your About Page hero, stats, and the Values/Services grid. Note: To edit the main photo and text paragraphs on the About page, please go to Pages > About and use the standard WordPress editor.', 'newsum' ),
        'panel'       => 'newsum_templates_panel',
        'priority'    => 45,
    ) );

    $wp_customize->add_setting( 'newsum_about_values_show_about', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_values_show_about', array(
        'label'       => __( 'Show Values/Services Grid on About Page?', 'newsum' ),
        'section'     => 'newsum_about_section',
        'priority'    => 1,
    ) ) );

    $wp_customize->add_setting( 'newsum_about_values_show_home', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_values_show_home', array(
        'label'       => __( 'Show Values/Services Grid on Homepage?', 'newsum' ),
        'description' => __( 'Enable to display the Values/Services grid on your homepage.', 'newsum' ),
        'section'     => 'newsum_about_section',
        'priority'    => 2,
    ) ) );

    // Hero Text
    $wp_customize->add_setting( 'newsum_about_hero_title', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_hero_title', array( 'label' => __( 'Hero Title', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_about_hero_subtitle', array( 'default' => 'We believe every reader deserves the absolute best stories.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_hero_subtitle', array( 'label' => __( 'Hero Subtitle', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    // Stats Section Toggles & Settings
    $wp_customize->add_setting( 'newsum_about_show_stats', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_show_stats', array(
        'label'       => __( 'Show Stats Bar on About Page?', 'newsum' ),
        'section'     => 'newsum_about_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_about_show_stats_home', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_show_stats_home', array(
        'label'       => __( 'Show Stats Bar on Homepage?', 'newsum' ),
        'section'     => 'newsum_about_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_about_stat1_num', array( 'default' => '10K+', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat1_num', array( 'label' => __( 'Stat 1 Number', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'newsum_about_stat1_label', array( 'default' => 'Active Readers', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat1_label', array( 'label' => __( 'Stat 1 Label', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_about_stat2_num', array( 'default' => '50+', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat2_num', array( 'label' => __( 'Stat 2 Number', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'newsum_about_stat2_label', array( 'default' => 'Partner Brands', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat2_label', array( 'label' => __( 'Stat 2 Label', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_about_stat3_num', array( 'default' => '24/7', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat3_num', array( 'label' => __( 'Stat 3 Number', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'newsum_about_stat3_label', array( 'default' => 'Expert Support', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_stat3_label', array( 'label' => __( 'Stat 3 Label', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    // Social Links
    $wp_customize->add_setting( 'newsum_about_show_social', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_show_social', array(
        'label'       => __( 'Show Social Icons?', 'newsum' ),
        'section'     => 'newsum_about_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_about_social_tw', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_about_social_tw', array( 'label' => __( 'Twitter/X URL', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'newsum_about_social_ig', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_about_social_ig', array( 'label' => __( 'Instagram URL', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'newsum_about_social_yt', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_about_social_yt', array( 'label' => __( 'YouTube URL', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'newsum_about_social_email', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_social_email', array( 'label' => __( 'Email Address (or mailto: link)', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    // Values Title
    $wp_customize->add_setting( 'newsum_about_values_title', array( 'default' => 'What We Believe', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_values_title', array( 'label' => __( 'Values Title', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    // Values
    for ( $i = 1; $i <= 4; $i++ ) {
        $default_titles = array( 1 => 'Quality First', 2 => 'Community', 3 => 'Sustainability', 4 => 'Pure Joy' );
        $default_descs = array(
            1 => 'Every story is crafted by experts and industry veterans.',
            2 => 'We’re building a community of passionate thinkers.',
            3 => 'Eco-friendly packaging and ethical sourcing, always.',
            4 => 'Because great ideas bring out the best in all of us.'
        );

        $wp_customize->add_setting( 'newsum_about_val' . $i . '_title', array( 'default' => $default_titles[$i], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( 'newsum_about_val' . $i . '_title', array( 'label' => sprintf( __( 'Value %d Title', 'newsum' ), $i ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

        $wp_customize->add_setting( 'newsum_about_val' . $i . '_desc', array( 'default' => $default_descs[$i], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( 'newsum_about_val' . $i . '_desc', array( 'label' => sprintf( __( 'Value %d Description', 'newsum' ), $i ), 'section' => 'newsum_about_section', 'type' => 'text' ) );
    }

    // Masthead Controls
    $wp_customize->add_setting( 'newsum_about_show_masthead', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_about_show_masthead', array(
        'label'       => __( 'Show Editorial Masthead Section?', 'newsum' ),
        'section'     => 'newsum_about_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_about_masthead_title', array( 'default' => 'Editorial Masthead', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_masthead_title', array( 'label' => __( 'Masthead Title', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_about_masthead_desc', array( 'default' => 'Independent journalists, data analysts, and investigative researchers committed to depth and accuracy.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_about_masthead_desc', array( 'label' => __( 'Masthead Description', 'newsum' ), 'section' => 'newsum_about_section', 'type' => 'text' ) );

    // ── Selective Refresh Partials (Edit Shortcuts) ─────────
    if ( isset( $wp_customize->selective_refresh ) ) {
        // 0. Top Bar
        $wp_customize->selective_refresh->add_partial( 'newsum_topbar_partial', array(
            'selector'            => '.header-super-top',
            'settings'            => array(
                'newsum_topbar_show',
                'newsum_topbar_text',
                'newsum_topbar_show_date',
                'newsum_topbar_twitter',
                'newsum_topbar_instagram',
                'newsum_topbar_facebook',
                'newsum_topbar_youtube',
            ),
            'render_callback'     => 'newsum_render_header_topbar',
            'container_inclusive' => true,
        ) );

        // 0.1 Header Advertisement Slot
        $wp_customize->selective_refresh->add_partial( 'newsum_header_ad_partial', array(
            'selector'            => '.newsum-header-sponsor-frame',
            'settings'            => array(
                'newsum_header_ad_show',
                'newsum_header_ad_type',
                'newsum_header_ad_image',
                'newsum_header_ad_url',
                'newsum_header_ad_target',
                'newsum_header_ad_code',
            ),
            'render_callback'     => 'newsum_render_header_ad',
            'container_inclusive' => true,
        ) );

        // 0.2 Header Breaking News Ticker
        $wp_customize->selective_refresh->add_partial( 'newsum_trending_ticker_partial', array(
            'selector'            => '.trending-ticker',
            'settings'            => array(
                'newsum_header_show_ticker',
                'newsum_header_ticker_label',
                'newsum_header_ticker_count',
            ),
            'render_callback'     => 'newsum_render_trending_ticker',
            'container_inclusive' => true,
        ) );

        // 0.3 Primary Header Navigation & Settings
        $wp_customize->selective_refresh->add_partial( 'newsum_header_partial', array(
            'selector'            => '.header-bottom',
            'settings'            => array(
                'newsum_sticky_header',
                'newsum_header_show_dark_mode',
                'newsum_header_show_search',
                'newsum_header_subscribe_text',
                'newsum_header_subscribe_url',
                'newsum_header_subscribe_target',
            ),
            'render_callback'     => function() {
                // Return bottom header HTML
                ob_start();
                get_template_part( 'template-parts/header/header-nav' );
                $nav = ob_get_clean();
                if ( empty( $nav ) ) {
                    // Fallback to reload preview
                    return '';
                }
                return $nav;
            },
            'container_inclusive' => true,
        ) );

        // 1. Stories Reel
        $wp_customize->selective_refresh->add_partial( 'newsum_stories_partial', array(
            'selector'            => '.stories-highlights-section',
            'settings'            => array(
                'newsum_stories_show',
                'newsum_stories_title',
                'newsum_stories_source',
                'newsum_stories_category',
                'newsum_stories_count',
                'newsum_stories_show_nav',
            ),
            'render_callback'     => 'newsum_render_stories_section',
            'container_inclusive' => true,
        ) );

        // 2. Hero Section
        $wp_customize->selective_refresh->add_partial( 'newsum_hero_partial', array(
            'selector'            => '#hero-section-wrapper',
            'settings'            => array(
                'newsum_hero_show',
                'newsum_hero_layout',
                'newsum_hero_source',
                'newsum_hero_category',
                'newsum_hero_tag',
                'newsum_hero_slider_autoplay',
                'newsum_hero_side_cards_rotate',
                'newsum_hero_animation',
                'newsum_newspaper_left_kicker',
                'newsum_newspaper_left_show_date',
                'newsum_newspaper_right_kicker',
                'newsum_newspaper_right_badge',
                'newsum_newspaper_right_show_badge',
            ),
            'render_callback'     => 'newsum_render_hero_section',
            'container_inclusive' => true,
        ) );

        // 3. Marquee Strip
        $wp_customize->selective_refresh->add_partial( 'newsum_marquee_partial', array(
            'selector'            => '#marquee-wrapper',
            'settings'            => array(
                'newsum_marquee_show',
                'newsum_marquee_source',
                'newsum_marquee_text',
                'newsum_marquee_count',
            ),
            'render_callback'     => 'newsum_render_marquee_section',
            'container_inclusive' => true,
        ) );

        // 4. Home Categories Accordion
        $wp_customize->selective_refresh->add_partial( 'newsum_home_categories_partial', array(
            'selector'            => '#homeCategoriesWrapper',
            'settings'            => array(
                'newsum_home_categories_show',
                'newsum_home_categories_title',
                'newsum_home_categories_count',
            ),
            'render_callback'     => 'newsum_render_home_categories_section',
            'container_inclusive' => true,
        ) );

        // 5. Custom CTA / Split Screen Section
        $wp_customize->selective_refresh->add_partial( 'newsum_custom_banner_partial', array(
            'selector'            => '.newsum-custom-cta',
            'settings'            => array(
                'newsum_custom_banner_layout',
                'newsum_custom_banner_section_title',
                'newsum_custom_banner_bg_color',
                'newsum_custom_banner_image',
                'newsum_custom_banner_title',
                'newsum_custom_banner_text',
                'newsum_custom_banner_btn_text',
                'newsum_custom_banner_btn_url',
                'newsum_custom_banner_btn_target',
            ),
            'render_callback'     => 'newsum_render_custom_banner_section',
            'container_inclusive' => true,
        ) );

        // 6. The Wire / Masonry Section
        $wp_customize->selective_refresh->add_partial( 'newsum_masonry_partial', array(
            'selector'            => '#masonrySectionWrap',
            'settings'            => array(
                'newsum_masonry_show',
                'newsum_masonry_title',
                'newsum_masonry_sidebar',
                'newsum_masonry_list_count',
                'newsum_featured_grid_title',
            ),
            'render_callback'     => 'newsum_render_masonry_section',
            'container_inclusive' => true,
        ) );

        // 7. Interlude Quote
        $wp_customize->selective_refresh->add_partial( 'newsum_quote_partial', array(
            'selector'            => '#interludeQuoteBlock',
            'settings'            => array(
                'newsum_quote_show',
                'newsum_quote_text',
                'newsum_quote_author',
                'newsum_quote_bg_color',
                'newsum_quote_bg_image',
            ),
            'render_callback'     => 'newsum_render_quote_section',
            'container_inclusive' => true,
        ) );

        // 8. Recent Dispatches Feed
        $wp_customize->selective_refresh->add_partial( 'newsum_feed_partial', array(
            'selector'            => '#recentDispatchesHeader',
            'settings'            => array(
                'newsum_feed_show',
                'newsum_latest_stories_title',
            ),
            'render_callback'     => 'newsum_render_feed_section',
            'container_inclusive' => true,
        ) );

        // 9. Shortcode / Ad Block
        $wp_customize->selective_refresh->add_partial( 'newsum_shortcode_partial', array(
            'selector'            => '#homepageShortcodeSection',
            'settings'            => array(
                'newsum_homepage_shortcode_show',
                'newsum_homepage_shortcode',
                'newsum_homepage_shortcode_fullwidth',
            ),
            'render_callback'     => 'newsum_render_shortcode_section',
            'container_inclusive' => true,
        ) );

        // 10. Social Proof Bar
        $wp_customize->selective_refresh->add_partial( 'newsum_social_proof_partial', array(
            'selector'            => '#socialProofBar',
            'settings'            => array(
                'newsum_social_proof_show',
                'newsum_social_proof_title',
                'newsum_social_proof_logo_1',
                'newsum_social_proof_logo_2',
                'newsum_social_proof_logo_3',
                'newsum_social_proof_logo_4',
            ),
            'render_callback'     => 'newsum_render_social_proof_section',
            'container_inclusive' => true,
        ) );

        // 11. Full-Width Newsletter CTA
        $wp_customize->selective_refresh->add_partial( 'newsum_newsletter_partial', array(
            'selector'            => '#newsletterSection',
            'settings'            => array(
                'newsum_newsletter_heading',
                'newsum_newsletter_desc',
                'newsum_newsletter_action',
            ),
            'render_callback'     => 'newsum_render_newsletter_section',
            'container_inclusive' => true,
        ) );

        // 12. Multimedia & Podcasts
        $wp_customize->selective_refresh->add_partial( 'newsum_media_partial', array(
            'selector'            => '#multimediaSection',
            'settings'            => array(
                'newsum_media_show',
                'newsum_media_title',
                'newsum_media_link_text',
                'newsum_media_link_url',
            ),
            'render_callback'     => 'newsum_render_media_section',
            'container_inclusive' => true,
        ) );

        // 13. Home Publication Stats Bar (Optional Home Display)
        $wp_customize->selective_refresh->add_partial( 'newsum_home_stats_partial', array(
            'selector'            => '#homeStatsSection',
            'settings'            => array(
                'newsum_about_show_stats_home',
                'newsum_about_stat1_num',
                'newsum_about_stat1_label',
                'newsum_about_stat2_num',
                'newsum_about_stat2_label',
                'newsum_about_stat3_num',
                'newsum_about_stat3_label',
            ),
            'render_callback'     => 'newsum_render_home_stats_section',
            'container_inclusive' => true,
        ) );

        // 14. Home Values / Principles Grid (Optional Home Display)
        $wp_customize->selective_refresh->add_partial( 'newsum_home_values_partial', array(
            'selector'            => '#homeValuesSection',
            'settings'            => array(
                'newsum_about_values_show_home',
                'newsum_about_values_title',
                'newsum_about_val1_title',
                'newsum_about_val1_desc',
                'newsum_about_val2_title',
                'newsum_about_val2_desc',
                'newsum_about_val3_title',
                'newsum_about_val3_desc',
                'newsum_about_val4_title',
                'newsum_about_val4_desc',
            ),
            'render_callback'     => 'newsum_render_home_values_section',
            'container_inclusive' => true,
        ) );

        // 15. Site Footer Partial
        $wp_customize->selective_refresh->add_partial( 'newsum_footer_partial', array(
            'selector'            => '#siteFooterWrap',
            'settings'            => array(
                'newsum_footer_show_brand',
                'newsum_footer_tagline',
                'newsum_footer_col1_title',
                'newsum_footer_col2_title',
                'newsum_footer_col3_title',
                'newsum_footer_col4_title',
                'newsum_footer_gallery_title',
                'newsum_footer_copyright',
                'newsum_footer_back_to_top',
            ),
            'render_callback'     => function() {
                ob_start();
                get_template_part( 'footer' );
                return ob_get_clean();
            },
            'container_inclusive' => true,
        ) );

        // About Page Template Partial
        $wp_customize->selective_refresh->add_partial( 'newsum_about_page_partial', array(
            'selector'            => '#aboutPageWrap',
            'settings'            => array(
                'newsum_about_hero_title',
                'newsum_about_hero_subtitle',
                'newsum_about_show_social',
                'newsum_about_social_ig',
                'newsum_about_social_tw',
                'newsum_about_social_yt',
                'newsum_about_social_email',
                'newsum_about_show_stats',
                'newsum_about_stat1_num',
                'newsum_about_stat1_label',
                'newsum_about_stat2_num',
                'newsum_about_stat2_label',
                'newsum_about_stat3_num',
                'newsum_about_stat3_label',
                'newsum_about_values_show_about',
                'newsum_about_values_title',
                'newsum_about_val1_title',
                'newsum_about_val1_desc',
                'newsum_about_val2_title',
                'newsum_about_val2_desc',
                'newsum_about_val3_title',
                'newsum_about_val3_desc',
                'newsum_about_val4_title',
                'newsum_about_val4_desc',
                'newsum_about_show_masthead',
                'newsum_about_masthead_title',
                'newsum_about_masthead_desc',
            ),
            'render_callback'     => '__return_false',
        ) );

        // Contact Page Template Partial
        $wp_customize->selective_refresh->add_partial( 'newsum_contact_page_partial', array(
            'selector'            => '#contactPageWrap',
            'settings'            => array(
                'newsum_contact_show_email',
                'newsum_contact_email',
                'newsum_contact_show_social',
                'newsum_contact_social_ig',
                'newsum_contact_social_tw',
                'newsum_contact_social_pi',
                'newsum_contact_form_shortcode',
                'newsum_contact_show_standards',
                'newsum_contact_standards_title',
                'newsum_contact_standards_text',
                'newsum_contact_show_address',
                'newsum_contact_address_title',
                'newsum_contact_address_text',
            ),
            'render_callback'     => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_topbar_show', array(
            'selector'        => '.header-super-top',
            'render_callback' => '__return_false',
        ) );
        $wp_customize->selective_refresh->add_partial( 'newsum_topbar_text', array(
            'selector'        => '.utility-tagline',
            'render_callback' => '__return_false',
        ) );
        $wp_customize->selective_refresh->add_partial( 'newsum_header_style', array(
            'selector' => '.header-bottom',
            'render_callback' => '__return_false',
        ) );
        $wp_customize->selective_refresh->add_partial( 'custom_logo', array(
            'selector' => '.brand-logo',
            'render_callback' => '__return_false',
        ) );
        $wp_customize->selective_refresh->add_partial( 'newsum_logo_height', array(
            'selector' => '.brand-logo',
            'render_callback' => '__return_false',
        ) );
    }

    // ── Contact Page Section ────────────────────────────────
    $wp_customize->add_section( 'newsum_contact_section', array(
        'title'    => __( 'Contact Page Settings', 'newsum' ),
        'panel'    => 'newsum_templates_panel',
        'priority' => 46,
    ) );

    // Email Settings
    $wp_customize->add_setting( 'newsum_contact_show_email', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_contact_show_email', array( 'label' => __( 'Show Email Section', 'newsum' ), 'section' => 'newsum_contact_section' ) ) );

    $wp_customize->add_setting( 'newsum_contact_email', array( 'default' => '', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'newsum_contact_email', array( 'label' => __( 'Contact Email', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'email' ) );

    // Social Links Settings
    $wp_customize->add_setting( 'newsum_contact_show_social', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_contact_show_social', array( 'label' => __( 'Show Social Media Section', 'newsum' ), 'section' => 'newsum_contact_section' ) ) );

    $wp_customize->add_setting( 'newsum_contact_social_ig', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_contact_social_ig', array( 'label' => __( 'Instagram URL', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'newsum_contact_social_tw', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_contact_social_tw', array( 'label' => __( 'Twitter/X URL', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'url' ) );

    $wp_customize->add_setting( 'newsum_contact_social_pi', array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'newsum_contact_social_pi', array( 'label' => __( 'Pinterest URL', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'url' ) );

    // Form Shortcode Setting
    $wp_customize->add_setting( 'newsum_contact_form_shortcode', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_contact_form_shortcode', array( 
        'label'       => __( 'Form Shortcode (Optional)', 'newsum' ), 
        'description' => __( 'Paste any Contact Form 7 or WPForms shortcode (e.g. [wpforms id="123"]). If empty, the built-in clean form will be displayed.', 'newsum' ),
        'section'     => 'newsum_contact_section', 
        'type'        => 'text' 
    ) );

    // Response Standards Card
    $wp_customize->add_setting( 'newsum_contact_show_standards', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_contact_show_standards', array( 'label' => __( 'Show Response Standards Card?', 'newsum' ), 'section' => 'newsum_contact_section' ) ) );

    $wp_customize->add_setting( 'newsum_contact_standards_title', array( 'default' => '24–48 Hour Review', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_contact_standards_title', array( 'label' => __( 'Standards Title', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_contact_standards_text', array( 'default' => 'Our editorial board reviews submissions on rolling daily cycles. Time-sensitive breaking tips receive immediate triage.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_contact_standards_text', array( 'label' => __( 'Standards Description', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'text' ) );

    // Address / Headquarters Card
    $wp_customize->add_setting( 'newsum_contact_show_address', array( 'default' => true, 'sanitize_callback' => 'newsum_sanitize_checkbox' ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_contact_show_address', array( 'label' => __( 'Show Office Address Card?', 'newsum' ), 'section' => 'newsum_contact_section' ) ) );

    $wp_customize->add_setting( 'newsum_contact_address_title', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_contact_address_title', array( 'label' => __( 'Office Title', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_contact_address_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'newsum_contact_address_text', array( 'label' => __( 'Office Address Text', 'newsum' ), 'section' => 'newsum_contact_section', 'type' => 'textarea' ) );

    // ── Newsletter Section ──────────────────────────────────
    $wp_customize->add_section( 'newsum_newsletter_section', array(
        'title'    => __( 'Newsletter CTA', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 47,
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_heading', array( 'default' => 'Stories worth<br><em>reading and sharing</em> with the world.', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'newsum_newsletter_heading', array( 'label' => __( 'Heading', 'newsum' ), 'section' => 'newsum_newsletter_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_newsletter_desc', array( 'default' => 'Weekly essays, guides, and a healthy dose of inspiration. Join 12,000+ readers.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_newsletter_desc', array( 'label' => __( 'Description', 'newsum' ), 'section' => 'newsum_newsletter_section', 'type' => 'text' ) );

    $wp_customize->add_setting( 'newsum_newsletter_action', array( 'default' => '#', 'sanitize_callback' => 'newsum_sanitize_newsletter_action' ) );
    $wp_customize->add_control( 'newsum_newsletter_action', array( 
        'label' => __( 'Form Action URL or Shortcode', 'newsum' ), 
        'section' => 'newsum_newsletter_section', 
        'type' => 'text', 
    ) );

    $wp_customize->add_setting( 'newsum_newsletter_btn_text', array( 
        'default'           => 'Join The Terminal →', 
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_newsletter_btn_text', array( 
        'label'   => __( 'Button Text', 'newsum' ), 
        'section' => 'newsum_newsletter_section', 
        'type'    => 'text', 
    ) );
    
    $wp_customize->add_setting( 'newsum_newsletter_help', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Message_Control( $wp_customize, 'newsum_newsletter_help', array(
        'label'       => '',
        'description' => __( '<strong>How to connect your newsletter:</strong><br>1. Paste your exact Mailchimp Action URL.<br>2. Paste a plugin shortcode (e.g. <code>[mc4wp_form]</code>).<br>3. Or simply paste a full Mailchimp Embedded Form HTML block and we will instantly extract the URL for you.', 'newsum' ),
        'section'     => 'newsum_newsletter_section',
    ) ) );
    
    if ( class_exists( 'NewSum_Customize_Upsell_Control' ) && ! defined( 'NEWSUM_PRO_VERSION' ) ) {
        $wp_customize->add_setting( 'newsum_newsletter_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_newsletter_upsell', array(
            'label'       => __( 'Want Native AJAX Integration?', 'newsum' ),
            'description' => __( 'Tired of messy shortcodes and bulky plugins? Unlock ultra-fast, native AJAX Mailchimp integration built directly into the theme with NewSum Pro.', 'newsum' ),
            'section'     => 'newsum_newsletter_section',
            'upsell_text' => __( 'Upgrade to NewSum Pro &rarr;', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/newsum-pro/',
        ) ) );
    }




    // ═══ TYPOGRAPHY PANEL ═══════════════════════════
    $wp_customize->add_section( 'newsum_typography_section', array(
        'title'       => __( 'Typography Options', 'newsum' ),
        'panel'       => 'newsum_global_panel',
        'priority'    => 28,
    ) );

    if ( ! newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_typography_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_typography_upsell', array(
            'label'       => __( 'Want Custom Google Fonts & Global Buttons?', 'newsum' ),
            'section'     => 'newsum_typography_section',
            'upsell_text' => __( 'Unlock Google Fonts & Global Buttons in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 1,
        ) ) );
    }

    // Heading Font Family
    $wp_customize->add_setting( 'newsum_heading_font', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'newsum_sanitize_select',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_heading_font', array(
        'label'   => __( 'Heading Font Family', 'newsum' ),
        'section' => 'newsum_typography_section',
        'type'    => 'select',
        'choices' => array(
            'Playfair Display' => 'Playfair Display',
            'Georgia'          => 'Georgia',
            'Lora'             => 'Lora',
            'Merriweather'     => 'Merriweather',
            'Libre Baskerville' => 'Libre Baskerville',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'DM Serif Display' => 'DM Serif Display',
            'Noto Serif'       => 'Noto Serif',
            'Source Serif Pro'  => 'Source Serif Pro',
            'System / Uploaded Font' => 'System / Uploaded Font',
        ),
    ) );

    $wp_customize->add_setting( 'newsum_custom_heading_font', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_custom_heading_font', array(
        'label'       => __( 'Custom Heading Font Name', 'newsum' ),
        'description' => __( 'If using a custom uploaded font, type its exact family name here.', 'newsum' ),
        'section'     => 'newsum_typography_section',
        'type'        => 'text',
    ) );

    // Body Font Family
    $wp_customize->add_setting( 'newsum_body_font', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'newsum_sanitize_select',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_body_font', array(
        'label'   => __( 'Body Font Family', 'newsum' ),
        'section' => 'newsum_typography_section',
        'type'    => 'select',
        'choices' => array(
            'Inter'       => 'Inter',
            'Georgia'     => 'Georgia',
            'Roboto'      => 'Roboto',
            'Open Sans'   => 'Open Sans',
            'Nunito Sans' => 'Nunito Sans',
            'Work Sans'   => 'Work Sans',
            'DM Sans'     => 'DM Sans',
            'Outfit'      => 'Outfit',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'System / Uploaded Font' => 'System / Uploaded Font',
        ),
    ) );

    $wp_customize->add_setting( 'newsum_custom_body_font', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_custom_body_font', array(
        'label'       => __( 'Custom Body Font Name', 'newsum' ),
        'description' => __( 'If using a custom uploaded font, type its exact family name here.', 'newsum' ),
        'section'     => 'newsum_typography_section',
        'type'        => 'text',
    ) );

    // Heading Font Size Scale
    $wp_customize->add_setting( 'newsum_heading_scale', array(
        'default'           => 100,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_heading_scale', array(
        'label'       => __( 'Heading Font Size (%)', 'newsum' ),
        'description' => __( 'Scale all headings proportionally. 100% = default.', 'newsum' ),
        'section'     => 'newsum_typography_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 80,
            'max'  => 130,
            'step' => 5,
        ),
    ) );

    // Body Font Size
    $wp_customize->add_setting( 'newsum_body_font_size', array(
        'default'           => 16,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_body_font_size', array(
        'label'       => __( 'Body Font Size (px)', 'newsum' ),
        'section'     => 'newsum_typography_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 14,
            'max'  => 20,
            'step' => 1,
        ),
    ) );



    // ═══ GENERAL / LAYOUT PANEL ═══════════════════
    $wp_customize->add_section( 'newsum_general_section', array(
        'title'    => __( 'General Settings', 'newsum' ),
        'panel'    => 'newsum_global_panel',
        'priority' => 25,
    ) );

    // Layout Density
    $wp_customize->add_setting( 'newsum_layout_density', array(
        'default'           => 'standard',
        'sanitize_callback' => 'newsum_sanitize_select',
    ) );
    $wp_customize->add_control( 'newsum_layout_density', array(
        'label'   => __( 'Layout Density (Section Spacing)', 'newsum' ),
        'section' => 'newsum_layout_section',
        'type'    => 'select',
        'choices' => array(
            'compact'  => __( 'Compact', 'newsum' ),
            'standard' => __( 'Standard', 'newsum' ),
            'relaxed'  => __( 'Relaxed', 'newsum' ),
            'custom'   => __( 'Custom (Pro)', 'newsum' ),
        ),
        'priority' => 5,
    ) );

    if ( class_exists( 'NewSum_Customize_Upsell_Control' ) && ! defined( 'NEWSUM_PRO_VERSION' ) ) {
        $wp_customize->add_setting( 'newsum_layout_density_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_layout_density_upsell', array(
            'label'       => __( 'Pro Feature', 'newsum' ),
            'description' => __( 'Unlock exact custom spacing and scale control with NewSum Pro.', 'newsum' ),
            'upsell_text' => __( 'Upgrade to NewSum Pro &rarr;', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/',
            'section'     => 'newsum_layout_section',
            'priority'    => 6,
            'active_callback' => function( $control ) {
                return $control->manager->get_setting( 'newsum_layout_density' )->value() === 'custom';
            }
        ) ) );
    }

    // 🟩🟩🟩 FOOTER SETTINGS 🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩🟩
    $wp_customize->add_section( 'newsum_footer_section', array(
        'title'    => __( 'Footer Settings', 'newsum' ),
        'priority' => 22.5,
    ) );

    // Notice when widgets are active in footer-1
    $wp_customize->add_setting( 'newsum_footer_widgets_active_notice', array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( new NewSum_Message_Control( $wp_customize, 'newsum_footer_widgets_active_notice', array(
        'label'           => __( 'Footer Widgets Active', 'newsum' ),
        'description'     => __( 'You currently have active widgets or blocks in <strong>Footer Widget Area</strong> (under <em>Customize &gt; Widgets</em>).<br><br>These widgets take precedence and replace the default 4-column layout on your site. The column settings below only apply when the Footer Widget Area is empty.', 'newsum' ),
        'section'         => 'newsum_footer_section',
        'priority'        => 0,
        'active_callback' => function() { return is_active_sidebar( 'footer-1' ); },
    ) ) );

    // Notice when built-in columns are active (no widgets)
    $wp_customize->add_setting( 'newsum_footer_columns_info_notice', array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $footer_info_control = new NewSum_Message_Control( $wp_customize, 'newsum_footer_columns_info_notice', array(
        'label'           => __( 'Built-in 4-Column Layout', 'newsum' ),
        'description'     => __( 'The switches and headings below control the theme\'s built-in editorial columns.<br><br><strong>Want to add custom widgets or blocks instead?</strong> You can add any widget or block in <strong>Customize &gt; Widgets &gt; Footer Widget Area</strong>, and it will automatically replace these default columns.', 'newsum' ),
        'section'         => 'newsum_footer_section',
        'priority'        => 0,
        'active_callback' => function() { return ! is_active_sidebar( 'footer-1' ); },
    ) );
    $footer_info_control->message_type = 'info';
    $wp_customize->add_control( $footer_info_control );

    // Footer Brand Block
    $wp_customize->add_setting( 'newsum_footer_show_brand', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_show_brand', array(
        'label'   => __( 'Show Massive Brand & Tagline', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_footer_tagline', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_tagline', array(
        'label'   => __( 'Footer Tagline', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'textarea',
    ) );

    // Column Headings & Visibility
    $wp_customize->add_setting( 'newsum_footer_show_col1', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_show_col1', array(
        'label'   => __( 'Show Column 1 (Trending Stories)', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_footer_col1_title', array(
        'default'           => __( 'Trending', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_col1_title', array(
        'label'   => __( 'Column 1 Heading (Stories)', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_footer_show_col2', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_show_col2', array(
        'label'   => __( 'Show Column 2 (Coverage / Categories)', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_footer_col2_title', array(
        'default'           => __( 'Coverage', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_col2_title', array(
        'label'   => __( 'Column 2 Heading (Categories)', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_footer_show_col3', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_show_col3', array(
        'label'   => __( 'Show Column 3 (Company & Links)', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_footer_col3_title', array(
        'default'           => __( 'Company', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_col3_title', array(
        'label'   => __( 'Column 3 Heading (Links)', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_footer_show_col4', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_show_col4', array(
        'label'   => __( 'Show Column 4 (Newsletter & Gallery)', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_footer_col4_title', array(
        'default'           => __( 'Terminal Dispatch', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_col4_title', array(
        'label'   => __( 'Column 4 Heading (Newsletter)', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_footer_newsletter_desc', array(
        'default'           => 'Direct weekly briefing on frontier AI models, hardware leaks, and cybersecurity disclosures.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_newsletter_desc', array(
        'label'   => __( 'Column 4 Newsletter Description', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'newsum_footer_newsletter_action', array(
        'default'           => '#',
        'sanitize_callback' => 'newsum_sanitize_newsletter_action',
    ) );
    $wp_customize->add_control( 'newsum_footer_newsletter_action', array(
        'label'   => __( 'Column 4 Form Action URL', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'newsum_footer_gallery_title', array(
        'default'           => __( 'Tech Gallery', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_footer_gallery_title', array(
        'label'   => __( 'Gallery Subheading', 'newsum' ),
        'section' => 'newsum_footer_section',
        'type'    => 'text',
    ) );

    // Footer Copyright
    $wp_customize->add_setting( 'newsum_footer_copyright', array(
        'default'           => __( '© {year} {site}. All rights reserved.', 'newsum' ),
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'newsum_footer_copyright', array(
        'label'       => __( 'Footer Copyright Text', 'newsum' ),
        'description' => __( 'Use {year} for current year and {site} for site title.', 'newsum' ),
        'section'     => 'newsum_footer_section',
        'type'        => 'text',
    ) );

    // Back to Top Button
    $wp_customize->add_setting( 'newsum_footer_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_footer_back_to_top', array(
        'label'   => __( 'Show Back to Top Button', 'newsum' ),
        'section' => 'newsum_footer_section',
    ) ) );

    // Footer Upsell
    if ( ! class_exists( 'WPMatcha\\Pro\\Themes\\NewSum\\Module' ) ) {
        $wp_customize->add_setting( 'newsum_footer_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_footer_upsell', array(
            'label'       => __( 'Want a Mega Footer?', 'newsum' ),
            'section'     => 'newsum_footer_section',
            'upsell_text' => __( 'Unlock the advanced Mega Footer layout in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 99,
        ) ) );
    }

    // Preloader Toggle
    $wp_customize->add_setting( 'newsum_enable_preloader', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_enable_preloader', array(
        'label'   => __( 'Enable Page Preloader', 'newsum' ),
        'section' => 'newsum_general_section',
    ) ) );

    // Scroll Progress Toggle
    $wp_customize->add_setting( 'newsum_show_scroll_progress', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_show_scroll_progress', array(
        'label'   => __( 'Show Reading Progress Bar', 'newsum' ),
        'section' => 'newsum_general_section',
    ) ) );

    // Breadcrumbs on Static Pages
    $wp_customize->add_setting( 'newsum_page_show_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_page_show_breadcrumbs', array(
        'label'   => __( 'Show Breadcrumbs on Standard Pages', 'newsum' ),
        'section' => 'newsum_general_section',
    ) ) );

    // ═══ HEADER PANEL ═════════════════════════════
    $wp_customize->add_section( 'newsum_header_section', array(
        'title'    => __( 'Header Settings', 'newsum' ),
        'panel'    => 'newsum_header_footer_panel',
        'priority' => 29,
    ) );

    // Header layout setting available in NewSum Pro
    if ( ! newsum_pro_is_active() ) {
        $wp_customize->add_setting( 'newsum_header_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_header_upsell', array(
            'label'       => __( 'Need Centered or Stacked headers?', 'newsum' ),
            'section'     => 'newsum_header_section',
            'upsell_text' => __( 'Unlock Centered & Stacked Headers in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 5,
        ) ) );
    }



    // Header Width (Contained vs Full Width)
    $wp_customize->add_setting( 'newsum_header_width', array(
        'default'           => 'contained',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_header_width', array(
        'label'       => __( 'Header Width', 'newsum' ),
        'section'     => 'newsum_header_section',
        'type'        => 'radio',
        'choices'     => array(
            'contained' => __( 'Contained (Content Grid)', 'newsum' ),
            'full'      => __( 'Full Width (2rem Padding)', 'newsum' ),
        ),
        'priority'    => 6,
    ) );

    // Sticky Header
    $wp_customize->add_setting( 'newsum_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_sticky_header', array(
        'label'   => __( 'Enable Sticky Header', 'newsum' ),
        'section' => 'newsum_header_section',
        'priority' => 7,
    ) ) );


    $wp_customize->add_setting( 'newsum_logo_height', array(
        'default'           => 50,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_logo_height', array(
        'label'       => __( 'Logo Max Height (px)', 'newsum' ),
        'section'     => 'newsum_header_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 16,
            'max'  => 120,
            'step' => 2,
        ),
    ) );

    // Hide/Show Theme Mode Icon
    $wp_customize->add_setting( 'newsum_header_show_dark_mode', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_header_show_dark_mode', array(
        'label'   => __( 'Show Theme Mode (Dark/Light) Icon', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    // Hide/Show Search Icon
    $wp_customize->add_setting( 'newsum_header_show_search', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_header_show_search', array(
        'label'   => __( 'Show Search Icon', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    // Hide/Show Desktop Off-Canvas Drawer (Pro Feature)
    if ( newsum_pro_is_active() ) {
        $wp_customize->add_setting( 'newsum_header_show_drawer', array(
            'default'           => true,
            'sanitize_callback' => 'newsum_sanitize_checkbox',
        ) );
        $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_header_show_drawer', array(
            'label'       => __( 'Show Drawer Icon (Desktop)', 'newsum' ),
            'description' => __( 'Toggle the slide-out or dropdown editorial drawer icon on desktop.', 'newsum' ),
            'section'     => 'newsum_header_section',
        ) ) );
    } else {
        $wp_customize->add_setting( 'newsum_drawer_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_drawer_upsell', array(
            'label'       => __( 'Desktop Drawer & Taxonomy Index', 'newsum' ),
            'section'     => 'newsum_header_section',
            'upsell_text' => __( 'Unlock Desktop Drawer & Taxonomy Index in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
        ) ) );
    }

    // Subscribe Button Settings
    $wp_customize->add_setting( 'newsum_header_subscribe_text', array(
        'default'           => __( 'Subscribe', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_header_subscribe_text', array(
        'label'   => __( 'Subscribe Button Text', 'newsum' ),
        'section' => 'newsum_header_section',
        'type'    => 'text',
    ) );
    $wp_customize->add_setting( 'newsum_header_subscribe_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'newsum_header_subscribe_url', array(
        'label'       => __( 'Subscribe Button URL', 'newsum' ),
        'description' => __( 'Leave empty to hide the button.', 'newsum' ),
        'section'     => 'newsum_header_section',
        'type'        => 'url',
    ) );
    
    $wp_customize->add_setting( 'newsum_header_subscribe_target', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'newsum_header_subscribe_target', array(
        'label'       => __( 'Open link in a new tab', 'newsum' ),
        'section'     => 'newsum_header_section',
        'type'        => 'checkbox',
    ) );

    // ── Header Advertisement / Leaderboard ───────────────────────
    $wp_customize->add_setting( 'newsum_header_ad_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_header_ad_heading', array(
        'label'   => __( 'Header Leaderboard Advertisement', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_header_ad_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_header_ad_show', array(
        'label'   => __( 'Enable Header Advertisement Slot', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_header_ad_type', array(
        'default'           => 'image',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_header_ad_type', array(
        'label'           => __( 'Ad Display Type', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'radio',
        'choices'         => array(
            'image' => __( 'Image Banner (Direct Sponsor / Affiliate)', 'newsum' ),
            'code'  => __( 'Custom Code (Google AdSense / Script)', 'newsum' ),
        ),
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_header_ad_show' );
            return $s ? (bool) $s->value() : true;
        },
    ) );

    $wp_customize->add_setting( 'newsum_header_ad_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'newsum_header_ad_image', array(
        'label'           => __( 'Leaderboard Banner Image (728x90)', 'newsum' ),
        'description'     => __( 'Upload a 728x90 image banner for your sponsor.', 'newsum' ),
        'section'         => 'newsum_header_section',
        'active_callback' => function( $control ) {
            $show = $control->manager->get_setting( 'newsum_header_ad_show' );
            $type = $control->manager->get_setting( 'newsum_header_ad_type' );
            return ( $show && $show->value() ) && ( ! $type || $type->value() === 'image' );
        },
    ) ) );

    $wp_customize->add_setting( 'newsum_header_ad_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'newsum_header_ad_url', array(
        'label'           => __( 'Banner Destination URL', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'url',
        'active_callback' => function( $control ) {
            $show = $control->manager->get_setting( 'newsum_header_ad_show' );
            $type = $control->manager->get_setting( 'newsum_header_ad_type' );
            return ( $show && $show->value() ) && ( ! $type || $type->value() === 'image' );
        },
    ) );

    $wp_customize->add_setting( 'newsum_header_ad_target', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'newsum_header_ad_target', array(
        'label'           => __( 'Open banner in a new tab', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'checkbox',
        'active_callback' => function( $control ) {
            $show = $control->manager->get_setting( 'newsum_header_ad_show' );
            $type = $control->manager->get_setting( 'newsum_header_ad_type' );
            return ( $show && $show->value() ) && ( ! $type || $type->value() === 'image' );
        },
    ) );

    $wp_customize->add_setting( 'newsum_header_ad_code', array(
        'default'           => '',
        'sanitize_callback' => 'newsum_sanitize_ad_code',
    ) );
    $wp_customize->add_control( 'newsum_header_ad_code', array(
        'label'           => __( 'Custom Ad HTML / Script Code', 'newsum' ),
        'description'     => __( 'Paste your Google AdSense, Carbon Ads, or advertising script here.', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'textarea',
        'active_callback' => function( $control ) {
            $show = $control->manager->get_setting( 'newsum_header_ad_show' );
            $type = $control->manager->get_setting( 'newsum_header_ad_type' );
            return ( $show && $show->value() ) && ( $type && $type->value() === 'code' );
        },
    ) );

    // ── Header Breaking News Ticker ──────────────────────────────
    $wp_customize->add_setting( 'newsum_header_ticker_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_header_ticker_heading', array(
        'label'   => __( 'Breaking News Ticker', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_header_show_ticker', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_header_show_ticker', array(
        'label'   => __( 'Enable Breaking News Ticker in Header', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    $wp_customize->add_setting( 'newsum_header_ticker_label', array(
        'default'           => __( 'BREAKING', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'newsum_header_ticker_label', array(
        'label'           => __( 'Ticker Label', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'text',
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_header_show_ticker' );
            return ! $s || $s->value();
        },
    ) );

    $wp_customize->add_setting( 'newsum_header_ticker_count', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_header_ticker_count', array(
        'label'           => __( 'Number of Ticker Stories', 'newsum' ),
        'section'         => 'newsum_header_section',
        'type'            => 'number',
        'input_attrs'     => array( 'min' => 1, 'max' => 15, 'step' => 1 ),
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_header_show_ticker' );
            return ! $s || $s->value();
        },
    ) );

    // ── Reading List & Offline Bookmarks (Pro Feature) ───────────
    $wp_customize->add_setting( 'newsum_reading_list_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_reading_list_heading', array(
        'label'   => __( 'Saved Reading List / Bookmarks', 'newsum' ),
        'section' => 'newsum_header_section',
    ) ) );

    if ( function_exists( 'newsum_is_pro_active' ) && newsum_is_pro_active() ) {
        $wp_customize->add_setting( 'newsum_enable_reading_list', array(
            'default'           => true,
            'sanitize_callback' => 'newsum_sanitize_checkbox',
        ) );
        $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_enable_reading_list', array(
            'label'       => __( 'Enable Reading List Drawer & Save Buttons', 'newsum' ),
            'description' => __( 'Adds the bookmark button to article cards and the Reading List slide-out drawer in the header.', 'newsum' ),
            'section'     => 'newsum_header_section',
        ) ) );
    } else {
        $wp_customize->add_setting( 'newsum_reading_list_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_reading_list_upsell', array(
            'section'     => 'newsum_header_section',
            'upsell_text' => __( 'Unlock Reading List Drawer & Offline Bookmarks in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
        ) ) );
    }

    // ═══ BLOG / ARCHIVE PANEL ═════════════════════
    $wp_customize->add_section( 'newsum_blog_section', array(
        'title'    => __( 'Blog & Archive Settings', 'newsum' ),
        'panel'    => 'newsum_templates_panel',
        'priority' => 35,
    ) );

    // Archive Layout Style
    $wp_customize->add_setting( 'newsum_archive_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_archive_layout', array(
        'label'    => __( 'Archive Feed Layout', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'type'     => 'select',
        'choices'  => array(
            'grid'    => __( 'Grid Cards', 'newsum' ),
            'list'    => __( 'Editorial List (Horizontal)', 'newsum' ),
            'compact' => __( 'Compact News Wire', 'newsum' ),
        ),
        'priority' => 1,
    ) );

    // Archive Sidebar Position
    $wp_customize->add_setting( 'newsum_archive_sidebar', array(
        'default'           => 'right',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_archive_sidebar', array(
        'label'    => __( 'Sidebar Position', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'type'     => 'select',
        'choices'  => array(
            'right'  => __( 'Right Sidebar', 'newsum' ),
            'left'   => __( 'Left Sidebar', 'newsum' ),
            'none'   => __( 'No Sidebar (Full Width)', 'newsum' ),
            'narrow' => __( 'No Sidebar (Narrow Focused Column)', 'newsum' ),
        ),
        'priority' => 2,
    ) );

    // Grid Columns (shown when layout is grid)
    $wp_customize->add_setting( 'newsum_grid_columns', array(
        'default'           => '3',
        'sanitize_callback' => 'newsum_sanitize_select',
    ) );
    $wp_customize->add_control( 'newsum_grid_columns', array(
        'label'           => __( 'Grid Columns', 'newsum' ),
        'section'         => 'newsum_blog_section',
        'type'            => 'select',
        'choices'         => array(
            '2' => __( '2 Columns', 'newsum' ),
            '3' => __( '3 Columns', 'newsum' ),
        ),
        'priority'        => 3,
        'active_callback' => function( $control ) {
            $layout = $control->manager->get_setting( 'newsum_archive_layout' );
            return ! $layout || 'grid' === $layout->value();
        },
    ) );

    // Excerpt Length
    $wp_customize->add_setting( 'newsum_excerpt_length', array(
        'default'           => 22,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_excerpt_length', array(
        'label'       => __( 'Excerpt Length (words)', 'newsum' ),
        'section'     => 'newsum_blog_section',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 50,
            'step' => 2,
        ),
        'priority'    => 4,
    ) );

    // Show Subcategory Filter Pills
    $wp_customize->add_setting( 'newsum_archive_show_filters', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_filters', array(
        'label'    => __( 'Show Topic Filter Pills Bar', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 5,
    ) ) );

    // Show Category / Archive Description
    $wp_customize->add_setting( 'newsum_archive_show_desc', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_desc', array(
        'label'    => __( 'Show Archive Description', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 6,
    ) ) );

    // Show Total Stories Count Badge
    $wp_customize->add_setting( 'newsum_archive_show_count', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_count', array(
        'label'    => __( 'Show Published Story Count', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 7,
    ) ) );

    // Show Breadcrumbs on Archive Pages
    $wp_customize->add_setting( 'newsum_archive_show_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_breadcrumbs', array(
        'label'    => __( 'Show Breadcrumbs on Archive Pages', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 8,
    ) ) );

    // Heading: Archive Post Card Elements
    $wp_customize->add_setting( 'newsum_archive_card_heading', array( 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( new NewSum_Heading_Control( $wp_customize, 'newsum_archive_card_heading', array(
        'label'       => __( 'Post Card Elements & Meta', 'newsum' ),
        'description' => __( 'Toggle which meta elements appear on each story card in the feed.', 'newsum' ),
        'section'     => 'newsum_blog_section',
        'priority'    => 9,
    ) ) );

    // Card: Thumbnail
    $wp_customize->add_setting( 'newsum_archive_show_thumb', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_thumb', array(
        'label'    => __( 'Show Featured Thumbnail', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 10,
    ) ) );

    // Card: Category Badge
    $wp_customize->add_setting( 'newsum_archive_show_cat', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_cat', array(
        'label'    => __( 'Show Category Badge', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 11,
    ) ) );

    // Card: Reading Time Pill
    $wp_customize->add_setting( 'newsum_archive_show_reading_time', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_reading_time', array(
        'label'    => __( 'Show Reading Time Pill', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 12,
    ) ) );

    // Card: 30s Summary Button
    $wp_customize->add_setting( 'newsum_archive_show_summary_btn', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_summary_btn', array(
        'label'    => __( 'Show 30s Summary Button', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 13,
    ) ) );

    // Card: Bookmark Button
    $wp_customize->add_setting( 'newsum_archive_show_bookmark', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_bookmark', array(
        'label'    => __( 'Show Bookmark Button', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 14,
    ) ) );

    // Card: Excerpt
    $wp_customize->add_setting( 'newsum_archive_show_excerpt', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_excerpt', array(
        'label'    => __( 'Show Post Excerpt', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 15,
    ) ) );

    // Card: Read More Button / Link
    $wp_customize->add_setting( 'newsum_archive_show_read_more', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_read_more', array(
        'label'    => __( 'Show Read More Button', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 16,
    ) ) );

    // Card: Read More Text
    $wp_customize->add_setting( 'newsum_archive_read_more_text', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => __( 'Read Story →', 'newsum' ),
    ) );
    $wp_customize->add_control( 'newsum_archive_read_more_text', array(
        'label'           => __( 'Read More Text', 'newsum' ),
        'section'         => 'newsum_blog_section',
        'type'            => 'text',
        'priority'        => 17,
        'active_callback' => function( $control ) {
            $s = $control->manager->get_setting( 'newsum_archive_show_read_more' );
            return $s ? (bool) $s->value() : false;
        },
    ) );

    // Card: Author Byline
    $wp_customize->add_setting( 'newsum_archive_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_author', array(
        'label'    => __( 'Show Author Byline', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 18,
    ) ) );

    // Card: Publish Date
    $wp_customize->add_setting( 'newsum_archive_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_archive_show_date', array(
        'label'    => __( 'Show Publish Date', 'newsum' ),
        'section'  => 'newsum_blog_section',
        'priority' => 19,
    ) ) );

    // Archive Pagination Type
    $wp_customize->add_setting( 'newsum_archive_pagination_type', array(
        'default'           => 'load_more',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_archive_pagination_type', array(
        'label'       => __( 'Pagination Type', 'newsum' ),
        'description' => __( 'Choose how visitors navigate through archive stories.', 'newsum' ),
        'section'     => 'newsum_blog_section',
        'type'        => 'select',
        'choices'     => array(
            'numeric'   => __( 'Classic Numeric Pagination', 'newsum' ),
            'load_more' => __( 'AJAX "Load More" Button', 'newsum' ),
            'infinite'  => __( 'Infinite Scroll (Auto-load on scroll)', 'newsum' ),
        ),
        'priority'    => 20,
    ) );

    // Load More Button Text
    $wp_customize->add_setting( 'newsum_archive_load_more_text', array(
        'default'           => __( 'Load More Stories', 'newsum' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_archive_load_more_text', array(
        'label'       => __( 'Load More Button Label', 'newsum' ),
        'section'     => 'newsum_blog_section',
        'type'        => 'text',
        'priority'    => 21,
    ) );

    // ═══ SINGLE POST PANEL ═════════════════════
    $wp_customize->add_section( 'newsum_single_section', array(
        'title'    => __( 'Single Post Settings', 'newsum' ),
        'panel'    => 'newsum_templates_panel',
        'priority' => 36,
    ) );

    // Single Post Breadcrumbs
    $wp_customize->add_setting( 'newsum_single_show_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_breadcrumbs', array(
        'label'    => __( 'Show Breadcrumbs Trail', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 0.5,
    ) ) );

    // Single Post Header Style
    $wp_customize->add_setting( 'newsum_single_header_style', array(
        'default'           => 'standard',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_single_header_style', array(
        'label'    => __( 'Article Header Style', 'newsum' ),
        'section'  => 'newsum_single_section',
        'type'     => 'select',
        'choices'  => array(
            'standard' => __( 'Standard (Title Above Image)', 'newsum' ),
            'overlay'  => __( 'Hero Overlay (Title Over Image)', 'newsum' ),
        ),
        'priority' => 1,
    ) );

    // Single Post Sidebar Layout
    $wp_customize->add_setting( 'newsum_single_sidebar', array(
        'default'           => 'right',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_single_sidebar', array(
        'label'    => __( 'Single Post Sidebar', 'newsum' ),
        'section'  => 'newsum_single_section',
        'type'     => 'select',
        'choices'  => array(
            'right' => __( 'Right Sidebar', 'newsum' ),
            'none'  => __( 'No Sidebar (Centered Content)', 'newsum' ),
        ),
        'priority' => 2,
    ) );

    // Sticky Sidebar Toggle
    $wp_customize->add_setting( 'newsum_sticky_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_sticky_sidebar', array(
        'label'    => __( 'Sticky Sidebar on Scroll', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 2.5,
    ) ) );

    // Reading Progress Bar
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_show_scroll_progress_single', array(
        'label'    => __( 'Show Reading Progress Bar', 'newsum' ),
        'section'  => 'newsum_single_section',
        'settings' => 'newsum_show_scroll_progress',
        'priority' => 3,
    ) ) );

    // Single Post Content Width
    $wp_customize->add_setting( 'newsum_single_content_width', array(
        'default'           => 800,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'newsum_single_content_width', array(
        'label'       => __( 'Content Max Width (px)', 'newsum' ),
        'description' => __( 'Narrower widths (700-800px) improve readability.', 'newsum' ),
        'section'     => 'newsum_single_section',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 500,
            'max'  => 1200,
            'step' => 10,
        ),
        'priority'    => 4,
    ) );

    // Show Featured Image
    $wp_customize->add_setting( 'newsum_single_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_featured_image', array(
        'label'    => __( 'Show Featured Image', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 5,
    ) ) );

    // In-Article Table of Contents
    $wp_customize->add_setting( 'newsum_single_show_toc', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_toc', array(
        'label'       => __( 'Show In-Article Table of Contents', 'newsum' ),
        'description' => __( 'Generates a sleek, collapsible outline before the first heading if article contains 2 or more headings.', 'newsum' ),
        'section'     => 'newsum_single_section',
        'priority'    => 5.5,
    ) ) );

    // Sticky Social Share Bar
    $wp_customize->add_setting( 'newsum_single_show_share', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_share', array(
        'label'    => __( 'Show Social Share Bar', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 6,
    ) ) );

    // Author Bio Box
    $wp_customize->add_setting( 'newsum_single_show_author_box', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_author_box', array(
        'label'    => __( 'Show Author Bio Box', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 7,
    ) ) );

    // Story Navigation (Next / Previous)
    $wp_customize->add_setting( 'newsum_single_show_post_nav', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_post_nav', array(
        'label'    => __( 'Show Next/Previous Story Cards', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 8,
    ) ) );

    // Post Tags / Topics Deck
    $wp_customize->add_setting( 'newsum_single_show_tags', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_tags', array(
        'label'    => __( 'Show Topics / Tags', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 9,
    ) ) );

    // Comments Section Toggle
    $wp_customize->add_setting( 'newsum_single_show_comments', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_comments', array(
        'label'    => __( 'Show Comments Section', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 10,
    ) ) );

    // Related Posts Toggle
    $wp_customize->add_setting( 'newsum_single_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_related_posts', array(
        'label'    => __( 'Show Related Stories Section', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 11,
    ) ) );

    // Related Posts Query By
    $wp_customize->add_setting( 'newsum_single_related_by', array(
        'default'           => 'category',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_single_related_by', array(
        'label'           => __( 'Related Stories Based On', 'newsum' ),
        'section'         => 'newsum_single_section',
        'type'            => 'select',
        'choices'         => array(
            'category' => __( 'Same Category', 'newsum' ),
            'tag'      => __( 'Same Tags / Topics', 'newsum' ),
        ),
        'priority'        => 12,
        'active_callback' => function( $control ) {
            $setting = $control->manager->get_setting( 'newsum_single_show_related_posts' );
            return $setting ? (bool) $setting->value() : true;
        },
    ) );

    // Related Posts Count
    $wp_customize->add_setting( 'newsum_single_related_count', array(
        'default'           => '3',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_single_related_count', array(
        'label'           => __( 'Related Stories Count', 'newsum' ),
        'section'         => 'newsum_single_section',
        'type'            => 'select',
        'choices'         => array(
            '3' => __( '3 Stories', 'newsum' ),
            '6' => __( '6 Stories', 'newsum' ),
        ),
        'priority'        => 13,
        'active_callback' => function( $control ) {
            $setting = $control->manager->get_setting( 'newsum_single_show_related_posts' );
            return $setting ? (bool) $setting->value() : true;
        },
    ) );

    // Meta: Category Badge
    $wp_customize->add_setting( 'newsum_single_show_category', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_category', array(
        'label'    => __( 'Show Category Badge', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 14,
    ) ) );

    // Meta: Author Byline
    $wp_customize->add_setting( 'newsum_single_show_author', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_author', array(
        'label'    => __( 'Show Author Byline', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 15,
    ) ) );

    // Meta: Publish Date
    $wp_customize->add_setting( 'newsum_single_show_date', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_date', array(
        'label'    => __( 'Show Publish Date', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 16,
    ) ) );

    // Meta: Reading Time
    $wp_customize->add_setting( 'newsum_single_show_reading_time', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_reading_time', array(
        'label'    => __( 'Show Estimated Reading Time', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 17,
    ) ) );

    // Meta: Comments Count Pill
    $wp_customize->add_setting( 'newsum_single_show_comments_count', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_single_show_comments_count', array(
        'label'    => __( 'Show Comments Count Pill', 'newsum' ),
        'section'  => 'newsum_single_section',
        'priority' => 18,
    ) ) );

    // Dummy settings for Edit Shortcuts (Pencil Icons)
    $wp_customize->add_setting( 'newsum_blog_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_blog_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_blog_section' ) );

    // Blog Upsell
    if ( ! class_exists( 'WPMatcha\\Pro\\Themes\\NewSum\\Module' ) ) {
        $wp_customize->add_setting( 'newsum_blog_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_blog_upsell', array(
            'label'       => __( 'Need Advanced Feed Layouts?', 'newsum' ),
            'section'     => 'newsum_blog_section',
            'upsell_text' => __( 'Unlock Auto-Load Next Post, Bento Box Feeds & Share Bar in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 99,
        ) ) );
    }

    $wp_customize->add_setting( 'newsum_single_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_single_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_single_section' ) );

    $wp_customize->add_setting( 'newsum_hero_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_hero_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_hero_section' ) );

    // Move default WordPress sections to the bottom
    if ( $wp_customize->get_section( 'title_tagline' ) ) {
        $wp_customize->get_section( 'title_tagline' )->priority = 100;
    }
    if ( $wp_customize->get_section( 'header_image' ) ) {
        $wp_customize->get_section( 'header_image' )->priority = 101;
    }
    if ( $wp_customize->get_section( 'background_image' ) ) {
        $wp_customize->get_section( 'background_image' )->priority = 102;
    }
    if ( $wp_customize->get_panel( 'nav_menus' ) ) {
        $wp_customize->get_panel( 'nav_menus' )->priority = 103;
    }
    if ( $wp_customize->get_panel( 'widgets' ) ) {
        $wp_customize->get_panel( 'widgets' )->priority = 104;
    }
    if ( $wp_customize->get_section( 'static_front_page' ) ) {
        $wp_customize->get_section( 'static_front_page' )->priority = 105;
    }
    if ( $wp_customize->get_section( 'custom_css' ) ) {
        $wp_customize->get_section( 'custom_css' )->priority = 106;
    }

    $wp_customize->add_setting( 'newsum_home_categories_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_home_categories_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_home_categories_section' ) );

    $wp_customize->add_setting( 'newsum_featured_grid_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_featured_grid_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_featured_grid_section' ) );

    $wp_customize->add_setting( 'newsum_masonry_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_masonry_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_masonry_section' ) );

    $wp_customize->add_setting( 'newsum_marquee_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_marquee_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_marquee_section' ) );

    $wp_customize->add_setting( 'newsum_quote_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_quote_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_interlude_section' ) );

    $wp_customize->add_setting( 'newsum_newsletter_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_newsletter_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_newsletter_section' ) );

    $wp_customize->add_setting( 'newsum_footer_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_footer_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_footer_section' ) );

    $wp_customize->add_setting( 'newsum_social_proof_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_social_proof_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_social_proof_section' ) );

    $wp_customize->add_setting( 'newsum_services_shortcut', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'newsum_services_shortcut', array( 'type' => 'hidden', 'section' => 'newsum_about_section' ) );

    // ── Selective Refresh ─────────────────────────────────────────
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'newsum_home_categories_title', array(
            'selector'        => '.category-section-title',
            'render_callback' => function() {
                return esc_html( get_theme_mod( 'newsum_home_categories_title', __( 'Trending Topics', 'newsum' ) ) );
            },
        ) );
        
        // Archive Masthead Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_blog_shortcut', array(
            'selector'        => '.archive-masthead',
            'settings'        => array( 'newsum_archive_show_desc' ),
            'render_callback' => '__return_false',
        ) );

        // Archive Pagination & Load More Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_archive_pagination_shortcut', array(
            'selector'        => '.archive-pagination',
            'settings'        => array( 'newsum_archive_pagination_type' ),
            'render_callback' => '__return_false',
        ) );

        // Archive Feed Cards Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_archive_feed_shortcut', array(
            'selector'        => '.archive-feed-container',
            'settings'        => array( 'newsum_archive_show_thumb' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Hero & Header Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_shortcut', array(
            'selector'        => '.article-hero',
            'settings'        => array( 'newsum_single_header_style' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Table of Contents Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_toc_shortcut', array(
            'selector'        => '.article-toc-box',
            'settings'        => array( 'newsum_single_show_toc' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Floating / Bottom Share Bar Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_share_shortcut', array(
            'selector'        => '.sticky-share',
            'settings'        => array( 'newsum_single_show_share' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Author Bio Box Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_author_shortcut', array(
            'selector'        => '.author-box',
            'settings'        => array( 'newsum_single_show_author_box' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Story Navigation Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_nav_shortcut', array(
            'selector'        => '.post-navigation-deck',
            'settings'        => array( 'newsum_single_show_post_nav' ),
            'render_callback' => '__return_false',
        ) );

        // Single Post Related Stories Shortcut
        $wp_customize->selective_refresh->add_partial( 'newsum_single_related_shortcut', array(
            'selector'        => '.related-posts-section',
            'settings'        => array( 'newsum_single_show_related_posts' ),
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_hero_shortcut', array(
            'selector'        => '.hero-section',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_home_categories_shortcut', array(
            'selector'        => '.category-bar-wrapper',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_featured_grid_shortcut', array(
            'selector'        => '.featured-grid-section',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_masonry_shortcut', array(
            'selector'        => '.posts-grid-section',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_marquee_shortcut', array(
            'selector'        => '.marquee-strip',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_quote_shortcut', array(
            'selector'        => '.interlude',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_newsletter_shortcut', array(
            'selector'        => '.newsletter-cta',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_footer_shortcut', array(
            'selector'        => '.site-footer',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_social_proof_shortcut', array(
            'selector'        => '.newsum-social-proof-bar',
            'render_callback' => '__return_false',
        ) );

        $wp_customize->selective_refresh->add_partial( 'newsum_services_shortcut', array(
            'selector'        => '.newsum-home-services',
            'render_callback' => '__return_false',
        ) );
    }

    // --- CUSTOM CTA / SPLIT SECTION (FREEMIUM BASE) ---
    $wp_customize->add_section( 'newsum_custom_banner_section', array(
        'title'    => __( 'Custom CTA / Split Section', 'newsum' ),
        'panel'    => 'newsum_homepage_panel',
        'priority' => 32, // Fit it naturally in the homepage panel
    ) );

    $wp_customize->add_setting( 'newsum_custom_banner_layout', array(
        'default'           => 'split_left',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_layout', array(
        'label'   => __( 'Layout Style', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'select',
        'choices' => array(
            'split_left'  => __( 'Split Screen (Image Left)', 'newsum' ),
            'split_right' => __( 'Split Screen (Image Right)', 'newsum' ),
            'grid_3'      => __( '3-Column Grid (Pro Only)', 'newsum' ),
            'overlay'     => __( 'Full-Width Overlay (Pro Only)', 'newsum' ),
        ),
        'priority' => 1,
    ) );

    if ( ! newsum_pro_is_active() ) {
        $wp_customize->add_setting( 'newsum_hero_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_hero_upsell', array(
            'section'     => 'newsum_hero_section',
            'upsell_text' => __( 'Unlock Hover-Reveal Matrix, Live Dynamic Wall & Extra Layouts in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 1,
        ) ) );

        $wp_customize->add_setting( 'newsum_custom_banner_upsell', array( 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( new NewSum_Customize_Upsell_Control( $wp_customize, 'newsum_custom_banner_upsell', array(
            'section'     => 'newsum_custom_banner_section',
            'upsell_text' => __( 'Unlock 3-Column Grid & Full-Width Overlay Layouts in Pro', 'newsum' ),
            'upsell_url'  => 'https://wpmatcha.com/wordpress-themes/newsum/',
            'priority'    => 2,
        ) ) );
    }

    $wp_customize->add_setting( 'newsum_custom_banner_section_title', array(
        'default'           => 'Featured',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_section_title', array(
        'label'       => __( 'Section Heading', 'newsum' ),
        'description' => __( 'Optional top-level heading (like "Trending Topics")', 'newsum' ),
        'section'     => 'newsum_custom_banner_section',
        'type'        => 'text',
    ) );

    // Add selective refresh for the section title so the customizer pencil icon appears
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'newsum_custom_banner_section_title', array(
            'selector'            => '.newsum-custom-cta .section-bar',
            'container_inclusive' => false,
            'render_callback'     => function() {
                $title = get_theme_mod( 'newsum_custom_banner_section_title', 'Featured' );
                return ! empty( $title ) ? '<h2>' . esc_html( $title ) . '</h2>' : '';
            },
        ) );
    }

    $wp_customize->add_setting( 'newsum_custom_banner_bg_color', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'newsum_custom_banner_bg_color', array(
        'label'       => __( 'Banner Card Background Color', 'newsum' ),
        'description' => __( 'Background color for the CTA banner card. Leave blank for theme default.', 'newsum' ),
        'section'     => 'newsum_custom_banner_section',
        'priority'    => 2,
    ) ) );

    // --- COLUMN 1 (Main) ---
    $wp_customize->add_setting( 'newsum_custom_banner_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'newsum_custom_banner_image', array(
        'label'    => __( 'Col 1: Background / Split Image', 'newsum' ),
        'section'  => 'newsum_custom_banner_section',
        'priority' => 10,
    ) ) );

    $wp_customize->add_setting( 'newsum_custom_banner_title', array(
        'default'           => 'Important Disclosure',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_title', array(
        'label'   => __( 'Col 1: Title', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'text',
        'priority' => 11,
    ) );

    $wp_customize->add_setting( 'newsum_custom_banner_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_text', array(
        'label'   => __( 'Col 1: Text Content / Hover Description', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'text',
        'priority' => 12,
    ) );

    $wp_customize->add_setting( 'newsum_custom_banner_btn_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_btn_text', array(
        'label'   => __( 'Col 1: Button Text', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'text',
        'priority' => 13,
    ) );

    $wp_customize->add_setting( 'newsum_custom_banner_btn_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_btn_url', array(
        'label'   => __( 'Col 1: Button URL', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'url',
        'priority' => 14,
    ) );

    $wp_customize->add_setting( 'newsum_custom_banner_btn_target', array(
        'default'           => '_blank',
        'sanitize_callback' => 'sanitize_key',
    ) );
    $wp_customize->add_control( 'newsum_custom_banner_btn_target', array(
        'label'   => __( 'Col 1: Button Link Target', 'newsum' ),
        'section' => 'newsum_custom_banner_section',
        'type'    => 'select',
        'choices' => array(
            '_blank' => __( 'New Tab', 'newsum' ),
            '_self'  => __( 'Same Tab', 'newsum' ),
        ),
        'priority' => 15,
    ) );

    // ==========================================
    // 7. SHORTCODE / AD BLOCK SECTION
    // ==========================================
    $wp_customize->add_section( 'newsum_shortcode_section', array(
        'title'    => __( 'Shortcode / Ad Block', 'newsum' ),
        'priority' => 38,
        'panel'    => 'newsum_homepage_panel',
    ) );

    $wp_customize->add_setting( 'newsum_homepage_shortcode_show', array(
        'default'           => true,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'newsum_homepage_shortcode_show', array(
        'label'       => __( 'Show Shortcode Section?', 'newsum' ),
        'section'     => 'newsum_shortcode_section',
        'type'        => 'checkbox',
        'priority'    => 5,
    ) );

    $wp_customize->add_setting( 'newsum_homepage_shortcode', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'newsum_homepage_shortcode', array(
        'label'       => __( 'Custom Shortcode', 'newsum' ),
        'description' => __( 'Paste any shortcode here (e.g., [matcha_ad id="1"] or a Mailchimp form). It will render on the homepage.', 'newsum' ),
        'section'     => 'newsum_shortcode_section',
        'type'        => 'text',
        'priority'    => 10,
    ) );

    $wp_customize->add_setting( 'newsum_homepage_shortcode_fullwidth', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'newsum_homepage_shortcode_fullwidth', array(
        'label'       => __( 'Make Full Width?', 'newsum' ),
        'description' => __( 'Check this if your shortcode provides its own container and needs to span edge-to-edge.', 'newsum' ),
        'section'     => 'newsum_shortcode_section',
        'type'        => 'checkbox',
        'priority'    => 20,
    ) );

    // ==========================================
    // 8. SOCIAL PROOF / BRAND LOGOS
    // ==========================================
    $wp_customize->add_section( 'newsum_social_proof_section', array(
        'title'       => __( 'Social Proof / Brand Logos', 'newsum' ),
        'description' => __( 'Upload up to 4 brand logos to display in the Social Proof bar on the homepage. If no logos are uploaded, it automatically falls back to your About Page stat counters.', 'newsum' ),
        'priority'    => 39,
        'panel'       => 'newsum_homepage_panel',
    ) );

    $wp_customize->add_setting( 'newsum_social_proof_show', array(
        'default'           => false,
        'sanitize_callback' => 'newsum_sanitize_checkbox',
    ) );
    $wp_customize->add_control( new NewSum_Toggle_Control( $wp_customize, 'newsum_social_proof_show', array(
        'label'       => __( 'Show Social Proof Bar?', 'newsum' ),
        'description' => __( 'Enable to display the Social Proof / Brand Logos bar on your homepage.', 'newsum' ),
        'section'     => 'newsum_social_proof_section',
        'priority'    => 5,
    ) ) );

    $wp_customize->add_setting( 'newsum_social_proof_title', array(
        'default'           => 'Our Partners',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'newsum_social_proof_title', array(
        'label'       => __( 'Section Heading', 'newsum' ),
        'description' => __( 'Optional top-level heading above the brand logos.', 'newsum' ),
        'section'     => 'newsum_social_proof_section',
        'type'        => 'text',
        'priority'    => 8,
    ) );

    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting( 'newsum_social_proof_logo_' . $i, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'newsum_social_proof_logo_' . $i, array(
            'label'    => sprintf( __( 'Brand Logo %d', 'newsum' ), $i ),
            'section'  => 'newsum_social_proof_section',
            'priority' => $i * 10,
        ) ) );
    }

}
add_action( 'customize_register', 'newsum_customize_register' );

/**
 * Sanitize select/radio inputs
 */
function newsum_sanitize_select( $input, $setting ) {
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Dynamic CSS output based on customizer settings
 */
function newsum_dynamic_css() {
    // 1. Typography Settings
    $heading_font   = get_theme_mod( 'newsum_heading_font', 'Playfair Display' );
    $body_font      = get_theme_mod( 'newsum_body_font', 'Inter' );
    $custom_heading = get_theme_mod( 'newsum_custom_heading_font', '' );
    $custom_body    = get_theme_mod( 'newsum_custom_body_font', '' );
    $heading_scale  = get_theme_mod( 'newsum_heading_scale', 100 );
    $body_font_size = get_theme_mod( 'newsum_body_font_size', 16 );

    // 2. Layout & Width Settings
    $container_style = get_theme_mod( 'newsum_container_style', 'contained' );
    $content_width   = get_theme_mod( 'newsum_content_width', 1800 );
    $image_radius    = get_theme_mod( 'newsum_global_image_radius', 0 );
    $density         = get_theme_mod( 'newsum_layout_density', 'standard' );
    $logo_height     = get_theme_mod( 'newsum_logo_height', 40 );
    $sticky_header   = get_theme_mod( 'newsum_sticky_header', true );

    // 3. Color Settings
    $palette_choice = get_theme_mod( 'newsum_color_palette', 'crimson' );
    $custom_accent  = get_theme_mod( 'newsum_accent_color', '#ff2a2a' );

    $palette_map = array(
        'crimson' => '#ff2a2a',
        'cobalt'  => '#2563eb',
        'emerald' => '#059669',
        'amber'   => '#d97706',
        'violet'  => '#7c3aed',
        'slate'   => '#475569',
    );

    $accent_color = ( 'custom' === $palette_choice ) ? $custom_accent : ( $palette_map[ $palette_choice ] ?? '#ff2a2a' );

    $scale_factor = $heading_scale / 100;

    $css = ':root {';

    // Accent Color
    $css .= '--accent-color: ' . esc_attr( $accent_color ) . ';';
    $hex = ltrim( $accent_color, '#' );
    if ( 3 === strlen( $hex ) ) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    if ( 6 === strlen( $hex ) ) {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
        $css .= '--accent-color-rgb: ' . "$r, $g, $b" . ';';
    }
    $css .= '--accent-subtle: color-mix(in srgb, var(--accent-color) 12%, transparent);';
    $css .= '--accent-hover: color-mix(in srgb, var(--accent-color) 7%, transparent);';

    // Heading Font
    if ( 'System / Uploaded Font' === $heading_font ) {
        if ( ! empty( $custom_heading ) ) {
            $css .= '--font-heading: "' . esc_attr( $custom_heading ) . '", serif;';
        }
    } elseif ( ! empty( $heading_font ) ) {
        $css .= '--font-heading: "' . esc_attr( $heading_font ) . '", serif;';
    }

    // Body Font
    if ( 'System / Uploaded Font' === $body_font ) {
        if ( ! empty( $custom_body ) ) {
            $css .= '--font-body: "' . esc_attr( $custom_body ) . '", sans-serif;';
        }
    } elseif ( ! empty( $body_font ) ) {
        $css .= '--font-body: "' . esc_attr( $body_font ) . '", sans-serif;';
    }

    // Container Max Width
    if ( 'fluid' === $container_style ) {
        $css .= '--container-max: 100%;';
    } else {
        $css .= '--container-max: ' . absint( $content_width ) . 'px;';
    }

    // Layout Spacing Density
    if ( 'compact' === $density ) {
        $css .= '--space-xs: 0.35rem; --space-sm: 0.75rem; --space-md: 1.25rem; --space-lg: 2.25rem; --space-xl: 4.5rem;';
    } elseif ( 'relaxed' === $density ) {
        $css .= '--space-xs: 0.75rem; --space-sm: 1.5rem; --space-md: 3rem; --space-lg: 5.5rem; --space-xl: 9rem;';
    }

    $css .= '}';

    // Active & Hover Navigation styling with dynamic accent color
    $css .= '.nav-menu>li.current-menu-item>a, .nav-menu>li.current_page_item>a, .nav-menu>li.current-menu-ancestor>a { color: var(--accent-color); background: color-mix(in srgb, var(--accent-color) 12%, transparent); }';
    $css .= '.nav-menu>li>a:hover, .main-nav .nav-item>a:hover { color: var(--accent-color); background: color-mix(in srgb, var(--accent-color) 8%, transparent); }';

    // Explicit Font Assignment to Elements
    $css .= 'h1, h2, h3, h4, h5, h6, .entry-title, .single-title, .article-title, .hero-title, .bento-title, .card-title, .tile-title, .section-title, .about-hero-title, .value-heading, .brand-logo { font-family: var(--font-heading) !important; }';
    $css .= 'body, p, li, .article-body p, .single-content p, .about-content-body p, .tile-excerpt, .bento-desc { font-family: var(--font-body); font-size: ' . absint( $body_font_size ) . 'px; }';

    // Image Radius
    if ( $image_radius > 0 ) {
        $css .= 'img, .tile-img, .bento-tile, .card-thumb, .media-cover-img, .about-featured-img, .story-card, .value-card, .masthead-member-card, .contact-info-card { border-radius: ' . absint( $image_radius ) . 'px !important; }';
    }

    // Heading scale
    if ( 100 !== (int) $heading_scale ) {
        $css .= 'h1, .hero-title, .about-hero-title { font-size: calc(clamp(2rem, 4vw, 3rem) * ' . esc_attr( $scale_factor ) . ') !important; }';
        $css .= 'h2, .section-title, .bento-title { font-size: calc(clamp(1.5rem, 3vw, 2.25rem) * ' . esc_attr( $scale_factor ) . ') !important; }';
        $css .= 'h3, .card-title, .value-heading { font-size: calc(clamp(1.15rem, 2vw, 1.5rem) * ' . esc_attr( $scale_factor ) . ') !important; }';
    }

    // Logo height
    $css .= 'img.custom-logo { max-height: ' . absint( $logo_height ) . 'px !important; width: auto !important; height: auto !important; max-width: 100% !important; }';
    $css .= '.site-header .logo { font-size: ' . absint( $logo_height ) . 'px; }';

    // Sticky header
    if ( ! $sticky_header ) {
        $css .= '.site-header { position: relative !important; }';
    }

    if ( ! empty( $css ) ) {
        wp_add_inline_style( 'newsum-style', $css );
    }
}
add_action( 'wp_enqueue_scripts', 'newsum_dynamic_css', 20 );

/**
 * Checkbox sanitization callback
 */
function newsum_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}


/**
 * Add dropdown toggle buttons to navigation menus.
 */
function newsum_nav_menu_dropdown_button( $item_output, $item, $depth, $args ) {
    if ( in_array( 'menu-item-has-children', $item->classes ) ) {
        $arrow  = '<span class="nav-arrow" aria-hidden="true"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>';
        $button = '<button class="dropdown-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Toggle drop-down', 'newsum' ) . '"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></button>';
        $item_output = str_replace( '</a>', ' ' . $arrow . '</a>' . $button, $item_output );
    }
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'newsum_nav_menu_dropdown_button', 10, 4 );

/**
 * Sanitize ad code — allows scripts and iframes for ad networks
 */
function newsum_sanitize_ad_code( $input ) {
    return current_user_can( 'unfiltered_html' ) ? $input : wp_kses_post( $input );
}

/**
 * Sanitize newsletter action URL by extracting it from HTML embed code if present.
 */
function newsum_sanitize_newsletter_action( $input ) {
    // If it looks like a shortcode, return it sanitized as text (allowing brackets)
    if ( strpos( $input, '[' ) !== false && strpos( $input, ']' ) !== false ) {
        return sanitize_text_field( $input );
    }
    // If it looks like HTML, try to extract the action attribute
    if ( strpos( $input, '<form' ) !== false ) {
        if ( preg_match( '/action=["\']([^"\']+)["\']/i', $input, $matches ) ) {
            return esc_url_raw( $matches[1] );
        }
    }
    // Otherwise, assume it's a direct URL
    return esc_url_raw( $input );
}

/**
 * Customizer UI Styles — Professional controls
 */
function newsum_customizer_styles() {
    ?>
    <style>
        /* Separate custom panels from default WordPress panels */
        #accordion-section-title_tagline {
            margin-top: 24px !important;
            border-top: 1px solid #dcdcde !important;
        }

        /* 🎨 NEWSUM CUSTOMIZER UI 🎨 */
        .customize-control { margin-bottom: 14px; }
        .customize-control-range { position: relative; }
        .customize-control-range input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #ddd;
            outline: none;
            margin: 10px 0;
        }
        .customize-control-range input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #007cba;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .customize-control-range input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #007cba;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        
        /* Section descriptions */
        .customize-section-description {
            font-size: 12px;
            color: #666;
            padding: 12px 16px;
            background: #f9f9f9;
            border-bottom: 1px solid #eee;
            margin: -12px -12px 12px;
        }

        /* Control descriptions */
        .customize-control .description {
            font-style: normal;
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }

        /* Checkbox group headers */
        .customize-control-checkbox label {
            font-weight: 600;
        }

        /* Textareas */
        .customize-control textarea {
            font-family: monospace;
            font-size: 12px;
            min-height: 80px;
        }

        /* ==========================================================
           Blogsy & Neve Inspired Customizer Controls
           ========================================================== */
        #customize-theme-controls .customize-control {
            margin-bottom: 0 !important;
            padding: 9px 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            position: relative;
            overflow: visible !important;
        }

        #customize-theme-controls .customize-control:last-child {
            border-bottom: none !important;
        }

        /* Reset legacy card box styles */
        .newsum-control-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        /* Blogsy-style Toggle Control Row */
        .newsum-toggle-wrapper {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            gap: 12px;
        }

        .newsum-toggle-title-wrap {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            flex: 1 !important;
            min-width: 0 !important;
        }

        .newsum-toggle-title-wrap .customize-control-title {
            margin-bottom: 0 !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #111827 !important;
            line-height: 1.35 !important;
            cursor: pointer !important;
        }

        /* Blogsy (?) SVG Tooltip Icon */
        .newsum-help-icon {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #94a3b8 !important;
            margin-left: 2px !important;
            position: relative !important;
            cursor: pointer !important;
            transition: color 0.15s ease !important;
            flex-shrink: 0 !important;
            vertical-align: middle !important;
            width: 16px !important;
            height: 16px !important;
        }

        .newsum-help-icon svg {
            width: 14px !important;
            height: 14px !important;
            stroke: currentColor !important;
        }

        .newsum-help-icon:hover,
        .newsum-help-icon:focus {
            color: #2271b1 !important;
            outline: none !important;
        }

        .newsum-help-icon .newsum-tooltip {
            display: none !important;
            position: absolute !important;
            bottom: calc(100% + 8px) !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            background: #111827 !important;
            color: #f1f5f9 !important;
            font-size: 11px !important;
            font-weight: 400 !important;
            line-height: 1.45 !important;
            padding: 6px 10px !important;
            border-radius: 4px !important;
            white-space: normal !important;
            width: max-content !important;
            max-width: 220px !important;
            z-index: 999999 !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25) !important;
            pointer-events: none !important;
            text-align: left !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif !important;
        }

        .newsum-help-icon .newsum-tooltip:after {
            content: '' !important;
            position: absolute !important;
            top: 100% !important;
            left: 50% !important;
            margin-left: -5px !important;
            border-width: 5px !important;
            border-style: solid !important;
            border-color: #111827 transparent transparent transparent !important;
        }

        .newsum-help-icon:hover .newsum-tooltip,
        .newsum-help-icon:focus .newsum-tooltip {
            display: block !important;
        }

        /* Blogsy-style Toggle Pill Switch */
        .newsum-toggle {
            display: flex !important;
            align-items: center !important;
            margin-left: auto !important;
            flex-shrink: 0 !important;
        }

        .newsum-toggle input[type="checkbox"] {
            display: none !important;
        }

        .newsum-toggle-switch-label {
            display: flex !important;
            align-items: center !important;
            margin: 0 !important;
            cursor: pointer !important;
        }

        .newsum-toggle .newsum-toggle-switch {
            display: block !important;
            width: 32px !important;
            height: 18px !important;
            background-color: #96b0c3 !important;
            border-radius: 9px !important;
            padding: 2px !important;
            box-sizing: border-box !important;
            transition: background-color 0.2s ease !important;
            position: relative !important;
        }

        .newsum-toggle .newsum-toggle-switch:after {
            content: '' !important;
            display: block !important;
            width: 14px !important;
            height: 14px !important;
            border-radius: 50% !important;
            background: #ffffff !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
            transition: transform 0.15s ease !important;
            transform: translateX(0) !important;
        }

        .newsum-toggle input[type="checkbox"]:checked + label .newsum-toggle-switch {
            background-color: #2271b1 !important;
        }

        .newsum-toggle input[type="checkbox"]:checked + label .newsum-toggle-switch:after {
            transform: translateX(14px) !important;
        }

        .newsum-toggle:hover .newsum-toggle-switch {
            background-color: #7698b1 !important;
        }

        .newsum-toggle input[type="checkbox"]:checked + label:hover .newsum-toggle-switch {
            background-color: #1a5c91 !important;
        }

        /* Neve & Blogsy Style Group Heading Banner */
        .customize-control-newsum_heading {
            border-bottom: none !important;
            padding: 0 !important;
            margin: 16px -12px 4px -12px !important;
        }

        .newsum-heading-banner {
            background: #f0f0f1;
            border-top: 1px solid #dcdcde;
            border-bottom: 1px solid #dcdcde;
            padding: 10px 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .newsum-heading-title {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #334155;
            line-height: 1.3;
        }

        .newsum-heading-desc {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: #64748b;
            margin-top: 2px;
            line-height: 1.35;
        }

        .newsum-description-hidden {
            display: none !important;
        }

        #customize-theme-controls,
        #customize-theme-controls .customize-pane-child,
        #customize-theme-controls .customize-control {
            overflow: visible !important;
        }

        /* Compact Neve-Style Form Controls (Selects, Text, Number) */
        #customize-theme-controls .customize-control-title {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            margin-bottom: 4px !important;
        }

        #customize-theme-controls .customize-control select,
        #customize-theme-controls .customize-control input[type="text"],
        #customize-theme-controls .customize-control input[type="number"],
        #customize-theme-controls .customize-control input[type="url"] {
            width: 100% !important;
            height: 30px !important;
            min-height: 30px !important;
            padding: 3px 8px !important;
            border-radius: 4px !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-size: 12px !important;
            box-shadow: none !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }

        #customize-theme-controls .customize-control select:focus,
        #customize-theme-controls .customize-control input[type="text"]:focus,
        #customize-theme-controls .customize-control input[type="number"]:focus,
        #customize-theme-controls .customize-control input[type="url"]:focus {
            border-color: #2271b1 !important;
            box-shadow: 0 0 0 1px #2271b1 !important;
            outline: none !important;
        }

        #customize-theme-controls .customize-control textarea {
            font-size: 12px !important;
            padding: 6px 8px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px !important;
            box-shadow: none !important;
            min-height: 60px;
        }

        /* Compact Range Slider */
        .customize-control input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            outline: none;
            margin: 8px 0;
        }

        .customize-control input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #2271b1;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: transform 0.1s;
        }

        .newsum-range-value {
            display: inline-block;
            background: #2271b1;
            color: #fff;
            border-radius: 3px;
            padding: 1px 7px;
            font-size: 11px;
            font-weight: 600;
            margin-left: auto;
            min-width: 30px;
            text-align: center;
            line-height: 18px;
        }
        /* Premium Radio Image Control */
        .newsum-radio-image-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
        }
        .newsum-radio-image-label {
            flex: 1;
            cursor: pointer;
        }
        .newsum-radio-image-label input[type="radio"] {
            display: none;
        }
        .newsum-radio-image-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 4px;
            border: 2px solid #e2e4e7;
            border-radius: 6px;
            background: #fff;
            color: #646970;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            text-align: center;
        }
        .newsum-radio-image-btn svg {
            width: 20px;
            height: 20px;
            margin-bottom: 6px;
        }
        .newsum-radio-image-label-text {
            font-size: 10px;
            line-height: 1.2;
            font-weight: 500;
        }
        .newsum-radio-image-label input[type="radio"]:checked + .newsum-radio-image-btn {
            border-color: #2271b1;
            background: #f0f6fc;
            color: #2271b1;
        }
        .newsum-radio-image-label:hover .newsum-radio-image-btn {
            border-color: #c3c4c7;
        }

        /* Enhanced Color Palette */
        .newsum-palette-swatches {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
        }
        .newsum-palette-option {
            cursor: pointer;
            display: inline-block;
        }
        .newsum-palette-option input[type="radio"] {
            display: none;
        }
        .newsum-palette-color {
            display: block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid transparent;
            box-shadow: 0 0 0 1px #e2e4e7, 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            position: relative;
        }
        .newsum-palette-option input[type="radio"]:checked + .newsum-palette-color {
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #2271b1;
            transform: scale(1.05);
        }
        .newsum-palette-option:hover .newsum-palette-color {
            box-shadow: 0 0 0 2px #fff, 0 0 0 3px #8c8f94;
        }
        
    </style>
    <?php
}
add_action( 'customize_controls_print_styles', 'newsum_customizer_styles' );

/**
 * Customizer Tooltip & Range Slider JS (Blogsy/Neve Inspired)
 */
function newsum_customizer_scripts() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        function processCustomizerControls() {
            // 1. Process Range Sliders
            $('input[type="range"]:not(.newsum-range-processed)').each(function() {
                var $input = $(this);
                $input.addClass('newsum-range-processed');
                var $display = $('<span class="newsum-range-value"></span>');
                
                var $control = $input.closest('.customize-control');
                var $title = $control.find('.customize-control-title').first();
                
                if ($title.length) {
                    $title.append($display);
                }
                
                $display.text($input.val());
                $input.on('input', function() {
                    $display.text($(this).val());
                });
            });

            // 2. Process Descriptions into Blogsy-style SVG (?) Tooltips
            $('.customize-control .description:not(.newsum-tooltip-processed)').each(function() {
                var $desc = $(this);
                
                // Skip upsells, messages, and controls that already have newsum-help-icon
                var $control = $desc.closest('.customize-control');
                if ($control.hasClass('customize-control-newsum-upsell') || 
                    $control.hasClass('customize-control-newsum_message') ||
                    $control.find('.newsum-help-icon').length > 0 ||
                    $desc.closest('.newsum-upsell-wrapper, .newsum-customizer-message, .newsum-customizer-upsell').length > 0) {
                    $desc.addClass('newsum-tooltip-processed');
                    return;
                }
                
                $desc.addClass('newsum-tooltip-processed');
                
                var text = $desc.text().trim();
                if (text === '') return;
                
                var $title = $control.find('.customize-control-title').first();

                if ($title.length && !$title.find('.newsum-help-icon').length) {
                    var $icon = $('<span class="newsum-help-icon" tabindex="0" role="tooltip" aria-label="' + text.replace(/"/g, '&quot;') + '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg><span class="newsum-tooltip">' + $('<div>').text(text).html() + '</span></span>');
                    $title.append($icon);
                    $desc.addClass('newsum-description-hidden');
                }
            });
        }

        // Run on initial load
        processCustomizerControls();

        // Watch for dynamically loaded panels
        var tooltipTimer;
        var observer = new MutationObserver(function() {
            clearTimeout(tooltipTimer);
            tooltipTimer = setTimeout(processCustomizerControls, 100);
        });
        var targetNode = document.getElementById('customize-theme-controls');
        if (targetNode) {
            observer.observe(targetNode, { childList: true, subtree: true });
        }
        
        // Also run when Customizer is ready
        if (typeof wp !== 'undefined' && wp.customize) {
            wp.customize.bind('ready', function() {
                processCustomizerControls();
            });
        }
    });
    </script>
    <?php
}
add_action( 'customize_controls_print_footer_scripts', 'newsum_customizer_scripts' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function newsum_customize_preview_js() {
	wp_enqueue_script( 'newsum-customizer-preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-preview', 'jquery' ), NEWSUM_VERSION, true );
}
add_action( 'customize_preview_init', 'newsum_customize_preview_js' );

/**
 * Fix Customizer Edit Shortcut Icons Position
 */
function newsum_customize_preview_styles() {
    ?>
    <style>
        .customize-partial-edit-shortcut, .widget .customize-partial-edit-shortcut {
            margin: -1px 0 0 35px !important;
        }
    </style>
    <?php
}
add_action( 'customize_preview_init', function() {
    add_action( 'wp_head', 'newsum_customize_preview_styles', 999 );
} );

add_action( 'customize_register', 'newsum_customize_register' );
