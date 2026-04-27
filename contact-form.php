<?php
/**
 * Dispatch Theme — inc/contact-form.php
 * Handles the contact form POST submitted from page-contact.php.
 * Hooked via functions.php: require_once get_template_directory() . '/inc/contact-form.php';
 *
 * Flow:
 *   JS (dispatch.js) submits to admin-post.php with action=dispatch_contact
 *   → dispatch_handle_contact() runs, validates, sends email, returns JSON
 */

// Handle the AJAX / admin-post form submission
add_action( 'admin_post_nopriv_dispatch_contact', 'dispatch_handle_contact' );
add_action( 'admin_post_dispatch_contact',        'dispatch_handle_contact' );

function dispatch_handle_contact() {
    // 1. Verify nonce
    if ( ! isset( $_POST['dispatch_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dispatch_nonce'] ) ), 'dispatch_contact_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed. Please refresh and try again.' ] );
    }

    // 2. Honeypot — silently succeed to fool bots
    if ( ! empty( $_POST['dispatch_hp'] ) ) {
        wp_send_json_success( [ 'message' => 'Message sent.' ] );
    }

    // 3. Sanitise inputs
    $name    = sanitize_text_field( wp_unslash( $_POST['contact_name']  ?? '' ) );
    $email   = sanitize_email(      wp_unslash( $_POST['contact_email'] ?? '' ) );
    $org     = sanitize_text_field( wp_unslash( $_POST['contact_org']   ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject']       ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message']   ?? '' ) );

    // 4. Validate required fields
    $errors = [];
    if ( empty( $name ) )              $errors[] = 'Name is required.';
    if ( ! is_email( $email ) )        $errors[] = 'A valid email address is required.';
    if ( empty( $message ) )           $errors[] = 'Message is required.';
    if ( strlen( $message ) < 10 )     $errors[] = 'Message is too short.';

    if ( ! empty( $errors ) ) {
        wp_send_json_error( [ 'message' => implode( ' ', $errors ) ] );
    }

    // 5. Build and send the email
    $to         = get_option( 'admin_email' );
    $site_name  = get_bloginfo( 'name' );

    $subject_labels = [
        'pitch'       => 'Story pitch',
        'tip'         => 'News tip',
        'correction'  => 'Correction',
        'advertising' => 'Advertising',
        'general'     => 'General enquiry',
    ];
    $subject_label = $subject_labels[ $subject ] ?? 'General enquiry';

    $email_subject = "[{$site_name}] {$subject_label} from {$name}";

    $email_body  = "You have a new message from the {$site_name} contact form.\n\n";
    $email_body .= "Name:         {$name}\n";
    $email_body .= "Email:        {$email}\n";
    if ( $org ) $email_body .= "Organisation: {$org}\n";
    $email_body .= "Subject:      {$subject_label}\n";
    $email_body .= "\n--- Message ---\n\n{$message}\n";
    $email_body .= "\n--- / ---\n";
    $email_body .= "\nSent from: " . home_url( '/contact' );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: {$name} <{$email}>",
    ];

    $sent = wp_mail( $to, $email_subject, $email_body, $headers );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'Message sent. We\'ll be in touch.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'There was a problem sending your message. Please email us directly.' ] );
    }
}
