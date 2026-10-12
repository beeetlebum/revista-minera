<?php if ( is_active_sidebar( 'above-footer-widget' ) ) { ?>
	<div class="above-footer-widget">
		<div class="section-wrapper">
			<div class="above-footer-widgets-wrapper above-footer-widget-1"> 
				<div class="above-footer-widgets-section">
					<?php dynamic_sidebar( 'above-footer-widget' ); ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
