</main><!-- #main-content -->

<!-- ─── Footer ────────────────────────────────────────────────────────────────── -->
<footer class="site-footer" role="contentinfo">
    <div class="footer">

        <!-- Brand column -->
        <div class="footer-brand-col">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php bloginfo( 'name' ); ?> — home">
                <?php bloginfo( 'name' ); ?>
            </a>
            <p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
        </div>

        <!-- Sections column -->
        <div class="footer-col">
            <h3><?php esc_html_e( 'Sections', 'dispatch' ); ?></h3>
            <ul>
                <?php
                $footer_cats = get_categories( [ 'number' => 6, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ] );
                foreach ( $footer_cats as $cat ) : ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- About column -->
        <div class="footer-col">
            <h3><?php esc_html_e( 'About', 'dispatch' ); ?></h3>
            <ul>
                <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About us', 'dispatch' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/masthead' ) ); ?>"><?php esc_html_e( 'Masthead', 'dispatch' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'dispatch' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/advertise' ) ); ?>"><?php esc_html_e( 'Advertise', 'dispatch' ); ?></a></li>
                <li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy', 'dispatch' ); ?></a></li>
            </ul>
        </div>

    </div><!-- .footer -->

    <div class="footer-bottom">
        <span>
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>
        </span>
        <span>
            <?php
            printf(
                /* translators: %s: WordPress link */
                esc_html__( 'Built on %s · Dispatch theme', 'dispatch' ),
                '<a href="https://wordpress.org" target="_blank" rel="noopener noreferrer">WordPress</a>'
            );
            ?>
        </span>
    </div><!-- .footer-bottom -->

</footer><!-- .site-footer -->

<?php wp_footer(); ?>
</body>
</html>
