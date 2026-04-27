<?php
/*
 * Template Name: About
 */

// ── Edit creator info here ─────────────────────────────────────────────────────

$creator_1 = [
    'name'  => 'Jordan Kellerman',
    'role'  => 'Editor & Co-founder',
    'img'   => '', // paste your Media Library URL here, e.g. home_url('/wp-content/uploads/2026/04/jordan.jpg')
    'initials' => 'JK',
    'bio'   => 'Jordan has spent a decade writing about the ways technology and power intersect in everyday life. Before Dispatch, she was a senior editor at a national digital outlet where she led the technology desk and oversaw a team of eight reporters. She believes the best journalism makes the invisible visible.',
    'links' => [
        [ 'label' => 'Twitter / X', 'url' => '#' ],
        [ 'label' => 'Email',       'url' => 'mailto:jordan@dispatch.test' ],
    ],
];

$creator_2 = [
    'name'  => 'Theo Park',
    'role'  => 'Design & Co-founder',
    'img'   => '', // paste your Media Library URL here
    'initials' => 'TP',
    'bio'   => 'Theo is a designer and writer whose work sits at the intersection of visual culture and editorial journalism. He designed Dispatch from scratch with the belief that how a story looks is as important as what it says. He previously led product design at two independent media startups.',
    'links' => [
        [ 'label' => 'Portfolio', 'url' => '#' ],
        [ 'label' => 'Email',     'url' => 'mailto:theo@dispatch.test' ],
    ],
];

// ─────────────────────────────────────────────────────────────────────────────

get_header();
?>

<div class="about-page">

    <!-- ─── Hero ───────────────────────────────────────────────────────────── -->
    <div class="about-hero">
        <div class="about-hero-inner">
            <div class="about-eyebrow"><?php esc_html_e( 'Who we are', 'dispatch' ); ?></div>
            <h1 class="about-title"><?php the_title(); ?></h1>
            <p class="about-intro">
                <?php esc_html_e( 'Dispatch is an independent editorial site built by journalists who got tired of watching good stories get optimised out of existence. We write about culture, power, and the future — for people who read past the headline.', 'dispatch' ); ?>
            </p>
        </div>
        <div class="about-hero-accent" aria-hidden="true">US</div>
    </div>

    <!-- ─── Mission quote ──────────────────────────────────────────────────── -->
    <div class="about-mission reveal">
        <div class="about-mission-label"><?php esc_html_e( 'Our mission', 'dispatch' ); ?></div>
        <div class="about-mission-text">
            <?php esc_html_e( 'We believe independent journalism should be ', 'dispatch' ); ?>
            <strong><?php esc_html_e( 'bold, honest, and readable', 'dispatch' ); ?></strong>
            <?php esc_html_e( ' — not optimised for the algorithm that ate the news.', 'dispatch' ); ?>
        </div>
    </div>

    <!-- ─── Page content (editable in WP editor) ───────────────────────────── -->
    <?php if ( have_posts() ) : the_post(); ?>
        <?php if ( get_the_content() ) : ?>
            <div class="about-mission reveal">
                <div class="about-mission-label"><?php esc_html_e( 'More', 'dispatch' ); ?></div>
                <div class="art-body" style="padding:0">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- ─── Team ────────────────────────────────────────────────────────────── -->
    <section class="about-team-section" aria-label="<?php esc_attr_e( 'Meet the team', 'dispatch' ); ?>">
        <div class="about-team-label"><?php esc_html_e( 'The team', 'dispatch' ); ?></div>

        <div class="about-team-grid">

            <?php foreach ( [ $creator_1, $creator_2 ] as $creator ) : ?>
            <div class="creator-card reveal">

                <!-- Round image -->
                <div class="creator-img-wrap">
                    <?php if ( ! empty( $creator['img'] ) ) : ?>
                        <img
                            src="<?php echo esc_url( $creator['img'] ); ?>"
                            alt="<?php echo esc_attr( $creator['name'] ); ?>"
                            loading="lazy"
                        >
                    <?php else : ?>
                        <!-- Placeholder shown until you add a photo URL above -->
                        <div class="creator-img-placeholder"><?php echo esc_html( $creator['initials'] ); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="creator-info">
                    <div class="creator-role"><?php echo esc_html( $creator['role'] ); ?></div>
                    <h2 class="creator-name"><?php echo esc_html( $creator['name'] ); ?></h2>
                    <p class="creator-bio"><?php echo esc_html( $creator['bio'] ); ?></p>

                    <?php if ( ! empty( $creator['links'] ) ) : ?>
                        <div class="creator-links">
                            <?php foreach ( $creator['links'] as $link ) : ?>
                                <a href="<?php echo esc_url( $link['url'] ); ?>" class="creator-link">
                                    <?php echo esc_html( $link['label'] ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; ?>

        </div>
    </section>

</div><!-- .about-page -->

<script>
// Trigger scroll reveal for this page
document.addEventListener( 'DOMContentLoaded', function () {
    var els = document.querySelectorAll( '.reveal' );
    var obs = new IntersectionObserver( function ( entries ) {
        entries.forEach( function ( e ) {
            if ( e.isIntersecting ) { e.target.classList.add( 'visible' ); obs.unobserve( e.target ); }
        });
    }, { threshold: 0.1 } );
    els.forEach( function ( el ) { obs.observe( el ); } );
});
</script>

<?php get_footer(); ?>
