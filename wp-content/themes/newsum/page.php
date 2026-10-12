<?php
/**
 * The template for displaying all pages
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main" id="primary">

    <?php
    while ( have_posts() ) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-page-article' ); ?>>
            
            <!-- PAGE HERO -->
            <header class="page-hero">
                <div class="page-hero-inner">
                    <?php if ( function_exists( 'newsum_breadcrumbs' ) ) newsum_breadcrumbs(); ?>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </div>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="page-hero-image-wrap">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'page-hero-image' ) ); ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- PAGE CONTENT -->
            <div class="page-content-wrapper">
                <div class="page-body article-body">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'newsum' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                    ) );
                    ?>
                </div>

                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
