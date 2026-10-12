<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package NewSum
 */

$show_brand       = get_theme_mod( 'newsum_footer_show_brand', true );
$footer_tagline   = get_theme_mod( 'newsum_footer_tagline', get_bloginfo( 'description' ) ?: __( 'Delivering clarity, depth, and precision across every story.', 'newsum' ) );
$show_col1                = get_theme_mod( 'newsum_footer_show_col1', true );
$show_col2                = get_theme_mod( 'newsum_footer_show_col2', true );
$show_col3                = get_theme_mod( 'newsum_footer_show_col3', true );
$show_col4                = get_theme_mod( 'newsum_footer_show_col4', true );
$footer_newsletter_desc   = get_theme_mod( 'newsum_footer_newsletter_desc', __( 'Direct weekly briefing on frontier AI models, hardware leaks, and cybersecurity disclosures.', 'newsum' ) );
$footer_newsletter_action = get_theme_mod( 'newsum_footer_newsletter_action', '#' );
$col1_title               = get_theme_mod( 'newsum_footer_col1_title', __( 'Trending', 'newsum' ) );
$col2_title               = get_theme_mod( 'newsum_footer_col2_title', __( 'Coverage', 'newsum' ) );
$col3_title               = get_theme_mod( 'newsum_footer_col3_title', __( 'Company', 'newsum' ) );
$col4_title               = get_theme_mod( 'newsum_footer_col4_title', __( 'Terminal Dispatch', 'newsum' ) );
$gallery_title            = get_theme_mod( 'newsum_footer_gallery_title', __( 'Tech Gallery', 'newsum' ) );
$copyright_raw            = get_theme_mod( 'newsum_footer_copyright', __( '© {year} {site}. All rights reserved.', 'newsum' ) );
$show_back_to_top         = get_theme_mod( 'newsum_footer_back_to_top', true );

// Dynamic tokens in copyright
$copyright = str_replace(
    array( '{year}', '{site}' ),
    array( date( 'Y' ), get_bloginfo( 'name' ) ),
    $copyright_raw
);

// Dynamic footer badge tagline
$badge_intel = get_theme_mod( 'newsum_footer_badge_intel', get_bloginfo( 'description' ) ?: __( 'Independent Journalism & Analysis', 'newsum' ) );

// Query 3 trending/recent posts
$footer_query = new WP_Query( array(
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
) );
$footer_posts = $footer_query->posts;

// Query categories
$footer_categories = get_categories( array(
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 5,
    'hide_empty' => true,
) );

// Gallery images (Dynamic query for latest post thumbnails)
$gallery_query = new WP_Query( array(
    'posts_per_page'      => 6,
    'post_status'         => 'publish',
    'meta_key'            => '_thumbnail_id',
    'ignore_sticky_posts' => 1,
) );
$footer_gallery_images = array();
if ( $gallery_query->have_posts() ) {
    while ( $gallery_query->have_posts() ) {
        $gallery_query->the_post();
        $footer_gallery_images[] = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
    }
    wp_reset_postdata();
}
if ( empty( $footer_gallery_images ) ) {
    for ( $i = 0; $i < 6; $i++ ) {
        $footer_gallery_images[] = newsum_get_default_fallback_image( $i );
    }
}
?>

    <?php do_action( 'newsum_before_footer' ); ?>

    <footer class="site-footer" id="siteFooterWrap">
        <div class="footer-inner">
            
            <?php if ( $show_brand ) : ?>
            <div class="footer-massive-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo massive">
                    <?php bloginfo( 'name' ); ?><span>.</span>
                </a>
                <?php if ( ! empty( $footer_tagline ) ) : ?>
                    <p class="footer-tagline"><?php echo esc_html( $footer_tagline ); ?></p>
                <?php endif; ?>
                <div class="footer-edition-badge">
                    <span class="badge-dot">●</span>
                    <span class="badge-edition"><?php echo esc_html( date( 'Y.m' ) ); ?> <?php esc_html_e( 'EDITION', 'newsum' ); ?></span>
                    <span class="badge-sep">•</span>
                    <span class="badge-intel"><?php echo esc_html( $badge_intel ); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>
            <?php else : ?>
                <div class="footer-grid">
                    
                    <?php if ( $show_col1 ) : ?>
                    <!-- 1. TRENDING STORIES -->
                    <div class="footer-col footer-col-trending">
                        <h4><?php echo esc_html( $col1_title ); ?></h4>
                        <div class="footer-mini-feed">
                            <?php
                            if ( ! empty( $footer_posts ) ) {
                                foreach ( $footer_posts as $f_idx => $f_post ) {
                                    $fp_id    = $f_post->ID;
                                    $fp_title = get_the_title( $fp_id );
                                    $fp_link  = get_permalink( $fp_id );
                                    $fp_img   = has_post_thumbnail( $fp_id ) ? get_the_post_thumbnail_url( $fp_id, 'thumbnail' ) : newsum_get_default_fallback_image( $f_idx );
                                    $fp_date  = get_the_date( 'd M', $fp_id );
                                    ?>
                                    <a href="<?php echo esc_url( $fp_link ); ?>" class="footer-mini-item">
                                        <div class="footer-mini-thumb">
                                            <img src="<?php echo esc_url( $fp_img ); ?>" alt="<?php echo esc_attr( $fp_title ); ?>" loading="lazy">
                                        </div>
                                        <div class="footer-mini-info">
                                            <span class="footer-mini-title"><?php echo esc_html( $fp_title ); ?></span>
                                            <span class="footer-mini-date"><?php echo esc_html( $fp_date ); ?></span>
                                        </div>
                                    </a>
                                    <?php
                                }
                            } else {
                                ?>
                                <p class="footer-empty-notice"><?php esc_html_e( 'No stories published yet.', 'newsum' ); ?></p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ( $show_col2 ) : ?>
                    <!-- 2. COVERAGE / CATEGORIES -->
                    <div class="footer-col footer-col-coverage">
                        <h4><?php echo esc_html( $col2_title ); ?></h4>
                        <ul class="footer-cat-list">
                            <?php
                            if ( ! empty( $footer_categories ) ) {
                                foreach ( $footer_categories as $cat ) {
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                            <span class="cat-name"><?php echo esc_html( $cat->name ); ?></span>
                                            <span class="cat-count"><?php echo esc_html( $cat->count ); ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="footer-empty-notice"><?php esc_html_e( 'No categories found.', 'newsum' ); ?></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if ( $show_col3 ) : ?>
                    <!-- 3. COMPANY & SOCIALS -->
                    <div class="footer-col footer-col-company">
                        <h4><?php echo esc_html( $col3_title ); ?></h4>
                        <?php if ( has_nav_menu( 'drawer-menu' ) ) : ?>
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'drawer-menu',
                                'menu_class'     => 'footer-links-list',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ) );
                            ?>
                        <?php else : ?>
                            <ul class="footer-links-list">
                                <?php
                                $pages = get_pages( array(
                                    'number'      => 5,
                                    'sort_column' => 'menu_order, post_title',
                                    'parent'      => 0,
                                    ) );
                                if ( ! empty( $pages ) ) {
                                    foreach ( $pages as $p ) {
                                        ?>
                                        <li><a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><?php echo esc_html( get_the_title( $p->ID ) ); ?></a></li>
                                        <?php
                                    }
                                } else {
                                    $privacy_url = get_privacy_policy_url();
                                    if ( ! empty( $privacy_url ) ) {
                                        ?>
                                        <li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'newsum' ); ?></a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        <?php endif; ?>

                        <div class="footer-social-deck">
                            <?php newsum_render_social_icons( 'footer' ); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ( $show_col4 ) : ?>
                    <!-- 4. TERMINAL DISPATCH & TECH GALLERY -->
                    <div class="footer-col footer-col-dispatch">
                        <h4><?php echo esc_html( $col4_title ); ?></h4>
                        <p class="footer-newsletter-desc"><?php echo esc_html( $footer_newsletter_desc ); ?></p>
                        
                        <form class="footer-newsletter-form" method="post" action="<?php echo esc_url( $footer_newsletter_action ); ?>" target="_blank">
                            <div class="footer-input-wrap">
                                <label for="footerNewsletterEmail" class="screen-reader-text"><?php esc_html_e( 'Email address', 'newsum' ); ?></label>
                                <input type="email" id="footerNewsletterEmail" placeholder="<?php esc_attr_e( 'user@example.com', 'newsum' ); ?>" required>
                                <button type="submit" aria-label="<?php echo esc_attr( sprintf( __( 'Subscribe to %s', 'newsum' ), get_bloginfo( 'name' ) ) ); ?>">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </button>
                            </div>
                        </form>
                        
                        <h5 class="footer-gallery-title"><?php echo esc_html( $gallery_title ); ?></h5>
                        <div class="footer-gallery-grid">
                            <?php foreach ( $footer_gallery_images as $g_url ) : ?>
                                <div class="footer-gallery-thumb">
                                    <img src="<?php echo esc_url( $g_url ); ?>" alt="<?php esc_attr_e( 'Technology Photo', 'newsum' ); ?>" loading="lazy">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <!-- FOOTER BOTTOM BAR -->
            <div class="footer-bottom-bar">
                <div class="footer-bottom-left">
                    <p class="footer-copyright"><?php echo wp_kses_post( $copyright ); ?></p>
                </div>
                <div class="footer-bottom-center">
                    <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-menu',
                            'menu_class'     => 'footer-legal-links',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    <?php else : ?>
                        <ul class="footer-legal-links">
                            <?php
                            $privacy_url = get_privacy_policy_url();
                            if ( ! empty( $privacy_url ) ) :
                                ?>
                                <li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'newsum' ); ?></a></li>
                                <?php
                            endif;
                            ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <?php if ( $show_back_to_top ) : ?>
                <div class="footer-bottom-right">
                    <button type="button" class="footer-back-to-top" id="footerBackToTop" aria-label="<?php esc_attr_e( 'Back to top of page', 'newsum' ); ?>">
                        <span><?php esc_html_e( 'Top', 'newsum' ); ?></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    </button>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
