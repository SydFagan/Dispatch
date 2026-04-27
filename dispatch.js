/**
 * Dispatch Theme — dispatch.js
 * Handles:
 *  - Mobile nav (hamburger / drawer)
 *  - Reading progress bar (single.php)
 *  - Table of contents builder (single.php)
 *  - Scroll-reveal animations (IntersectionObserver)
 *  - Horizontal scroll drag (homepage)
 *  - REST API fetch → homepage hero + article cards
 *  - Copy-link share button (single.php)
 *  - Contact form AJAX submit (page-contact.php)
 */

( function () {
    'use strict';

    // ── Category colour map ───────────────────────────────────────────────────
    const CAT_COLORS = {
        'culture':      '#ff4d2e',
        'power':        '#8b2be2',
        'the-future':   '#3af5e4',
        'very-online':  '#f5a623',
        'long-reads':   '#ff3d8a',
    };

    function catColor( slug ) {
        return CAT_COLORS[ (slug || '').toLowerCase() ] || '#c8f53a';
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    function qs( sel, ctx )  { return ( ctx || document ).querySelector( sel ); }
    function qsa( sel, ctx ) { return Array.from( ( ctx || document ).querySelectorAll( sel ) ); }

    function formatDate( iso ) {
        return new Date( iso ).toLocaleDateString( 'en-US', {
            month: 'short', day: 'numeric', year: 'numeric'
        });
    }

    function stripTags( html ) {
        const d = document.createElement( 'div' );
        d.innerHTML = html;
        return d.textContent || d.innerText || '';
    }

    function truncate( str, words ) {
        const arr = str.trim().split( /\s+/ );
        return arr.length <= words ? str : arr.slice( 0, words ).join( ' ' ) + '…';
    }

    // ── Mobile nav ────────────────────────────────────────────────────────────

    function initMobileNav() {
        const btn    = qs( '#dispatch-hamburger' );
        const drawer = qs( '#dispatch-drawer' );
        if ( ! btn || ! drawer ) return;

        let open = false;

        btn.addEventListener( 'click', () => {
            open = ! open;
            btn.classList.toggle( 'open', open );
            drawer.classList.toggle( 'open', open );
            btn.setAttribute( 'aria-expanded', open );
            drawer.setAttribute( 'aria-hidden', ! open );
            document.body.style.overflow = open ? 'hidden' : '';
        });

        document.addEventListener( 'click', ( e ) => {
            if ( open && ! drawer.contains( e.target ) && ! btn.contains( e.target ) ) {
                open = false;
                btn.classList.remove( 'open' );
                drawer.classList.remove( 'open' );
                btn.setAttribute( 'aria-expanded', false );
                drawer.setAttribute( 'aria-hidden', true );
                document.body.style.overflow = '';
            }
        });

        document.addEventListener( 'keydown', ( e ) => {
            if ( e.key === 'Escape' && open ) {
                open = false;
                btn.classList.remove( 'open' );
                drawer.classList.remove( 'open' );
                btn.setAttribute( 'aria-expanded', false );
                drawer.setAttribute( 'aria-hidden', true );
                document.body.style.overflow = '';
                btn.focus();
            }
        });
    }

    // ── Reading progress bar ──────────────────────────────────────────────────

    function initProgressBar() {
        const bar = qs( '#dispatch-progress' );
        if ( ! bar ) return;

        function update() {
            const total = document.body.scrollHeight - window.innerHeight;
            const pct   = total > 0 ? Math.min( ( window.scrollY / total ) * 100, 100 ) : 0;
            bar.style.width = pct + '%';
            bar.setAttribute( 'aria-valuenow', Math.round( pct ) );
        }

        window.addEventListener( 'scroll', update, { passive: true } );
        update();
    }

    // ── Table of contents ─────────────────────────────────────────────────────

    function initTOC() {
        const toc  = qs( '#dispatch-toc' );
        const body = qs( '#dispatch-article-body' );
        if ( ! toc || ! body ) return;

        const headings = qsa( 'h2', body );
        if ( headings.length === 0 ) {
            const sidebar = qs( '.art-sidebar-left' );
            if ( sidebar ) sidebar.style.display = 'none';
            return;
        }

        headings.forEach( ( h, i ) => {
            if ( ! h.id ) h.id = 'section-' + i;
            const li = document.createElement( 'li' );
            const a  = document.createElement( 'a' );
            a.href        = '#' + h.id;
            a.textContent = h.textContent;
            li.appendChild( a );
            toc.appendChild( li );
            a.addEventListener( 'click', ( e ) => {
                e.preventDefault();
                h.scrollIntoView( { behavior: 'smooth', block: 'start' } );
            });
        });

        const secObs = new IntersectionObserver(
            ( entries ) => {
                entries.forEach( ( entry ) => {
                    if ( entry.isIntersecting ) {
                        qsa( 'a', toc ).forEach( a => a.classList.remove( 'active' ) );
                        const active = qs( `a[href="#${entry.target.id}"]`, toc );
                        if ( active ) active.classList.add( 'active' );
                    }
                });
            },
            { threshold: 0.5, rootMargin: '0px 0px -40% 0px' }
        );

        headings.forEach( h => secObs.observe( h ) );
    }

    // ── Scroll-reveal ─────────────────────────────────────────────────────────

    function initScrollReveal() {
        const els = qsa( '.reveal, .article-card' );
        if ( els.length === 0 ) return;

        const obs = new IntersectionObserver(
            ( entries ) => {
                entries.forEach( ( entry ) => {
                    if ( entry.isIntersecting ) {
                        entry.target.classList.add( 'visible' );
                        obs.unobserve( entry.target );
                    }
                });
            },
            { threshold: 0.12 }
        );

        els.forEach( el => obs.observe( el ) );
    }

    // ── Horizontal scroll drag ────────────────────────────────────────────────

    function initHScroll() {
        const wrap = qs( '#hscroll' );
        if ( ! wrap ) return;

        let isDown = false, startX = 0, scrollLeft = 0;

        wrap.addEventListener( 'mousedown', ( e ) => {
            isDown     = true;
            startX     = e.pageX - wrap.offsetLeft;
            scrollLeft = wrap.scrollLeft;
            wrap.style.cursor = 'grabbing';
        });
        wrap.addEventListener( 'mouseleave', () => { isDown = false; wrap.style.cursor = 'grab'; });
        wrap.addEventListener( 'mouseup',    () => { isDown = false; wrap.style.cursor = 'grab'; });
        wrap.addEventListener( 'mousemove',  ( e ) => {
            if ( ! isDown ) return;
            e.preventDefault();
            wrap.scrollLeft = scrollLeft - ( e.pageX - wrap.offsetLeft - startX ) * 1.4;
        });
    }

    // ── Copy-link share button ────────────────────────────────────────────────

    function initShareButtons() {
        const btn = qs( '#dispatch-copy-link' );
        if ( ! btn ) return;

        btn.addEventListener( 'click', () => {
            const url = btn.dataset.url || window.location.href;
            navigator.clipboard.writeText( url ).then( () => {
                const orig = btn.innerHTML;
                btn.textContent    = 'Copied!';
                btn.style.color    = 'var(--lime)';
                setTimeout( () => { btn.innerHTML = orig; btn.style.color = ''; }, 2000 );
            }).catch( () => {
                const ta = document.createElement( 'textarea' );
                ta.value = url;
                document.body.appendChild( ta );
                ta.select();
                document.execCommand( 'copy' );
                document.body.removeChild( ta );
            });
        });
    }

    // ── REST API fetch ────────────────────────────────────────────────────────

    async function fetchPosts( endpoint ) {
        try {
            const res = await fetch( endpoint, {
                headers: { 'X-WP-Nonce': dispatchData.nonce }
            });
            if ( ! res.ok ) throw new Error( 'REST ' + res.status );
            return await res.json();
        } catch ( err ) {
            console.warn( '[Dispatch] REST fetch failed:', err );
            return null;
        }
    }

    // ── Hero image injection ──────────────────────────────────────────────────
    // Uses a real <img> element instead of background-image to avoid
    // CSS shorthand conflicts that can silently swallow background-image changes.

    function injectHeroImage( container, imgUrl, altText ) {
        if ( ! imgUrl || ! container ) return;

        // Remove any previously injected image
        const prev = qs( '.dispatch-hero-img', container );
        if ( prev ) prev.remove();

        const img        = document.createElement( 'img' );
        img.src          = imgUrl;
        img.alt          = altText || '';
        img.className    = 'dispatch-hero-img';
        img.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;' +
                            'object-fit:cover;object-position:center;' +
                            'z-index:0;pointer-events:none;';

        // Insert behind all existing children
        container.insertBefore( img, container.firstChild );
    }

    // ── Hero render functions ─────────────────────────────────────────────────

    function renderHeroMain( post ) {
        const cats   = post._embedded?.['wp:term']?.[0] ?? [];
        const cat    = cats[0] ?? null;
        const color  = catColor( cat?.slug ?? '' );
        const title  = stripTags( post.title.rendered );
        const date   = formatDate( post.date );
        const link   = post.link;
        const author = post._embedded?.author?.[0]?.name ?? '';
        const mins   = post.dispatch_read_time ?? '?';
        const imgUrl = post._embedded?.['wp:featuredmedia']?.[0]?.source_url ?? '';

        const heroLeft = qs( '.hero-left' );
        injectHeroImage( heroLeft, imgUrl, title );

        const tagEl   = qs( '.hero-tag' );
        const titleEl = qs( '#hero-title' );
        const metaEl  = qs( '#hero-meta' );
        const linkEl  = qs( '#hero-link' );

        if ( tagEl )   { tagEl.textContent = cat ? cat.name : 'Featured'; tagEl.style.color = color; }
        if ( titleEl ) { titleEl.innerHTML = `<a href="${link}">${title}</a>`; }
        if ( metaEl )  { metaEl.textContent = `${author}${author ? ' \u00b7 ' : ''}${date} \u00b7 ${mins} min read`; }
        if ( linkEl )  { linkEl.href = link; linkEl.textContent = 'Read the story'; }
    }

    function renderHeroSecondary( post, wrapId, tagId, titleId ) {
        const wrap  = qs( '#' + wrapId );
        const tagEl = qs( '#' + tagId );
        const titEl = qs( '#' + titleId );
        if ( ! wrap ) return;

        const cats   = post._embedded?.['wp:term']?.[0] ?? [];
        const cat    = cats[0] ?? null;
        const color  = catColor( cat?.slug ?? '' );
        const title  = stripTags( post.title.rendered );
        const imgUrl = post._embedded?.['wp:featuredmedia']?.[0]?.source_url ?? '';

        injectHeroImage( wrap, imgUrl, title );

        if ( tagEl ) { tagEl.textContent = cat ? cat.name : ''; tagEl.style.color = color; }
        if ( titEl ) { titEl.textContent = title; }

        wrap.style.cursor = 'pointer';
        wrap.setAttribute( 'role', 'link' );
        wrap.setAttribute( 'tabindex', '0' );
        wrap.addEventListener( 'click', () => { window.location.href = post.link; });
        wrap.addEventListener( 'keydown', ( e ) => { if ( e.key === 'Enter' ) window.location.href = post.link; });
    }

    // ── Article grid ──────────────────────────────────────────────────────────

    function renderArticleGrid( posts ) {
        const grid = qs( '#articles-grid' );
        if ( ! grid ) return;

        grid.innerHTML = '';

        posts.forEach( ( post, i ) => {
            const cats    = post._embedded?.['wp:term']?.[0] ?? [];
            const cat     = cats[0] ?? null;
            const color   = catColor( cat?.slug ?? '' );
            const title   = stripTags( post.title.rendered );
            const date    = formatDate( post.date );
            const mins    = post.dispatch_read_time ?? '?';
            const imgUrl  = post._embedded?.['wp:featuredmedia']?.[0]?.source_url ?? '';

            // Use manual excerpt if set, otherwise fall back to the post content
            const rawExcerpt = stripTags( post.excerpt.rendered ).trim();
            const rawContent = stripTags( post.content.rendered ).trim();
            const excerpt    = truncate( rawExcerpt || rawContent, 22 );

            const card         = document.createElement( 'a' );
            card.className     = 'article-card';
            card.href          = post.link;
            card.style.transitionDelay = ( i * 0.1 ) + 's';

            card.innerHTML = `
                <div class="card-color-bar" style="background:${color}"></div>
                ${ imgUrl ? `<div class="card-img-wrap"><img src="${imgUrl}" alt="${title}" loading="lazy"></div>` : '' }
                <div class="card-body">
                    <div class="card-tag" style="color:${color}">${ cat ? cat.name : '' }</div>
                    <h2 class="card-title">${title}</h2>
                    <p class="card-excerpt">${excerpt}</p>
                    <div class="card-meta">
                        <time datetime="${post.date}">${date}</time>
                        &nbsp;·&nbsp; ${mins} min
                    </div>
                </div>
            `;
            grid.appendChild( card );
        });

        requestAnimationFrame( () => {
            qsa( '.article-card', grid ).forEach( ( c ) => {
                setTimeout( () => {
                    const obs = new IntersectionObserver(
                        ( entries ) => {
                            entries.forEach( e => {
                                if ( e.isIntersecting ) { e.target.classList.add( 'visible' ); obs.unobserve( e.target ); }
                            });
                        },
                        { threshold: 0.12 }
                    );
                    obs.observe( c );
                }, 50 );
            });
        });
    }

    // ── Homepage init ─────────────────────────────────────────────────────────

    async function initHomepage() {
        const url   = dispatchData.restUrl + 'posts?_embed&per_page=5&status=publish';
        const posts = await fetchPosts( url );
        if ( ! posts || posts.length === 0 ) return;

        if ( posts[0] ) renderHeroMain( posts[0] );
        if ( posts[1] ) renderHeroSecondary( posts[1], 'hero-story-2', 'hero-tag-2', 'hero-title-2' );
        if ( posts[2] ) renderHeroSecondary( posts[2], 'hero-story-3', 'hero-tag-3', 'hero-title-3' );

        const gridPosts = posts.slice( 2, 5 );
        if ( gridPosts.length > 0 ) renderArticleGrid( gridPosts );
    }

    // ── Contact form ──────────────────────────────────────────────────────────

    function initContactForm() {
        const form = qs( '#dispatch-contact-form' );
        if ( ! form ) return;

        const statusEl = qs( '#dispatch-form-status' );

        form.addEventListener( 'submit', async ( e ) => {
            e.preventDefault();

            const submitBtn = qs( '[type="submit"]', form );
            const data      = new FormData( form );

            if ( data.get( 'dispatch_hp' ) ) return;

            submitBtn.disabled    = true;
            submitBtn.textContent = 'Sending…';

            try {
                const res  = await fetch( form.action, {
                    method:  'POST',
                    headers: { 'X-WP-Nonce': dispatchData.nonce },
                    body:    data,
                });
                const json = await res.json();

                if ( json.success ) {
                    form.reset();
                    showFormStatus( statusEl, "Message sent. We'll be in touch.", 'success' );
                } else {
                    showFormStatus( statusEl, json.data?.message || 'Something went wrong.', 'error' );
                }
            } catch {
                showFormStatus( statusEl, 'Network error. Please try again.', 'error' );
            } finally {
                submitBtn.disabled    = false;
                submitBtn.textContent = 'Send message';
            }
        });
    }

    function showFormStatus( el, message, type ) {
        if ( ! el ) return;
        el.textContent   = message;
        el.className     = 'form-status form-status--' + type;
        el.style.display = 'block';
        el.setAttribute( 'role', 'alert' );
        el.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
        setTimeout( () => { el.style.display = 'none'; }, 6000 );
    }

    // ── Boot ──────────────────────────────────────────────────────────────────

    document.addEventListener( 'DOMContentLoaded', () => {
        initMobileNav();
        initProgressBar();
        initTOC();
        initScrollReveal();
        initHScroll();
        initShareButtons();
        initContactForm();

        if ( typeof dispatchData !== 'undefined' && dispatchData.isHome === 'true' ) {
            initHomepage();
        }
    });

}());
