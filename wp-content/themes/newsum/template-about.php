<?php
/**
 * Template Name: About Page
 * Description: Editorial About Page template with hero, stats counter, rich story content, values grid, and masthead.
 *
 * @package NewSum
 */

get_header();

// Customizer Settings
$hero_title    = get_theme_mod( 'newsum_about_hero_title', sprintf( __( 'Hi, we’re %s.', 'newsum' ), get_bloginfo( 'name' ) ) );
$hero_subtitle = get_theme_mod( 'newsum_about_hero_subtitle', __( 'We believe every reader deserves the absolute best stories.', 'newsum' ) );
$show_social   = get_theme_mod( 'newsum_about_show_social', true );
$social_tw     = get_theme_mod( 'newsum_about_social_tw', '#' );
$social_ig     = get_theme_mod( 'newsum_about_social_ig', '#' );
$social_yt     = get_theme_mod( 'newsum_about_social_yt', '#' );
$social_email  = get_theme_mod( 'newsum_about_social_email', get_option( 'admin_email' ) );

$show_stats  = get_theme_mod( 'newsum_about_show_stats', true );
$stat1_num   = get_theme_mod( 'newsum_about_stat1_num', '10K+' );
$stat1_label = get_theme_mod( 'newsum_about_stat1_label', __( 'Active Readers', 'newsum' ) );
$stat2_num   = get_theme_mod( 'newsum_about_stat2_num', '50+' );
$stat2_label = get_theme_mod( 'newsum_about_stat2_label', __( 'Partner Brands', 'newsum' ) );
$stat3_num   = get_theme_mod( 'newsum_about_stat3_num', '24/7' );
$stat3_label = get_theme_mod( 'newsum_about_stat3_label', __( 'Expert Support', 'newsum' ) );

$show_values  = get_theme_mod( 'newsum_about_values_show_about', true );
$values_title = get_theme_mod( 'newsum_about_values_title', __( 'What We Believe', 'newsum' ) );

$show_masthead  = get_theme_mod( 'newsum_about_show_masthead', true );
$masthead_title = get_theme_mod( 'newsum_about_masthead_title', __( 'Editorial Masthead', 'newsum' ) );
$masthead_desc  = get_theme_mod( 'newsum_about_masthead_desc', __( 'Independent journalists, data analysts, and investigative researchers committed to depth and accuracy.', 'newsum' ) );
?>

<main class="site-content site-main page-about-content" id="primary">

    <!-- ABOUT HERO SECTION -->
    <section class="about-hero-section">
        <div class="about-hero-inner">
            <span class="about-tagline-badge"><?php esc_html_e( 'ABOUT OUR PUBLICATION', 'newsum' ); ?></span>
            <h1 class="about-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <?php if ( ! empty( $hero_subtitle ) ) : ?>
                <p class="about-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>

            <?php if ( $show_social ) : ?>
                <div class="about-social-deck">
                    <?php if ( ! empty( $social_tw ) ) : ?>
                        <a href="<?php echo esc_url( $social_tw ); ?>" target="_blank" rel="noopener noreferrer" class="about-social-pill" aria-label="Twitter">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                            <span>Twitter / X</span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $social_ig ) ) : ?>
                        <a href="<?php echo esc_url( $social_ig ); ?>" target="_blank" rel="noopener noreferrer" class="about-social-pill" aria-label="Instagram">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span>Instagram</span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $social_yt ) ) : ?>
                        <a href="<?php echo esc_url( $social_yt ); ?>" target="_blank" rel="noopener noreferrer" class="about-social-pill" aria-label="YouTube">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span>YouTube</span>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $social_email ) ) : ?>
                        <a href="<?php echo esc_url( 'mailto:' . antispambot( str_replace( 'mailto:', '', $social_email ) ) ); ?>" class="about-social-pill" aria-label="Email">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span>Contact Desk</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- STATS BAR -->
    <?php if ( $show_stats ) : ?>
        <section class="about-stats-bar" id="aboutStatsBar">
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number"><?php echo esc_html( $stat1_num ); ?></span>
                    <span class="stat-label"><?php echo esc_html( $stat1_label ); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo esc_html( $stat2_num ); ?></span>
                    <span class="stat-label"><?php echo esc_html( $stat2_label ); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?php echo esc_html( $stat3_num ); ?></span>
                    <span class="stat-label"><?php echo esc_html( $stat3_label ); ?></span>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- MAIN PAGE CONTENT (EDITABLE VIA WP GUTENBERG) -->
    <section class="about-story-container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                if ( has_post_thumbnail() ) :
                    ?>
                    <div class="about-featured-image-wrap">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'about-featured-img' ) ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( get_the_content() ) : ?>
                    <div class="about-content-body article-body">
                        <?php the_content(); ?>
                    </div>
                <?php else : ?>
                    <div class="about-content-body article-body">
                        <p class="drop-cap"><?php echo sprintf( esc_html__( 'Founded in %1$s, %2$s was built with a single guiding mission: to bring clarity, precision, and depth back to journalism. In an age where digital feeds are dominated by sensationalism and automated noise, we believe discerning thinkers deserve uncompromising truth and thoughtful analysis.', 'newsum' ), esc_html( date( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
                        <p><?php esc_html_e( 'Our independent team of investigative journalists, technical analysts, and industry contributors work across global bureaus to dissect emerging paradigms, frontier technology, economic shifts, and policy decisions before they reach mainstream headlines.', 'newsum' ); ?></p>
                    </div>
                <?php endif; ?>
                <?php
            endwhile;
        else :
            ?>
            <div class="about-content-body article-body">
                <p class="drop-cap"><?php echo sprintf( esc_html__( 'Founded in %1$s, %2$s was built with a single guiding mission: to bring clarity, precision, and depth back to journalism. In an age where digital feeds are dominated by sensationalism and automated noise, we believe discerning thinkers deserve uncompromising truth and thoughtful analysis.', 'newsum' ), esc_html( date( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
                <p><?php esc_html_e( 'Our independent team of investigative journalists, technical analysts, and industry contributors work across global bureaus to dissect emerging paradigms, frontier technology, economic shifts, and policy decisions before they reach mainstream headlines.', 'newsum' ); ?></p>
            </div>
        <?php endif; ?>
    </section>

    <!-- VALUES / SERVICES SECTION -->
    <?php if ( $show_values ) : ?>
        <section class="about-values-section">
            <div class="values-header">
                <span class="values-tag"><?php esc_html_e( 'CORE PRINCIPLES', 'newsum' ); ?></span>
                <h2 class="values-title"><?php echo esc_html( $values_title ); ?></h2>
            </div>

            <div class="values-grid">
                <?php
                for ( $i = 1; $i <= 4; $i++ ) :
                    $v_title = get_theme_mod( 'newsum_about_val' . $i . '_title' );
                    $v_desc  = get_theme_mod( 'newsum_about_val' . $i . '_desc' );
                    if ( empty( $v_title ) ) {
                        continue;
                    }
                    ?>
                    <div class="value-card">
                        <span class="value-index"><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?></span>
                        <h3 class="value-heading"><?php echo esc_html( $v_title ); ?></h3>
                        <p class="value-desc"><?php echo esc_html( $v_desc ); ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- EDITORIAL MASTHEAD SECTION -->
    <?php if ( $show_masthead ) : ?>
        <section class="about-masthead-section">
            <div class="masthead-header">
                <span class="masthead-tag"><?php esc_html_e( 'THE NEWSROOM', 'newsum' ); ?></span>
                <h2 class="masthead-title"><?php echo esc_html( $masthead_title ); ?></h2>
                <?php if ( ! empty( $masthead_desc ) ) : ?>
                    <p class="masthead-desc"><?php echo esc_html( $masthead_desc ); ?></p>
                <?php endif; ?>
            </div>

            <div class="masthead-grid">
                <?php
                // Query site authors/editors
                $authors = get_users( array(
                    'capability' => array( 'edit_posts' ),
                    'number'     => 4,
                    'orderby'    => 'post_count',
                    'order'      => 'DESC',
                ) );

                if ( ! empty( $authors ) ) :
                    foreach ( $authors as $author ) :
                        $a_id       = $author->ID;
                        $a_name     = $author->display_name;
                        $a_bio      = get_the_author_meta( 'description', $a_id );
                        $a_url      = get_author_posts_url( $a_id );
                        $post_count = count_user_posts( $a_id, 'post', true );
                        ?>
                        <div class="masthead-member-card">
                            <div class="member-card-header">
                                <div class="member-avatar-wrap">
                                    <?php echo get_avatar( $a_id, 96, '', $a_name, array( 'class' => 'member-avatar' ) ); ?>
                                    <span class="member-status-dot" title="<?php esc_attr_e( 'Active Newsroom Editor', 'newsum' ); ?>"></span>
                                </div>
                                <div class="member-identity">
                                    <h3 class="member-name"><a href="<?php echo esc_url( $a_url ); ?>"><?php echo esc_html( $a_name ); ?></a></h3>
                                    <span class="member-role-badge"><?php esc_html_e( 'Contributing Editor', 'newsum' ); ?></span>
                                </div>
                            </div>

                            <p class="member-bio">
                                <?php
                                if ( ! empty( $a_bio ) ) {
                                    echo wp_trim_words( esc_html( $a_bio ), 22 );
                                } else {
                                    esc_html_e( 'Covering in-depth reporting, specialized intelligence, and longform perspectives across global technology bureaus.', 'newsum' );
                                }
                                ?>
                            </p>

                            <div class="member-card-footer">
                                <span class="member-stories-count">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    <?php echo sprintf( _n( '%d Story Published', '%d Stories Published', $post_count, 'newsum' ), $post_count ); ?>
                                </span>
                                <a href="<?php echo esc_url( $a_url ); ?>" class="member-link-btn">
                                    <span><?php esc_html_e( 'View Stories', 'newsum' ); ?></span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            </div>
                        </div>
                        <?php
                    endforeach;
                else :
                    // Fallback placeholder masthead cards
                    $sample_team = array(
                        array( 'name' => 'Elena Vance', 'role' => 'Editor in Chief', 'img' => newsum_get_default_fallback_image( 0 ) ),
                        array( 'name' => 'Marcus Sterling', 'role' => 'Senior Tech Editor', 'img' => newsum_get_default_fallback_image( 1 ) ),
                    );
                    foreach ( $sample_team as $mem ) :
                        ?>
                        <div class="masthead-member-card">
                            <div class="member-card-header">
                                <div class="member-avatar-wrap">
                                    <img src="<?php echo esc_url( $mem['img'] ); ?>" alt="<?php echo esc_attr( $mem['name'] ); ?>" class="member-avatar">
                                </div>
                                <div class="member-identity">
                                    <h3 class="member-name"><?php echo esc_html( $mem['name'] ); ?></h3>
                                    <span class="member-role-badge"><?php echo esc_html( $mem['role'] ); ?></span>
                                </div>
                            </div>
                            <p class="member-bio"><?php esc_html_e( 'Directing deep investigations and high-precision commentary.', 'newsum' ); ?></p>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
get_footer();
