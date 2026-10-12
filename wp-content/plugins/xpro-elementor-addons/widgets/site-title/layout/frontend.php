<?php
defined( 'ABSPATH' ) || die();
use Elementor\Icons_Manager;

$html_tag = sanitize_key( $settings['title_tag'] );

$url = get_home_url();
$attr = '';

if ( 'custom' === $settings['custom_link'] ) {
	$link_attributes = xpro_elementor_get_link_attributes( $settings['title_link'], 'a' );
	$url  = ! empty( $settings['title_link']['url'] ) ? $settings['title_link']['url'] : $url;
	$attr = $link_attributes['attr'];
}

$class  = 'xpro-site-title';
$class .= ( $settings['icon']['value'] ) ? ' xpro-site-title-icon-' . $settings['icon_align'] : '';
?>

<a href="<?php echo esc_url( $url ); ?>"<?php echo wp_kses_data( $attr ); ?>>
    <?php
	     $allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	     $html_tag = in_array( strtolower( $html_tag ), $allowed_tags ) ? strtolower( $html_tag ) : 'h2';
	?>
	<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $class ); ?>">
	<?php if ( $settings['icon']['value'] ) : ?>
		<span class="xpro-site-title-icon">
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
	<?php endif; ?>
	<span class="xpro-site-title-text">
		<?php echo esc_html( get_bloginfo() ); ?>
	</span>

</<?php echo esc_attr( $html_tag ); ?>>
</a>
