	<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	use Elementor\Icons_Manager;
	$settings = $this->get_settings_for_display();
	$slider_settings = wp_json_encode(
		array_filter(
			array(
				'slide_effect'     => $settings['slide_animation'],
				'slide_speed'      => $settings['slide_speed']['size'],
				'loop'             => 'yes' === $settings['loop'],
				'mouse_drag'       => 'yes' === $settings['mouse_drag'],
				'autoplay'         => 'yes' === $settings['autoplay'],
				'autoplay_timeout' => 'yes' === $settings['autoplay'] && $settings['autoplay_timeout'] ? $settings['autoplay_timeout']['size'] : '',
			)
		)
	);

	?>
	<div class="xpro-hero-slider-wrapper xpro-swiper-slider-theme xpro-swiper-navigation-horizontal-<?php echo esc_attr( $settings['nav_layout'] ?? 'style-1' ); ?> xpro-swiper-dots-horizontal-<?php echo esc_attr( $settings['dots_layout'] ?? 'style-1' ); ?>">
	<div id="xpro-hero-slider-<?php echo esc_attr( $this->get_id() ); ?>" class="swiper xpro-hero-slider" data-xpro-hero-slider-setting="<?php echo esc_attr( $slider_settings ); ?>">
		<div class="swiper-wrapper">
			<?php
			foreach ( $settings['slide_items'] as $i => $item ) {
				?>
				<!-- Slides -->
				<div class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> swiper-slide">
					<!--Slide BG-->
					<div <?php echo esc_attr( 'slide' === $settings['slide_animation'] ? 'data-swiper-parallax=50%' : '' ); ?> class="xpro-hero-slider-slide-bg"></div>
					<!--Slide Content-->
					<div class="xpro-hero-slider-slide-content-wrapper">
						<div class="xpro-hero-slider-slide-content-area">
							<?php echo wp_kses_post( 'before_title' === $item['subtitle_position'] ? $this->render_subtitle( $item ) : '' ); ?>
							<?php echo wp_kses_post( $this->render_title( $item ) ); ?>
							<?php echo wp_kses_post( 'after_title' === $item['subtitle_position'] ? $this->render_subtitle( $item ) : '' ); ?>
							<?php if ( 'yes' === $item['enable_description'] && ! empty( $item['description'] ) ) { ?>
							<div class="xpro-hero-slider-description-wrapper">
								<div class="xpro-hero-slider-description" data-animation="<?php echo esc_attr( $item['description_animation_effect'] ); ?>">
									<?php echo wp_kses_post( $item['description'] ); ?>
								</div>
							</div>
							<?php } ?>

							<?php
							/**
							 * Button Section - Refactored with Elementor native attribute handling
							 */
							if (
								( 'yes' === $item['enable_primary_button'] && ! empty( $item['primary_button_title'] ) ) ||
								( 'yes' === $item['enable_secondary_button'] && ! empty( $item['secondary_button_title'] ) )
							) :
								?>
								<div class="xpro-hero-slider-slide-button-wrapper">

									<?php
									/**
									 * Primary Button
									 */
									if ( 'yes' === $item['enable_primary_button'] && ! empty( $item['primary_button_title'] ) ) :

										$primary_btn_tag  = ! empty( $item['primary_button_link']['url'] ) ? 'a' : 'button';
										$primary_attr_key = 'primary_button_' . $i;

										// Reset attributes for this button
										$this->set_render_attribute( $primary_attr_key, array() );

										if ( ! empty( $item['primary_button_css_id'] ) ) {
											$this->add_render_attribute(
												$primary_attr_key,
												'id',
												sanitize_html_class( $item['primary_button_css_id'] )
											);
										}

										if ( ! empty( $item['primary_button_link'] ) ) {
											$this->add_link_attributes(
												$primary_attr_key,
												$item['primary_button_link']
											);
										}

										$this->add_render_attribute(
											$primary_attr_key,
											'class',
											array(
												'xpro-hero-slider-button-primary',
												'xpro-hero-slider-button-default',
											)
										);

										$this->add_render_attribute(
											$primary_attr_key,
											'data-animation',
											$item['primary_button_animation_effect']
										);
										?>

										<<?php echo esc_attr( $primary_btn_tag ); ?>
											<?php echo $this->get_render_attribute_string( $primary_attr_key ); ?>>

											<span class="xpro-hero-slider-button-text">
												<?php echo esc_html( $item['primary_button_title'] ); ?>
											</span>

											<?php if ( ! empty( $item['primary_button_icon']['value'] ) ) : ?>
												<span class="xpro-hero-slider-button-media">
													<?php
													Icons_Manager::render_icon(
														$item['primary_button_icon'],
														array(
															'aria-hidden' => 'true',
														)
													);
													?>
												</span>
											<?php endif; ?>

										</<?php echo esc_attr( $primary_btn_tag ); ?>>

									<?php endif; ?>

									<?php
									/**
									 * Secondary Button
									 */
									if ( 'yes' === $item['enable_secondary_button'] && ! empty( $item['secondary_button_title'] ) ) :

										$secondary_btn_tag  = ! empty( $item['secondary_button_link']['url'] ) ? 'a' : 'button';
										$secondary_attr_key = 'secondary_button_' . $i;

										// Reset attributes for this button
										$this->set_render_attribute( $secondary_attr_key, array() );

										if ( ! empty( $item['secondary_button_css_id'] ) ) {
											$this->add_render_attribute(
												$secondary_attr_key,
												'id',
												sanitize_html_class( $item['secondary_button_css_id'] )
											);
										}

										if ( ! empty( $item['secondary_button_link'] ) ) {
											$this->add_link_attributes(
												$secondary_attr_key,
												$item['secondary_button_link']
											);
										}

										$this->add_render_attribute(
											$secondary_attr_key,
											'class',
											array(
												'xpro-hero-slider-button-secondary',
												'xpro-hero-slider-button-default',
											)
										);

										$this->add_render_attribute(
											$secondary_attr_key,
											'data-animation',
											$item['secondary_button_animation_effect']
										);
										?>

										<<?php echo esc_attr( $secondary_btn_tag ); ?>
											<?php echo $this->get_render_attribute_string( $secondary_attr_key ); ?>>

											<span class="xpro-hero-slider-button-text">
												<?php echo esc_html( $item['secondary_button_title'] ); ?>
											</span>

											<?php if ( ! empty( $item['secondary_button_icon']['value'] ) ) : ?>
												<span class="xpro-hero-slider-button-media">
													<?php
													Icons_Manager::render_icon(
														$item['secondary_button_icon'],
														array(
															'aria-hidden' => 'true',
														)
													);
													?>
												</span>
											<?php endif; ?>

										</<?php echo esc_attr( $secondary_btn_tag ); ?>>

									<?php endif; ?>

								</div>
							<?php endif; ?>

						</div>
					</div>
				</div>
				<?php } ?>
		</div>
	</div>

	<?php if ( $settings['dots'] ) : ?>
		<div class="swiper-pagination"></div>
	<?php endif; ?>
	<!-- Navigation Arrows -->
	<?php if ( $settings['nav'] ) : ?>
		<button type="button" class="swiper-button-prev"></button>
		<button type="button" class="swiper-button-next"></button>
	<?php endif; ?>
	</div>