<?php
namespace BXGX\App;

if (!defined('ABSPATH')) {exit;}

class Route
{

    /**
     * init the hooks and classes
     */
    function __construct()
    {
        $cart = new Controllers\Frontend\Cart();
        $admin = new Controllers\Admin\Admin();
        $function = new Helpers\Functions();

        if (is_admin()) {
            add_filter('woocommerce_product_data_tabs', array($admin,'adminProductTab'));
            add_action('save_post', array($admin,'savePost'));
            add_action('woocommerce_product_data_panels', array($admin,'addPanel'));
            add_action('admin_enqueue_scripts', array($admin,'scriptFiles'));
            add_action( 'admin_notices', array($function,'woocommerceDeactivateError'));
        }
        add_action('woocommerce_before_calculate_totals', array($cart,'beforeCalculateTotals'));
        add_action('woocommerce_after_calculate_totals', array($cart,'afterCalculateTotals'));
        add_action('woocommerce_single_product_summary', array($cart,'productViewPage'));
        add_filter('woocommerce_cart_item_quantity', array($cart,'disableCartItemQuantity'),10,3);
        add_filter('woocommerce_cart_item_remove_link', array($cart,'removeCartItemLink'),10,2);

    }
}