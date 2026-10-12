<?php
/**
 * Frontpage Customizer Settings
 *
 * @package Uncut News
 *
 * Custom Controller
 */

/**
 * Toggle Switch Custom Control
 *
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 */
class Uncut_News_Toggle_Checkbox_Custom_control extends WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'toggle_switch';

	/**
	 * Render the control in the customizer
	 */
	public function render_content(){
		?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}

/**
 * Horizontal Line Control
 */
class Uncut_News_Customize_Horizontal_Line extends WP_Customize_Control {
	/**
	 * Control Type
	 */
	public $type = 'hr';

	/**
	 * Render Settings
	 */
	public function render_content() {
		?>
		<div>
			<hr style="border: 1px dotted #72777c;" />
		</div>
		<?php
	}
}

class Uncut_News_Section_Sub_Heading_Control extends WP_Customize_Control {

	// The type of control being rendered
	public $type = 'sub_section_heading';

	// Render the control in the customizer

	public function render_content() {

		?>
		<div class="sub-section-heading-control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<h4 class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</h4>
			<?php } ?>

		</div>
		<?php
	}
}


/**
 * Multi Input field
 */
class Uncut_News_Multi_Input_Custom_control extends WP_Customize_Control {
	public $type = 'multi_input';

	public function render_content() {
		?>
		<label class="customize_multi_input">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<p><?php echo wp_kses_post( $this->description ); ?></p>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize_multi_value_field" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>"/>
			<div class="customize_multi_fields">
				<div class="set">
					<input type="text" value="" class="customize_multi_single_field"/>
					<a href="#" class="customize_multi_remove_field"><i class="fa-solid fa-xmark"></i></a>
				</div>
			</div>
			<a href="#" class="button button-primary customize_multi_add_field"><?php esc_html_e( 'Add More', 'uncut-news' ); ?></a>
		</label>
		<?php
	}
}
