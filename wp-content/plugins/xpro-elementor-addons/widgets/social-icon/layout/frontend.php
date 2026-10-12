<?php

use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

$hover_animation = ( '2d-transition' === $settings['social_icon_hover_animation'] )
	? 'xpro-button-2d-animation ' . $settings['social_icon_hover_2d_css_animation']
	: (
		( 'background-transition' === $settings['social_icon_hover_animation'] )
		? 'xpro-button-bg-animation ' . $settings['social_icon_hover_background_css_animation']
		: (
			( 'hover-effect' === $settings['social_icon_hover_animation'] )
			? 'xpro-unique-' . $settings['social_icon_hover_effect_animation']
			: 'xpro-elementor-button-animation-none'
		)
	);
?>
<!-- Social Icon -->
<ul class="xpro-social-icon-wrapper <?php echo esc_attr( $settings['styles'] ); ?>">
	<?php foreach ( $settings['item'] as $i => $item ) : ?>
		<?php
		$icon    = $item['icon']['value'];
		$library = ( 'svg' === $item['icon']['library'] ) ? 'svg' : 'icon';

		if ( ! empty( $icon ) && 'svg' !== $library ) {
			$social_name = str_replace(
				array( 'fa fa-', 'fab fa-', 'far fa-', 'fas fa-' ),
				'',
				$icon
			);
		} else {
			$social_name = 'label';
		}

		$classes = sprintf(
			'xpro-social-icon %s elementor-social-icon-%s',
			esc_attr( $hover_animation ),
			esc_attr( $social_name )
		);

		$aria_label = ! empty( $item['title'] )
			? sprintf(
				/* translators: %s: Social platform name */
				__( 'Visit our %s page', 'xpro-elementor-addons' ),
				$item['title']
			)
			: __( 'Social icon link', 'xpro-elementor-addons' );

		$link_attributes = '';

		if ( ! empty( $item['link']['url'] ) ) {
			$link_attributes .= ' href="' . esc_url( $item['link']['url'] ) . '"';

			if ( ! empty( $item['link']['is_external'] ) ) {
				$link_attributes .= ' target="_blank"';
				$link_attributes .= ' rel="noopener noreferrer';
				
				if ( ! empty( $item['link']['nofollow'] ) ) {
					$link_attributes .= ' nofollow';
				}

				$link_attributes .= '"';
			} elseif ( ! empty( $item['link']['nofollow'] ) ) {
				$link_attributes .= ' rel="nofollow"';
			}

			$link_attributes .= ' aria-label="' . esc_attr( $aria_label ) . '"';
		}
		?>
		<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>"> 
			<?php if ( ! empty( $item['link']['url'] ) ) : ?> 
				<a class="<?php echo esc_attr ($classes); ?>"<?php echo $link_attributes; ?>>
			<?php else : ?>
				<span class="<?php echo esc_attr ($classes); ?>">
			<?php endif; ?>

				<?php
				if ( ! empty( $item['icon'] ) ) {
					$item['icon'] = xpro_fix_elementor_icon_library( $item['icon'] );
					Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
				}
				?>

				<?php if ( ! empty( $item['title'] ) ) : ?>
					<span class="xpro-social-icon-title">
						<?php echo esc_html( $item['title'] ); ?>
					</span>
				<?php endif; ?>

			<?php if ( ! empty( $item['link']['url'] ) ) : ?>
				</a>
			<?php else : ?>
				</span>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>