/*!
 * wp-color-picker-alpha
 *
 * Overwrite Automattic Iris for enabled Alpha Channel in wpColorPicker
 * Only run in input and is defined data alpha in true
 *
 * Version: 2.0.1
 * https://github.com/kallookoo/wp-color-picker-alpha
 * Licensed under the GPLv2 license.
 *
 * Customized and fixed by Elementor team
 */
( function( $ ) {
	// Variable for some backgrounds ( grid )
	var image   = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==',
		// html stuff for wpColorPicker copy of the original color-picker.js
		_before = '<button type="button" class="button wp-color-result" aria-expanded="false"><span class="wp-color-result-text"></span></button>',
		_after = '<div class="wp-picker-holder" />',
		_wrap = '<div class="wp-picker-container" />',
		_button = '<input type="button" class="button button-small" />',
		_wrappingLabel = '<label></label>',
		_wrappingLabelText = '<span class="screen-reader-text"></span>';

	/**
	 * Overwrite Color
	 * for enable support rbga
	 */
	Color.fn.toString = function() {
		if ( this._alpha < 1 ) {
			return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
		}
		var hex = parseInt( this._color, 10 ).toString( 16 );

		if ( this.error ) {
			return '';
		}
		if ( hex.length < 6 ) {
			hex = ('00000' + hex).substr( -6 );
		}
		return '#' + hex;
	};

	/**
	 * Overwrite wpColorPicker
	 */
	$.widget( 'wp.wpColorPicker', $.wp.wpColorPicker, {
		/**
		 * @summary Creates the color picker.
		 *
		 * Creates the color picker, sets default values, css classes and wraps it all in HTML.
		 *
		 * @since 3.5.0
		 *
		 * @access private
		 *
		 * @returns {void}
		 */
		_create: function() {
			// Return early if Iris support is missing.
			if ( ! $.support.iris ) {
				return;
			}

			var self = this,
				el = self.element;

			// Override default options with options bound to the element.
			$.extend( self.options, el.data() );

			// Create a color picker which only allows adjustments to the hue.
			if ( self.options.type === 'hue' ) {
				return self._createHueOnly();
			}

			// Bind the close event.
			self.close = $.proxy( self.close, self );

			self.initialValue = el.val();

			// Add a CSS class to the input field.
			el.addClass( 'wp-color-picker' );

			/*
			 * Check if there's already a wrapping label, e.g. in the Customizer.
			 * If there's no label, add a default one to match the Customizer template.
			 */
			if ( ! el.parent( 'label' ).length ) {
				// Wrap the input field in the default label.
				el.wrap( _wrappingLabel );
				// Insert the default label text.
				self.wrappingLabelText = $( _wrappingLabelText )
					.insertBefore( el )
					.text( wpColorPickerL10n.defaultLabel );
			}

			/*
			 * At this point, either it's the standalone version or the Customizer
			 * one, we have a wrapping label to use as hook in the DOM, let's store it.
			 */
			self.wrappingLabel = el.parent();

			// Wrap the label in the main wrapper.
			self.wrappingLabel.wrap( _wrap );
			// Store a reference to the main wrapper.
			self.wrap = self.wrappingLabel.parent();
			// Set up the toggle button and insert it before the wrapping label.
			self.toggler = $( _before )
				.insertBefore( self.wrappingLabel );
			// Set the toggle button span element text.
			self.toggler.find( '.wp-color-result-text' ).text( wpColorPickerL10n.pick );
			// Set up the Iris container and insert it after the wrapping label.
			self.pickerContainer = $( _after ).insertAfter( self.wrappingLabel );
			// Store a reference to the Clear/Default button.
			self.button = $( _button );

			// Set up the Clear/Default button.
			if ( self.options.defaultColor ) {
				self.button
					.addClass( 'wp-picker-default' )
					.val( wpColorPickerL10n.defaultString )
					.attr( 'aria-label', wpColorPickerL10n.defaultAriaLabel );
			} else {
				self.button
					.addClass( 'wp-picker-clear' )
					.val( wpColorPickerL10n.clear )
					.attr( 'aria-label', wpColorPickerL10n.clearAriaLabel );
			}

			// Wrap the wrapping label in its wrapper and append the Clear/Default button.
			self.wrappingLabel
				.wrap( '<span class="wp-picker-input-wrap hidden" />' )
				.after( self.button );

			/*
			 * The input wrapper now contains the label+input+Clear/Default button.
			 * Store a reference to the input wrapper: we'll use this to toggle
			 * the controls visibility.
			 */
			self.inputWrapper = el.closest( '.wp-picker-input-wrap' );

			/*
			 * CSS for support < 4.9
			 */
			self.toggler.css({
				padding: 0
			});

			self.toggler.find( '.wp-color-result-text' ).hide();

			var setTogglerColor = function( color ) {
				if ( self.options.alpha ) {
					self.toggler.css( {
						'background-image' : 'url(' + image + ')',
						'position' : 'relative'
					} );

					if ( ! self.toggler.find('span.color-alpha').length ) {
						self.toggler.append('<span class="color-alpha" />');
					}

					self.toggler.find( 'span.color-alpha' ).css( { backgroundColor : color } );
				} else {
					self.toggler.css( { backgroundColor : color } );
				}
			};

			var accentsArr = Object.values( VamtamColorPickerStrings.accents );
			var hasAccents = accentsArr.length;

			if ( hasAccents ) {
				self.pickerContainer.addClass('vamtam-accents');
			}

			el.iris( {
				target: self.pickerContainer,
				hide: self.options.hide,
				width: self.options.width,
				mode: self.options.mode,
				palettes: hasAccents ? self.options.palettes.concat( accentsArr ) : self.options.palettes,
				/**
				 * @summary Handles the onChange event if one has been defined in the options.
				 *
				 * Handles the onChange event if one has been defined in the options and additionally
				 * sets the background color for the toggler element.
				 *
				 * @since 3.5.0
				 *
				 * @param {Event} event    The event that's being called.
				 * @param {HTMLElement} ui The HTMLElement containing the color picker.
				 *
				 * @returns {void}
				 */
				change: function( event, ui ) {
					setTogglerColor( ui.color );

					if ( $.isFunction( self.options.change ) ) {
						self.options.change.call( this, event, ui );
					}
				}
			} );

			el.val( self.initialValue );

			setTogglerColor( self.initialValue );

			self._addListeners();

			// Distinquish accents
			if ( hasAccents ) {
				var accentsEls = self.pickerContainer.find( '.iris-palette-container' ).children().slice(-8);
				accentsEls.each( function (i, el) {
					$(el).attr('data-accent', ++i);
				} )
				var isAccent = this._sanitizeAccent( self.initialValue );

				// Set initial value, in case of accent
				if ( isAccent ) {
					if ( isAccent.isCssVar ) {
						self.element.iris( 'color', isAccent.userFriendly );
					} else {
						self.element.iris( 'color', self.initialValue );
					}
				}
			}

			// Accent palette event bindings
			self.pickerContainer.find( '.iris-palette-container' ).on( 'click.palette', '.iris-palette', function(event) {
				var accent = event.target && $(event.target).attr('data-accent');
				if ( accent ) {
					self.element.iris( 'color', 'var(--vamtam-accent-color-' + accent + ')');
				}
			} );

			// Force the color picker to always be closed on initial load.
			if ( ! self.options.hide ) {
				self.toggler.click();
			}
		},

		open: function() {
			this.element.iris( 'toggle' );
			this.inputWrapper.removeClass( 'hidden' );
			this.wrap.addClass( 'wp-picker-active' );
			this.toggler
				.addClass( 'wp-picker-open' )
				.attr( 'aria-expanded', 'true' );
			$( 'body' ).on( 'click.wpcolorpicker', this.close );
		},

		/**
		 * @summary Binds event listeners to the color picker.
		 *
		 * @since 3.5.0
		 *
		 * @access private
		 *
		 * @returns {void}
		 */
		_addListeners: function() {
			var self = this;

			/**
			 * @summary Prevent any clicks inside this widget from leaking to the top and closing it.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returs {void}
			 */
			self.wrap.on( 'click.wpcolorpicker', function( event ) {
				event.stopPropagation();
			});

			/**
			 * @summary Open or close the color picker depending on the class.
			 *
			 * @since 3.5
			 */
			self.toggler.click( function(){
				if ( self.toggler.hasClass( 'wp-picker-open' ) ) {
					self.close();
				} else {
					self.open();
				}
			});

			/**
			 * @summary Checks if value is empty when changing the color in the color picker.
			 *
			 * Checks if value is empty when changing the color in the color picker.
			 * If so, the background color is cleared.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returns {void}
			 */
			self.element.on( 'change', function( event ) {
				// Empty or Error = clear
				if ( $( this ).val() === '' || self.element.hasClass( 'iris-error' ) ) {
					if ( self.options.alpha ) {
						self.toggler.find( 'span.color-alpha' ).css( 'backgroundColor', '' );
					} else {
						self.toggler.css( 'backgroundColor', '' );
					}

					// fire clear callback if we have one
					if ( $.isFunction( self.options.clear ) )
						self.options.clear.call( this, event );
				}
			} );

			/**
			 * @summary Enables the user to clear or revert the color in the color picker.
			 *
			 * Enables the user to either clear the color in the color picker or revert back to the default color.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returns {void}
			 */
			self.button.on( 'click', function( event ) {
				if ( $( this ).hasClass( 'wp-picker-clear' ) ) {
					self.element.val( '' );
					if ( self.options.alpha ) {
						self.toggler.find( 'span.color-alpha' ).css( 'backgroundColor', '' );
					} else {
						self.toggler.css( 'backgroundColor', '' );
					}

					if ( $.isFunction( self.options.clear ) )
						self.options.clear.call( this, event );

				} else if ( $( this ).hasClass( 'wp-picker-default' ) ) {
					self.element.val( self.options.defaultColor ).change();
				}
			});
		},

		_sanitizeAccent: function( value ) {
			if ( value.match( /^var\(--vamtam-accent-color-(\d)\)$/ ) ) {
				return {
					val:value, // css var
					isCssVar: true,
					userFriendly: value.substr( value.indexOf( 'accent' ), 14 ).replace( '-color-', '' ),
				}
			}

			var accent = value.match( /^accent(\d)/ );

			if ( accent ) {
				return VamtamColorPickerStrings.accents[ accent[1] ];
		}

			return false;
		},
	});

	/**
	 * Overwrite iris
	 */
	$.widget( 'a8c.iris', $.a8c.iris, {
		_create: function() {
			this._super();

			// Global option for check is mode rbga is enabled
			this.options.alpha = this.element.data( 'alpha' ) || false;

			// Is not input disabled
			if ( ! this.element.is( ':input' ) )
				this.options.alpha = false;

			if ( typeof this.options.alpha !== 'undefined' && this.options.alpha ) {
				var self       = this,
					el         = self.element,
					_html      = '<div class="iris-strip iris-slider iris-alpha-slider"><div class="iris-slider-offset iris-slider-offset-alpha"></div></div>',
					aContainer = $( _html ).appendTo( self.picker.find( '.iris-picker-inner' ) ),
					aSlider    = aContainer.find( '.iris-slider-offset-alpha' ),
					controls   = {
						aContainer : aContainer,
						aSlider    : aSlider
					};

				if ( typeof el.data( 'custom-width' ) !== 'undefined' ) {
					self.options.customWidth = parseInt( el.data( 'custom-width' ) ) || 0;
				} else {
					self.options.customWidth = 100;
				}

				// Set default width for input reset
				self.options.defaultWidth = el.width();

				// Update width for input
				if ( self._color._alpha < 1 || self._color.toString().indexOf('rgb') != -1 ) {
					//el.width( parseInt( self.options.defaultWidth + self.options.customWidth ) );
				}
				el.width( parseInt( self.options.defaultWidth + self.options.customWidth ) );

				// Push new controls
				$.each( controls, function( k, v ) {
					self.controls[k] = v;
				} );

				// Change size strip and add margin for sliders
				self.controls.square.css( { 'margin-right': '0' } );
				var emptyWidth   = ( self.picker.width() - self.controls.square.width() - 20 ),
					stripsMargin = ( emptyWidth / 6 ),
					stripsWidth  = ( ( emptyWidth / 2 ) - stripsMargin );

				$.each( [ 'aContainer', 'strip' ], function( k, v ) {
					self.controls[v].width( stripsWidth ).css( { 'margin-left' : stripsMargin + 'px' } );
				} );

				self.setAlphaSliderBackground();

				// Add new slider
				self._initControls();
			}
		},
		_initControls: function() {
			this._super();

			if ( this.options.alpha ) {
				var self     = this,
					controls = self.controls;

				controls.aSlider.slider({
					orientation : 'vertical',
					min         : 0,
					max         : 100,
					step        : 1,
					value       : parseInt( self._color._alpha * 100 ),
					slide       : function( event, ui ) {
						// Update alpha value
						self._color._alpha = parseFloat( ui.value / 100 );
						self._change.apply( self, arguments );
					}
				});
			}
		},
		_change: function() {
			var self = this,
				controls = self.controls,
				color = self._getHSpaceColor(),
				actions = [ 'square', 'strip' ],
				controlOpts = self.options.controls,
				type = controlOpts[self.active] || 'external',
				oldHue = self.hue;

			if ( self.active === 'strip' ) {
				// take no action on any of the square sliders if we adjusted the strip
				actions = [];
			} else if ( self.active !== 'external' ) {
				// for non-strip, non-external, strip should never change
				actions.pop(); // conveniently the last item
			}

			$.each( actions, function(index, item) {
				var value, dimensions, cssObj;
				if ( item !== self.active ) {
					switch ( item ) {
						case 'strip':
							// reverse for hue
							value = ( controlOpts.strip === 'h' ) ? self._scale[controlOpts.strip] - color[controlOpts.strip] : color[controlOpts.strip];
							controls.stripSlider.slider( 'value', value );
							break;
						case 'square':
							dimensions = self._squareDimensions();
							cssObj = {
								left: color[controlOpts.horiz] / self._scale[controlOpts.horiz] * dimensions.w,
								top: dimensions.h - ( color[controlOpts.vert] / self._scale[controlOpts.vert] * dimensions.h )
							};

							self.controls.squareDrag.css( cssObj );
							break;
					}
				}
			});

			// Ensure that we don't change hue if we triggered a hue reset
			if ( color.h !== oldHue && self._isNonHueControl( self.active, type ) ) {
				self._color.h(oldHue);
			}

			// store hue for repeating above check next time
			self.hue = self._color.h();

			if ( ! self.options.accent ) {
				self.options.color = self._color.toString();
			}

			// only run after the first time
			if ( self._inited ) {
				self._trigger( 'change', { type: self.active }, { color: self._color } );
			}

			if ( self.element.is( ':input' ) && ! self._color.error ) {
				self.element.removeClass( 'iris-error' );
				if ( self.options.accent ) {
					if ( self.options.accent.isCssVar ) {
						//when css var we still show a more user-friendly string like accentN
						self.element.val() !== self.options.color && self.element.val( self.options.accent.userFriendly );
					} else {
						self.element.val() !== self.options.color && self.element.val( self.options.color );
					}
				} else {
					self.element.val() !== self._color.toString() && self.element.val( self._color.toString() );
				}
			}

			delete self.options.accent;

			self._paint();
			self._inited = true;
			self.active = false;

			var el = self.element;

			if ( self.options.alpha ) {
				var	controls     = self.controls,
					alpha        = parseInt( self._color._alpha * 100 ),
					target       = self.picker.closest( '.wp-picker-container' ).find( '.wp-color-result' );

				self.setAlphaSliderBackground();

				if ( target.hasClass( 'wp-picker-open' ) ) {
					// Update alpha value
					controls.aSlider.slider( 'value', alpha );

					/**
					 * Disabled change opacity in default slider Saturation ( only is alpha enabled )
					 * and change input width for view all value
					 */
					if ( self._color._alpha < 1 ) {
						controls.strip.attr( 'style', controls.strip.attr( 'style' ).replace( /rgba\(([0-9]+,)(\s+)?([0-9]+,)(\s+)?([0-9]+)(,(\s+)?[0-9\.]+)\)/g, 'rgb($1$3$5)' ) );
						//el.width( parseInt( defaultWidth + customWidth ) );
					} else {
						//el.width( defaultWidth );
						}
					}
				}

			var reset = el.data( 'reset-alpha' ) || false;

			if ( reset ) {
				self.picker.find( '.iris-palette-container' ).on( 'click.palette', '.iris-palette', function() {
					self._color._alpha = 1;
					self.active        = 'external';
					self._change();
				} );
			}
		},
		_setOption: function( key, value ) {
			var self = this,
				oldValue = self.options[key],
				doDimensions = false,
				hexLessColor,
				newColor,
				method;

			// ensure the new value is set. We can reset to oldValue if some check wasn't met.
			self.options[key] = value;

			switch(key) {
				case 'color':
					// cast to string in case we have a number
					value = '' + value;
					hexLessColor = value.replace( /^#/, '' );
					var isAccent = this._sanitizeAccent( value );
					if ( isAccent ) {
						if ( isAccent.isCssVar ) {
							newColor = new Color( VamtamColorPickerStrings.accents[ value[value.length -2] ] ).setHSpace( self.options.mode );
						} else {
							//accentN
							newColor = new Color( VamtamColorPickerStrings.accents[ value[value.length -1] ] ).setHSpace( self.options.mode );
						}
						self.options.accent = isAccent;
					} else {
						newColor = new Color( value ).setHSpace( self.options.mode );
					}

					if ( newColor.error && ! isAccent ) {
						self.options[key] = oldValue;
					} else {
						self._color = newColor;
						self.options.color = self.options[key] = isAccent ? value : self._color.toString();
						self.active = 'external';
						self._change();
					}
					break;
				case 'palettes':
					doDimensions = true;

					if ( value ) {
						self._addPalettes();
					} else {
						self.picker.find('.iris-palette-container').remove();
					}

					// do we need to add events?
					if ( ! oldValue ) {
						self._paletteListeners();
					}
					break;
				case 'width':
					doDimensions = true;
					break;
				case 'border':
					doDimensions = true;
					method = value ? 'addClass' : 'removeClass';
					self.picker[method]('iris-border');
					break;
				case 'mode':
				case 'controls':
					// if nothing's changed, let's bail, since this causes re-rendering the whole widget
					if ( oldValue === value ) {
						return;
					}

					// we're using these poorly named variables because they're already scoped.
					// method is the element that Iris was called on. oldValue will be the options
					method = self.element;
					oldValue = self.options;
					oldValue.hide = ! self.picker.is( ':visible' );
					self.destroy();
					self.picker.remove();
					return $(self.element).iris(oldValue);
			}

			// Do we need to recalc dimensions?
			if ( doDimensions ) {
				self._dimensions(true);
			}
		},
		_sanitizeAccent: function( value ) {
			if ( value.match( /^var\(--vamtam-accent-color-(\d)\)$/ ) ) {
				return {
					val:value, // css var
					isCssVar: true,
					userFriendly: value.substr( value.indexOf( 'accent' ), 14 ).replace( '-color-', '' ),
				}
			}

			var accent = value.match( /^accent(\d)/ );

			if ( accent ) {
				return VamtamColorPickerStrings.accents[ accent[1] ];
			}

			return false;
		},
		_addInputListeners: function( input ) {
			var self             = this,
				debounceTimeout  = 100,
				callback         = function( event ) {
					var color    = new Color( input.val() ),
						val      = input.val();
						isAccent = self._sanitizeAccent( val );

					input.removeClass( 'iris-error' );

					if ( isAccent ) {
						if ( isAccent.isCssVar ) {
							self._setOption( 'color', val );
						} else {
							self._setOption( 'color', 'var(--vamtam-accent-color-' + val[ val.length - 1 ] + ')' );
						}
						return;
					}

					// we gave a bad color
					if ( color.error ) {
						// don't error on an empty input
						if ( val !== '' ) {
							input.addClass( 'iris-error' );
						}
					} else {
						if ( color.toString() !== self._color.toString() ) {
							// let's not do this on keyup for hex shortcodes
							if ( ! ( event.type === 'keyup' && val.match( /^[0-9a-fA-F]{3}$/ ) ) ) {
								self._setOption( 'color', color.toString() );
							}
						}
					}
				};

			input.on( 'change', callback ).on( 'keyup', self._debounce( callback, debounceTimeout ) );

			// If we initialized hidden, show on first focus. The rest is up to you.
			if ( self.options.hide ) {
				input.on( 'focus', function() {
					self.show();
				} );
			}
		},
		setAlphaSliderBackground: function() {
			var color = this._color.toRgb(),
				gradient = [
					'rgb(' + color.r + ',' + color.g + ',' + color.b + ') 0%',
					'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0) 100%'
				];

			this.controls.aContainer.css( { 'background' : 'linear-gradient(to bottom, ' + gradient.join( ', ' ) + '), url(' + image + ')' } );
		}
	} );
}( jQuery ) );
