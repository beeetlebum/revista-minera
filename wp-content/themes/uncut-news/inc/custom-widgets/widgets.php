<?php

// Dual Column Widget.
require get_template_directory() . '/inc/custom-widgets/dual-column-widget.php';

// Featured Posts Widget.
require get_template_directory() . '/inc/custom-widgets/mini-list-widget.php';

// Grid Posts Widget.
require get_template_directory() . '/inc/custom-widgets/grid-posts-widget.php';

// List Posts Widget.
require get_template_directory() . '/inc/custom-widgets/list-posts-widget.php';

// Social Widget.
require get_template_directory() . '/inc/custom-widgets/social-widget.php';

// Tile List Widget.
require get_template_directory() . '/inc/custom-widgets/tile-list-widget.php';

// Tile Posts Widget.
require get_template_directory() . '/inc/custom-widgets/tile-posts-widget.php';

// Header Posts Widget.
require get_template_directory() . '/inc/custom-widgets/header-post-widget.php';

/**
 * Register Widgets
 */
function uncut_news_register_widgets() {

	register_widget( 'Uncut_News_Dual_Column_Posts_Widget' );

	register_widget( 'Uncut_News_Mini_List_Widget' );

	register_widget( 'Uncut_News_Grid_Posts_Widget' );

	register_widget( 'Uncut_News_List_Posts_Widget' );

	register_widget( 'Uncut_News_Social_Widget' );

	register_widget( 'Uncut_News_Tile_List_Widget' );

	register_widget( 'Uncut_News_Tile_Posts_Widget' );

	register_widget( 'Uncut_News_Header_Post_Widget' );

}
add_action( 'widgets_init', 'uncut_news_register_widgets' );
