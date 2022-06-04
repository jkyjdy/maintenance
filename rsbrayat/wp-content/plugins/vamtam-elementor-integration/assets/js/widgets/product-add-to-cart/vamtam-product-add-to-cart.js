var VamtamProductAddToCart = elementorModules.frontend.handlers.Base.extend({
	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		if ( window.VAMTAM.bijouxCustomNumericInputs ) {
			window.VAMTAM.bijouxCustomNumericInputs();
		}
	}
});

jQuery( window ).on( 'elementor/frontend/init', () => {
	let addHandler;
	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamProductAddToCart, {
				$element,
			} );
		};
	} else {
		addHandler = elementorFrontend.elementsHandler.attachHandler( 'woocommerce-product-add-to-cart', VamtamProductAddToCart );
	}

	elementorFrontend.hooks.addAction( 'frontend/element_ready/woocommerce-product-add-to-cart.default', addHandler );
} );
