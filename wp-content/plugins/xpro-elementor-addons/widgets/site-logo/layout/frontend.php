<?php

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();


$image = ( 'custom' === $settings['logo_type'] && ! empty( $settings['custom_logo']['id'] ) )
	? $settings['custom_logo']['id']
	: get_theme_mod( 'custom_logo' );

$url  = get_home_url();
$attr = '';

if ( 'custom' === $settings['link_type'] ) {
	$link_attributes = xpro_elementor_get_link_attributes( $settings['link'], 'a' );
	$url  = ! empty( $settings['link']['url'] ) ? $settings['link']['url'] : '';
	$attr = $link_attributes['attr'];

}

?>
<a href="<?php echo esc_url( $url ); ?>"<?php echo wp_kses_data( $attr ); ?>>
	<div class="xpro-site-logo">
		<?php
		if ( 'default' === $settings['logo_type'] && has_custom_logo() ) {

			echo wp_get_attachment_image( $image, $settings['thumbnail_size'] );
		} elseif ( 'custom' === $settings['logo_type'] ) {
			echo wp_kses(Group_Control_Image_Size::get_attachment_image_html(
				$settings,
				'thumbnail',
				'custom_logo'
			), xpro_allowed_img_kses());

		}
		?>
	</div>
</a>
