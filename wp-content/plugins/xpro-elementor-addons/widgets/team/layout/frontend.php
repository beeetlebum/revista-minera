<?php
/**
 * Team Widget Frontend Template
 * 
 * @package XPro_Elementor_Addons
 */

use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

// Build title link attributes using your existing function
$title_data = xpro_elementor_get_link_attributes( $settings['title_link'], 'h2' );
$title_tag = $title_data['tag'];
$title_attr = $title_data['attr'];

// Layout shortcuts
$layout_inner_content      = in_array( $settings['layout'], [ '8', '9' ], true );
$layout_social_top         = in_array( $settings['layout'], [ '2', '3', '5', '8', '12', '13', '15' ], true );
$layout_social_bottom      = ! $layout_social_top;
$layout_designation_top    = '2' === $settings['layout'];
$layout_designation_bottom = '2' !== $settings['layout'];

?>

<div class="xpro-team-wrapper xpro-team-layout-<?php echo esc_attr( $settings['layout'] ); ?>">
    <?php if ( $settings['designation'] && $layout_designation_top ) : ?>
        <h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
    <?php endif; ?>

    <?php if ( $settings['image']['id'] || $settings['image']['url'] ) : ?>
        <div class="xpro-team-image">
            <?php
            $image_markup = ! empty( $settings['image']['id'] ) 
                ? wp_get_attachment_image( $settings['image']['id'], $settings['thumbnail_size'] ) 
                : '';
                
            if ( ! empty( $image_markup ) ) {
                echo wp_kses( $image_markup, xpro_allowed_img_kses() );
            } else {
                echo '<img alt="team-img" src="' . esc_url( $settings['image']['url'] ) . '">';
            }
            ?>

            <?php if ( $layout_inner_content ) : ?>
                <div class="xpro-team-inner-content">
                    <?php if ( $settings['title'] ) : ?>
                        <<?php echo esc_attr( $title_tag ); ?> <?php xpro_elementor_kses( $title_attr ); ?> class="xpro-team-title">
                            <?php echo esc_attr( $settings['title'] ); ?>
                        </<?php echo esc_attr( $title_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( $settings['designation'] && $layout_designation_bottom ) : ?>
                        <h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( $settings['social_enable'] && $settings['social_icon_list'] && $layout_social_top ) : ?>
                <ul class="xpro-team-social-list xpro-team-social-list-dis">
                    <?php foreach ( $settings['social_icon_list'] as $icon ) : 
                        $social_data = xpro_elementor_get_link_attributes( $icon['icon_link'], 'span' );
                        $social_tag = $social_data['tag'];
                        $social_attr = $social_data['attr'];
                    ?>
                        <li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
                            <<?php echo esc_attr( $social_tag ); ?> <?php xpro_elementor_kses( $social_attr ); ?> class="xpro-team-social-icon">
                                <?php Icons_Manager::render_icon( $icon['social_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                            </<?php echo esc_attr( $social_tag ); ?>>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="xpro-team-content">
        <?php if ( ! $layout_inner_content ) : ?>
            <?php if ( $settings['title'] ) : ?>
                <<?php echo esc_attr( $title_tag ); ?> <?php xpro_elementor_kses( $title_attr ); ?> class="xpro-team-title">
                    <?php echo esc_attr( $settings['title'] ); ?>
                </<?php echo esc_attr( $title_tag ); ?>>
            <?php endif; ?>

            <?php if ( $settings['designation'] && $layout_designation_bottom ) : ?>
                <h4 class="xpro-team-designation"><?php echo esc_attr( $settings['designation'] ); ?></h4>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ( $settings['description'] ) : ?>
            <p class="xpro-team-description"><?php echo esc_html( $settings['description'] ); ?></p>
        <?php endif; ?>

        <?php if ( $settings['social_enable'] && $settings['social_icon_list'] && $layout_social_bottom ) : ?>
            <ul class="xpro-team-social-list xpro-team-social-list-dis">
                <?php foreach ( $settings['social_icon_list'] as $icon ) : 
                    $social_data = xpro_elementor_get_link_attributes( $icon['icon_link'], 'span' );
                    $social_tag = $social_data['tag'];
                    $social_attr = $social_data['attr'];
                ?>
                    <li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
                        <<?php echo esc_attr( $social_tag ); ?> <?php xpro_elementor_kses( $social_attr ); ?> class="xpro-team-social-icon">
                            <?php Icons_Manager::render_icon( $icon['social_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                        </<?php echo esc_attr( $social_tag ); ?>>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>