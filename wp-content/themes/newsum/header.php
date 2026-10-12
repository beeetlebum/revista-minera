<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<!-- 
THESIS: A high-contrast editorial terminal that balances an advanced bento slider with dense masonry data, refusing standard white-box WordPress clutter.
OWN-WORLD: Deep black (#0a0a0a) background, stark white text, electric yellow accents (#eaff00). High-contrast mix of modern serif (headlines) and monospace (metadata/tech credibility).
STORY: The reader feels immersed in premium, high-urgency tech journalism with fluid, sleek animations.
FIRST VIEWPORT: A radical Typography-first Hover-Reveal Index. A massive list of 5 headlines spans the full width. Hovering over any headline instantly crossfades the background of the entire hero section to that article's featured image.
FORM: Option A (The Hover-Reveal Index).
-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'newsum' ); ?></a>
<?php if ( get_theme_mod( 'newsum_enable_preloader', false ) ) : ?>
<!-- Site Preloader -->
<div id="newsumPreloader" class="newsum-preloader" aria-hidden="true">
    <div class="preloader-inner">
        <div class="preloader-brand"><?php bloginfo( 'name' ); ?><span>.</span></div>
        <div class="preloader-bar"><div class="preloader-fill"></div></div>
        <div class="preloader-label"><?php esc_html_e( 'Loading Stories...', 'newsum' ); ?></div>
    </div>
</div>
<?php endif; ?>
    <header class="site-header">
        <?php
        // Top Bar settings
        $show_topbar       = get_theme_mod( 'newsum_topbar_show', true );
        $topbar_text       = get_theme_mod( 'newsum_topbar_text', '' );
        $topbar_twitter    = get_theme_mod( 'newsum_topbar_twitter', '#' );
        $topbar_instagram  = get_theme_mod( 'newsum_topbar_instagram', '#' );
        $topbar_facebook   = get_theme_mod( 'newsum_topbar_facebook', '#' );
        $topbar_youtube    = get_theme_mod( 'newsum_topbar_youtube', '#' );
        $show_date         = get_theme_mod( 'newsum_topbar_show_date', true );

        // Header settings
        $sticky_header     = get_theme_mod( 'newsum_sticky_header', true );
        $show_dark_mode    = get_theme_mod( 'newsum_header_show_dark_mode', true );
        $show_search       = get_theme_mod( 'newsum_header_show_search', true );
        $subscribe_text    = get_theme_mod( 'newsum_header_subscribe_text', __( 'Subscribe', 'newsum' ) );
        $subscribe_url     = get_theme_mod( 'newsum_header_subscribe_url', '#' );
        $subscribe_target  = get_theme_mod( 'newsum_header_subscribe_target', true );

        // Header Advertisement
        $show_header_ad    = (bool) get_theme_mod( 'newsum_header_ad_show', true );
        $header_ad_type    = get_theme_mod( 'newsum_header_ad_type', 'image' );
        $header_ad_image   = get_theme_mod( 'newsum_header_ad_image', '' );
        $header_ad_url     = get_theme_mod( 'newsum_header_ad_url', '#' );
        $header_ad_target  = get_theme_mod( 'newsum_header_ad_target', true );
        $header_ad_code    = get_theme_mod( 'newsum_header_ad_code', '' );

        // Tagline fallback
        if ( empty( $topbar_text ) ) {
            $topbar_text = get_bloginfo( 'description' );
        }
        ?>

        <?php newsum_render_header_topbar(); ?>
        
        <div class="header-top">
            <div class="header-inner">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="brand-logo"><?php the_custom_logo(); ?></div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo"><?php bloginfo( 'name' ); ?><span>.</span></a>
                    <?php endif; ?>
                </div>
                <?php newsum_render_header_ad(); ?>
            </div>
        </div>
        <div class="header-bottom">
            <div class="header-inner nav-row">
                <button class="hamburger-menu" id="hamburgerMenu" type="button" aria-label="<?php esc_attr_e( 'Toggle navigation', 'newsum' ); ?>" aria-expanded="false" aria-controls="offcanvasDrawer">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
                <nav class="main-nav" id="siteNavigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'newsum' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                        'fallback_cb'    => 'newsum_nav_fallback',
                        'depth'          => 3,
                    ) );
                    ?>
                </nav>
                <div class="header-actions">
                    <?php if ( $show_dark_mode ) : ?>
                    <button class="theme-toggle" id="themeToggle" aria-label="<?php esc_attr_e( 'Switch to light theme', 'newsum' ); ?>" title="<?php esc_attr_e( 'Switch to light theme', 'newsum' ); ?>" type="button">
                        <svg class="theme-icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg class="theme-icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                    <?php if ( $show_search ) : ?>
                    <button class="search-btn" id="searchBtn" type="button" aria-label="<?php esc_attr_e( 'Open Search', 'newsum' ); ?>" aria-haspopup="dialog">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span><?php esc_html_e( 'Search', 'newsum' ); ?></span>
                        <kbd>[CTRL+K]</kbd>
                    </button>
                    <?php endif; ?>
                    <?php if ( newsum_is_reading_list_enabled() ) : ?>
                    <button class="reading-list-btn" id="readingListBtn" type="button" aria-label="<?php esc_attr_e( 'View Reading List', 'newsum' ); ?>" title="<?php esc_attr_e( 'Saved Reading List', 'newsum' ); ?>">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span class="reading-list-count" id="readingListCount">0</span>
                    </button>
                    <?php endif; ?>
                    <?php if ( ! empty( $subscribe_url ) ) : ?>
                    <a href="<?php echo esc_url( $subscribe_url ); ?>" class="btn-subscribe"<?php echo $subscribe_target ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $subscribe_text ); ?></a>
                    <?php endif; ?>
                </div>

                <!-- Off-Canvas / Dropdown Navigation Drawer & Backdrop -->
                <div class="drawer-backdrop" id="drawerBackdrop" aria-hidden="true"></div>
                <aside class="offcanvas-drawer" id="offcanvasDrawer" aria-label="<?php esc_attr_e( 'Site Navigation Drawer', 'newsum' ); ?>" aria-hidden="true">
                    <div class="drawer-header">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="drawer-brand">
                            <?php bloginfo( 'name' ); ?><span>.</span>
                        </a>
                        <button class="drawer-close" id="drawerClose" aria-label="<?php esc_attr_e( 'Close navigation drawer', 'newsum' ); ?>" type="button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="drawer-content">
                        <!-- Navigation Menu Fallback inside Drawer -->
                        <div class="drawer-nav drawer-mobile-menu">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'menu-1',
                                'menu_id'        => 'drawer-primary-menu',
                                'container'      => false,
                                'fallback_cb'    => false,
                            ) );
                            ?>
                        </div>

                        <?php
                        /**
                         * Hook for Pro Drawer Sections (Live Categories, Edition Info, Pro Widgets)
                         * In Free theme, only the standard mobile nav above renders.
                         */
                        do_action( 'newsum_drawer_content' );
                        ?>
                    </div>
                    <?php if ( has_action( 'newsum_drawer_footer' ) ) : ?>
                    <div class="drawer-footer">
                        <?php do_action( 'newsum_drawer_footer' ); ?>
                    </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>

        <?php newsum_render_trending_ticker(); ?>

        <?php if ( newsum_is_reading_list_enabled() ) : ?>
        <!-- Reading List Slide-out Modal / Drawer -->
        <div class="reading-list-modal-overlay" id="readingListModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Saved Reading List', 'newsum' ); ?>">
            <div class="reading-list-backdrop" id="readingListBackdrop"></div>
            <div class="reading-list-drawer">
                <div class="reading-list-header">
                    <div class="reading-list-title-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                        <h3 class="reading-list-title"><?php esc_html_e( 'Saved Reading List', 'newsum' ); ?></h3>
                        <span class="reading-list-badge" id="readingListDrawerCount">0 articles</span>
                    </div>
                    <button class="reading-list-close" id="readingListClose" aria-label="<?php esc_attr_e( 'Close reading list', 'newsum' ); ?>" type="button">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="reading-list-body" id="readingListBody">
                    <!-- Populated dynamically via JS -->
                </div>
                <div class="reading-list-footer" id="readingListFooter">
                    <button type="button" class="reading-list-clear-btn" id="readingListClearBtn"><?php esc_html_e( 'Clear Reading List', 'newsum' ); ?></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Advanced Command Palette & Live Search Modal -->
        <div class="search-modal-overlay" id="searchModal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search Content', 'newsum' ); ?>">
            <div class="search-modal-backdrop" id="searchModalBackdrop"></div>
            <div class="search-modal-container">
                <div class="search-modal-dialog">
                    <form method="get" class="search-modal-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="search-input-wrap">
                            <svg class="search-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="search" id="searchModalInput" class="search-modal-input" placeholder="<?php esc_attr_e( 'Search articles, topics, keywords...', 'newsum' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                            <div class="search-input-actions">
                                <button type="reset" class="search-input-clear" id="searchClearBtn" aria-label="<?php esc_attr_e( 'Clear search query', 'newsum' ); ?>" style="display: none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                                <kbd class="search-modal-kbd">ESC</kbd>
                                <button type="button" class="search-modal-close" id="searchModalClose" aria-label="<?php esc_attr_e( 'Close search', 'newsum' ); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Quick Topic Pills / Categories -->
                    <?php
                    $popular_categories = get_categories( array(
                        'number'  => 6,
                        'orderby' => 'count',
                        'order'   => 'DESC',
                    ) );
                    if ( ! empty( $popular_categories ) ) :
                    ?>
                    <div class="search-quick-topics" id="searchQuickTopics">
                        <span class="search-quick-label"><?php esc_html_e( 'POPULAR TOPICS', 'newsum' ); ?></span>
                        <div class="search-topic-pills">
                            <?php foreach ( $popular_categories as $cat ) : ?>
                                <button type="button" class="search-topic-pill" data-search="<?php echo esc_attr( $cat->name ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                    <span class="pill-count"><?php echo esc_html( $cat->count ); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Live Search Results Container -->
                    <div class="search-live-results" id="searchLiveResults" style="display: none;">
                        <div class="search-results-list" id="searchResultsList"></div>
                        <a href="#" class="search-view-all-link" id="searchViewAllLink" style="display: none;">
                            <span><?php esc_html_e( 'View all search results', 'newsum' ); ?></span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>

                    <!-- Empty / Loading State -->
                    <div class="search-modal-state" id="searchModalState" style="display: none;">
                        <div class="search-state-loading" id="searchStateLoading" style="display: none;">
                            <span class="search-spinner"></span>
                            <span><?php esc_html_e( 'Searching index...', 'newsum' ); ?></span>
                        </div>
                        <div class="search-state-empty" id="searchStateEmpty" style="display: none;">
                            <span><?php esc_html_e( 'No matching articles found.', 'newsum' ); ?></span>
                        </div>
                    </div>

                    <!-- Footer Command Hint -->
                    <div class="search-modal-footer">
                        <span><kbd>↵</kbd> <?php esc_html_e( 'to select', 'newsum' ); ?></span>
                        <span><kbd>↑</kbd> <kbd>↓</kbd> <?php esc_html_e( 'to navigate', 'newsum' ); ?></span>
                        <span><kbd>ESC</kbd> <?php esc_html_e( 'to exit', 'newsum' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php do_action( 'newsum_after_header' ); ?>