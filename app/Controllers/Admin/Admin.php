<?php
/**
 * Buy x get x product for woocommerce
 *
 * @package   buy-x-get-x-woocommerce
 * @author    Kirubanithi G <kirubanithikm@gmail.com>
 * @license   GPL-3.0-or-later
 */

namespace BXGX\App\Controllers\Admin;
use BXGX\App\Helpers\Functions;

if (!defined('ABSPATH')) {exit;}

class Admin
{

    /**
     * Create admin product tab
     * @hooked woocommerce_product_data_tabs
     * @param $tab
     * @return mixed
     */
    function adminProductTab($tab)
    {
        $tab['_bxgx_data'] = array(
            'label' => __('Buy x get x', 'woocommerce'),
            'target' => '_bxgx_data',
            'class' => array(),
            'priority' => 30,
        );
        return $tab;
    }

    /**
     * Update or save data as array in db using meta key
     * @hooked save_post
     * @return void
     */
    function savePost()
    {
        global $post;
        $bxgx_data = [
            'enabled' => isset($_POST['buy_get_product']) ? sanitize_textarea_field($_POST['buy_get_product']) : null,
            'discount_type' => isset($_POST['buy_get_discount_type']) ? sanitize_textarea_field($_POST['buy_get_discount_type']) : null,
            'discount_value' => isset($_POST['buy_get_discount_value']) ? sanitize_textarea_field($_POST['buy_get_discount_value']) : null,
        ];
        update_post_meta($post->ID, "_bxgx_data", $bxgx_data);
    }

    /**
     * Admin panel - view file
     * @hooked woocommerce_product_data_panels
     * @return void
     */
    function addPanel()
    {
        $data=[];
        Functions::view('Admin/Panels',$data,true);
    }

    /**
     * Load the script and style
     * @hooked admin_enqueue_scripts
     * @return void
     */
    function scriptFiles()
    {
        wp_register_style(
            'bg_add_css',
            trailingslashit(BXGX_PLUGIN_URL) . 'assets/css/bxgx-style.css');
        wp_enqueue_style('bg_add_css');

        wp_enqueue_script(
            'bg_add_js',
            trailingslashit(BXGX_PLUGIN_URL) . 'assets/js/bxgx-index.js',
            array('jquery'));
        wp_enqueue_script('script-with-dependency');
    }
}