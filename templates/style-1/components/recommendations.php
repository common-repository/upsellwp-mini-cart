<?php
/**
 * Recommendation
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/components/recommendations.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced)) {
    return;
}
if (empty($advanced['recommendations']['ids'])) return;

$recommendation_section_style = (!empty($advanced['recommendations']['enable']) ? 'display: block;' : 'display: none;')
    . (!empty($style['recommendations']) ? $style['recommendations'] : '') . $style['card'];
$recommendation_item_style = (!empty($style['recommendation_items']) ? $style['recommendation_items'] : '') . $style['card'];
?>
<div class="uwpmc-recommended-items-section"
     style="font-size: 16px; box-shadow: 0 16px 20px 0;
     <?php echo esc_attr($recommendation_section_style); ?>">
    <div class="uwpmc-recommendation" style="margin: 0;">
        <div style="text-align: justify; padding: 2px 8px;"><?php esc_html_e('You may also like...', 'upsellwp-mini-cart'); ?></div>
        <div class="uwpmc-related-products">
            <?php foreach ($advanced['recommendations']['ids'] as $related_product_id) {
                $product = wc_get_product($related_product_id); ?>
                <div class="uwpmc-related-product-row uwpmc-border"
                     style="<?php echo esc_attr($recommendation_item_style); ?>"
                     data-product_id="<?php echo esc_attr($product->get_id()); ?>">
                    <div class="uwpmc-related-product uwpmc-border"
                         style="display: flex; gap: 8px; height: 100%; <?php echo esc_attr($style['card']); ?>">
                        <div class="uwpmc-related-product-image"
                             style="width: 52px; height: auto; padding: 6px; display: flex;">
                            <?php echo wp_kses_post($product->get_image()); ?>
                        </div>
                        <div style="padding: 6px; display: flex; flex-direction: column;">
                            <div class="uwpmc-related-product-title"><?php echo esc_html(wp_strip_all_tags($product->get_title())); ?> </div>
                            <div class="uwpmc-related-product-price"><?php echo wp_kses_post($product->get_price_html()); ?> </div>
                        </div>
                    </div>
                    <?php if ($product->is_type(['simple', 'subscription'])) { ?>
                        <div class="uwpmc-action uwpmc-add-recommended-item uwpmc-recommended-item uwpmc-border"
                             style="<?php echo esc_attr($style['action']); ?><?php echo esc_attr($style['card']); ?>">
                            <?php esc_html_e('Add', 'upsellwp-mini-cart'); ?>
                        </div>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>"
                           style="text-decoration: none; color: inherit; margin-top: auto;">
                            <div class="uwpmc-action uwpmc-recommended-item uwpmc-border"
                                 style="<?php echo esc_attr($style['action']); ?>">
                                <?php esc_html_e('Add', 'upsellwp-mini-cart'); ?>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
