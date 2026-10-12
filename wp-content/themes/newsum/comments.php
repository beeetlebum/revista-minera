<?php
/**
 * The template for displaying comments
 *
 * @package NewSum
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <div class="comments-header">
            <h3 class="comments-title">
                <?php
                $comment_count = get_comments_number();
                if ( '1' === $comment_count ) {
                    printf(
                        /* translators: 1: title. */
                        esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'newsum' ),
                        '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                    );
                } else {
                    printf(
                        /* translators: 1: comment count number, 2: title. */
                        esc_html( _nx( '%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'newsum' ) ),
                        number_format_i18n( $comment_count )
                    );
                }
                ?>
            </h3>
        </div>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'reply_text'  => __( 'Reply', 'newsum' ),
            ) );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

        <?php if ( ! comments_open() ) : ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed for this story.', 'newsum' ); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    $commenter     = wp_get_current_commenter();
    $req           = get_option( 'require_name_email' );
    $aria_req      = ( $req ? " aria-required='true'" : '' );
    $consent       = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

    $fields = array(
        'author' => '<div class="comment-form-author-wrap"><label for="author">' . esc_html__( 'Name', 'newsum' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . ' placeholder="' . esc_attr__( 'Your name', 'newsum' ) . '"></div>',
        'email'  => '<div class="comment-form-email-wrap"><label for="email">' . esc_html__( 'Email', 'newsum' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' placeholder="' . esc_attr__( 'user@example.com', 'newsum' ) . '"></div>',
        'url'    => '<div class="comment-form-url-wrap"><label for="url">' . esc_html__( 'Website', 'newsum' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" placeholder="' . esc_attr__( 'https://example.com', 'newsum' ) . '"></div>',
        'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
                     '<label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name and email in this browser for the next time I comment.', 'newsum' ) . '</label></p>',
    );

    comment_form( array(
        'title_reply'          => __( 'Join the Discussion', 'newsum' ),
        'title_reply_to'       => __( 'Reply to %s', 'newsum' ),
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'    => '</h3>',
        'cancel_reply_link'    => __( 'Cancel Reply', 'newsum' ),
        'label_submit'         => __( 'Post Comment', 'newsum' ),
        'class_submit'         => 'comment-submit-btn',
        'comment_field'        => '<div class="comment-form-comment"><label for="comment">' . esc_html__( 'Your Comment', 'newsum' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" placeholder="' . esc_attr__( 'Share your perspectives or thoughts...', 'newsum' ) . '"></textarea></div>',
        'fields'               => $fields,
        'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'newsum' ) . '</span></p>',
    ) );
    ?>

</div>
