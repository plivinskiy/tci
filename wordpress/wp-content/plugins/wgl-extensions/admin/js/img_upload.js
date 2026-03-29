(function($) {

    'use strict';
    $(document).ready( function() {
        $('body').on('click','.wgl_extensions_media_upload', function(e) {
            var mediaImg = $(this).parent('p').find('img');          
            var mediaUrl = $(this).parent('p').find('.wgl_extensions_media_url');
            if(0 === mediaUrl.length){
                var theme_option =  wgl_verify.themeName.toLowerCase();
                mediaUrl = $(this).parent('p').find('.' + theme_option + '_media_url');
            }

            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use This Image',
                },
                library: {
                    type: 'image'
                },
                multiple: false,
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $(mediaImg).attr('src', attachment.url);
                $(mediaUrl).val(attachment.url);
            })
            .open();
        });

        $('body').on('click','.wgl_extensions_media_upload_delete', function(e) {
            $(this).parent('p').find('img').attr('src', '');
            $(this).parent('p').find('.wgl_extensions_media_url').val('');
            var theme_option =  wgl_verify.themeName.toLowerCase();
            $(this).parent('p').find('.' + theme_option + '_media_url').val('');
        });

        jQuery('.wgl-mega-menu_wrapper.megamenu-lib-ext').each(function (index) {
            let mega_menu_enabled = jQuery(this).find('.edit-menu-item-megamenu-enable').val();
            if (mega_menu_enabled.length > 0) {
                if ('links' === mega_menu_enabled) {
                    jQuery(this).find('.field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos').fadeOut();
                    jQuery(this).children().not('.field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos').fadeIn();
                } else if ('elementor' === mega_menu_enabled) {
                    jQuery(this).children().not('.field-megamenu-enable').fadeOut();
                    jQuery(this).find('.field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos').fadeIn();
                } else {
                    jQuery(this).children().not('.field-megamenu-enable').fadeOut();
                }
            }
        });

        jQuery('body').on('change', '.wgl-mega-menu_wrapper.megamenu-lib-ext .edit-menu-item-megamenu-enable', function () {
            let currentItem = jQuery(this).val();
            if ('links' === currentItem) {
                jQuery(this).closest('.wgl-mega-menu_wrapper').find('.field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos').fadeOut();
                jQuery(this).closest('.wgl-mega-menu_wrapper').children().not('.field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos').fadeIn();
            } else if ('elementor' === currentItem) {
                jQuery(this).closest('.wgl-mega-menu_wrapper').children().not('.field-megamenu-enable').fadeOut();
                jQuery(this).closest('.wgl-mega-menu_wrapper').find(".field-megamenu-e-builder,.field-megamenu-width-e,.field-megamenu-pos").fadeIn();
            } else {
                jQuery(this).closest('.wgl-mega-menu_wrapper').children().not('.field-megamenu-enable').fadeOut();
            }
        });

    });
})(jQuery);