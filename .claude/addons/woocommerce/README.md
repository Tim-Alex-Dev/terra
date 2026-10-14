# Add WooCommerce support to a project

WooCommerce is not part of the starter. This folder keeps everything needed to add it. `.claude/` is never built or deployed.

1. Copy everything from `files/` into the theme root (same paths): `woocommerce/` (template overrides), `inc/woo.php`,
   `assets/scss/3-vendors/woocommerce/`, `assets/scss/3-vendors/_select2.scss`, `assets/js/modules/woocommerce.js`.
2. Copy `rules-woocommerce.md` to `.claude/rules/woocommerce.md`.
3. Add the snippets below to the shared files.
4. Verify: `php -l` the edited files, `npm run lint`, `gulp prod`. Check the `@version` of the template overrides against the installed WooCommerce.

## functions.php (after the other `require` lines)
```php
if ( class_exists( 'WooCommerce' ) ) {
	require IT_DIR . '/inc/woo.php'; // Woocommerce functions
}
```

## header.php (inside `.site-header > .container`, before the burger button)
```php
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
			<a class="mini-cart <?php echo ( 0 === $count ) ? 'mini-cart--empty' : ''; ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<svg>
					<use xlink:href="#cart"></use>
				</svg>
				<?php if ( 0 !== $count ) : ?>
					<span class="mini-cart__total"><?php echo absint( $count ); ?></span>
				<?php endif; ?>
			</a>
			<a class="user-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
				<svg id="user-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="36" height="36">
					<path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm128 421.6c-35.9 26.5-80.1 42.4-128 42.4s-92.1-15.9-128-42.4V416c0-35.3 28.7-64 64-64 11.1 0 27.5 11.4 64 11.4 36.6 0 52.8-11.4 64-11.4 35.3 0 64 28.7 64 64v13.6zm30.6-27.5c-6.8-46.4-46.3-82.1-94.6-82.1-20.5 0-30.4 11.4-64 11.4S204.6 320 184 320c-48.3 0-87.8 35.7-94.6 82.1C53.9 363.6 32 312.4 32 256c0-119.1 96.9-216 216-216s216 96.9 216 216c0 56.4-21.9 107.6-57.4 146.1zM248 120c-48.6 0-88 39.4-88 88s39.4 88 88 88 88-39.4 88-88-39.4-88-88-88zm0 144c-30.9 0-56-25.1-56-56s25.1-56 56-56 56 25.1 56 56-25.1 56-56 56z"/>
				</svg>
			</a>
		<?php endif; ?>
```

## template-parts/svg.php (cart icon of the mini cart)
```html
	<symbol id="cart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M551.991 64H129.28l-8.329-44.423C118.822 8.226 108.911 0 97.362 0H12C5.373 0 0 5.373 0 12v8c0 6.627 5.373 12 12 12h78.72l69.927 372.946C150.305 416.314 144 431.42 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-17.993-7.435-34.24-19.388-45.868C506.022 391.891 496.76 384 485.328 384H189.28l-12-64h331.381c11.368 0 21.177-7.976 23.496-19.105l43.331-208C578.592 77.991 567.215 64 551.991 64zM240 448c0 17.645-14.355 32-32 32s-32-14.355-32-32 14.355-32 32-32 32 14.355 32 32zm224 32c-17.645 0-32-14.355-32-32s14.355-32 32-32 32 14.355 32 32-14.355 32-32 32zm38.156-192H171.28l-36-192h406.876l-40 192z"/></symbol>
```

## assets/js/main.js (in the `loadModules({...})` list)
```js
	'body.woocommerce, .woocommerce, .swiper-products': () => import(/* webpackChunkName: "woocommerce" */ './modules/woocommerce'),
```

## assets/js/components/_custom.js (Nice Select must not replace the Select2 fields of the account page)
Replace `$('select').niceSelect();` with:
```js
	if (!$('body').hasClass('woocommerce-account')) {
		$('select').niceSelect();
	}
```

## assets/scss/main.scss (after `@import "3-vendors/*";`)
```scss
// WooCommerce custom styles
@import "3-vendors/woocommerce/*";
```

## assets/scss/admin-styles.scss (end of file)
```scss
// Disable Woocommerce Header in WP Admin
.woocommerce-layout__header {
	display: none;
}

.woocommerce-layout__activity-panel-tabs {
	display: none;
}

.woocommerce-layout__header-breadcrumbs {
	display: none;
}

.woocommerce-embed-page .woocommerce-layout__primary {
	display: none;
}

.woocommerce-embed-page #screen-meta,
.woocommerce-embed-page #screen-meta-links {
	top: 0;
}
```

## gulpfile.js (optional: BrowserSync reloads on template changes, `files` list)
```js
'./woocommerce/**/*.php'
```

## Template overrides: status

Overrides are in `woocommerce/`; the `@version` header shows the WooCommerce template version each one is based on.

* Up to date with WooCommerce 11.1: `checkout/form-checkout.php`, `checkout/form-coupon.php`, `content-product.php`, `content-single-product.php`, `loop/*`, `single-product/related.php`, `single-product/up-sells.php`.
* **Outdated (customized, must be ported and tested on a real shop):** `cart/cart.php` (based on 7.0.1, core is 11.0.0) and `single-product/product-image.php` (based on 3.5.1, core is 11.1.0).
