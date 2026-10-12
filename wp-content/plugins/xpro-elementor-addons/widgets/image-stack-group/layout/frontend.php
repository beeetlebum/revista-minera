    <?php
    defined( 'ABSPATH' ) || die();
    use Elementor\Icons_Manager;

    if ( empty( $settings['images'] ) ) {
        return;
    }

    // Security: Validate and sanitize settings
    $group_layout            = sanitize_text_field( $settings['group_layout_style'] );
    $hover_animation         = sanitize_text_field( $settings['hover_animation'] );
    $tooltip_enabled         = filter_var( $settings['tooltip_global_enable'], FILTER_VALIDATE_BOOLEAN );
    $default_tooltip_position = sanitize_text_field( $settings['tooltip_global_position'] );

    // Unique wrapper ID
    // $group_id = wp_unique_id( 'xpro-image-stacked-group-' );
    $group_id = 'xpro-image-stacked-group-' . $this->get_id();

    // Gradient border CSS
    if ( ! empty( $settings['gradient_border'] ) && 'yes' === $settings['gradient_border'] ) {

        $gradient_start = sanitize_hex_color( $settings['gradient_border_start'] );
        $gradient_end   = sanitize_hex_color( $settings['gradient_border_end'] );

        if ( $gradient_start && $gradient_end ) {

            $gradient_css = '
            <style>
                #' . esc_attr( $group_id ) . ' .xpro-image-stacked-item {
                    border: none !important;
                    position: relative;
                }

                #' . esc_attr( $group_id ) . ' .xpro-image-stacked-item::before {
                    content: "";
                    position: absolute;
                    top: -2px;
                    left: -2px;
                    right: -2px;
                    bottom: -2px;
                    background: linear-gradient(135deg, ' . esc_attr( $gradient_start ) . ', ' . esc_attr( $gradient_end ) . ');
                    border-radius: inherit;
                    z-index: -1;
                }
            </style>';

            echo wp_kses(
                $gradient_css,
                array(
                    'style' => array(),
                )
            );
        }
    }
    ?>

    <div id="<?php echo esc_attr( $group_id ); ?>" class="xpro-image-stacked-group <?php echo esc_attr( $group_layout ); ?> hover-<?php echo esc_attr( $hover_animation ); ?>" >
        <?php
        foreach ($settings['images'] as $index => $item) :
            
            $item_key = 'xpro-image-stacked-item-' . $item['_id'];
            $style_variation = !empty($item['item_style_variation']) ? sanitize_text_field($item['item_style_variation']) : 'default';

            $this->add_render_attribute(
                $item_key,
                'class',
                array(
                    'xpro-image-stacked-item',
                    'elementor-repeater-item-' . esc_attr($item['_id']),
                    'style-' . esc_attr($style_variation),
                )
            );

            // Security: Sanitize tooltip text
            $tooltip_text = !empty($item['tooltip']) ? sanitize_text_field($item['tooltip']) : '';
            
            if ($tooltip_enabled && !empty($tooltip_text)) {

                $tooltip_position = !empty($item['tooltip_position'])
                    ? sanitize_text_field($item['tooltip_position'])
                    : $default_tooltip_position;

                $this->add_render_attribute(
                    $item_key,
                    array(
                        'data-tooltip' => esc_attr($tooltip_text),
                        'data-flow'    => esc_attr($tooltip_position),
                    )
                );
            }

            // Security: Validate URL before adding link attributes
            $link_url = !empty($item['link']['url']) ? esc_url_raw($item['link']['url']) : '';
            
            if (!empty($link_url) && filter_var($link_url, FILTER_VALIDATE_URL)) {

                $this->add_link_attributes($item_key, $item['link']);

                $tag = 'a';

            } else {

                $tag = 'span';
            }

            ?>

            <<?php echo esc_attr($tag); ?>
                <?php echo wp_kses_data( $this->get_render_attribute_string( $item_key ) ) ?>>
                <?php
                if ('icon' === $item['media_type']) :

                    ?>

                    <span class="xpro-image-stacked-icon">

                        <?php
                        // Security: Validate icon rendering
                        if (!empty($item['selected_icon']['value'])) {
                            // Sanitize icon library and value
                            $icon_library = isset($item['selected_icon']['library']) ? sanitize_text_field($item['selected_icon']['library']) : '';
                            $icon_value = sanitize_text_field($item['selected_icon']['value']);
                            
                            if (!empty($icon_value)) {
                                Icons_Manager::render_icon(
                                    $item['selected_icon'],
                                    array(
                                        'aria-hidden' => 'true',
                                        'aria-label' => !empty($tooltip_text) ? esc_attr($tooltip_text) : 'icon',
                                    )
                                );
                            }
                        }
                        ?>

                    </span>

                    <?php

                else :

                    // Security: Validate image URL
                    $image_url = !empty($item['image']['url'])
                        ? esc_url_raw($item['image']['url'])
                        : Utils::get_placeholder_image_src();
                    
                    // Security: Validate image ID for additional security
                    $image_id = !empty($item['image']['id']) ? absint($item['image']['id']) : 0;
                    
                    if ($image_id > 0) {
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        $alt_text = !empty($image_alt) ? sanitize_text_field($image_alt) : sanitize_text_field($tooltip_text);
                    } else {
                        $alt_text = sanitize_text_field($tooltip_text);
                    }

                    ?>

                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($alt_text); ?>"
                        loading="lazy"
                        <?php if ($image_id > 0) : ?>
                        data-id="<?php echo esc_attr($image_id); ?>"
                        <?php endif; ?>
                    >

                    <?php

                endif;
                ?>

            </<?php echo esc_attr($tag); ?>>

            <?php

        endforeach;
        ?>

    </div>

    <?php