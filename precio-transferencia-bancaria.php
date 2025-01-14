<?php
/**
 *
 *
 * @link              https://alexistejada.com/
 * @package           Precio de Transferencia Bancaria
 * @author            Alexis Tejada
 * @wordpress-plugin
 * Plugin Name:       Precio de Transferencia Bancaria
 * Plugin URI:        https://alexistejada.com/precio-transferencia-bancaria-para-woocommerce
 * Description:       Precio de Transferencia Bancaria
 * Version:           0.1
 * Author:            Alexis Tejada
 * Author URI:        https://alexistejada.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       precio-transferencia-bancaria
 * Domain Path:       /language
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * WC tested up to:   9.3.3
 * * WC requires at least: 2.6
 */
if (!defined('ABSPATH')) {
    exit;
}

$plugin_precio_transferencia_version = get_file_data(__FILE__, array('Version' => 'Version'), false);

define('Version_Precio_Transferencia', $plugin_precio_transferencia_version['Version']);

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );


function at_func_transferencia_load_textdomain()
{
    load_plugin_textdomain('precio-transferencia-bancaria', false, basename(dirname(__FILE__)) . '/language/');
}

add_action('init', 'at_func_transferencia_load_textdomain');


/*
 * ADMIN
 */
require dirname(__FILE__) . "/at_transferencia_admin.php";

/*
 * CHECKOUT
 */
require dirname(__FILE__) . "/at_transferencia_checkout.php";
