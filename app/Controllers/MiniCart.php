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

use UWPMC\App\Helpers\Template;
use UWPMC\App\Helpers\WC;

defined('ABSPATH') || exit;

class MiniCart
{
    /**
     * To hold template data.
     *
     * @var array
     */
    private static $template_data;

    /**
     * Get data.
     *
     * @return array
     */
    public static function getData(): array
    {
        $sidebar_data = array_merge(Template::getDefaultData(), get_option('uwpmc_settings', []));
        if (!isset($sidebar_data['data']['widget']['show'])) {
            $sidebar_data['data']['widget']['show'] = 1;
        }
        if (!isset($sidebar_data['data']['slider']['auto_open'])) {
            $sidebar_data['data']['slider']['auto_open'] = 1;
        }
        return $sidebar_data;
    }

    /**
     * To get HTML content.
     *
     * @param string $path
     * @param array $params
     * @param bool $print
     * @return false|string
     */
    public static function getTemplate(string $path, array $params = [], bool $print = true)
    {
        $data = self::getTemplateData();
        if (!empty($params)) {
            $data = array_merge($data, $params);
        }

        if (!empty($path) && !empty($data)) {
            $file_path = $data['style']['layout'] . '/' . $path;
            return Template::getHtml($file_path, $data, $print);
        }
        return false;
    }

    /**
     * To get template data.
     *
     * @return array
     */
    public static function getTemplateData(): array
    {
        if (isset(self::$template_data)) {
            //return self::$template_data;
        }

        self::$template_data = [];
        $data = MiniCart::getData();
        $data = apply_filters('uwpmc_template_data', $data);
        $data = Template::prepareData($data);
        $data['data']['active_theme'] = $data['active_theme'];
        $active_theme = Template::getThemes($data['active_theme']);
        $data['style']['layout'] = $active_theme['layout'];
        if (!empty($data['data']) && !empty($data['style'])) {
            self::$template_data = [
                'data' => $data['data'],
                'style' => $data['style'] ?? [],
                'advanced' => $data['advanced'],
            ];
        }
        return self::$template_data;
    }

    /**
     * To load widget and sidebar.
     *
     * @return void
     */
    public static function loadWidgetAndSidebar()
    {
        self::getTemplate('widget');
        self::getTemplate('sidebar');
    }

    /**
     * To add random products in cart for preview.
     *
     * @return void
     */
    public static function addProductsForPreview()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!empty($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) == UWPMC_PLUGIN_SLUG && function_exists('wc_load_cart')) {
            wc_load_cart();
            $cart = WC::getCart();
            if (!empty($cart) && empty($cart->get_cart()) && $product_ids = WC::getRandomProducts()) {
                foreach ($product_ids as $product_id) {
                    try {
                        $cart->add_to_cart($product_id);
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }

    /**
     * To store related products in session.
     *
     * @return array
     */
    public static function getRelatedProductIds(): array
    {
        $cart_items = WC::getCartItems();
        if (empty($cart_items) || count($cart_items) == self::getSessionData('cart_items_count', 0)) {
            return self::getSessionData('related_product_ids', []);
        }

        $related_product_ids = [];
        foreach ($cart_items as $cart_item) {
            $related_product_ids = array_merge(
                wc_get_related_products($cart_item['product_id'], 5, array_column($cart_items, 'product_id')),
                $related_product_ids
            );
        }

        $related_product_ids = array_filter(array_unique($related_product_ids), [WC::class, 'isPurchasableProduct']);
        if (empty($related_product_ids)) {
            self::updateSessionData(['cart_items_count' => count($cart_items), 'related_product_ids' => []]);
            return [];
        }

        $related_product_ids = apply_filters('uwpmc_related_product_ids', array_slice($related_product_ids, 0, 5), $related_product_ids);
        self::updateSessionData([
            'cart_items_count' => count($cart_items),
            'related_product_ids' => $related_product_ids,
        ]);
        return $related_product_ids;
    }

    /**
     * To get session data
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getSessionData(string $key, $default = false)
    {
        if (!empty($key)) {
            $session = WC::getSession('uwpmc_data', []);
            if (isset($session[$key])) {
                return $session[$key];
            }
        }
        return $default;
    }

    /**
     * To update session data
     *
     * @param array $data
     */
    public static function updateSessionData(array $data)
    {
        WC::setSession('uwpmc_data', array_merge(WC::getSession('uwpmc_data', []), $data));
    }

    /**
     * To delete the session data.
     *
     * @return void
     */
    public static function deleteSessionData()
    {
        WC::deleteSession('uwpmc_data');
    }
}