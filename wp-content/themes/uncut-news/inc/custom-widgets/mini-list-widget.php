<?php
if ( ! class_exists( 'Uncut_News_Mini_List_Widget' ) ) {
	/**
	 * Adds Uncut News Mini List Widget.
	 */
	class Uncut_News_Mini_List_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			$mini_list_widget = array(
				'classname'   => 'uncut-news-widget mini-list-widget',
				'description' => __( 'Retrive Posts List Widgets', 'uncut-news' ),
			);
			parent::__construct(
				'uncut_news_mini_list_widget',
				__( 'Artify Widget: Mini List Widget', 'uncut-news' ),
				$mini_list_widget
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
			$mini_list_title       = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
			$mini_list_title       = apply_filters( 'widget_title', $mini_list_title, $instance, $this->id_base );
			$mini_list_btn_label   = ( ! empty( $instance['button_label'] ) ) ? $instance['button_label'] : '';
			$mini_list_post_count  = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
			$mini_list_post_offset = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : '';
			$mini_list_category    = isset( $instance['category'] ) ? absint( $instance['category'] ) : '';
			$mini_list_btn_link    = ( ! empty( $instance['button_link'] ) ) ? $instance['button_link'] : esc_url( get_category_link( $mini_list_category ) );

			echo $args['before_widget'];

			if ( ! empty( $mini_list_title || $mini_list_btn_label ) ) {
				?>
				<div class="header-title">
					<?php echo $args['before_title'] . esc_html( $mini_list_title ) . $args['after_title']; ?>
					<?php if ( ! empty( $mini_list_btn_label ) ) : ?>
						<a href="<?php echo esc_url( $mini_list_btn_link ); ?>" class="view-all"><?php echo esc_html( $mini_list_btn_label ); ?></a>
					<?php endif; ?>
				</div>
			<?php } ?>
			<div class="widget-content-area">
				<?php
				$mini_list_widgets_args = array(
					'post_type'      => 'post',
					'posts_per_page' => absint( $mini_list_post_count ),
					'offset'         => absint( $mini_list_post_offset ),
					'cat'            => absint( $mini_list_category ),
				);

				$query = new WP_Query( $mini_list_widgets_args );
				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<div class="single-card-container list-card">
							<div class="single-card-image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail(); ?>					
								</a>
							</div>
							<div class="single-card-detail">
								<h3 class="card-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>  
								<div class="card-meta">
									<span class="post-author"><?php uncut_news_posted_by(); ?></span>
									<span class="post-date"><?php uncut_news_posted_on(); ?></span>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$mini_list_title       = isset( $instance['title'] ) ? $instance['title'] : '';
			$mini_list_btn_label   = isset( $instance['button_label'] ) ? $instance['button_label'] : '';
			$mini_list_btn_link    = isset( $instance['button_link'] ) ? $instance['button_link'] : '';
			$mini_list_post_count  = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
			$mini_list_post_offset = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : '';
			$mini_list_category    = isset( $instance['category'] ) ? absint( $instance['category'] ) : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Section Title:', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $mini_list_title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>"><?php esc_html_e( 'View All Button:', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_label' ) ); ?>" type="text" value="<?php echo esc_attr( $mini_list_btn_label ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>"><?php esc_html_e( 'View All Button URL:', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_link' ) ); ?>" type="text" value="<?php echo esc_attr( $mini_list_btn_link ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show(Min:1 | Max:6):', 'uncut-news' ); ?></label>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" max="6" value="<?php echo absint( $mini_list_post_count ); ?>" size="3" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'Number of posts to displace or pass over:', 'uncut-news' ); ?></label>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $mini_list_post_offset ); ?>" size="3" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Select the category to show posts:', 'uncut-news' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" class="widefat" style="width:100%;">
					<?php
					$categories = uncut_news_get_post_cat_choices();
					foreach ( $categories as $category => $value ) {
						?>
						<option value="<?php echo absint( $category ); ?>" <?php selected( $mini_list_category, $category ); ?>><?php echo esc_html( $value ); ?></option>
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
			$instance['title']        = sanitize_text_field( $new_instance['title'] );
			$instance['button_label'] = sanitize_text_field( $new_instance['button_label'] );
			$instance['button_link']  = esc_url_raw( $new_instance['button_link'] );
			$instance['number']       = (int) $new_instance['number'];
			if ( $instance['number'] < 1 ) {
				$instance['number'] = 1;
			} elseif ( $instance['number'] > 6 ) {
				$instance['number'] = 6;
			}
			$instance['offset']   = (int) $new_instance['offset'];
			$instance['category'] = (int) $new_instance['category'];
			return $instance;
		}
	}
}
