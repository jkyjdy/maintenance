( function( $, undefined ) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {}; // Namespace
	window.VAMTAM.CUSTOM_ANIMATIONS = {};

	$( function() {
		window.VAMTAM.CUSTOM_ANIMATIONS = {
			init: function () {
				this.VamtamCustomAnimations.init();
			},
			onDomReady: function () {
				this.VamtamCustomAnimations.scrollBasedAnims();
			},
			// Handles custom animations.
			VamtamCustomAnimations: {
				init: function() {
					this.registerAnimations();
					this.utils.watchScrollDirection();
					// this.observedAnims(); // Disabled in favor of elementorFrontend.waypoint().
				},
				registerAnimations: function () {
					var self = this;

					// Register animations here.
					var animations = [
						'stickyHeader', // Same name as function.
					];

					animations.forEach( function( animation ) {
						self[ animation ].apply( self );
					} );
				},
				// A sticky header animation.
				stickyHeader: function () {
					var $target                = $( '.vamtam-sticky-header' ),
						topScrollOffsetTrigger = 300,
						_self                  = this;

					if ( ! $target.length ) {
						return;
					}

					if ( $target.length > 1 ) {
						// There should only be one sticky header.
						$target = $target[ 0 ];
					}

					( function () { // IIFE for closure so $target is available in rAF.
						var prevAnimState,
							isTransparentHeader = $( $target ).hasClass( 'vamtam-sticky-header--transparent-header' ),
							isFrontend          = ! window.elementorFrontend.isEditMode();

						// state: fixed, scrolled up (not visible).
						var fixedHiddenState = function () {
							$( $target ).removeClass( 'vamtam-sticky-header--fixed-shown' );
							if ( ! $( $target ).hasClass( 'vamtam-sticky-header--fixed-hidden' ) ) {
								$( $target ).addClass( 'vamtam-sticky-header--fixed-hidden' );
							}
							prevAnimState = 'fixedHiddenState';
						};

						// state: fixed, scrolled down (visible).
						var fixedShownState = function () {
							$( $target ).removeClass( 'vamtam-sticky-header--fixed-hidden' );
							if ( ! $( $target ).hasClass( 'vamtam-sticky-header--fixed-shown' ) ) {
								$( $target ).addClass( 'vamtam-sticky-header--fixed-shown' );
							}
							prevAnimState = 'fixedShownState';
						};

						// state: no animation.
						var noAnimState = function () {
							$( $target ).removeClass( 'vamtam-sticky-header--fixed-shown' );
							$( $target ).removeClass( 'vamtam-sticky-header--fixed-hidden' );
							prevAnimState = 'noAnimState';
						};

						// Initial phase

						if ( isFrontend ) {
							$( $target ).after( '<div class="vamtam-prevent-scroll-jumps"></div>' );
						}

						function preventScrollJumps() {
							if ( ! isFrontend ) {
								return;
							}
							// Apply a negative margin to prevent content jumps.
							var stickyHeight = $( $target ).innerHeight();
							$( $target ).css( 'margin-bottom', '-' + stickyHeight + 'px' );
							$( $target ).next( '.vamtam-prevent-scroll-jumps' ).css( 'padding-top', stickyHeight + 'px' );
						}

						if ( window.VAMTAM.isMaxDeviceWidth() ) {
							preventScrollJumps();
						}

						// If passed the trigger point it should always be at fixed hidden state.
						if ( window.pageYOffset >= topScrollOffsetTrigger ) {
							fixedHiddenState();
						}

						var scrollTimer = null, lastScrollYPause = window.scrollY, lastDirection; // Used to check if the user has scrolled up far enough to trigger the sticky header.
						window.addEventListener( 'scroll', function( e ) {
							if ( scrollTimer !== null ) {
								clearTimeout( scrollTimer );
							}

							// If the user hasn't scrolled for 500ms we use that as the new Y point.
							scrollTimer = setTimeout( function() {
								lastScrollYPause = window.scrollY;
						  	}, 500 );

							var anim = window.VAMTAM.debounce( function() {
								if ( e.target.nodeName === '#document' ) {
									var direction =  _self.utils.getScrollDirection();

									if ( lastDirection !== direction ) {
										lastScrollYPause = window.scrollY;
									}
									lastDirection = direction;

									const scrollDifference = Math.abs( window.scrollY - lastScrollYPause ); // Pixels.
									if ( scrollDifference < 80 && window.scrollY > 80 ) {
										return;
									}
									
									if ( direction === 'up' ) {
										if ( window.pageYOffset >= topScrollOffsetTrigger ) {
											if ( prevAnimState !== 'fixedShownState' ) {
												fixedShownState();
											}
										} else {
											if ( prevAnimState !== 'noAnimState' ) {
												noAnimState();
											}
										}
										return;
									}

									if ( direction === 'down' ) {
										if ( window.pageYOffset >= topScrollOffsetTrigger || isTransparentHeader ) { // Transparent header gets hidden right away.
											// Safe-guard for times when the opening of the cart can cause a scroll down and hide the menu (also sliding the cart upwards).
											var menuCardNotVisible = ! $( $target ).find( '.elementor-menu-cart--shown' ).length;
											if ( prevAnimState !== 'fixedHiddenState' && menuCardNotVisible ) {
												fixedHiddenState();
											}
										}
									}
								}
							}, 200 );

							if ( window.VAMTAM.isMaxDeviceWidth() ) {
								preventScrollJumps();
								requestAnimationFrame( anim );
							} else if ( prevAnimState !== 'noAnimState' ) {
								noAnimState();
							}
						} );
					} )();
				},
				// Attaches observers to required anims and fires (adds vamtam-animate class & triggers vamtam:animate event)
				// them only when they are visible.
				observedAnims: function() {
					var observeClass    = 'vamtam-observe',
						animClass       = 'vamtam-animate',
						loopedAnimClass = 'vamtam-looped', // Whenever the el toggles to visible the anim will fire.
						animEls         = document.querySelectorAll( '.' + observeClass );

					if ( ! animEls.length ) {
						return;
					}

					var observer;

					var cb = function( iOEntries, observer ) {
						iOEntries.forEach( function( entry ) {
							var isVisible = false,
								el        = entry.target,
								$el       = el && $( el );

							if ( entry.isIntersecting ) {
								isVisible = true;
							}

							if ( isVisible ) {
								if ( ! $el.hasClass( animClass ) ) {
									$el.addClass( animClass );
									$el.trigger('vamtam:animate');
								}

								if ( ! $el.hasClass( loopedAnimClass ) ) {
									// If not looped, stop observing (anim fires only once).
									observer && observer.unobserve && observer.unobserve( el );
								}
							} else {
								if ( $el.hasClass( animClass ) ) {
									$el.removeClass( animClass );
								}
							}
						} );
					};

					animEls.forEach( function( el ) {
						var $el = $( el );
						$el.removeClass( animClass );

						if ( ! observer ) {
							observer = new IntersectionObserver( cb );
						}

						observer.observe( el );
					} );
				},
				// Scroll-based anims.
				scrollBasedAnims: function() {
					var scrollAnims = [
						'[data-settings*="growFromLeftScroll"]',
						'[data-settings*="growFromRightScroll"]',
					];

					var animEls = document.querySelectorAll( scrollAnims.join( ', ' ) );

					if ( ! animEls.length ) {
						return;
					}

					var observer, entries = {}, _this = this;

					var cb = function( iOEntries ) {
						iOEntries.forEach( function( entry ) {
							var currentScrollY       = entry.boundingClientRect.y,
								isInViewport         = entry.isIntersecting,
								observedEl           = entry.target,
								scrollPercentage     = Math.abs( parseFloat( ( entry.intersectionRatio * 100 ).toFixed( 2 ) ) ),
								prevScrollPercentage = entries[ observedEl.dataset.vamtam_anim_id ].lastScrollPercentage,
								lastScrollY          = entries[ observedEl.dataset.vamtam_anim_id ].lastScrollY,
								animateEl            = entries[ observedEl.dataset.vamtam_anim_id ].animateEl;

							var animate = function () {
								window.requestAnimationFrame( function() {
									animateEl.style.setProperty( '--vamtam-scroll-ratio', scrollPercentage + '%' );
								} );
							};

							if ( isInViewport && lastScrollY !== currentScrollY ) {
								if( _this.utils.getScrollDirection() === 'down') {
									if ( prevScrollPercentage < scrollPercentage ) {
										animate();
									}
								} else {
									animate();
								}
							}

							entries[ observedEl.dataset.vamtam_anim_id ].lastScrollY          = currentScrollY;
							entries[ observedEl.dataset.vamtam_anim_id ].lastScrollPercentage = scrollPercentage;
						} );
					};

					var buildThresholdList = function() {
						var thresholds = [],
							numSteps   = 50,
							i;

						for ( i = 1.0; i <= numSteps; i++ ) {
							var ratio = i / numSteps;
							thresholds.push( ratio );
						}

						thresholds.push( 0 );
						return thresholds;
					};

					const thresholds = buildThresholdList();

					animEls.forEach( function( el ) {
						if ( ! observer ) {
							var options = {
								root: null,
								rootMargin: "20% 0% 20% 0%",
								threshold: thresholds,
							};
							observer = new IntersectionObserver( cb, options );
						}

						// Init.
						el.style.setProperty( '--vamtam-scroll-ratio', '1%' );

						var observeEl;
						if ( el.classList.contains( 'elementor-widget' ) || el.classList.contains( 'elementor-column' ) ) {
							// For widgets we observe .elementor-widget-wrap
							// For columns we observe .elementor-row
							observeEl = el.parentElement;
							observeEl.setAttribute('data-vamtam_anim_id', el.dataset.id );
						} else {
							// Sections.
							// Add scroll anim wrapper.
							$( el ).before( '<div class="vamtam-scroll-anim-wrap" data-vamtam_anim_id="' + el.dataset.id + '"></div>' );
							var $wrap = $( el ).prev( '.vamtam-scroll-anim-wrap' );
							$( $wrap ).append( el );
							observeEl = $wrap[ 0 ];
						}

						entries[el.dataset.id] = {
							lastScrollY: '',
							lastScrollPercentage: '',
							observeEl: observeEl,
							animateEl: el,
						};

						observer.observe( observeEl );
					} );
				},
				// Common funcs used in custom animations.
				utils: {
					getAdminBarHeight: function () {
						return window.VAMTAM.adminBarHeight;
					},
					watchScrollDirection: function () {
						var watcher = function () {
							this.lastScrollTop = 0;
							this.utils = this;
							return {
								init: function () {
								},
								measure: function ( cpos ) {
									this.direction = cpos > this.lastScrollTop ? 'down' : 'up';
								}.bind( this ),
								mutate: function ( cpos ) {
									this.utils.getScrollDirection = function () {
										return this.direction;
									};
									this.lastScrollTop = cpos <= 0 ? 0 : cpos; // For Mobile or negative scrolling
								}.bind( this ),
							};
						}.bind( this );

						window.VAMTAM.addScrollHandler( watcher() );
					},
					isTouchDevice: function() {
						const prefixes = ' -webkit- -moz- -o- -ms- '.split( ' ' );
						
						const mq = function( query ) {
							return window.matchMedia( query ).matches;
						};
					
						if ( ( 'ontouchstart' in window ) || window.DocumentTouch && document instanceof DocumentTouch ) { // jshint ignore:line
							return true;
						}
					
						// include the 'heartz' as a way to have a non matching MQ to help terminate the join
						// https://git.io/vznFH
						var query = [ '(', prefixes.join( 'touch-enabled),(' ), 'heartz', ')' ].join( '' );

						return mq( query );
					},
				}
			},
		};

		window.VAMTAM.CUSTOM_ANIMATIONS.init();

		$( window ).ready( function () {
			window.VAMTAM.CUSTOM_ANIMATIONS.onDomReady();
		} );
	});
})( jQuery );
