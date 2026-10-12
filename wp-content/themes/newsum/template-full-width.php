<?php
/**
 * Template Name: Full Width (No Sidebar)
 *
 * A full-width clean page template without a sidebar, ideal for custom layouts,
 * block-based landing pages, and long-form editorial stories.
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main full-width-page-content" id="primary">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'full-width-article' ); ?>>

            <header class="page-hero">
                <div class="page-hero-inner">
                    <span class="browsing-label"><?php esc_html_e( 'PAGE OVERVIEW', 'newsum' ); ?></span>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </div>
            </header>

            <div class="page-content-body full-width-container">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'newsum' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <div class="page-comments-wrap full-width-container">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
