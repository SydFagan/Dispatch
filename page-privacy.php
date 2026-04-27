<?php
/*
 * Template Name: Privacy
 *
 * HOW TO SET UP:
 * 1. Go to Pages → Add New
 * 2. Title it "Privacy Policy"
 * 3. Page Attributes → Template → select "Privacy"
 * 4. Publish
 */

get_header();

// Last updated date — change this whenever you update the policy
$last_updated = 'April 25, 2026';
?>

<div class="static-page privacy-page">

    <!-- ─── Hero ──────────────────────────────────────────────────────────── -->
    <div class="static-hero" style="--accent: var(--sky)">
        <div class="static-hero-inner">
            <div class="static-eyebrow"><?php esc_html_e( 'Legal', 'dispatch' ); ?></div>
            <h1 class="static-title"><?php the_title(); ?></h1>
            <p class="static-dek">
                <?php
                printf(
                    esc_html__( 'Last updated %s. We believe in plain language, so we\'ve written this to actually be read.', 'dispatch' ),
                    esc_html( $last_updated )
                );
                ?>
            </p>
        </div>
        <div class="static-hero-accent" aria-hidden="true">§</div>
    </div>

    <!-- ─── Quick summary ────────────────────────────────────────────────── -->
    <div class="privacy-summary reveal">
        <div class="static-section-label"><?php esc_html_e( 'The short version', 'dispatch' ); ?></div>
        <div class="privacy-summary-grid">
            <div class="privacy-pill privacy-pill--good">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8l4 4 6-6"/></svg>
                <?php esc_html_e( 'We don\'t sell your data', 'dispatch' ); ?>
            </div>
            <div class="privacy-pill privacy-pill--good">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8l4 4 6-6"/></svg>
                <?php esc_html_e( 'No third-party ad tracking', 'dispatch' ); ?>
            </div>
            <div class="privacy-pill privacy-pill--good">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8l4 4 6-6"/></svg>
                <?php esc_html_e( 'First-party analytics only', 'dispatch' ); ?>
            </div>
            <div class="privacy-pill privacy-pill--good">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8l4 4 6-6"/></svg>
                <?php esc_html_e( 'You can request deletion', 'dispatch' ); ?>
            </div>
        </div>
    </div>

    <!-- ─── Policy sections ───────────────────────────────────────────────── -->
    <div class="privacy-body">

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '1. Who we are', 'dispatch' ); ?></h2>
            <p><?php printf( esc_html__( 'Dispatch is an independent editorial website operated by Dispatch Media. Our site is located at %s. You can contact us at %s.', 'dispatch' ), '<strong>' . esc_html( home_url() ) . '</strong>', '<a href="mailto:privacy@dispatch.test">privacy@dispatch.test</a>' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '2. What data we collect', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'When you visit Dispatch, our server logs basic technical information: your IP address, the page you visited, the time of your visit, and the browser and device you used. This information is retained for up to 30 days for security and performance purposes and is never linked to a personal profile.', 'dispatch' ); ?></p>
            <p><?php esc_html_e( 'If you submit our contact form, we collect your name, email address, and the content of your message. This information is used solely to respond to your enquiry and is not added to any marketing list.', 'dispatch' ); ?></p>
            <p><?php esc_html_e( 'If you comment on an article, WordPress stores your name, email address, IP address, and the content of your comment. Your email address is never displayed publicly.', 'dispatch' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '3. Analytics', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'We use custom first-party analytics built into this site to understand how readers engage with our content. This system tracks scroll depth, time on page, and click patterns. It does not use cookies, does not fingerprint your device, and does not share data with any third party. No data collected by our analytics system ever leaves our server.', 'dispatch' ); ?></p>
            <p><?php esc_html_e( 'We do not use Google Analytics, Meta Pixel, or any other third-party tracking script.', 'dispatch' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '4. Cookies', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'Dispatch sets the minimum number of cookies required to function. WordPress sets a session cookie if you log in to the admin area. We do not set any advertising, tracking, or analytics cookies for regular readers.', 'dispatch' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '5. Third parties', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'We load fonts from Google Fonts, which means Google\'s servers may record your IP address when the font files are requested. This is standard practice for web fonts. We do not embed any other third-party scripts, iframes, or tracking pixels on this site.', 'dispatch' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '6. Your rights', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'You have the right to request a copy of any personal data we hold about you, to request that we correct inaccurate data, and to request deletion of your data. To make any of these requests, email us at privacy@dispatch.test. We will respond within 30 days.', 'dispatch' ); ?></p>
        </div>

        <div class="privacy-section reveal">
            <h2 class="privacy-section-title"><?php esc_html_e( '7. Changes to this policy', 'dispatch' ); ?></h2>
            <p><?php esc_html_e( 'If we make material changes to this policy, we will update the "Last updated" date at the top of this page. We will not notify you by email unless the change significantly affects how we handle your personal data.', 'dispatch' ); ?></p>
        </div>

        <!-- Page content from WP editor (appears after the policy sections if added) -->
        <?php if ( have_posts() ) : the_post(); ?>
            <?php if ( get_the_content() ) : ?>
                <div class="privacy-section reveal">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div><!-- .privacy-body -->

    <!-- ─── Questions CTA ─────────────────────────────────────────────────── -->
    <div class="static-cta reveal">
        <div class="static-cta-inner">
            <h2 class="static-cta-title"><?php esc_html_e( 'Questions about your data?', 'dispatch' ); ?></h2>
            <p class="static-cta-body">
                <?php esc_html_e( 'We\'re happy to answer questions about how we handle your information. Reach out and we\'ll get back to you within two business days.', 'dispatch' ); ?>
            </p>
            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="static-cta-btn">
                <?php esc_html_e( 'Contact us', 'dispatch' ); ?>
            </a>
        </div>
    </div>

</div><!-- .privacy-page -->

<?php get_footer(); ?>
