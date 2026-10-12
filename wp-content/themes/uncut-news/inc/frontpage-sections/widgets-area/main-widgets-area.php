<?php
if ( is_active_sidebar( 'primary-widgets-area' ) || is_active_sidebar( 'secondary-widgets-area' ) ) :
	$no_sidebar = is_active_sidebar( 'primary-widgets-area' ) && is_active_sidebar( 'secondary-widgets-area' ) ? '' : 'no-sidebar';
	?>
	<div class="widget-element-section">
		<div class="site-container-width">
			<div class="widget-element-wrap secondary-sidebar-right <?php echo esc_attr( $no_sidebar ); ?>">
				<?php if ( is_active_sidebar( 'primary-widgets-area' ) ) { ?>
					<div class="primary-widget-section">
						<?php dynamic_sidebar( 'primary-widgets-area' ); ?>
					</div>
					<?php
				}
				if ( is_active_sidebar( 'secondary-widgets-area' ) ) {
					?>
					<div class="secondary-widget-section">
						<?php dynamic_sidebar( 'secondary-widgets-area' ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

<?php endif; ?>
