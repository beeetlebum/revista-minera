<?php
/**
 * Comments template.
 *
 * @package Novaflow
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( post_password_required() ) {
	return;
}

$comment_count = (int) get_comments_number();
?>
<section id="comments" class="nf-comments-area nf-comments-section">
	<h2 class="nf-article-section-title">
		<span class="nf-comments-title-icon" aria-hidden="true"><?php novaflow_icon( 'comment' ); ?></span>
		<?php
		if ( have_comments() ) {
			printf(
				/* translators: %s: Number of comments. */
				esc_html( _nx( '%s comment', '%s comments', $comment_count, 'comments title', 'novaflow' ) ),
				esc_html( number_format_i18n( $comment_count ) )
			);
		} else {
			esc_html_e( 'Leave a comment', 'novaflow' );
		}
		?>
	</h2>

	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && $comment_count ) : ?>
		<p><?php esc_html_e( 'Comments are closed.', 'novaflow' ); ?></p>
	<?php endif; ?>

	<?php
	if ( comments_open() ) {
		$commenter      = wp_get_current_commenter();
		$require_email  = (bool) get_option( 'require_name_email' );
		$required       = $require_email ? ' required' : '';
		$aria_required  = $require_email ? ' aria-required="true"' : '';
		$comment_fields = array(
			'author' => '<p class="comment-form-author"><label class="screen-reader-text" for="author">' . esc_html__( 'Name', 'novaflow' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_attr__( 'Your name', 'novaflow' ) . '" maxlength="245" autocomplete="name"' . $aria_required . $required . '></p>',
			'email'  => '<p class="comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Email', 'novaflow' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_attr__( 'Your email address', 'novaflow' ) . '" maxlength="100" autocomplete="email"' . $aria_required . $required . '></p>',
		);

		if ( get_option( 'show_comments_cookies_opt_in' ) ) {
			$consent                   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
			$comment_fields['cookies'] = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . '><label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name and email in this browser for the next time I comment.', 'novaflow' ) . '</label></p>';
		}

		comment_form(
			array(
				'class_form'           => 'comment-form nf-article-comment-form',
				'class_submit'         => 'submit nf-comment-submit',
				'title_reply_before'   => '<h3 class="nf-comment-reply-title">',
				'title_reply_after'    => '</h3>',
				'title_reply'          => __( 'Write a Comment', 'novaflow' ),
				'label_submit'         => __( 'Submit Comment', 'novaflow' ),
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'fields'               => $comment_fields,
				'comment_field'        => '<p class="comment-form-comment"><label class="screen-reader-text" for="comment">' . esc_html__( 'Comment', 'novaflow' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Write a comment...', 'novaflow' ) . '" maxlength="65525" required aria-required="true"></textarea></p>',
				'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s <span aria-hidden="true">&rarr;</span></button>',
			)
		);
	}
	?>
</section>
