<?php

// Tile List Widget.
require get_template_directory() . '/inc/widgets/tile-list-widget.php';

// Social Icons Widget.
require get_template_directory() . '/inc/widgets/social-icons-widget.php';

/**
 * Register Widgets
 */
function beyond_blog_register_widgets() {

	register_widget( 'Beyond_Blog_Tile_List_Widget' );

	register_widget( 'Beyond_Blog_Social_Icons_Widget' );
}
add_action( 'widgets_init', 'beyond_blog_register_widgets' );
