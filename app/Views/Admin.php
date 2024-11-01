<?php
defined('ABSPATH') || exit;

use UWPMC\App\Helpers\Template;

$sidebar_data = \UWPMC\App\Controllers\MiniCart::getData();
$themes = Template::getThemes();
$style = array_merge(Template::getThemeStyle($sidebar_data['active_theme']), $sidebar_data['style']);

$tabs_info = Template::getTabs();
$show_tab_styles = array_sum(array_column($tabs_info, 'load'));

$is_upsellwp_active = \UWPMC\App\Helpers\Plugin::isUpsellWPActive();

$active_theme_name = $themes[$sidebar_data['active_theme']]['name'];
?>
<div>
    <div id="uwpmc-admin-page" class="col-6 px-1">
        <div class="uwpmc-admin-notice">
            <?php
            if (!empty($status)) {
                $message = ($status == 'error')
                    ? __('Security verification failed!', 'upsellwp-mini-cart')
                    : __('Changes saved successfully.', 'upsellwp-mini-cart');
                ?>
                <div class="notice notice-<?php echo !empty($status == 'error') ? 'error' : 'success'; ?> is-dismissible"
                     style="margin: 0 0 10px 0;">
                    <p><?php echo esc_html($message); ?></p>
                </div>
                <?php
            } ?>
        </div>
        <div class="mb-3 d-flex align-items-end" style="gap: 8px;">
            <h4 id="header" class="m-0 py-1">
                <?php esc_html_e('Side Cart', 'upsellwp-mini-cart'); ?>
            </h4>
            <p class="m-0 py-1">
                <?php esc_html_e('by UpsellWP', 'upsellwp-mini-cart'); ?>
            </p>
        </div>
        <!--navbar-->
        <div class="position-sticky">
            <ul class="nav nav-tabs tabs-h font-weight-bold text-primary">
                <li class="nav-item m-0">
                    <button class="nav-link active" id="customize-tab" data-toggle="pill"
                            data-target="#uwpmc-customize"
                            type="button" role="tab" aria-controls="customize" aria-selected="true"
                            style="padding: 12px;">
                        <?php esc_html_e('Customization', 'upsellwp-mini-cart'); ?>
                    </button>
                </li>
                <li class="nav-item m-0">
                    <button class="nav-link" id="theme-tab" data-toggle="pill" data-target="#uwpmc-theme"
                            type="button"
                            role="tab" aria-controls="theme" aria-selected="false" style="padding: 12px 24px;">
                        <?php esc_html_e('Theme', 'upsellwp-mini-cart'); ?>
                    </button>
                </li>
                <li class="nav-item m-0">
                    <button class="nav-link" id="advanced-tab" data-toggle="pill" data-target="#uwpmc-advanced"
                            type="button" role="tab" aria-controls="advanced" aria-selected="false"
                            style="padding: 12px;">
                        <?php esc_html_e('Advanced', 'upsellwp-mini-cart'); ?>
                    </button>
                </li>
                <li class="nav-item m-0 ml-auto d-flex align-items-center px-3">
                    <div style="color: #6C727F; font-size: 18px;">
                        <?php echo 'v' . esc_html(UWPMC_PLUGIN_VERSION); ?>
                    </div>
                </li>
                <li class="nav-item m-0 d-flex align-items-center bg-light"
                    style="border: 1px solid #dee2e6; border-radius: 0 6px 0 0; border-bottom: none; padding: 0 4px;">
                    <div>
                        <button id="uwpmc-page-save" class="btn btn-primary">
                            <?php esc_html_e('Save', 'upsellwp-mini-cart'); ?>
                        </button>
                    </div>
                </li>
            </ul>
        </div>

        <form id="uwpmc-page-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="uwpmc_save_settings" value="1">
            <input type="hidden" name="uwpmc_save_nonce"
                   value="<?php echo esc_attr(wp_create_nonce('uwpmc_save_settings')); ?>">
            <div class="tab-content card mw-100 p-3 px-md-4 mt-0 border-top-0" id="pills-tabContent"
                 style="border-radius: 0 0 .25rem .25rem;">
                <!-- customization -->
                <div id="uwpmc-customize" class="tab-pane fade show active" role="tabpanel"
                     aria-labelledby="customize-tab">
                    <div id="uwpmc-custom-section">
                        <div id="uwpmc-content-widget">
                            <h5 class="text-dark"><?php esc_html_e('Widget', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Show widget', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-switch mx-3">
                                        <input type="checkbox" id="uwpmc-show-widget-switch"
                                               class="custom-control-input" data-target=".uwpmc-widget-container"
                                               name="uwpmc_settings[data][widget][show]" value="1"
                                            <?php if (!empty($sidebar_data['data']['widget']['show']))
                                                echo 'checked'; ?>>
                                        <label class="custom-control-label" for="uwpmc-show-widget-switch"></label>
                                    </div>
                                </div>
                                <div class="<?php echo (!empty($sidebar_data['data']['widget']['show']))
                                    ? 'd-flex' : 'd-none'; ?> align-items-center uwpmc-widget-details"
                                     style="gap: 4px;">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Position', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control w-100"
                                                name="uwpmc_settings[data][widget][position]">
                                            <option value="left"
                                                <?php selected('left', $sidebar_data['data']['widget']['position']); ?>>
                                                <?php esc_html_e('Left', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="right"
                                                <?php selected('right', $sidebar_data['data']['widget']['position']); ?>>
                                                <?php esc_html_e('Right', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="<?php echo (!empty($sidebar_data['data']['widget']['show']))
                                    ? 'd-flex' : 'd-none'; ?> align-items-center uwpmc-widget-details"
                                     style="gap: 4px;">
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-medium">
                                            <?php esc_html_e('Horizontally', 'upsellwp-mini-cart'); ?> &#8644;
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control w-100" type="number"
                                               name="uwpmc_settings[data][widget][float_x]"
                                               value="<?php echo esc_attr($sidebar_data['data']['widget']['float_x']); ?>"/>
                                    </div>
                                </div>
                                <div class="<?php echo (!empty($sidebar_data['data']['widget']['show']))
                                    ? 'd-flex' : 'd-none'; ?> align-items-center uwpmc-widget-details"
                                     style="gap: 4px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Vertically', 'upsellwp-mini-cart'); ?> &#8645;
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control w-100" type="number"
                                               name="uwpmc_settings[data][widget][float_y]"
                                               value="<?php echo esc_attr($sidebar_data['data']['widget']['float_y']); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-slider">
                            <h5 class="text-dark"><?php esc_html_e('Slider', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="font-weight-medium">
                                            <?php esc_html_e('Position', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control w-100"
                                                name="uwpmc_settings[data][slider][position]">
                                            <option value="left"
                                                <?php selected('left', $sidebar_data['data']['slider']['position']); ?>>
                                                <?php esc_html_e('Left', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="right"
                                                <?php selected('right', $sidebar_data['data']['slider']['position']); ?>>
                                                <?php esc_html_e('Right', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="py-2 d-flex flex-column" style="gap: 4px;">
                                    <div class="d-flex align-items-center">
                                        <div class="col-md-4">
                                            <label class="custom-label font-weight-medium">
                                                <?php esc_html_e('Show after adding product', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-switch mx-3">
                                            <input type="checkbox" id="uwpmc-show-mini-cart-switch"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[data][slider][auto_open]" value="1"
                                                <?php if (!empty($sidebar_data['data']['slider']['auto_open']))
                                                    echo 'checked'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-mini-cart-switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-header">
                            <h5 class="text-dark"><?php esc_html_e('Header', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label">
                                            <?php esc_html_e('Title', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control w-100" type="text"
                                               name="uwpmc_settings[data][header][title]"
                                               data-target=".uwpmc-header-title"
                                               value="<?php echo esc_attr($sidebar_data['data']['header']['title']); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-items">
                            <h5 class="text-dark"><?php esc_html_e('Items', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Show remove option', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-switch mx-3">
                                        <input type="checkbox" id="uwpmc-remove-item-switch"
                                               class="custom-control-input" data-target=".uwpmc-remove-item"
                                               name="uwpmc_settings[data][items][item][show_remove]" value="1"
                                            <?php if (!empty($sidebar_data['data']['items']['item']['show_remove']))
                                                echo 'checked'; ?>>
                                        <label class="custom-control-label" for="uwpmc-remove-item-switch"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 d-flex align-items-center">
                                <div class="col-md-4">
                                    <label class="custom-label font-weight-medium">
                                        <?php esc_html_e('Product price', 'upsellwp-mini-cart'); ?>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control w-100 uwpmc-price-format"
                                            name="uwpmc_settings[data][items][item][display_price]">
                                        <option value="item_subtotal"
                                            <?php selected('item_subtotal', $sidebar_data['data']['items']['item']['display_price']); ?>>
                                            <?php esc_html_e('Show item subtotal', 'upsellwp-mini-cart'); ?>
                                        </option>
                                        <option value="product_price"
                                            <?php selected('product_price', $sidebar_data['data']['items']['item']['display_price']); ?>>
                                            <?php esc_html_e('Show product price', 'upsellwp-mini-cart'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-coupon">
                            <h5 class="text-dark"><?php esc_html_e('Coupon', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center" style="height: 36px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Show coupon', 'upsellwp-mini-cart'); ?></label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-between gap-2 uwpmc-show-action">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-coupon-switch"
                                                   class="custom-control-input" data-target=".uwpmc-coupon-section"
                                                   name="uwpmc_settings[data][coupon][enable]" value="1"
                                                <?php if (!empty($sidebar_data['data']['coupon']['enable']))
                                                    echo 'checked'; ?>>
                                            <label class="custom-control-label" for="uwpmc-show-coupon-switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-actions">
                            <h5 class="text-dark"><?php esc_html_e('Buttons', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center" style="height: 36px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Checkout text', 'upsellwp-mini-cart'); ?></label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-between gap-2 uwpmc-show-action">
                                        <div class="uwpmc-show-button-details w-100">
                                            <input class="form-control w-100" type="text"
                                                   name="uwpmc_settings[data][actions][checkout][text]"
                                                   data-target=".uwpmc-checkout-text"
                                                   value="<?php echo esc_attr($sidebar_data['data']['actions']['checkout']['text']); ?>"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center" style="height: 36px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Show total', 'upsellwp-mini-cart'); ?></label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-between gap-2 uwpmc-show-action">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-total-amount-switch"
                                                   class="custom-control-input" data-target=".uwpmc-checkout-total"
                                                   name="uwpmc_settings[data][actions][checkout][show_total]" value="1"
                                                <?php if (!empty($sidebar_data['data']['actions']['checkout']['show_total']))
                                                    echo 'checked'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-total-amount-switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center" style="height: 36px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Show cart button', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-cart-content col-md-6 d-flex align-items-center justify-content-between gap-2 uwpmc-show-action">
                                        <div class="custom-control custom-switch d-flex">
                                            <input type="checkbox" id="uwpmc-show-cart-switch"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[data][actions][cart][enable]"
                                                   data-target=".uwpmc-cart-action"
                                                   value="1" <?php if (!empty($sidebar_data['data']['actions']['cart']['enable'])) echo 'checked'; ?>>
                                            <label class="custom-control-label" for="uwpmc-show-cart-switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 col-md-12 flex-column uwpmc-cart-button-details"
                                 style="gap: 4px;
								 <?php echo (!empty($sidebar_data['data']['actions']['cart']['enable']))
                                     ? 'display: flex;' : 'display: none;'; ?>">
                                <div class="d-flex align-items-center" style="height: 36px;">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Cart text', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-cart-content col-md-6 d-flex align-items-center justify-content-between gap-2 uwpmc-show-action">
                                        <div class="w-100">
                                            <input class="form-control w-100" type="text"
                                                   name="uwpmc_settings[data][actions][cart][text]"
                                                   data-target=".uwpmc-cart-button"
                                                   value="<?php echo esc_attr($sidebar_data['data']['actions']['cart']['text']); ?>"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-content-totals">
                            <h5 class="text-dark"><?php esc_html_e('Cart totals', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 4px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Show subtotal', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-subtotal-switch"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[data][totals][show][subtotal]"
                                                   data-target=".uwpmc-show-cart-subtotal" value="1"
                                                <?php if (!empty($sidebar_data['data']['totals']['show']['subtotal'])) echo 'checked'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-subtotal-switch"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Show discount', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-discount-switch"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[data][totals][show][discount]"
                                                   data-target=".uwpmc-show-cart-discount" value="1"
                                                <?php if (!empty($sidebar_data['data']['totals']['show']['discount'])) echo 'checked'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-discount-switch"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium"><?php esc_html_e('Show total', 'upsellwp-mini-cart'); ?></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-total-switch"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[data][totals][show][total]"
                                                   data-target=".uwpmc-show-cart-total" value="1"
                                                <?php if (!empty($sidebar_data['data']['totals']['show']['total'])) echo 'checked'; ?>>
                                            <label class="custom-control-label" for="uwpmc-show-total-switch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- themes -->
                <div id="uwpmc-theme" class="tab-pane fade" role="tabpanel" aria-labelledby="theme-tab">
                    <div id="uwpmc-style-navbar" class="d-flex justify-content-between my-3">
                        <div>
                            <label class="m-0">
                                <?php esc_html_e('Active theme: ', 'upsellwp-mini-cart'); ?>
                            </label>
                            <label class="font-weight-bold text-dark m-0 uwp-active-theme-name">
                                <?php echo esc_html($active_theme_name); ?>
                            </label>
                            <input type="hidden" class="uwpmc-active-theme_key" name="uwpmc_settings[active_theme]"
                                   value="<?php echo esc_attr($sidebar_data['active_theme']); ?>">
                        </div>
                        <div class="d-flex" style="gap: 4px;">
                            <button id="uwpmc-theme-reset" type="button" class="btn btn-outline-primary d-none"
                                    data-theme="<?php echo esc_attr($sidebar_data['active_theme']); ?>">
                                <?php esc_html_e('Reset', 'upsellwp-mini-cart'); ?>
                            </button>
                            <button type="button" title="<?php esc_attr_e('Edit', 'upsellwp-mini-cart'); ?>"
                                    class="btn btn-outline-dark align-items-center uwpmc-theme-edit">
                                <?php esc_html_e('Edit', 'upsellwp-mini-cart'); ?>
                            </button>
                            <button id="uwpmc-back-to-theme" type="button" class="btn btn-outline-dark d-none">
                                <?php esc_html_e('Close', 'upsellwp-mini-cart'); ?>
                            </button>
                        </div>
                    </div>
                    <div id="uwpmc-theme-section" class="d-flex flex-column py-3">
                        <h5 class="text-dark">
                            <?php esc_html_e('Available themes', 'upsellwp-mini-cart'); ?>:
                        </h5>
                        <div class="row flex-wrap">
                            <?php foreach ($themes as $theme_key => $theme) {
                                $callback = function ($data) use ($theme_key) {
                                    $data['style'] = Template::getThemeStyle($theme_key);
                                    return $data;
                                };
                                ?>
                                <div class="uwpmc-theme card p-0 col-md-5 mx-auto"
                                     style="min-width: 256px; max-width: 320px;">
                                    <div id="uwpmc-theme-layout"
                                         class="<?php echo ($theme_key == $sidebar_data['active_theme']) ? 'uwpmc-active-theme' : ''; ?>"
                                         data-theme="<?php echo esc_attr($theme_key); ?>" style="pointer-events: none;">
                                        <?php
                                        add_filter('uwpmc_template_data', $callback, 100);
                                        uwpmc_get_template('widget');
                                        uwpmc_get_template('sidebar');
                                        remove_filter('uwpmc_template_data', $callback, 100);
                                        ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center my-2 px-3 h-100">
                                        <label class="font-weight-bold text-dark m-0 uwp-theme-name">
                                            <?php echo esc_html($theme['name']); ?>
                                        </label>
                                        <div class="d-flex" style="gap: 8px;">
                                            <button type="button"
                                                    class="btn btn-outline-primary my-1 uwpmc-theme-activate <?php echo ($theme_key == $sidebar_data['active_theme']) ? 'd-none' : 'd-flex'; ?>"
                                                    data-theme="<?php echo esc_attr($theme_key); ?>" data-toggle="modal"
                                                    data-target="#uwpmc-warning-model">
                                                <?php esc_html_e('Activate', 'upsellwp-mini-cart'); ?>
                                            </button>
                                            <button type="button"
                                                    title="<?php esc_attr_e('Preview', 'upsellwp-mini-cart'); ?>"
                                                    class="btn btn-primary d-flex my-1 align-items-center  <?php echo ($theme_key == $sidebar_data['active_theme']) ? 'uwpmc-active-theme-preview' : 'uwpmc-theme-preview'; ?>"
                                                    data-target="#uwpmc-preview-theme"
                                                    data-theme="<?php echo esc_attr($theme_key); ?>">
                                                <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g id="Edit / Show">
                                                        <g id="Vector">
                                                            <path d="M3.5868 13.7788C5.36623 15.5478 8.46953 17.9999 12.0002 17.9999C15.5308 17.9999 18.6335 15.5478 20.413 13.7788C20.8823 13.3123 21.1177 13.0782 21.2671 12.6201C21.3738 12.2933 21.3738 11.7067 21.2671 11.3799C21.1177 10.9218 20.8823 10.6877 20.413 10.2211C18.6335 8.45208 15.5308 6 12.0002 6C8.46953 6 5.36623 8.45208 3.5868 10.2211C3.11714 10.688 2.88229 10.9216 2.7328 11.3799C2.62618 11.7067 2.62618 12.2933 2.7328 12.6201C2.88229 13.0784 3.11714 13.3119 3.5868 13.7788Z"
                                                                  stroke="currentColor" stroke-width="2"
                                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"
                                                                  stroke="currentColor" stroke-width="2"
                                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- edit styling -->
                    <div id="uwpmc-style-section" class="d-none">
                        <div id="uwpmc-style-widget">
                            <h5 class="text-dark"><?php esc_html_e('Widget', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][widget][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-widget-container"
                                                   value="<?php echo esc_attr($style['widget']['background-color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][widget][color]" data-name="color"
                                                   data-target=".uwpmc-widget-container"
                                                   value="<?php echo esc_attr($style['widget']['color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-sidebar">
                            <h5 class="text-dark"><?php esc_html_e('Slider', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Width', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex" style="gap: 8px;">
                                        <input type="range" name="uwpmc_settings[style][slider][width]"
                                               class="form-control-range w-80"
                                               data-target=".uwpmc-sidebar" data-name="width" min="320" max="480"
                                               value="<?php echo esc_attr($style['slider']['width']); ?>">
                                        <input id="uwpmc-cart-width" class="form-control bg-light text-center"
                                               type="text"
                                               style="width: 50px;"
                                               value="<?php echo esc_attr($style['slider']['width']); ?>"
                                               readonly/>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Background type', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <select class="form-control uwpmc-cart-background"
                                                    name="uwpmc_settings[style][slider][background-type]">
                                                <option value="gradient"
                                                    <?php selected('gradient', $style['slider']['background-type']); ?>>
                                                    <?php esc_html_e('Gradient', 'upsellwp-mini-cart'); ?>
                                                </option>
                                                <option value="solid"
                                                    <?php selected('solid', $style['slider']['background-type']); ?>>
                                                    <?php esc_html_e('Solid', 'upsellwp-mini-cart'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="uwpmc-color-inputs col-md-12 align-items-center p-0 my-1 uwpmc-solid-background
								<?php echo ($style['slider']['background-type'] == 'solid')
                                    ? 'd-flex' : 'd-none'; ?>">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex" style="gap: 8px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][slider][background-color]"
                                                   data-name="background"
                                                   data-target=".uwpmc-sidebar"
                                                   value="<?php echo isset($style['slider']['background-color'])
                                                       ? esc_attr($style['slider']['background-color'])
                                                       : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="uwpmc-gradient-background my-1
								<?php echo ($style['slider']['background-type'] == 'gradient')
                                    ? 'd-flex' : 'd-none'; ?> flex-column">
                                    <div class="d-flex">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('Gradient degree', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="d-flex col-md-6 my-1" style="gap: 8px;">
                                            <input type="range" name="uwpmc_settings[style][slider][gradient-deg]"
                                                   class="form-control-range w-80 uwpmc-gradient-degree"
                                                   data-name="degree" min="0" max="360"
                                                   value="<?php echo isset($style['slider']['gradient-deg'])
                                                       ? esc_attr($style['slider']['gradient-deg'])
                                                       : '0'; ?>">
                                            <input id="uwpmc-gradient-degree" class="form-control bg-light text-center"
                                                   type="text" style="width: 50px;"
                                                   value="<?php echo isset($style['slider']['gradient-deg'])
                                                       ? esc_attr($style['slider']['gradient-deg'])
                                                       : '0'; ?>" readonly/>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('Gradient color 1', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="uwpmc-color-inputs d-flex col-md-6 my-1" style="gap: 4px;">
                                            <div style="position: relative;">
                                                <div class="col-md-2 p-0"
                                                     style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                    <input class="uwpmc-color-picker" type="color"
                                                           style="height: 32px; width: 32px; border: 0;">
                                                </div>
                                                <input type="text"
                                                       class="uwpmc-color-input form-control flex-fill uwpmc-gradient-color-1 px-5"
                                                       name="uwpmc_settings[style][slider][gradient-bg-color-1]"
                                                       value="<?php echo isset($style['slider']['gradient-bg-color-1'])
                                                           ? esc_attr($style['slider']['gradient-bg-color-1'])
                                                           : ''; ?>"
                                                       maxlength="7" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('Gradient color 2', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="uwpmc-color-inputs d-flex col-md-6 my-1" style="gap: 4px;">
                                            <div style="position: relative">
                                                <div class="col-md-2 p-0"
                                                     style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                    <input class="uwpmc-color-picker" type="color"
                                                           style="height: 32px; width: 32px; border: 0;">
                                                </div>
                                                <input type="text"
                                                       class="uwpmc-color-input form-control flex-fill uwpmc-gradient-color-2 px-5"
                                                       name="uwpmc_settings[style][slider][gradient-bg-color-2]"
                                                       value="<?php echo isset($style['slider']['gradient-bg-color-2'])
                                                           ? esc_attr($style['slider']['gradient-bg-color-2'])
                                                           : ''; ?>"
                                                       maxlength="7" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Border width', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex" style="gap: 8px;">
                                        <select class="form-control w-100 uwpmc-slider-border"
                                                name="uwpmc_settings[style][card][border-width]"
                                                data-name="border-width"
                                                data-target=".uwpmc-border">
                                            <option value="none"
                                                <?php selected('none', $style['card']['border-width']); ?>>
                                                <?php esc_html_e('None', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="thin"
                                                <?php selected('thin', $style['card']['border-width']); ?>>
                                                <?php esc_html_e('Thin', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="medium"
                                                <?php selected('medium', $style['card']['border-width']); ?>>
                                                <?php esc_html_e('Medium', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="<?php echo($style['card']['border-width'] == 'none' ? 'd-none' : 'd-flex'); ?> align-items-center uwpmc-border-color">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Border color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 8px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][card][border-color]"
                                                   data-name="border-color"
                                                   data-target=".uwpmc-border"
                                                   value="<?php echo isset($style['card']['border-color'])
                                                       ? esc_attr($style['card']['border-color'])
                                                       : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Border radius', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex" style="gap: 8px;">
                                        <input type="range" name="uwpmc_settings[style][card][border-radius]"
                                               class="form-control-range w-80"
                                               data-target=".uwpmc-border" data-name="border-radius" min="0" max="12"
                                               value="<?php echo esc_attr($style['card']['border-radius']); ?>">
                                        <input id="uwpmc-border-radius" class="form-control bg-light text-center"
                                               type="text" style="width: 50px;"
                                               value="<?php echo esc_attr($style['card']['border-radius']); ?>"
                                               readonly/>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Component color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 8px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][component_style][border-color]"
                                                   data-name="border-color"
                                                   data-target=".uwpmc-component-layout"
                                                   value="<?php echo isset($style['component_style']['border-color'])
                                                       ? esc_attr($style['component_style']['border-color'])
                                                       : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-header">
                            <h5 class="text-dark"><?php esc_html_e('Header', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Font size', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="uwpmc_settings[style][header][font-size]"
                                                data-name="font-size" data-target=".uwpmc-header">
                                            <option value="24px" <?php selected('24px', $style['header']['font-size']); ?>>
                                                <?php esc_html_e('Default', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="18px" <?php selected('18px', $style['header']['font-size']); ?>>
                                                <?php esc_html_e('18px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="28px" <?php selected('28px', $style['header']['font-size']); ?>>
                                                <?php esc_html_e('28px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="32px" <?php selected('32px', $style['header']['font-size']); ?>>
                                                <?php esc_html_e('32px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][header][color]" data-name="color"
                                                   data-target=".uwpmc-header"
                                                   value="<?php echo esc_attr($style['header']['color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-item">
                            <h5 class="text-dark"><?php esc_html_e('Items', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Font size', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="uwpmc_settings[style][item][font-size]"
                                                data-name="font-size" data-target=".uwpmc-items">
                                            <option value="16px" <?php selected('16px', $style['item']['font-size']); ?>>
                                                <?php esc_html_e('Default', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="14px" <?php selected('14px', $style['item']['font-size']); ?>>
                                                <?php esc_html_e('14px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="18px" <?php selected('18px', $style['item']['font-size']); ?>>
                                                <?php esc_html_e('18px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="24px" <?php selected('24px', $style['item']['font-size']); ?>>
                                                <?php esc_html_e('24px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][item][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-items, .uwpmc-coupon"
                                                   value="<?php echo esc_attr($style['item']['background-color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][item][color]" data-name="color"
                                                   data-target=".uwpmc-items, .uwpmc-coupon"
                                                   value="<?php echo esc_attr($style['item']['color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-coupon">
                            <h5 class="text-dark"><?php esc_html_e('Coupon', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][coupon][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-coupon-section"
                                                   value="<?php echo esc_attr($style['coupon']['background-color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-action">
                            <h5 class="text-dark"><?php esc_html_e('Buttons', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Font size', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="uwpmc_settings[style][action][font-size]"
                                                data-name="font-size" data-target=".uwpmc-action">
                                            <option value="16px" <?php selected('16px', $style['action']['font-size']); ?>>
                                                <?php esc_html_e('Default', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="14px" <?php selected('14px', $style['action']['font-size']); ?>>
                                                <?php esc_html_e('14px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="18px" <?php selected('18px', $style['action']['font-size']); ?>>
                                                <?php esc_html_e('18px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="24px" <?php selected('24px', $style['action']['font-size']); ?>>
                                                <?php esc_html_e('24px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][action][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-action"
                                                   value="<?php echo esc_attr($style['action']['background-color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class=" font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][action][color]" data-name="color"
                                                   data-target=".uwpmc-action"
                                                   value="<?php echo esc_attr($style['action']['color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-totals">
                            <h5 class="text-dark"><?php esc_html_e('Cart totals', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Font size', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="uwpmc_settings[style][totals][font-size]"
                                                data-name="font-size" data-target=".uwpmc-cart-totals">
                                            <option value="16px" <?php selected('16px', $style['totals']['font-size']); ?>>
                                                <?php esc_html_e('Default', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="14px" <?php selected('14px', $style['totals']['font-size']); ?>>
                                                <?php esc_html_e('14px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="18px" <?php selected('18px', $style['totals']['font-size']); ?>>
                                                <?php esc_html_e('18px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                            <option value="24px" <?php selected('24px', $style['totals']['font-size']); ?>>
                                                <?php esc_html_e('24px', 'upsellwp-mini-cart'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][totals][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-cart-totals"
                                                   value="<?php echo esc_attr($style['totals']['background-color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][totals][color]" data-name="color"
                                                   data-target=".uwpmc-cart-totals"
                                                   value="<?php echo esc_attr($style['totals']['color']); ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-recommendation"
                             style="<?php echo(!empty($sidebar_data['advanced']['recommendations']['enable']) ? 'display: block;' : 'display: none;'); ?>">
                            <h5 class="text-dark"><?php esc_html_e('Recommendations', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][recommendations][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-recommended-items-section"
                                                   value="<?php echo !empty($style['recommendations']['background-color']) ? esc_attr($style['recommendations']['background-color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][recommendations][color]"
                                                   data-name="color"
                                                   data-target=".uwpmc-recommended-items-section"
                                                   value="<?php echo !empty($style['recommendations']['color']) ? esc_attr($style['recommendations']['color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="text-dark"><?php esc_html_e('Recommended items', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][recommendation_items][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-related-product"
                                                   value="<?php echo !empty($style['recommendation_items']['background-color']) ? esc_attr($style['recommendation_items']['background-color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][recommendation_items][color]"
                                                   data-name="color"
                                                   data-target=".uwpmc-related-product"
                                                   value="<?php echo !empty($style['recommendation_items']['color']) ? esc_attr($style['recommendation_items']['color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="uwpmc-style-goals"
                             style="<?php echo(!empty($sidebar_data['advanced']['goals']['enable']) ? 'display: block;' : 'display: none;'); ?>">
                            <h5 class="text-dark"><?php esc_html_e('Goals', 'upsellwp-mini-cart'); ?></h5>
                            <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative;">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][goals][background-color]"
                                                   data-name="background-color"
                                                   data-target=".uwpmc-goal"
                                                   value="<?php echo !empty($style['goals']['background-color']) ? esc_attr($style['goals']['background-color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="col-md-4">
                                        <label class="custom-label font-weight-medium">
                                            <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                         style="gap: 4px;">
                                        <div style="position: relative">
                                            <div class="col-md-2 p-0"
                                                 style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                <input class="uwpmc-color-picker" type="color"
                                                       style="height: 32px; width: 32px; border: 0;">
                                            </div>
                                            <input type="text" class="uwpmc-color-input form-control flex-fill px-5"
                                                   name="uwpmc_settings[style][goals][color]" data-name="color"
                                                   data-target=".uwpmc-goal, .uwpmc-goal-range"
                                                   value="<?php echo !empty($style['goals']['background-color']) ? esc_attr($style['goals']['color']) : ''; ?>"
                                                   maxlength="7" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($show_tab_styles) { ?>
                            <div id="uwpmc-style-tab"
                                 style="<?php echo(!empty($sidebar_data['advanced']['tabs']) ? 'display: block;' : 'display: none;'); ?>">
                                <h5 class="text-dark"><?php esc_html_e('Tabs', 'upsellwp-mini-cart'); ?></h5>
                                <div class="p-2 col-md-12 d-flex flex-column" style="gap: 8px;">
                                    <div class="d-flex align-items-center">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('Font size', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control" name="uwpmc_settings[style][tabs][font-size]"
                                                    data-name="font-size" data-target=".uwpmc-tabs">
                                                <option value="16px" <?php selected('16px', !empty($style['tabs']['font-size']) ? $style['tabs']['font-size'] : ''); ?>>
                                                    <?php esc_html_e('Default', 'upsellwp-mini-cart'); ?>
                                                </option>
                                                <option value="12px" <?php selected('12px', !empty($style['tabs']['font-size']) ? $style['tabs']['font-size'] : ''); ?>>
                                                    <?php esc_html_e('12px', 'upsellwp-mini-cart'); ?>
                                                </option>
                                                <option value="18px" <?php selected('18px', !empty($style['tabs']['font-size']) ? $style['tabs']['font-size'] : ''); ?>>
                                                    <?php esc_html_e('18px', 'upsellwp-mini-cart'); ?>
                                                </option>
                                                <option value="24px" <?php selected('24px', !empty($style['tabs']['font-size']) ? $style['tabs']['font-size'] : ''); ?>>
                                                    <?php esc_html_e('24px', 'upsellwp-mini-cart'); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                             style="gap: 4px;">
                                            <div style="position: relative;">
                                                <div class="col-md-2 p-0"
                                                     style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                    <input class="uwpmc-color-picker" type="color"
                                                           style="height: 32px; width: 32px; border: 0;">
                                                </div>
                                                <input type="text"
                                                       class="uwpmc-color-input form-control flex-fill px-5"
                                                       name="uwpmc_settings[style][tabs][background-color]"
                                                       data-name="background-color"
                                                       data-target=".uwpmc-tabs"
                                                       value="<?php echo !empty($style['tabs']['background-color']) ? esc_attr($style['tabs']['background-color']) : ''; ?>"
                                                       maxlength="7" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="col-md-4">
                                            <label class=" font-weight-medium">
                                                <?php esc_html_e('color', 'upsellwp-mini-cart'); ?>
                                            </label>
                                        </div>
                                        <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                             style="gap: 4px;">
                                            <div style="position: relative;">
                                                <div class="col-md-2 p-0"
                                                     style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                    <input class="uwpmc-color-picker" type="color"
                                                           style="height: 32px; width: 32px; border: 0;">
                                                </div>
                                                <input type="text"
                                                       class="uwpmc-color-input form-control flex-fill px-5"
                                                       name="uwpmc_settings[style][tabs][color]" data-name="color"
                                                       data-target=".uwpmc-tabs"
                                                       value="<?php echo !empty($style['tabs']['color']) ? esc_attr($style['tabs']['color']) : ''; ?>"
                                                       maxlength="7" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- advanced -->
                <div id="uwpmc-advanced" class="tab-pane fade" role="tabpanel" aria-labelledby="advanced-tab">
                    <div id="uwpmc-advanced-section">
                        <div id="uwpmc-banner-option">
                            <h5 class="text-dark"><?php esc_html_e('Banner', 'upsellwp-mini-cart'); ?></h5>
                            <div class="d-flex align-items-center" style="height: 32px;">
                                <div class="col-md-6">
                                    <label class=" font-weight-medium">
                                        <?php esc_html_e('Enable banner section', 'upsellwp-mini-cart'); ?>
                                    </label>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" id="uwpmc-show-banner-switch"
                                               class="custom-control-input"
                                               name="uwpmc_settings[advanced][banner][enabled]"
                                               data-target=".uwpmc-banners"
                                               data-property="flex"
                                               value="1" <?php if (!empty($sidebar_data['advanced']['banner']['enabled'])) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="uwpmc-show-banner-switch"></label>
                                    </div>
                                    <div class="btn btn-primary uwpmc-add-new-banner px-3 py-1"
                                         style="<?php echo (!empty($sidebar_data['advanced']['banner']['enabled'])) ? 'display: block;' : 'display: none;'; ?>">
                                        <?php esc_html_e('Add', 'upsellwp-mini-cart'); ?>
                                    </div>
                                    <div class="btn btn-outline-secondary uwpmc-close-banner px-3 py-1 d-none">
                                        <?php esc_html_e('Close', 'upsellwp-mini-cart'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="uwpmc-banner-section"
                                 style="<?php echo (!empty($sidebar_data['advanced']['banner']['enabled'])) ? 'display: block;' : 'display: none;'; ?>">
                                <div class="p-2 col-md-12 flex-column uwpmc-banner-table-container"
                                     style="gap: 4px; <?php echo esc_attr(!empty($sidebar_data['advanced']['banner']['list']) ? 'display: flex;' : 'display: none;'); ?>">
                                    <table id="uwpmc-banner-table" class="table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th><?php esc_html_e('Content', 'upsellwp-mini-cart'); ?></th>
                                            <th style="width: 20%;"><?php esc_html_e('Action', 'upsellwp-mini-cart'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody-light">
                                        <?php
                                        if (!empty($sidebar_data['advanced']['banner']['list'])) {
                                            foreach ($sidebar_data['advanced']['banner']['list'] as $banner_key => $banner) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="uwpmc-banner"
                                                             style="<?php echo 'background-color: ' . esc_attr($banner['background-color']) . ';' . 'color: ' . esc_attr($banner['color']) . ';'; ?>;">
                                                            <?php echo wp_kses_post($banner['content']); ?>
                                                            <input type="hidden"
                                                                   name="uwpmc_settings[advanced][banner][list][<?php echo esc_attr($banner_key); ?>][content]"
                                                                   value="<?php echo esc_attr($banner['content']); ?>">
                                                            <input type="hidden"
                                                                   name="uwpmc_settings[advanced][banner][list][<?php echo esc_attr($banner_key); ?>][background-color]"
                                                                   value="<?php echo esc_attr($banner['background-color']); ?>">
                                                            <input type="hidden"
                                                                   name="uwpmc_settings[advanced][banner][list][<?php echo esc_attr($banner_key); ?>][color]"
                                                                   value="<?php echo esc_attr($banner['color']); ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="uwpmc-banner-delete btn btn-danger"><?php esc_html_e('Delete', 'upsellwp-mini-cart'); ?></div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="uwpmc-advanced-banner-style" class="uwpmc-advanced-banner-builder"
                                     style="display: none;">
                                    <div>
                                        <div>
                                            <div class="uwpmc-wp-editor d-flex flex-column">
                                                <?php
                                                wp_editor('', 'banner', array(
                                                        'media_buttons' => false,
                                                        'quicktags' => array("buttons" => "link,em,strong,del,ins,close"),
                                                    )
                                                );
                                                ?>
                                                <div id="uwpmc-banner-preview" class="mt-2"
                                                     style="display: none; min-height: 48px; width: 100%; border: 1px solid #64748b;">
                                                    <div class="uwpmc-banner">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="col-md-4">
                                                <label class="custom-label font-weight-medium">
                                                    <?php esc_html_e('Text color', 'upsellwp-mini-cart'); ?>
                                                </label>
                                            </div>
                                            <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                                 style="gap: 4px;">
                                                <div style="position: relative">
                                                    <div class="col-md-2 p-0"
                                                         style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                        <input class="uwpmc-color-picker"
                                                               type="color"
                                                               style="height: 32px; width: 32px; border: 0;">
                                                    </div>
                                                    <input id="uwpmc-banner-color" type="text"
                                                           class="uwpmc-color-input form-control flex-fill px-5"
                                                           data-name="color"
                                                           data-target="#uwpmc-banner-preview .uwpmc-banner" value=""
                                                           maxlength="7" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="col-md-4">
                                                <label class="custom-label font-weight-medium">
                                                    <?php esc_html_e('Background color', 'upsellwp-mini-cart'); ?>
                                                </label>
                                            </div>
                                            <div class="uwpmc-color-inputs col-md-6 d-flex align-items-center"
                                                 style="gap: 4px;">
                                                <div style="position: relative;">
                                                    <div class="col-md-2 p-0"
                                                         style="position: absolute; top: 4px; left: 2px; right: 2px;">
                                                        <input class="uwpmc-color-picker" type="color"
                                                               style="height: 32px; width: 32px; border: 0;">
                                                    </div>
                                                    <input id="uwpmc-banner-background-color" type="text"
                                                           class="uwpmc-color-input form-control flex-fill px-5"
                                                           data-name="background-color"
                                                           data-target="#uwpmc-banner-preview .uwpmc-banner"
                                                           value="" maxlength="7" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <div id="uwpmc-preview-banner-content"
                                                 class="btn btn-primary m-1 px-3 py-1">
                                                <div class="uwpmc-banner-preview-btn">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <g id="Edit / Show">
                                                            <g id="Vector">
                                                                <path d="M3.5868 13.7788C5.36623 15.5478 8.46953 17.9999 12.0002 17.9999C15.5308 17.9999 18.6335 15.5478 20.413 13.7788C20.8823 13.3123 21.1177 13.0782 21.2671 12.6201C21.3738 12.2933 21.3738 11.7067 21.2671 11.3799C21.1177 10.9218 20.8823 10.6877 20.413 10.2211C18.6335 8.45208 15.5308 6 12.0002 6C8.46953 6 5.36623 8.45208 3.5868 10.2211C3.11714 10.688 2.88229 10.9216 2.7328 11.3799C2.62618 11.7067 2.62618 12.2933 2.7328 12.6201C2.88229 13.0784 3.11714 13.3119 3.5868 13.7788Z"
                                                                      stroke="currentColor" stroke-width="2"
                                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z"
                                                                      stroke="currentColor" stroke-width="2"
                                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="uwpmc-banner-preview-btn" style="display: none">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <g id="Edit / Hide">
                                                            <path id="Vector"
                                                                  d="M3.99989 4L19.9999 20M16.4999 16.7559C15.1473 17.4845 13.6185 17.9999 11.9999 17.9999C8.46924 17.9999 5.36624 15.5478 3.5868 13.7788C3.1171 13.3119 2.88229 13.0784 2.7328 12.6201C2.62619 12.2933 2.62616 11.7066 2.7328 11.3797C2.88233 10.9215 3.11763 10.6875 3.58827 10.2197C4.48515 9.32821 5.71801 8.26359 7.17219 7.42676M19.4999 14.6335C19.8329 14.3405 20.138 14.0523 20.4117 13.7803L20.4146 13.7772C20.8832 13.3114 21.1182 13.0779 21.2674 12.6206C21.374 12.2938 21.3738 11.7068 21.2672 11.38C21.1178 10.9219 20.8827 10.6877 20.4133 10.2211C18.6338 8.45208 15.5305 6 11.9999 6C11.6624 6 11.3288 6.02241 10.9999 6.06448M13.3228 13.5C12.9702 13.8112 12.5071 14 11.9999 14C10.8953 14 9.99989 13.1046 9.99989 12C9.99989 11.4605 10.2135 10.9711 10.5608 10.6113"
                                                                  stroke="currentColor" stroke-width="2"
                                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div id="uwpmc-insert-banner-content"
                                                 class="btn btn-primary m-1 px-3 py-1"><?php esc_html_e('Add', 'upsellwp-mini-cart'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="uwpmc-goal-range-option" class="mt-2">
                            <h5 class="text-dark">
                                <?php esc_html_e('Goals', 'upsellwp-mini-cart'); ?>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="col-md-6">
                                    <label class=" font-weight-medium">
                                        <?php esc_html_e('Enable goals section', 'upsellwp-mini-cart'); ?>
                                    </label>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" id="uwpmc-show-goal-switch" class="custom-control-input"
                                               name="uwpmc_settings[advanced][goals][enable]"
                                               data-target=".uwpmc-goals"
                                               value="1" <?php if (!empty($sidebar_data['advanced']['goals']['enable'])) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="uwpmc-show-goal-switch"></label>
                                    </div>
                                </div>
                            </div>
                            <div id="uwpmc-goal-method-section"
                                 style="<?php echo !empty($sidebar_data['advanced']['goals']['enable']) ? 'display: block;' : 'display: none;'; ?>">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label class="font-weight-medium">
                                            <?php esc_html_e('Free shipping', 'upsellwp-mini-cart'); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="uwpmc-show-free-shipping-goal"
                                                   class="custom-control-input"
                                                   name="uwpmc_settings[advanced][goals][list][free_shipping]"
                                                   data-target=".uwpmc-free-shipping"
                                                   value="1" <?php if (!empty($sidebar_data['advanced']['goals']['list']['free_shipping'])) echo 'checked'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-free-shipping-goal"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="uwpmc-recommendations-option" class="mt-2">
                            <h5 class="text-dark">
                                <?php esc_html_e('Recommendations', 'upsellwp-mini-cart'); ?>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="col-md-6">
                                    <label class=" font-weight-medium">
                                        <?php esc_html_e('Enable recommendation section', 'upsellwp-mini-cart'); ?>
                                    </label>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" id="uwpmc-show-recommendation-switch"
                                               class="custom-control-input"
                                               name="uwpmc_settings[advanced][recommendations][enable]"
                                               data-target=".uwpmc-recommended-items-section"
                                               value="1" <?php if (!empty($sidebar_data['advanced']['recommendations']['enable'])) echo 'checked'; ?>>
                                        <label class="custom-control-label"
                                               for="uwpmc-show-recommendation-switch"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="uwpmc-tabs-option" class="mt-2">
                            <?php foreach ($tabs_info as $tab_slug => $tab) { ?>
                                <h5 class="text-dark">
                                    <?php echo esc_html($tab['title']); ?>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label class="uwpmc-tab-section-enable-text font-weight-medium">
                                            <?php
                                            /* translators: %s: title */
                                            echo sprintf(esc_html__('Enable %s section', 'upsellwp-mini-cart'), esc_html($tab['title']));
                                            ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
                                                   id="uwpmc-show-<?php echo esc_attr($tab_slug); ?>-switch"
                                                   class="custom-control-input uwpmc-show-tab"
                                                   name="uwpmc_settings[advanced][tabs][<?php echo esc_attr($tab_slug); ?>][enable]"
                                                   data-target="#uwpmc-<?php echo esc_attr($tab_slug); ?>-button"
                                                   value="1"
                                                <?php if (!empty($sidebar_data['advanced']['tabs'][$tab_slug]['enable']) && !empty($tab['load'])) echo 'checked'; ?>
                                                <?php if (empty($tab['load'])) echo 'disabled'; ?>>
                                            <label class="custom-control-label"
                                                   for="uwpmc-show-<?php echo esc_attr($tab_slug); ?>-switch"></label>
                                        </div>
                                        <?php if (empty($tab['load'])) { ?>
                                            <div>
                                                <a class="thickbox open-plugin-details-modal"
                                                   href="<?php echo esc_url(network_admin_url('plugin-install.php?tab=plugin-information&plugin=checkout-upsell-and-order-bumps&TB_iframe=true&width=772&height=512')); ?>">
                                                    <?php esc_html_e('Install UpsellWP', 'upsellwp-mini-cart') ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- template preview  -->
    <div id="uwpmc-template-preview" class="col-4 mt-2 p-3">
        <?php \UWPMC\App\Controllers\MiniCart::loadWidgetAndSidebar(); ?>
    </div>

    <!-- Reset modal -->
    <div class="modal fade" id="uwpmc-warning-model" tabindex="-1" aria-labelledby="uwpmc-warning-model-label"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="uwpmc-warning-model-label"><?php esc_html_e('Activate theme', 'upsellwp-mini-cart'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="uwp-modal-message"></div>
                    <div class="text-danger uwp-modal-notice"></div>
                </div>
                <div class="modal-footer">
                    <button id="uwpmc-modal-yes-button" type="button" data-function="" data-theme=""
                            class="btn btn-primary" data-dismiss="modal">
                        <?php esc_html_e('Yes', 'upsellwp-mini-cart'); ?>
                    </button>
                    <button id="uwpmc-reset-button" type="button" class="btn btn-secondary" data-dismiss="modal">
                        <?php esc_html_e('No', 'upsellwp-mini-cart'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview slider -->
    <div class="uwpmc-preview-slider d-none" id="uwpmc-preview-theme" data-theme="">
        <div class="uwpmc-body">
            <div class="uwpmc-preview">
            </div>
        </div>
    </div>
</div>
