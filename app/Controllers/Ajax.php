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

use UWPMC\App\Helpers\WC;

defined('ABSPATH') || exit;

class Ajax
{
    /**
     * Get authenticated user request handlers.
     *
     * @return array
     */
    private static function getAuthRequestHandlers(): array
    {
        return (array)apply_filters('uwpmc_ajax_auth_request_handlers', [
            'remove_item_from_cart' => [__CLASS__, 'removeItemFromCart'],
            'update_item_quantity' => [__CLASS__, 'updateItemQuantity'],
            'get_sidebar_fragments' => [__CLASS__, 'getSidebarFragments'],
            'apply_coupon' => [__CLASS__, 'applyCoupon'],
            'remove_coupon' => [__CLASS__, 'removeCoupon'],
            'add_product_to_cart' => [__CLASS__, 'addProductToCart'],
        ]);
    }

    /**
     * Get non-authenticated (guest) user request handlers.
     *
     * @return array
     */
    private static function getGuestRequestHandlers(): array
    {
        return (array)apply_filters('uwpmc_ajax_guest_request_handlers', [
            'remove_item_from_cart' => [__CLASS__, 'removeItemFromCart'],
            'update_item_quantity' => [__CLASS__, 'updateItemQuantity'],
            'get_sidebar_fragments' => [__CLASS__, 'getSidebarFragments'],
            'apply_coupon' => [__CLASS__, 'applyCoupon'],
            'remove_coupon' => [__CLASS__, 'removeCoupon'],
            'add_product_to_cart' => [__CLASS__, 'addProductToCart'],
        ]);
    }

    /**
     * To handle authenticated user requests.
     *
     * @return void
     */
    public static function handleAuthRequests()
    {
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));
        if (!empty($nonce) && !wp_verify_nonce($nonce, 'uwpmc_nonce')) {
            wp_send_json_error(['message' => __("Security check failed!", 'upsellwp-mini-cart')]);
        }

        $method = sanitize_text_field(wp_unslash($_POST['method'] ?? ''));
        $handlers = self::getAuthRequestHandlers();
        if (!empty($method) && isset($handlers[$method]) && is_callable($handlers[$method])) {
            wp_send_json_success(call_user_func($handlers[$method]));
        }
        wp_send_json_error(['message' => __("Method not exists.", 'upsellwp-mini-cart')]);
    }

    /**
     * To handle non-authenticated (guest) user requests.
     *
     * @return void
     */
    public static function handleGuestRequests()
    {
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));
        if (!empty($nonce) && !wp_verify_nonce($nonce, 'uwpmc_nonce')) {
            wp_send_json_error(['message' => __("Security check failed!", 'upsellwp-mini-cart')]);
        }

        $method = sanitize_text_field(wp_unslash($_POST['method'] ?? ''));
        $handlers = self::getGuestRequestHandlers();
        if (!empty($method) && isset($handlers[$method]) && is_callable($handlers[$method])) {
            wp_send_json_success(call_user_func($handlers[$method]));
        }
        wp_send_json_error(['message' => __("Method not exists.", 'upsellwp-mini-cart')]);
    }

    /**
     * Remove item from cart.
     *
     * @return array
     */
    private static function removeItemFromCart(): array
    {
        $cart_item_key = sanitize_text_field(wp_unslash($_POST['cart_item_key'] ?? ''));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($cart_item_key) && method_exists($cart, 'remove_cart_item')) {
            $removed = $cart->remove_cart_item($cart_item_key);
            return self::prepareResponse([
                'status' => $removed ? 'success' : 'error',
                'removed' => $removed,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * To update cart item quantity.
     *
     * @return array
     */
    private static function updateItemQuantity(): array
    {
        $cart_item_key = sanitize_text_field(wp_unslash($_POST['cart_item_key'] ?? ''));
        $current_quantity = sanitize_text_field(wp_unslash($_POST['current_quantity'] ?? ''));
        $quantity_action = sanitize_text_field(wp_unslash($_POST['quantity_action'] ?? ''));
        $cart = WC::getCart();

        if (!empty($cart) && !empty($cart_item_key) && !empty($quantity_action)
            && method_exists($cart, 'remove_cart_item')
            && method_exists($cart, 'set_quantity')
        ) {
            if (empty($current_quantity)) {
                $quantity_updated = $cart->remove_cart_item($cart_item_key);
            } else {
                if ($quantity_action == 'plus') {
                    $current_quantity += 1;
                } elseif ($quantity_action == 'minus') {
                    $current_quantity -= 1;
                }
                $cart_item = WC::getCartItem($cart_item_key);
                if (!empty($cart_item['product_id']) && !WC::isPurchasableProduct($cart_item['product_id'], $current_quantity)) {
                    return ['status' => "error", 'message' => __("No more quantities are available in stock.", 'upsellwp-mini-cart')];
                }
                $quantity_updated = $cart->set_quantity($cart_item_key, $current_quantity);
            }
            return self::prepareResponse([
                'status' => $quantity_updated ? 'success' : 'error',
                'quantity_updated' => $quantity_updated,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * To get sidebar html.
     *
     * @return array
     */
    private static function getSidebarFragments(): array
    {
        return self::prepareResponse();
    }

    /**
     * To apply coupon.
     *
     * @return array
     */
    private static function applyCoupon(): array
    {
        $coupon_code = sanitize_text_field(wp_unslash($_POST['coupon_code'] ?? ''));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($coupon_code) && isset(WC()->session)
            && method_exists($cart, 'apply_coupon')
            && method_exists($cart, 'calculate_totals')
            && function_exists('wc_clear_notices')
        ) {
            wc_clear_notices();
            $applied = $cart->apply_coupon($coupon_code);

            $session = WC::getSession('wc_notices', []);
            $message = '';
            if (isset($session['success'])) {
                $message = $session['success'][0]['notice'];
            } elseif (isset($session['error'])) {
                $message = $session['error'][0]['notice'];
            }

            wc_clear_notices();

            if ($applied) {
                WC()->cart->calculate_totals();
            }

            return self::prepareResponse([
                'status' => $applied ? 'success' : 'error',
                'applied' => $applied,
                'message' => $message,
            ]);

        }
        return ['status' => 'error'];

    }

    /**
     * To remove coupon.
     *
     * @return array
     */
    private static function removeCoupon(): array
    {
        $coupon_code = sanitize_text_field(wp_unslash($_POST['coupon_code'] ?? ''));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($coupon_code)
            && method_exists($cart, 'remove_coupon')
            && method_exists($cart, 'calculate_totals')
            && function_exists('wc_clear_notices')
        ) {
            $message = __("Unable to remove coupon.", 'upsellwp-mini-cart');
            $removed = $cart->remove_coupon($coupon_code);
            if ($removed) {
                wc_clear_notices();
                WC()->cart->calculate_totals();
                $message = __("Coupon has been removed.", 'woocommerce');
            }

            return self::prepareResponse([
                'status' => $removed ? 'success' : 'error',
                'removed' => $removed,
                'message' => $message,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * Add product to cart.
     *
     * @return array
     */
    private static function addProductToCart(): array
    {
        $product_id = sanitize_text_field(wp_unslash($_POST['product_id'] ?? ''));
        $quantity = sanitize_text_field(wp_unslash($_POST['quantity'] ?? 1));
        if (!empty($product_id)) {
            $cart = WC::getCart();
            try {
                $added = $cart->add_to_cart($product_id, $quantity);
                return self::prepareResponse([
                    'status' => $added ? 'success' : 'error',
                    'added' => $added,
                ]);
            } catch (\Exception $e) {
            }
        }
        return ['status' => "error"];
    }

    /**
     * To prepare response with sidebar data.
     *
     * @param array $extra_data
     * @return array
     */
    private static function prepareResponse(array $extra_data = []): array
    {
        if (!empty($_POST['process_notice'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $extra_data = array_merge(WC::getLastNoticeFromSession(true), $extra_data);
        }

        return array_merge([
            'cart_body' => MiniCart::getTemplate('contents/cart', [], false),
            'cart_items_qty' => method_exists(WC()->cart, 'get_cart_contents_count')
                ? WC()->cart->get_cart_contents_count() : '',
        ], $extra_data);
    }
}