// Contains logic related to elementor.
( function( $, undefined ) {
	"use strict";

	$( function() {

		var VAMTAM_ELEMENTOR = {
			init: function () {
				var isElementorBeforeV3 = parseInt( elementorCommonConfig.version[ 0 ] ) < 3;
				if ( isElementorBeforeV3 ) {
					this.VamtamColorPicker.init();
				}
				this.removeGrowScaleAnims.init();
			},
			// Removes grow-scale anims (select options) for all widgets except image.
			removeGrowScaleAnims: {
				init: function () {
					let selectedWidget = '';

					function removeImageAnims() {
						[ '', '_tablet', '_mobile' ].forEach( device => {
							const optGroupSelector = `#elementor-panel select[data-setting="_animation${device}"] optgroup[label="Vamtam"], #elementor-panel select[data-setting="animation${device}"] optgroup[label="Vamtam"]`,
								$animsVamtamOptGroup = $( optGroupSelector ),
								$imageGrowScaleAnims = $animsVamtamOptGroup.find( 'option[value*="imageGrowWithScale"' );

							// Remove the options.
							$.each( $imageGrowScaleAnims, function ( i, opt ) {
								$( opt ).remove();
							} );

							// If Vamtam optgroup is empty, remove it.
							if( $animsVamtamOptGroup.children(':visible').length == 0 ) {
								$animsVamtamOptGroup.remove();
							}
						} );
					}

					// Widgets.
					elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
						// Update selected widget.
						selectedWidget = model.elType || model.attributes.widgetType;
					} );
					// Columns.
					elementor.hooks.addAction( 'panel/open_editor/column', function( panel, model, view ) {
						// Update selected widget.
						selectedWidget = model.elType || model.attributes.elType;
					} );
					// Sections.
					elementor.hooks.addAction( 'panel/open_editor/section', function( panel, model, view ) {
						// Update selected widget.
						selectedWidget = model.elType || model.attributes.elType;
					} );

					const docClickHandler = ( e ) => {
						// We dont remove for Image widget.
						if ( selectedWidget === 'image' ) {
							return;
						}
						// Advanced Tab.
						if ( ! $( 'body' ).hasClass( 'e-route-panel-editor-advanced' ) ) {
							return;
						}
						// Isnide Motion Effects section.
						if ( e.target.closest( '.elementor-control-section_effects' ) ) {
							setTimeout( () => {
								removeImageAnims();
							}, 10 );
						}
					};

					const panel = document.getElementById( 'elementor-panel' );
					panel.addEventListener( 'click', docClickHandler, { passive: true, capture: true } ); // we need capture phase here.
				}
			},
			// Adds accent support to Elementor's PickR implementation.
			VamtamColorPicker: {
				init: function() {
					if ( elementor ) {
						var _self = this;
						var cp = elementor.modules.controls.Color.prototype;

						this.accentsArr = Object.values( VamtamColorPickerStrings.accents );

						// No accents, nothing to extend.
						if ( ! this.accentsArr.length ) {
							return;
						}

						// Extend initPicker method.
						cp.initPicker = ( function( existingHanler ) {
							function extendedFunc() {
								var control = this; // this === color control.
								existingHanler.apply( control );

								control.$colorInput = $( control.colorPicker.picker.getRoot().app ).find( '.pcr-result' );
								control.accentsArr = _self.accentsArr;
								control.sanitizeAccent = _self.sanitizeAccent.bind( this );

								// Inject accents.
								_self.injectAccents.apply( control );

								var accent = control.sanitizeAccent( control.getControlValue() );

								// Set initial value, in case of accent.
								if ( accent.val ) {
									// Set the color-picker button color as it wont automatically adjust in that case.
									control.colorPicker.picker.getRoot().button.style.color = accent.val;

									// Triggers the change cycle.
									control.colorPicker.picker.setColor( accent.val, true );

									// Set the input val.
									control.$colorInput.val( accent.isCssVar ? accent.userFriendly : control.getControlValue() );

									//Add active class to accent.
									$( control.colorPicker.picker.getRoot().app ).find( '[data-accent="' + accent.index + '"]' ).addClass( 'pcr-active' );
								}

								// Bind listeners for proper accent detection.
								_self.attachCustomListeners.apply( this );
							}

							return extendedFunc;
						})( cp.initPicker );

						// Extend onPickerChange method.
						cp.onPickerChange = ( function( existingHanler ) {
							function extendedFunc() {
								var control = this; // this === color control.
								existingHanler.apply( control );

								var $app = $( control.colorPicker.picker.getRoot().app );
								// That's for when an accent has been set from the accents palette or from user input.
								if ( control.accent ) {
									// Set the input val.
									control.$colorInput.val( control.accent.isCssVar ? 'var(--vamtam-accent-color-' + control.accent.index + ')' : 'accent' + control.accent.index);
									// Set the Control value.
									control.setValue.apply( control, [ 'var(--vamtam-accent-color-' + control.accent.index + ')' ] );
									// Update active class.
									$app.find( '.pcr-active[data-accent]' ).removeClass( 'pcr-active' );
									$app.find( '[data-accent="' + control.accent.index + '"]' ).addClass( 'pcr-active' );

									control.prevColorWasAccent = true; // Optimizes the else if statement below.
									control.accent = undefined;
								} else if ( control.prevColorWasAccent || typeof control.prevColorWasAccent === 'undefined' ) {
									// If prev color was accent, remove active class.
									$app.find( '.pcr-active[data-accent]' ).removeClass( 'pcr-active' );
									control.prevColorWasAccent = false;
								}
							}

							return extendedFunc;
						})( cp.onPickerChange );
					}
				},
				attachCustomListeners: function () {
					var control = this; // this === color control.

					var handleInputChange = function () {
						var accent = control.sanitizeAccent( control.$colorInput.val() );
						if ( accent.val ) {
							control.accent = {
								color: accent.val,
								index: accent.index,
								isCssVar: accent.isCssVar,
							};
							// This will trigger the regular onChange procedure.
							control.$colorInput.val( accent.val );
						}
					}

					// add listener on capture phase so it fires before Pickr's.
					control.$colorInput[0].addEventListener( 'input', handleInputChange, true );
					// add listener on capture phase so it fires before Pickr's.
					control.$colorInput[0].addEventListener( 'keyup', handleInputChange, true );
				},
				injectAccents: function () {
					var control = this; // this === color control.
					var accentsWrap = document.createElement( 'div' );

					$( accentsWrap ).addClass( 'vamtam-accents' );

					control.accentsArr.forEach( function( accent, i ) {
						var accentEl = document.createElement( 'div' );
						$( accentEl ).addClass( 'pcr-swatch' );
						$( accentEl ).attr( 'data-accent', ++i );
						$( accentEl ).css( 'color', accent );
						// Accents click.
						$( accentEl ).on( 'click', function () {
							var accentIndex = parseInt( $( this ).attr( 'data-accent' ) );
							var color = control.accentsArr[ accentIndex - 1 ];

							if ( ( 'accent' + accentIndex ) === control.$colorInput.val() ) {
								return; // Same accent.
							}

							control.accent = {
								color: color,
								index: accentIndex,
							};

							control.colorPicker.picker.setColor( color );
						});
						$( accentsWrap ).append( accentEl );
					});

					$( control.colorPicker.picker.getRoot().app ).append( accentsWrap );
				},
				sanitizeAccent: function( value ) {
					if ( value.startsWith( '#' ) ) {
						return false;
					}

					var accent = value.match( /^var\(--vamtam-accent-color-(\d)\)$/ );
					if ( accent ) {
						return {
							val: this.accentsArr[ accent[1] - 1 ],
							index: accent[1],
							isCssVar: true,
							userFriendly: 'accent' + accent[1],
							asCssVar: value,
						}
					}

					accent = value.match( /^accent(\d)/ );

					if ( accent ) {
						return {
							val: this.accentsArr[ accent[1] - 1 ],
							index: accent[1],
						}
					}

					return false;
				},
			}
		}

		$( window ).load( function() {
			VAMTAM_ELEMENTOR.init();
		});
	});
})( jQuery );
