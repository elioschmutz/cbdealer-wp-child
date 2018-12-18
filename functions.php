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

function is_product_in_cart($product_id) {
    global $woocommerce;
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
        $_product = $values['data'];
        if ( $_product->id == $product_id )
            return true;
        }
   return false;
}


add_action('woocommerce_cart_totals_after_shipping', 'wc_shipping_insurance_note_after_cart');
add_action('woocommerce_review_order_after_shipping', 'wc_shipping_insurance_note_after_cart');
function wc_shipping_insurance_note_after_cart() {
    $shipping_insurance = 661;
    $express_shipping = 668;
    $registered_letter = 664;

    if ( ! is_product_in_cart($shipping_insurance) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Shipping Insurance', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $shipping_insurance; ?>"><?php _e( 'Add shipping insurance', 'mantis-child' ); ?> </a></td>
        </tr>
    <?php endif;

    if ( ! is_product_in_cart($express_shipping) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Express shipping', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $express_shipping; ?>"><?php _e( 'Add express shipping', 'mantis-child' ); ?> </a></td>
        </tr>
    <?php endif;

    if ( ! is_product_in_cart($registered_letter) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Registered letter', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $registered_letter; ?>"><?php _e( 'Add registered letter', 'mantis-child' ); ?> </a></td>
        </tr>
    <?php endif;
}

