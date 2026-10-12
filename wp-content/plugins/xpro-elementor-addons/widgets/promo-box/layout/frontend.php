<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();
$link_attributes = xpro_elementor_get_link_attributes( $settings['title_link'], 'h3' );
$html_tag        = $link_attributes['tag'];
$attr            = $link_attributes['attr'];
?>
<div class="xpro-promo-box-wrapper">
	<div class="xpro-promo-box-inner">

		<?php if ( $settings['badge_text'] ) : ?>
			<span class="xpro-promo-box-badge xpro-badge xpro-badge-<?php echo esc_attr( $settings['badge_position'] ); ?>"><?php echo esc_attr( $settings['badge_text'] ); ?></span>
		<?php endif; ?>

		<div class="xpro-promo-box-content">
			<?php if ( 'none' !== $settings['media_type'] && 'before' === $settings['image_position'] ) : ?>
				<div class="xpro-promo-box-media">
					<?php
					if ( 'image' === $settings['media_type'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['sub_title'] && 'before' === $settings['sub_title_position'] ) : ?>
				<h4 class="xpro-promo-box-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></h4>
			<?php endif; ?>

			<?php if ( $settings['title'] ) : ?>
			<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="xpro-promo-box-title">
				<?php echo esc_html( $settings['title'] ); ?>
		</<?php echo esc_attr( $html_tag ); ?>>
	<?php endif; ?>

		<?php if ( $settings['sub_title'] && 'after' === $settings['sub_title_position'] ) : ?>
			<h4 class="xpro-promo-box-sub-title"><?php echo esc_html( $settings['sub_title'] ); ?></h4>
		<?php endif; ?>

		<?php if ( $settings['description'] ) : ?>
			<div class="xpro-promo-box-desc"><?php echo wp_kses_post( $settings['description'] ); ?></div>
		<?php endif; ?>

		<?php if ( 'none' !== $settings['media_type'] && 'after' === $settings['image_position'] ) : ?>
			<div class="xpro-promo-box-media">
				<?php
				if ( 'image' === $settings['media_type'] ) {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $settings['button_text'] ) : ?>
	<?php
	$button_link_attributes = xpro_elementor_get_link_attributes( $settings['button_link'], 'div' );
	$btn_tag  = $button_link_attributes['tag'];
	$btn_attr = $button_link_attributes['attr'];
	?>
	<<?php echo esc_attr( $btn_tag ); ?> <?php echo wp_kses_data($btn_attr); ?> class="xpro-promo-box-btn xpro-promo-box-align-<?php echo esc_attr( $settings['icon_align'] ); ?>">
		<?php if ( $settings['icon']['value'] ) : ?>
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
	<?php endif; ?>

	<span class="xpro-promo-box-btn-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
</<?php echo esc_attr( $btn_tag ); ?>>
<?php endif; ?>
</div>
</div>
