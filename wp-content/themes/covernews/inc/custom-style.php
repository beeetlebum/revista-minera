<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Customizer
 *
 * @class   covernews
 */

if (!function_exists('covernews_custom_style')) {

    function covernews_custom_style()
    {


        $covernews_primary_font = 'system-ui, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
        $covernews_secondary_font = 'system-ui, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';


        ob_start();
?>


<?php if (!empty($covernews_primary_font)) : ?>
            body,body :is(button,input,select,optgroup,textarea) {font-family: <?php echo $covernews_primary_font; ?>;}
        <?php endif; ?>

        <?php if (!empty($covernews_secondary_font)) : ?>
            body h1,body h2,body h3,body h4,body h5,body h6,
            .bs-exclusive-now,
            .blockspare-posts-block-post-category a,
            .blockspare-posts-block-post-grid-byline,
            body .wp-block-search__label,
            body .main-navigation a,
            body .font-family-1,
            body .site-description,
            body .trending-posts-line,
            body .exclusive-posts,
            body .widget-title,
            body .em-widget-subtitle,
            body .entry-meta .item-metadata,
            body .grid-item-metadata .item-metadata,
            body .grid-item-metadata span.item-metadata.posts-author,
            body .grid-item-metadata span.aft-comment-count,
            body .grid-item-metadata span.aft-view-count,
            body .af-navcontrols .slide-count,
            body .figure-categories .cat-links,
            body .nav-links a,
            body span.trending-no {
            font-family: <?php echo $covernews_secondary_font; ?>;
            }
        <?php endif; ?>

        .align-content-left .elementor-section-stretched,
        .align-content-right .elementor-section-stretched { max-width: 100%;left: 0 !important;}


<?php
        $css = ob_get_clean();

        // Minify CSS: remove comments, newlines, extra spaces
        $css = preg_replace(
            array('!/\*.*?\*/!s', '/\s+/', '/\s*([\{\};:,])\s*/'),
            array('', ' ', '$1'),
            $css
        );

        return trim($css);

        // return ob_get_clean();
    }
}
