<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

function at_func_price_html($price_html, $product)
{
    global $woocommerce_loop;

    if (is_product() && !$woocommerce_loop['name'] == 'related') {
        $precio_transferencia = get_post_meta($product->get_id(), 'transferencia_price_field_at', true);
        $simbolo = get_woocommerce_currency_symbol();
        if ($precio_transferencia) {
            $price_html .= '<div class="TFP">
                <div class="TF"> '. get_option('at_text_transferencia_price_html') .'  <br></div>
                <p class="price product-page-price price-on-sale"> ' . $simbolo . number_format($precio_transferencia, 2, '.', ',')  . ' </p>
                </div>
            ';
        }
    }
    return $price_html;
}

add_filter('woocommerce_get_price_html', 'at_func_price_html', 9, 2);

function at_func_able_woocommerce_loading_css_js()
{
    // Check if WooCommerce plugin is active
    if (function_exists('is_woocommerce')) {
        // Check if it's any of WooCommerce page
        if (is_checkout()) {
            wp_register_script('js_precio_transferencia_checkout-js', plugins_url('js/js_precio_transferencia_checkout.js', __FILE__), array(), Version_Precio_Transferencia, true);
            wp_enqueue_script('js_precio_transferencia_checkout-js');
        }
    }
}

add_action('wp_enqueue_scripts', 'at_func_able_woocommerce_loading_css_js', 99);


function at_func_checkout_update_refresh($post_data)
{
    if (!WC()->session->__isset("reload_checkout")) {
        if (is_page('cart') || is_cart() || is_checkout() || is_page('checkout')) {

            // This is necessary for WC 3.0+
            if (is_admin() && !defined('DOING_AJAX'))
                return;

            $cart = WC()->cart;
            $post = array();
            $vars = explode('&', $post_data);
            foreach ($vars as $k => $value) {
                $v = explode('=', urldecode($value));
                $post[$v[0]] = $v[1];
            }
            $payment_method = $post['payment_method'];

            foreach ($cart->get_cart() as $cart_item) {
                if ($payment_method == "bacs" || $payment_method == 'cod') {
                    $precio_transferencia = get_post_meta($cart_item['product_id'], 'transferencia_price_field_at', true);
                    if ($precio_transferencia > 0) {
                        $cart_item['data']->set_price($precio_transferencia);
                    }
                }
            }
        }
    }
}

add_action('woocommerce_checkout_update_order_review', 'at_func_checkout_update_refresh', 10, 1);

function at_func_checkout_calculate_totals($cart)
{
    if (!WC()->session->__isset("reload_checkout")) {
        if (is_page('cart') || is_cart() || is_checkout() || is_page('checkout')) {

            // This is necessary for WC 3.0+
            if (is_admin() && !defined('DOING_AJAX'))
                return;

            $chosen_payment_method_id = WC()->session->get('chosen_payment_method');

            foreach ($cart->get_cart() as $cart_item) {
                if ('bacs' === $chosen_payment_method_id || 'cod' === $chosen_payment_method_id) {
                    $precio_transferencia = get_post_meta($cart_item['product_id'], 'transferencia_price_field_at', true);
                    if ($precio_transferencia > 0) {
                        $cart_item['data']->set_price($precio_transferencia);
                    }
                }
            }
        }
    }
}

add_action('woocommerce_before_calculate_totals', 'at_func_checkout_calculate_totals');