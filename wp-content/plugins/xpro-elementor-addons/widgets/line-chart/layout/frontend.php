<?php
if (!defined('ABSPATH')) {
	exit; 
}
?>
<div class="xpro-chart-wrapper">
	<canvas
		id="<?php echo esc_attr($chart_id); ?>"
		class="xpro-line-chart-js"
		data-settings='<?php echo esc_attr(wp_json_encode($chart_data)); ?>'>
	</canvas>
</div>

