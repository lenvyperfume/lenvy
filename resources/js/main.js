/**
 * Lenvy — main entry point.
 *
 * Vite compiles this file (and everything it imports) into assets/build/.
 */

import '../css/tailwind.css';
import '../scss/main.scss';

// ─── Mobile menu toggle ────────────────────────────────────────────────────

document.addEventListener( 'DOMContentLoaded', () => {
	const toggle   = document.querySelector( '[data-mobile-menu-toggle]' );
	const menu     = document.getElementById( 'mobile-menu' );
	const iconOpen  = document.getElementById( 'icon-open' );
	const iconClose = document.getElementById( 'icon-close' );

	if ( ! toggle || ! menu ) return;

	toggle.addEventListener( 'click', () => {
		const isOpen = menu.classList.toggle( 'hidden' ) === false;

		toggle.setAttribute( 'aria-expanded', String( isOpen ) );
		menu.setAttribute( 'aria-hidden', String( ! isOpen ) );

		if ( iconOpen )  iconOpen.classList.toggle( 'hidden', isOpen );
		if ( iconClose ) iconClose.classList.toggle( 'hidden', ! isOpen );
	} );

	// Close on outside click.
	document.addEventListener( 'click', ( e ) => {
		if ( ! menu.classList.contains( 'hidden' ) && ! toggle.contains( e.target ) && ! menu.contains( e.target ) ) {
			menu.classList.add( 'hidden' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-hidden', 'true' );
			if ( iconOpen )  iconOpen.classList.remove( 'hidden' );
			if ( iconClose ) iconClose.classList.add( 'hidden' );
		}
	} );
} );

// ─── WooCommerce — cart quantity update on change ─────────────────────────

document.addEventListener( 'DOMContentLoaded', () => {
	document.querySelectorAll( 'input.qty' ).forEach( ( input ) => {
		input.addEventListener( 'change', () => {
			const form = input.closest( 'form.woocommerce-cart-form' );
			if ( form ) {
				const updateBtn = form.querySelector( '[name="update_cart"]' );
				if ( updateBtn ) updateBtn.disabled = false;
			}
		} );
	} );
} );
