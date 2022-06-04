class VamtamProductImages extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.elementor-widget-container',
				widget: '.elementor-widget-container',
				gallery: '.woocommerce-product-gallery, .woocommerce-product-gallery--vamtam',
				dummy: '.woocommerce-product-gallery--vamtam',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$container: this.$element.find( selectors.container ),
			$widget: this.$element.find( selectors.widget ),
			$gallery: this.$element.find( selectors.gallery ),
			$dummy: this.$element.find( selectors.dummy ),
		};
	}

	onInit( ...args ) {
		super.onInit( ...args );
		this.wcFlexsliderHack();
		this.handleProductImage();
        this.reInitWCProductGallery();
	}

	reInitWCProductGallery() {
		// We only need to do that in the case of a full-sized gallery,
		// with WC's lightbox enabled.
		const wcLightboxActive = jQuery( 'body' ).hasClass( 'wc-product-gallery-lightbox-active' ),
            isFullSizedGallery = this.$element.hasClass( 'vamtam-has-full-sized-gallery' );

		if ( ! wcLightboxActive || ! isFullSizedGallery ) {
			return;
		}

		const galleryParams = {
			...wc_single_product_params,
			flexslider_enabled: false, // No flexslider full-size gallery (would break the layout).
			zoom_enabled: false, // No WC zoom in full-size gallery (doesnt really make sense and also needs different html to support that).
		};

		this.elements.$gallery.trigger( 'wc-product-gallery-before-init', [ this, galleryParams ] );
		this.elements.$gallery.wc_product_gallery( galleryParams );
		this.elements.$gallery.trigger( 'wc-product-gallery-after-init', [ this, galleryParams ] );
	}

	wcFlexsliderHack() {
		if ( ! this.elements.$dummy.length ) {
			return;
		}

		this.elements.$gallery.removeClass( 'woocommerce-product-gallery--vamtam' );
		this.elements.$gallery.addClass( 'woocommerce-product-gallery' );
		this.elements.$gallery.css( 'opacity', '1' );
	}

	handleProductImage() {
		this.handleDisableLinkOption();
		this.handleDoubleLightbox();
		this.handleWcZoomElementorLightBoxConflict();
	}

	handleWcZoomElementorLightBoxConflict() {
		const wcZoomActive = jQuery( 'body' ).hasClass( 'wc-product-gallery-zoom-active' );

		if ( ! wcZoomActive ) {
			return;
		}

		const elementorLightboxActive = elementorFrontend.getKitSettings( 'global_image_lightbox' );

		if ( ! elementorLightboxActive ) {
			return;
		}

		const onZoomedImgClick = function ( e ) {
			const link = jQuery( e.target ).siblings( 'a' );
			if ( link.length ) {
				link.click(); // Open Elementor Lightbox.
			}
		}

		jQuery( document ).on( 'click', '.woocommerce-product-gallery__image img.zoomImg', onZoomedImgClick );
	}

	handleDoubleLightbox() {
		const wcLightboxActive = jQuery( 'body' ).hasClass( 'wc-product-gallery-lightbox-active' );

		if ( ! wcLightboxActive ) {
			return;
		}

		const elementorLightboxActive =  elementorFrontend.getKitSettings( 'global_image_lightbox' );

		if ( ! elementorLightboxActive ) {
			return;
		}

		// Both are enabled. WC's is explicit (added by add_theme_supports) but Elementor's
		// is implicit (by global Elementor option), thus we prioritize WC's.
		this.disableImageLinks( wcLightboxActive );
	}

	disableImageLinks( wcLightboxActive = false ) {
		const links = this.$element.find( 'a > img' ).parent();

		if ( ! links.length ) {
			return;
		}

		jQuery.each( links, function ( i, link ) {
			if ( wcLightboxActive ) {
				// Just disable Elementor's lightbox. We need the pointer-events for WC's lightbox.
				jQuery( link ).attr( 'data-elementor-open-lightbox', 'no' );
			} else {
				// Remove the link's href (no pointer-events/linking to the image/lightbox).
				jQuery( link ).removeAttr( 'href' );
			}
		} );
	}

	handleDisableLinkOption() {
		if ( ! this.$element.hasClass( 'vamtam-has-disable-image-link' ) ) {
			return;
		}

		const wcLightboxActive = jQuery( 'body' ).hasClass( 'wc-product-gallery-lightbox-active' );
		this.disableImageLinks( wcLightboxActive );
	}
}


jQuery( window ).on( 'elementor/frontend/init', () => {
	let addHandler;
	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamProductImages, {
				$element,
			} );
		};
	} else {
		addHandler = elementorFrontend.elementsHandler.attachHandler( 'woocommerce-product-images', VamtamProductImages );
	}

	elementorFrontend.hooks.addAction( 'frontend/element_ready/woocommerce-product-images.default', addHandler, 100 );
} );
