<?php
/**
 * Dispatch Theme — functions.php
 * Registers menus, enqueues assets, declares theme support,
 * and sets up REST API access for the headless frontend.
 */

// ─── Theme Support ────────────────────────────────────────────────────────────

function dispatch_setup() {
    // Let WordPress manage the document title tag
    add_theme_support( 'title-tag' );

    // Enable featured images on posts and pages
    add_theme_support( 'post-thumbnails' );

    // HTML5 markup for core features
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style',
    ] );

    // Register navigation menus
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'dispatch' ),
        'footer'  => __( 'Footer Navigation', 'dispatch' ),
    ] );

    // Feed links in <head>
    add_theme_support( 'automatic-feed-links' );

    // Widescreen and full-width image alignment
    add_theme_support( 'align-wide' );

    // Custom logo
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
}
add_action( 'after_setup_theme', 'dispatch_setup' );


// ─── Enqueue Styles & Scripts ─────────────────────────────────────────────────

function dispatch_enqueue_assets() {
    $ver = wp_get_theme()->get( 'Version' );

    // Google Fonts — Bebas Neue (display), DM Serif Display (headlines), DM Sans (body)
    wp_enqueue_style(
        'dispatch-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'dispatch-main',
        get_template_directory_uri() . '/css/main.css',
        [ 'dispatch-fonts' ],
        $ver
    );

    // Main JS — loaded in footer, deferred
    wp_enqueue_script(
        'dispatch-main',
        get_template_directory_uri() . '/js/dispatch.js',
        [],
        $ver,
        true // load in footer
    );

    // Pass WordPress data to JavaScript so dispatch.js can use it
    wp_localize_script( 'dispatch-main', 'dispatchData', [
        'restUrl'     => esc_url_raw( rest_url( 'wp/v2/' ) ),
        'homeUrl'     => esc_url( home_url( '/' ) ),
        'nonce'       => wp_create_nonce( 'wp_rest' ),
        'isHome'      => is_front_page() ? 'true' : 'false',
        'isSingle'    => is_single() ? 'true' : 'false',
        'postId'      => is_single() ? get_the_ID() : 0,
        'siteName'    => get_bloginfo( 'name' ),
        'themeUri'    => get_template_directory_uri(),
    ] );

    // Comments script on singular posts that have comments open
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'dispatch_enqueue_assets' );


// ─── REST API: Allow cross-origin requests (for headless/decoupled setup) ─────

function dispatch_rest_cors_headers() {
    // In production, replace * with your actual frontend domain, e.g.:
    // header( 'Access-Control-Allow-Origin: https://yourfrontenddomain.com' );
    header( 'Access-Control-Allow-Origin: *' );
    header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
    header( 'Access-Control-Allow-Headers: X-WP-Nonce, Content-Type, Authorization' );
}
add_action( 'rest_api_init', function() {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', function( $value ) {
        dispatch_rest_cors_headers();
        return $value;
    });
}, 15 );


// ─── REST API: Expose extra fields on posts ────────────────────────────────────

// Add category name directly to post REST response so JS doesn't need a second request
function dispatch_register_rest_fields() {
    register_rest_field( 'post', 'dispatch_category', [
        'get_callback' => function( $post ) {
            $cats = get_the_category( $post['id'] );
            if ( empty( $cats ) ) return 'Uncategorized';
            return $cats[0]->name;
        },
        'schema' => [
            'description' => 'Primary category name',
            'type'        => 'string',
        ],
    ] );

    register_rest_field( 'post', 'dispatch_read_time', [
        'get_callback' => function( $post ) {
            $word_count = str_word_count( wp_strip_all_tags( $post['content']['rendered'] ) );
            return max( 1, (int) ceil( $word_count / 200 ) ); // ~200 wpm
        },
        'schema' => [
            'description' => 'Estimated read time in minutes',
            'type'        => 'integer',
        ],
    ] );
}
add_action( 'rest_api_init', 'dispatch_register_rest_fields' );


// ─── Custom Excerpt Length ────────────────────────────────────────────────────

function dispatch_excerpt_length() {
    return 22; // words
}
add_filter( 'excerpt_length', 'dispatch_excerpt_length' );

function dispatch_excerpt_more( $more ) {
    return '…';
}
add_filter( 'excerpt_more', 'dispatch_excerpt_more' );


// ─── Body Classes ─────────────────────────────────────────────────────────────

function dispatch_body_classes( $classes ) {
    if ( is_singular() ) $classes[] = 'dispatch-single';
    if ( is_front_page() ) $classes[] = 'dispatch-home';
    if ( is_archive() ) $classes[] = 'dispatch-archive';
    return $classes;
}
add_filter( 'body_class', 'dispatch_body_classes' );


// ─── Category Color Map ───────────────────────────────────────────────────────
// Returns a CSS custom property value for a given category slug.
// Used in PHP templates; JS has its own copy in dispatch.js.

function dispatch_category_color( $category_slug ) {
    $colors = [
        'culture'    => '#ff4d2e',
        'power'      => '#8b2be2',
        'the-future' => '#3af5e4',
        'very-online' => '#f5a623',
        'long-reads' => '#ff3d8a',
    ];
    return $colors[ strtolower( $category_slug ) ] ?? '#c8f53a';
}


// ─── Contact Form Handler ─────────────────────────────────────────────────────

require_once get_template_directory() . '/inc/contact-form.php';


// ─── Enqueue Contact Page CSS ─────────────────────────────────────────────────

function dispatch_enqueue_contact_styles() {
    if ( is_page_template( 'page-contact.php' ) ) {
        wp_enqueue_style(
            'dispatch-contact',
            get_template_directory_uri() . '/css/contact.css',
            [ 'dispatch-main' ],
            wp_get_theme()->get( 'Version' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'dispatch_enqueue_contact_styles' );


// ─── Customizer: Site Tagline & Accent Color ──────────────────────────────────

function dispatch_customizer( $wp_customize ) {
    $wp_customize->add_section( 'dispatch_options', [
        'title'    => __( 'Dispatch Theme Options', 'dispatch' ),
        'priority' => 30,
    ] );

    $wp_customize->add_setting( 'dispatch_accent_color', [
        'default'           => '#c8f53a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dispatch_accent_color', [
        'label'   => __( 'Accent Color (lime)', 'dispatch' ),
        'section' => 'dispatch_options',
    ] ) );
}
add_action( 'customize_register', 'dispatch_customizer' );

// Output customizer CSS inline
function dispatch_customizer_css() {
    $accent = get_theme_mod( 'dispatch_accent_color', '#c8f53a' );
    echo '<style>:root { --lime: ' . esc_attr( $accent ) . '; }</style>';
}
add_action( 'wp_head', 'dispatch_customizer_css' );
