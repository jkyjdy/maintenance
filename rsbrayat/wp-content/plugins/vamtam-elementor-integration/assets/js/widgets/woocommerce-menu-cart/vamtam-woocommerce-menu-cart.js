var VamtamWCMenuCart = elementorModules.frontend.handlers.Base.extend({
	onInit: function onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
		this.menuCartFooterBtnsBijouxBtnType();
	},
	// Bijoux-only.
	menuCartFooterBtnsBijouxBtnType: function () {
		if ( ! this.$element.hasClass( 'vamtam-has-bijoux-alt') ) {
			return;
		}

		var $footerBtns = this.$element.find( '.elementor-menu-cart__footer-buttons' ),
			viewCartBtn = $footerBtns.find( '.elementor-button--view-cart' ),
			checkoutBtn = $footerBtns.find( '.elementor-button--checkout' ),
			linePrefix  = '<span class="vamtam-prefix"></span>';

		if ( ! viewCartBtn.find( '.vamtam-prefix' ).length ) {
			viewCartBtn.prepend( linePrefix );
		}
		if ( ! checkoutBtn.find( '.vamtam-prefix' ).length ) {
			checkoutBtn.prepend( linePrefix );
		}
	},
});

jQuery( window ).on( 'elementor/frontend/init', () => {
	let addHandler;
	if ( ! elementorFrontend.elementsHandler || ! elementorFrontend.elementsHandler.attachHandler ) {
		addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamWCMenuCart, {
				$element,
			} );
		};
	} else {
		addHandler = elementorFrontend.elementsHandler.attachHandler( 'woocommerce-menu-cart', VamtamWCMenuCart );
	}

	elementorFrontend.hooks.addAction( 'frontend/element_ready/woocommerce-menu-cart.default', addHandler );
} );
