jQuery(function ($) {
    let uwpmc_ajx_url = uwpmc_admin_script_data.ajax_url;
    let uwpmc_default_data = uwpmc_admin_script_data.template_data;
    let uwpmc_theme_styles = uwpmc_admin_script_data.theme_styles;
    let uwpmc_nonce = uwpmc_admin_script_data.nonce;
    let uwpmc_messages = uwpmc_admin_script_data.messages || [];

    const uwpmc_admin_view = {
        init: function () {
            this.event_listeners();
            this.handle_tab_change();
        },

        // to reset template styles
        reset_theme_styles: function (theme_key) {
            $.each(uwpmc_theme_styles[theme_key], function (section_name, section_property) {
                $.each(section_property, function (property_name, property_value) {
                    $('#uwpmc-style-section').find('[name="uwpmc_settings[style][' + section_name + '][' + property_name + ']"]').val(property_value);
                    $('#uwpmc-style-section').find('[name="uwpmc_settings[style][' + section_name + '][' + property_name + ']"]').trigger('input');
                });
            });
            $('.uwpmc-slider-border').trigger('change');
            $('.uwpmc-cart-background').trigger('input');
        },

        // to handle tab changes
        handle_tab_change: function () {
            var hash = window.location.hash;
            hash && $('#uwpmc-admin-page .nav-tabs button[data-target="' + hash + '"]').tab('show');

            $('#uwpmc-admin-page .nav-tabs button').click(function (e) {
                let target = $(this).data('target');
                if (target) {
                    $(this).tab('show');
                    var scrollmem = $('body').scrollTop();
                    window.location.hash = target;
                    $('html,body').scrollTop(scrollmem);
                }
            });
        },

        update_tab_preview: function () {
            let visible_tabs = $('#uwpmc-template-preview .uwpmc-tabs').find('button:visible').length;
            if (visible_tabs < 2 && !$('#uwpmc-template-preview #uwpmc-cart-button').is(':hidden')) {
                $('#uwpmc-template-preview #uwpmc-cart-button, #uwpmc-style-tab').hide();
                $('#uwpmc-template-preview .uwpmc-tabs').css('border', 'none');
                $('#uwpmc-style-tab :input').attr('disabled', true);
            } else {
                $('#uwpmc-template-preview #uwpmc-cart-button, #uwpmc-style-tab').show();
                $('#uwpmc-template-preview .uwpmc-tabs').css('border', 'thin solid');
                $('#uwpmc-template-preview .uwpmc-tabs').css('border-color', $('.uwpmc-border-color .uwpmc-color-input').val());
                $('#uwpmc-style-tab :input').attr('disabled', false);
                $("#uwpmc-style-tab :input").trigger('input');
            }
        },

        event_listeners: function () {
            // to save function.
            $("#uwpmc-admin-page #uwpmc-page-save").click(function () {
                $('#uwpmc-page-form').submit();
                $(this).attr('disabled', true);
            });

            // to modal permission function.
            $('#uwpmc-modal-yes-button').click(function () {
                if ($(this).data('function') === 'activate') {
                    $('#uwpmc-preview-theme').addClass('d-none');
                    $('#uwpmc-template-preview').removeClass('d-none');
                    $('.uwpmc-active-theme_key').val($(this).data('theme'));
                    uwpmc_admin_view.reset_theme_styles($(this).data('theme'));
                    $("#uwpmc-admin-page #uwpmc-page-save").trigger('click');
                }
            });

            // to display color picker value as hex value.
            $(document).on('input', '#uwpmc-admin-page .uwpmc-color-inputs .uwpmc-color-picker', function () {
                $(this).closest('.uwpmc-color-inputs').find('.uwpmc-color-input').val($(this).val()).trigger('input');
            });

            // to display color of hex value.
            $(document).on('input', '#uwpmc-admin-page .uwpmc-color-inputs .uwpmc-color-input', function () {
                if ($(this).val() && !/^#[0-9a-fA-F]{6}$/i.test($(this).val())) {
                    $(this).addClass('border-danger');
                } else {
                    $(this).removeClass('border-danger');
                }
                $(this).closest('.uwpmc-color-inputs').find('.uwpmc-color-picker').val($(this).val());
            });

            $('#uwpmc-admin-page .uwpmc-color-inputs .uwpmc-color-input').trigger('input');

            // to update theme styles
            $('#uwpmc-admin-page #uwpmc-style-section').on('input', ':input', function () {
                if (!$(this).data('name')) {
                    return;
                }
                if ($(this).data('name') === 'width') {
                    $(this).closest('#uwpmc-style-section').find('#uwpmc-cart-width').val($(this).val())
                }
                if ($(this).data('name') === 'border-radius') {
                    $(this).closest('#uwpmc-style-section').find('#uwpmc-border-radius').val($(this).val())
                    $('#uwpmc-template-preview').find($(this).data('target')).css($(this).data('name'), $(this).val() + 'px');
                }
                if ($(this).data('name') === 'border-width') {
                    $('#uwpmc-template-preview').find($(this).data('target'))
                        .css('border', ($(this).val() !== 'none') ? $(this).val() + ' solid' : $(this).val());
                }
                if ($(this).data('name') === 'degree') {
                    $(this).closest('#uwpmc-style-section').find('#uwpmc-gradient-degree').val($(this).val())
                }
                if ($(this).data('name') === 'background' && $(this).data('target') === '.uwpmc-sidebar') {
                    $('#uwpmc-template-preview').find($(this).data('target')).css($(this).data('name'), '');
                }
                $('#uwpmc-template-preview').find($(this).data('target')).css($(this).data('name'), $(this).val());
            });

            // to update banner style
            $('#uwpmc-admin-page #uwpmc-advanced #uwpmc-advanced-banner-style').on('input', ':input', function () {
                if (!$(this).data('name')) {
                    return;
                }
                $('#uwpmc-admin-page #uwpmc-advanced').find($(this).data('target')).css($(this).data('name'), $(this).val());
            })

            // to update custom and advanced section
            $('#uwpmc-admin-page #uwpmc-customize, #uwpmc-admin-page #uwpmc-advanced').on('input', ':input', function () {
                if (!$(this).data('target')) {
                    return;
                }
                if ($(this).attr('type') === 'checkbox') {
                    $('#uwpmc-template-preview').find($(this).data('target')).css('display', $(this).prop('checked')
                        ? ($(this).data('property') ? $(this).data('property') : 'block')
                        : 'none');
                    if ($(this).hasClass('uwpmc-show-tab')) {
                        uwpmc_admin_view.update_tab_preview();
                    }
                } else {
                    $('#uwpmc-template-preview').find($(this).data('target')).html($(this).val());
                }
            });

            // to prevent click events in preview
            $("#uwpmc-template-preview").click(function () {
                return false;
            });

            // sidebar background type section
            $(".uwpmc-cart-background").on('input', function () {
                if ($(this).val() === 'gradient') {
                    $('.uwpmc-gradient-background').removeClass('d-none').addClass('d-flex').trigger('input');
                    $('.uwpmc-gradient-background :input').prop('disabled', false);
                    $('.uwpmc-solid-background').removeClass('d-flex').addClass('d-none');
                    $('.uwpmc-solid-background :input').prop('disabled', true);
                } else {
                    $('.uwpmc-solid-background').removeClass('d-none').addClass('d-flex');
                    $('.uwpmc-solid-background :input').prop('disabled', false).trigger('input');
                    $('.uwpmc-gradient-background').removeClass('d-flex').addClass('d-none');
                    $('.uwpmc-gradient-background :input').prop('disabled', true);
                }
            });

            $('#uwpmc-style-section').ready(function () {
                $('.uwpmc-cart-background').trigger('input');
            })

            $('.uwpmc-gradient-background').on('input', function () {
                let gradient_value = 'linear-gradient(' +
                    $(this).find('.uwpmc-gradient-degree').val() + 'deg' + ', ' +
                    $(this).find('.uwpmc-gradient-color-1').val() + ', ' +
                    $(this).find('.uwpmc-gradient-color-2').val() +
                    ')';

                $('#uwpmc-template-preview .uwpmc-sidebar').css('background', gradient_value);
            });

            // to update slider border style
            $('.uwpmc-slider-border').on('change', function () {
                if ($(this).val() === 'none') {
                    $('.uwpmc-border-color').removeClass('d-flex').addClass('d-none');
                } else {
                    $('.uwpmc-border-color').removeClass('d-none').addClass('d-flex');
                }
                $('.uwpmc-border-color :input').trigger('input');
                $('.uwpmc-border-color :input').attr('disabled', ($(this).val() === 'none'))
            });

            // to update custom switch functions
            $('#uwpmc-show-cart-switch').on('click', function () {
                $('.uwpmc-cart-button-details').toggle();
                $('.uwpmc-cart-button-details :input').attr('disabled', !$(this).is(':checked'));
            });

            // to update custom switch functions
            $('#uwpmc-show-widget-switch').on('click', function () {
                $('.uwpmc-widget-details').toggleClass('d-none d-flex');
            });

            $('#uwpmc-show-recommendation-switch').on('click', function () {
                $('#uwpmc-style-recommendation').toggle();
                $('#uwpmc-style-recommendation :input').attr('disabled', !$(this).is(':checked'));
            });

            $('#uwpmc-show-goal-switch').on('click', function () {
                $('#uwpmc-style-goals').toggle();
                $('#uwpmc-style-goals :input').attr('disabled', !$(this).is(':checked'));
                $('#uwpmc-goal-method-section').toggle();
                $('#uwpmc-goal-method-section :input').attr('disabled', !$(this).is(':checked'));
            });

            $('.uwpmc-price-format').on('change', function () {
                $('.uwpmc-item-price').toggle();
                $('.uwpmc-item-subtotal').toggle();
            });

            $('#uwpmc-show-banner-switch').on('click', function () {
                $('.uwpmc-add-new-banner, .uwpmc-banner-section').toggle();
                $('.uwpmc-banner-section :input').attr('disabled', !($(this).is(':checked')));
                if (!($(this).is(':checked'))) {
                    $('.uwpmc-add-new-banner, .uwpmc-advanced-banner-builder').hide();
                    $('.uwpmc-close-banner').addClass('d-none');
                } else if ($('#uwpmc-banner-table tr').length > 1) {
                    $('.uwpmc-banner-table-container').show();
                }
            });

            // to delete banner
            $('#uwpmc-banner-table').on('click', '.uwpmc-banner-delete', function () {
                $(this).closest('tr').remove();
                $('.uwpmc-banners').children().eq($(this).closest('tr').index()).remove();
                $('.uwpmc-add-new-banner').show();
                if ($('#uwpmc-banner-table tr').length <= 1) {
                    $('.uwpmc-banner-table-container').hide();
                }
            });

            // to preview banner
            $('#uwpmc-preview-banner-content').on('click', function () {
                let content = $('#banner_ifr').contents().find("html p");
                content.find('br[data-mce-bogus="1"]').remove();
                let new_content = content.html();
                $('#wp-banner-wrap').toggle();
                $('#uwpmc-banner-preview').css('display', ($('#uwpmc-banner-preview').css('display') === 'flex' ? 'none' : 'flex'));
                $('#uwpmc-banner-preview .uwpmc-banner').html(new_content);
                $('.uwpmc-banner-preview-btn').toggle();
            });

            // to add banner
            $('.uwpmc-add-new-banner').on('click', function () {
                $(this).hide();
                $('.uwpmc-close-banner').removeClass('d-none');
                $('.uwpmc-banner-table-container').hide();
                $('.uwpmc-banner-section').css('display', 'block');
                $('#wp-banner-wrap').css('display', 'block');
                $('#uwpmc-banner-preview').css('display', 'none');
                $('.uwpmc-advanced-banner-builder').show();
                $("#uwpmc-banner-background-color").val('#' + Math.floor(Math.random() * 16777215).toString(16));
                $("#uwpmc-banner-color").val('#' + Math.floor(Math.random() * 16777215).toString(16));
                $('#uwpmc-admin-page .uwpmc-color-inputs .uwpmc-color-input').trigger('input');
            });

            $('#uwpmc-insert-banner-content').on('click', function () {
                $('#uwpmc-preview-banner-content').trigger('click');
                let timestamp = (+new Date);
                let new_content = $('#uwpmc-banner-preview').html();
                $('#uwpmc-banner-table tr:last').after('<tr>' +
                    '<td>' +
                    new_content +
                    '<textarea name="uwpmc_settings[advanced][banner][list][' + timestamp + '][content]" style="display: none;">' + $('#uwpmc-banner-preview .uwpmc-banner').html() + '</textarea>' +
                    '<input type="hidden" name="uwpmc_settings[advanced][banner][list][' + timestamp + '][background-color]" value="' + $('#uwpmc-banner-background-color').val() + '">' +
                    '<input type="hidden" name="uwpmc_settings[advanced][banner][list][' + timestamp + '][color]" value="' + $('#uwpmc-banner-color').val() + '">' +
                    '</td>' +
                    '<td><div class="uwpmc-banner-delete btn btn-danger">' + uwpmc_messages['banner_delete'] + '</div></td>' +
                    '</tr>'
                );
                $('.uwpmc-banner-table-container').show();
                $('.uwpmc-advanced-banner-builder').hide();
                $('#uwpmc-admin-page #uwpmc-advanced .uwpmc-color-inputs .uwpmc-color-input').trigger('input');
                $('#uwpmc-template-preview .uwpmc-banners').append(new_content);
                $('#banner_ifr').contents().find("html p").html('');
                $('.uwpmc-close-banner').addClass('d-none');
                $('.uwpmc-add-new-banner').show();
            });

            $('.uwpmc-close-banner').on('click', function () {
                $('.uwpmc-banner-table-container').show();
                $('.uwpmc-advanced-banner-builder').hide();
                $(this).addClass('d-none');
                $('.uwpmc-add-new-banner').show();
                if ($('#uwpmc-banner-table tr').length <= 1) {
                    $('.uwpmc-banner-table-container').hide();
                }
            });

            // activate theme
            $('.uwpmc-theme-activate').on('click', function () {
                $('#uwpmc-modal-yes-button').attr('data-function', 'activate');
                $('#uwpmc-modal-yes-button').attr('data-theme', $(this).data('theme'));
                $('.uwp-modal-message').html(uwpmc_messages.theme_activate_waring);
                $('.uwp-modal-notice').html(uwpmc_messages.theme_activate_notice);
            });

            // reset style values
            $('#uwpmc-theme-reset').on('click', function () {
                uwpmc_admin_view.reset_theme_styles($(this).data('theme'));
            });

            // theme edit section
            $('.uwpmc-theme-edit').on('click', function () {
                $('#uwpmc-theme-section').removeClass('d-flex').addClass('d-none');
                $('#uwpmc-style-section').toggleClass('d-none');
                $('#uwpmc-preview-theme .uwpmc-theme').removeClass('d-flex').addClass('d-none');
                $('#uwpmc-template-preview').removeClass('d-none');
                $('#uwpmc-back-to-theme').removeClass('d-none');
                $('#uwpmc-theme-reset').removeClass('d-none');
                $(this).hide();
            });

            // to close preview section
            $('#uwpmc-back-to-theme').on('click', function () {
                $('#uwpmc-theme-section').removeClass('d-none').addClass('d-flex');
                $('#uwpmc-style-section').toggleClass('d-none');
                $('#uwpmc-template-preview').removeClass('d-none');
                $('#uwpmc-theme-reset').addClass('d-none');
                $('.uwpmc-theme-edit').show();
                $(this).addClass('d-none');
            });

            // preview theme
            $('.uwpmc-theme-preview').on('click', function () {
                if ($('#uwpmc-preview-theme').data('theme') === $(this).data('theme')) {
                    $('#uwpmc-template-preview').removeClass('d-none');
                    $('#uwpmc-preview-theme').addClass('d-none');
                    $('#uwpmc-preview-theme').data('theme', '');
                    $('#uwpmc-preview-theme .uwpmc-preview').html('');
                } else {
                    $('#uwpmc-template-preview').addClass('d-none');
                    $('#uwpmc-preview-theme').removeClass('d-none');
                    let theme_content = $(this).closest('.uwpmc-theme').find('#uwpmc-theme-layout').html();
                    $('#uwpmc-preview-theme').data('theme', $(this).data('theme'));
                    $('#uwpmc-preview-theme .uwpmc-preview').html(theme_content);
                }
            });

            // preview active theme
            $('.uwpmc-active-theme-preview').on('click', function () {
                $('#uwpmc-preview-theme').data('theme', '');
                $('#uwpmc-preview-theme .uwpmc-preview').html('');
                $('#uwpmc-preview-theme').removeClass('d-flex').addClass('d-none');
                $('#uwpmc-template-preview').removeClass('d-none');
            });

            // To slide banner.
            $(document).ready(function () {
                let leftWidth = 0;
                let slider = $("#uwpmc-template-preview .uwpmc-banners");
                if (slider.length > 0) {
                    // Move slide every 3 seconds
                    setInterval(function () {
                        leftWidth += slider[0].clientWidth;
                        if ((leftWidth + 10) < slider[0].scrollWidth) {
                            slider.animate({
                                scrollLeft: '+=' + slider[0].clientWidth,
                                behaviour: 'smooth'
                            }, 100);
                        } else {
                            slider.animate({
                                scrollLeft: '=' + 0,
                                behaviour: 'smooth'
                            });
                            leftWidth = 0;
                        }
                    }, 3000);
                }
            });
        }
    }

    /* Init */
    $(document).ready(function () {
        uwpmc_admin_view.init();
    });
});