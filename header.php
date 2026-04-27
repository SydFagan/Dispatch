<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php
        if ( is_single() ) {
            the_excerpt();
        } else {
            bloginfo( 'description' );
        }
    ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
    <?php esc_html_e( 'Skip to content', 'dispatch' ); ?>
</a>

<!-- ─── Navigation ──────────────────────────────────────────────────────────── -->
<header class="site-header" role="banner">
    <nav class="nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'dispatch' ); ?>">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — home">
            <?php
            if ( has_custom_logo() ) :
                the_custom_logo();
            else :
                echo esc_html( get_bloginfo( 'name' ) );
            endif;
            ?>
        </a>

        <!-- Desktop links -->
        <ul class="nav-links desktop-only" role="list">
            <?php
            $menu_items = wp_get_nav_menu_items( 'primary' );
            if ( $menu_items ) :
                foreach ( $menu_items as $item ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $item->url ); ?>"
                           <?php echo ( $item->url === get_permalink() ) ? 'aria-current="page"' : ''; ?>>
                            <?php echo esc_html( $item->title ); ?>
                        </a>
                    </li>
                <?php endforeach;
            else :
                // Fallback: output categories as nav items
                $categories = get_categories( [ 'number' => 5, 'orderby' => 'count', 'order' => 'DESC' ] );
                foreach ( $categories as $cat ) : ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </a>
                    </li>
                <?php endforeach;
            endif;
            ?>
        </ul>

        <!-- Desktop CTA -->
        <a href="<?php echo esc_url( home_url( '/subscribe' ) ); ?>" class="nav-cta desktop-only">
            <?php esc_html_e( 'Subscribe', 'dispatch' ); ?>
        </a>

        <!-- Mobile hamburger -->
        <button
            class="hamburger mobile-only"
            id="dispatch-hamburger"
            aria-label="<?php esc_attr_e( 'Open menu', 'dispatch' ); ?>"
            aria-expanded="false"
            aria-controls="dispatch-drawer"
        >
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>

    </nav><!-- .nav -->

    <!-- Mobile drawer -->
    <div class="drawer" id="dispatch-drawer" aria-hidden="true" role="dialog" aria-label="<?php esc_attr_e( 'Site menu', 'dispatch' ); ?>">
        <div class="drawer-inner">
            <nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'dispatch' ); ?>">
                <?php
                $categories = get_categories( [ 'number' => 6, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
                foreach ( $categories as $cat ) :
                    $color = dispatch_category_color( $cat->slug );
                    ?>
                    <a
                        href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                        class="drawer-nav-item"
                        style="--cat-color: <?php echo esc_attr( $color ); ?>"
                    >
                        <span class="drawer-nav-label" style="color: <?php echo esc_attr( $color ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </span>
                        <span class="drawer-nav-count">
                            <?php echo esc_html( $cat->count ); ?> <?php esc_html_e( 'stories', 'dispatch' ); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Popular tags -->
            <div class="drawer-tags">
                <?php
                $tags = get_tags( [ 'number' => 8, 'orderby' => 'count', 'order' => 'DESC' ] );
                foreach ( $tags as $tag ) : ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="drawer-tag">
                        <?php echo esc_html( $tag->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Page links: About + Contact -->
            <div class="drawer-page-links">
                <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="drawer-page-link">
                    <span><?php esc_html_e( 'About', 'dispatch' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
                </a>
                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="drawer-page-link">
                    <span><?php esc_html_e( 'Contact', 'dispatch' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
                </a>
            </div>

            <div class="drawer-footer">
                <a href="<?php echo esc_url( home_url( '/subscribe' ) ); ?>" class="drawer-subscribe">
                    <?php esc_html_e( 'Subscribe — free forever', 'dispatch' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="drawer-search">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <circle cx="7" cy="7" r="4.5"/>
                        <path d="M10.5 10.5l3 3"/>
                    </svg>
                    <?php esc_html_e( 'Search stories', 'dispatch' ); ?>
                </a>
            </div>
        </div><!-- .drawer-inner -->
    </div><!-- .drawer -->

    <!-- Reading progress bar (visible on single posts) -->
    <?php if ( is_single() ) : ?>
        <div class="progress-bar" id="dispatch-progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" aria-label="<?php esc_attr_e( 'Reading progress', 'dispatch' ); ?>"></div>
    <?php endif; ?>

</header><!-- .site-header -->

<main id="main-content" tabindex="-1">
