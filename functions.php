<?php

/**
 * Child Theme Function
 *
 */


if( is_admin() )
    require 'class-cbdealer-settings.php';

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

function hook_javascript() {
    ?>
       <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118293806-3"></script>
      <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-118293806-3');
      </script> 
    <?php
}

add_action('wp_head', 'hook_javascript');

add_action('woocommerce_cart_totals_after_shipping', 'wc_shipping_insurance_note_after_cart');
add_action('woocommerce_review_order_after_shipping', 'wc_shipping_insurance_note_after_cart');
function wc_shipping_insurance_note_after_cart() {
    $settings = get_option('cbdealer_settings');
    $shipping_insurance = $settings['delivery_insurance_product_id'] ?? '';
    $express_shipping = $settings['express_delivery_product_id'] ?? '';
    $registered_letter = $settings['registered_delivery_product_id'] ?? '';
    
    if ( $shipping_insurance && !is_product_in_cart($shipping_insurance) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Shipping Insurance', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $shipping_insurance; ?>"><?php printf(esc_html__( 'Add for %s', 'mantis-child' ), get_product_price($shipping_insurance)); ?> </a></td>
        </tr>
    <?php endif;

    if ( $express_shipping && !is_product_in_cart($express_shipping) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Express shipping', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $express_shipping; ?>"><?php printf(esc_html__( 'Add for %s', 'mantis-child' ), get_product_price($express_shipping)); ?> </a></td>
        </tr>
    <?php endif;

    if ( $registered_letter && !is_product_in_cart($registered_letter) ):
    ?>
        <tr class="shipping">
            <th><?php _e( 'Registered delivery', 'mantis-child' ); ?></th>
            <td><a href="?add-to-cart=<?php echo $registered_letter; ?>"><?php printf(esc_html__( 'Add for %s', 'mantis-child' ), get_product_price($registered_letter)); ?> </a></td>
        </tr>
    <?php endif;
}

function get_product_price( $product_id ) {
    $product = wc_get_product( $product_id );
    return wc_price( floatval($product->get_price()), array( 'currency' => get_woocommerce_currency() ) );
}
function delivery_time() {
  echo '<tr><th>' . esc_html__( 'Delivery time', 'mantis-child' ) . '</th><td>3-5 ' . esc_html__( 'days', 'mantis-child' )  . '</td></tr>';
}
add_action( 'woocommerce_review_order_before_submit', 'add_checkout_privacy_policy', 9 );

function add_checkout_privacy_policy() {

    woocommerce_form_field( 'age_validation', array(
        'type'          => 'checkbox',
        'class'         => array('form-row privacy'),
        'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
        'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
        'required'      => true,
        'label'         => __('I agree, that I am at least 18 years old', 'mantis-child'),
    ));

    woocommerce_form_field( 'newsletter', array(
        'type'          => 'checkbox',
        'class'         => array('form-row newsletter'),
        'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
        'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
        'required'      => false,
        'label'         => __('I want to subscribe to the newsletter', 'mantis-child'),
    ));
}

// Show notice if customer does not tick

add_action( 'woocommerce_checkout_process', 'not_approved_privacy' );

function not_approved_privacy() {
    if ( ! (int) isset( $_POST['age_validation'] ) ) {
        wc_add_notice( __( 'Please validate your age', 'mantis-child' ), 'error' );
    }
}
add_action( 'woocommerce_cart_totals_after_shipping', 'delivery_time', 90);
add_action( 'woocommerce_review_order_after_shipping', 'delivery_time', 90);

add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1 );

function wc_npr_filter_phone( $address_fields ) {
    $address_fields['billing_phone']['required'] = false;
    return $address_fields;
}
