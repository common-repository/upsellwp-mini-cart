<?php
/**
 * Banner
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/components/banner.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced)) {
    return;
}

?>

<div class="uwpmc-banners"
     style="<?php echo (!empty($advanced['banner']['enabled'])) ? 'display: flex;' : 'display: none;'; ?>">
    <?php if (!empty($advanced['banner']['list'])) {
        foreach ($advanced['banner']['list'] as $banner) { ?>
            <div class="uwpmc-banner" style="<?php echo esc_attr($banner['style']); ?>">
                <?php echo wp_kses_post(__($banner['content'], 'upsellwp-mini-cart')); ?>
            </div>
        <?php }
    } ?>
</div>
