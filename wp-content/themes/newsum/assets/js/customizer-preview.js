/**
 * Customizer Live Preview JS Handler.
 * Handles instant postMessage live updates and pencil shortcut focus.
 */
( function( $ ) {
    wp.customize.bind( 'preview-ready', function() {
        // Map data-customize-partial-id to section IDs
        var sectionMap = {
            'newsum_topbar_partial'          : 'newsum_topbar_section',
            'newsum_header_ad_partial'       : 'newsum_header_section',
            'newsum_header_partial'          : 'newsum_header_section',
            'newsum_trending_ticker_partial' : 'newsum_header_section',
            'newsum_hero_partial'            : 'newsum_hero_section',
            'newsum_stories_partial'         : 'newsum_stories_section',
            'newsum_marquee_partial'         : 'newsum_marquee_section',
            'newsum_home_categories_partial' : 'newsum_home_categories_section',
            'newsum_custom_banner_partial'   : 'newsum_custom_banner_section',
            'newsum_masonry_partial'         : 'newsum_masonry_section',
            'newsum_quote_partial'           : 'newsum_quote_section',
            'newsum_feed_partial'            : 'newsum_feed_section',
            'custom_logo'                    : 'title_tagline',
            'blogname'                       : 'title_tagline',
            'blogdescription'                : 'title_tagline',
        };

        $( document ).on( 'click', '.customize-partial-edit-shortcut-button', function( e ) {
            e.preventDefault();
            e.stopPropagation();
            var id = $( this ).closest( '.customize-partial-edit-shortcut' ).data( 'customize-partial-id' );
            var sectionId = sectionMap[ id ] || id;
            if ( sectionId ) {
                wp.customize.preview.send( 'focus-section', sectionId );
            }
        } );

        function hexToRgb(hex) {
            if (!hex) return '255, 42, 42';
            hex = hex.replace('#', '');
            if (hex.length === 3) hex = hex.split('').map(function(c) { return c + c; }).join('');
            var num = parseInt(hex, 16);
            return !isNaN(num) ? ((num >> 16) & 255) + ', ' + ((num >> 8) & 255) + ', ' + (num & 255) : '255, 42, 42';
        }

        function applyAccentColor(col) {
            if (!col) return;
            document.documentElement.style.setProperty( '--accent-color', col );
            document.documentElement.style.setProperty( '--accent-color-rgb', hexToRgb( col ) );
            document.documentElement.style.setProperty( '--accent-subtle', 'color-mix(in srgb, ' + col + ' 12%, transparent)' );
            document.documentElement.style.setProperty( '--accent-hover', 'color-mix(in srgb, ' + col + ' 7%, transparent)' );
            $( '.nav-menu>li.current-menu-item>a, .nav-menu>li.current_page_item>a, .nav-menu>li.current-menu-ancestor>a' ).css( {
                'color': col,
                'background-color': 'color-mix(in srgb, ' + col + ' 12%, transparent)'
            } );
        }

        // 1. Accent Color
        wp.customize( 'newsum_accent_color', function( value ) {
            value.bind( function( to ) {
                applyAccentColor( to );
            } );
        } );

        // 2. Palette Selection
        var paletteMap = {
            'crimson': '#ff2a2a',
            'cobalt' : '#2563eb',
            'emerald': '#059669',
            'amber'  : '#d97706',
            'violet' : '#7c3aed',
            'slate'  : '#475569'
        };
        wp.customize( 'newsum_color_palette', function( value ) {
            value.bind( function( to ) {
                if ( paletteMap[ to ] ) {
                    applyAccentColor( paletteMap[ to ] );
                } else if ( to === 'custom' ) {
                    var customColor = wp.customize( 'newsum_accent_color' ) ? wp.customize( 'newsum_accent_color' ).get() : '#ff2a2a';
                    applyAccentColor( customColor );
                }
            } );
        } );

        // 3. Default Theme Mode (Light / Dark / Auto)
        wp.customize( 'newsum_default_mode', function( value ) {
            value.bind( function( to ) {
                var isLight = ( to === 'light' );
                if ( to === 'auto' ) {
                    isLight = ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: light)' ).matches );
                }
                $( 'html, body' ).toggleClass( 'light-theme', isLight );
                try {
                    localStorage.removeItem( 'newsum_theme' );
                    localStorage.removeItem( 'theme' );
                } catch ( e ) {}
            } );
        } );

        // 4. Heading Font Family
        wp.customize( 'newsum_heading_font', function( value ) {
            value.bind( function( to ) {
                if ( to && to !== 'System / Uploaded Font' ) {
                    var systemFonts = [ 'Georgia', 'Arial', 'Helvetica', 'Times New Roman' ];
                    if ( systemFonts.indexOf( to ) === -1 ) {
                        var fontLink = document.getElementById( 'newsum-dynamic-heading-font' );
                        if ( ! fontLink ) {
                            fontLink = document.createElement( 'link' );
                            fontLink.id = 'newsum-dynamic-heading-font';
                            fontLink.rel = 'stylesheet';
                            document.head.appendChild( fontLink );
                        }
                        fontLink.href = 'https://fonts.googleapis.com/css2?family=' + encodeURIComponent( to ).replace( /%20/g, '+' ) + ':ital,wght@0,400;0,600;0,700;1,400;1,700&display=swap';
                    }
                    document.documentElement.style.setProperty( '--font-heading', '"' + to + '", serif' );
                    $( 'h1, h2, h3, h4, h5, h6, .entry-title, .single-title, .article-title, .hero-title, .bento-title, .card-title, .tile-title, .section-title, .about-hero-title, .value-heading, .brand-logo' ).css( 'font-family', '"' + to + '", serif' );
                }
            } );
        } );

        // 5. Body Font Family
        wp.customize( 'newsum_body_font', function( value ) {
            value.bind( function( to ) {
                if ( to && to !== 'System / Uploaded Font' ) {
                    var systemFonts = [ 'Georgia', 'Arial', 'Helvetica', 'Times New Roman' ];
                    if ( systemFonts.indexOf( to ) === -1 ) {
                        var fontLink = document.getElementById( 'newsum-dynamic-body-font' );
                        if ( ! fontLink ) {
                            fontLink = document.createElement( 'link' );
                            fontLink.id = 'newsum-dynamic-body-font';
                            fontLink.rel = 'stylesheet';
                            document.head.appendChild( fontLink );
                        }
                        fontLink.href = 'https://fonts.googleapis.com/css2?family=' + encodeURIComponent( to ).replace( /%20/g, '+' ) + ':wght@300;400;500;600;700&display=swap';
                    }
                    document.documentElement.style.setProperty( '--font-body', '"' + to + '", sans-serif' );
                    $( 'body, p, li, .article-body p, .single-content p, .about-content-body p, .tile-excerpt, .bento-desc' ).css( 'font-family', '"' + to + '", sans-serif' );
                }
            } );
        } );

        // 6. Body Font Size
        wp.customize( 'newsum_body_font_size', function( value ) {
            value.bind( function( to ) {
                $( 'body, p, li, .article-body p, .single-content p, .about-content-body p, .tile-excerpt, .bento-desc' ).css( 'font-size', to + 'px' );
            } );
        } );

        // 7. Heading Scale
        wp.customize( 'newsum_heading_scale', function( value ) {
            value.bind( function( to ) {
                var factor = to / 100;
                $( 'h1, .hero-title, .about-hero-title' ).css( 'font-size', ( 2.5 * factor ) + 'rem' );
                $( 'h2, .section-title, .bento-title' ).css( 'font-size', ( 1.85 * factor ) + 'rem' );
                $( 'h3, .card-title, .value-heading' ).css( 'font-size', ( 1.35 * factor ) + 'rem' );
            } );
        } );

        // 8. Container Layout Style (Contained vs Full-Width Fluid)
        wp.customize( 'newsum_container_style', function( value ) {
            value.bind( function( to ) {
                if ( to === 'fluid' ) {
                    document.documentElement.style.setProperty( '--container-max', '100%' );
                } else {
                    var width = wp.customize( 'newsum_content_width' ) ? wp.customize( 'newsum_content_width' ).get() : 1800;
                    document.documentElement.style.setProperty( '--container-max', width + 'px' );
                }
            } );
        } );

        // 9. Container Max Width
        wp.customize( 'newsum_content_width', function( value ) {
            value.bind( function( to ) {
                var style = wp.customize( 'newsum_container_style' ) ? wp.customize( 'newsum_container_style' ).get() : 'contained';
                if ( style !== 'fluid' ) {
                    document.documentElement.style.setProperty( '--container-max', to + 'px' );
                }
            } );
        } );

        // 9. Global Image Radius
        wp.customize( 'newsum_global_image_radius', function( value ) {
            value.bind( function( to ) {
                $( 'img, .tile-img, .bento-tile, .card-thumb, .media-cover-img, .about-featured-img, .story-card, .value-card, .masthead-member-card, .contact-info-card' ).css( 'border-radius', to + 'px' );
            } );
        } );

        // 10. Topbar Tagline
        wp.customize( 'newsum_topbar_text', function( value ) {
            value.bind( function( to ) {
                $( '.utility-tagline' ).text( to );
            } );
        } );

        // 11. Logo Height
        wp.customize( 'newsum_logo_height', function( value ) {
            value.bind( function( to ) {
                $( 'img.custom-logo' ).css( 'max-height', to + 'px' );
                $( '.site-header .logo' ).css( 'font-size', to + 'px' );
            } );
        } );

        // 12. Reading Progress Bar Toggle
        wp.customize( 'newsum_show_scroll_progress', function( value ) {
            value.bind( function( to ) {
                $( '.reading-progress-container' ).toggle( to );
            } );
        } );

        // 13. Header Leaderboard Advertisement / Sponsor Slot
        wp.customize( 'newsum_header_ad_show', function( value ) {
            value.bind( function( to ) {
                var $ad = $( '.newsum-header-sponsor-frame' );
                if ( to ) {
                    $ad.show().css( 'display', 'flex' );
                } else {
                    $ad.hide();
                }
            } );
        } );

        wp.customize( 'newsum_header_ad_image', function( value ) {
            value.bind( function( to ) {
                if ( to ) {
                    var $banner = $( '.newsum-sponsor-media' );
                    if ( $banner.length ) {
                        $banner.attr( 'src', to );
                    } else {
                        $( '.newsum-header-sponsor-frame' ).html( '<a href="#" class="newsum-sponsor-link"><img src="' + to + '" alt="Featured Sponsor" class="newsum-sponsor-media"></a>' );
                    }
                }
            } );
        } );

        // 13.1 Header Breaking News Ticker
        wp.customize( 'newsum_header_show_ticker', function( value ) {
            value.bind( function( to ) {
                $( '.trending-ticker' ).toggle( to );
            } );
        } );

        wp.customize( 'newsum_header_ticker_label', function( value ) {
            value.bind( function( to ) {
                $( '.trending-ticker .ticker-label' ).text( to );
            } );
        } );

        // 14. Newspaper (Tribune) Text & Header Live Preview
        function updateNewspaperLeftHeader() {
            var kicker = wp.customize( 'newsum_newspaper_left_kicker' ) ? wp.customize( 'newsum_newspaper_left_kicker' ).get() : '';
            var showDate = wp.customize( 'newsum_newspaper_left_show_date' ) ? wp.customize( 'newsum_newspaper_left_show_date' ).get() : true;
            var $col = $( '.newspaper-col-left' );
            var $header = $col.find( '.newspaper-col-header' );
            var hasKicker = kicker && kicker.trim() !== '';
            var hasContent = hasKicker || showDate;

            if ( hasContent ) {
                if ( !$header.length ) {
                    $col.prepend( '<div class="newspaper-col-header"></div>' );
                    $header = $col.find( '.newspaper-col-header' );
                }
                $header.show().css( 'display', 'flex' );
                
                var $kickerSpan = $header.find( '.newspaper-col-kicker' );
                if ( hasKicker ) {
                    if ( !$kickerSpan.length ) {
                        $header.prepend( '<span class="newspaper-col-kicker"></span>' );
                        $kickerSpan = $header.find( '.newspaper-col-kicker' );
                    }
                    $kickerSpan.text( kicker ).show();
                } else {
                    $kickerSpan.remove();
                }

                var $badgeSpan = $header.find( '.newspaper-col-badge' );
                if ( showDate ) {
                    if ( !$badgeSpan.length ) {
                        $header.append( '<span class="newspaper-col-badge"></span>' );
                        $badgeSpan = $header.find( '.newspaper-col-badge' );
                    }
                    if ( !$badgeSpan.text() ) {
                        var now = new Date();
                        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                        $badgeSpan.text( months[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear() );
                    }
                    $badgeSpan.show();
                } else {
                    $badgeSpan.remove();
                }
            } else {
                $header.remove();
            }
        }

        function updateNewspaperRightHeader() {
            var kicker = wp.customize( 'newsum_newspaper_right_kicker' ) ? wp.customize( 'newsum_newspaper_right_kicker' ).get() : '';
            var badge = wp.customize( 'newsum_newspaper_right_badge' ) ? wp.customize( 'newsum_newspaper_right_badge' ).get() : '';
            var showBadge = wp.customize( 'newsum_newspaper_right_show_badge' ) ? wp.customize( 'newsum_newspaper_right_show_badge' ).get() : true;
            var $col = $( '.newspaper-col-right' );
            var $header = $col.find( '.newspaper-col-header' );
            var hasKicker = kicker && kicker.trim() !== '';
            var hasBadge = showBadge && badge && badge.trim() !== '';
            var hasContent = hasKicker || hasBadge;

            if ( hasContent ) {
                if ( !$header.length ) {
                    $col.prepend( '<div class="newspaper-col-header"></div>' );
                    $header = $col.find( '.newspaper-col-header' );
                }
                $header.show().css( 'display', 'flex' );

                var $kickerSpan = $header.find( '.newspaper-col-kicker' );
                if ( hasKicker ) {
                    if ( !$kickerSpan.length ) {
                        $header.prepend( '<span class="newspaper-col-kicker"></span>' );
                        $kickerSpan = $header.find( '.newspaper-col-kicker' );
                    }
                    $kickerSpan.text( kicker ).show();
                } else {
                    $kickerSpan.remove();
                }

                var $badgeSpan = $header.find( '.newspaper-col-badge' );
                if ( hasBadge ) {
                    if ( !$badgeSpan.length ) {
                        $header.append( '<span class="newspaper-col-badge"></span>' );
                        $badgeSpan = $header.find( '.newspaper-col-badge' );
                    }
                    $badgeSpan.text( badge ).show();
                } else {
                    $badgeSpan.remove();
                }
            } else {
                $header.remove();
            }
        }

        wp.customize( 'newsum_newspaper_left_kicker', function( value ) {
            value.bind( updateNewspaperLeftHeader );
        } );

        wp.customize( 'newsum_newspaper_left_show_date', function( value ) {
            value.bind( updateNewspaperLeftHeader );
        } );

        wp.customize( 'newsum_newspaper_right_kicker', function( value ) {
            value.bind( updateNewspaperRightHeader );
        } );

        wp.customize( 'newsum_newspaper_right_badge', function( value ) {
            value.bind( updateNewspaperRightHeader );
        } );

        wp.customize( 'newsum_newspaper_right_show_badge', function( value ) {
            value.bind( updateNewspaperRightHeader );
        } );

        // 15. Hero Slider Auto-Play Toggle Live Feedback
        wp.customize( 'newsum_hero_slider_autoplay', function( value ) {
            value.bind( function( to ) {
                var $slider = $( '#heroSlider' );
                if ( $slider.length ) {
                    $slider.attr( 'data-autoplay', to ? 'true' : 'false' );
                    if ( typeof window.newsumInitHeroSlider === 'function' ) {
                        window.newsumInitHeroSlider();
                    }
                }
            } );
        } );

        // 16. Hero Side Cards Content Rotation Toggle Live Feedback
        wp.customize( 'newsum_hero_side_cards_rotate', function( value ) {
            value.bind( function( to ) {
                var $wall = $( '.hero-live-wall' );
                if ( $wall.length ) {
                    $wall.attr( 'data-rotate', to ? 'true' : 'false' );
                    if ( typeof window.newsumInitLiveWall === 'function' ) {
                        window.newsumInitLiveWall();
                    }
                }
            } );
        } );

        // 17. Custom CTA Banner Card Background Color Live Preview
        wp.customize( 'newsum_custom_banner_bg_color', function( value ) {
            value.bind( function( to ) {
                var $card = $( '.custom-cta-card' );
                if ( $card.length ) {
                    if ( to ) {
                        $card.css( 'background-color', to );
                        var hex = to.replace( '#', '' );
                        if ( hex.length === 6 ) {
                            var r = parseInt( hex.substring( 0, 2 ), 16 );
                            var g = parseInt( hex.substring( 2, 4 ), 16 );
                            var b = parseInt( hex.substring( 4, 6 ), 16 );
                            var lum = ( 0.299 * r + 0.587 * g + 0.114 * b ) / 255;
                            $card.toggleClass( 'has-light-bg', lum > 0.55 );
                        }
                    } else {
                        $card.css( 'background-color', '' );
                        $card.removeClass( 'has-light-bg' );
                    }
                }
            } );
        } );
    } );
} )( jQuery );
