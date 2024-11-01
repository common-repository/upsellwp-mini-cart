<?php
/**
 * Widget
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/widget.php.
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

$style['widget'] .= $data['widget']['position'] . ':' . $data['widget']['float_x'] . 'px;';
$style['widget'] .= 'bottom: ' . $data['widget']['float_y'] . 'px;';
?>
<div class="uwpmc-widget-container upw-mc-<?php echo esc_attr($data['active_theme']); ?>"
     style="<?php if (empty($data['widget']['show'])) echo 'display: none;' ?> <?php echo esc_attr($style['widget']); ?>">
    <div class="uwpmc-widget">
        <div id="uwpmc-widget-icon">
            <div class="uwpmc-widget-qty">
                <span><?php echo esc_html($cart->get_cart_contents_count()); ?></span>
            </div>
            <svg class="uwpmc-widget-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.86376 16.4552C3.00581 13.0234 2.57684 11.3075 3.47767 10.1538C4.3785 9 6.14721 9 9.68462 9H14.3153C17.8527 9 19.6214 9 20.5222 10.1538C21.4231 11.3075 20.9941 13.0234 20.1362 16.4552C19.5905 18.6379 19.3176 19.7292 18.5039 20.3646C17.6901 21 16.5652 21 14.3153 21H9.68462C7.43476 21 6.30983 21 5.49605 20.3646C4.68227 19.7292 4.40943 18.6379 3.86376 16.4552Z"
                      stroke="currentColor" stroke-width="1.5"/>
                <path d="M19.5 9.5L18.7896 6.89465C18.5157 5.89005 18.3787 5.38775 18.0978 5.00946C17.818 4.63273 17.4378 4.34234 17.0008 4.17152C16.5619 4 16.0413 4 15 4M4.5 9.5L5.2104 6.89465C5.48432 5.89005 5.62128 5.38775 5.90221 5.00946C6.18199 4.63273 6.56216 4.34234 6.99922 4.17152C7.43808 4 7.95872 4 9 4"
                      stroke="currentColor" stroke-width="1.5"/>
                <path d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4C15 4.55228 14.5523 5 14 5H10C9.44772 5 9 4.55228 9 4Z"
                      stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </div>
    </div>
</div>