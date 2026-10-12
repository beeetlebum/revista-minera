<?php
/**
 * The template for displaying all single posts
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main" id="primary">

    <?php if ( get_theme_mod( 'newsum_show_scroll_progress', true ) ) : ?>
    <!-- READING PROGRESS BAR -->
    <div class="reading-progress-container">
        <div class="reading-progress-bar" id="readingProgressBar" style="width: 0%;"></div>
    </div>
    <?php endif; ?>

    <?php
    while ( have_posts() ) :
        the_post();

        $post_id         = get_the_ID();
        $reading_time    = newsum_get_reading_time( $post_id );
        $categories      = get_the_category( $post_id );
        $primary_cat     = ! empty( $categories ) ? $categories[0] : null;
        $author_id       = get_the_author_meta( 'ID' );
        $author_name     = get_the_author();
        $author_bio      = get_the_author_meta( 'description' );
        $author_posts_url = get_author_posts_url( $author_id );
        $author_avatar   = get_avatar( $author_id, 80 );
        $share_url       = rawurlencode( get_permalink() );
        $share_title     = rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );
        $excerpt         = get_the_excerpt();
        // Customizer display options
        $header_style        = get_theme_mod( 'newsum_single_header_style', 'standard' );
        $single_sidebar      = get_theme_mod( 'newsum_single_sidebar', 'right' );
        $show_feat_img       = get_theme_mod( 'newsum_single_show_featured_image', true );
        $show_toc            = get_theme_mod( 'newsum_single_show_toc', true );
        $show_share          = get_theme_mod( 'newsum_single_show_share', true );
        $show_author_box     = get_theme_mod( 'newsum_single_show_author_box', true );
        $show_post_nav       = get_theme_mod( 'newsum_single_show_post_nav', true );
        $show_tags           = get_theme_mod( 'newsum_single_show_tags', true );
        $show_comments       = get_theme_mod( 'newsum_single_show_comments', true );
        $show_related        = get_theme_mod( 'newsum_single_show_related_posts', true );
        $show_category       = get_theme_mod( 'newsum_single_show_category', true );
        $show_author         = get_theme_mod( 'newsum_single_show_author', true );
        $show_date           = get_theme_mod( 'newsum_single_show_date', true );
        $show_read_time      = get_theme_mod( 'newsum_single_show_reading_time', true );
        $show_comments_count = get_theme_mod( 'newsum_single_show_comments_count', true );

        $sticky_sidebar      = get_theme_mod( 'newsum_sticky_sidebar', true );

        $hero_classes = array( 'article-hero', 'hero-style-' . $header_style );
        if ( has_post_thumbnail() && $show_feat_img ) {
            $hero_classes[] = 'has-thumbnail';
        }

        $layout_classes = array( 'article-layout' );
        if ( ! $show_share ) {
            $layout_classes[] = 'has-no-share';
        }
        if ( 'none' === $single_sidebar ) {
            $layout_classes[] = 'sidebar-none';
        }
        if ( $sticky_sidebar ) {
            $layout_classes[] = 'has-sticky-sidebar';
        }
        $feat_img_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'full' ) : '';
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
            
            <!-- ARTICLE HERO -->
            <header class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>">
                <div class="hero-inner">
                    <?php if ( function_exists( 'newsum_breadcrumbs' ) ) newsum_breadcrumbs(); ?>
                    <div class="article-meta-top">
                        <?php if ( $primary_cat && $show_category ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="category-badge">
                                <?php echo esc_html( $primary_cat->name ); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ( $show_read_time ) : ?>
                        <span class="read-time">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <?php echo esc_html( $reading_time ); ?> <?php esc_html_e( 'MIN READ', 'newsum' ); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ( $show_comments_count && ( comments_open() || get_comments_number() ) ) : ?>
                            <a href="#comments" class="comments-link-pill">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                <?php echo get_comments_number(); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <h1 class="article-title"><?php the_title(); ?></h1>

                    <div class="article-meta-bottom">
                        <?php if ( $show_author || $show_date ) : ?>
                        <div class="author-info">
                            <?php if ( $show_author ) : ?>
                            <a href="<?php echo esc_url( $author_posts_url ); ?>" class="author-avatar-link">
                                <?php echo get_avatar( $author_id, 44, '', $author_name, array( 'class' => 'author-avatar' ) ); ?>
                            </a>
                            <?php endif; ?>
                            <div class="author-text">
                                <?php if ( $show_author ) : ?>
                                <span class="byline"><?php esc_html_e( 'By', 'newsum' ); ?> <a href="<?php echo esc_url( $author_posts_url ); ?>"><strong><?php echo esc_html( $author_name ); ?></strong></a></span>
                                <?php endif; ?>
                                <?php if ( $show_date ) : ?>
                                <time class="publish-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                                </time>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ( newsum_is_reading_list_enabled() ) : ?>
                        <div class="hero-actions-deck">
                            <button type="button" class="article-action-btn card-bookmark-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-title="<?php echo esc_attr( get_the_title() ); ?>" data-post-link="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Save to Reading List', 'newsum' ); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                                <span><?php esc_html_e( 'Save', 'newsum' ); ?></span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( has_post_thumbnail() && $show_feat_img ) : ?>
                    <div class="hero-image-wrap">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'hero-image' ) ); ?>
                        <?php
                        $caption = get_the_post_thumbnail_caption();
                        if ( ! empty( $caption ) ) :
                            ?>
                            <span class="image-caption"><?php echo esc_html( $caption ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- ARTICLE CONTENT LAYOUT -->
            <div class="<?php echo esc_attr( implode( ' ', $layout_classes ) ); ?>">
                
                <?php if ( $show_share ) : ?>
                <!-- STICKY SHARE SIDEBAR -->
                <aside class="sticky-share">
                    <div class="share-inner">
                        <span class="share-label"><?php esc_html_e( 'SHARE', 'newsum' ); ?></span>
                        <a href="https://x.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Share on X">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Share on Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Share on LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Share on WhatsApp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.8 14.16c-.24.68-1.2 1.26-1.68 1.32-.45.06-1.03.09-3.04-.73-2.39-.98-3.92-3.41-4.04-3.57-.12-.16-.97-1.29-.97-2.46s.61-1.74.83-1.98c.22-.24.48-.3.64-.3s.32.01.46.01c.15 0 .35-.06.55.42.2.48.68 1.66.74 1.78.06.12.1.26.02.42-.08.16-.12.26-.24.4-.12.14-.25.31-.36.42-.12.12-.25.25-.11.49.14.24.63 1.04 1.35 1.68.93.83 1.71 1.09 1.95 1.21.24.12.38.1.52-.06.14-.16.6-.7.76-.94.16-.24.32-.2.54-.12.22.08 1.41.67 1.65.79.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/></svg>
                        </a>
                        <?php if ( ! empty( $feat_img_url ) ) : ?>
                            <a href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=' . $share_url . '&media=' . rawurlencode( $feat_img_url ) . '&description=' . $share_title ); ?>" target="_blank" rel="noopener noreferrer" class="share-icon" aria-label="Share on Pinterest">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                            </a>
                        <?php endif; ?>
                        <button type="button" class="share-icon copy-link-btn" data-url="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy article link', 'newsum' ); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        </button>
                    </div>
                </aside>
                <?php endif; ?>

                <!-- MAIN ARTICLE BODY -->
                <div class="article-body-wrapper">
                    
                    <?php if ( ! empty( $excerpt ) ) : ?>
                        <!-- KEY TAKEAWAYS / EXECUTIVE SUMMARY -->
                        <div class="article-takeaways-card">
                            <div class="takeaways-header">
                                <span class="takeaways-badge">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                    <?php esc_html_e( 'Key Takeaways', 'newsum' ); ?>
                                </span>
                            </div>
                            <div class="takeaways-content">
                                <p><?php echo esc_html( $excerpt ); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="article-body" id="articleBodyContent" data-show-toc="<?php echo $show_toc ? '1' : '0'; ?>">
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

                    <?php if ( $show_share ) : ?>
                    <!-- POST END SHARE DECK -->
                    <div class="post-share-bar">
                        <span class="share-bar-title"><?php esc_html_e( 'Share this story', 'newsum' ); ?></span>
                        <div class="share-bar-icons">
                            <a href="https://x.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-x" aria-label="Share on X">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                                <span>X</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-fb" aria-label="Share on Facebook">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                                <span>Facebook</span>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-wa" aria-label="Share on WhatsApp">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.8 14.16c-.24.68-1.2 1.26-1.68 1.32-.45.06-1.03.09-3.04-.73-2.39-.98-3.92-3.41-4.04-3.57-.12-.16-.97-1.29-.97-2.46s.61-1.74.83-1.98c.22-.24.48-.3.64-.3s.32.01.46.01c.15 0 .35-.06.55.42.2.48.68 1.66.74 1.78.06.12.1.26.02.42-.08.16-.12.26-.24.4-.12.14-.25.31-.36.42-.12.12-.25.25-.11.49.14.24.63 1.04 1.35 1.68.93.83 1.71 1.09 1.95 1.21.24.12.38.1.52-.06.14-.16.6-.7.76-.94.16-.24.32-.2.54-.12.22.08 1.41.67 1.65.79.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/></svg>
                                <span>WhatsApp</span>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-in" aria-label="Share on LinkedIn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                <span>LinkedIn</span>
                            </a>
                            <?php if ( ! empty( $feat_img_url ) ) : ?>
                            <a href="<?php echo esc_url( 'https://pinterest.com/pin/create/button/?url=' . $share_url . '&media=' . rawurlencode( $feat_img_url ) . '&description=' . $share_title ); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-pin" aria-label="Share on Pinterest">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                                <span>Pinterest</span>
                            </a>
                            <?php endif; ?>
                            <button type="button" class="share-btn share-copy copy-link-btn" data-url="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy Link', 'newsum' ); ?>">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                <span><?php esc_html_e( 'Copy Link', 'newsum' ); ?></span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ARTICLE TAGS -->
                    <?php
                    $tags = get_the_tags();
                    if ( ! empty( $tags ) && $show_tags ) :
                        ?>
                        <div class="article-tags-deck">
                            <span class="tags-label"><?php esc_html_e( 'Topics:', 'newsum' ); ?></span>
                            <?php foreach ( $tags as $t ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $t->term_id ) ); ?>" class="tag-pill">#<?php echo esc_html( $t->name ); ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $show_author_box ) : ?>
                    <!-- AUTHOR BIO BOX -->
                    <div class="author-box">
                        <div class="author-box-avatar-wrap">
                            <?php echo get_avatar( $author_id, 84, '', $author_name, array( 'class' => 'author-box-avatar' ) ); ?>
                        </div>
                        <div class="author-box-content">
                            <span class="author-box-label"><?php esc_html_e( 'WRITTEN BY', 'newsum' ); ?></span>
                            <h4 class="author-box-name"><a href="<?php echo esc_url( $author_posts_url ); ?>"><?php echo esc_html( $author_name ); ?></a></h4>
                            <p class="author-box-bio">
                                <?php
                                if ( ! empty( $author_bio ) ) {
                                    echo esc_html( $author_bio );
                                } else {
                                    esc_html_e( 'Staff writer and contributing journalist covering modern trends, deep insights, and field analysis.', 'newsum' );
                                }
                                ?>
                            </p>
                            <a href="<?php echo esc_url( $author_posts_url ); ?>" class="author-box-link">
                                <?php printf( esc_html__( 'All stories by %s &rarr;', 'newsum' ), esc_html( $author_name ) ); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- PREVIOUS / NEXT POST NAVIGATION -->
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if ( ( $prev_post || $next_post ) && $show_post_nav ) :
                        ?>
                        <nav class="post-navigation-deck" aria-label="<?php esc_attr_e( 'Article navigation', 'newsum' ); ?>">
                            <?php if ( $prev_post ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="post-nav-card prev-card">
                                    <span class="nav-direction">&larr; <?php esc_html_e( 'Previous Story', 'newsum' ); ?></span>
                                    <span class="nav-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if ( $next_post ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="post-nav-card next-card">
                                    <span class="nav-direction"><?php esc_html_e( 'Next Story', 'newsum' ); ?> &rarr;</span>
                                    <span class="nav-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>

                    <!-- COMMENTS SECTION -->
                    <?php
                    if ( ( comments_open() || get_comments_number() ) && $show_comments ) :
                        comments_template();
                    endif;
                    ?>

                </div>

                <?php if ( 'none' !== $single_sidebar ) : ?>
                <!-- RIGHT SIDEBAR -->
                <aside class="sidebar-column">
                    <?php
                    if ( is_active_sidebar( 'sidebar-1' ) ) :
                        dynamic_sidebar( 'sidebar-1' );
                    else :
                        // Curated editorial sidebar widgets
                        $sidebar_query = new WP_Query( array(
                            'posts_per_page'      => 4,
                            'post_status'         => 'publish',
                            'post__not_in'        => array( $post_id ),
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

                        <div class="sidebar-widget newsletter-widget">
                            <h3 class="widget-title"><?php esc_html_e( 'Daily Dispatch', 'newsum' ); ?></h3>
                            <p><?php esc_html_e( 'Get the day’s top journalism and essential commentary delivered to your inbox.', 'newsum' ); ?></p>
                            <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed to Daily Dispatch.');">
                                <input type="email" placeholder="<?php esc_attr_e( 'user@example.com', 'newsum' ); ?>" required>
                                <button type="submit"><?php esc_html_e( 'Subscribe', 'newsum' ); ?></button>
                            </form>
                        </div>
                    <?php endif; ?>
                </aside>
                <?php endif; ?>

            </div>
        </article>

        <!-- RELATED POSTS SECTION -->
        <?php
        if ( $show_related ) :
            $related_by    = get_theme_mod( 'newsum_single_related_by', 'category' );
            $related_count = absint( get_theme_mod( 'newsum_single_related_count', 3 ) );
            if ( $related_count <= 0 ) {
                $related_count = 3;
            }

            $related_args = array(
                'posts_per_page'      => $related_count,
                'post__not_in'        => array( $post_id ),
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
            );

            if ( 'tag' === $related_by ) {
                $post_tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
                if ( ! empty( $post_tags ) ) {
                    $related_args['tag__in'] = $post_tags;
                } elseif ( $primary_cat ) {
                    $related_args['cat'] = $primary_cat->term_id;
                }
            } else {
                if ( $primary_cat ) {
                    $related_args['cat'] = $primary_cat->term_id;
                }
            }

            $related_query = new WP_Query( $related_args );

            if ( $related_query->have_posts() ) :
                ?>
                <section class="related-posts-section">
                    <div class="feed-header">
                        <h2><?php esc_html_e( 'Related Stories', 'newsum' ); ?></h2>
                    </div>
                    <div class="feed-grid">
                        <?php
                        while ( $related_query->have_posts() ) :
                            $related_query->the_post();
                            $r_cats = get_the_category();
                            $r_cat  = ! empty( $r_cats ) ? $r_cats[0]->name : __( 'Story', 'newsum' );
                            $r_img  = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : newsum_get_default_fallback_image( get_the_ID() );
                            ?>
                            <article class="feed-card">
                                <div class="card-img-wrap">
                                    <img src="<?php echo esc_url( $r_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                </div>
                                <div class="card-content">
                                    <span class="category"><?php echo esc_html( $r_cat ); ?></span>
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
            <?php
            endif;
        endif; ?>

    <?php endwhile; ?>

</main>

<?php
get_footer();
