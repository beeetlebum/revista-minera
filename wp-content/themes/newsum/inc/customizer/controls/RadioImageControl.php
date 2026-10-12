<?php
/**
 * Premium Radio Image / Icon Button Control for NewSum Lite
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

if ( ! class_exists( 'NewSum_Radio_Image_Control' ) ) {
    /**
     * Custom control for icon-based radio buttons.
     */
    class NewSum_Radio_Image_Control extends \WP_Customize_Control {
        
        public $type = 'newsum-radio-image';

    /**
     * Render the control's content in PHP.
     */
    protected function render_content() {
        if ( empty( $this->choices ) ) {
            return;
        }
        
        $name = '_customize-radio-' . $this->id;
        ?>
        <div class="newsum-control-wrapper newsum-radio-image-control">
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
            </label>
            
            <div class="newsum-radio-image-group">
                <?php foreach ( $this->choices as $value => $data ) : ?>
                    <label class="newsum-radio-image-label">
                        <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), $value ); ?> />
                        <span class="newsum-radio-image-btn" title="<?php echo esc_attr( $data['name'] ); ?>">
                            <?php echo newsum_sanitize_svg( $data['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <span class="newsum-radio-image-label-text"><?php echo esc_html( $data['name'] ); ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
}
