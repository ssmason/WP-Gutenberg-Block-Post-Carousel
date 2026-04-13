/* global requestAnimationFrame */

const SWIPE_THRESHOLD = 50;

/*
 * Initialises carousel behaviour for a single carousel root element.
 *
 * Parameters:
 *  carousel - The carousel root element.
 */
function initCarousel( carousel ) {
	// Declare slides first so the early return can check its length.
	const slides = carousel.querySelectorAll( '.satori-post-carousel__slide' );

	const slidesPerView = parseInt( carousel.dataset.slidesPerView, 10 ) || 1;

	if ( slides.length <= slidesPerView ) {
		return;
	}

	const track = carousel.querySelector( '.satori-post-carousel__track' );
	const dots = carousel.querySelectorAll( '.satori-post-carousel__dot' );
	const prevBtn = carousel.querySelector(
		'.satori-post-carousel__btn--prev'
	);
	const nextBtn = carousel.querySelector(
		'.satori-post-carousel__btn--next'
	);
	const pauseBtn = carousel.querySelector(
		'.satori-post-carousel__btn--pause'
	);

	const autoplayInterval =
		( parseInt( carousel.dataset.autoplayInterval, 10 ) || 5 ) * 1000;

	if ( ! carousel.hasAttribute( 'tabindex' ) ) {
		carousel.setAttribute( 'tabindex', '0' );
	}

	let current = 0;
	let autoplayTimer = null;
	let isPaused = false;

	/*
	 * Navigates to a slide by index, wrapping at both ends.
	 *
	 * Parameters:
	 *  index - Desired slide index (wraps via modulo).
	 */
	function goTo( index ) {
		const total = slides.length;
		const next = ( ( index % total ) + total ) % total;

		slides.forEach( ( slide ) => {
			slide.setAttribute( 'hidden', '' );
			slide.classList.remove( 'is-active' );
		} );
		dots.forEach( ( dot ) => {
			dot.setAttribute( 'aria-selected', 'false' );
			dot.classList.remove( 'is-active' );
		} );

		for ( let i = 0; i < slidesPerView; i++ ) {
			slides[ ( next + i ) % total ].removeAttribute( 'hidden' );
		}
		slides[ next ].classList.add( 'is-active' );

		if ( dots[ next ] ) {
			dots[ next ].setAttribute( 'aria-selected', 'true' );
			dots[ next ].classList.add( 'is-active' );
		}

		track.setAttribute( 'aria-live', 'off' );
		requestAnimationFrame( () => {
			track.setAttribute( 'aria-live', 'polite' );
		} );

		current = next;
	}

	function startAutoplay() {
		if ( isPaused ) {
			return;
		}
		clearInterval( autoplayTimer );
		autoplayTimer = setInterval(
			() => goTo( current + 1 ),
			autoplayInterval
		);
	}

	function stopAutoplay() {
		clearInterval( autoplayTimer );
		autoplayTimer = null;
	}

	if ( pauseBtn ) {
		pauseBtn.addEventListener( 'click', () => {
			isPaused = ! isPaused;
			pauseBtn.setAttribute( 'aria-pressed', String( isPaused ) );
			pauseBtn.setAttribute(
				'aria-label',
				isPaused
					? pauseBtn.dataset.labelPlay
					: pauseBtn.dataset.labelPause
			);
			if ( isPaused ) {
				stopAutoplay();
			} else {
				startAutoplay();
			}
		} );
	}

	// Pause while the pointer is over the carousel.
	carousel.addEventListener( 'mouseenter', stopAutoplay );
	carousel.addEventListener( 'mouseleave', () => {
		if ( ! isPaused ) {
			startAutoplay();
		}
	} );

	// Pause while focus is anywhere inside the carousel.
	carousel.addEventListener( 'focusin', stopAutoplay );
	carousel.addEventListener( 'focusout', ( event ) => {
		if ( ! carousel.contains( event.relatedTarget ) && ! isPaused ) {
			startAutoplay();
		}
	} );

	// Pause when the tab is not visible.
	document.addEventListener( 'visibilitychange', () => {
		if ( document.hidden ) {
			stopAutoplay();
		} else if ( ! isPaused ) {
			startAutoplay();
		}
	} );

	if ( prevBtn ) {
		prevBtn.addEventListener( 'click', () => goTo( current - 1 ) );
	}

	if ( nextBtn ) {
		nextBtn.addEventListener( 'click', () => goTo( current + 1 ) );
	}

	dots.forEach( ( dot, i ) => {
		dot.addEventListener( 'click', () => goTo( i ) );
	} );

	let touchStartX = 0;

	carousel.addEventListener(
		'touchstart',
		( event ) => {
			touchStartX = event.touches[ 0 ].clientX;
		},
		{ passive: true }
	);

	carousel.addEventListener(
		'touchend',
		( event ) => {
			const delta = touchStartX - event.changedTouches[ 0 ].clientX;
			if ( Math.abs( delta ) >= SWIPE_THRESHOLD ) {
				goTo( delta > 0 ? current + 1 : current - 1 );
			}
		},
		{ passive: true }
	);

	carousel.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'ArrowLeft' ) {
			event.preventDefault();
			goTo( current - 1 );
		} else if ( event.key === 'ArrowRight' ) {
			event.preventDefault();
			goTo( current + 1 );
		}
	} );

	startAutoplay();
}

document.querySelectorAll( '.satori-post-carousel' ).forEach( initCarousel );
