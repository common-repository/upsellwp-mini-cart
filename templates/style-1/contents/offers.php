<?php
/**
 * Offer
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/contents/offers.php.
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
$offer_contents = '';
if (method_exists('\CUW\App\Modules\Campaigns\CartUpsells', 'getOffersHtml')) {
    $offer_contents = \CUW\App\Modules\Campaigns\CartUpsells::getOffersHtml('uwpmc_offer_contents', false);
}

$block_styles = $style['item'] . $style['card'];
?>

<div class="uwpmc-offers-block uwpmc-border" style="display: none; <?php echo esc_attr($block_styles); ?>">
    <?php if (empty($offer_contents)) { ?>
        <div class="uwpmc-no-offers" style="display: flex; justify-content: center; align-items: center; height: 100%;">
            <?php esc_html_e('No offers found.', 'upsellwp-mini-cart'); ?>
        </div>
    <?php } else {
        echo $offer_contents; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } ?>
</div>
