<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main" id="primary">

    <section class="error-404 not-found">
        <div class="error-404-inner">
            
            <span class="error-code-badge"><?php esc_html_e( 'ERROR 404', 'newsum' ); ?></span>
            <h1 class="error-title"><?php esc_html_e( 'Story Not Found', 'newsum' ); ?></h1>
            <p class="error-desc">
                <?php esc_html_e( 'The article, dispatch, or page you were looking for could not be located. It may have been archived, renamed, or moved.', 'newsum' ); ?>
            </p>

            <!-- 404 SEARCH FORM -->
            <div class="error-search-wrap">
                <?php get_search_form(); ?>
            </div>

            <!-- EXPLORE CATEGORIES -->
            <div class="error-categories-deck">
                <span class="suggested-label"><?php esc_html_e( 'Or explore active topics:', 'newsum' ); ?></span>
                <div class="topics-pills">
                    <?php
                    $error_cats = get_categories( array( 'number' => 6 ) );
                    foreach ( $error_cats as $ec ) :
                        ?>
                        <a href="<?php echo esc_url( get_category_link( $ec->term_id ) ); ?>" class="tag-pill">
                            <?php echo esc_html( $ec->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="error-home-btn-wrap">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-return-home">&larr; <?php esc_html_e( 'Back to Homepage', 'newsum' ); ?></a>
            </div>

        </div>
    </section>

    <!-- RECENT DISPATCHES FOR 404 VISITORS -->
    <?php
    $recent_404_query = new WP_Query( array(
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
    ) );

    if ( $recent_404_query->have_posts() ) :
        ?>
        <section class="related-posts-section error-recent-posts">
            <div class="feed-header">
                <h2><?php esc_html_e( 'Latest Stories', 'newsum' ); ?></h2>
            </div>
            <div class="feed-grid">
                <?php
                while ( $recent_404_query->have_posts() ) :
                    $recent_404_query->the_post();
                    $cats     = get_the_category();
                    $cat_name = ! empty( $cats ) ? $cats[0]->name : __( 'Story', 'newsum' );
                    $img_url  = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : newsum_get_default_fallback_image( get_the_ID() );
                    ?>
                    <article class="feed-card">
                        <div class="card-img-wrap">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                        </div>
                        <div class="card-content">
                            <span class="category"><?php echo esc_html( $cat_name ); ?></span>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                            <div class="card-footer">
                                <span class="author"><?php esc_html_e( 'By', 'newsum' ); ?> <?php the_author(); ?></span>
                                <span class="read-time"><?php echo newsum_get_reading_time( get_the_ID() ); ?> MIN READ</span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
get_footer();
