/* global redux_change, wp */

(function($) {
    "use strict";
    $(document).ready(function() {
        $.fn.wglImporter();
    });
    $.fn.wglImporter = function() {

        $('.wgl-custom-pages .select2').select2({placeholder: "Select a Page"});

        $('.wgl_import_option').on('change', function() {
            if(0 === $(this).val().indexOf('partial')){
                jQuery('.wrap-importer').hide();
                let part = $(this).val().replace('partial', '');
                jQuery('.wrap-importer.pages'+part).show();
                jQuery('.wrap-importer.partial-options'+part).show();
                $('.select2-custom-pages'+part).select2();
            }else{
                jQuery('.wrap-importer').hide();
            }
        });

        var Progress = {
            show: function (){
                jQuery(this).find('.importer_status').css({ 'opacity' : '1'});
            },
            hide: function (){
                jQuery(this).find('.importer_status').css({ 'opacity' : '0'});
            },
            addPercents: function (i, count) {
                var container = 100 / count;
                container = container.toFixed(1);
                i--;
                
                var number = i * container;
                number = number > 99 ? 100 : number;                
                this.find('.importer_status .progressbar .progressbar_filled').css(
                    'width', number + '%'
                );
                this.find('.importer_status .progressbar .progressbar_content').css(
                    'width', number + '%'
                );

                this.find('.importer_status .progressbar_value').html((Math.ceil(number * 10) / 10).toFixed(1) + '%');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.warn('was 500 (Internal Server Error) but we try to load import again');
                if (typeof this.tryCount !== "number") this.tryCount = 1;
                switch (true) {
                    case (this.tryCount < 10):
                        this.tryCount++;
                        this.data = this.data + '&re_import_item=1';
                        jQuery.post(this).fail(Progress.error);
                        break;
                    default:
                        alert('There was an error importing demo content. Please reload page and try again.');
                }
            }
        }

        $('.theme-actions .import-demo-data').unbind('click').on('click', function(e) {
            e.preventDefault();

            var option_name = jQuery(this).closest('.wgl_importer').find('.wgl_import_option').val();
            var parent = jQuery(this).closest('.themes');
            
            var message = 'Import Demo Content?';
            var r = confirm(message);
            if (r == false) return;
                   
            Progress.show.call(parent);

            var data = jQuery(this).data();    
            
            var custom_pages, count_pages;
            count_pages = 1;

            if(0 === option_name.indexOf('partial')){
                let part = option_name.replace('partial', '');
                part = part.replace('_', '');
                custom_pages = jQuery(this).closest('.themes').find('.custom-pages'+part).find('.select2-custom-pages').val();
                data.custom_pages = true;
                data.type_option = [];
                if(custom_pages){
                    count_pages = custom_pages.length;
                    data.type_option.push('custom_pages');
                    data.selectedPages = JSON.parse(JSON.stringify(custom_pages));                    
                }
                if(jQuery(this).closest('.themes').find('#widgets'+part).is(":checked")){
                    data.type_option.push('widgets');
                    count_pages++;
                }
                if(jQuery(this).closest('.themes').find('#options'+part).is(":checked")){
                    data.type_option.push('options');
                    count_pages++;
                }
                if(jQuery(this).closest('.themes').find('#rev-slider'+part).is(":checked")){
                    data.type_option.push('rev_slider');
                    count_pages++;
                }
                var selectedCPT = {};
                jQuery(this).closest('.themes').find('.сpt-wrapper'+part+' input').each(function(){
                    if(jQuery(this).is(":checked")){
                        selectedCPT[jQuery(this).data('folder')] =  jQuery(this).attr('name');
                        count_pages++;
                    }
                }); 

                data.selectedCPT = JSON.parse(JSON.stringify(selectedCPT));
                data.type_option = JSON.parse(JSON.stringify(data.type_option));
            }else if(0 === option_name.indexOf('all')){
                count_pages = 10;
            }

            data.action = "wgl_importer";
            data.demo_import_pages_id = parent.find('.pages').attr("data-demo-id");
            data.demo_import_full_id = parent.find('.full').attr("data-demo-id");
            data.demo_import_cpt_id = parent.find('.cpt').attr("data-demo-id");
            data.nonce = parent.attr("data-nonce");
            data.type = 'import-demo-content';
            data.content = 1;
            data.count_pages = count_pages;

            var new_demo = option_name.replace('all', '');
            new_demo = new_demo.replace('partial', '');
            new_demo = new_demo.replace('_', '');

            if(0 < new_demo.length){
                data.demo_content = new_demo;
                data['demo_import_' + new_demo + '_id'] = parent.find('.' + new_demo).attr("data-demo-id");
            }else{
                if(data.hasOwnProperty('demo_content')){
                    delete data.demo_content;
                }
            }
            
            parent.find('.wgl_image').css('opacity', '0.5');
            parent.find('.import__select').addClass('active_import');
            jQuery('body').addClass('wgl-active_import');
            parent.find('.import__select .overlay__import').css('opacity', '0.5');
            parent.find('#info-opt-info-success').hide();

            loadContent(parent, data, count_pages);            

            return false;
        });

        $('.skins_importer .wgl-skins-container .wrap-importer').on('click', function(e) {
            $('.skins_importer .wgl-skins-container .wrap-importer').removeClass('selected');
            $(this).addClass('selected');
            jQuery(this).closest('.wgl_importer').find('.import-demo-skin-data').removeClass('disabled');
        });

        $('.theme-actions .import-demo-skin-data').unbind('click').on('click', function(e) {
            e.preventDefault();
            var option_name = jQuery(this).closest('.wgl_importer').find('.wgl_import_option').val();
            var selected = jQuery(this).closest('.wgl_importer').find('.wrap-importer.selected');
            if(!selected.length){
                alert('Selected Items');
                return;
            }
            
            var parent = jQuery(this).closest('.themes');
            
            var message = 'Import Demo Content?';
            var r = confirm(message);
            if (r == false) return;
                   
            Progress.show.call(parent);

            var data = jQuery(this).data();    
            var count_pages = 0;

            data.type_skin_option = [];

            if(0 === option_name.indexOf('partial')){
                if(jQuery(this).closest('.themes').find('#content').is(":checked")){
                    data.type_skin_option.push('content');
                    count_pages = 10;
                }
                if(jQuery(this).closest('.themes').find('#widgets').is(":checked")){
                    data.type_skin_option.push('widgets');
                    count_pages++;
                }
                if(jQuery(this).closest('.themes').find('#options').is(":checked")){
                    data.type_skin_option.push('options');
                    count_pages++;
                }
                if(jQuery(this).closest('.themes').find('#rev-slider').is(":checked")){
                    data.type_skin_option.push('rev_slider');
                    count_pages++;
                }
            }else{
                count_pages = 13;
                data.type_skin_option.push('content');
                data.type_skin_option.push('widgets');
                data.type_skin_option.push('options');
                data.type_skin_option.push('rev_slider');
                data.type_skin_option.push('homepage');
            }
            data.type_skin_option = JSON.parse(JSON.stringify(data.type_skin_option));

            data.action = 'wgl_importer';
            data.demo_import_full_id = selected.attr("data-demo-id");
            data.nonce = selected.attr("data-nonce");
            data.type = 'import-demo-content';
            data.active_skin = true;
            data.content = 1;
            data.count_pages = count_pages;

            parent.find('.wgl_image').css('opacity', '0.5');
            parent.find('.import__select').addClass('active_import');
            jQuery('body').addClass('wgl-active_import');
            parent.find('.import__select .overlay__import').css('opacity', '0.5');
            parent.find('#info-opt-info-success').hide();

            loadContent(parent, data, count_pages);            

            return false;
        });

        function loadContent(parent, data, count = false) {
            jQuery.post(ajaxurl, data, function(response) {
                //console.log(response);
                //debugger;
                if (response.length > 0 && response.match(/Have fun!/gi)) {
                    data.content++;
                    var itemCount = count ? count : 10;
                    Progress.addPercents.call(parent, data.content, itemCount);
                    
                    if (data.content > itemCount) {
                        console.log('Finished');
                        parent.find('.wgl_image').css('opacity', '1');
                        parent.find('.import__select').removeClass('active_import');
                        jQuery('body').removeClass('wgl-active_import');
                        parent.find('.import__select .overlay__import').css('opacity', '0');
                        parent.find('#info-opt-info-success').show('slow');
                    } else {
                        loadContent(parent, data, count);
                    }
                } else if(response.match(/cURL error/gi)){
                    //debugger;
                    if(data.without_image){
						var $temp = jQuery('<div>').html(response);
						var fullText = $temp.find('p.notice.notice-error').first().text().trim();
						var curlErrorMatch = fullText.match(/cURL error.*$/i);
						var errorText = curlErrorMatch ? curlErrorMatch[0] : 'Unknown cURL error.';
						alert('There was an error importing demo content:\n\n' + errorText);
                    }
                    data.without_image = true;
                    data.error_log = response;
                    console.log('Connection timeout');
                    loadContent(parent, data, count);
                }else if($('<div>').html(response).find('.choose-skin').length > 0){
                    alert($('<div>').html(response).find('.choose-skin').text());
                    parent.find('.wgl_image').css('opacity', '1');
                    parent.find('.import__select').removeClass('active_import');
                    jQuery('body').removeClass('wgl-active_import');
                    parent.find('.import__select .overlay__import').css('opacity', '0');
                    Progress.hide.call(parent);
                }else { 
                    parent.find('.import-demo-data').show();
                    alert('There was an error importing demo content: \n\n' + response.replace(/(<([^>]+)>)/gi, ""));
                }    
            }).fail(Progress.error)
        }

        $('.not-license').unbind('click').on('click', function(e) {
            e.preventDefault();
            window.location.href = jQuery(this).data('url');
            return false;
        });
    };

})(jQuery);