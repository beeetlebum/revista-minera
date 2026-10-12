<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main" id="primary">

    <div class="content-sidebar-wrap archive-wrap">
        <div class="main-content-col">

            <?php if ( have_posts() ) : ?>

                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <header class="page-hero">
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <div class="feed-grid main-feed-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        $post_id   = get_the_ID();
                        $read_time = newsum_get_reading_time( $post_id );
                        $cats      = get_the_category();
                        $cat_name  = ! empty( $cats ) ? $cats[0]->name : '';
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'feed-card' ); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="card-img-wrap">
                                    <a href="<?php the_permalink(); ?>" class="card-thumb-link" tabindex="-1" aria-hidden="true">
                                        <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                                    </a>
                                    <div class="card-action-deck">
                                        <span class="card-action-pill card-read-time">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <?php echo esc_html( $read_time ); ?>m read
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="card-content">
                                <?php if ( $cat_name ) : ?>
                                    <span class="category"><?php echo esc_html( $cat_name ); ?></span>
                                <?php endif; ?>
                                
                                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>

                                <div class="card-footer">
                                    <span class="author"><?php esc_html_e( 'By', 'newsum' ); ?> <?php the_author(); ?></span>
                                    <span class="read-time"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- PAGINATION -->
                <nav class="archive-pagination-wrap" aria-label="<?php esc_attr_e( 'Posts navigation', 'newsum' ); ?>">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => esc_html__( '&larr; Previous', 'newsum' ),
                        'next_text' => esc_html__( 'Next &rarr;', 'newsum' ),
                    ) );
                    ?>
                </nav>

            <?php else : ?>

                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'newsum' ); ?></h1>
                    </header>

                    <div class="page-content">
                        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
                            <p>
                                <?php
                                printf(
                                    /* translators: 1: link to WP admin new post page. */
                                    wp_kses(
                                        __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'newsum' ),
                                        array(
                                            'a' => array(
                                                'href' => array(),
                                            ),
                                        )
                                    ),
                                    esc_url( admin_url( 'post-new.php' ) )
                                );
                                ?>
                            </p>
                        <?php else : ?>
                            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'newsum' ); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </section>

            <?php endif; ?>

        </div>

        <?php get_sidebar(); ?>

    </div>

</main>

<?php
get_footer();
