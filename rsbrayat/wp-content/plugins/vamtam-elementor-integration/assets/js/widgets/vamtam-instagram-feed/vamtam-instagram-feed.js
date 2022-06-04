class VamtamInstaFeed extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.elementor-widget-container',
				widget: '.elementor-widget-container',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$container: this.$element.find( selectors.container ),
			$widget: this.$element.find( selectors.widget ),
		};
	}

	onInit( ...args ) {
		super.onInit( ...args );
		this.VamtamInstaFeedPopupHandler(this.$element);
	}

	getElementSettings( $element ) {
		var elementSettings = {},
			modelCID 		= $element.data( 'model-cid' );

		const isEditMode = !jQuery( 'body' ).hasClass( 'elementor-editor-active' );


		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}

		return elementSettings;
	}

	VamtamInstaFeedPopupHandler( $scope ) {
		if ( ! $scope ) {
			return;
		}

		var widgetId        = $scope.data('id'),
			elementSettings = this.getElementSettings( $scope ),
			layout          = elementSettings.feed_layout;

		if ( layout === 'carousel' ) {
			var carousel      = $scope.find('.swiper-container').eq(0),
				sliderOptions = JSON.parse( carousel.attr('data-slider-settings') ),
				mySwiper      = new Swiper(carousel, sliderOptions);
		} else if ( layout === 'masonry' ) {
			var grid = jQuery('#vamtam-instafeed-' + widgetId).imagesLoaded( function() {
				grid.masonry({
					itemSelector: '.vamtam-feed-item',
					percentPosition: true
				});
			});
		}
	}
}


jQuery( window ).on( 'elementor/frontend/init', () => {
	let addHandler;
	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamInstaFeed, {
				$element,
			} );
		};
	} else {
		addHandler = elementorFrontend.elementsHandler.attachHandler( 'vamtam-instafeed', VamtamInstaFeed );
	}

	elementorFrontend.hooks.addAction( 'frontend/element_ready/vamtam-instafeed.default', addHandler, 100 );
} );