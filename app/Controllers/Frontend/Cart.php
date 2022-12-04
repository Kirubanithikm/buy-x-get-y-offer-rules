<?php
/**
 * Buy x get x product for woocommerce
 *
 * @package   buy-x-get-x-woocommerce
 * @author    Kirubanithi G <kirubanithikm@gmail.com>
 * @license   GPL-3.0-or-later
 */

namespace BXGX\App\Controllers\Frontend;
use BXGX\App\Helpers\Functions;

if (!defined('ABSPATH')) {exit;}

class Cart
{
    /**
     * @hooked woocommerce_after_calculate_totals hook
     * @param $cart_object
     * @return void
     * @throws \Exception
     */
    function afterCalculateTotals($cart_object)
    {
        self::checkCartItems($cart_object);
        self::removeChildProduct();
    }

    /**
     * @hooked woocommerce_before_calculate_totals hook
     * @param $cart_object
     * @return void
     */
    function beforeCalculateTotals($cart_object)
    {
        self::changeCartItemPrice($cart_object);
        self::updateChildQty();
    }

    /**
     * Check the buy product conditions and add the get product in cart
     * @hooked woocommerce_after_calculate_totals hook
     * @param $cart_object
     * @return void
     * @throws \Exception
     */
    function checkCartItems($cart_object)
    {
        global $buy_item_key;
        foreach ($cart_object->cart_contents as $buy_item_key => $buy_item) {
            if (isset($buy_item['buy_item_key'])) { // is a get item
                continue; // skip
            }
            foreach ($cart_object->cart_contents as $get_item_key => $get_item) {
                if (isset($get_item['buy_item_key']) && $get_item['buy_item_key'] == $buy_item_key) {
                    continue 2; //get item set? && get item not equal to buy item
                }
            }
            $product_id = $buy_item['product_id'];
            $bxgx_data = get_post_meta($product_id, '_bxgx_data', true);
            if (!empty($bxgx_data) && isset($bxgx_data['enabled']) && $bxgx_data['enabled'] == "yes") {
                WC()->cart->add_to_cart($product_id, 1, 0, array(), array('buy_item_key' => $buy_item_key));
            }
        }
    }

    /**
     * Check the get product type and change the price
     * @hooked woocommerce_before_calculate_totals hook
     * @param $cart_object
     * @return void
     */
    function changeCartItemPrice($cart_object)
    {
        foreach ($cart_object->cart_contents as $key => $cart_item) {
            if (isset($cart_item['buy_item_key'])) {
                $product_id = $cart_item['product_id'];
                $bxgx_data = get_post_meta($product_id, '_bxgx_data', true);
                if (!empty($bxgx_data)) {
                    $enabled = $bxgx_data['enabled'];
                    $discount_type = $bxgx_data['discount_type'];
                    $discount_value = $bxgx_data['discount_value'];

                    if ($enabled == "yes" && $discount_type == "bxgx_free") {
                        $cart_item['data']->set_price(0);
                    } elseif ($enabled == "yes" && $discount_type == "bxgx_fixed_price" && $discount_value != null) {
                        $current_price = get_post_meta($cart_item['product_id'], '_price', true);
                        $cart_item['data']->set_price($current_price - $discount_value);
                    } elseif ($enabled == "yes" && $discount_type == "bxgx_percentage" && $discount_value != null) {
                        $current_price = get_post_meta($cart_item['product_id'], '_price', true);
                        $percentage_discount_price = $current_price - ($current_price * $discount_value / 100);
                        $cart_item['data']->set_price($percentage_discount_price);
                    } else {
                        wc_add_notice( __( 'Sorry there was a problem to add buy x get x.', 'woocommerce' ), 'error' );
                    }
                }
            }
        }
    }

    /**
     * Disable the quantity item for the child product
     * @hooked woocommerce_cart_item_quantity
     * @param $product_quantity
     * @param $cart_item_key
     * @param $cart_item
     * @return mixed|string
     */
    function disableCartItemQuantity($product_quantity, $cart_item_key, $cart_item)
    {
        if (isset($cart_item['buy_item_key'])) {
            $product_quantity = sprintf(
                '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />',
                $cart_item_key,
                $cart_item['quantity']
            );
        }
        return $product_quantity;
    }

    /**
     * Disable cart item remove link for child product
     * @hooked woocommerce_cart_item_remove_link
     * @param $button_link
     * @param $cart_item_key
     * @return mixed|string
     */
    function removeCartItemLink($button_link, $cart_item_key)
    {
        $cart_item = WC()->cart->get_cart()[$cart_item_key];
        if (isset($cart_item['buy_item_key'])) {
            $button_link = '';
        }
        return $button_link;
    }

    /**
     * Remove child product when parent product is removed
     * @hooked woocommerce_after_calculate_totals
     * @return void
     */
    function removeChildProduct()
    {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

            if(!isset($cart_item['buy_item_key'])) {
                $parent_key = $cart_item['key'];
            }
            if (isset($cart_item['buy_item_key'])) {
                $child_key = $cart_item['buy_item_key'];
                if($child_key != $parent_key){
                    WC()->cart->remove_cart_item($cart_item_key);
                }
            }
        }
    }

    /**
     * Update the child product quantity value based on the parent product quantity value
     * @hooked woocommerce_before_calculate_totals hook
     * @return void
     */
    function updateChildQty()
    {
        foreach (WC()->cart->get_cart() as $parent_item) {
            if(isset($parent_item['buy_item_key'])) continue;
            foreach (WC()->cart->get_cart() as $child_item) {
                if(isset($child_item['buy_item_key']) && $parent_item['product_id'] == $child_item['product_id'] && $parent_item['quantity'] != $child_item['quantity']){
                    WC()->cart->set_quantity($child_item['key'], $parent_item['quantity']);
                }
            }
        }
    }

    /**
     * Product details - view file
     * @hooked woocommerce_single_product_summary
     * @return void
     */
    function productViewPage()
    {
        global $post;
        $bxgx_data = get_post_meta($post->ID, '_bxgx_data', true);
        if (!empty($bxgx_data)) {
            $data = [
                'enabled' => $bxgx_data['enabled'],
                'discount_type' => $bxgx_data['discount_type'],
                'discount_value' => $bxgx_data['discount_value'],
            ];
            Functions::view('Frontend/ProductView',$data,true);
        }
    }
}