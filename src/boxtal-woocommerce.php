<?php
/**
 * Plugin Name: Boxtal Woocommerce
 * Plugin URI: https://github.com/boxtal/boxtal-woocommerce-poc
 * Description: Manage multiple carriers using one single plugin and reduce your shipping costs without commitments or any contract to sign.
 * Author: API Boxtal
 * Author URI: https://www.boxtal.com
 * Text Domain: boxtal
 * Domain Path: /languages
 * Version: 0.1.0
 *
 * @package Boxtal\BoxtalWoocommerce
 */

use Boxtal\BoxtalWoocommerce\Plugin;
use Boxtal\BoxtalWoocommerce\Api\Order_Sync;
use Boxtal\BoxtalWoocommerce\Admin\Notices;
use Boxtal\BoxtalWoocommerce\Config\Environment_Check;

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

require_once trailingslashit( __DIR__ ) . 'Boxtal/BoxtalWoocommerce/autoload/autoloader.php';

add_action( 'plugins_loaded', 'boxtal_woocommerce_init' );
/**
 * Plugin initialization.
 *
 * @void
 */
function boxtal_woocommerce_init() {
	$plugin                      = new Plugin(); // Create container.
	$plugin['path']              = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	$plugin['url']               = plugin_dir_url( __FILE__ );
	$plugin['version']           = '0.1.0';
	$plugin['min-wc-version']    = '2.3.0';
	$plugin['min-php-version']   = '5.3.0';
	$plugin['notices']           = 'boxtal_woocommerce_init_admin_notices';
	$plugin['check-environment'] = 'boxtal_woocommerce_check_environment';
	$plugin['api-order-sync']    = 'boxtal_woocommerce_service_api_order_sync';
	$plugin->run();
}


/**
 * Check PHP version, WC version.
 *
 * @param array $plugin plugin array.
 * @return Environment_Check $environment_check static environment check instance.
 */
function boxtal_woocommerce_check_environment( $plugin ) {
	static $object;

	if ( null !== $object ) {
		return $object;
	}

	$object = new Environment_Check( $plugin );
	return $object;
}

/**
 * Get new Order_Sync instance.
 *
 * @return Order_Sync $order_sync
 */
function boxtal_woocommerce_service_api_order_sync() {
	static $object;

	if ( null !== $object ) {
		return $object;
	}

	$object = new Order_Sync();
	return $object;
}

/**
 * Start admin notice singleton.
 *
 * @param array $plugin plugin array.
 * @return string $message
 */
function boxtal_woocommerce_init_admin_notices( $plugin ) {
	static $object;

	if ( null !== $object ) {
		return $object;
	}

	$object = new Notices( $plugin );
	return $object;
}
