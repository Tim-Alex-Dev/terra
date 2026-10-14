<?php
/**
 * Declares WooCommerce theme support.
 *
 * @link https://www.businessbloomer.com/category/woocommerce-tips/visual-hook-series/
 */

add_action( 'after_setup_theme', 'it_woocommerce_support' );
function it_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/**
 * Shop page
 */
add_action( 'woocommerce_before_shop_loop', 'it_shop_heading_start', 17 );
function it_shop_heading_start() {
	echo '<header class="shop-heading">';
}

add_action( 'woocommerce_before_shop_loop', 'it_shop_heading_end', 32 );
function it_shop_heading_end() {
	echo '</header>';
}

/**
 * Add loop product image wrapper
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'it_template_loop_product_thumbnail_wrap_start', 8 );
function it_template_loop_product_thumbnail_wrap_start() {
	echo '<div class="woocommerce-loop-product__thumbnail">';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'it_template_loop_product_thumbnail_wrap_end', 12 );
function it_template_loop_product_thumbnail_wrap_end() {
	echo '</div>';
}

/**
 * Change loop product title tag
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'it_change_products_title', 10 );
function it_change_products_title() {
	echo '<h3 class="h5 woocommerce-loop-product__title">' . wp_kses_post( get_the_title() ) . '</h3>';
}

/**
 * Add minus button before quantity
 */
add_action( 'woocommerce_before_quantity_input_field', 'it_display_quantity_minus' );
function it_display_quantity_minus() {
	echo '<button type="button" class="btn-qty btn-qty__minus"><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 18 18"><path d="M9 .25a8.75 8.75 0 10.001 17.501A8.75 8.75 0 009 .25zm3.75 9.219c0 .086-.07.156-.156.156H5.406a.157.157 0 01-.156-.156V8.53c0-.086.07-.156.156-.156h7.188c.086 0 .156.07.156.156v.938z"/></svg></button>';
}

/**
 * Add plus button after quantity
 */
add_action( 'woocommerce_after_quantity_input_field', 'it_display_quantity_plus', 100 );
function it_display_quantity_plus() {
	echo '<button type="button" class="btn-qty btn-qty__plus"><svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 18 18"><path d="M9 .25a8.75 8.75 0 10.001 17.501A8.75 8.75 0 009 .25zm3.75 9.219c0 .086-.07.156-.156.156H9.625v2.969c0 .086-.07.156-.156.156H8.53a.157.157 0 01-.156-.156V9.625H5.406a.157.157 0 01-.156-.156V8.53c0-.086.07-.156.156-.156h2.969V5.406c0-.086.07-.156.156-.156h.938c.086 0 .156.07.156.156v2.969h2.969c.086 0 .156.07.156.156v.938z"/></svg></button>';
}

/**
 * Mini-cart update
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'it_header_add_to_cart_fragment', 30, 1 );
function it_header_add_to_cart_fragment( $fragments ) {
	ob_start();

	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<a class="mini-cart <?php echo ( 0 === $count ) ? 'mini-cart--empty' : ''; ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
		<svg>
			<use xlink:href="#cart"></use>
		</svg>
		<?php if ( 0 !== $count ) : ?>
			<span class="mini-cart__total"><?php echo absint( $count ); ?></span>
		<?php endif; ?>
	</a>
	<?php
	$fragments['a.mini-cart'] = ob_get_clean();

	return $fragments;
}
