<?php
/**
 * AJAX Archive Pagination & Card Rendering
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render a single archive story card
 *
 * @param int|WP_Post|null $post_item Post ID or object.
 */
function newsum_render_archive_card( $post_item = null ) {
    $post_obj = get_post( $post_item );
    if ( ! $post_obj ) {
        return;
    }

    $post_id          = $post_obj->ID;
    $read_time        = newsum_get_reading_time( $post_id );
    $cats             = get_the_category( $post_id );
    $cat_name         = ! empty( $cats ) ? $cats[0]->name : '';
    $img_url          = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'medium_large' ) : newsum_get_default_fallback_image( $post_id );
    $author_id        = $post_obj->post_author;
    $author_name      = get_the_author_meta( 'display_name', $author_id );
    $author_posts_url = get_author_posts_url( $author_id );

    // Customizer display options
    $show_thumb        = get_theme_mod( 'newsum_archive_show_thumb', true );
    $show_cat          = get_theme_mod( 'newsum_archive_show_cat', true );
    $show_reading_time = get_theme_mod( 'newsum_archive_show_reading_time', true );
    $show_summary_btn  = get_theme_mod( 'newsum_archive_show_summary_btn', true );
    $show_bookmark     = get_theme_mod( 'newsum_archive_show_bookmark', true );
    $show_excerpt      = get_theme_mod( 'newsum_archive_show_excerpt', true );
    $excerpt_length    = get_theme_mod( 'newsum_archive_excerpt_length', 22 );
    $show_read_more    = get_theme_mod( 'newsum_archive_show_read_more', false );
    $read_more_text    = get_theme_mod( 'newsum_archive_read_more_text', __( 'Read Full Story →', 'newsum' ) );
    $show_author       = get_theme_mod( 'newsum_archive_show_author', true );
    $show_date         = get_theme_mod( 'newsum_archive_show_date', true );
    ?>
    <article id="post-<?php echo esc_attr( $post_id ); ?>" <?php post_class( 'archive-post-card', $post_id ); ?>>
        
        <?php if ( $show_thumb ) : ?>
        <div class="card-media-wrap">
            <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="card-media-link" tabindex="-1" aria-hidden="true">
                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" loading="lazy" class="card-thumb-img">
            </a>
            <?php if ( $cat_name && $show_cat ) : ?>
                <span class="card-category-badge"><?php echo esc_html( $cat_name ); ?></span>
            <?php endif; ?>
            
            <?php if ( $show_reading_time || $show_summary_btn || $show_bookmark ) : ?>
            <div class="card-action-deck">
                <?php if ( $show_reading_time ) : ?>
                <span class="card-action-pill card-read-time">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <?php echo esc_html( $read_time ); ?>m
                </span>
                <?php endif; ?>
                <?php if ( $show_summary_btn ) : ?>
                <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" data-post-excerpt="<?php echo esc_attr( wp_trim_words( get_the_excerpt( $post_id ), 30 ) ); ?>" data-post-link="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    <span><?php esc_html_e( '30s', 'newsum' ); ?></span>
                </button>
                <?php endif; ?>
                <?php if ( $show_bookmark && function_exists( 'newsum_render_bookmark_button' ) ) : ?>
                    <?php newsum_render_bookmark_button( $post_id, get_the_title( $post_id ), get_permalink( $post_id ) ); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php elseif ( $cat_name && $show_cat ) : ?>
        <div class="card-cat-deck">
            <span class="card-category-badge"><?php echo esc_html( $cat_name ); ?></span>
        </div>
        <?php endif; ?>

        <div class="card-body-wrap">
            <h2 class="card-title">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
            </h2>
            
            <?php if ( $show_excerpt ) : ?>
            <p class="card-excerpt"><?php echo wp_trim_words( get_the_excerpt( $post_id ), absint( $excerpt_length ) ); ?></p>
            <?php endif; ?>

            <?php if ( $show_read_more && ! empty( $read_more_text ) ) : ?>
            <div class="card-read-more-wrap">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="card-read-more-link">
                    <?php echo wp_kses_post( $read_more_text ); ?>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ( $show_author || $show_date ) : ?>
            <footer class="card-meta-footer">
                <?php if ( $show_author ) : ?>
                <div class="author-byline">
                    <span class="by-text"><?php esc_html_e( 'By', 'newsum' ); ?></span>
                    <a href="<?php echo esc_url( $author_posts_url ); ?>" class="author-link">
                        <strong><?php echo esc_html( $author_name ); ?></strong>
                    </a>
                </div>
                <?php endif; ?>
                <?php if ( $show_date ) : ?>
                <div class="date-meta">
                    <time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y', $post_id ) ); ?></time>
                </div>
                <?php endif; ?>
            </footer>
            <?php endif; ?>
        </div>

    </article>
    <?php
}

/**
 * Handle AJAX Load More Requests
 */
function newsum_ajax_load_more() {
    check_ajax_referer( 'newsum_ajax_nonce', 'nonce' );

    $page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    if ( $page < 1 ) {
        $page = 1;
    }

    $raw_query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '{}';
    $query_vars = json_decode( $raw_query, true );
    if ( ! is_array( $query_vars ) ) {
        $query_vars = array();
    }

    // Default query arguments
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'paged'          => $page,
        'posts_per_page' => get_option( 'posts_per_page', 10 ),
    );

    // Whitelist allowed query parameters for security
    $allowed_keys = array(
        'cat', 'category_name', 'tag', 'tag_id',
        'author', 'author_name', 'year', 'monthnum',
        'day', 's', 'post_type'
    );

    foreach ( $allowed_keys as $key ) {
        if ( ! empty( $query_vars[ $key ] ) ) {
            if ( in_array( $key, array( 'cat', 'tag_id', 'author', 'year', 'monthnum', 'day' ), true ) ) {
                $args[ $key ] = absint( $query_vars[ $key ] );
            } else {
                $args[ $key ] = sanitize_text_field( $query_vars[ $key ] );
            }
        }
    }

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            newsum_render_archive_card( get_post() );
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    $max_pages = $query->max_num_pages;
    $has_more  = $page < $max_pages;

    wp_send_json_success( array(
        'html'         => $html,
        'current_page' => $page,
        'max_pages'    => $max_pages,
        'has_more'     => $has_more,
    ) );
}
add_action( 'wp_ajax_newsum_load_more', 'newsum_ajax_load_more' );
add_action( 'wp_ajax_nopriv_newsum_load_more', 'newsum_ajax_load_more' );
