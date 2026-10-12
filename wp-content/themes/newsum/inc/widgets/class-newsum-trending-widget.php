<?php
/**
 * NewSum: Trending Stories (Ranked) Widget
 *
 * A sidebar widget that displays a numbered list of
 * trending or most popular stories with category kickers and reading times.
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NewSum_Trending_Stories_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'newsum_trending_stories',
			esc_html__( 'NewSum: Trending Stories (Ranked)', 'newsum' ),
			array(
				'description' => esc_html__( 'Displays numbered trending stories (01–05) with category kickers and reading times.', 'newsum' ),
			)
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$raw_title  = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending Stories', 'newsum' );
		$title      = preg_replace( '/^\/\/\s*/', '', $raw_title );
		$count      = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$order_by   = ! empty( $instance['order_by'] ) ? sanitize_key( $instance['order_by'] ) : 'comment_count';
		$category   = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$show_thumb = ! empty( $instance['show_thumb'] );

		$query_args = array(
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish',
		);

		if ( 'comment_count' === $order_by ) {
			$query_args['orderby'] = 'comment_count';
			$query_args['order']   = 'DESC';
		} else {
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'DESC';
		}

		if ( $category > 0 ) {
			$query_args['cat'] = $category;
		}

		$trending_query = new WP_Query( $query_args );
		?>
		<div class="newsum-trending-widget">
			<?php if ( $title ) : ?>
				<?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<?php if ( $trending_query->have_posts() ) : ?>
				<div class="newsum-trending-list">
					<?php
					$rank = 0;
					while ( $trending_query->have_posts() ) :
						$trending_query->the_post();
						$rank++;
						$rank_str = sprintf( '%02d', $rank );
						$categories = get_the_category();
						$cat_name = ! empty( $categories ) ? $categories[0]->name : '';
						?>
						<article class="newsum-trending-item">
							<span class="newsum-trending-rank"><?php echo esc_html( $rank_str ); ?></span>
							<div class="newsum-trending-content">
								<h5 class="newsum-trending-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h5>

								<div class="newsum-trending-meta">
									<?php if ( $cat_name ) : ?>
										<span class="newsum-trending-cat"><?php echo esc_html( $cat_name ); ?></span>
										<span class="meta-sep">&bull;</span>
									<?php endif; ?>
									<span class="meta-date"><?php echo esc_html( get_the_date( 'M j' ) ); ?></span>
									<?php if ( function_exists( 'newsum_get_reading_time' ) ) : ?>
										<span class="meta-sep">&bull;</span>
										<span class="meta-read"><?php echo esc_html( newsum_get_reading_time( get_the_ID() ) ); ?>m read</span>
									<?php endif; ?>
								</div>
							</div>
							<?php if ( $show_thumb && has_post_thumbnail() ) : ?>
								<div class="newsum-trending-thumb">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?>
									</a>
								</div>
							<?php endif; ?>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Widget settings.
	 */
	public function form( $instance ) {
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending Stories', 'newsum' );
		$count      = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		$order_by   = ! empty( $instance['order_by'] ) ? sanitize_key( $instance['order_by'] ) : 'comment_count';
		$category   = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
		$show_thumb = ! empty( $instance['show_thumb'] );

		$categories = get_categories( array( 'hide_empty' => false ) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Header / Title:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of Stories (1–10):', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>"><?php esc_html_e( 'Order Stories By:', 'newsum' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order_by' ) ); ?>">
				<option value="comment_count" <?php selected( $order_by, 'comment_count' ); ?>><?php esc_html_e( 'Most Commented (Trending)', 'newsum' ); ?></option>
				<option value="date" <?php selected( $order_by, 'date' ); ?>><?php esc_html_e( 'Latest Published Stories', 'newsum' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Filter by Category:', 'newsum' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
				<option value="0"><?php esc_html_e( 'All Topics & Categories', 'newsum' ); ?></option>
				<?php foreach ( $categories as $cat ) : ?>
					<option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $category, $cat->term_id ); ?>>
						<?php echo esc_html( $cat->name ); ?> (<?php echo (int) $cat->count; ?>)
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" value="1" <?php checked( $show_thumb, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php esc_html_e( 'Show Post Thumbnails', 'newsum' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Sanitize and save.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array Sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['count']      = ! empty( $new_instance['count'] ) ? absint( $new_instance['count'] ) : 5;
		$instance['order_by']   = ! empty( $new_instance['order_by'] ) ? sanitize_key( $new_instance['order_by'] ) : 'comment_count';
		$instance['category']   = ! empty( $new_instance['category'] ) ? absint( $new_instance['category'] ) : 0;
		$instance['show_thumb'] = ! empty( $new_instance['show_thumb'] ) ? 1 : 0;
		return $instance;
	}
}
