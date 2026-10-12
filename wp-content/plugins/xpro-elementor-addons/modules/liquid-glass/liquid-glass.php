<?php
/**
 * Liquid Glass Extension - Fixed & Optimized
 *
 * @package XproElementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined('ABSPATH') || die();

class Xpro_Elementor_Liquid_Glass {
    
    private static $instance = null;
    private static $has_glass = false;
    private static $enqueued = false;

    public static function instance() {

        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;

    }

    public function __construct() {
        // Register controls
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'register_controls'], 10);
        add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'register_controls'], 10);
        add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register_controls'], 10);
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10);

        // Detect usage and enqueue
        	// Detect usage only on the **frontend** (not in editor)
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			add_action( 'elementor/frontend/before_render', [ $this, 'detect_usage' ], 10 );
		}

        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_assets'], 99);
        add_action('wp_enqueue_scripts', [$this, 'maybe_enqueue_assets'], 99);
        
        // Add inline SVG immediately
        add_action('wp_footer', [$this, 'print_svg_filters'], 1);
    }

    public function detect_usage($element) {

        if (self::$has_glass) return;
        
        if ('yes' === $element->get_settings_for_display('xpro_liquid_glass_enable')) {
            self::$has_glass = true;
        }
        
    }

    public function maybe_enqueue_assets() {
        if (self::$enqueued) return;
        
        // Check if current post has glass enabled
        if (self::$has_glass || $this->has_glass_in_content()) {
            $this->enqueue_assets();
        }
    }

    public function enqueue_assets() {
        if (self::$enqueued) return;
        self::$enqueued = true;

        // CSS
        wp_enqueue_style(
            'xpro-liquid-glass',
            XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/liquid-glass/css/liquid-glass.css',
            [],
            XPRO_ELEMENTOR_ADDONS_VERSION
        );

        // JS
        wp_enqueue_script(
            'xpro-liquid-glass',
            XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/liquid-glass/js/liquid-glass.min.js',
            ['jquery'],
            XPRO_ELEMENTOR_ADDONS_VERSION,
            true
        );
    }

    private function has_glass_in_content() {
        if (!is_singular()) return false;
        
        $post_id = get_the_ID();
        if (!$post_id) return false;

        // Check Elementor data
        $document = \Elementor\Plugin::$instance->documents->get($post_id);
        if ($document) {
            $data = $document->get_elements_data();
            if ($this->search_elements($data)) return true;
        }

        // Fallback: Check raw content for classes (for cached pages)
        $content = get_post_field('post_content', $post_id);
        if (strpos($content, 'xpro-glass-effect__glass') !== false) {
            return true;
        }

        return false;
    }

    private function search_elements($elements) {
        foreach ($elements as $element) {
            if (isset($element['settings']['xpro_liquid_glass_enable']) && 
                'yes' === $element['settings']['xpro_liquid_glass_enable']) {
                return true;
            }
            if (!empty($element['elements'])) {
                if ($this->search_elements($element['elements'])) return true;
            }
        }
        return false;
    }

    public function print_svg_filters() {
        // Only print if glass is used
        if (!self::$has_glass && !$this->has_glass_in_content()) return;
        
        $filters = [
            ['id' => 1, 'freq' => '0.004 0.004', 'scale' => 125],
            ['id' => 2, 'freq' => '0.007 0.007', 'scale' => 111],
            ['id' => 3, 'freq' => '0.02 0.02', 'scale' => 81],
            ['id' => 4, 'freq' => '0.015 0.015', 'scale' => 179],
        ];
        
        echo '<!-- Xpro Liquid Glass SVG Filters -->';
        foreach ($filters as $filter) {
            printf(
                '<svg class="xpro-glass-svg-%d" xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="position:absolute;overflow:hidden;visibility:hidden" aria-hidden="true">
                <defs>
                    <filter id="glass-distortion%d" x="0%%" y="0%%" width="100%%" height="100%%">
                        <feTurbulence type="fractalNoise" baseFrequency="%s" numOctaves="2" seed="92" result="noise"/>
                        <feGaussianBlur in="noise" stdDeviation="2" result="blurred"/>
                        <feDisplacementMap in="SourceGraphic" in2="blurred" scale="%d" xChannelSelector="R" yChannelSelector="G"/>
                    </filter>
                </defs>
                </svg>',
                 absint($filter['id']),
                absint($filter['id']),
                esc_attr($filter['freq']),
                absint($filter['scale'])
            );
        }
    }
public function register_controls($element) {

    if (in_array(get_post_type(), array('xpro-themer', 'xpro_content'))) {
        return;
    }

    $element->start_controls_section(
        'section_xpro_liquid_glass',
        array(
            'label' => __('Liquid Glass', 'xpro-elementor-addons'),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        )
    );

    $element->add_control(
        'xpro_liquid_glass_enable',
        array(
            'label'        => __('Enable Liquid Glass', 'xpro-elementor-addons'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'prefix_class' => 'xpro-glass-enabled--',
            'render_type'  => 'template',
            'frontend_available' => true,
        )
    );

    $element->add_control(
        'xpro_liquid_glass_effect',
        array(
            'label'   => __('Effect Style', 'xpro-elementor-addons'),
            'type'    => Controls_Manager::SELECT,
            'options' => array(
                'glass1'  => __('Frosted Light', 'xpro-elementor-addons'),
                'glass2'  => __('Frosted Heavy', 'xpro-elementor-addons'),
                'glass3'  => __('Liquid Ripple 1', 'xpro-elementor-addons'),
                'glass4'  => __('Liquid Ripple 2', 'xpro-elementor-addons'),
                'glass5'  => __('Liquid Ripple 3', 'xpro-elementor-addons'),
                'glass6'  => __('Liquid Ripple 4', 'xpro-elementor-addons'),
                'glass7'  => __('Crystal Bright', 'xpro-elementor-addons'),
                'glass8'  => __('Crystal Dark', 'xpro-elementor-addons'),
                'glass9'  => __('Saturated Glow', 'xpro-elementor-addons'),
                'glass10' => __('High Contrast', 'xpro-elementor-addons'),
            ),
            'prefix_class' => 'xpro-glass-effect__',
            'default'      => 'glass1',
            'render_type'  => 'template',
            'frontend_available' => true,
            'condition'    => array(
                'xpro_liquid_glass_enable' => 'yes',
            ),
        )
    );

    $element->add_control(
        'xpro_liquid_glass_shadow',
        array(
            'label'   => __('Inner Shadow', 'xpro-elementor-addons'),
            'type'    => Controls_Manager::SELECT,
            'options' => array(
                'none'     => __('None', 'xpro-elementor-addons'),
                'shadow1'  => __('Soft Glow', 'xpro-elementor-addons'),
                'shadow2'  => __('Bright Glow', 'xpro-elementor-addons'),
                'shadow3'  => __('Intense Glow', 'xpro-elementor-addons'),
                'shadow4'  => __('Top Shine', 'xpro-elementor-addons'),
                'shadow5'  => __('Full Bloom', 'xpro-elementor-addons'),
                'shadow6'  => __('Bottom Shine', 'xpro-elementor-addons'),
                'shadow7'  => __('Deep Inset', 'xpro-elementor-addons'),
                'shadow8'  => __('Subtle Top', 'xpro-elementor-addons'),
                'shadow9'  => __('Double Layer', 'xpro-elementor-addons'),
                'shadow10' => __('Gradient Inset', 'xpro-elementor-addons'),
            ),
            'prefix_class' => 'xpro-glass-shadow__',
            'default'      => 'none',
            'render_type'  => 'template',
            'frontend_available' => true,
            'condition'    => array(
                'xpro_liquid_glass_enable' => 'yes',
            ),
        )
    );

    $element->end_controls_section();
}

}

Xpro_Elementor_Liquid_Glass::instance();