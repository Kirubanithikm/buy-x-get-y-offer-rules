<?php
/**
 * Buy x get x product for woocommerce
 *
 * @package           buy-x-get-y-offers-rule
 * @author            Kirubanithi G <kirubanithikm@gmail.com>
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       buy-x-get-y-offers-rule
 * Plugin URI:        https://github.com/Kirubanithikm/buy-x-get-y-offers-rule
 * Description:       Buy product X and get the product Y!
 * Requires at least: 5.2
 * Version:           1.0
 * Requires PHP:      5.6
 * Author:            Kirubanithi G
 * Author URI:        http://example.org/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {exit;}

/**
 * Define plugin path
 */
if (!defined('BXGX_PLUGIN_PATH')) {
    define('BXGX_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

/**
 * Define plugin url
 */
if (!defined('BXGX_PLUGIN_URL')) {
    define('BXGX_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (file_exists(__DIR__. '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    wp_die('Buy x get x is unable to find the autoload file.');
}

// To init the hooks
if (class_exists('BXGX\App\Route')) {
    new BXGX\App\Route();
} else {
    wp_die('Buy x and get x plugin for WooCommerce is unable to find the Route class.');
}


// Git config for update

require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Kirubanithikm/buy-x-get-y-offers-rule',
    __FILE__,
    'buy-x-get-y-offers-rule'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');