<?php
/**
 * NewSum: Author Profile Widget
 *
 * A sidebar widget that displays an author/editor profile card
 * with avatar, correspondent title, bio, and social channels.
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class NewSum_Author_Profile_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'newsum_author_profile',
			esc_html__( 'NewSum: Author Profile', 'newsum' ),
			array(
				'description' => esc_html__( 'Displays a newsroom correspondent or author profile card.', 'newsum' ),
			)
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_media_scripts' ) );
		add_action( 'admin_footer', array( $this, 'render_media_js' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'render_media_js' ) );
	}

	/**
	 * Enqueue WordPress Media Uploader scripts.
	 */
	public function enqueue_media_scripts() {
		wp_enqueue_media();
	}

	/**
	 * Render JS for media picker in admin and Customizer footer.
	 */
	public function render_media_js() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
		<script>
		jQuery(document).ready(function($) {
			$(document).on('click', '.newsum-upload-avatar-btn', function(e) {
				e.preventDefault();
				var $btn = $(this);
				var $wrap = $btn.closest('.newsum-widget-media-wrap');
				var $input = $wrap.find('.newsum-avatar-url-input');
				var $preview = $wrap.find('.newsum-avatar-preview');
				var $removeBtn = $wrap.find('.newsum-remove-avatar-btn');

				var frame = wp.media({
					title: '<?php echo esc_js( __( 'Select or Upload Author Photo', 'newsum' ) ); ?>',
					button: { text: '<?php echo esc_js( __( 'Use this Photo', 'newsum' ) ); ?>' },
					multiple: false,
					library: { type: 'image' }
				});

				frame.on('select', function() {
					var attachment = frame.state().get('selection').first().toJSON();
					var imgUrl = attachment.url;
					if (attachment.sizes && attachment.sizes.thumbnail) {
						imgUrl = attachment.sizes.thumbnail.url;
					} else if (attachment.sizes && attachment.sizes.medium) {
						imgUrl = attachment.sizes.medium.url;
					}
					$input.val(imgUrl).trigger('change');
					$preview.html('<img src="' + imgUrl + '" style="width:48px;height:48px;object-fit:cover;border-radius:50%;border:2px solid #ff2a2a;display:block;" />');
					$removeBtn.show();
				});

				frame.open();
			});

			$(document).on('click', '.newsum-remove-avatar-btn', function(e) {
				e.preventDefault();
				var $btn = $(this);
				var $wrap = $btn.closest('.newsum-widget-media-wrap');
				$wrap.find('.newsum-avatar-url-input').val('').trigger('change');
				$wrap.find('.newsum-avatar-preview').html('');
				$btn.hide();
			});
		});
		</script>
		<?php
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$title       = ! empty( $instance['title'] ) ? preg_replace( '/^\/\/\s*/', '', $instance['title'] ) : '';
		$name        = ! empty( $instance['author_name'] ) ? $instance['author_name'] : '';
		$avatar      = ! empty( $instance['avatar_url'] ) ? $instance['avatar_url'] : '';
		$role        = ! empty( $instance['role'] ) ? $instance['role'] : '';
		$bio         = ! empty( $instance['bio'] ) ? $instance['bio'] : '';
		$twitter     = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
		$github      = ! empty( $instance['github'] ) ? $instance['github'] : '';
		$linkedin    = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
		$website_url = ! empty( $instance['website_url'] ) ? $instance['website_url'] : '';
		?>
		<div class="newsum-author-profile-widget">
			<?php if ( $title ) : ?>
				<?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<div class="newsum-author-card">
				<div class="newsum-author-header">
					<div class="newsum-author-avatar">
						<?php if ( $avatar ) : ?>
							<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" />
						<?php else : ?>
							<div class="newsum-author-avatar-fallback">
								<?php echo esc_html( ! empty( $name ) ? mb_strtoupper( mb_substr( $name, 0, 1 ) ) : 'N' ); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="newsum-author-meta">
						<?php if ( $name ) : ?>
							<h4 class="newsum-author-name"><?php echo esc_html( $name ); ?></h4>
						<?php endif; ?>

						<?php if ( $role ) : ?>
							<span class="newsum-author-role"><?php echo esc_html( $role ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $bio ) : ?>
					<p class="newsum-author-bio"><?php echo wp_kses_post( $bio ); ?></p>
				<?php endif; ?>

				<div class="newsum-author-socials">
					<?php if ( $twitter ) : ?>
						<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" aria-label="X / Twitter">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
						</a>
					<?php endif; ?>
					<?php if ( $github ) : ?>
						<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
						</a>
					<?php endif; ?>
					<?php if ( $linkedin ) : ?>
						<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
						</a>
					<?php endif; ?>
					<?php if ( $website_url ) : ?>
						<a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Website">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
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
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Author Profile', 'newsum' );
		$name        = ! empty( $instance['author_name'] ) ? $instance['author_name'] : '';
		$avatar      = ! empty( $instance['avatar_url'] ) ? $instance['avatar_url'] : '';
		$role        = ! empty( $instance['role'] ) ? $instance['role'] : '';
		$bio         = ! empty( $instance['bio'] ) ? $instance['bio'] : '';
		$twitter     = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
		$github      = ! empty( $instance['github'] ) ? $instance['github'] : '';
		$linkedin    = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
		$website_url = ! empty( $instance['website_url'] ) ? $instance['website_url'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Header / Title:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'author_name' ) ); ?>"><?php esc_html_e( 'Author Name:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'author_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'author_name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>">
		</p>
		<div class="newsum-widget-media-wrap" style="margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
			<label for="<?php echo esc_attr( $this->get_field_id( 'avatar_url' ) ); ?>" style="display:block; margin-bottom: 6px; font-weight: 600;"><?php esc_html_e( 'Author Photo / Avatar:', 'newsum' ); ?></label>
			<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
				<div class="newsum-avatar-preview" style="min-width: 48px; min-height: 48px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%; border: 1px dashed #cbd5e1;">
					<?php if ( $avatar ) : ?>
						<img src="<?php echo esc_url( $avatar ); ?>" style="width: 48px; height: 48px; object-fit: cover; border-radius: 50%; border: 2px solid #ff2a2a; display: block;" />
					<?php else : ?>
						<span style="font-size: 20px; color: #94a3b8;">👤</span>
					<?php endif; ?>
				</div>
				<div>
					<button type="button" class="button button-secondary newsum-upload-avatar-btn">
						<?php esc_html_e( 'Upload / Select Photo', 'newsum' ); ?>
					</button>
					<button type="button" class="button button-link-delete newsum-remove-avatar-btn" style="<?php echo empty( $avatar ) ? 'display:none;' : 'display:inline-block; margin-left: 6px;'; ?>">
						<?php esc_html_e( 'Remove', 'newsum' ); ?>
					</button>
				</div>
			</div>
			<input class="widefat newsum-avatar-url-input" id="<?php echo esc_attr( $this->get_field_id( 'avatar_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'avatar_url' ) ); ?>" type="text" value="<?php echo esc_attr( $avatar ); ?>" placeholder="<?php esc_attr_e( 'Photo URL (or click upload button above)', 'newsum' ); ?>" style="font-size: 11px;">
		</div>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>"><?php esc_html_e( 'Role / Title:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'role' ) ); ?>" type="text" value="<?php echo esc_attr( $role ); ?>" placeholder="<?php esc_attr_e( 'e.g. Lead Tech Correspondent', 'newsum' ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>"><?php esc_html_e( 'Short Bio:', 'newsum' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bio' ) ); ?>" rows="3"><?php echo esc_textarea( $bio ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'X / Twitter URL:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_attr( $twitter ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'github' ) ); ?>"><?php esc_html_e( 'GitHub URL:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'github' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'github' ) ); ?>" type="url" value="<?php echo esc_attr( $github ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn URL:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_attr( $linkedin ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'website_url' ) ); ?>"><?php esc_html_e( 'Website URL:', 'newsum' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'website_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'website_url' ) ); ?>" type="url" value="<?php echo esc_attr( $website_url ); ?>">
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
		$instance                = array();
		$instance['title']       = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['author_name'] = ! empty( $new_instance['author_name'] ) ? sanitize_text_field( $new_instance['author_name'] ) : '';
		$instance['avatar_url']  = ! empty( $new_instance['avatar_url'] ) ? esc_url_raw( $new_instance['avatar_url'] ) : '';
		$instance['role']        = ! empty( $new_instance['role'] ) ? sanitize_text_field( $new_instance['role'] ) : '';
		$instance['bio']         = ! empty( $new_instance['bio'] ) ? wp_kses_post( $new_instance['bio'] ) : '';
		$instance['twitter']     = ! empty( $new_instance['twitter'] ) ? esc_url_raw( $new_instance['twitter'] ) : '';
		$instance['github']      = ! empty( $new_instance['github'] ) ? esc_url_raw( $new_instance['github'] ) : '';
		$instance['linkedin']    = ! empty( $new_instance['linkedin'] ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
		$instance['website_url'] = ! empty( $new_instance['website_url'] ) ? esc_url_raw( $new_instance['website_url'] ) : '';
		return $instance;
	}
}
