<?php
/**
 * Homepage Dynamic Sections & Selective Refresh Renderers for NewSum Theme.
 *
 * @package NewSum
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Visual Story Highlights Reel (Pro Feature)
 */
function newsum_render_stories_section() {
    if ( ! function_exists( 'newsum_is_pro_active' ) || ! newsum_is_pro_active() ) {
        return;
    }

    if ( ! get_theme_mod( 'newsum_stories_show', true ) ) {
        return;
    }

    $stories_title  = get_theme_mod( 'newsum_stories_title', __( 'DAILY BRIEFINGS', 'newsum' ) );
    $stories_source = get_theme_mod( 'newsum_stories_source', 'latest' );
    $stories_cat    = get_theme_mod( 'newsum_stories_category', 0 );
    $stories_count  = get_theme_mod( 'newsum_stories_count', 8 );

    $story_args = array(
        'posts_per_page'      => absint( $stories_count ),
        'post_status'         => 'publish',
        'ignore_sticky_posts' => ( 'sticky' === $stories_source ) ? 0 : 1,
    );

    if ( 'sticky' === $stories_source ) {
        $sticky = get_option( 'sticky_posts' );
        if ( ! empty( $sticky ) ) {
            $story_args['post__in'] = $sticky;
        }
    } elseif ( 'category' === $stories_source && ! empty( $stories_cat ) ) {
        $story_args['cat'] = absint( $stories_cat );
    }

    $stories_query = new WP_Query( $story_args );
    if ( $stories_query->have_posts() ) :
    ?>
    <section class="stories-highlights-section" aria-label="<?php esc_attr_e( 'Story Highlights', 'newsum' ); ?>">
        <div class="stories-header">
            <div class="stories-title-wrap" style="color: #ffffff !important;">
                <span class="stories-live-indicator"><span class="pulse-dot" style="background: #ffffff !important; box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7) !important;"></span></span>
                <h2 class="stories-title" style="color: #ffffff !important;"><?php echo esc_html( $stories_title ); ?></h2>
            </div>
            <?php if ( get_theme_mod( 'newsum_stories_show_nav', true ) ) : ?>
            <div class="stories-nav-arrows">
                <button type="button" class="stories-arrow prev" id="storiesPrevBtn" aria-label="<?php esc_attr_e( 'Previous stories', 'newsum' ); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" class="stories-arrow next" id="storiesNextBtn" aria-label="<?php esc_attr_e( 'Next stories', 'newsum' ); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="stories-track-wrapper" id="storiesTrackWrapper">
            <div class="stories-track" id="storiesTrack">
                <?php
                $story_index = 0;
                while ( $stories_query->have_posts() ) : $stories_query->the_post();
                    $s_id = get_the_ID();
                    $s_cats = get_the_category( $s_id );
                    $s_cat_name = ! empty( $s_cats ) ? $s_cats[0]->name : '';
                    $s_thumb = has_post_thumbnail( $s_id ) ? get_the_post_thumbnail_url( $s_id, 'medium' ) : newsum_get_default_fallback_image();
                    $s_large_img = has_post_thumbnail( $s_id ) ? get_the_post_thumbnail_url( $s_id, 'large' ) : $s_thumb;
                    $s_time = newsum_get_reading_time( $s_id );
                    $is_live = ( $story_index === 0 );
                ?>
                <article class="story-item<?php echo $is_live ? ' is-live' : ''; ?>" 
                         data-story-index="<?php echo $story_index; ?>"
                         data-story-id="<?php echo $s_id; ?>"
                         data-story-title="<?php the_title_attribute(); ?>"
                         data-story-excerpt="<?php echo esc_attr( wp_trim_words( get_the_excerpt(), 25 ) ); ?>"
                         data-story-img="<?php echo esc_url( $s_large_img ); ?>"
                         data-story-link="<?php the_permalink(); ?>"
                         data-story-cat="<?php echo esc_attr( $s_cat_name ); ?>"
                         data-story-date="<?php echo esc_attr( get_the_date( 'M j, Y' ) ); ?>"
                         data-story-readtime="<?php echo esc_attr( $s_time ); ?> min">
                    <button type="button" class="story-card-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Open story: %s', 'newsum' ), get_the_title() ) ); ?>">
                        <div class="story-avatar-wrap">
                            <div class="story-ring"></div>
                            <div class="story-avatar">
                                <img src="<?php echo esc_url( $s_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                            </div>
                            <?php if ( $is_live ) : ?>
                            <span class="story-badge-live">LIVE</span>
                            <?php else : ?>
                            <span class="story-badge-time"><?php echo $s_time; ?>m</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="story-card-title"><?php the_title(); ?></h3>
                    </button>
                </article>
                <?php
                    $story_index++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
    <?php
    endif;
}

/**
 * 2. Hero Section Switcher (Bento / Hover-Reveal / Live Wall)
 */
function newsum_render_hero_section() {
    if ( ! get_theme_mod( 'newsum_hero_show', true ) ) {
        return;
    }

    $hero_layout        = get_theme_mod( 'newsum_hero_layout', 'bento' );
    $hero_source        = get_theme_mod( 'newsum_hero_source', 'latest' );
    $hero_cat           = get_theme_mod( 'newsum_hero_category', 0 );
    $hero_tag           = get_theme_mod( 'newsum_hero_tag', '' );
    $side_cards_rotate  = (bool) get_theme_mod( 'newsum_hero_side_cards_rotate', false );
    $hero_animation     = get_theme_mod( 'newsum_hero_animation', 'update-fade' );
    if ( ! $side_cards_rotate ) {
        $hero_animation = 'none';
    } elseif ( ! function_exists( 'newsum_is_pro_active' ) || ! newsum_is_pro_active() || empty( $hero_animation ) ) {
        $hero_animation = 'update-fade';
    }

    $args = array(
        'posts_per_page'      => 15,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
    );

    if ( 'category' === $hero_source && ! empty( $hero_cat ) ) {
        $args['cat'] = absint( $hero_cat );
    } elseif ( 'tag' === $hero_source && ! empty( $hero_tag ) ) {
        $args['tag'] = sanitize_text_field( $hero_tag );
    }

    $hero_query = new WP_Query( $args );
    $hero_posts = $hero_query->posts;
    $default_img = newsum_get_default_fallback_image();

    if ( empty( $hero_posts ) ) {
        return;
    }

    $hide_mobile   = get_theme_mod( 'newsum_hero_hide_mobile', false );
    $wrapper_class = 'hero-section-wrapper' . ( $hide_mobile ? ' hide-on-mobile' : '' );
    echo '<div id="hero-section-wrapper" class="' . esc_attr( $wrapper_class ) . '">';

    if ( 'hover_reveal' === $hero_layout && ! empty( $hero_posts ) ) :
    ?>
    <section class="hover-reveal-section hero-mode-reveal" id="heroHoverReveal">
        <?php
        for ( $i = 0; $i < min( 5, count( $hero_posts ) ); $i++ ) {
            $p = $hero_posts[ $i ];
            $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'full' ) : $default_img;
            $active_class = ( $i === 0 ) ? ' active' : '';
            echo '<div class="hero-bg-layer' . $active_class . '" id="hero-reveal-bg-' . $i . '" style="background-image: url(\'' . esc_url( $img ) . '\');"></div>';
        }
        ?>
        <div class="hero-overlay"></div>
        <div class="hover-index-content">
            <span class="hover-matrix-tag"><?php esc_html_e( 'FEATURED DOSSIER • HOVER TO EXPLORE', 'newsum' ); ?></span>
            <ol class="hover-index-list">
                <?php
                for ( $i = 0; $i < min( 5, count( $hero_posts ) ); $i++ ) {
                    $p = $hero_posts[ $i ];
                    $cats = get_the_category( $p->ID );
                    $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                    $active_class = ( $i === 0 ) ? ' class="active"' : '';
                    $num = sprintf( '%02d', $i + 1 );
                    $r_time = newsum_get_reading_time( $p->ID );
                    ?>
                    <li data-bg="hero-reveal-bg-<?php echo $i; ?>"<?php echo $active_class; ?>>
                        <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
                            <span class="index-num"><?php echo esc_html( $num ); ?></span>
                            <div class="index-meta-title">
                                <div class="index-meta">
                                    <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                                    <span class="time"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m read</span>
                                </div>
                                <span class="index-title"><?php echo esc_html( get_the_title( $p->ID ) ); ?></span>
                            </div>
                            <svg class="index-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ol>
        </div>
    </section>

    <?php elseif ( 'newspaper' === $hero_layout && ! empty( $hero_posts ) ) : 
        $newspaper_left_kicker      = get_theme_mod( 'newsum_newspaper_left_kicker', __( 'THE DISPATCH', 'newsum' ) );
        $newspaper_left_show_date   = get_theme_mod( 'newsum_newspaper_left_show_date', true );
        $newspaper_right_kicker     = get_theme_mod( 'newsum_newspaper_right_kicker', __( 'PERSPECTIVES', 'newsum' ) );
        $newspaper_right_badge      = get_theme_mod( 'newsum_newspaper_right_badge', __( 'Top Stories', 'newsum' ) );
        $newspaper_right_show_badge = get_theme_mod( 'newsum_newspaper_right_show_badge', true );

        $has_left_header  = ( ! empty( trim( $newspaper_left_kicker ) ) || $newspaper_left_show_date );
        $has_right_header = ( ! empty( trim( $newspaper_right_kicker ) ) || ( $newspaper_right_show_badge && ! empty( trim( $newspaper_right_badge ) ) ) );
    ?>
    <!-- HERO LAYOUT: CLASSIC NEWSPAPER BROADSHEET (TRIBUNE) -->
    <section class="hero-newspaper-broadsheet" id="heroNewspaper">
        <div class="newspaper-broadsheet-container">
            <div class="newspaper-grid">
                
                <!-- Col 1: The Daily Dispatch (3 Stories) -->
                <div class="newspaper-col newspaper-col-left">
                    <?php if ( $has_left_header ) : ?>
                        <div class="newspaper-col-header">
                            <?php if ( ! empty( trim( $newspaper_left_kicker ) ) ) : ?>
                                <span class="newspaper-col-kicker"><?php echo esc_html( $newspaper_left_kicker ); ?></span>
                            <?php endif; ?>
                            <?php if ( $newspaper_left_show_date ) : ?>
                                <span class="newspaper-col-badge"><?php echo esc_html( date_i18n( 'M j, Y' ) ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="newspaper-stories-stack">
                        <?php
                        for ( $i = 1; $i < min( 4, count( $hero_posts ) ); $i++ ) {
                            $p = $hero_posts[ $i ];
                            $cats = get_the_category( $p->ID );
                            $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                            $r_time = newsum_get_reading_time( $p->ID );
                            ?>
                            <article class="newspaper-brief-item">
                                <?php if ( $cat_name ) : ?>
                                    <span class="newspaper-kicker"><?php echo esc_html( $cat_name ); ?></span>
                                <?php endif; ?>
                                <h3 class="newspaper-brief-title">
                                    <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><?php echo esc_html( get_the_title( $p->ID ) ); ?></a>
                                </h3>
                                <p class="newspaper-brief-excerpt"><?php echo wp_trim_words( get_the_excerpt( $p->ID ), 14 ); ?></p>
                                <div class="newspaper-item-meta">
                                    <span class="newspaper-meta-time"><?php echo esc_html( human_time_diff( get_the_time( 'U', $p->ID ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'newsum' ) ); ?></span>
                                    <span class="newspaper-meta-read"><?php echo $r_time; ?>m read</span>
                                </div>
                            </article>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Col 2: The Lead Feature Story -->
                <div class="newspaper-col newspaper-col-center">
                    <?php
                    $lead_p = $hero_posts[0];
                    $lead_cats = get_the_category( $lead_p->ID );
                    $lead_cat_name = ! empty( $lead_cats ) ? $lead_cats[0]->name : '';
                    $lead_img = has_post_thumbnail( $lead_p->ID ) ? get_the_post_thumbnail_url( $lead_p->ID, 'large' ) : $default_img;
                    $lead_r_time = newsum_get_reading_time( $lead_p->ID );
                    ?>
                    <article class="newspaper-lead-article">
                        <div class="newspaper-lead-media">
                            <a href="<?php echo esc_url( get_permalink( $lead_p->ID ) ); ?>" class="newspaper-lead-thumb-link">
                                <img src="<?php echo esc_url( $lead_img ); ?>" alt="<?php echo esc_attr( get_the_title( $lead_p->ID ) ); ?>" class="newspaper-lead-img">
                            </a>
                            <?php if ( $lead_cat_name ) : ?>
                                <span class="newspaper-lead-pill"><?php echo esc_html( $lead_cat_name ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="newspaper-lead-body">
                            <h1 class="newspaper-lead-title">
                                <a href="<?php echo esc_url( get_permalink( $lead_p->ID ) ); ?>"><?php echo esc_html( get_the_title( $lead_p->ID ) ); ?></a>
                            </h1>
                            <p class="newspaper-lead-excerpt"><?php echo wp_trim_words( get_the_excerpt( $lead_p->ID ), 28 ); ?></p>
                            <div class="newspaper-lead-footer">
                                <div class="newspaper-author-box">
                                    <span class="author-byline"><?php esc_html_e( 'By', 'newsum' ); ?> <strong><?php echo esc_html( get_the_author_meta( 'display_name', $lead_p->post_author ) ); ?></strong></span>
                                    <span class="dot-sep">•</span>
                                    <time datetime="<?php echo esc_attr( get_the_date( 'c', $lead_p->ID ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y', $lead_p->ID ) ); ?></time>
                                    <span class="dot-sep">•</span>
                                    <span class="reading-time-stat"><?php echo $lead_r_time; ?>m read</span>
                                </div>
                                <div class="card-action-deck">
                                    <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo $lead_p->ID; ?>" data-post-title="<?php echo esc_attr( get_the_title( $lead_p->ID ) ); ?>" data-post-excerpt="<?php echo esc_attr( wp_trim_words( get_the_excerpt( $lead_p->ID ), 30 ) ); ?>" data-post-link="<?php echo esc_url( get_permalink( $lead_p->ID ) ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                        <span><?php esc_html_e( '30s Summary', 'newsum' ); ?></span>
                                    </button>
                                    <?php newsum_render_bookmark_button( $lead_p->ID, get_the_title( $lead_p->ID ), get_permalink( $lead_p->ID ) ); ?>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Col 3: Visual Perspectives & Wire Stack (2 Cards) -->
                <div class="newspaper-col newspaper-col-right">
                    <?php if ( $has_right_header ) : ?>
                        <div class="newspaper-col-header">
                            <?php if ( ! empty( trim( $newspaper_right_kicker ) ) ) : ?>
                                <span class="newspaper-col-kicker"><?php echo esc_html( $newspaper_right_kicker ); ?></span>
                            <?php endif; ?>
                            <?php if ( $newspaper_right_show_badge && ! empty( trim( $newspaper_right_badge ) ) ) : ?>
                                <span class="newspaper-col-badge"><?php echo esc_html( $newspaper_right_badge ); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="newspaper-featured-stack">
                        <?php
                        for ( $i = 4; $i < min( 6, count( $hero_posts ) ); $i++ ) {
                            $p = $hero_posts[ $i ];
                            $cats = get_the_category( $p->ID );
                            $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                            $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'medium_large' ) : $default_img;
                            $r_time = newsum_get_reading_time( $p->ID );
                            ?>
                            <article class="newspaper-featured-card">
                                <div class="newspaper-card-thumb">
                                    <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
                                        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $p->ID ) ); ?>" class="card-thumb-img">
                                    </a>
                                    <?php if ( $cat_name ) : ?>
                                        <span class="newspaper-card-tag"><?php echo esc_html( $cat_name ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="newspaper-card-content">
                                    <h3 class="newspaper-card-title">
                                        <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><?php echo esc_html( get_the_title( $p->ID ) ); ?></a>
                                    </h3>
                                    <p class="newspaper-card-excerpt"><?php echo wp_trim_words( get_the_excerpt( $p->ID ), 14 ); ?></p>
                                    <div class="newspaper-item-meta">
                                        <span class="newspaper-meta-time"><?php echo esc_html( get_the_date( 'M j', $p->ID ) ); ?></span>
                                        <span class="newspaper-meta-read"><?php echo $r_time; ?>m read</span>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php elseif ( 'live_wall' === $hero_layout && ! empty( $hero_posts ) ) : ?>
    <?php
        $update_pool = array();
        for ( $i = 6; $i < count( $hero_posts ); $i++ ) {
            $p = $hero_posts[ $i ];
            $cats = get_the_category( $p->ID );
            $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
            $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'large' ) : $default_img;
            $update_pool[] = array(
                'cat'     => $cat_name,
                'title'   => get_the_title( $p->ID ),
                'excerpt' => wp_trim_words( get_the_excerpt( $p->ID ), 12 ),
                'img'     => $img,
                'link'    => get_permalink( $p->ID )
            );
        }
        $pool_json = json_encode( $update_pool );
    ?>
    <section class="hero-live-wall matrix-wall-dense" data-update-pool="<?php echo esc_attr( $pool_json ); ?>" data-animation="<?php echo esc_attr( $hero_animation ); ?>">
        <div class="dense-wall-grid">
            <?php
            for ( $i = 0; $i < min( 6, count( $hero_posts ) ); $i++ ) {
                $p = $hero_posts[ $i ];
                $cats = get_the_category( $p->ID );
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'large' ) : $default_img;
                $r_time = newsum_get_reading_time( $p->ID );
                ?>
                <article class="bento-tile live-tile" id="tile-<?php echo $i+1; ?>">
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $p->ID ) ); ?>" class="tile-bg">
                    <div class="tile-overlay"></div>
                    <div class="card-action-deck">
                        <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m</span>
                        <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo $p->ID; ?>" data-post-title="<?php echo esc_attr( get_the_title( $p->ID ) ); ?>" data-post-excerpt="<?php echo esc_attr( wp_trim_words( get_the_excerpt( $p->ID ), 25 ) ); ?>" data-post-link="<?php echo esc_url( get_permalink( $p->ID ) ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s</span></button>
                        <?php newsum_render_bookmark_button( $p->ID, get_the_title( $p->ID ), get_permalink( $p->ID ) ); ?>
                    </div>
                    <div class="tile-content">
                        <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                        <h3 class="tile-title"><a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>"><?php echo esc_html( get_the_title( $p->ID ) ); ?></a></h3>
                        <p class="tile-excerpt"><?php echo wp_trim_words( get_the_excerpt( $p->ID ), 12 ); ?></p>
                    </div>
                </article>
                <?php
            }
            ?>
        </div>
    </section>

    <?php else : ?>
    <!-- HERO LAYOUT 1: BENTO SLIDER (DEFAULT) -->
    <?php
        $update_pool = array();
        for ( $i = 6; $i < count( $hero_posts ); $i++ ) {
            if ( isset( $hero_posts[ $i ] ) ) {
                $p = $hero_posts[ $i ];
                $cats = get_the_category( $p->ID );
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'large' ) : $default_img;
                if ( empty( $img ) ) $img = $default_img;
                $update_pool[] = array(
                    'cat'     => $cat_name,
                    'title'   => get_the_title( $p->ID ),
                    'excerpt' => wp_trim_words( get_the_excerpt( $p->ID ), 12 ),
                    'img'     => $img,
                    'link'    => get_permalink( $p->ID )
                );
            }
        }

        if ( ! empty( $update_pool ) && count( $update_pool ) < 4 ) {
            $pool_copy = $update_pool;
            while ( count( $update_pool ) < 4 ) {
                $update_pool = array_merge( $update_pool, $pool_copy );
            }
        }
        $pool_json = json_encode( $update_pool );
    ?>
    <section class="hero-live-wall" data-update-pool="<?php echo esc_attr( $pool_json ); ?>" data-animation="<?php echo esc_attr( $hero_animation ); ?>" data-rotate="<?php echo $side_cards_rotate ? 'true' : 'false'; ?>">
        <div class="hero-col left-col">
            <?php
            for ( $i = 0; $i < 2; $i++ ) {
                $p = $hero_posts[ $i % count( $hero_posts ) ];
                $cats = get_the_category( $p->ID );
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'large' ) : $default_img;
                $title = get_the_title( $p->ID );
                $excerpt = wp_trim_words( get_the_excerpt( $p->ID ), 12 );
                $link = get_permalink( $p->ID );
                $post_id = $p->ID;
                $r_time = newsum_get_reading_time( $p->ID );
                ?>
                <article class="bento-tile live-tile" id="tile-<?php echo $i+1; ?>">
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="tile-bg">
                    <div class="tile-overlay"></div>
                    <div class="card-action-deck">
                        <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m</span>
                        <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( $title ); ?>" data-post-excerpt="<?php echo esc_attr( $excerpt ); ?>" data-post-link="<?php echo esc_url( $link ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s</span></button>
                        <?php newsum_render_bookmark_button( $post_id, $title, $link ); ?>
                    </div>
                    <div class="tile-content">
                        <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                        <h3 class="tile-title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a></h3>
                        <p class="tile-excerpt"><?php echo esc_html( $excerpt ); ?></p>
                    </div>
                </article>
                <?php
            }
            ?>
        </div>

        <div class="hero-col middle-col">
            <div class="hero-slider" id="heroSlider" data-autoplay="<?php echo esc_attr( get_theme_mod( 'newsum_hero_slider_autoplay', true ) ? 'true' : 'false' ); ?>">
                <div class="slides">
                    <?php
                    $slide_max = min( 3, count( $hero_posts ) );
                    for ( $s = 0; $s < $slide_max; $s++ ) {
                        $p_index = ( $s + 2 ) % count( $hero_posts );
                        $p = $hero_posts[ $p_index ];
                        $cats = get_the_category( $p->ID );
                        $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                        $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'full' ) : $default_img;
                        $title = get_the_title( $p->ID );
                        $excerpt = wp_trim_words( get_the_excerpt( $p->ID ), 20 );
                        $link = get_permalink( $p->ID );
                        $date_str = get_the_date( '', $p->ID );
                        $post_id = $p->ID;
                        $r_time = newsum_get_reading_time( $p->ID );

                        $is_active = ( $s === 0 );
                        $active_class = $is_active ? ' active' : '';
                        ?>
                        <article class="slide<?php echo $active_class; ?>">
                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="slide-bg">
                            <div class="slide-overlay"></div>
                            <div class="card-action-deck slider-deck">
                                <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m read</span>
                                <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( $title ); ?>" data-post-excerpt="<?php echo esc_attr( $excerpt ); ?>" data-post-link="<?php echo esc_url( $link ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s Takeaway</span></button>
                                <?php newsum_render_bookmark_button( $post_id, $title, $link ); ?>
                            </div>
                            <div class="slide-content">
                                <span class="badge"><?php esc_html_e( 'Featured', 'newsum' ); ?></span>
                                <div class="article-meta">
                                    <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                                    <span class="date"><?php echo esc_html( $date_str ); ?></span>
                                </div>
                                <h1 class="hero-headline"><a href="<?php echo esc_url( $link ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( $title ); ?></a></h1>
                                <p class="hero-excerpt"><?php echo esc_html( $excerpt ); ?></p>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
                </div>
                <div class="slider-controls">
                    <?php if ( get_theme_mod( 'newsum_hero_slider_autoplay', true ) ) : ?>
                    <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
                    <?php endif; ?>
                    <div class="slider-nav">
                        <button class="nav-btn prev" id="sliderPrev">&larr;</button>
                        <span class="slide-counter"><span id="currentSlide">1</span> / 3</span>
                        <button class="nav-btn next" id="sliderNext">&rarr;</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-col right-col">
            <?php
            for ( $i = 0; $i < 2; $i++ ) {
                $p_index = ( $i + 5 ) % count( $hero_posts );
                $p = $hero_posts[ $p_index ];
                $cats = get_the_category( $p->ID );
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                $img = has_post_thumbnail( $p->ID ) ? get_the_post_thumbnail_url( $p->ID, 'large' ) : $default_img;
                $title = get_the_title( $p->ID );
                $excerpt = wp_trim_words( get_the_excerpt( $p->ID ), 12 );
                $link = get_permalink( $p->ID );
                $post_id = $p->ID;
                $r_time = newsum_get_reading_time( $p->ID );
                ?>
                <article class="bento-tile live-tile" id="tile-<?php echo $i+6; ?>">
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="tile-bg">
                    <div class="tile-overlay"></div>
                    <div class="card-action-deck">
                        <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m</span>
                        <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( $title ); ?>" data-post-excerpt="<?php echo esc_attr( $excerpt ); ?>" data-post-link="<?php echo esc_url( $link ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s</span></button>
                        <?php newsum_render_bookmark_button( $post_id, $title, $link ); ?>
                    </div>
                    <div class="tile-content">
                        <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                        <h3 class="tile-title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a></h3>
                        <p class="tile-excerpt"><?php echo esc_html( $excerpt ); ?></p>
                    </div>
                </article>
                <?php
            }
            ?>
        </div>
    </section>
    <?php endif; ?>
    </div><!-- /#hero-section-wrapper -->
    <?php
}

/**
 * 3. Marquee Strip Section
 */
function newsum_render_marquee_section() {
    if ( ! get_theme_mod( 'newsum_marquee_show', true ) ) {
        return;
    }

    $source = get_theme_mod( 'newsum_marquee_source', 'categories' );
    $custom_text = get_theme_mod( 'newsum_marquee_text', __( 'LATEST STORIES • MARKET INTELLIGENCE • IN-DEPTH ANALYSIS • EDITORIAL DISPATCHES', 'newsum' ) );
    $count = get_theme_mod( 'newsum_marquee_count', 10 );

    $items = array();
    if ( 'categories' === $source ) {
        $cats = get_categories( array(
            'number'     => absint( $count ),
            'orderby'    => 'count',
            'order'      => 'DESC',
            'hide_empty' => true,
        ) );
        if ( ! empty( $cats ) ) {
            foreach ( $cats as $cat ) {
                $clean_name = html_entity_decode( $cat->name, ENT_QUOTES, 'UTF-8' );
                $items[]    = mb_strtoupper( $clean_name, 'UTF-8' ) . ' (' . $cat->count . ')';
            }
        }
    }

    if ( empty( $items ) ) {
        $items = array_map( 'trim', explode( '•', $custom_text ) );
    }
    ?>
    <div class="marquee-banner" id="marquee-wrapper">
        <div class="marquee-content">
            <?php for ( $r = 0; $r < 3; $r++ ) : ?>
                <?php foreach ( $items as $idx => $item ) : ?>
                    <span<?php echo ( $idx % 2 === 1 ) ? ' class="highlight"' : ''; ?>><?php echo esc_html( $item ); ?></span>
                    <span class="marquee-dot">•</span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </div>
    <?php
}

/**
 * 4. Home Categories Accordion Section
 */
function newsum_render_home_categories_section() {
    if ( ! get_theme_mod( 'newsum_home_categories_show', true ) ) {
        return;
    }

    $title = get_theme_mod( 'newsum_home_categories_title', __( 'TRENDING TOPICS', 'newsum' ) );
    $count = get_theme_mod( 'newsum_home_categories_count', 4 );

    $cats = get_categories( array(
        'number'     => absint( $count ),
        'orderby'    => 'count',
        'order'      => 'DESC',
        'hide_empty' => true,
    ) );
    ?>
    <section class="category-accordion-wrapper" id="homeCategoriesWrapper">
        <?php if ( ! empty( $title ) ) : ?>
        <div class="feed-header">
            <h2><?php echo esc_html( $title ); ?></h2>
        </div>
        <?php endif; ?>
        <div class="category-accordion">
            <?php
            if ( ! empty( $cats ) ) :
                $c_idx = 0;
                foreach ( $cats as $cat ) :
                    $recent_posts = get_posts( array(
                        'cat'            => $cat->term_id,
                        'posts_per_page' => 3,
                        'post_status'    => 'publish',
                    ) );

                    $img = '';
                    if ( ! empty( $recent_posts ) ) {
                        foreach ( $recent_posts as $rp ) {
                            if ( has_post_thumbnail( $rp->ID ) ) {
                                $img = get_the_post_thumbnail_url( $rp->ID, 'large' );
                                break;
                            }
                        }
                    }
                    if ( empty( $img ) ) {
                        $img = newsum_get_default_fallback_image( $c_idx );
                    }
            ?>
                <div class="accordion-panel" style="background-image: url('<?php echo esc_url( $img ); ?>');">
                    <div class="panel-overlay"></div>
                    <div class="panel-content">
                        <h3 class="panel-title"><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( $cat->name ); ?></a></h3>
                        <div class="panel-links">
                            <?php if ( ! empty( $recent_posts ) ) : ?>
                                <?php foreach ( $recent_posts as $rp ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $rp->ID ) ); ?>"><?php echo esc_html( wp_trim_words( get_the_title( $rp->ID ), 6 ) ); ?></a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( sprintf( __( 'Explore %s Stories &rarr;', 'newsum' ), $cat->name ) ); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
                    $c_idx++;
                endforeach;
            else :
                ?>
                <p class="empty-state-notice" style="padding: 2.5rem; text-align: center; width: 100%; color: var(--text-secondary); font-family: var(--font-mono); font-size: 0.875rem;">
                    <?php esc_html_e( 'No categories found. Assign posts to categories to display them here.', 'newsum' ); ?>
                </p>
                <?php
            endif;
            ?>
        </div>
    </section>
    <?php
}

/**
 * 5. Custom CTA / Split Section
 */
function newsum_render_custom_banner_section() {
    $title     = get_theme_mod( 'newsum_custom_banner_title', __( 'The Next Frontier: Autonomous Systems & Hardware Intelligence', 'newsum' ) );
    $sec_title = get_theme_mod( 'newsum_custom_banner_section_title', __( 'FEATURED DOSSIER', 'newsum' ) );
    $layout    = get_theme_mod( 'newsum_custom_banner_layout', 'split_left' );
    $image     = get_theme_mod( 'newsum_custom_banner_image', newsum_get_default_fallback_image() );
    $text      = get_theme_mod( 'newsum_custom_banner_text', __( 'An exhaustive technical breakdown of 2nm semiconductors, optical interconnect architectures, and why edge AI hardware is rapidly overtaking monolithic cloud infrastructure.', 'newsum' ) );
    $btn_text  = get_theme_mod( 'newsum_custom_banner_btn_text', __( 'Read Intelligence Dossier', 'newsum' ) );
    $btn_url   = get_theme_mod( 'newsum_custom_banner_btn_url', '#' );
    $btn_tgt   = get_theme_mod( 'newsum_custom_banner_btn_target', '_self' );
    $bg_color  = get_theme_mod( 'newsum_custom_banner_bg_color', '' );

    if ( empty( $title ) && empty( $image ) && empty( $text ) ) {
        return;
    }

    $is_light_bg = false;
    if ( ! empty( $bg_color ) ) {
        $hex = ltrim( $bg_color, '#' );
        if ( strlen( $hex ) === 6 ) {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
            $lum = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;
            if ( $lum > 0.55 ) {
                $is_light_bg = true;
            }
        }
    }
    $card_classes = 'custom-cta-card' . ( $is_light_bg ? ' has-light-bg' : '' );
    $bg_style     = ! empty( $bg_color ) ? ' style="background-color: ' . esc_attr( $bg_color ) . ';"' : '';
    ?>
    <section class="newsum-custom-cta layout-<?php echo esc_attr( $layout ); ?>">
        <?php if ( ! empty( $sec_title ) ) : ?>
        <div class="feed-header">
            <h2><?php echo esc_html( $sec_title ); ?></h2>
        </div>
        <?php endif; ?>
        <div class="<?php echo esc_attr( $card_classes ); ?>"<?php echo $bg_style; ?>>
            <div class="cta-img-col">
                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
            </div>
            <div class="cta-content-col">
                <span class="cta-badge"><?php esc_html_e( 'SPECIAL REPORT', 'newsum' ); ?></span>
                <h3 class="cta-title"><?php echo wp_kses_post( $title ); ?></h3>
                <?php if ( ! empty( $text ) ) : ?>
                <p class="cta-desc"><?php echo wp_kses_post( $text ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $btn_text ) ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="btn-primary cta-btn" target="<?php echo esc_attr( $btn_tgt ); ?>" rel="noopener"><?php echo esc_html( $btn_text ); ?> &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * 6. Masonry / The Wire Section (Featured + 4 List Cards + 3-Col Feed Grid)
 */
function newsum_render_masonry_section() {
    if ( ! get_theme_mod( 'newsum_masonry_show', true ) ) {
        return;
    }

    $title          = get_theme_mod( 'newsum_masonry_title', __( 'The Wire', 'newsum' ) );
    $sidebar_layout = get_theme_mod( 'newsum_masonry_sidebar', 'right' );
    $has_sidebar    = ( 'none' !== $sidebar_layout );
    $list_count     = get_theme_mod( 'newsum_masonry_list_count', 6 );
    $list_count     = max( 3, min( 10, absint( $list_count ) ) );

    // Query posts for mixed block (1 big featured + N list cards)
    $mixed_query = new WP_Query( array(
        'posts_per_page'      => $list_count + 1,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
    ) );
    $mixed_posts = $mixed_query->posts;

    if ( empty( $mixed_posts ) ) {
        return;
    }

    $fallback_img = newsum_get_default_fallback_image();
    $big_post     = $mixed_posts[0];
    $big_id       = $big_post->ID;
    $big_title    = get_the_title( $big_id );
    $big_link     = get_permalink( $big_id );
    $big_excerpt  = wp_trim_words( get_the_excerpt( $big_id ), 25 );
    $big_img      = has_post_thumbnail( $big_id ) ? get_the_post_thumbnail_url( $big_id, 'large' ) : $fallback_img;
    $big_cats     = get_the_category( $big_id );
    $big_cat_name = ! empty( $big_cats ) ? $big_cats[0]->name : __( 'Featured', 'newsum' );
    $big_date     = get_the_date( 'd M Y', $big_id );
    $big_rtime    = newsum_get_reading_time( $big_id );
    ?>
    <section class="the-wire-section" id="masonrySectionWrap">
        <div class="feed-header" id="masonryFeedHeader">
            <h2 style="color: #ffffff !important;"><?php echo esc_html( $title ); ?></h2>
            <div class="feed-filters">
                <button class="active"><?php esc_html_e( 'All', 'newsum' ); ?></button>
                <button><?php esc_html_e( "Editor's Picks", 'newsum' ); ?></button>
                <button><?php esc_html_e( 'Trending', 'newsum' ); ?></button>
            </div>
        </div>

        <div class="content-sidebar-wrap sidebar-<?php echo esc_attr( $sidebar_layout ); ?>">
            <div class="main-column">
                
                <!-- 1. MIXED BLOCK -->
                <div class="mixed-block">
                    <div class="mixed-main">
                        <article class="article-card">
                            <div class="card-img-wrap">
                                <img src="<?php echo esc_url( $big_img ); ?>" alt="<?php echo esc_attr( $big_title ); ?>">
                                <div class="card-action-deck">
                                    <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $big_rtime; ?>m read</span>
                                    <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php echo esc_attr( $big_id ); ?>" data-post-title="<?php echo esc_attr( $big_title ); ?>" data-post-excerpt="<?php echo esc_attr( $big_excerpt ); ?>" data-post-link="<?php echo esc_url( $big_link ); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s</span></button>
                                    <?php newsum_render_bookmark_button( $big_id, $big_title, $big_link ); ?>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="article-meta">
                                    <span class="category"><?php echo esc_html( $big_cat_name ); ?></span>
                                    <span class="date"><?php echo esc_html( $big_date ); ?></span>
                                </div>
                                <h3><a href="<?php echo esc_url( $big_link ); ?>"><?php echo esc_html( $big_title ); ?></a></h3>
                                <p class="excerpt"><?php echo esc_html( $big_excerpt ); ?></p>
                            </div>
                        </article>
                    </div>
                    <div class="mixed-list">
                        <?php
                        $available_list_count = count( $mixed_posts ) - 1;
                        if ( $available_list_count > 0 ) {
                            for ( $idx = 1; $idx <= min( $list_count, $available_list_count ); $idx++ ) {
                                $lp      = $mixed_posts[ $idx ];
                                $l_id    = $lp->ID;
                                $l_title = get_the_title( $l_id );
                                $l_link  = get_permalink( $l_id );
                                $l_img   = has_post_thumbnail( $l_id ) ? get_the_post_thumbnail_url( $l_id, 'medium' ) : newsum_get_default_fallback_image( $idx );
                                $l_time  = sprintf( __( '%s ago', 'newsum' ), human_time_diff( get_the_time( 'U', $l_id ), current_time( 'timestamp' ) ) );
                                ?>
                                <article class="list-card">
                                    <div class="list-thumb">
                                        <img src="<?php echo esc_url( $l_img ); ?>" alt="<?php echo esc_attr( $l_title ); ?>" loading="lazy">
                                    </div>
                                    <div class="list-content">
                                        <h4><a href="<?php echo esc_url( $l_link ); ?>"><?php echo esc_html( $l_title ); ?></a></h4>
                                        <span class="date"><?php echo esc_html( $l_time ); ?></span>
                                    </div>
                                </article>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- 2. GRID BLOCK -->
                <div class="feed-grid">
                    <?php
                    $grid_query = new WP_Query( array(
                        'posts_per_page'      => 3,
                        'offset'              => $list_count + 1,
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 1,
                    ) );

                    if ( $grid_query->have_posts() ) :
                        while ( $grid_query->have_posts() ) :
                            $grid_query->the_post();
                            $g_id       = get_the_ID();
                            $g_cats     = get_the_category();
                            $g_cat_name = ! empty( $g_cats ) ? $g_cats[0]->name : __( 'Story', 'newsum' );
                            $g_img      = has_post_thumbnail() ? get_the_post_thumbnail_url( $g_id, 'medium_large' ) : newsum_get_default_fallback_image( $g_id );
                            $g_rtime    = newsum_get_reading_time( $g_id );
                            ?>
                            <article class="feed-card">
                                <div class="card-img-wrap">
                                    <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                        <img src="<?php echo esc_url( $g_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                    </a>
                                </div>
                                <div class="card-content">
                                    <span class="category"><?php echo esc_html( $g_cat_name ); ?></span>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                                    <div class="card-footer">
                                        <span class="author"><?php esc_html_e( 'By', 'newsum' ); ?> <?php the_author(); ?></span>
                                        <span class="read-time"><?php echo esc_html( $g_rtime ); ?> MIN READ</span>
                                    </div>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>

                <!-- 3. QUOTE / CALLOUT BLOCK -->
                <?php newsum_render_quote_section(); ?>

                <!-- 4. RECENT ARTICLES FEED -->
                <?php newsum_render_feed_section(); ?>

            </div>

            <?php if ( $has_sidebar ) : ?>
            <!-- SIDEBAR -->
            <aside class="sidebar-column widget-area">
                <?php
                if ( is_active_sidebar( 'sidebar-1' ) ) :
                    dynamic_sidebar( 'sidebar-1' );
                else :
                    $ec_title = get_theme_mod( 'newsum_featured_grid_title', __( "Editor's Choice", 'newsum' ) );
                    $ec_cat   = absint( get_theme_mod( 'newsum_editors_choice_cat', 0 ) );
                    $ec_count = absint( get_theme_mod( 'newsum_editors_choice_count', 3 ) );

                    $ec_args = array(
                        'posts_per_page'      => max( 1, $ec_count ),
                        'ignore_sticky_posts' => 1,
                        'post_status'         => 'publish',
                    );
                    if ( $ec_cat > 0 ) {
                        $ec_args['cat'] = $ec_cat;
                    }
                    $ec_query = new WP_Query( $ec_args );
                    ?>
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php echo esc_html( $ec_title ); ?></h3>
                        <div class="sidebar-posts">
                            <?php
                            if ( $ec_query->have_posts() ) :
                                $rank = 1;
                                while ( $ec_query->have_posts() ) :
                                    $ec_query->the_post();
                                    $rank_str = sprintf( '%02d', $rank++ );
                                    $cats = get_the_category();
                                    $cat_badge = ! empty( $cats ) ? $cats[0]->name : 'EDITORIAL';
                                    ?>
                                    <div class="sidebar-post-item">
                                        <span class="rank"><?php echo esc_html( $rank_str ); ?></span>
                                        <div>
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <span class="meta"><?php echo esc_html( newsum_get_reading_time( get_the_ID() ) ); ?> MIN READ &bull; <?php echo esc_html( strtoupper( $cat_badge ) ); ?></span>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                ?>
                                <p class="sidebar-empty-notice" style="font-family: var(--font-mono); font-size: 0.8125rem; color: var(--text-secondary); padding: 1rem 0;">
                                    <?php esc_html_e( 'No trending stories published yet.', 'newsum' ); ?>
                                </p>
                                <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <?php if ( get_theme_mod( 'newsum_show_newsletter_box', true ) ) : ?>
                    <div class="sidebar-widget newsletter-widget">
                        <h3 class="widget-title"><?php echo esc_html( get_theme_mod( 'newsum_newsletter_title', sprintf( __( '%s Dispatch', 'newsum' ), get_bloginfo( 'name' ) ) ) ); ?></h3>
                        <p><?php echo esc_html( get_theme_mod( 'newsum_newsletter_desc', __( 'Get curated deep-tech analysis delivered directly to your inbox every Thursday.', 'newsum' ) ) ); ?></p>
                        <?php
                        $newsletter_action = get_theme_mod( 'newsum_newsletter_action', '' );
                        if ( ! empty( $newsletter_action ) && 0 === strpos( $newsletter_action, '[' ) ) :
                            echo do_shortcode( $newsletter_action );
                        else :
                        ?>
                        <form class="newsletter-form" action="<?php echo esc_url( $newsletter_action ? $newsletter_action : '#' ); ?>" method="<?php echo $newsletter_action ? 'post' : 'get'; ?>" <?php if ( ! $newsletter_action ) echo 'onsubmit="event.preventDefault();"'; ?>>
                            <input type="email" placeholder="<?php echo esc_attr( get_theme_mod( 'newsum_newsletter_placeholder', 'reader@example.com' ) ); ?>" required>
                            <button type="submit"><?php echo esc_html( get_theme_mod( 'newsum_newsletter_btn_text', __( 'Subscribe', 'newsum' ) ) ); ?></button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </aside>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

/**
 * 7. Interlude Quote Section
 */
function newsum_render_quote_section() {
    if ( ! get_theme_mod( 'newsum_quote_show', true ) ) {
        return;
    }

    $quote_text   = get_theme_mod( 'newsum_quote_text', 'The question is no longer whether autonomous agents will write most code, but whether human developers will still be able to audit it.' );
    $quote_author = get_theme_mod( 'newsum_quote_author', 'Dr. Aris Thorne, MIT CSAIL Keynote 2026' );
    $bg_color     = get_theme_mod( 'newsum_quote_bg_color', '' );
    $bg_image     = get_theme_mod( 'newsum_quote_bg_image', '' );

    $style = '';
    if ( ! empty( $bg_color ) ) {
        $style .= 'background-color:' . esc_attr( $bg_color ) . ';';
    }
    if ( ! empty( $bg_image ) ) {
        $style .= 'background-image:url(' . esc_url( $bg_image ) . ');background-size:cover;background-position:center;';
    }
    $style_attr = ! empty( $style ) ? ' style="' . esc_attr( $style ) . '"' : '';
    ?>
    <div class="editorial-callout" id="interludeQuoteBlock"<?php echo $style_attr; ?>>
        <div class="quote-mark">“</div>
        <blockquote><?php echo esc_html( $quote_text ); ?></blockquote>
        <?php if ( ! empty( $quote_author ) ) : ?>
        <cite>— <?php echo esc_html( $quote_author ); ?></cite>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * 8. Latest Stories Feed Section
 */
function newsum_render_feed_section() {
    if ( ! get_theme_mod( 'newsum_feed_show', true ) ) {
        return;
    }

    $title = get_theme_mod( 'newsum_latest_stories_title', __( 'Recent Dispatches', 'newsum' ) );
    ?>
    <div class="feed-header" id="recentDispatchesHeader" style="margin-top: var(--space-xl);">
        <h2><?php echo esc_html( $title ); ?></h2>
    </div>
    <div class="main-feed-grid">
        <?php
        $feed_query = new WP_Query( array(
            'posts_per_page'      => 6,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
        ) );

        if ( $feed_query->have_posts() ) :
            while ( $feed_query->have_posts() ) : $feed_query->the_post();
                $cats = get_the_category();
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                $r_time = newsum_get_reading_time( get_the_ID() );
        ?>
        <article class="feed-card">
            <div class="card-img-wrap">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium_large' ); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url( newsum_get_default_fallback_image() ); ?>" alt="<?php the_title_attribute(); ?>">
                <?php endif; ?>
                <div class="card-action-deck">
                    <span class="card-action-pill card-read-time"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> <?php echo $r_time; ?>m</span>
                    <button type="button" class="card-action-pill card-summary-btn" data-post-id="<?php the_ID(); ?>" data-post-title="<?php the_title_attribute(); ?>" data-post-excerpt="<?php echo esc_attr( wp_trim_words( get_the_excerpt(), 25 ) ); ?>" data-post-link="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e( 'Quick 30s Summary', 'newsum' ); ?>"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> <span>30s</span></button>
                    <?php newsum_render_bookmark_button( get_the_ID(), get_the_title(), get_permalink() ); ?>
                </div>
            </div>
            <div class="card-content">
                <?php if ( $cat_name ) : ?><span class="category"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
                <div class="card-footer">
                    <span class="author"><?php esc_html_e( 'By', 'newsum' ); ?> <?php the_author(); ?></span>
                    <span class="read-time"><?php echo $r_time; ?> MIN READ</span>
                </div>
            </div>
        </article>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
    <?php
}

/**
 * 9. Multimedia Grid Section
 */
function newsum_render_media_section() {
    if ( ! get_theme_mod( 'newsum_media_show', true ) ) {
        return;
    }

    $title     = get_theme_mod( 'newsum_media_title', __( 'Multimedia & Podcasts', 'newsum' ) );
    $link_text = get_theme_mod( 'newsum_media_link_text', __( 'All Audio/Video →', 'newsum' ) );
    $link_url  = get_theme_mod( 'newsum_media_link_url', '#' );
    ?>
    <section class="media-section" id="multimediaSection">
        <div class="feed-header">
            <h2><?php echo esc_html( $title ); ?></h2>
            <?php if ( ! empty( $link_text ) ) : ?>
            <a href="<?php echo esc_url( $link_url ); ?>" class="view-all"><?php echo esc_html( $link_text ); ?></a>
            <?php endif; ?>
        </div>
        <div class="media-grid">
            <?php
            $media_query = new WP_Query( array(
                'posts_per_page'      => 4,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
            ) );

            $durations = array( '18:45', '24:10', '32:00', '14:25' );
            $m_idx = 0;

            if ( $media_query->have_posts() ) :
                while ( $media_query->have_posts() ) :
                    $media_query->the_post();
                    $m_id   = get_the_ID();
                    $m_img  = has_post_thumbnail() ? get_the_post_thumbnail_url( $m_id, 'medium_large' ) : newsum_get_default_fallback_image( $m_id );
                    $m_dur  = $durations[ $m_idx % count( $durations ) ];
                    ?>
                    <a href="<?php the_permalink(); ?>" class="media-card">
                        <div class="media-thumb">
                            <img src="<?php echo esc_url( $m_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                            <div class="play-btn"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                            <span class="duration"><?php echo esc_html( $m_dur ); ?></span>
                        </div>
                        <h4><?php the_title(); ?></h4>
                    </a>
                    <?php
                    $m_idx++;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    <?php
}

/**
 * 10. Shortcode / Ad Block Section
 */
function newsum_render_shortcode_section() {
    if ( ! get_theme_mod( 'newsum_homepage_shortcode_show', true ) ) {
        return;
    }

    $shortcode = get_theme_mod( 'newsum_homepage_shortcode', '' );
    if ( empty( $shortcode ) ) {
        return;
    }

    $fullwidth = get_theme_mod( 'newsum_homepage_shortcode_fullwidth', false );
    $wrap_cls  = $fullwidth ? 'shortcode-fullwidth' : 'shortcode-contained container';
    ?>
    <section class="newsum-shortcode-section <?php echo esc_attr( $wrap_cls ); ?>" id="homepageShortcodeSection">
        <?php echo do_shortcode( $shortcode ); ?>
    </section>
    <?php
}

/**
 * 11. Social Proof / Brand Logos Section
 */
function newsum_render_social_proof_section() {
    if ( ! get_theme_mod( 'newsum_social_proof_show', false ) ) {
        return;
    }

    $title = get_theme_mod( 'newsum_social_proof_title', 'Our Partners & Coverage' );
    $logos = array();
    for ( $i = 1; $i <= 4; $i++ ) {
        $logo_url = get_theme_mod( 'newsum_social_proof_logo_' . $i, '' );
        if ( ! empty( $logo_url ) ) {
            $logos[] = $logo_url;
        }
    }

    if ( empty( $logos ) ) {
        return;
    }
    ?>
    <section class="newsum-social-proof-bar" id="socialProofBar">
        <?php if ( ! empty( $title ) ) : ?>
        <h4 class="social-proof-title"><?php echo esc_html( $title ); ?></h4>
        <?php endif; ?>
        <div class="social-proof-logos">
            <?php foreach ( $logos as $logo ) : ?>
                <div class="proof-logo-item">
                    <img src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr_e( 'Partner Logo', 'newsum' ); ?>" loading="lazy" />
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * 12. Full-Width Newsletter CTA Section
 */
function newsum_render_newsletter_section() {
    $heading  = get_theme_mod( 'newsum_newsletter_heading', 'Stories worth<br><em>reading and sharing</em> with the world.' );
    $desc     = get_theme_mod( 'newsum_newsletter_desc', 'Weekly essays, guides, and a healthy dose of inspiration. Join 12,000+ readers.' );
    $action   = get_theme_mod( 'newsum_newsletter_action', '#' );
    $btn_text = get_theme_mod( 'newsum_newsletter_btn_text', __( 'Join The Terminal →', 'newsum' ) );

    if ( empty( $heading ) && empty( $desc ) ) {
        return;
    }
    ?>
    <section class="newsletter-cta-section" id="newsletterSection">
        <div class="newsletter-cta-inner">
            <div class="newsletter-content">
                <span class="newsletter-tag"><?php esc_html_e( 'NEWSLETTER', 'newsum' ); ?></span>
                <h2 class="newsletter-heading"><?php echo wp_kses_post( $heading ); ?></h2>
                <p class="newsletter-desc"><?php echo esc_html( $desc ); ?></p>
            </div>
            <div class="newsletter-form-wrap">
                <?php if ( strpos( $action, '[' ) !== false ) : ?>
                    <?php echo do_shortcode( $action ); ?>
                <?php else : ?>
                    <form class="newsletter-form-block" action="<?php echo esc_url( $action ); ?>" method="post" target="_blank">
                        <div class="input-group">
                            <input type="email" name="EMAIL" placeholder="<?php esc_attr_e( 'Enter your email address...', 'newsum' ); ?>" required>
                            <button type="submit" class="btn-primary"><?php echo esc_html( $btn_text ); ?></button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * 13. Home Values / Principles Grid Section
 */
function newsum_render_home_values_section() {
    $show_home = get_theme_mod( 'newsum_about_values_show_home', false );
    if ( ! $show_home && ! is_customize_preview() ) {
        return;
    }

    $values_title = get_theme_mod( 'newsum_about_values_title', __( 'What We Believe', 'newsum' ) );
    ?>
    <section class="about-values-section home-values-section" id="homeValuesSection"<?php if ( ! $show_home ) echo ' style="display:none;"'; ?>>
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
    <?php
}

/**
 * 14. Home Publication Stats Bar Section
 */
function newsum_render_home_stats_section() {
    $show_home = get_theme_mod( 'newsum_about_show_stats_home', false );
    if ( ! $show_home && ! is_customize_preview() ) {
        return;
    }

    $stat1_num   = get_theme_mod( 'newsum_about_stat1_num', '10K+' );
    $stat1_label = get_theme_mod( 'newsum_about_stat1_label', __( 'Active Readers', 'newsum' ) );
    $stat2_num   = get_theme_mod( 'newsum_about_stat2_num', '50+' );
    $stat2_label = get_theme_mod( 'newsum_about_stat2_label', __( 'Partner Brands', 'newsum' ) );
    $stat3_num   = get_theme_mod( 'newsum_about_stat3_num', '24/7' );
    $stat3_label = get_theme_mod( 'newsum_about_stat3_label', __( 'Expert Support', 'newsum' ) );
    ?>
    <section class="about-stats-bar home-stats-bar" id="homeStatsSection"<?php if ( ! $show_home ) echo ' style="display:none;"'; ?>>
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
    <?php
}


