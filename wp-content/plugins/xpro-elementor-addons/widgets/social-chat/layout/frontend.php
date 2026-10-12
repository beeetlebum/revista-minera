<?php

defined( 'ABSPATH' ) || die();

// Safely fetch settings with fallbacks
$channels = ! empty( $settings['channels'] ) && is_array( $settings['channels'] ) ? $settings['channels'] : array();

$position_horizontal = isset( $settings['position_horizontal'] ) ? sanitize_key( $settings['position_horizontal'] ) : 'right';
$tooltip_placement   = isset( $settings['tooltip_placement'] ) ? sanitize_key( $settings['tooltip_placement'] ) : 'left';
$position_vertical   = isset( $settings['position_vertical'] ) ? sanitize_key( $settings['position_vertical'] ) : 'bottom';

$button_text = isset( $settings['button_text'] ) ? $settings['button_text'] : 'Chat';

/**
 * Hover Animation Settings
 */
$hover_animation    = isset( $settings['hover_animation'] ) ? sanitize_key( $settings['hover_animation'] ) : 'none';
$hover_2d_animation = isset( $settings['hover_2d_css_animation'] ) ? sanitize_key( $settings['hover_2d_css_animation'] ) : '';
$hover_bg_animation = isset( $settings['hover_background_css_animation'] ) ? sanitize_key( $settings['hover_background_css_animation'] ) : '';

/**
 * Shared Animation Classes (IMPORTANT)
 */
$animation_classes = array();

// Apply animation classes
if ( '2d-transition' === $hover_animation && ! empty( $hover_2d_animation ) ) {
	$animation_classes[] = $hover_2d_animation;
}

if ( 'background-transition' === $hover_animation && ! empty( $hover_bg_animation ) ) {
	$animation_classes[] = $hover_bg_animation;
}

// fallback
if ( 'none' === $hover_animation ) {
	$animation_classes[] = 'xpro-elementor-button-animation-none';
}

/**
 * Main Button Classes
 */
$main_button_classes = array_merge(
	array( 'xpro-social-chat-main-btn' ),
	$animation_classes
);

/**
 * Widget Classes
 */
$widget_classes = array(
	'xpro-social-chat-widget',
	'xpro-social-chat-position-' . $position_horizontal,
	'xpro-social-chat-placement-' . $tooltip_placement,
	'xpro-social-chat-vertical-pos-' . $position_vertical,
);

/**
 * Single Channel Check
 */
$is_single_channel = count( $channels ) === 1;

if ( $is_single_channel ) {
	$widget_classes[] = 'single-channel';
}

// Render attributes
$this->add_render_attribute(
	'widget',
	array(
		'class' => $widget_classes,
		'id'    => 'xpro-social-chat-' . esc_attr( $this->get_id() ),
	)
);

?>

<div <?php  echo wp_kses_data( $this->get_render_attribute_string('widget')); ?>>

	<div class="xpro-social-chat-channels">
		<?php foreach ( $channels as $index => $channel ) :

			$channel_type  = isset( $channel['channel_type'] ) ? sanitize_key( $channel['channel_type'] ) : '';
			$channel_label = isset( $channel['channel_label'] ) ? $channel['channel_label'] : '';
			$new_tab       = isset( $channel['link_new_tab'] ) ? $channel['link_new_tab'] : 'yes';

			$target  = ( 'yes' === $new_tab ) ? '_blank' : '_self';
			$href    = ! empty( $channel_type ) ? $this->get_channel_url( $channel ) : '#';
			$tooltip = $channel_label ?: '';

			/**
			 * Visibility Classes
			 */
			$visibility_classes = array();

			if ( isset( $channel['show_on_desktop'] ) && 'yes' !== $channel['show_on_desktop'] ) {
				$visibility_classes[] = 'xpro-hide-desktop';
			}

			if ( isset( $channel['show_on_mobile'] ) && 'yes' !== $channel['show_on_mobile'] ) {
				$visibility_classes[] = 'xpro-hide-mobile';
			}

			/**
			 * Channel Classes (FIXED)
			 */
			$channel_classes = array_merge(
				array(
					'xpro-social-chat-channel',
					'xpro-social-chat-' . $channel_type,
				),
				$animation_classes,
				$visibility_classes
			);

			/**
			 * Icon HTML
			 */
			$icon_html = '';

			if ( isset( $channel['icon_type'] ) && 'image' === $channel['icon_type'] && ! empty( $channel['custom_image']['url'] ) ) {

				$icon_html = '<img src="' . esc_url( $channel['custom_image']['url'] ) . '" alt="' . esc_attr( $tooltip ) . '" />';

			} elseif ( isset( $channel['icon_type'] ) && 'icon' === $channel['icon_type'] && ! empty( $channel['custom_icon']['value'] ) ) {

				ob_start();
				\Elementor\Icons_Manager::render_icon(
					$channel['custom_icon'],
					array( 'aria-hidden' => 'true' )
				);
				$icon_html = ob_get_clean();

			} else {

				$default_icon = ! empty( $channel_type ) ? $this->get_default_icon( $channel_type ) : 'fas fa-comment';
				$icon_html    = '<i class="' . esc_attr( $default_icon ) . '"></i>';
			}
		?>
		
		<a href="<?php echo esc_url( $href ); ?>"
		   target="<?php echo esc_attr( $target ); ?>"
		   class="<?php echo esc_attr( implode( ' ', $channel_classes ) ); ?>"
		   data-tooltip="<?php echo esc_attr( $tooltip ); ?>">
			<?php echo wp_kses_post( $icon_html ); ?>
			<span class="xpro-social-chat-tooltip">
				<?php echo esc_html( $tooltip ); ?>
			</span>
		</a>
		<?php endforeach; ?>
	</div>

	<?php if ( ! $is_single_channel ) : ?>

	<a class="<?php echo esc_attr( implode( ' ', $main_button_classes ) ); ?>"
	   data-tooltip="<?php echo esc_attr( $button_text ); ?>">

		<i class="fas fa-comments"></i>
		<i class="fas fa-times"></i>

		<span class="xpro-social-chat-tooltip">
			<?php echo esc_html( $button_text ); ?>
		</span>

	</a>

	<?php endif; ?>

</div>