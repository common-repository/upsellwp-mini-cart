<?php
/**
 * Footer
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/components/footer.php.
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

$tabs = (!empty($advanced['tabs'])) ? $advanced['tabs'] : [];
$tab_style = (!empty($style['tabs']) ? $style['tabs'] : '') . $style['card']
    . (empty($tabs['cart']['enable']) ? 'border: none;' : '');
$tabs_count = count($tabs);
?>
<div class="uwpmc-footer">
    <div class="uwpmc-tabs"
         style="display: flex; <?php echo esc_attr($tab_style); ?>">
        <?php if (!empty($tabs)) {
            $tab_count = 1;
            foreach ($tabs as $tab_slug => $tab) { ?>
                <button id="uwpmc-<?php echo esc_attr($tab_slug); ?>-button" class="uwpmc-tab-button
                <?php echo ($tab_count == 1) ? 'uwpmc-active-page' : '' ?>"
                        style="<?php if (empty($tab['enable'])) echo 'display: none;' ?>
                        <?php echo ($tab_count == 1)
                            ? 'border-radius: 0 0 0 6px;'
                            : ($tab_count == $tabs_count ? 'border-radius: 0 0 6px 0;' : 'border-radius: 0;') ?>">
                    <?php echo esc_html($tab['title']); ?>
                </button>
                <?php $tab_count += 1; ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
