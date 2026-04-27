<?php
/**
 * Dispatch Theme — index.php
 * Homepage template. Renders the shell; JavaScript (dispatch.js)
 * fetches posts from the WordPress REST API and populates the cards.
 */
get_header();
?>

<!-- ─── Hero ──────────────────────────────────────────────────────────────────── -->
<section class="hero" aria-label="<?php esc_attr_e( 'Featured stories', 'dispatch' ); ?>">

    <!-- Hero left: featured post (largest) -->
    <div class="hero-left" id="hero-featured">
        <div class="hero-bg-text" aria-hidden="true">NOW</div>
        <div class="hero-tag"><?php esc_html_e( 'Featured story', 'dispatch' ); ?></div>
        <h1 class="hero-title" id="hero-title">
            <!-- Populated by JS from REST API -->
            <span class="skeleton" style="display:block;height:1em;width:85%;margin-bottom:.4em">&nbsp;</span>
            <span class="skeleton" style="display:block;height:1em;width:70%;margin-bottom:.4em">&nbsp;</span>
            <span class="skeleton" style="display:block;height:1em;width:55%">&nbsp;</span>
        </h1>
        <div class="hero-meta" id="hero-meta">
            <span class="skeleton" style="display:inline-block;height:.8em;width:160px">&nbsp;</span>
        </div>
        <a href="#" class="hero-read" id="hero-link" aria-label="<?php esc_attr_e( 'Read featured story', 'dispatch' ); ?>">
            <?php esc_html_e( 'Read the story', 'dispatch' ); ?>
        </a>
    </div>

    <!-- Hero right: two secondary featured posts -->
    <div class="hero-right" id="hero-secondary">
        <div class="hero-right-top" id="hero-story-2">
            <div class="story-tag" id="hero-tag-2">&nbsp;</div>
            <div class="story-title" id="hero-title-2">
                <span class="skeleton" style="display:block;height:.9em;width:90%;margin-bottom:.3em">&nbsp;</span>
                <span class="skeleton" style="display:block;height:.9em;width:70%">&nbsp;</span>
            </div>
        </div>
        <div class="hero-right-bot" id="hero-story-3">
            <div class="story-tag" id="hero-tag-3">&nbsp;</div>
            <div class="story-title" id="hero-title-3">
                <span class="skeleton" style="display:block;height:.9em;width:90%;margin-bottom:.3em">&nbsp;</span>
                <span class="skeleton" style="display:block;height:.9em;width:60%">&nbsp;</span>
            </div>
        </div>
    </div>

</section><!-- .hero -->


<!-- ─── Ticker ────────────────────────────────────────────────────────────────── -->
<div class="ticker" aria-hidden="true">
    <div class="ticker-inner" id="ticker-inner">
        <!-- Populated by JS with latest post titles -->
        <?php
        $ticker_posts = get_posts( [ 'numberposts' => 8, 'post_status' => 'publish' ] );
        foreach ( $ticker_posts as $post ) :
            $cat  = get_the_category( $post->ID );
            $name = $cat ? $cat[0]->name : get_bloginfo( 'name' );
            echo '<span>' . esc_html( $name ) . '</span>';
            echo '<span class="ticker-dot">×</span>';
            echo '<span>' . esc_html( get_the_title( $post ) ) . '</span>';
            echo '<span class="ticker-dot">×</span>';
        endforeach;
        // Duplicate for seamless loop
        foreach ( $ticker_posts as $post ) :
            $cat  = get_the_category( $post->ID );
            $name = $cat ? $cat[0]->name : get_bloginfo( 'name' );
            echo '<span>' . esc_html( $name ) . '</span>';
            echo '<span class="ticker-dot">×</span>';
            echo '<span>' . esc_html( get_the_title( $post ) ) . '</span>';
            echo '<span class="ticker-dot">×</span>';
        endforeach;
        ?>
    </div>
</div>


<!-- ─── Latest Stories Grid ───────────────────────────────────────────────────── -->
<div class="section-label">
    <span><?php esc_html_e( 'Latest stories', 'dispatch' ); ?></span>
</div>

<div class="articles-grid" id="articles-grid">
    <!-- JS populates this from REST API. PHP fallback below for no-JS / SEO. -->
    <noscript>
        <?php
        $latest = new WP_Query( [ 'posts_per_page' => 3, 'post_status' => 'publish' ] );
        if ( $latest->have_posts() ) :
            while ( $latest->have_posts() ) : $latest->the_post();
                $cats  = get_the_category();
                $cat   = $cats ? $cats[0] : null;
                $color = $cat ? dispatch_category_color( $cat->slug ) : '#c8f53a';
                ?>
                <article class="article-card">
                    <div class="card-color-bar" style="background:<?php echo esc_attr( $color ); ?>"></div>
                    <div class="card-tag" style="color:<?php echo esc_attr( $color ); ?>">
                        <?php echo $cat ? esc_html( $cat->name ) : ''; ?>
                    </div>
                    <h2 class="card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <p class="card-excerpt"><?php the_excerpt(); ?></p>
                    <div class="card-meta">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </noscript>
</div><!-- .articles-grid -->


<!-- ─── Big Feature Block ─────────────────────────────────────────────────────── -->
<?php
$featured_post = get_posts( [
    'numberposts'  => 1,
    'meta_key'     => '_dispatch_featured',
    'meta_value'   => '1',
    'post_status'  => 'publish',
] );
// Fallback: use the most recent post if no post is manually featured
if ( empty( $featured_post ) ) {
    $featured_post = get_posts( [ 'numberposts' => 1, 'offset' => 3 ] );
}
$big = ! empty( $featured_post ) ? $featured_post[0] : null;
?>
<?php if ( $big ) : ?>
    <?php
    $big_cats  = get_the_category( $big->ID );
    $big_cat   = $big_cats ? $big_cats[0]->name : 'Long read';
    $big_color = $big_cats ? dispatch_category_color( $big_cats[0]->slug ) : '#3af5e4';
    $word_count = str_word_count( wp_strip_all_tags( $big->post_content ) );
    $read_time  = max( 1, (int) ceil( $word_count / 200 ) );
    ?>
    <div class="big-feature">
        <div class="bf-left">
            <div>
                <div class="bf-eyebrow" style="color:<?php echo esc_attr( $big_color ); ?>">
                    <?php echo esc_html( $big_cat ); ?> &nbsp;·&nbsp; <?php esc_html_e( 'Long read', 'dispatch' ); ?>
                </div>
                <h2 class="bf-title" style="--bf-accent:<?php echo esc_attr( $big_color ); ?>">
                    <?php echo wp_kses_post( strtoupper( get_the_title( $big ) ) ); ?>
                </h2>
            </div>
            <div>
                <p class="bf-body"><?php echo esc_html( wp_trim_words( get_the_excerpt( $big ), 40 ) ); ?></p>
                <a href="<?php echo esc_url( get_permalink( $big ) ); ?>" class="hero-read" style="color:<?php echo esc_attr( $big_color ); ?>">
                    <?php esc_html_e( 'Read the investigation', 'dispatch' ); ?>
                </a>
            </div>
        </div>
        <div class="bf-right" style="background:<?php echo esc_attr( $big_color ); ?>">
            <div class="bf-right-bg" aria-hidden="true"><?php echo esc_html( $read_time ); ?></div>
            <div class="bf-right-label"><?php esc_html_e( 'Estimated read time', 'dispatch' ); ?></div>
            <div class="bf-right-stat"><?php echo esc_html( $read_time ); ?> <span style="font-size:1.8rem">min</span></div>
        </div>
    </div><!-- .big-feature -->
<?php endif; ?>


<!-- ─── Also Worth Your Time (horizontal scroll) ─────────────────────────────── -->
<div class="section-label">
    <span><?php esc_html_e( 'Also worth your time', 'dispatch' ); ?></span>
</div>

<div class="hscroll-wrap" id="hscroll" role="region" aria-label="<?php esc_attr_e( 'More stories', 'dispatch' ); ?>">
    <div class="hscroll-row" id="hscroll-row">
        <?php
        $scroll_posts = get_posts( [ 'numberposts' => 8, 'offset' => 4, 'post_status' => 'publish' ] );
        $i = 1;
        foreach ( $scroll_posts as $sp ) :
            $sp_cats  = get_the_category( $sp->ID );
            $sp_cat   = $sp_cats ? $sp_cats[0]->name : '';
            $sp_color = $sp_cats ? dispatch_category_color( $sp_cats[0]->slug ) : '#c8f53a';
            ?>
            <a href="<?php echo esc_url( get_permalink( $sp ) ); ?>" class="hscroll-card">
                <div class="hscroll-num" style="color:<?php echo esc_attr( $sp_color ); ?>">
                    <?php echo str_pad( $i, 2, '0', STR_PAD_LEFT ); ?>
                </div>
                <div class="hscroll-title"><?php echo esc_html( get_the_title( $sp ) ); ?></div>
                <div class="hscroll-tag"><?php echo esc_html( $sp_cat ); ?></div>
            </a>
            <?php
            $i++;
        endforeach;
        ?>
    </div>
</div><!-- .hscroll-wrap -->

<?php get_footer(); ?>
