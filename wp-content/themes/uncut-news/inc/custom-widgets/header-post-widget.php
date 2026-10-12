<?php
if ( ! class_exists( 'Uncut_News_Header_Post_Widget' ) ) {
	/**
	 * Adds Uncut News Header Posts Widget.
	 */
	class Uncut_News_Header_Post_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			$header_post_widget = array(
				'classname'   => 'uncut-news-widget header-posts-widget',
				'description' => __( 'Retrive Header Posts Widgets', 'uncut-news' ),
			);
			parent::__construct(
				'uncut_news_header_post_widget',
				__( 'Artify Widget: Header Posts Widget', 'uncut-news' ),
				$header_post_widget
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			$header_posts = isset( $instance['select_posts'] ) ? absint( $instance['select_posts'] ) : '';

			echo isset( $args['before_widget'] ) ? $args['before_widget'] : '';
				?>
			<div class="header-post-outer">
				<?php
					$header_posts_args = array(
						'post_type'           => 'post',
						'post__in'            => array( $header_posts ),
						'orderby'             => 'post__in',
						'posts_per_page'      => absint( 1 ),
						'ignore_sticky_posts' => true,
					);
					$header_posts = new WP_Query( $header_posts_args );
					if ( $header_posts->have_posts() ) {
						while ( $header_posts->have_posts() ) :
							$header_posts->the_post();
							?>
							<div class="single-card-container list-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="single-card-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail(); ?>							
										</a>
									</div>
								<?php endif; ?>
								<div class="single-card-detail">
									<h3 class="card-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
								</div>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					}
				?>
			</div>
			<?php
			echo isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$header_posts = isset( $instance['select_posts'] ) ? absint( $instance['select_posts'] ) : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'select_posts' ) ); ?>"><?php esc_html_e( 'Select the Post to show:', 'uncut-news' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'select_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'select_posts' ) ); ?>" class="widefat" style="width:100%;">
					<?php
					$posts = uncut_news_get_post_choices();
					foreach ( $posts as $post_id => $post_title ) {
						?>
						<option value="<?php echo absint( $post_id ); ?>" <?php selected( $header_posts, $post_id ); ?>><?php echo esc_html( $post_title ); ?></option>
					<?php } ?>      
				</select>
			</p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                 = $old_instance;
			$instance['select_posts'] = (int) $new_instance['select_posts'];
			return $instance;
		}
	}
}
