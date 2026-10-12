<?php
/**
 * The template for displaying archive pages (Category, Tag, Author, Date)
 *
 * @package NewSum
 */

get_header();

$total_posts    = $GLOBALS['wp_query']->found_posts;
$archive_layout = get_theme_mod( 'newsum_archive_layout', 'grid' );
$grid_cols      = get_theme_mod( 'newsum_grid_columns', '3' );
$sidebar_pos    = get_theme_mod( 'newsum_archive_sidebar', 'right' );
$excerpt_length = get_theme_mod( 'newsum_excerpt_length', 22 );
$show_filters   = get_theme_mod( 'newsum_archive_show_filters', true );

// Archive header & meta toggles
$show_desc         = get_theme_mod( 'newsum_archive_show_desc', true );
$show_count        = get_theme_mod( 'newsum_archive_show_count', true );
$show_thumb        = get_theme_mod( 'newsum_archive_show_thumb', true );
$show_cat          = get_theme_mod( 'newsum_archive_show_cat', true );
$show_author       = get_theme_mod( 'newsum_archive_show_author', true );
$show_date         = get_theme_mod( 'newsum_archive_show_date', true );
$show_reading_time = get_theme_mod( 'newsum_archive_show_reading_time', true );
$show_summary_btn  = get_theme_mod( 'newsum_archive_show_summary_btn', true );
$show_bookmark     = get_theme_mod( 'newsum_archive_show_bookmark', true );
$show_excerpt      = get_theme_mod( 'newsum_archive_show_excerpt', true );
$show_read_more    = get_theme_mod( 'newsum_archive_show_read_more', false );
$read_more_text    = get_theme_mod( 'newsum_archive_read_more_text', __( 'Read Story →', 'newsum' ) );

// Determine archive context
$is_author_archive = is_author();
$is_cat_archive    = is_category();
$is_tag_archive    = is_tag();
$current_cat_id    = $is_cat_archive ? get_queried_object_id() : 0;

$archive_kicker = __( 'ARCHIVE', 'newsum' );
$archive_title  = '';

if ( $is_cat_archive ) {
    $archive_kicker = __( 'CATEGORY', 'newsum' );
    $archive_title  = single_cat_title( '', false );
} elseif ( $is_tag_archive ) {
    $archive_kicker = __( 'TOPIC', 'newsum' );
    $archive_title  = single_tag_title( '', false );
} elseif ( $is_author_archive ) {
    $archive_kicker = __( 'CORRESPONDENT PROFILE', 'newsum' );
    $author_obj     = get_queried_object();
    $archive_title  = $author_obj ? $author_obj->display_name : get_the_author();
} elseif ( is_date() ) {
    $archive_kicker = __( 'CHRONOLOGICAL ARCHIVE', 'newsum' );
    $archive_title  = get_the_archive_title();
} else {
    $archive_title  = get_the_archive_title();
}

$archive_desc = get_the_archive_description();
?>

<main class="site-content site-main archive-page-content" id="primary">

    <?php if ( $is_author_archive && ! empty( $author_obj ) ) : ?>
    <!-- ═══ AUTHOR MASTHEAD HERO ═══════════════════════════ -->
    <header class="archive-masthead author-masthead-hero">
        <div class="archive-masthead-inner author-hero-grid">
            <?php if ( function_exists( 'newsum_breadcrumbs' ) ) newsum_breadcrumbs(); ?>
            <div class="author-avatar-wrap">
                <?php echo get_avatar( $author_obj->ID, 110, '', esc_attr( $author_obj->display_name ), array( 'class' => 'author-masthead-img' ) ); ?>
            </div>
            <div class="author-info-wrap">
                <span class="archive-kicker"><?php echo esc_html( $archive_kicker ); ?></span>
                <h1 class="archive-title author-name"><?php echo esc_html( $author_obj->display_name ); ?></h1>
                <?php if ( ! empty( $author_obj->description ) && $show_desc ) : ?>
                    <p class="author-bio-desc"><?php echo esc_html( $author_obj->description ); ?></p>
                <?php endif; ?>
                <div class="author-meta-badges">
                    <span class="meta-badge count-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <?php
                        printf(
                            /* translators: %s: number of articles */
                            esc_html( _n( '%s Dispatched Story', '%s Dispatched Stories', $total_posts, 'newsum' ) ),
                            number_format_i18n( $total_posts )
                        );
                        ?>
                    </span>
                    <?php if ( ! empty( $author_obj->user_url ) ) : ?>
                        <a href="<?php echo esc_url( $author_obj->user_url ); ?>" class="meta-badge link-badge" target="_blank" rel="noopener noreferrer">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <?php esc_html_e( 'Website', 'newsum' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <?php else : ?>
    <!-- ═══ STANDARD TOPIC / CATEGORY / DATE MASTHEAD ══════ -->
    <header class="archive-masthead category-masthead-hero">
        <div class="archive-masthead-inner">
            <?php if ( function_exists( 'newsum_breadcrumbs' ) ) newsum_breadcrumbs(); ?>
            <div class="archive-masthead-header">
                <span class="archive-kicker"><?php echo esc_html( $archive_kicker ); ?></span>
                <?php if ( $show_count ) : ?>
                <span class="archive-count-pill">
                    <?php
                    printf(
                        /* translators: %s: number of articles */
                        esc_html( _n( '%s Story Published', '%s Stories Published', $total_posts, 'newsum' ) ),
                        number_format_i18n( $total_posts )
                    );
                    ?>
                </span>
                <?php endif; ?>
            </div>
            <h1 class="archive-title"><?php echo esc_html( $archive_title ); ?></h1>
            <?php if ( ! empty( $archive_desc ) && $show_desc ) : ?>
                <div class="archive-desc"><?php echo wp_kses_post( $archive_desc ); ?></div>
            <?php endif; ?>

            <?php
            // Sibling & Related Category Filter Pills Bar
            if ( $show_filters && ( $is_cat_archive || $is_tag_archive ) ) :
                $cats = get_categories( array(
                    'number'     => 10,
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'hide_empty' => true,
                ) );
                if ( ! empty( $cats ) ) :
                ?>
                <nav class="archive-filter-bar" aria-label="<?php esc_attr_e( 'Filter by Topic', 'newsum' ); ?>">
                    <span class="filter-label"><?php esc_html_e( 'TOPICS:', 'newsum' ); ?></span>
                    <div class="filter-pills-track">
                        <?php foreach ( $cats as $cat ) :
                            $is_active = ( $current_cat_id === $cat->term_id ) ? ' active' : '';
                        ?>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="filter-pill<?php echo esc_attr( $is_active ); ?>">
                                <?php echo esc_html( $cat->name ); ?>
                                <span class="pill-count"><?php echo esc_html( $cat->count ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </nav>
                <?php
                endif;
            endif;
            ?>
        </div>
    </header>
    <?php endif; ?>

    <!-- ═══ ARCHIVE MAIN CONTENT GRID & SIDEBAR ════════════ -->
    <div class="content-sidebar-wrap archive-layout-wrap sidebar-pos-<?php echo esc_attr( $sidebar_pos ); ?>">

        <div class="main-content-col">

            <?php if ( have_posts() ) : ?>
                
                <?php
                $feed_classes = array( 'archive-feed-container' );
                if ( 'list' === $archive_layout ) {
                    $feed_classes[] = 'feed-layout-list';
                } elseif ( 'compact' === $archive_layout ) {
                    $feed_classes[] = 'feed-layout-compact';
                } else {
                    $feed_classes[] = 'feed-layout-grid';
                    $feed_classes[] = ( '2' === $grid_cols ) ? 'grid-2-col' : 'grid-3-col';
                }
                ?>

                <div class="<?php echo esc_attr( implode( ' ', $feed_classes ) ); ?>">
                    <?php
                    $post_counter = 0;
                    while ( have_posts() ) :
                        the_post();
                        $post_counter++;
                        newsum_render_archive_card( get_post() );
                        do_action( 'newsum_after_loop_post', $post_counter );
                    endwhile;
                    ?>
                </div>

                <!-- ═══ PAGINATION ════════════════════════════════ -->
                <?php
                $pagination_type = get_theme_mod( 'newsum_archive_pagination_type', 'load_more' );
                $load_more_text  = get_theme_mod( 'newsum_archive_load_more_text', __( 'Load More Stories', 'newsum' ) );
                global $wp_query;
                $max_pages     = $wp_query->max_num_pages;
                $paged         = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
                $has_next_page = $paged < $max_pages;

                $ajax_query_vars = array(
                    'cat'           => get_query_var( 'cat' ),
                    'category_name' => get_query_var( 'category_name' ),
                    'tag'           => get_query_var( 'tag' ),
                    'tag_id'        => get_query_var( 'tag_id' ),
                    'author'        => get_query_var( 'author' ),
                    'author_name'   => get_query_var( 'author_name' ),
                    'year'          => get_query_var( 'year' ),
                    'monthnum'      => get_query_var( 'monthnum' ),
                    'day'           => get_query_var( 'day' ),
                    's'             => get_query_var( 's' ),
                    'post_type'     => get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : 'post',
                );
                $ajax_query_vars = array_filter( $ajax_query_vars );
                ?>
                <nav class="archive-pagination<?php echo 'numeric' !== $pagination_type ? ' has-ajax-pagination' : ''; ?>" 
                     aria-label="<?php esc_attr_e( 'Stories Pagination', 'newsum' ); ?>"
                     data-pagination-type="<?php echo esc_attr( $pagination_type ); ?>"
                     data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
                     data-current-page="<?php echo esc_attr( $paged ); ?>"
                     data-query="<?php echo esc_attr( wp_json_encode( $ajax_query_vars ) ); ?>">
                    
                    <?php if ( 'numeric' !== $pagination_type && $has_next_page ) : ?>
                        <div class="archive-load-more-wrap">
                            <button type="button" class="btn-archive-load-more" id="archiveLoadMoreBtn">
                                <span class="btn-text"><?php echo esc_html( $load_more_text ); ?></span>
                                <svg class="spinner-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="archive-classic-pagination<?php echo 'numeric' !== $pagination_type ? ' screen-reader-text' : ''; ?>">
                        <?php
                        the_posts_pagination( array(
                            'mid_size'           => 2,
                            'prev_text'          => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> <span>' . __( 'Previous', 'newsum' ) . '</span>',
                            'next_text'          => '<span>' . __( 'Next', 'newsum' ) . '</span> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                            'before_page_number' => '',
                        ) );
                        ?>
                    </div>
                </nav>

            <?php else : ?>
                <!-- ═══ EMPTY ARCHIVE STATE ════════════════════════ -->
                <div class="archive-empty-state">
                    <div class="empty-icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="15" x2="16" y2="15"></line><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                    </div>
                    <h2 class="empty-title"><?php esc_html_e( 'No Dispatches Found', 'newsum' ); ?></h2>
                    <p class="empty-desc"><?php esc_html_e( 'There are currently no published stories under this topic archive.', 'newsum' ); ?></p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-return-home">
                            &larr; <?php esc_html_e( 'Return to Frontpage', 'newsum' ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <?php if ( 'none' !== $sidebar_pos && 'narrow' !== $sidebar_pos ) : ?>
        <!-- ═══ ARCHIVE SIDEBAR ════════════════════════════════ -->
        <aside class="sidebar-column archive-sidebar" aria-label="<?php esc_attr_e( 'Archive Sidebar', 'newsum' ); ?>">
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
                    <h3 class="widget-title"><?php esc_html_e( 'Trending Wire', 'newsum' ); ?></h3>
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

                <div class="sidebar-widget newsletter-widget">
                    <h3 class="widget-title"><?php esc_html_e( 'The Daily Dossier', 'newsum' ); ?></h3>
                    <p><?php esc_html_e( 'Get the day’s top journalism and essential tech commentary delivered to your inbox.', 'newsum' ); ?></p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed to Daily Dossier.');">
                        <input type="email" placeholder="<?php esc_attr_e( 'user@example.com', 'newsum' ); ?>" required>
                        <button type="submit"><?php esc_html_e( 'Subscribe', 'newsum' ); ?></button>
                    </form>
                </div>
            <?php endif; ?>
        </aside>
        <?php endif; ?>

    </div>

</main>

<?php
get_footer();
