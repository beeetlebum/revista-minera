<?php
/**
 * Block Patterns for NewSum
 *
 * @package NewSum
 */

/**
 * Register NewSum Block Patterns and Categories
 */
function newsum_register_block_patterns() {
    if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    // Register Category
    register_block_pattern_category(
        'newsum-editorial',
        array(
            'label' => __( 'NewSum Editorial', 'newsum' ),
        )
    );

    // 1. Key Takeaways Callout Box
    register_block_pattern(
        'newsum/key-takeaways',
        array(
            'title'       => __( 'Key Takeaways Box', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'featured' ),
            'description' => __( 'An executive summary callout box with bullet points.', 'newsum' ),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","right":"1.5rem","bottom":"1.5rem","left":"1.5rem"},"margin":{"top":"2rem","bottom":"2rem"}},"border":{"radius":"4px","left":{"color":"#e11d48","width":"3px"}}},"backgroundColor":"surface","className":"article-takeaways-card"} -->
<div class="wp-block-group article-takeaways-card has-surface-background-color has-background" style="border-left-color:#e11d48;border-left-width:3px;border-radius:4px;margin-top:2rem;margin-bottom:2rem;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
<!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.06em"},"color":{"text":"#e11d48"}},"fontSize":"small"} -->
<h4 class="wp-block-heading has-text-color has-small-font-size" style="color:#e11d48;font-style:normal;font-weight:700;letter-spacing:0.06em;text-transform:uppercase">KEY TAKEAWAYS</h4>
<!-- /wp:heading -->
<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item -->
<li>Critical developments reshaping the landscape across modern industry verticals.</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li>Early adoption data reveals significant efficiency and cost benefits for forward-looking teams.</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li>Regulatory and privacy frameworks are expected to evolve throughout the upcoming quarter.</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->',
        )
    );

    // 2. Editorial Pullquote
    register_block_pattern(
        'newsum/editorial-pullquote',
        array(
            'title'       => __( 'Editorial Pull-Quote', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'text' ),
            'description' => __( 'A bold pull-quote for impactful statements.', 'newsum' ),
            'content'     => '<!-- wp:quote {"className":"is-style-default pull-quote"} -->
<blockquote class="wp-block-quote is-style-default pull-quote"><p>"The true benchmark of journalistic excellence is not speed, but the relentless pursuit of clarity in an overwhelming era."</p><cite>— Editorial Board Dispatch</cite></blockquote>
<!-- /wp:quote -->',
        )
    );

    // 3. Two-Column Feature Story Split
    register_block_pattern(
        'newsum/feature-split',
        array(
            'title'       => __( 'Feature Story Split', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'columns' ),
            'description' => __( 'A split column feature story layout with image and text.', 'newsum' ),
            'content'     => '<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"3rem","bottom":"3rem"},"blockGap":{"top":"2rem","left":"2.5rem"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:3rem;margin-bottom:3rem">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-large is-style-rounded"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/default-fallback.svg' ) . '" alt="Feature Story"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em"},"color":{"text":"#e11d48"}},"fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="color:#e11d48;letter-spacing:0.08em;text-transform:uppercase">SPECIAL DOSSIER</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Next-Generation Infrastructure: The Architecture of Scale</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>As networks expand, engineering teams must balance rapid feature iteration with foundational reliability. This comprehensive field report examines the structural paradigms leading organizations adopt to thrive under exponential demand.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"accent","textColor":"black","style":{"border":{"radius":"3px"},"typography":{"fontWeight":"700","textTransform":"uppercase"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-accent-background-color has-text-color has-background wp-element-button" style="border-radius:3px;font-weight:700;text-transform:uppercase">Read Special Report</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->',
        )
    );

    // 4. In-Article Newsletter CTA Box
    register_block_pattern(
        'newsum/article-newsletter-box',
        array(
            'title'       => __( 'In-Article Newsletter Box', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'featured' ),
            'description' => __( 'A sleek newsletter signup box for inserting inside long articles.', 'newsum' ),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"},"margin":{"top":"3rem","bottom":"3rem"}},"border":{"radius":"4px","width":"1px"}},"backgroundColor":"surface","className":"article-inline-newsletter"} -->
<div class="wp-block-group article-inline-newsletter has-surface-background-color has-background" style="border-width:1px;border-radius:4px;margin-top:3rem;margin-bottom:3rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Get The Daily Briefing</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"textAlign":"center","style":{"color":{"text":"#94a3b8"}}} -->
<p class="has-text-align-center has-text-color" style="color:#94a3b8">Curated reporting, deep analysis, and essential context delivered to your inbox every Thursday morning.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"backgroundColor":"accent","textColor":"black","style":{"border":{"radius":"3px"},"typography":{"fontWeight":"700","textTransform":"uppercase"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-accent-background-color has-text-color has-background wp-element-button" style="border-radius:3px;font-weight:700;text-transform:uppercase">Subscribe for Free</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
        )
    );

    // 5. Magazine 3-Column Story Grid
    register_block_pattern(
        'newsum/query-magazine-grid',
        array(
            'title'       => __( 'Magazine 3-Column Story Grid', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'query' ),
            'description' => __( 'A 3-column responsive grid displaying latest stories with images, category pills, headlines, and reading metadata.', 'newsum' ),
            'content'     => '<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"3rem","bottom":"3rem"}}},"className":"newsum-pattern-magazine-grid"} -->
<div class="wp-block-group alignwide newsum-pattern-magazine-grid" style="margin-top:3rem;margin-bottom:3rem">
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","alignItems":"center"},"style":{"spacing":{"margin":{"bottom":"1.5rem"}}},"className":"newsum-pattern-header"} -->
<div class="wp-block-group newsum-pattern-header" style="margin-bottom:1.5rem">
<!-- wp:heading {"level":2,"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.04em","fontStyle":"normal","fontWeight":"800"}},"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size" style="font-style:normal;font-weight:800;letter-spacing:0.04em;text-transform:uppercase">' . esc_html__( 'LATEST DISPATCHES', 'newsum' ) . '</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"},"color":{"text":"#e11d48"}},"fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="color:#e11d48;font-style:normal;font-weight:700;letter-spacing:0.08em;text-transform:uppercase">// FIELD INTELLIGENCE</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","style":{"border":{"radius":"3px"}}} /-->
<!-- wp:post-terms {"term":"category","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.06em"},"color":{"text":"#e11d48"},"spacing":{"margin":{"top":"0.75rem","bottom":"0.5rem"}}},"fontSize":"small"} /-->
<!-- wp:post-title {"isLink":true,"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"medium"} /-->
<!-- wp:post-excerpt {"moreText":"' . esc_html__( 'Read Story &rarr;', 'newsum' ) . '","excerptLength":16} /-->
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"},"style":{"typography":{"fontSize":"0.75rem"},"color":{"text":"#a1a1aa"},"spacing":{"margin":{"top":"0.75rem"}}}} -->
<div class="wp-block-group has-text-color" style="color:#a1a1aa;margin-top:0.75rem;font-size:0.75rem">
<!-- wp:post-author-name {"isLink":true} /-->
<!-- wp:post-date /-->
</div>
<!-- /wp:group -->
<!-- /wp:post-template -->
<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:group -->',
        )
    );

    // 6. Editorial Hero Spotlight & Side Stories
    register_block_pattern(
        'newsum/query-hero-split',
        array(
            'title'       => __( 'Editorial Hero Spotlight & Side Stories', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'query' ),
            'description' => __( 'A magazine bento block featuring 1 large lead story on the left and 2 stacked dispatches on the right.', 'newsum' ),
            'content'     => '<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"3rem","bottom":"3rem"}}},"className":"newsum-pattern-hero-split"} -->
<div class="wp-block-group alignwide newsum-pattern-hero-split" style="margin-top:3rem;margin-bottom:3rem">
<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"2rem","left":"2.5rem"}}}} -->
<div class="wp-block-columns alignwide">
<!-- wp:column {"width":"62%"} -->
<div class="wp-block-column" style="flex-basis:62%">
<!-- wp:query {"queryId":2,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"4px"}}} /-->
<!-- wp:post-terms {"term":"category","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.06em"},"color":{"text":"#e11d48"},"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}},"fontSize":"small"} /-->
<!-- wp:post-title {"isLink":true,"level":2,"style":{"typography":{"fontStyle":"normal","fontWeight":"800","lineHeight":"1.2"}},"fontSize":"large"} /-->
<!-- wp:post-excerpt {"moreText":"' . esc_html__( 'Read Lead Story &rarr;', 'newsum' ) . '","excerptLength":28} /-->
<!-- wp:group {"layout":{"type":"flex"},"style":{"typography":{"fontSize":"0.75rem"},"color":{"text":"#a1a1aa"},"spacing":{"margin":{"top":"0.75rem"}}}} -->
<div class="wp-block-group has-text-color" style="color:#a1a1aa;margin-top:0.75rem;font-size:0.75rem">
<!-- wp:post-author-name {"isLink":true} /-->
<!-- wp:post-date /-->
</div>
<!-- /wp:group -->
<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"38%"} -->
<div class="wp-block-column" style="flex-basis:38%">
<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","letterSpacing":"0.08em","textTransform":"uppercase"},"color":{"text":"#e11d48"}},"fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="color:#e11d48;font-style:normal;font-weight:700;letter-spacing:0.08em;text-transform:uppercase">// SECONDARY DISPATCHES</p>
<!-- /wp:paragraph -->
<!-- wp:query {"queryId":3,"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","style":{"border":{"radius":"3px"},"spacing":{"margin":{"bottom":"0.5rem"}}}} /-->
<!-- wp:post-terms {"term":"category","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase","letterSpacing":"0.06em"},"color":{"text":"#e11d48"}},"fontSize":"small"} /-->
<!-- wp:post-title {"isLink":true,"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"medium"} /-->
<!-- wp:post-date {"style":{"typography":{"fontSize":"0.75rem"},"color":{"text":"#a1a1aa"}}} /-->
<!-- wp:separator {"style":{"spacing":{"margin":{"top":"1rem","bottom":"1.5rem"}}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:1rem;margin-bottom:1.5rem"/>
<!-- /wp:separator -->
<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
        )
    );

    // 7. Editorial News Wire List
    register_block_pattern(
        'newsum/query-news-wire',
        array(
            'title'       => __( 'Editorial News Wire List', 'newsum' ),
            'categories'  => array( 'newsum-editorial', 'query' ),
            'description' => __( 'A dense, fast-scanning headline list with publication timestamps and category tags, perfect for breaking news or sidebars.', 'newsum' ),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"},"margin":{"top":"3rem","bottom":"3rem"}},"border":{"radius":"4px","width":"1px"}},"backgroundColor":"surface","className":"newsum-pattern-news-wire"} -->
<div class="wp-block-group newsum-pattern-news-wire has-surface-background-color has-background" style="border-width:1px;border-radius:4px;margin-top:3rem;margin-bottom:3rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem">
<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between","alignItems":"center"},"style":{"spacing":{"margin":{"bottom":"1.5rem"}}}} -->
<div class="wp-block-group" style="margin-bottom:1.5rem">
<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.05em","fontWeight":"800"}},"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size" style="font-weight:800;letter-spacing:0.05em;text-transform:uppercase">TERMINAL WIRE FEED</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.75rem"},"color":{"text":"#e11d48"}}} -->
<p class="has-text-color" style="color:#e11d48;font-size:0.75rem;font-weight:700">● LIVE UPDATES</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:query {"queryId":4,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query">
<!-- wp:post-template -->
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","alignItems":"baseline"},"style":{"spacing":{"margin":{"bottom":"0.75rem"},"blockGap":"1rem"}}} -->
<div class="wp-block-group" style="margin-bottom:0.75rem">
<!-- wp:post-date {"format":"H:i","style":{"typography":{"fontSize":"0.75rem","fontWeight":"700"},"color":{"text":"#e11d48"}}} /-->
<!-- wp:post-title {"isLink":true,"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"small"} /-->
</div>
<!-- /wp:group -->
<!-- wp:separator {"style":{"spacing":{"margin":{"top":"0.5rem","bottom":"0.75rem"}}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:0.5rem;margin-bottom:0.75rem"/>
<!-- /wp:separator -->
<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:group -->',
        )
    );
}
add_action( 'init', 'newsum_register_block_patterns' );
