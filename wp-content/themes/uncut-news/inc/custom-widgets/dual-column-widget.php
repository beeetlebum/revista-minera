<?php
if ( ! class_exists( 'Uncut_News_Dual_Column_Posts_Widget' ) ) {
	/**
	 * Adds Uncut_News_Dual_Column_Posts_Widget Widget.
	 */
	class Uncut_News_Dual_Column_Posts_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			$uncut_news_dual_column_posts_widget_ops = array(
				'classname'   => 'uncut-news-widget dual-column-widget',
				'description' => __( 'Retrive Dual Column Posts Widgets', 'uncut-news' ),
			);
			parent::__construct(
				'uncut_news_dual_column_widget',
				__( 'Artify Widget: Dual Column Posts Widget', 'uncut-news' ),
				$uncut_news_dual_column_posts_widget_ops
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
			$dual_column_posts_offset = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : '';

			echo $args['before_widget'];
			?>
			<div class="widget-content-area">
				<?php
				for ( $i = 1; $i <= 2; $i++ ) {
					$dual_column_posts_title    = ( ! empty( $instance[ 'title_' . $i ] ) ) ? ( $instance[ 'title_' . $i ] ) : '';
					$dual_column_posts_title    = apply_filters( 'widget_title_' . $i, $dual_column_posts_title, $instance, $this->id_base );
					$dual_column_posts_category = isset( $instance[ 'category_' . $i ] ) ? absint( $instance[ 'category_' . $i ] ) : '';
					?>
					<div class="post-column">
						<?php if ( ! empty( $dual_column_posts_title ) ) { ?>
							<div class="header-title">
								<?php echo $args['before_title'] . esc_html( $dual_column_posts_title ) . $args['after_title']; ?>
							</div>
							<?php
						}
						$dual_column_posts_widgets_args = array(
							'post_type'      => 'post',
							'posts_per_page' => absint( 4 ),
							'offset'         => absint( $dual_column_posts_offset ),
							'cat'            => absint( $dual_column_posts_category ),
						);

						$query = new WP_Query( $dual_column_posts_widgets_args );
						if ( $query->have_posts() ) :
							$j = 1;
							while ( $query->have_posts() ) :
								$query->the_post();
								?>
								<div class="single-card-container <?php echo esc_attr( $j === 1 ? 'grid-card' : 'list-card' ); ?>">
									<div class="single-card-image">
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail(); ?>					
										</a>
									</div>
									<div class="single-card-detail">
										<div class="card-categories">
											<?php uncut_news_categories_list(); ?>								
										</div>
										<h3 class="card-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>  
										<?php if ( $j === 1 ): ?>
											<div class="post-exerpt">
												<p><?php echo wp_kses_post( wp_trim_words( get_the_content(), 15 ) ); ?></p>
											</div>
										<?php endif; ?>
										<div class="card-meta">
											<span class="post-author"><?php uncut_news_posted_by(); ?></span>
											<span class="post-date"><?php uncut_news_posted_on(); ?></span>
										</div>
									</div>
								</div>			
								<?php
								$j++;
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</div>
					<?php
				}
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
			$dual_column_posts_title_1    = isset( $instance['title_1'] ) ? ( $instance['title_1'] ) : '';
			$dual_column_posts_title_2    = isset( $instance['title_2'] ) ? ( $instance['title_2'] ) : '';
			$dual_column_posts_offset     = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : '';
			$dual_column_posts_category_1 = isset( $instance['category_1'] ) ? absint( $instance['category_1'] ) : '';
			$dual_column_posts_category_2 = isset( $instance['category_2'] ) ? absint( $instance['category_2'] ) : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title_1' ) ); ?>"><?php esc_html_e( 'Section Title 1', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_1' ) ); ?>" type="text" value="<?php echo esc_attr( $dual_column_posts_title_1 ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category_1' ) ); ?>"><?php esc_html_e( 'Select the category 1 to show posts', 'uncut-news' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_1' ) ); ?>" class="widefat" style="width:100%;">
					<?php
					$categories_1 = uncut_news_get_post_cat_choices();
					foreach ( $categories_1 as $category_1 => $value_1 ) {
						?>
						<option value="<?php echo absint( $category_1 ); ?>" <?php selected( $dual_column_posts_category_1, $category_1 ); ?>><?php echo esc_html( $value_1 ); ?></option>
						<?php
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title_2' ) ); ?>"><?php esc_html_e( 'Section Title 2', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_2' ) ); ?>" type="text" value="<?php echo esc_attr( $dual_column_posts_title_2 ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category_2' ) ); ?>"><?php esc_html_e( 'Select the category 2 to show posts', 'uncut-news' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_2' ) ); ?>" class="widefat" style="width:100%;">
					<?php
					$categories_2 = uncut_news_get_post_cat_choices();
					foreach ( $categories_2 as $category_2 => $value_2 ) {
						?>
						<option value="<?php echo absint( $category_2 ); ?>" <?php selected( $dual_column_posts_category_2, $category_2 ); ?>><?php echo esc_html( $value_2 ); ?></option>
						<?php
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'Number of posts to displace or pass over', 'uncut-news' ); ?></label>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $dual_column_posts_offset ); ?>" />
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
			$instance               = $old_instance;
			$instance['title_1']    = sanitize_text_field( $new_instance['title_1'] );
			$instance['title_2']    = sanitize_text_field( $new_instance['title_2'] );
			$instance['offset']     = (int) $new_instance['offset'];
			$instance['category_1'] = (int) $new_instance['category_1'];
			$instance['category_2'] = (int) $new_instance['category_2'];
			return $instance;
		}
	}
}
