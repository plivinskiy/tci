(function( $ ) {
    'use strict';

    jQuery(document).ready(function(){
        wgl_verify_init();
        wgl_accordion();
        wgl_installer_plugins();
    });

    function wgl_verify_init(){
        var wait_load = false;
        var security, purchase_item, user_email, content, js_activation;
        var reset_security, user_acc, url_activation;
        var btn;
        jQuery(document).on('click', '.activate-license', function(e){
            e.preventDefault();
            if ( wait_load ) return;
            wait_load = true;
            security = jQuery(this).closest('.wgl-purchase').find('#security').val();
            user_email = jQuery(this).closest('.wgl-purchase').find('input[name="user_email"]').val();
            purchase_item = jQuery(this).closest('.wgl-purchase').find('input[name="purchase_item"]').val(),
            content = jQuery(this).closest('.wgl-purchase').find('input[name="content"]'),
            js_activation = jQuery(this).closest('.wgl-purchase').find('input[name="js_activation"]'),

            btn = jQuery(this);
            jQuery(btn).closest('.wgl-purchase').find('.notice-validation').remove();
            jQuery.ajax({
                type : "post",
                cache: false,
                async: true,
                url : ajaxurl,
                dataType: "json",
                data : {
                    action: "purchase_activation",
                    security: security,
                    purchase_code: purchase_item,
                    email: user_email,
                },
                beforeSend: function() {
                    // setting a timeout
                    btn.addClass('loading');
                },
                error: function(jqXHR, textStatus, errorThrown) {},
                success: function(response) {
                    if(!response){
                        wgl_verify_alternative(security, purchase_item, user_email);
                    }else{
                        if(response.error == 1){
                            var node_str = '<div class="notice-validation notice notice-error error" style="display: none;">';
                            node_str += response.message;
                            node_str  += '</div>';

                            jQuery(btn).closest('.wgl-purchase').append(node_str);
                            jQuery('.notice-validation').fadeIn();

                            if(response.status && 678 === response.status){
                                request_to_reset_purchase(btn);
                            }
                        }

                        if(response.success == 1){
                            var node_str = '<div class="notice-validation notice notice-success success" style="display: none;">';
                            node_str += response.message;
                            node_str  += '</div>';
                            jQuery(btn).closest('.wgl-purchase').append(node_str);
                            jQuery('.notice-validation').fadeIn();
                            setTimeout(function(){
                                window.location.reload();
                            }, 400);
                        }

                        btn.removeClass('loading');
                        wait_load = false;
                    }

                },
            });

            function request_to_reset_purchase(btn){

                jQuery('.register_purchase--form').on('click', function(e) {
                    // Show/Hide Tabs
                    var self = jQuery(this);
                    jQuery('.form-lost-purchase').fadeToggle();
                    jQuery('html,body').animate({scrollTop: self.offset().top},'slow');
                    e.preventDefault();
                });

                jQuery(document).on('click', '.reset-activate-license', function(e){
                    e.preventDefault();

                    if ( wait_load ) return;
                    wait_load = true;

                    reset_security = jQuery(this).closest('.wgl-reset-purchase').find('#reset-security-purshase').val();
                    user_acc = jQuery(this).closest('.wgl-reset-purchase').find('input[name="user_accout"]').val();

                    btn = jQuery(this);
                    jQuery(btn).closest('.wgl-reset-purchase').find('.notice-validation').remove();
                    jQuery.ajax({
                        type : "post",
                        cache: false,
                        async: true,
                        url : ajaxurl,
                        dataType: "json",
                        data : {
                            action: "reset_purchase",
                            security: reset_security,
                            purchase_code: purchase_item,
                            accout: user_acc,
                            email: user_email,
                        },
                        beforeSend: function() {
                            // setting a timeout
                            btn.addClass('loading');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {},
                        success: function(response) {
                            debugger;
                            if(!response){
                                wgl_reset_code(reset_security, purchase_item, user_email, user_acc);
                            }else{
                                if(response.error == 1){
                                    var node_str = '<div class="notice-validation notice notice-error error" style="display: none;">';
                                    node_str += response.message;
                                    node_str  += '</div>';

                                    jQuery('.form-lost-purchase').append(node_str);
                                    jQuery('.notice-validation').fadeIn();
                                }

                                if(response.success == 1){
                                    var node_str = '<div class="notice-validation notice notice-success success" style="display: none;">';
                                    node_str += response.message;
                                    node_str  += '</div>';
                                    jQuery('.form-lost-purchase').append(node_str);
                                    jQuery('.notice-validation').fadeIn();
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 400);
                                }

                                btn.removeClass('loading');
                                wait_load = false;

                            }

                        },
                    });
                });
            }

            function wgl_reset_code( security, purchase_item, user_email, user_acc ){
                var dataParams = { security: security,
                    purchase_code: purchase_item,
                    email: user_email,
                    accout: user_acc,
                    action: "purchase_js_reset",
                    domain_url: wgl_verify.domainUrl,
                    theme_name: wgl_verify.themeName };
                    dataParams = JSON.stringify( dataParams );
                jQuery.ajax({
                        type : "post",
                        cache: false,
                        async: true,
                        url : wgl_verify.wglUrlReset,
                        dataType: "json",
                        data : dataParams,
                        beforeSend: function() {},
                        error: function(jqXHR, textStatus, errorThrown) {
                            if(0 === jqXHR.readyState){
                                var node_str = '<div class="notice-validation notice notice-error error" style="display: none;">';
                                node_str += response.message;
                                node_str  += '</div>';

                                jQuery('.form-lost-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();
                            }
                        },
                        success: function(response) {
                            if(response.error == 1){
                                var node_str = '<div class="notice-validation notice notice-error error" style="display: none;">';
                                node_str += response.message;
                                node_str  += '</div>';

                                jQuery('.form-lost-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();
                            }

                            if(response.success == 1){
                                var node_str = '<div class="notice-validation notice notice-success success" style="display: none;">';
                                node_str += response.message;
                                node_str  += '</div>';
                                jQuery('.form-lost-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();
                                setTimeout(function(){
                                    window.location.reload();
                                }, 400);
                            }

                            btn.removeClass('loading');
                            wait_load = false;
                        },
                    });
            }

            function wgl_verify_alternative( security, purchase_item, user_email ){
                var dataParams = { security: security,
                    purchase_code: purchase_item,
                    email: user_email,
                    active_alternative_theme: 1,
                    action: "purchase_js_activation",
                    domain_url: wgl_verify.domainUrl,
                    theme_name: wgl_verify.themeName };
                    dataParams = JSON.stringify( dataParams );
                jQuery.ajax({
                        type : "post",
                        cache: false,
                        async: true,
                        url : wgl_verify.wglUrlActivate,
                        dataType: "json",
                        data : dataParams,
                        beforeSend: function() {},
                        error: function(jqXHR, textStatus, errorThrown) {
                            if(0 === jqXHR.readyState){
                                var node_str = '<div class="notice-validation notice notice-success success" style="display: none;">';
                                node_str += wgl_verify.message;
                                node_str  += '</div>';
                                jQuery(btn).closest('.wgl-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();
                                jQuery(content).val( "[]" );
                                jQuery(js_activation).val('1');
                                jQuery(btn).closest('.wgl-purchase').submit();
                            }
                        },
                        success: function(response) {
                             if(response.error == 1){
                                var node_str = '<div class="notice-validation notice notice-error error" style="display: none;">';
                                if(response.status && 678 === response.status){
                                    node_str += '<div class="register_purchase">';
                                    node_str += '<span class="register_purchase--title">' + wgl_verify.titleCodeRigistered + '</span><br/>';
                                    node_str +=  wgl_verify.messageCodeRigistered + '<br/><br/>';
                                    node_str += '<a class="register_purchase--form" href="#">' + wgl_verify.messageLostCode + '</a>';
                                    node_str += '</div>';
                                }else{
                                    node_str += response.message;
                                }
                                node_str  += '</div>';
                                jQuery(btn).closest('.wgl-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();

                                if(response.status && 678 === response.status){
                                    request_to_reset_purchase(btn);
                                }
                            }

                            if(response.success == 1){
                                var node_str = '<div class="notice-validation notice notice-success success" style="display: none;">';
                                node_str += wgl_verify.message;
                                node_str  += '</div>';
                                jQuery(btn).closest('.wgl-purchase').append(node_str);
                                jQuery('.notice-validation').fadeIn();
                                jQuery(content).val( JSON.stringify(response.content) );
                                jQuery(js_activation).val('1');
                                jQuery(btn).closest('.wgl-purchase').submit();

                            }
                            btn.removeClass('loading');
                            wait_load = false;
                        },
                    });
            }
        });
        jQuery(document).on('submit', '.deactivation_form', function(e){
            e.preventDefault();
            if ( wait_load ) return;
            wait_load = true;

            security = jQuery(this).find('#security').val();
            btn = jQuery(this).find('.deactivate_theme-license');

            var dataParams = { security: security,
                purchase_code: wgl_verify.purchaseCode,
                email: wgl_verify.email,
                deactivate_theme: 1,
                action: "purchase_js_deactivate",
                domain_url: wgl_verify.domainUrl,
                theme_name: wgl_verify.themeName };
                dataParams = JSON.stringify( dataParams );
            jQuery.ajax({
                    type : "post",
                    cache: false,
                    async: true,
                    url : wgl_verify.wglUrlDeactivate,
                    dataType: "json",
                    data : dataParams,
                    beforeSend: function(jqXHR, settings) {
                        btn.addClass('loading');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if(0 === jqXHR.readyState){
                            e.currentTarget.submit();
                        }
                    },
                    success: function(response) {
                        btn.removeClass('loading');
                        wait_load = false;
                        e.currentTarget.submit();
                    },
                });
        });
    }

    function wgl_installer_plugins() {

        let itemArray = [];
        let pluginDependency = [];
        let eventAllClick = false;

        jQuery(document).on('click', '.wgl-ajax-installer-plugins:not(.wgl-deactivate-now)', handlePluginAction);
        jQuery(document).on('click', '.wgl-deactivate-now', handleDeactivateAction);
        jQuery(document).on('click', '.wgl-wizard-all-plugins', handleAllPluginsAction);
    
        function handlePluginAction(e) {
            e.preventDefault();
            const link = jQuery(this);
            addLinkClasses(link);
            parsePlugins(link);
            hideNotice();
        }
    
        function handleDeactivateAction(e) {
            e.preventDefault();
            const link = jQuery(this);
            addLinkClasses(link);
            deactivatePlugin(link);
        }
    
        function handleAllPluginsAction(e) {
            e.preventDefault();
            eventAllClick = true;
    
            $('.item-wrapper .wgl-ajax-installer-plugins:not(.wgl-deactivate-now)').each(function() {
                itemArray.push($(this));
            });
    
            function activationAction() {
                if (itemArray.length) {
                    const link = $(itemArray.shift());
                    addLinkClasses(link);
                    parsePlugins(link, activationAction);
                }else if(pluginDependency.length){
                    const link = $(pluginDependency.shift());
                    addLinkClasses(link);
                    parsePlugins(link, activationAction);
                }
            }
    
            activationAction();
            hideNotice();
        }
    
        function showNotice($selector, $type, $message) {
            const noticeHTML = `<div class="wgl-notice ${$type}">${$message}</div>`;
            $selector.append(noticeHTML).fadeIn();
        }
    
        function hideNotice() {
            jQuery('.wgl-plugin-response .wgl-notice').each(function() {
                $( this ).fadeOut();
            });
        }
    
        function addLinkClasses(link) {
            const $wrapper = link.closest('.item-wrapper');
            $wrapper.addClass('wgl-loading');
            const $parent = link.closest('.wgl-theme-wizard');
            $parent.addClass('wgl-loading');
            $('.wgl-theme-wizard-footer').addClass('wgl-disabled');
            const action = link.data('action');
            link.text(wgl_verify[`${action}_process_plugin_btn_text`]);
        }
    
        function removeLoadingState(link) {
            const $wrapper = link.parents('.item-wrapper');
            $wrapper.removeClass('wgl-loading');
            const $parent = link.closest('.wgl-theme-wizard');
            $parent.removeClass('wgl-loading');
            jQuery('.wgl-theme-wizard-footer').removeClass('wgl-disabled');
        }
    
        function updatePageStatus(data) {
            jQuery('.wgl-theme-wizard').toggleClass('wgl-all-active', data.is_all_activated === 'yes');
            jQuery('.wgl-tgmpa_dashboard').toggleClass('wgl-all-required', data.required_plugins === 'no');
        }
    
        function verifyPluginState(link, callback) {
            let urlWithoutRedirect = ajaxurl;
            urlWithoutRedirect += (urlWithoutRedirect.includes('?') ? '&' : '?') + 'activate-multi=1';
            jQuery.ajax({
                url: urlWithoutRedirect,
                method: 'POST',
                data: {
                    action: 'wgl_check_plugins',
                    wgl_plugin: link.data('plugin'),
                },
                success(response) {
                    if (typeof response === 'object' && response !== null) {
                        if (response.status === 'success') {
                            updatePageStatus(response.data);
                        } else {
                            showNotice($('.wgl-plugin-response'), 'warning', response.message);
                            removeLoadingState(link);
                        }            
                    }else{
                        removeLoadingState(link);
                    }
                    callback(response);            

                }
            });
        }
    
        function togglePluginAction(actionBefore, actionAfter, link, response) {
            if (response?.data?.version) {
                link.closest('.item-wrapper')
                    .find('.wgl-plugin-version span')
                    .text(response.data.version);
            }
            
            const pluginsList = JSON.parse($('.wgl-installer-plugins').val());
            const pluginData = pluginsList[link.data('plugin')];
            const actionUrl = pluginData[`${actionAfter}_url`].replaceAll('&amp;', '&');
            
            link.removeClass(`wgl-${actionBefore}-now`)
                .addClass(`wgl-${actionAfter}-now`)
                .attr('href', actionUrl)
                .data('action', actionAfter)
                .text(wgl_verify[`${actionAfter}_plugin_btn_text`]);
        }
    
        function parsePlugins(link, callback = () => {}) {
            let urlWithoutRedirect = link.attr('href');
            urlWithoutRedirect += (urlWithoutRedirect.includes('?') ? '&' : '?') + 'activate-multi=1';
            jQuery.ajax({
                url: urlWithoutRedirect,
                method: 'POST',
                success(result) {
                    setTimeout(() => {
                        verifyPluginState(link, (response) => {
                            if ('success' === response.status && 'activate' === response.data.status) {
                                activatePlugin(link, callback);
                            } else {
                                removeLoadingState(link);
                                togglePluginAction('activate', 'deactivate', link, response);
                                callback();
                            }
                        });
                    }, 1000);
                }
            });
        }

        function activatePlugin(link, callback = () => {}) {
            const pluginsList = JSON.parse($('.wgl-installer-plugins').val());
            let activateUrl = pluginsList[link.data('plugin')]['activate_url'].replaceAll('&amp;', '&');
            activateUrl += (activateUrl.includes('?') ? '&' : '?') + 'activate-multi=1';
            jQuery.ajax({
                url: activateUrl,
                method: 'GET',
                success(result) {
                    if(jQuery(result).find('#message').hasClass('error') && !eventAllClick){
                        showNotice($('.wgl-plugin-response'), 'error', jQuery(result).find('#message').html());
                        removeLoadingState(link);
                    }else{
                        if(jQuery(result).find('#message').hasClass('error') && eventAllClick){
                            pluginDependency.push(link);
                        }
                        verifyPluginState(link, (response) => {
                            if ('success' === response.status || 'woocommerce-ajax-filters' === link.data('plugin') ) {
                                removeLoadingState(link);
                                togglePluginAction('activate', 'deactivate', link, response);
                                togglePluginAction('install', 'deactivate', link, response);
                                togglePluginAction('update', 'deactivate', link, response);
                                callback();
                            }
                        });                        
                    }

                }
            });
        }
    
        function deactivatePlugin(link) {
            let urlWithoutRedirect = ajaxurl;
            urlWithoutRedirect += (urlWithoutRedirect.includes('?') ? '&' : '?') + 'activate-multi=1';
            jQuery.ajax({
                url: urlWithoutRedirect,
                method: 'POST',
                data: {
                    action: 'wgl_deactivate_plugin',
                    wgl_plugin: link.data('plugin')
                },
                success(response) {
                    if (response.status === 'error') {
                        showNotice($('.wgl-plugin-response'), 'warning', response.message);
                        removeLoadingState(link);
                        return;
                    }
    
                    verifyPluginState(link, (response) => {
                        if ('success' === response.status && 'activate' === response.data.status) {
                            removeLoadingState(link);
                            togglePluginAction('deactivate', 'activate', link, response);
                        } else {
                            deactivatePlugin(link);
                        }
                    });
                }
            });
        }
    }
    
})( jQuery );

function wgl_accordion(){
    jQuery('body').on('click', '.wgl_accordion_heading', function(e){
        e.preventDefault();
        var parent = jQuery(this).parent('.wgl_accordion_wrapper');
        var body =  jQuery(parent).children('.wgl_accordion_body');

        if(jQuery(parent).hasClass('open'))
        {
            jQuery(body).slideUp('fast');
            jQuery(parent).removeClass('open').addClass('close');
        } else {
            jQuery(body).slideDown('fast');
            jQuery(parent).removeClass('close').addClass('open');
        }
    });
}
