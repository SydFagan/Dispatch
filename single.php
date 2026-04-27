<?php
/**
 * Dispatch Theme — single.php
 * Single article template with reading progress bar,
 * table of contents, pull quotes, and related posts.
 */
get_header();

// Bail gracefully if no post
if ( ! have_posts() ) {
    echo '<p class="no-posts">' . esc_html__( 'No post found.', 'dispatch' ) . '</p>';
    get_footer();
    exit;
}

the_post();

$cats      = get_the_category();
$cat       = $cats ? $cats[0] : null;
$cat_name  = $cat ? $cat->name : __( 'Uncategorized', 'dispatch' );
$cat_link  = $cat ? get_category_link( $cat->term_id ) : home_url( '/' );
$cat_color = $cat ? dispatch_category_color( $cat->slug ) : '#3af5e4';

$word_count = str_word_count( wp_strip_all_tags( get_the_content() ) );
$read_time  = max( 1, (int) ceil( $word_count / 200 ) );

$author_id    = get_the_author_meta( 'ID' );
$author_name  = get_the_author();
$author_initials = implode( '', array_map( fn($w) => strtoupper( $w[0] ), explode( ' ', trim( $author_name ) ) ) );
$author_url   = get_author_posts_url( $author_id );

// Hero image
$hero_img     = get_the_post_thumbnail_url( null, 'full' );
$hero_img_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
?>

<!-- ─── Article Hero ──────────────────────────────────────────────────────────── -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'dispatch-article' ); ?>>

    <header class="art-hero">

        <!-- Left: headline block -->
        <div class="art-hero-left">
            <div class="art-category">
                <a href="<?php echo esc_url( $cat_link ); ?>" style="color:<?php echo esc_attr( $cat_color ); ?>">
                    <?php echo esc_html( $cat_name ); ?>
                </a>
                &nbsp;·&nbsp; <?php echo esc_html( $read_time ); ?> <?php esc_html_e( 'min read', 'dispatch' ); ?>
            </div>

            <h1 class="art-title"><?php the_title(); ?></h1>

            <?php if ( has_excerpt() ) : ?>
                <p class="art-dek"><?php the_excerpt(); ?></p>
            <?php endif; ?>

            <div class="art-byline">
                <a href="<?php echo esc_url( $author_url ); ?>" class="byline-avatar" aria-label="<?php echo esc_attr( $author_name ); ?>">
                    <?php
                    $avatar = get_avatar( $author_id, 36 );
                    if ( $avatar ) {
                        echo $avatar;
                    } else {
                        echo esc_html( substr( $author_initials, 0, 2 ) );
                    }
                    ?>
                </a>
                <div>
                    <div class="byline-name">
                        <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
                    </div>
                    <div class="byline-date">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: featured image or colored fallback -->
        <div class="art-hero-right" style="<?php echo $hero_img ? 'background-image:url(' . esc_url( $hero_img ) . ');background-size:cover;background-position:center' : 'background:' . esc_attr( $cat_color ); ?>">
            <?php if ( ! $hero_img ) : ?>
                <div class="art-hero-img-placeholder" aria-hidden="true">
                    <?php echo esc_html( strtoupper( substr( $cat_name, 0, 2 ) ) ); ?>
                </div>
            <?php endif; ?>
            <div class="art-hero-tag" style="background:<?php echo esc_attr( $cat_color ); ?>;color:#0a0a0a">
                <?php echo esc_html( $cat_name ); ?>
            </div>
        </div>

    </header><!-- .art-hero -->


    <!-- ─── Article Body + Sidebars ─────────────────────────────────────────── -->
    <div class="art-body-wrap">

        <!-- Left sidebar: table of contents (built by JS) -->
        <aside class="art-sidebar-left" aria-label="<?php esc_attr_e( 'Table of contents', 'dispatch' ); ?>">
            <div class="sidebar-label"><?php esc_html_e( 'Contents', 'dispatch' ); ?></div>
            <ul class="toc-list" id="dispatch-toc" aria-live="polite">
                <!-- Populated by dispatch.js by scanning h2 elements -->
            </ul>

            <div style="margin-top:2rem">
                <div class="read-time"><?php echo esc_html( $read_time ); ?></div>
                <div class="read-time-label"><?php esc_html_e( 'min read', 'dispatch' ); ?></div>
            </div>
        </aside>

        <!-- Article content -->
        <div class="art-body" id="dispatch-article-body">
            <?php the_content(); ?>
        </div>

        <!-- Right sidebar: share + tags -->
        <aside class="art-sidebar-right" aria-label="<?php esc_attr_e( 'Article tools', 'dispatch' ); ?>">
            <div class="sidebar-label"><?php esc_html_e( 'Share', 'dispatch' ); ?></div>

            <button class="share-btn" id="dispatch-copy-link" data-url="<?php echo esc_attr( get_permalink() ); ?>">
                <svg class="share-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M13 8V13H3V8M8 2v8M5 5l3-3 3 3"/>
                </svg>
                <?php esc_html_e( 'Copy link', 'dispatch' ); ?>
            </button>

            <a
                class="share-btn"
                href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=<?php echo rawurlencode( get_permalink() ); ?>"
                aria-label="<?php esc_attr_e( 'Share by email', 'dispatch' ); ?>"
            >
                <svg class="share-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M2 4h12v10H2zM2 4l6 5 6-5"/>
                </svg>
                <?php esc_html_e( 'Email', 'dispatch' ); ?>
            </a>

            <!-- Tags -->
            <?php
            $tags = get_the_tags();
            if ( $tags ) : ?>
                <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(245,242,236,0.08)">
                    <div class="sidebar-label"><?php esc_html_e( 'Filed under', 'dispatch' ); ?></div>
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a
                                href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                                class="article-tag"
                                style="color:<?php echo esc_attr( $cat_color ); ?>"
                            >
                                <?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </aside><!-- .art-sidebar-right -->

    </div><!-- .art-body-wrap -->

</article><!-- .dispatch-article -->


<!-- ─── Related Posts ─────────────────────────────────────────────────────────── -->
<?php
$related = get_posts( [
    'numberposts'     => 3,
    'category__in'    => $cat ? [ $cat->term_id ] : [],
    'post__not_in'    => [ get_the_ID() ],
    'post_status'     => 'publish',
    'orderby'         => 'rand',
] );

// Fallback: latest posts if no category matches
if ( empty( $related ) ) {
    $related = get_posts( [ 'numberposts' => 3, 'post__not_in' => [ get_the_ID() ] ] );
}
?>
<?php if ( ! empty( $related ) ) : ?>
    <section class="related" aria-label="<?php esc_attr_e( 'Related stories', 'dispatch' ); ?>">
        <div class="related-label"><?php esc_html_e( 'Keep reading', 'dispatch' ); ?></div>
        <div class="related-grid">
            <?php foreach ( $related as $rel ) :
                $rel_cats  = get_the_category( $rel->ID );
                $rel_cat   = $rel_cats ? $rel_cats[0]->name : '';
                $rel_color = $rel_cats ? dispatch_category_color( $rel_cats[0]->slug ) : '#c8f53a';
                $rel_words = str_word_count( wp_strip_all_tags( $rel->post_content ) );
                $rel_time  = max( 1, (int) ceil( $rel_words / 200 ) );
                ?>
                <a href="<?php echo esc_url( get_permalink( $rel ) ); ?>" class="related-card reveal">
                    <div class="related-tag" style="color:<?php echo esc_attr( $rel_color ); ?>">
                        <?php echo esc_html( $rel_cat ); ?>
                    </div>
                    <div class="related-title"><?php echo esc_html( get_the_title( $rel ) ); ?></div>
                    <div class="related-min">
                        <?php echo esc_html( $rel_time ); ?> <?php esc_html_e( 'min', 'dispatch' ); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section><!-- .related -->
<?php endif; ?>

<?php get_footer(); ?>
