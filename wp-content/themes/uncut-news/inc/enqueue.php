<?php

// Add custom fonts, used in the main stylesheet.
wp_enqueue_style( 'uncut-news-fonts', wptt_get_webfont_url( uncut_news_fonts_url() ), array(), null );

// Slick style.
wp_enqueue_style( 'slick-style', get_template_directory_uri() . '/assets/css/slick.min.css', array(), '1.8.0' );

// Fontawesome style.
wp_enqueue_style( 'fontawesome-style', get_template_directory_uri() . '/assets/css/all.min.css', array(), '6.7.2' );

// Conveyor Ticker style.
wp_enqueue_style( 'conveyor-ticker-style', get_template_directory_uri() . '/assets/css/jquery.jConveyorTicker.min.css', array(), '1.1.0' );

// blocks.
wp_enqueue_style( 'uncut-news-blocks-style', get_template_directory_uri() . '/assets/css/blocks.min.css' );

// style.
wp_enqueue_style( 'uncut-news-style', get_template_directory_uri() . '/style.css', array(), UNCUT_NEWS_VERSION );

// navigation.
wp_enqueue_script( 'uncut-news-navigation', get_template_directory_uri() . '/assets/js/navigation.min.js', array(), UNCUT_NEWS_VERSION, true );

// Slick script.
wp_enqueue_script( 'slick-script', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), '1.8.0', true );

// Conveyor Ticker script.
wp_enqueue_script( 'conveyor-ticker-script', get_template_directory_uri() . '/assets/js/jquery.jConveyorTicker.js', array( 'jquery' ), '1.1.0', true );

// Custom script.
wp_enqueue_script( 'uncut-news-custom-script', get_template_directory_uri() . '/assets/js/custom.min.js', array( 'jquery' ), UNCUT_NEWS_VERSION, true );

if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}