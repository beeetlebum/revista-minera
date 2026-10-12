<?php
if ( ! class_exists( 'Uncut_News_Social_Widget' ) ) {
	/**
	 * Adds Uncut News Social Widget.
	 */
	class Uncut_News_Social_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			$uncut_news_social_widget = array(
				'classname'   => 'uncut-news-widget social-widget style-1',
				'description' => __( 'Retrive Social Widget', 'uncut-news' ),
			);
			parent::__construct(
				'uncut_news_social_widget',
				__( 'Artify Widget: Social Widget', 'uncut-news' ),
				$uncut_news_social_widget
			);
		}

		/**
		 * Front-end display of widget.

		 * @see WP_Widget::widget()

		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}
			$section_title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$section_title = apply_filters( 'widget_title', $section_title, $instance, $this->id_base );

			echo $args['before_widget'];
			if ( ! empty( $section_title ) ) {
				echo $args['before_title'] . esc_html( $section_title ) . $args['after_title'];
			}
			?>

			<div class="widget-content-area">
				<div class="social-feed-widgets-wrap author-social-contacts">
					<?php
					for ( $i = 1; $i <= 4; $i++ ) {
						$social_icon_label = ( ! empty( $instance[ 'social_icon_label-' . $i ] ) ) ? $instance[ 'social_icon_label-' . $i ] : '';
						$link              = ( ! empty( $instance[ 'link-' . $i ] ) ) ? $instance[ 'link-' . $i ] : '';
						if ( ! empty( $link ) ) :
							?>
							<a href="<?php echo esc_url( $link ); ?>">
								<?php if ( ! empty( $social_icon_label ) ) : ?>
									<span class="screen-reader-text"><?php echo esc_html( $social_icon_label ); ?></span>
								<?php endif; ?>
							</a>
							<?php
						endif;
					}
					?>
				</div>
			</div>

			<?php
			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.

		 * @see WP_Widget::form()

		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$section_title = isset( $instance['title'] ) ? $instance['title'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Section Title:', 'uncut-news' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $section_title ); ?>" />
			</p>
			<?php
			for ( $i = 1; $i <= 4; $i++ ) {
				$social_icon_label = isset( $instance[ 'social_icon_label-' . $i ] ) ? $instance[ 'social_icon_label-' . $i ] : '';
				$link              = isset( $instance[ 'link-' . $i ] ) ? $instance[ 'link-' . $i ] : '';
				?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'social_icon_label-' . $i ) ); ?>"><?php echo sprintf( esc_html__( 'Social Icon Label %d :', 'uncut-news' ), $i ); ?></label>
					<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'social_icon_label-' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'social_icon_label-' . $i ) ); ?>" value="<?php echo esc_html( $social_icon_label ); ?>"/>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'link-' . $i ) ); ?>"><?php echo sprintf( esc_html__( 'Link %d :', 'uncut-news' ), $i ); ?></label>
					<input type="url" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link-' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link-' . $i ) ); ?>" value="<?php echo esc_url( $link ); ?>"/>
				</p>
			<?php } ?>

			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.

		 * @see WP_Widget::update()

		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.

		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance          = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			for ( $i = 1; $i <= 4; $i++ ) {
				$instance[ 'social_icon_label-' . $i ] = sanitize_text_field( $new_instance[ 'social_icon_label-' . $i ] );
				$instance[ 'link-' . $i ]              = esc_url_raw( $new_instance[ 'link-' . $i ] );
			}
			return $instance;
		}
	}
}
