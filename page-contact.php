<?php
/**
 * Dispatch Theme — page-contact.php
 * Template Name: Contact
 *
 * WordPress uses this file automatically for any Page that has
 * "Contact" selected as its Template (Page Attributes → Template).
 *
 * To set up:
 *   1. Go to Pages → Add New in WP Admin
 *   2. Title it "Contact"
 *   3. In Page Attributes (right sidebar), set Template → "Contact"
 *   4. Publish — WordPress will use this file automatically
 */
get_header();
?>

<div class="contact-page">

    <!-- ─── Page Hero ──────────────────────────────────────────────────────── -->
    <div class="contact-hero">
        <div class="contact-hero-inner">
            <div class="contact-eyebrow"><?php esc_html_e( 'Get in touch', 'dispatch' ); ?></div>
            <h1 class="contact-title">
                <?php the_title(); ?>
            </h1>
            <?php if ( has_excerpt() ) : ?>
                <p class="contact-dek"><?php the_excerpt(); ?></p>
            <?php else : ?>
                <p class="contact-dek">
                    <?php esc_html_e( 'Tips, pitches, corrections, complaints, and compliments — we read everything, and we reply to most of it.', 'dispatch' ); ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="contact-hero-accent" aria-hidden="true">✉</div>
    </div>

    <!-- ─── Content + Form Grid ────────────────────────────────────────────── -->
    <div class="contact-grid">

        <!-- Left: page content (editable in WP Admin) + contact info -->
        <div class="contact-info">

            <?php if ( have_posts() ) : the_post(); ?>
                <?php if ( get_the_content() ) : ?>
                    <div class="contact-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="contact-details">
                <div class="contact-detail-item">
                    <div class="contact-detail-label"><?php esc_html_e( 'Editorial', 'dispatch' ); ?></div>
                    <a href="mailto:editor@dispatch.test" class="contact-detail-value">editor@dispatch.test</a>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-detail-label"><?php esc_html_e( 'Pitches', 'dispatch' ); ?></div>
                    <a href="mailto:pitches@dispatch.test" class="contact-detail-value">pitches@dispatch.test</a>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-detail-label"><?php esc_html_e( 'Tips', 'dispatch' ); ?></div>
                    <a href="mailto:tips@dispatch.test" class="contact-detail-value">tips@dispatch.test</a>
                </div>
                <div class="contact-detail-item">
                    <div class="contact-detail-label"><?php esc_html_e( 'Response time', 'dispatch' ); ?></div>
                    <div class="contact-detail-value"><?php esc_html_e( 'Usually within 2–3 business days', 'dispatch' ); ?></div>
                </div>
            </div>

        </div><!-- .contact-info -->

        <!-- Right: the contact form -->
        <div class="contact-form-wrap">
            <div class="contact-form-header">
                <div class="contact-form-label"><?php esc_html_e( 'Send a message', 'dispatch' ); ?></div>
            </div>

            <!-- Status message (shown/hidden by dispatch.js) -->
            <div id="dispatch-form-status" class="form-status" role="alert" aria-live="polite" style="display:none"></div>

            <form
                id="dispatch-contact-form"
                class="dispatch-form"
                action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                method="POST"
                novalidate
            >
                <?php wp_nonce_field( 'dispatch_contact_nonce', 'dispatch_nonce' ); ?>
                <input type="hidden" name="action" value="dispatch_contact">

                <!-- Honeypot (hidden from real users, traps bots) -->
                <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none" aria-hidden="true">
                    <label for="dispatch_hp">Leave this blank</label>
                    <input type="text" id="dispatch_hp" name="dispatch_hp" tabindex="-1" autocomplete="off">
                </div>

                <!-- Subject selector -->
                <div class="form-group">
                    <label class="form-label" for="contact-subject"><?php esc_html_e( 'I\'m writing about', 'dispatch' ); ?></label>
                    <div class="form-select-wrap">
                        <select id="contact-subject" name="subject" class="form-select" required>
                            <option value="" disabled selected><?php esc_html_e( 'Select a topic…', 'dispatch' ); ?></option>
                            <option value="pitch"><?php esc_html_e( 'Story pitch', 'dispatch' ); ?></option>
                            <option value="tip"><?php esc_html_e( 'News tip', 'dispatch' ); ?></option>
                            <option value="correction"><?php esc_html_e( 'Correction', 'dispatch' ); ?></option>
                            <option value="advertising"><?php esc_html_e( 'Advertising', 'dispatch' ); ?></option>
                            <option value="general"><?php esc_html_e( 'General enquiry', 'dispatch' ); ?></option>
                        </select>
                        <svg class="select-arrow" viewBox="0 0 12 8" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M1 1l5 5 5-5"/>
                        </svg>
                    </div>
                </div>

                <!-- Two-column row: name + email -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="contact-name"><?php esc_html_e( 'Name', 'dispatch' ); ?></label>
                        <input
                            type="text"
                            id="contact-name"
                            name="contact_name"
                            class="form-input"
                            placeholder="<?php esc_attr_e( 'Your name', 'dispatch' ); ?>"
                            required
                            autocomplete="name"
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact-email"><?php esc_html_e( 'Email', 'dispatch' ); ?></label>
                        <input
                            type="email"
                            id="contact-email"
                            name="contact_email"
                            class="form-input"
                            placeholder="<?php esc_attr_e( 'you@example.com', 'dispatch' ); ?>"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <!-- Organisation (optional) -->
                <div class="form-group">
                    <label class="form-label" for="contact-org">
                        <?php esc_html_e( 'Organisation', 'dispatch' ); ?>
                        <span class="form-optional"><?php esc_html_e( 'optional', 'dispatch' ); ?></span>
                    </label>
                    <input
                        type="text"
                        id="contact-org"
                        name="contact_org"
                        class="form-input"
                        placeholder="<?php esc_attr_e( 'Publication, company, etc.', 'dispatch' ); ?>"
                        autocomplete="organization"
                    >
                </div>

                <!-- Message -->
                <div class="form-group">
                    <label class="form-label" for="contact-message"><?php esc_html_e( 'Message', 'dispatch' ); ?></label>
                    <textarea
                        id="contact-message"
                        name="message"
                        class="form-textarea"
                        rows="6"
                        placeholder="<?php esc_attr_e( 'Tell us what\'s on your mind…', 'dispatch' ); ?>"
                        required
                    ></textarea>
                </div>

                <button type="submit" class="form-submit">
                    <?php esc_html_e( 'Send message', 'dispatch' ); ?>
                </button>

            </form>
        </div><!-- .contact-form-wrap -->

    </div><!-- .contact-grid -->

</div><!-- .contact-page -->

<?php get_footer(); ?>
