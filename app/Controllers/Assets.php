<?php
/**
 * Mini-cart by UpsellWP
 *
 * @package   upsellwp-mini-cart
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace UWPMC\App\Controllers;

use UWPMC\App\Helpers\Plugin;
use UWPMC\App\Helpers\Template;

defined('ABSPATH') || exit;

class Assets
{
    /**
     * To load admin area assets.
     */
    public static function loadAdminAssets()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!empty($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) == UWPMC_PLUGIN_SLUG) {
            $plugin_url = plugin_dir_url(UWPMC_PLUGIN_FILE);

            // load styles
            wp_enqueue_style('uwpmc_admin_css', $plugin_url . 'assets/css/admin.css', [], UWPMC_PLUGIN_VERSION);
            wp_enqueue_style('uwpmc_bootstrap_css', $plugin_url . 'assets/css/bootstrap.min.css', [], '4.6.2');
            wp_enqueue_style('uwpmc_mini_cart_css', $plugin_url . 'assets/css/uwp-mini-cart.css', [], UWPMC_PLUGIN_VERSION);

            // load scripts
            wp_enqueue_script('uwpmc_bootstrap_script', $plugin_url . 'assets/js/bootstrap.min.js', ['jquery'], '4.6.2', ['in_footer' => true]);
            wp_enqueue_script('uwpmc_admin_script', $plugin_url . 'assets/js/admin.js', ['jquery'], UWPMC_PLUGIN_VERSION, ['in_footer' => true]);
            wp_localize_script('uwpmc_admin_script', 'uwpmc_admin_script_data', apply_filters('uwpmc_admin_scripts_data', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'template_data' => Template::getDefaultData(),
                    'theme_styles' => Template::getThemeStyle(),
                    'nonce' => wp_create_nonce('uwpmc_nonce'),
                    'messages' => [
                        'settings_updated' => __('Settings updated', 'upsellwp-mini-cart'),
                        'banner_delete' => __('Delete', 'upsellwp-mini-cart'),
                        'theme_activate_waring' => __('Are you sure, you want to activate this theme?', 'upsellwp-mini-cart'),
                        'theme_activate_notice' => __('Upon activation, all locally made style changes are permanently lost.', 'upsellwp-mini-cart'),
                    ],
                ]
            ));

            if (!Plugin::isUpsellWPActive() && function_exists('add_thickbox')) { // load plugin install model style and scripts
                add_thickbox();
                wp_enqueue_script('uwpmc_admin_script', plugins_url('js/plugin-install.js'), ['jquery'], UWPMC_PLUGIN_VERSION, ['in_footer' => true]);
            }
        }
    }

    /**
     * To load front-end assets.
     */
    public static function loadFrontendAssets()
    {
        $slider_data = MiniCart::getData();
        $plugin_url = plugin_dir_url(UWPMC_PLUGIN_FILE);

        // load styles
        wp_enqueue_style('uwpmc_mini_cart_css', $plugin_url . 'assets/css/uwp-mini-cart.css', [], UWPMC_PLUGIN_VERSION);

        // load scripts
        wp_enqueue_script('uwpmc_mini_cart_script', $plugin_url . 'assets/js/uwp-mini-cart.js', ['jquery'], UWPMC_PLUGIN_VERSION, ['in_footer' => true]);
        wp_localize_script('uwpmc_mini_cart_script', 'uwpmc_data', apply_filters('uwpmc_mini_cart_script_data', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('uwpmc_nonce'),
                'is_cart' => function_exists('is_cart') && is_cart(),
                'is_checkout' => function_exists('is_checkout') && is_checkout(),
                'has_cart_block' => function_exists('has_block') && has_block('woocommerce/cart'),
                'has_checkout_block' => function_exists('has_block') && has_block('woocommerce/checkout'),
                'messages' => [
                    'out_of_stock' => __('No more quantities are available in stock.', 'upsellwp-mini-cart'),
                    'error' => __('Something went wrong.', 'upsellwp-mini-cart'),
                    'no_offers' => __('No offers found.', 'upsellwp-mini-cart'),
                ],
                'auto_open_slider' => !empty($slider_data['data']['slider']['auto_open']),
            ]
        ));
    }
}