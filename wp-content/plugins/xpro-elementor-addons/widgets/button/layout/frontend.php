<?php

use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

if ( !empty( $settings['onclick_event'] ) && self::check_capability( 'manage_options' ) ) {
    $attr .= ' onclick="' . esc_attr( $settings['onclick_event'] ) . '"';
}
$link_attributes = xpro_elementor_get_link_attributes( $settings['link'], 'span' );
$html_tag = $link_attributes['tag'];
$attr     = $link_attributes['attr'];

$hover_animation = ( '2d-transition' === $settings['hover_animation'] ) ? 'xpro-button-2d-animation ' . $settings['hover_2d_css_animation'] : ( ( 'background-transition' === $settings['hover_animation'] ) ? 'xpro-button-bg-animation ' . $settings['hover_background_css_animation'] : ( ( 'unique' === $settings['hover_animation'] ) ? 'xpro-elementor-button-hover-style-' . $settings['hover_unique_animation'] : 'xpro-elementor-button-animation-none' ) );
?>
<<?php echo esc_attr( $html_tag ); ?> <?php echo  wp_kses_data($attr) ?> class="xpro-elementor-button <?php echo esc_attr( $hover_animation ); ?> xpro-align-icon-<?php echo ( 'left' === $settings['icon_align'] ) ? 'left' : 'right'; ?>">
<span class="xpro-elementor-button-inner">
<?php if ( $settings['icon']['value'] ) { ?>
<span class="xpro-elementor-button-media"><?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
<?php } ?>
<span class="xpro-button-text"><?php echo esc_html( $settings['text'] ); ?></span>
</span>
</<?php echo esc_attr( $html_tag ); ?>>
