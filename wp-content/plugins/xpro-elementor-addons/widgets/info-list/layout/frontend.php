<?php
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();
?>
<ul class="xpro-infolist-wrapper xpro-infolist-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
	<?php foreach ( $settings['item'] as $i => $item ) { ?>
		<li class="xpro-infolist-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
			<?php
		$link_attributes = xpro_elementor_get_link_attributes( $item['link'], 'a' );
        $attr = $link_attributes['attr'];

			echo ( $item['link']['url'] ) ? '<a href="' . esc_url( $item['link']['url'] ) . '" ' .  esc_attr($attr) . '>' : '';
			?>
			<?php if ( 'none' !== $item['media_type'] ) : ?>
				<div class="xpro-infolist-media xpro-infolist-media-type-<?php echo esc_attr( $item['media_type'] ); ?>">
					<?php
					if ( 'icon' === $item['media_type'] && $item['icon'] ) {
						Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
					}

					if ( 'image' === $item['media_type'] && $item['image'] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' ) );
					}

					if ( 'custom' === $item['media_type'] && $item['custom'] ) {
						echo '<i class="xpro-infolist-custom">' . esc_html( $item['custom'] ) . '</i>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="xpro-infolist-content">
				<?php if ( $item['title'] ) :

                    $html_tag = isset( $item['title_tag'] ) ? strtolower( sanitize_key( $item['title_tag'] ) ) : 'h2';
                    $allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span' );
					
                    if ( ! in_array( $html_tag, $allowed_tags, true ) ) {
                        $html_tag = 'h2';
                    }

                ?>
                <<?php echo esc_attr( $html_tag ); ?> class="xpro-infolist-title"> <?php echo esc_html( $item['title'] ); ?> </<?php echo esc_attr( $html_tag ); ?>>
                <?php endif; ?>
				<?php if ( $item['description'] ) : ?>
					<p class="xpro-infolist-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo ( $item['link']['url'] ) ? '</a>' : ''; ?>
		</li>
	<?php } ?>
</ul>
