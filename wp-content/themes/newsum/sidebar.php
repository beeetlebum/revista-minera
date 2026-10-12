<?php
/**
 * The sidebar containing the main widget area
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside id="secondary" class="sidebar-column widget-area" aria-label="<?php esc_attr_e( 'Main Sidebar', 'newsum' ); ?>">
	<?php
	if ( is_active_sidebar( 'sidebar-1' ) ) :
		dynamic_sidebar( 'sidebar-1' );
	else :
		// Fallback Widgets when none are configured in Customizer/Widgets screen
		the_widget( 'NewSum_Author_Profile_Widget', array(
			'title'       => __( 'Editorial Lead', 'newsum' ),
			'author_name' => get_bloginfo( 'name' ),
			'role'        => __( 'Editorial Newsroom & Data Desk', 'newsum' ),
			'bio'         => get_bloginfo( 'description' ),
		) );

		the_widget( 'NewSum_Trending_Stories_Widget', array(
			'title'    => __( 'Trending Stories', 'newsum' ),
			'count'    => 5,
			'order_by' => 'comment_count',
		) );
	endif;
	?>
</aside>
