/* global confirm, redux, redux_change */


(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.custom_header_mobile_builder = redux.field_objects.custom_header_mobile_builder || {};

    var scroll = '',
    builderItems = [],
    itemOptBuilder,
    itemPlus;

    redux.field_objects.custom_header_mobile_builder.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-custom_header_mobile_builder:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }

                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }

                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                //var modal_builder = 'wgl_modal_mobile_builder';
                var builderID = el.data('id');
                builderItems.push(builderID);
                var modal_builder = 'wgl_modal_' + builderID;

                /**    Call Modal Window with Items Optios */
                el.find('ul li i.edit-item').on("click", function(){
                    var idSorter = $(this).closest('.redux-sorter').attr('id');

                    var tr = jQuery(this).closest('table').find("table.wgl-item-mobile-option[data-id-table='" + idSorter + "'] tr");
                    tr.css({'display' : 'none'});
                   
                    var optId = jQuery(this).data('optId');

                    for(var i = 0; i <= tr.length; i++){
                        if(tr[i]){
                            var b = tr[i];
                            b = jQuery(b).find('fieldset').data('id');
                            if(b.indexOf(optId) >= 0){
                                jQuery(tr[i]).not('.hide').css({'display' : 'table-row'}).trigger('resize');
                            }
                        }
                    }

                    jQuery('#'+ modal_builder + ' .modal-body').append(jQuery("table.wgl-item-mobile-option[data-id-table='" + idSorter + "']").css({'display' : 'table'}));
                    jQuery('#'+ modal_builder).css({'display' : 'block'});

                });

                /**    Toggle Row */
                el.find('.wgl_header_row-toggle a').on("click", function(e){
                    e.preventDefault();
                    jQuery(this).closest('.wgl_header_row').toggleClass('hide_row');
                });

                /**    Add Item */
                el.find('.add_item_mobile_'+ builderID + ' a').on("click", function(e){
                    e.preventDefault();
                    jQuery(this).closest('.redux-field-container').find('.wgl_header_mobile_items').css({'display' : 'block'});
                    jQuery(this).closest('.redux-field-container').find('#'+ modal_builder+'_items').css({'display' : 'block'});
                    itemPlus = jQuery(this).closest('ul').data( 'groupId' );
                });

                /**    Close Modal Window */
                el.find('span.close').on("click", function () {       
                    $(this).parent().parent().parent().css({ 'display': 'none' });
                });

                /**    Sorter (Layout Manager) */
                el.find( '.redux-sorter' ).each(
                    function() {
                        var id = $( this ).attr( 'id' );
                        var self = $( this );
                        //Options Items
                        itemOptBuilder = $(this).closest('.compiler').nextAll();
                        itemOptBuilder.wrapAll( "<table class='wgl-item-mobile-option' data-id-table='"+id+"'></table>" );
                        setTimeout(function(){
                            self.closest('table').find('table.wgl-item-mobile-option').css({'display' : 'none'});
                            self.closest('table').find('table.wgl-item-mobile-option tr').css({'display' : 'none'});
                        }, 1);
                        
                        el.find( '#' + id ).find( 'ul:not(#'+modal_builder+'_items)' ).sortable(
                            {
                                items: 'li',
                                placeholder: "placeholder",
                                connectWith: '.sortlist_' + id,
                                opacity: 0.8,
                                scroll: false,
                                cancel: '.add_item_mobile_'+ builderID,
                                out: function( event, ui ) {
                                    if ( !ui.helper ) return;
                                    if ( ui.offset.top > 0 ) {
                                        scroll = 'down';
                                    } else {
                                        scroll = 'up';
                                    }
                                    redux.field_objects.custom_header_mobile_builder.scrolling( $( this ).parents( '.redux-field-container:first' ) );

                                },
                                revert: true,
                                over: function( event, ui ) {
                                    scroll = '';
                                },

                                deactivate: function( event, ui ) {
                                    scroll = '';
                                },
                                stop: function( event, ui ) {},

                                update: function( event, ui ) {

                                    $( this ).find( '.position' ).each(
                                        function() {
                                            var listID = $( this ).parent().attr( 'data-id' );
                                            var parentID = $( this ).parent().parent().attr( 'data-group-id' );

                                            redux_change( $( this ) );

                                            var optionID = $( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                            if('items' !== parentID){
                                                if($( this ).parent().find( '.trash-item_' + builderID ).length === 0){
                                                    $( this ).parent().find('.icon_wrapper').append('<i class="trash-item_mobile trash-item_'+builderID+' fas fa-trash fa fa-6"></i>');
                                                }
                                            }else{
                                                if($( this ).parent().find( '.add-item_icon_mobile_' + builderID ).length === 0){
                                                    $( this ).parent().append('<span class="add-item_icon_mobile add-item_icon_mobile_' + builderID +'"></span>');
                                                }
                                            }
                                            $( this ).prop(
                                                "name",
                                                redux.opt_names + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                        }
                                    );
                                }
                            }
                        );
                        jQuery(document).on( "click", '.trash-item_' + builderID,
                            function( e ) {
                                var element = jQuery(this);
                                console.log(e);

                                var r = confirm(wglBuilderVars.delete);
                                if (r == false) return;

                                jQuery( this ).closest('.redux-sorter').find( 'ul .position' ).each(
                                    function() {
                                        var listID = jQuery( this ).parent().attr( 'data-id' );
                                        var parentID = jQuery( this ).parent().parent().attr( 'data-group-id' );
                                        jQuery(element).closest('li').detach().appendTo('#' + builderID + '_items');

                                        redux_change( jQuery( this ) );

                                        var optionID = jQuery( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                        if(parentID == 'items'){
                                            if($( this ).parent().find( '.add-item_icon_mobile_' + builderID ).length === 0){
                                                $( this ).parent().append('<span data-id="'+builderID+'" class="add-item_icon_mobile add-item_icon_mobile_'+builderID+'"></span>');
                                            }
                                        }
                                        jQuery( this ).prop(
                                            "name",
                                            redux.opt_names + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                    }
                                );

                                jQuery(this).remove();

                        });

                        jQuery(document).on( "click", '.add-item_icon_mobile_'+ builderID,
                            function( e ) {
                                var element = jQuery(this);

                                jQuery( this ).closest('.redux-field-container').find( 'ul .position' ).each(
                                    function() {

                                        var listID = jQuery( this ).parent().attr( 'data-id' );

                                        jQuery(element).closest('li').detach().insertBefore('ul[data-group-id="'+ itemPlus +'"] .add_item_mobile_'+ builderID);

                                        redux_change( jQuery( this ) );

                                        var optionID = jQuery( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                        var parentID = jQuery( this ).parent().parent().attr( 'data-group-id' );

                                        if(parentID != 'items'){
                                            if($( this ).parent().find( '.trash-item_' +  builderID).length === 0){
                                                $( this ).parent().find('.icon_wrapper').append('<i class="trash-item_mobile trash-item_'+builderID+' fas fa-trash fa fa-6"></i>');
                                            }
                                        }
                                        
                                        jQuery( this ).prop(
                                            "name",
                                            redux.opt_names + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                    }
                                );

                                jQuery(this).remove();

                        });
                        el.find( ".redux-sorter" ).disableSelection();
                    }
                );
                el.find( 'select.redux-select-item' ).each(
                    function() {
                        var default_params = {
                            width: 'resolve',
                            triggerChange: true,
                            allowClear: true
                        };

                        if ( $( this ).siblings( '.select2_params' ).size() > 0 ) {
                            var select2_params = $( this ).siblings( '.select2_params' ).val();
                            default_params = $.extend( {}, default_params, select2_params );
                        }

                        $( this ).select2( default_params );

                        $( this ).on(
                            "change", function() {
                                $( this ).siblings( '.select2_params' ).val($(this).val());
                                var parentID = jQuery( $( $( this ) ) ).closest( '.redux-group-tab' ).attr( 'id' );
                                if(parentID){
                                    redux_change( $( $( this ) ) );
                                    $( this ).select2SortableOrder();
                                }

                            }
                        );
                    }
                );
            }
        );
    };

    redux.field_objects.custom_header_mobile_builder.scrolling = function( selector ) {
        if (selector === undefined) {
            return;
        }

        var scrollable = selector.find( ".redux-sorter" );

        if ( scroll == 'up' ) {
            scrollable.scrollTop( scrollable.scrollTop() - 20 );
            setTimeout( redux.field_objects.custom_header_mobile_builder.scrolling, 50 );
        } else if ( scroll == 'down' ) {
            scrollable.scrollTop( scrollable.scrollTop() + 20 );
            setTimeout( redux.field_objects.custom_header_mobile_builder.scrolling, 50 );
        }
    };
    
    window.addEventListener('click', function(event) {
        if(builderItems.length){
            builderItems.forEach((element) => { 
                if (event.target == document.getElementById('wgl_modal_'+element+'_items')) {
                    document.getElementById('wgl_modal_'+element+'_items').style.display = "none";
                }
                if (event.target == document.getElementById('wgl_modal_'+element)) {
                    document.getElementById('wgl_modal_'+element).style.display = "none";
                }
            })
        }
    });


})( jQuery );