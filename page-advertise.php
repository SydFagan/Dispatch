<?php
/*
 * Template Name: Advertise
 *
 * HOW TO SET UP:
 * 1. Go to Pages → Add New
 * 2. Title it "Advertise"
 * 3. Page Attributes → Template → select "Advertise"
 * 4. Publish
 */

get_header();
?>

<div class="static-page advertise-page">

    <!-- ─── Hero ──────────────────────────────────────────────────────────── -->
    <div class="static-hero" style="--accent: var(--amber)">
        <div class="static-hero-inner">
            <div class="static-eyebrow"><?php esc_html_e( 'Work with us', 'dispatch' ); ?></div>
            <h1 class="static-title"><?php the_title(); ?></h1>
            <p class="static-dek">
                <?php esc_html_e( 'Reach an engaged audience of curious, independent-minded readers who seek out journalism that goes deeper than the headline.', 'dispatch' ); ?>
            </p>
        </div>
        <div class="static-hero-accent" aria-hidden="true">$</div>
    </div>

    <!-- ─── Audience stats ────────────────────────────────────────────────── -->
    <div class="advert-stats reveal">
        <div class="advert-stat">
            <div class="advert-stat-number">42K</div>
            <div class="advert-stat-label"><?php esc_html_e( 'Monthly readers', 'dispatch' ); ?></div>
        </div>
        <div class="advert-stat">
            <div class="advert-stat-number">6.4</div>
            <div class="advert-stat-label"><?php esc_html_e( 'Avg. minutes per visit', 'dispatch' ); ?></div>
        </div>
        <div class="advert-stat">
            <div class="advert-stat-number">71%</div>
            <div class="advert-stat-label"><?php esc_html_e( 'Return readers', 'dispatch' ); ?></div>
        </div>
        <div class="advert-stat">
            <div class="advert-stat-number">3.2K</div>
            <div class="advert-stat-label"><?php esc_html_e( 'Newsletter subscribers', 'dispatch' ); ?></div>
        </div>
    </div>

    <!-- ─── Why Dispatch ──────────────────────────────────────────────────── -->
    <div class="static-section reveal">
        <div class="static-section-label"><?php esc_html_e( 'Why Dispatch', 'dispatch' ); ?></div>
        <div class="static-section-body">
            <p><?php esc_html_e( 'Our readers are not passive consumers. They arrive via direct links shared in newsletters, reading groups, and work Slack channels. They are professionals, students, and engaged citizens who choose to spend time with longer-form journalism. They are not here by accident, and they are not easy to reach elsewhere.', 'dispatch' ); ?></p>
            <p><?php esc_html_e( 'Advertising on Dispatch means your brand sits alongside editorial content that people actually finish reading. We do not run pre-roll video, interstitials, or any format designed to interrupt. Every ad placement is agreed on in advance, clearly labelled, and contextually relevant to the section it appears in.', 'dispatch' ); ?></p>
        </div>
    </div>

    <!-- ─── Options ───────────────────────────────────────────────────────── -->
    <div class="static-section reveal">
        <div class="static-section-label"><?php esc_html_e( 'Ad formats', 'dispatch' ); ?></div>
        <div class="advert-options">

            <div class="advert-option">
                <div class="advert-option-tag" style="color:var(--lime)">
                    <?php esc_html_e( 'Most popular', 'dispatch' ); ?>
                </div>
                <h3 class="advert-option-title"><?php esc_html_e( 'Sponsored story', 'dispatch' ); ?></h3>
                <p class="advert-option-desc">
                    <?php esc_html_e( 'A full editorial-style article written by our team, clearly marked as sponsored. Sits in our regular article feed. Minimum 800 words.', 'dispatch' ); ?>
                </p>
                <div class="advert-option-meta"><?php esc_html_e( 'From $800 per placement', 'dispatch' ); ?></div>
            </div>

            <div class="advert-option">
                <div class="advert-option-tag" style="color:var(--sky)">
                    <?php esc_html_e( 'Newsletter', 'dispatch' ); ?>
                </div>
                <h3 class="advert-option-title"><?php esc_html_e( 'Newsletter mention', 'dispatch' ); ?></h3>
                <p class="advert-option-desc">
                    <?php esc_html_e( 'A short, honest mention in our weekly newsletter sent to 3,200 subscribers. Written in our voice, linked to your landing page.', 'dispatch' ); ?>
                </p>
                <div class="advert-option-meta"><?php esc_html_e( 'From $300 per issue', 'dispatch' ); ?></div>
            </div>

            <div class="advert-option">
                <div class="advert-option-tag" style="color:var(--coral)">
                    <?php esc_html_e( 'Display', 'dispatch' ); ?>
                </div>
                <h3 class="advert-option-title"><?php esc_html_e( 'Sidebar unit', 'dispatch' ); ?></h3>
                <p class="advert-option-desc">
                    <?php esc_html_e( 'A static image or text unit in the article sidebar. Fixed placement, no auction, no programmatic. Your creative, approved by us.', 'dispatch' ); ?>
                </p>
                <div class="advert-option-meta"><?php esc_html_e( 'From $150 / week', 'dispatch' ); ?></div>
            </div>

        </div>
    </div>

    <!-- ─── Page content from WP editor ──────────────────────────────────── -->
    <?php if ( have_posts() ) : the_post(); ?>
        <?php if ( get_the_content() ) : ?>
            <div class="static-section reveal">
                <div class="static-section-label"><?php esc_html_e( 'More detail', 'dispatch' ); ?></div>
                <div class="static-section-body"><?php the_content(); ?></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ─── CTA ───────────────────────────────────────────────────────────── -->
    <div class="static-cta reveal">
        <div class="static-cta-inner">
            <h2 class="static-cta-title">
                <?php esc_html_e( 'Ready to talk?', 'dispatch' ); ?>
            </h2>
            <p class="static-cta-body">
                <?php esc_html_e( 'We respond to all enquiries within two business days. Please include a brief description of your brand and which format interests you.', 'dispatch' ); ?>
            </p>
            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="static-cta-btn">
                <?php esc_html_e( 'Get in touch', 'dispatch' ); ?>
            </a>
        </div>
    </div>

</div><!-- .advertise-page -->

<?php get_footer(); ?>
