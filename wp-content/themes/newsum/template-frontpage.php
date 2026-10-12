<?php
/**
 * Template Name: Front Page (Magazine Hub)
 *
 * A selectable custom page template that renders the dynamic NewSum Magazine
 * homepage layout (Hero Switcher, Stories Reel, Category Accordions, Data Marquee, 
 * Masonry Feed, Multimedia Grid, and Newsletter CTA).
 *
 * @package NewSum
 */

get_header();
?>

<main class="site-content site-main" id="primary">
    <?php
    // Optional Gutenberg Page Editor Content (if user added content to the static page)
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            $page_content = trim( get_the_content() );
            if ( ! empty( $page_content ) ) :
                ?>
                <section class="frontpage-custom-content">
                    <div class="content-container">
                        <?php the_content(); ?>
                    </div>
                </section>
                <?php
            endif;
        endwhile;
    endif;

    // ── 1. Visual Story Highlights Reel ─────────────────────
    newsum_render_stories_section();

    // ── 2. Hero Section Switcher (Bento / Hover-Reveal / Live Wall / Newspaper Broadsheet / Terminal) ──
    newsum_render_hero_section();

    // ── 3. Data Stream Marquee Strip ────────────────────────
    newsum_render_marquee_section();

    // ── 4. Home Categories Accordion / Cards ────────────────
    newsum_render_home_categories_section();

    // ── 5. Custom CTA / Split Screen Section ────────────────
    newsum_render_custom_banner_section();

    // ── 6. Masonry Feed / The Wire / Sidebar Column ─────────
    newsum_render_masonry_section();

    // ── 7. Multimedia & Podcasts Grid ───────────────────────
    newsum_render_media_section();

    // ── 8. Custom Shortcode / Ad Block ──────────────────────
    newsum_render_shortcode_section();

    // ── 9. Publication Stats Bar (Optional Home Display) ───
    newsum_render_home_stats_section();

    // ── 10. Core Values / Principles Grid (Optional Home Display) ──
    newsum_render_home_values_section();

    // ── 11. Full-Width Newsletter CTA ───────────────────────
    newsum_render_newsletter_section();
    ?>

    <!-- Story Quick-Read Lightbox Modal -->
    <div class="story-quick-modal" id="storyQuickModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Story Quick Preview', 'newsum' ); ?>">
        <div class="story-modal-backdrop" id="storyModalBackdrop"></div>

        <div class="story-modal-wrapper">
            <!-- Next / Previous Story Navigation Controls -->
            <?php if ( get_theme_mod( 'newsum_stories_show_modal_nav', true ) ) : ?>
            <button type="button" class="story-modal-nav story-nav-prev" id="storyModalPrev" aria-label="<?php esc_attr_e( 'Previous story', 'newsum' ); ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <?php endif; ?>

            <div class="story-modal-card">
                <div class="story-modal-progress"><div class="story-progress-bar" id="storyProgressBar"></div></div>
                <div class="story-modal-header">
                    <div class="story-header-author">
                        <span class="story-header-cat" id="storyModalCat">TECH</span>
                        <span class="story-header-date" id="storyModalDate">AUG 26</span>
                    </div>
                    <button type="button" class="story-modal-close" id="storyModalClose" aria-label="<?php esc_attr_e( 'Close story', 'newsum' ); ?>">✕</button>
                </div>
                <div class="story-modal-body">
                    <div class="story-modal-img-wrap">
                        <img src="" id="storyModalImg" alt="" />
                        <div class="story-modal-overlay"></div>
                    </div>
                    <div class="story-modal-info">
                        <span class="story-readtime-pill" id="storyModalReadtime">3 MIN READ</span>
                        <h3 class="story-modal-title" id="storyModalTitle">Story Title</h3>
                        <p class="story-modal-excerpt" id="storyModalExcerpt">Story excerpt text...</p>
                        <div class="story-modal-actions">
                            <a href="#" class="story-read-full-btn" id="storyModalFullBtn"><?php esc_html_e( 'Read Full Article', 'newsum' ); ?> →</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ( get_theme_mod( 'newsum_stories_show_modal_nav', true ) ) : ?>
            <button type="button" class="story-modal-nav story-nav-next" id="storyModalNext" aria-label="<?php esc_attr_e( 'Next story', 'newsum' ); ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- 30-Second Quick Takeaway Popover Drawer -->
    <div class="quick-summary-drawer" id="quickSummaryDrawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Executive Brief', 'newsum' ); ?>">
        <div class="summary-drawer-backdrop" id="summaryDrawerBackdrop"></div>
        <div class="summary-drawer-card">
            <div class="summary-drawer-header">
                <div class="summary-header-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    <span><?php esc_html_e( '30-SECOND EXECUTIVE BRIEF', 'newsum' ); ?></span>
                </div>
                <button type="button" class="summary-drawer-close" id="summaryDrawerClose" aria-label="<?php esc_attr_e( 'Close summary', 'newsum' ); ?>">✕</button>
            </div>
            <div class="summary-drawer-content">
                <h3 class="summary-drawer-title" id="summaryDrawerTitle">Article Title</h3>
                <div class="summary-takeaways-list" id="summaryTakeawaysList">
                    <!-- Populated dynamically via JS -->
                </div>
                <div class="summary-drawer-footer">
                    <a href="#" class="summary-full-link" id="summaryDrawerFullLink"><?php esc_html_e( 'Read Complete Story', 'newsum' ); ?> →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookmark Toast Notification -->
    <div class="bookmark-toast" id="bookmarkToast" aria-live="polite">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
        <span class="bookmark-toast-msg" id="bookmarkToastMsg"><?php esc_html_e( 'Saved to your Reading List', 'newsum' ); ?></span>
    </div>

</main>

<?php
get_footer();
