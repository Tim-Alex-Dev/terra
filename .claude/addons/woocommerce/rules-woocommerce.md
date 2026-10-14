---
paths:
  - "woocommerce/**"
  - "inc/woo.php"
  - "assets/scss/3-vendors/woocommerce/**"
  - "assets/js/modules/woocommerce.js"
---

# WooCommerce rules

- The `@version` header of an override must be the WooCommerce template version it is really based on. Never bump it without porting the core changes.
- Outdated and heavily customized (based on 7.0.1 / 3.5.1, core is 11.0.0 / 11.1.0): `cart/cart.php`, `single-product/product-image.php`. Porting them needs a real WooCommerce site to test (quantity handling, gallery markup), so leave them until one is available.
- To port an override: fetch the current core template (github.com/woocommerce/woocommerce, `plugins/woocommerce/templates`), diff it against the override, port the core changes and keep the theme customizations (Swiper markup, custom layout classes).
- Guard everything that depends on the cart or WooCommerce being loaded (`WC()->cart` can be null).
- The product gallery (`.swiper-product-image` + `.swiper-product-thumbs`) and product carousels (`.swiper-products`) are initialised in `assets/js/modules/woocommerce.js`.
