<?php
/**
 * Blogsy/Neve-inspired Toggle Switch Control for NewSum
 *
 * @package NewSum
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return;
}

/**
 * Custom control for an iOS-style toggle switch with Blogsy-style help tooltip.
 */
class NewSum_Toggle_Control extends \WP_Customize_Control {
    
    public $type = 'newsum-toggle';

    /**
     * Render the control's content in PHP.
     */
    protected function render_content() {
        $input_id = '_customize-input-' . $this->id;
        ?>
        <div class="newsum-toggle-wrapper">
            <div class="newsum-toggle-title-wrap">
                <label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="newsum-help-icon" tabindex="0" role="tooltip" aria-label="<?php echo esc_attr( $this->description ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span class="newsum-tooltip"><?php echo esc_html( $this->description ); ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <div class="newsum-toggle">
                <input type="checkbox" id="<?php echo esc_attr( $input_id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> <?php checked( $this->value() ); ?> />
                <label for="<?php echo esc_attr( $input_id ); ?>" class="newsum-toggle-switch-label">
                    <span class="newsum-toggle-switch"></span>
                </label>
            </div>
        </div>
        <?php
    }
}
