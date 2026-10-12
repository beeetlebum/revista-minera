<?php
/**
 * Template Name: Contact Page
 * Description: Universal Contact Page template with direct communication channels, editorial desk guidelines, and message form.
 *
 * @package NewSum
 */

get_header();

// Customizer Settings
$show_email      = get_theme_mod( 'newsum_contact_show_email', true );
$email           = get_theme_mod( 'newsum_contact_email', get_option( 'admin_email' ) );
$show_social     = get_theme_mod( 'newsum_contact_show_social', true );
$social_ig       = get_theme_mod( 'newsum_contact_social_ig', '#' );
$social_tw       = get_theme_mod( 'newsum_contact_social_tw', '#' );
$social_pi       = get_theme_mod( 'newsum_contact_social_pi', '#' );

$form_shortcode  = get_theme_mod( 'newsum_contact_form_shortcode', '' );

$show_standards  = get_theme_mod( 'newsum_contact_show_standards', true );
$standards_title = get_theme_mod( 'newsum_contact_standards_title', __( '24–48 Hour Review', 'newsum' ) );
$standards_text  = get_theme_mod( 'newsum_contact_standards_text', __( 'Our editorial board reviews submissions on rolling daily cycles. Time-sensitive breaking tips receive immediate triage.', 'newsum' ) );

$show_address    = get_theme_mod( 'newsum_contact_show_address', true );
$address_title   = get_theme_mod( 'newsum_contact_address_title', sprintf( __( '%s Editorial Office', 'newsum' ), get_bloginfo( 'name' ) ) );
$address_text    = get_theme_mod( 'newsum_contact_address_text', '' );

$page_title = get_the_title();
if ( empty( $page_title ) || ! is_page() ) {
    $page_title = __( 'Contact Our Newsroom', 'newsum' );
}
?>

<main class="site-content site-main page-contact-content" id="primary">

    <!-- CONTACT HERO -->
    <header class="category-header contact-header">
        <div class="category-header-inner">
            <span class="browsing-label"><?php esc_html_e( 'GET IN TOUCH', 'newsum' ); ?></span>
            <h1 class="category-title"><?php echo esc_html( $page_title ); ?></h1>
            <p class="category-desc">
                <?php
                if ( have_posts() && has_excerpt() ) {
                    echo esc_html( get_the_excerpt() );
                } else {
                    esc_html_e( 'Have a breaking story, news tip, editorial inquiry, or sponsorship proposal? Our team is always listening.', 'newsum' );
                }
                ?>
            </p>
        </div>
    </header>

    <!-- CONTACT TWO-COLUMN WRAPPER -->
    <div class="contact-page-layout">
        
        <!-- LEFT: DIRECT CHANNELS & DESK INFO -->
        <aside class="contact-channels-col">
            
            <?php if ( $show_email && ! empty( $email ) ) : ?>
                <div class="contact-info-card email-card">
                    <div class="card-icon-pill">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <div class="card-text-wrap">
                        <span class="channel-label"><?php esc_html_e( 'Direct Email', 'newsum' ); ?></span>
                        <a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>" class="channel-main-link"><?php echo esc_html( antispambot( $email ) ); ?></a>
                        <p class="channel-subtext"><?php esc_html_e( 'For editorial pitches, corrections, and general correspondence.', 'newsum' ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $show_social ) : ?>
                <div class="contact-info-card social-card">
                    <div class="card-icon-pill">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                    </div>
                    <div class="card-text-wrap">
                        <span class="channel-label"><?php esc_html_e( 'Social Desks', 'newsum' ); ?></span>
                        <div class="contact-social-pills">
                            <?php if ( ! empty( $social_tw ) && '#' !== $social_tw ) : ?>
                                <a href="<?php echo esc_url( $social_tw ); ?>" target="_blank" rel="noopener noreferrer" class="contact-pill-link">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                                    <span>Twitter / X</span>
                                </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $social_ig ) && '#' !== $social_ig ) : ?>
                                <a href="<?php echo esc_url( $social_ig ); ?>" target="_blank" rel="noopener noreferrer" class="contact-pill-link">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    <span>Instagram</span>
                                </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $social_pi ) && '#' !== $social_pi ) : ?>
                                <a href="<?php echo esc_url( $social_pi ); ?>" target="_blank" rel="noopener noreferrer" class="contact-pill-link">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0a12 12 0 0 0-4.37 23.18c-.05-.98-.1-2.48.02-3.55l.72-3.05s-.18-.37-.18-.92c0-.86.5-1.5 1.13-1.5.53 0 .79.4.79.88 0 .53-.34 1.34-.52 2.08-.15.62.31 1.13.92 1.13 1.1 0 1.95-1.16 1.95-2.84 0-1.48-1.07-2.52-2.59-2.52-1.77 0-2.8 1.32-2.8 2.68 0 .53.2.1.47 1.42.05.24.06.45-.02.7-.08.28-.27 1.1-.31 1.25-.05.2-.18.25-.42.15-1.57-.73-2.55-3.02-2.55-4.86 0-3.95 2.87-7.58 8.28-7.58 4.35 0 7.73 3.1 7.73 7.24 0 4.32-2.72 7.8-6.5 7.8-1.27 0-2.46-.66-2.87-1.44l-.78 2.98c-.28 1.09-1.05 2.45-1.56 3.29A12 12 0 1 0 12 0z"/></svg>
                                    <span>Pinterest</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- DESK STANDARDS & RESPONSE TIME -->
            <?php if ( $show_standards ) : ?>
                <div class="contact-info-card desk-card">
                    <div class="card-icon-pill">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="card-text-wrap">
                        <span class="channel-label"><?php esc_html_e( 'Response Standards', 'newsum' ); ?></span>
                        <h4 class="desk-title"><?php echo esc_html( $standards_title ); ?></h4>
                        <p class="channel-subtext"><?php echo esc_html( $standards_text ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- LOCATION / BUREAU -->
            <?php if ( $show_address && ! empty( $address_text ) ) : ?>
                <div class="contact-info-card bureau-card">
                    <div class="card-icon-pill">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div class="card-text-wrap">
                        <span class="channel-label"><?php esc_html_e( 'Editorial Headquarters', 'newsum' ); ?></span>
                        <h4 class="desk-title"><?php echo esc_html( $address_title ); ?></h4>
                        <p class="channel-subtext"><?php echo nl2br( esc_html( $address_text ) ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

        </aside>

        <!-- RIGHT: EDITORIAL MESSAGE FORM -->
        <div class="contact-form-col">
            <div class="contact-form-wrapper">
                
                <?php
                if ( ! empty( $form_shortcode ) ) :
                    ?>
                    <div class="contact-shortcode-wrap">
                        <?php echo do_shortcode( wp_kses_post( $form_shortcode ) ); ?>
                    </div>
                    <?php
                else :
                    $has_custom_content = false;
                    if ( have_posts() ) {
                        while ( have_posts() ) {
                            the_post();
                            $content = trim( get_the_content() );
                            if ( ! empty( $content ) ) {
                                $has_custom_content = true;
                                ?>
                                <div class="contact-custom-content">
                                    <?php the_content(); ?>
                                </div>
                                <?php
                            }
                        }
                    }

                    if ( ! $has_custom_content ) :
                        ?>
                        <!-- BUILT-IN UNIVERSAL CONTACT FORM -->
                        <div class="form-header-deck">
                            <span class="form-badge"><?php esc_html_e( 'DIRECT DISPATCH', 'newsum' ); ?></span>
                            <h3 class="form-heading"><?php esc_html_e( 'Send Us a Message', 'newsum' ); ?></h3>
                            <p class="form-subheading"><?php esc_html_e( 'Please provide as much context as possible so we can route your message to the appropriate desk.', 'newsum' ); ?></p>
                        </div>

                        <form class="contact-dispatch-form" id="contactDispatchForm" method="post" action="#">
                            <div class="form-row-grid">
                                <div class="form-field-wrap">
                                    <label for="contactName"><?php esc_html_e( 'Your Name', 'newsum' ); ?> <span class="required">*</span></label>
                                    <input type="text" id="contactName" name="name" required placeholder="<?php esc_attr_e( 'Jane Doe', 'newsum' ); ?>">
                                </div>
                                <div class="form-field-wrap">
                                    <label for="contactEmail"><?php esc_html_e( 'Email Address', 'newsum' ); ?> <span class="required">*</span></label>
                                    <input type="email" id="contactEmail" name="email" required placeholder="<?php esc_attr_e( 'jane@example.com', 'newsum' ); ?>">
                                </div>
                            </div>

                            <div class="form-field-wrap">
                                <label for="contactDepartment"><?php esc_html_e( 'Inquiry Type', 'newsum' ); ?></label>
                                <select id="contactDepartment" name="department">
                                    <option value="editorial"><?php esc_html_e( 'Editorial Pitch / Story Submission', 'newsum' ); ?></option>
                                    <option value="tip"><?php esc_html_e( 'Confidential News Tip', 'newsum' ); ?></option>
                                    <option value="partnership"><?php esc_html_e( 'Advertising & Partnerships', 'newsum' ); ?></option>
                                    <option value="correction"><?php esc_html_e( 'Correction Request', 'newsum' ); ?></option>
                                    <option value="general"><?php esc_html_e( 'General Inquiry', 'newsum' ); ?></option>
                                </select>
                            </div>

                            <div class="form-field-wrap">
                                <label for="contactSubject"><?php esc_html_e( 'Subject', 'newsum' ); ?> <span class="required">*</span></label>
                                <input type="text" id="contactSubject" name="subject" required placeholder="<?php esc_attr_e( 'Brief summary of your inquiry...', 'newsum' ); ?>">
                            </div>

                            <div class="form-field-wrap">
                                <label for="contactMessage"><?php esc_html_e( 'Message Details', 'newsum' ); ?> <span class="required">*</span></label>
                                <textarea id="contactMessage" name="message" rows="6" required placeholder="<?php esc_attr_e( 'Include all relevant background, links, and documents...', 'newsum' ); ?>"></textarea>
                            </div>

                            <button type="submit" class="contact-submit-btn">
                                <span><?php esc_html_e( 'Send Message', 'newsum' ); ?></span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </form>
                        <?php
                    endif;
                endif;
                ?>

            </div>
        </div>

    </div>

</main>

<?php
get_footer();
