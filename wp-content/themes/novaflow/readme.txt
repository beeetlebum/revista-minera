=== Novaflow ===
Contributors: yineali
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A colorful, responsive news and magazine theme built with native WordPress features.

== Description ==
Novaflow is a classic WordPress news and magazine theme with an editorial latest-posts layout, full static-page support, article and archive templates, widget areas, three menu locations, block editor styles, and optional RTL styling.

Novaflow combines a high-impact lead story, supporting headlines, structured story cards, trending content, category navigation, article metadata, and flexible sidebar regions in one responsive editorial system. Its visual language is energetic without compromising readability, making it suitable for newsrooms, online magazines, culture publications, technology journals, entertainment sites, and independent editorial teams.

The theme is designed around native WordPress workflows. Publishers can manage identity, menus, widgets, header areas, colors, typography, social links, advertisements, the header banner, and homepage sections without a page builder or companion plugin. Semantic markup, keyboard-friendly navigation, responsive images, translatable strings, RTL support, and visible focus states provide a dependable foundation for accessible publishing.

The theme uses WordPress posts, pages, menus, widgets, the Media Library, the Customizer, and one prefixed option array. It does not require a companion plugin and does not include analytics, tracking, social-sharing functionality, remote fonts, third-party scripts, direct external API requests, or automatic redirects. The top utility bar shows the localized date, an owner-managed utility menu, optional social-profile links, and a color-mode switch. The owner-requested footer retains its fixed Turkish linked phrase and an ordinary link to https://narya.tr/. The surrounding copyright sentence is translatable; the linked phrase remains the owner's explicit exception and is not configurable in the admin panel.

== Installation ==
1. Open Appearance > Themes > Add New > Upload Theme.
2. Upload the Novaflow ZIP file and activate the theme.
3. Open Appearance > Customize to configure the Novaflow Header, Novaflow Social Links, Novaflow Homepage, and Novaflow Design sections.
4. Set the site logo and optional header image in their native WordPress Customizer controls.
5. Assign the Primary, Utility, and Footer menus under Appearance > Menus.
The primary menu's custom homepage link can use the nf-menu-home-highlight CSS class for its permanent highlight and localized Home label. Translation applies only when that marked item's URL is the current site's homepage. Other custom menu labels remain owner-managed content.
6. Configure the Primary Sidebar, Header Banner, and footer columns under Appearance > Widgets.
7. Select either latest posts or a static front page under Settings > Reading. Novaflow respects both choices.

== Frequently Asked Questions ==

= Does Novaflow require a page builder or plugin? =
No. The theme uses native WordPress APIs and content.

= Does the theme contact external services? =
The theme itself does not make direct external API requests or enqueue remote assets. Standard WordPress features and content added by the site owner, such as avatars, embeds, widgets, banner links, and links, may contact services selected by WordPress or the site owner.

= How does the front page work? =
The magazine front page shows three featured stories and eight independently paginated latest stories in both Reading modes. A static page keeps its editor content above this feed. A separately selected posts page uses the standard blog index and WordPress pagination.

= How do I add links to the top information bar? =
Assign a menu to the Utility Menu location under Appearance > Menus. If no menu is assigned, the theme shows only generic WordPress destinations that are already configured: Home, the posts page, and the privacy-policy page. The right side defaults to the official Facebook, Instagram, Pinterest, YouTube, and X homepages. Under Appearance > Customize > Novaflow Social Links, replace these URLs with your public profiles or clear a field to hide that icon. TikTok and LinkedIn remain optional. All social links use nofollow, noopener, and noreferrer. These are ordinary navigation links, not sharing buttons.

= How do I configure advertisements, the header banner? =
The optional header advertisement area is disabled by default. You can enable and manage it under Appearance > Customize > Novaflow Header Advertisement with either WordPress-native method:

1. Upload a custom advertisement image in the Customizer and optionally enter its destination URL, alternative text, and new-tab preference.
2. Leave the Customizer image empty and add an Image, Custom HTML, or other block/widget under Appearance > Widgets > Header Banner.

If no custom image or widget is enabled, the colorful search header and three visual shortcuts are displayed. Choose their categories under Appearance > Customize > Novaflow Header. Automatic destinations use the posts page configured under Settings > Reading. If no separate posts page is configured, shortcuts use the homepage latest-posts section. No site-specific category names or IDs are embedded in the theme.

= How do languages work? =
Theme interface source strings are English and use the novaflow text domain. Turkish translations are bundled in languages/tr_TR.po and languages/tr_TR.mo; languages/novaflow.pot is the source catalog for additional translations. WordPress loads available language packs for the selected site locale. An unavailable translation falls back to the English source; the theme does not automatically translate into every language.

Optional localized section headings use the native WordPress Block Bindings API (novaflow/section-heading). Bind a core Heading block's content to one of these keys: latest-posts, latest-comments, archives, categories. Bound headings are translated when rendered, so a previously saved Turkish heading does not remain fixed after changing Site Language. The binding source is available in supported block editors. Unbound custom headings are never rewritten. Removing the binding restores the saved fallback text, which remains readable when another theme is active.

Example Heading block markup for the Widgets editor's code view:
<!-- wp:heading {"metadata":{"bindings":{"content":{"source":"novaflow/section-heading","args":{"key":"latest-comments"}}}}} -->
<h2 class="wp-block-heading">Recent Comments</h2>
<!-- /wp:heading -->

Saved widget bindings are site configuration, not files in a theme ZIP. Updating a theme does not automatically rewrite an existing site's widgets. Configure the binding once on each site where a saved heading should follow the site language. The source supports frontend rendering on WordPress 6.6+, with editor binding controls depending on the WordPress editor version.

Posts, pages, taxonomy names, custom menu labels, custom logos, and manually saved widget headings are site content, not theme-interface strings. Changing Settings > General > Site Language does not translate that content. Translate it through WordPress content management or a dedicated multilingual-content plugin. The theme does not rewrite owner content or send text to an external translation service. The owner-requested fixed Turkish footer phrase is an explicit exception, not a universal translation or WordPress.org approval claim.

= How do I use the 1600px layout? =
The default and maximum canvas width is 1600px. Existing saved choices are respected; choose 1600 under Appearance > Customize > Novaflow Design > Container width to update a previously saved 1500px installation.

= Where are theme settings stored? =
All custom presentation settings are stored in one prefixed option array named novaflow_options. They are edited through the native WordPress Customizer under Appearance > Customize, while WordPress core identity, menu, widget, and header-image controls remain in their native interfaces.

== Privacy ==
Novaflow does not collect, store, transmit, or sell personal data and does not include tracking code or direct external API calls. Clicking the author-site or social-platform links takes the visitor to an external website. The fixed footer link points to https://narya.tr/, while optional social links are configured by the site owner. Standard WordPress features and content added by the site owner—such as avatars, embeds, widgets, menus, banner links, and links—may have separate privacy implications controlled by WordPress or the site owner.

The color-mode control stores only the visitor's light/dark preference in localStorage under novaflow-color-mode. It is not sent to a remote server.

== Copyright ==
Novaflow WordPress Theme, Copyright (C) 2026 yineali.
Novaflow is distributed under the terms of the GNU General Public License v2 or later.

Except where another GPL-compatible license is explicitly stated below, all PHP, JavaScript, CSS, JSON, templates, documentation, and original artwork included in the Novaflow theme package are Copyright (C) 2026 yineali and licensed under GPLv2 or later.

== Resources ==

= Theme code and original interface artwork =
Novaflow theme code, CSS artwork, original interface icons, and documentation. Lucide-derived inline paths are covered by the Lucide/Feather notices below.
Copyright (C) 2026 yineali.
License: GNU GPL v2 or later, https://www.gnu.org/licenses/gpl-2.0.html
Source: https://narya.tr/novaflow/

Novaflow wordmark and logo files: assets/images/novaflow-logo.png and assets/images/novaflow-logo.webp.
Copyright (C) 2026 yineali.
License: GNU GPL v2 or later, https://www.gnu.org/licenses/gpl-2.0.html
Source: https://narya.tr/novaflow/

= Screenshot and author-created photographs =
File: screenshot.png (1200 x 900 pixels).
The author supplied the new screenshot on 23 September 2026 and confirmed that the room and furniture photographs are their own creations.
Creator: yineali.
The author's original photographic content and screenshot composition are dedicated to the public domain under CC0 1.0 Universal:
https://creativecommons.org/publicdomain/zero/1.0/
Full CC0 legal text bundled at assets/icons/social/LICENSE-simple-icons.md.
Source: original author-supplied artwork embedded in screenshot.png in this theme distribution.
Author/credit URL: https://profiles.wordpress.org/yineali/
The complete author-supplied screenshot is resized to 1200 x 900 pixels without cropping or adding borders. Its horizontal and vertical scale are adjusted to fill the 4:3 preview card. No photographs or text are replaced.
Third-party icons visible in the screenshot retain their own licenses listed below. CC0 does not override third-party rights.

Individual photographs shown in screenshot.png (same creator, CC0 license, and author/credit URL above):
* Plant-filled living room with sofa and bookshelf: lead story, lower story card, and a trending thumbnail.
* Bedroom with framed artwork: upper supporting story and first trending thumbnail.
* Natural wood dining room: lower supporting story, first latest-story card, and second trending thumbnail.
* Shelving and seating interior: third trending thumbnail.
* Yellow armchair and wall art: fourth trending thumbnail.
* White office desk and gray chair: third latest-story card.
* Furniture showroom with sofas and wood tables: fourth latest-story card.
Repeated appearances and embedded decorative photo overlays are included in the author's release. Separate full-resolution photographs are not bundled; they are not automatically imported into a website.

= Simple Icons =
Files used by header.php: assets/icons/social/facebook.svg, instagram.svg, linkedin.svg, pinterest.svg, tiktok.svg, x.svg, and youtube.svg.
Simple Icons contributors.
License: CC0 1.0 Universal, https://creativecommons.org/publicdomain/zero/1.0/
Source: https://github.com/simple-icons/simple-icons
Bundled license copy: assets/icons/social/LICENSE-simple-icons.md

= Bootstrap Icons =
Files used as CSS masks in assets/css/main.css and assets/css/ui.css: assets/images/meta-icons/calendar3.svg and assets/images/meta-icons/eye-fill.svg.
Header shortcut icons rendered by header.php: assets/images/header-icons/inspiration.svg, editor-pick.svg, and trending.svg. Existing screenshot credits are preserved.
Copyright (c) 2019-2024 The Bootstrap Authors.
License: MIT, https://opensource.org/license/mit/
Source: https://github.com/twbs/icons
Bundled license copy: assets/images/meta-icons/LICENSE-bootstrap-icons.txt

= Lucide =
Interface SVG files in assets/icons/lucide, assets/icons/social/narya.svg, and Lucide-derived inline paths in inc/template-tags.php. Used by the theme's icon helpers and stylesheet masks.
Copyright (c) 2026 Lucide Icons and Contributors; Feather-derived icons Copyright (c) 2013-present Cole Bemis.
License: ISC; Feather-derived icons MIT. Both full notices are retained in the bundled LICENSE file.
License URLs: https://opensource.org/license/isc-license-txt/ and https://opensource.org/license/mit/
Source: https://github.com/lucide-icons/lucide
Bundled license copy: assets/icons/lucide/LICENSE

= Fonts =
Novaflow uses a system font stack. No font files are bundled or loaded remotely.

No third-party photographs, stock images, fonts, CDN files, or remote runtime resources are loaded by the theme. All bundled third-party icons are listed above with their filenames, copyright, license, source, and local license-copy path.

== Changelog ==

= 1.0.98 (shortcut restoration and translation maintenance) =
* Restored the three header shortcuts with configured category destinations and a real anchor for Editor's selection.
* Added safe automatic destinations and localized Customizer guidance; invalid categories no longer bypass the supplied fallback.
* Made the footer copyright suffix translatable, restored the shortcut translations and completed the missing contextual comment-count translation.
* Kept version 1.0.98, native locale handling, owner content, existing navigation behavior and assets unchanged.

= 1.0.98 (owner-requested fixed version, 2026-09-24) =
* Removed only the three pictured header shortcuts: Inspiring Ideas, Editor's selection, and Trends. Other visible sections, styles, scripts and saved site data are unchanged.
* Set the release version to 1.0.98 at the owner's request. Higher-numbered entries below are retained as historical development notes, not the current release number.

= 1.0.293 =
* Stabilized menu dimensions during hover, focus and selected-page states. Color feedback remains; weight changes and vertical movement no longer shift neighboring items.
* Preserved the gold icon button and permanent Home highlight. Repeated links are managed in WordPress menus, not silently filtered by the theme.

= 1.0.292 =
* Matched the icon-only overflow button to the pale-yellow navigation palette.
* Added an optional persistent hover-style highlight using the nf-menu-home-highlight CSS class on an owner-managed menu item. Set the Home label and destination under Appearance > Menus; the theme does not replace category links or modify menu records automatically.

= 1.0.291 =
* Made the overflow control icon-only, retaining an accessible translated name, and evenly distributed spare space across visible navigation items.
* Preserved the single-row overflow behavior and stable loading layout. Menu destinations and footer are unchanged.

= 1.0.290 =
* Prevented the initial desktop navigation flash and vertical jump with a reserved row and font-aware first reveal. A script-failure timeout restores the scrollable fallback.
* Restyled the More disclosure to match the colorful navigation, including dark mode and keyboard focus. Existing links and footer are unchanged.

= 1.0.289 =
* The existing footer credit now opens in a new tab with noopener/noreferrer and remains an ordinary followed link.
* Desktop navigation stays on one row; links that do not fit appear under More. Menu destinations, order and submenus are preserved, including after resizing. Mobile navigation remains available through its existing toggle.

= 1.0.288 =
* Changed the footer's surrounding text to English while keeping only the linked phrase in fixed Turkish. No link attributes, styles, or other frontend behavior changed.

= 1.0.287 =
* Applied the owner's fixed Turkish footer sentence with a visible, ordinary HTML link to https://narya.tr/ through the existing footer action.
* Kept the link text independent of gettext and removed rel restrictions and the underline override.
* No other frontend behavior or configuration settings changed.

= 1.0.286 =
* Restored five visible social-network shortcuts with official platform homepage defaults, editable or removable in the Customizer. Every social link uses nofollow, noopener, and noreferrer.
* Made the existing Author URI credit clearly visible as an underlined Narya link; the credit also uses nofollow.
* Utility links continue to use a menu assigned by the site owner; the theme does not create pages, menus, or site-specific slugs automatically.
* Preserved the borderless 1200 x 900 screenshot and all existing licenses.

= 1.0.285 =
* Removed the screenshot's added white margins and resized the complete original screenshot to 1200 x 900 pixels so it fills the 4:3 preview card.
* Preserved all pictured content and existing image licenses; no theme layout or functional code changes.

= 1.0.284 =
* Re-reviewed the updated 1.0.283 theme against the five review points.
* Replaced the screenshot with the author's new interior/furniture screenshot and updated all photographic credits and the CC0 dedication.
* Preserved Bootstrap, Simple Icons, and Lucide/Feather notices because these assets are actually used.
* Removed hardcoded page/category shortcuts and content-title/menu URL rewriting; added native Customizer category controls.
* Restored custom-logo output and optional-only social-profile destinations.
* Replaced the unrelated footer keyword link with the site copyright and a neutral author credit.
* Fixed magazine pagination in both Reading modes and bounded the Most Read request.
* Set the default and maximum width to 1600px while respecting previously saved preferences.
* Made every primary-menu entry available across responsive layouts and refreshed translation catalogs.

= 1.0.283 =
* Hardens backend/runtime code without changing the existing frontend design or responsive layout.
* Removes obsolete header-search category query/JavaScript code and other verified dead runtime code.
* Adds safer category-link handling, request-level option/category caching, stronger WP_Error fallbacks, and defensive text processing.
* Prevents unbounded Most Read fallback scans and stops duplicating comments when fewer than six approved comments exist.
* Keeps the fixed Turkish footer anchor required by the project while restoring translation handling for the surrounding copyright sentence.

= 1.0.282 =
* Sets existing installations to the requested 1500px theme width.
* Rebuilds the default header search to the supplied pill design with a functional category selector and pink Search arrow button.

= 1.0.252 =
* Rebuilds the three right-side header shortcuts to match the supplied vertical icon model, with circular icon badges, centered labels, subtitles, and separators.
* Makes each shortcut open its intended WordPress category archive and bundles three local Bootstrap Icons for the shortcuts.

= 1.0.251 =
* Rebuilds the default header center with the reference-style wide search field and three pastel shortcut cards, while keeping the optional Customizer header advertisement available when enabled.
* Adds responsive, dark-mode, and translation support for the new header shortcuts.

= 1.0.250 =
* Increases all text in the sidebar Editor's Selection widget by 2px without changing the card or image dimensions.
* Fixes homepage Latest Stories pagination so page 2 and later display the next eight published posts while the three Featured Stories remain stable.

= 1.0.246 =
* Fixes the previous typography override by converting all theme-owned public font weights to 400, removing synthetic menu bolding, and disabling synthesized bold.

= 1.0.198 =
* Standardizes all public-facing theme text to a normal 400 weight so headings, cards, menus, labels, buttons, strong text, widgets, and footer text no longer render bold.

= 1.0.197 =
* Restores and standardizes card hover motion across the theme: every reusable card rises consistently and media images zoom with one shared CSS rule set.

= 1.0.196 =
* Keeps the Home menu item visibly selected on the front page and places the Featured stories heading/mosaic inside one outer box.

= 1.0.195 =
* Replaces the unmasked square shown for the thirteenth/Shop primary-menu item with a locally bundled Lucide Store SVG matching the existing menu icon set.

= 1.0.194 =
* Distribute visible desktop primary-menu items evenly across the full navigation width while preserving natural label widths and hiding only trailing items that do not fit.

= 1.0.193 =
* Keeps desktop menu labels at natural width and displays only the complete top-level items that fit inside the 1500px navigation row; excess trailing items are hidden without an overflow button.

= 1.0.192 =
* Keeps the desktop primary menu inside the 1500px theme container after removal of the overflow button by allowing menu items to shrink safely.

= 1.0.191 =
* Removes the desktop primary-menu overflow control that generated the yellow three-line button at the right edge of the menu.
* Keeps WordPress primary-menu items in the normal menu row and prevents stale overflow-control markup from appearing.

= 1.0.190 =
* Removes the yellow hamburger/mobile menu button from the header and removes its reserved mobile grid space.

= 1.0.189 =
* Sets the theme container default and maximum width to 1500px and migrates older 1600px saved values down to the new maximum.

= 1.0.188 =
* Sets each desktop primary-menu item to 40px high and widens the right-side overflow control by 50px.

= 1.0.100 =
* Kept the desktop primary menu section at 40px while enlarging the inner navigation row, labels, icons, separators, spacing, and overflow control proportionally.

= 1.0.99 =
* Expanded the default and maximum theme container width from 1500px to 1600px.
* Added a one-time migration so installations saved at the former 1500px ceiling adopt the new width without blocking later Customizer choices.

= 1.0.98 =
* Removed the empty page-templates directory from the distributable package.
* Removed automatic primary-menu injection and all language-specific category slug assumptions; menu contents are now controlled entirely in WordPress and decorative icons use generic term IDs.
* Replaced non-English source strings with English translatable strings and refreshed the translation template and Turkish translation.
* Documented every bundled icon and image with exact file paths, copyright, license, source URL, and bundled license-copy location.
* Restored the author-supplied photographic screenshot and documented the author's GPLv2-or-later release for every photograph shown in it.
* Removed theme-side social sharing buttons from single posts because social share functionality belongs in plugins under the WordPress.org Theme Review requirements.
* Made all optional social-profile destinations empty by default so fresh installations render only the single permitted Narya author credit.
* Added a colorful editorial header showcase that fills the banner position whenever no site-owner advertisement or widget is configured.
* Set the theme canvas and Customizer width limit to 1500px for a balanced modern desktop layout.
* Restored screenshot.png with the original author-supplied photographs and retained its 1200 by 900 WordPress.org dimensions.
* Re-ran package, PHP, JavaScript, JSON, XML, link, tracking, privacy, and WordPress.org compliance checks.

= 1.0.97 =
* Removed all theme-side post-view tracking, cookies, database writes, stored-view queries, and default view-count output; plugins may optionally supply a read-only count through the existing filter.
* Changed the single permitted author credit to the Author URI at https://narya.tr/ and removed the former secondary credit link; the Narya credit is a normal followed link with only new-tab safety relationships.
* Updated compatibility metadata for WordPress 7.1 and addressed reviewer recommendations for color contrast, template variable naming, front-page heading structure, and translator comments.

= 1.0.96 =
* Hardened Customizer external URLs by requiring complete HTTP(S) destinations without embedded credentials.
* Added defensive type and error handling for filtered excerpts, article introductions, and WordPress tag links.
* Preserved the existing templates, CSS classes, layouts, hooks, option names, and WordPress-native media and comment workflows.

= 1.0.95 =
* Added a complete, privacy-friendly About Novaflow screen under Appearance using native WordPress administration APIs.
* Documented setup steps, theme features, requirements, accessibility, performance, licensing, privacy, and troubleshooting inside the theme.

= 1.0.94 =
* Added a theme-specific Theme URI header.
* Switched single-post tags to WordPress core tag output while preserving the existing Novaflow styling.
* Prepared the distributable theme under the required novaflow directory slug.

= 1.0.93 =
* Removed the header-banner shadow without changing its dimensions or placement.
* Added locally bundled WebP derivatives for the default logo and banner, reducing their combined transfer size while retaining the original source PNG files.
* Removed the unused front-end Dashicons request, reserved high image priority for the primary homepage visual, and lazy-loaded related-post thumbnails.
* Preserved WordPress core title, canonical, robots, feeds, and sitemap behavior while extending native HTML5 support to navigation widgets.

= 1.0.92 =
* Standardized the earlier author-site links with explicit labels and qualified new-tab relationships.
* Removed the earlier separate website Customizer option so the author-site credit followed one code path.

= 1.0.91 =
* Adjusted the earlier website-icon position in the utility social-link row.

= 1.0.90 =
* Updated the earlier website-link relationships and replaced generic link symbols with locally bundled Lucide icons.

= 1.0.89 =
* Changed the utility bar surface to white and added an optional, Customizer-managed external website slot with transparent accessible labeling and qualified outbound-link attributes.

= 1.0.88 =
* Increased only the Categories sidebar heading and category-label typography by two pixels while preserving the icon and grid dimensions.

= 1.0.87 =
* Removed white circular fills, visible borders, and shadows behind top-bar social icons.
* Retained a subtle transparent color highlight for keyboard and pointer interaction.

= 1.0.86 =
* Restyled the top utility bar with a modern colorful surface and clearer interactive states.
* Replaced the header's hand-drawn social marks with locally bundled Simple Icons brand SVGs.
* Added native Customizer URL controls and nofollow header links for TikTok, LinkedIn, and Pinterest.

= 1.0.85 =
* Increased all Newsroom Pulse card typography by two pixels.
* Limited Pulse card titles to one line and summaries to a stable two-line area.

= 1.0.84 =
* Limited Editor's selection card titles to a consistent two-line area.
* Increased homepage list-row height by another 20 pixels to 203 pixels.
* Increased three-column latest-card summary and date typography by two pixels.

= 1.0.83 =
* Widened every desktop sidebar column by 30 pixels across homepage, archive, page, and single-post templates.
* Increased homepage list-row height by 20 pixels and allowed summaries to use the complete available line width.
* Increased footer typography by two pixels while preserving its existing visual hierarchy and responsive layout.

= 1.0.82 =
* Expanded the default site container to fill modern desktop and 4K viewports while preserving existing theme spacing and responsive layouts.
* Kept saved Customizer width choices intact and extended the native width control to the new full-width limit.
* Reused the hardened advertisement image validator when normalizing saved options.
* Revalidated public templates, pagination, menus, headline controls, ticker animation, comments, sharing links, images, and responsive overflow.

= 1.0.81 =
* Hardened Customizer advertisement image validation with attachment-status and raster MIME allowlists.
* Limited post-view database writes to published GET requests and one count per visitor cookie per hour.
* Restored the native WordPress comment-cookie consent field and added core-compatible field length limits.
* Replaced static menu-icon innerHTML construction with safe DOM element creation.
* Audited upload, content, comment, REST, AJAX, database, output escaping, debug, and suspicious-code surfaces without changing the homepage template.

= 1.0.80 =
* Increased every text style in the homepage Newsroom Pulse section by one pixel while preserving its layout.

= 1.0.79 =
* Added a distinct three-card Newsroom Pulse section beneath homepage story pagination.
* Connected cards to native WordPress posts, categories, permalinks, dates, comments, excerpts, and view counts.
* Added query and before/after action hooks plus a responsive one-column mobile layout.

= 1.0.78 =
* Reduced desktop homepage list cards by 20 pixels at both the top and bottom.
* Preserved title, summary, category, icon, and metadata sizing while compacting vertical spacing.

= 1.0.77 =
* Removed the homepage list-card title divider.
* Vertically centered each story summary between its title and metadata row without changing card height.

= 1.0.76 =
* Added a subtle divider between each homepage list-card title and summary without changing card dimensions.

= 1.0.75 =
* Prevented portrait featured images from increasing homepage list-row height.
* Standardized all six desktop list cards at 203 pixels while preserving the existing mobile layout.
* Verified both numbered list pages for equal card sizing, image cropping, and horizontal overflow.

= 1.0.74 =
* Restored the additional homepage feed as an independent six-post WordPress query.
* Placed working numbered, previous, and next pagination directly below the six story rows.
* Preserved the existing card layout, sidebar, responsive rules, and hover effects.

= 1.0.73 =
* Replaced every legacy PHP, JavaScript, CSS, option, hook, block, image-size, and query-parameter prefix with the independent Novaflow namespace.
* Changed the theme option array to novaflow_options so Novaflow does not read or modify another theme's settings.
* Preserved the existing templates, layout values, responsive rules, colors, and interactions during the namespace migration.

= 1.0.72 =
* Prevented the additional homepage story list from repeating posts already displayed in the main editorial grid.
* Read the author link from the standard style.css Author URI instead of a hard-coded template URL.
* Completed bundled icon, metadata image, logo, advertisement, and screenshot license documentation.
* Re-audited the theme structure and current Theme Check rules.

= 1.0.71 =
* Limited Latest Comments widget entries to two lines while preserving their WordPress links.

= 1.0.70 =
* Made homepage card summaries and single-post introductions follow the current WordPress post content.
* Added extensibility filters to all editorial post queries.
* Audited dynamic WordPress links and content sources.

= 1.0.69 =
* Limited homepage horizontal-list descriptions to a stable two-line reading width.

= 1.0.68 =
* Limited homepage horizontal-list titles to one line and sidebar trending titles to two lines.

= 1.0.67 =
* Prevented card and image hover motion from flashing during reload while keeping the effects active for normal pointer interaction.

= 1.0.66 =
* Stabilized single post images during reload while preserving image hover effects.

= 1.0.65 =
* Removed custom reload masking and fixed position movement by using native instant scroll restoration.

= 1.0.64 =
* Prevented the brief top-of-page flash before a saved reload position is restored.

= 1.0.63 =
* Preserved the current scroll position during page reloads and restored top-bar social symbols.

= 1.0.62 =
* Replaced the animated comment submit treatment with a compact, light, static button.

= 1.0.61 =
* Refined the comment submit button and increased its label size for readability.

= 1.0.60 =
* Reduced the single post author avatar size by 15 pixels.

= 1.0.59 =
* Replaced hand-drawn share symbols with locally bundled, openly licensed brand SVG icons.

= 1.0.58 =
* Refined single post sharing controls and added LinkedIn and Telegram sharing.

= 1.0.57 =
* Reduced the space above single post titles to 10 pixels.

= 1.0.56 =
* Expanded single post introductory text to a consistent two-line summary from WordPress content.

= 1.0.55 =
* Reduced single post title sizing and kept title and deck text on one contained line at every viewport.

= 1.0.54 =
* Restored the primary sidebar on single post pages and tightened the related posts section.

= 1.0.53 =
* Rebuilt single post pages with a responsive editorial layout, dynamic author metadata, social sharing controls, WordPress tags, related posts, and a compact comment form.
* Added Turkish translations for the new single post interface.

= 1.0.52 =
* Extended the single post information bar to the card edges and removed its outline.

= 1.0.51 =
* Added a separated 50px information box below single post titles.

= 1.0.50 =
* Restored the previous single post title styling.
* Moved the post information row above the featured image.

= 1.0.49 =
* Increased and colorized the single post reading-time and comment information.
* Added a colorful visual treatment to single post title headers.

= 1.0.48 =
* Added a 4px inset and increased the single post featured-image corner radius.

= 1.0.47 =
* Restored full-width featured images on single post pages while retaining rounded corners.

= 1.0.46 =
* Moved the single post information row below the featured image.
* Added rounded corners to featured images on single post pages.

= 1.0.45 =
* Moved the author, date, reading-time, and comment row above the title on single post pages.

= 1.0.44 =
* Removed the category badge displayed above titles on single post pages.

= 1.0.43 =
* Moved header JavaScript into the WordPress script enqueue system without changing menu behavior.
* Compressed the 1200 x 900 theme screenshot for faster WordPress.org processing.
* Revalidated the package against the official WordPress Theme Check rules.

= 1.0.42 =
* Expanded the required English theme description and bundled documentation for WordPress.org.
* No admin pages, public information templates, external links, settings, or front-end components were added.

= 1.0.41 =
* Removed the optional Theme URI header to address WordPress.org theme review feedback.
* No theme layout, styling, functionality, or bundled assets were changed.

= 1.0.40 =
* Removed the custom Appearance > Novaflow Settings screen and moved every Novaflow presentation option into the native WordPress Customizer.
* Preserved the existing novaflow_options database array so saved settings continue to work without changing the public design.
* Added Customizer controls for the managed header advertisement image, URL, alternative text, new-tab behavior, and all social-profile URLs.
* Standardized the theme copyright and GPLv2-or-later license notice in readme.txt and LICENSE.txt.
* Rebuilt the theme screenshot with only original Novaflow abstract artwork and removed all third-party/unknown imagery and advertisement-looking preview content.
* Added explicit screenshot and bundled-asset copyright/license documentation.
* Removed the unused custom admin PHP, CSS, and JavaScript files.

= 1.0.39 =
* Corrected static-front-page and separate posts-page handling.
* Added complete mobile-menu focus containment, Escape handling, modal semantics, and focus restoration.
* Fixed long-title and unbroken-text wrapping throughout the theme.
* Hardened option validation, external URL protocols, attachment checks, nonces, capabilities, escaping, and accessible labels.
* Preserved the managed header advertisement, compact search, editorial homepage, light footer, admin settings screen, and existing visual design.
* Verified responsive layouts at 320, 360, 375, 390, 414, 480, 640, 768, 980, 1024, and 1366 pixel viewport widths.
* Optimized the 1200 × 900 PNG theme screenshot to reduce package size without changing its appearance.
* Refreshed translation, licensing, privacy, and resource documentation.

== Upgrade Notice ==

= 1.0.246 =
Actually removes bold typography across the public theme by normalizing source rules instead of relying on one low-specificity override.

= 1.0.198 =
Makes all public-facing theme typography visually thin and consistent at weight 400.

= 1.0.197 =
Restores one consistent hover lift and image-zoom effect across all reusable card boxes.

= 1.0.196 =
Keeps Anasayfa selected on the front page and boxes the Featured stories section.

= 1.0.195 =
Replaces the Shop menu square with a consistent storefront icon.

= 1.0.194 =
Desktop primary-menu items now fill the available navigation width evenly without squeezing labels.

= 1.0.193 =
Stops desktop menu labels from being squeezed; only complete menu items that fit in the navigation row are shown.

= 1.0.192 =
Prevents the desktop primary menu from extending beyond the right edge of the theme container.

= 1.0.191 =
Removes the desktop primary-menu overflow button and keeps all primary-menu items in the normal row.

= 1.0.190 =
Removes the header hamburger/mobile menu button and its reserved space.

= 1.0.100 =
Enlarges the 40px desktop primary navigation content proportionally without changing the rest of the header.

= 1.0.99 =
Expands the theme canvas to 1600px and migrates the former 1500px default on existing installations.

= 1.0.98 =
Removes theme-side social sharing, disables all optional outbound/profile and advertisement defaults, and updates the screenshot for WordPress.org compliance.

= 1.0.97 =
Removes theme-side statistics and tracking, keeps one compliant Narya author credit, and resolves the WordPress.org review recommendations while preserving the existing design.

= 1.0.80 =
Improves Newsroom Pulse text readability without changing the card structure.

= 1.0.79 =
Fills the homepage column with a responsive, fully dynamic WordPress content section.

= 1.0.78 =
Makes the six-row homepage story list more compact without resizing its content.

= 1.0.77 =
Centers homepage list summaries cleanly between their titles and metadata rows.

= 1.0.76 =
Adds a clean title-to-summary divider to the six-row homepage story list.

= 1.0.75 =
Keeps every homepage story-list row the same height regardless of featured-image proportions.

= 1.0.74 =
Restores six homepage story rows with working WordPress pagination directly below the list.

= 1.0.73 =
Makes Novaflow fully independent with its own code namespace, settings, hooks, block styles, and assets while preserving the design.

= 1.0.72 =
Removes duplicate homepage stories and completes the WordPress.org compatibility and resource audit.

= 1.0.71 =
Keeps long Latest Comments entries within a two-line layout.

= 1.0.70 =
Ensures updated post content is reflected in homepage summaries and article introductions.

= 1.0.69 =
Keeps horizontal-list descriptions within a consistent two-line area.

= 1.0.68 =
Keeps long homepage and trending titles within their intended line limits.

= 1.0.67 =
Prevents restored hover states from animating cards and images during page reload.

= 1.0.66 =
Prevents image flashing on reloaded single post pages.

= 1.0.65 =
Removes reload blanking and prevents animated browser scroll restoration.

= 1.0.64 =
Makes reload position restoration visually stable while the page layout settles.

= 1.0.63 =
Prevents visible position jumps when visitors reload a page.

= 1.0.62 =
Refines the comment submit button with a calmer and more compact appearance.

= 1.0.61 =
Improves the single post comment submit control.

= 1.0.60 =
Refines the single post author presentation.

= 1.0.59 =
Uses recognizable local brand artwork for single post sharing controls.

= 1.0.58 =
Improves and expands single post sharing controls.

= 1.0.57 =
Tightens the spacing above single post titles.

= 1.0.56 =
Improves the single post introductory summary layout.

= 1.0.55 =
Improves single post heading and introductory text fitting.

= 1.0.54 =
Restores the single post sidebar and improves related-post spacing.

= 1.0.53 =
Introduces the redesigned responsive single post experience while preserving existing theme settings and content hooks.

= 1.0.52 =
Improves alignment of the single post information bar.

= 1.0.51 =
Adds a compact post information box below single post titles.

= 1.0.50 =
Restores the previous title treatment and adjusts post information placement.

= 1.0.49 =
Enhances single post title and information styling.

= 1.0.48 =
Refines the single post featured-image spacing and corners.

= 1.0.47 =
Restores the original single post image width.

= 1.0.46 =
Refines single post image and metadata placement.

= 1.0.45 =
Refines the information hierarchy of single post headers.

= 1.0.44 =
Refines the single post header without changing archive or homepage category labels.

= 1.0.43 =
Improves WordPress.org validation and package size without changing the theme design.

= 1.0.42 =
Expands the WordPress.org theme description and documentation without changing the site design or behavior.

= 1.0.41 =
WordPress.org review update: removed the optional Theme URI header only; theme design and functionality are unchanged.

= 1.0.40 =
WordPress.org review compliance update: all theme settings now use the native Customizer, with clarified licensing and an original-artwork screenshot.
