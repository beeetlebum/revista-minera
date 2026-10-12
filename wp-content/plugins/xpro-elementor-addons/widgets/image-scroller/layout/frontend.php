<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

$link_attributes = xpro_elementor_get_link_attributes( $settings['link'], 'div' );
$html_tag = $link_attributes['tag'];
$attr     = $link_attributes['attr'];
?>

<<?php echo esc_attr( $html_tag ); ?> <?php echo  wp_kses_data($attr) ?> class="xpro-scroll-image-wrapper">
<div class="xpro-scroll-image-inner xpro-image-<?php echo esc_attr( $settings['trigger_type'] ); ?>">
	<!-- Image -->
	<div class="xpro-scroll-image-<?php echo esc_attr( $settings['direction_type'] ); ?> xpro-image-scroll-img">
		<?php
		if ( $settings['image'] ) {
			echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
		}
		?>
	</div>

	<?php if ( 'yes' === $settings['show_indicator'] ) : ?>
		<!-- Icon Indicator -->
		<span class="xpro-scroll-image-indicator-icon">
			<?php
			if ( $settings['icon_indicator'] ) {
				Icons_Manager::render_icon( $settings['icon_indicator'], array( 'aria-hidden' => 'true' ) );
			}
			?>
		</span>
	<?php endif; ?>

	<?php if ( 'yes' === $settings['show_badge'] ) : ?>
		<!-- Badge -->
		<span class="xpro-scroll-image-badge xpro-badge xpro-badge-<?php echo esc_attr( $settings['badge_position'] ); ?> ">
				<?php echo esc_attr( $settings['badge_text'] ); ?>
			</span>
	<?php endif; ?>

</div>
</<?php echo esc_attr( $html_tag ); ?>>
