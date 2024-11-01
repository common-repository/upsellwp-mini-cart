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

defined('ABSPATH') || exit;

class Page
{
    /**
     * To add admin menu.
     *
     * @return void
     */
    public static function addMenu()
    {
        add_menu_page(
            esc_html__('Side Cart', 'upsellwp-mini-cart'),
            esc_html__('Side Cart', 'upsellwp-mini-cart'),
            'manage_woocommerce',
            UWPMC_PLUGIN_SLUG,
            [__CLASS__, 'adminPage'],
            'dashicons-cart',
            60
        );
    }

    /**
     * To load admin menu page.
     *
     * @return void
     */
    public static function adminPage()
    {
        if (file_exists(UWPMC_PLUGIN_PATH . '/app/Views/Admin.php')) {
            $status = sanitize_text_field(wp_unslash($_GET['uwpmc_status'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            include UWPMC_PLUGIN_PATH . '/app/Views/Admin.php';
        }
    }

    /**
     * Save settings.
     */
    public static function saveSettings()
    {
        if (!empty($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) == UWPMC_PLUGIN_SLUG) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if (!empty($_POST['uwpmc_save_settings']) && !empty($_POST['uwpmc_settings'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $status = false;
                $nonce = sanitize_text_field(wp_unslash($_POST['uwpmc_save_nonce'] ?? ''));
                if (wp_verify_nonce($nonce, 'uwpmc_save_settings') && current_user_can('manage_woocommerce')) {
                    $data = wp_kses_post_deep(wp_unslash($_POST['uwpmc_settings'])); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

                    // to unset the data form WP editor
                    if (isset($data['banner'])) {
                        unset($data['banner']);
                    }

                    $data['data']['widget']['show'] = $data['data']['widget']['show'] ?? 0;
                    $data['data']['slider']['auto_open'] = $data['data']['slider']['auto_open'] ?? 0;

                    $data = apply_filters('uwpmc_settings_data', $data);
                    update_option('uwpmc_settings', $data);
                    $status = true;
                }
                wp_safe_redirect(add_query_arg('uwpmc_status', $status ? 'saved' : 'error'));
                exit;
            }
        }
    }

    /**
     * To change something.
     */
    public static function addTweaks()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!empty($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) == UWPMC_PLUGIN_SLUG) {
            // remove notices
            remove_all_filters('admin_notices');

            // limit MCE (classic text editor) functionality
            add_filter('mce_buttons', function () {
                return [
                    'bold',
                    'italic',
                    'bullist',
                    'numlist',
                    'blockquote',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'link',
                ];
            }, 1000);
            add_filter('mce_buttons_2', '__return_empty_array', 1000);
        }
    }
}