<?php
/**
 * The template for displaying search results pages
 *
 * @package NewSum
 */

get_header();

$search_query = get_search_query();
$total_posts  = $GLOBALS['wp_query']->found_posts;
?>

<main class="site-content site-main" id="primary">

    <!-- SEARCH HERO HEADER -->
    <header class="category-header search-header">
        <div class="category-header-inner">
            <span class="browsing-label"><?php esc_html_e( 'Search Results', 'newsum' ); ?></span>
            <h1 class="category-title">&ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;</h1>
            
            <div class="archive-count-badge">
                <span>
                    <?php
                    printf(
                        /* translators: %s: number of results */
                        esc_html( _n( '%s Result Found', '%s Results Found', $total_posts, 'newsum' ) ),
                        number_format_i18n( $total_posts )
                    );
                    ?>
                </span>
            </div>

            <!-- SEARCH REFINEMENT FORM -->
            <div class="search-refine-wrap">
                <?php get_search_form(); ?>
            </div>
        </div>
    </header>

    <!-- MAIN SEARCH CONTENT -->
    <div class="content-sidebar-wrap archive-wrap">
        
        <div class="main-content-col">
            
            <?php if ( have_posts() ) : ?>
                <div class="feed-grid main-feed-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        $post_id   = get_the_ID();
                        $read_time = newsum_get_reading_time( $post_id );
                        $cats      = get_the_category();
                        $cat_name  = ! empty( $cats ) ? $cats[0]->name : __( 'Article', 'newsum' );
                        $img_url   = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'medium_large' ) : newsum_get_default_fallback_image( $post_id );
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'feed-card' ); ?>>
                            <div class="card-img-wrap">
                                <a href="<?php the_permalink(); ?>" class="card-thumb-link" tabindex="-1" aria-hidden="true">
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                </a>
                                <div class="card-action-deck">
                                    <span class="card-action-pill card-read-time">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <?php echo esc_html( $read_time ); ?>m read
                                    </span>
                                </div>
                            </div>
                            <div class="card-content">
                                <span class="category"><?php echo esc_html( $cat_name ); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                                <div class="card-footer">
                                    <span class="author"><?php esc_html_e( 'By', 'newsum' ); ?> <?php the_author(); ?></span>
                                    <span class="read-time"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- PAGINATION -->
                <div class="archive-pagination-wrap">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __( '&larr; Previous', 'newsum' ),
                        'next_text' => __( 'Next &rarr;', 'newsum' ),
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <div class="no-posts-found search-no-results">
                    <h2><?php esc_html_e( 'No Matches Found', 'newsum' ); ?></h2>
                    <p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with different keywords or browse our trending categories below.', 'newsum' ); ?></p>
                    
                    <div class="search-suggested-topics">
                        <span class="suggested-label"><?php esc_html_e( 'Suggested Topics:', 'newsum' ); ?></span>
                        <div class="topics-pills">
                            <?php
                            $suggested_cats = get_categories( array( 'number' => 6 ) );
                            foreach ( $suggested_cats as $sc ) :
                                ?>
                                <a href="<?php echo esc_url( get_category_link( $sc->term_id ) ); ?>" class="tag-pill">
                                    <?php echo esc_html( $sc->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-return-home">&larr; <?php esc_html_e( 'Return to Homepage', 'newsum' ); ?></a>
                </div>
            <?php endif; ?>

        </div>

        <!-- SIDEBAR -->
        <aside class="sidebar-column">
            <?php
            if ( is_active_sidebar( 'sidebar-1' ) ) :
                dynamic_sidebar( 'sidebar-1' );
            else :
                $sidebar_query = new WP_Query( array(
                    'posts_per_page'      => 4,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 1,
                ) );
                ?>
                <div class="sidebar-widget">
                    <h3 class="widget-title"><?php esc_html_e( 'Trending Stories', 'newsum' ); ?></h3>
                    <div class="sidebar-posts">
                        <?php
                        if ( $sidebar_query->have_posts() ) :
                            $rank = 1;
                            while ( $sidebar_query->have_posts() ) :
                                $sidebar_query->the_post();
                                ?>
                                <div class="sidebar-post-item">
                                    <span class="rank"><?php echo str_pad( $rank++, 2, '0', STR_PAD_LEFT ); ?></span>
                                    <div>
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <span class="meta"><?php echo newsum_get_reading_time( get_the_ID() ); ?> MIN READ • <?php echo esc_html( get_the_date( 'd M' ) ); ?></span>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </aside>

    </div>

</main>

<?php
get_footer();
