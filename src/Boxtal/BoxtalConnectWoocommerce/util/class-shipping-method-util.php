<?php
/**
 * Contains code for shipping method util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Shipping method util class.
 *
 * Helper to manage consistency between woocommerce versions shipping methods.
 *
 * @class       Shipping_Method_Util
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 * @category    Class
 * @author      API Boxtal
 */
class Shipping_Method_Util {

	/**
	 * Get unique instance identifier from shipping method (must be same as rate id).
	 *
	 * @param \WC_Shipping_Method $method woocommerce shipping method.
	 *
	 * @return string $key shipping method identifier
	 */
	public static function get_unique_identifier( $method ) {
		return $method->id . ':' . $method->instance_id;
	}

	/**
	 * Clean posted pricing items.
	 *
	 * @param array $pricing_items_raw posted pricing items.
	 *
	 * @return array $pricing_items cleaned pricing items
	 */
	public static function clean_pricing_items( $pricing_items_raw ) {
		$pricing_items = array();
		if ( null !== $pricing_items_raw ) {
			foreach ( $pricing_items_raw as $id => $pricing_item_raw ) {
				$pricing_items[ $id ] = Misc_Util::array_keys_strip_encoded_double_quotes( $pricing_item_raw );
			}
		}
		return $pricing_items;
	}

	/**
	 * Get existing shipping classes + "none" shipping class.
	 *
	 * @return array $shipping_classes shipping classes
	 */
	public static function get_shipping_class_list() {
		if ( method_exists( WC()->shipping, 'get_shipping_classes' ) ) {
			$shipping_class_list = WC()->shipping->get_shipping_classes();
		} else {
			$shipping_class_list = WC()->shipping->shipping_classes;
		}
		$shipping_classes = array();
		foreach ( $shipping_class_list as $class ) {
			$shipping_classes[ $class->slug ] = $class->name;
		}
		$shipping_classes['none'] = __( 'No shipping class', 'boxtal-connect' );
		return $shipping_classes;
	}

	/**
	 * If the deprecated parcel point field is used on a shipping method.
	 *
	 * @return boolean
	 */
	public static function is_used_deprecated_parcel_point_field() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_name like \"woocommerce_%_settings\"", ARRAY_A ); // db call ok.
		foreach ( $results as $option ) {
			if ( isset( $option['option_value'] ) ) {
				//phpcs:ignore
				$value = @unserialize($option['option_value']);
				if ( false === $value ) {
					continue;
				} elseif ( isset( $value['bw_parcel_point_networks'] ) && ! empty( $value['bw_parcel_point_networks'] ) ) {
					return true;
				}
			}
		}
		return false;
	}

}
