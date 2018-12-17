<?php

/**
 * Child Theme Function
 *
 */

add_action( 'after_setup_theme', 'mantis_child_theme_setup' );
add_action( 'wp_enqueue_scripts', 'mantis_child_enqueue_styles', 20);

if( !function_exists('mantis_child_enqueue_styles') ) {
    function mantis_child_enqueue_styles() {
        wp_enqueue_style( 'mantis-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( 'mantis-theme' ),
            wp_get_theme()->get('Version')
        );

    }
}

if( !function_exists('mantis_child_theme_setup') ) {
    function mantis_child_theme_setup() {
        load_child_theme_textdomain( 'mantis-child', get_stylesheet_directory() . '/languages' );
    }
}

add_action('woocommerce_cart_totals_after_shipping', 'wc_shipping_insurance_note_after_cart');
function wc_shipping_insurance_note_after_cart() {
global $woocommerce;
    $product_id = 661;
foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
    $_product = $values['data'];
    if ( $_product->id == $product_id )
        $found = true;
    }
    // if product not found, add it
if ( ! $found ):
?>
    <tr class="shipping">
        <th><?php _e( 'Shipping Insurance', 'mantis-child' ); ?></th>
        <td><a href="<?php echo do_shortcode('[add_to_cart_url id="661"]'); ?>"><?php _e( 'Add shipping insurance', 'mantis-child' ); ?> </a></td>
    </tr>
<?php endif;
}

