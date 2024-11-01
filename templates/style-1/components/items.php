<?php
/**
 * Items
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/components/items.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || !function_exists('WC')) {
    return;
}
$cart = WC()->cart;
if (empty($cart)) {
    return;
}
?>
<div class="uwpmc-items" style="<?php echo esc_attr($style['item']); ?>">
    <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item) :
        $item_subtotal = $cart_item['line_subtotal'] ?? 0;
        if ($cart->display_prices_including_tax()) {
            $item_subtotal += $cart_item['line_subtotal_tax'] ?? 0;
        }

        $item_object = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $item_name = apply_filters('woocommerce_cart_item_name', $item_object->get_name(), $cart_item, $cart_item_key);
        $item_thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $item_object->get_image(), $cart_item, $cart_item_key);
        $item_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($item_object), $cart_item, $cart_item_key);
        $item_subtotal = apply_filters('woocommerce_cart_item_subtotal', wc_price($item_subtotal), $cart_item, $cart_item_key);
        $max_quantity = $item_object->get_backorders() === 'no' ? $item_object->get_stock_quantity() : '';
        $formatted_cart_item_data = wc_get_formatted_cart_item_data($cart_item);
        $remove_link = apply_filters('woocommerce_cart_item_remove_link', ' ', $cart_item_key);
        $quantity_section = apply_filters('woocommerce_cart_item_quantity', ' ', $cart_item_key, $cart_item);
        ?>
        <div class="uwpmc-item uwpmc-border" style="color: inherit;<?php echo esc_attr($style['card']); ?>"
             data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>">

            <div class="uwpmc-item-image" style="width: 64px;">
                <?php echo wp_kses_post($item_thumbnail); ?>
            </div>
            <div style="display: flex; flex-direction: column; width: 75%;">
                <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: space-between; gap: 8px;">
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: start; gap: 4px;">
                        <div class="uwpmc-item-title" style="color: inherit; font-size: inherit">
                            <?php echo esc_html(wp_strip_all_tags($item_name)); ?>
                            <?php echo wp_kses_post($formatted_cart_item_data); ?>
                        </div>
                        <?php if (!empty($remove_link)) { ?>
                            <div class="uwpmc-remove-item"
                                 style="<?php echo !empty($data['items']['item']['show_remove']) ? 'display: flex;' : 'display: none;'; ?> align-items: center; justify-content: center; cursor: pointer;">
                                <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                    <path d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                    <path d="M18.8484 9.14001L18.1984 19.21C18.0884 20.78 17.9984 22 15.2084 22H8.78844C5.99844 22 5.90844 20.78 5.79844 19.21L5.14844 9.14001"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                    <path d="M10.3281 16.5H13.6581" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.5 12.5H14.5" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        <?php } ?>
                    </div>
                    <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 4px;">
                        <div class="uwpmc-quantity-container uwpmc-component-layout"
                             style="display: flex; align-items: center; border: thin solid; <?php echo esc_attr($style['component_style']); ?>">
                            <?php if (!empty($quantity_section)) { ?>
                                <div class="uwpmc-quantity-minus"
                                     style="<?php echo (is_numeric($quantity_section)) ? 'pointer-events: none;' : '' ?>">
                                    <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 12H18" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <input type="number" class="uwpmc-quantity-input"
                                       value="<?php echo esc_attr($cart_item['quantity']); ?>" min="0"
                                       max="<?php echo esc_attr($max_quantity); ?>"
                                    <?php echo (is_numeric($quantity_section)) ? 'readonly' : '' ?>>

                                <div class="uwpmc-quantity-plus"
                                     style="<?php echo (is_numeric($quantity_section)) ? 'pointer-events: none;' : '' ?>">
                                    <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 12H18" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 18V6" stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="uwpmc-item-price"
                             style="color: inherit; <?php echo $data['items']['item']['display_price'] != 'product_price' ? 'display: none;' : '' ?>">
                            <?php echo wp_kses_post($item_price); ?>
                        </div>
                        <div class="uwpmc-item-subtotal"
                             style="color: inherit; <?php echo $data['items']['item']['display_price'] != 'item_subtotal' ? 'display: none;' : '' ?>">
                            <?php echo wp_kses_post($item_subtotal); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
