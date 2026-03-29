/* global elementor, elementorCommon */
/* eslint-disable */

const wglElementorCustomLibrary = window.wglElementorCustomLibrary = window.wglElementorCustomLibrary || {};

"undefined" != typeof jQuery &&
	!(function($) {
		$(function() {
			function modal() {
				const insertIndex = 0 < jQuery(this).parents(".elementor-section-wrap").length ? jQuery(this).parents(".elementor-add-section").index() : -1;
				wglElementorCustomLibrary.insertIndex = insertIndex;
			
				if (!window.wglElementorCustomLibraryModal) {
					window.wglElementorCustomLibraryModal = elementorCommon.dialogsManager.createWidget("lightbox", {
						id: "wgl-elementor-custom-library-modal",
						headerMessage: "WGL Elementor Library",
						message: "",
						hide: {
							auto: false,
							onClick: false,
							onOutsideClick: false,
							onOutsideContextMenu: false,
							onBackgroundClick: true
						},
						position: {
							my: "center",
							at: "center"
						},
						onShow: function() {
							const content = window.wglElementorCustomLibraryModal.getElements("content");
			
							// Add modal content
							content.append('<div id="wgl-elementor-custom-library" class="wrap"></div>');
			
							var event = new Event("modal-close");
							$("#wgl-elementor-custom-library").on("click", ".close-modal", function() {
								document.dispatchEvent(event);
								return window.wglElementorCustomLibraryModal.hide(), false;
							});
						},
						onHide: function() {}
					});
			
					window.wglElementorCustomLibraryModal.getElements("header").remove();
					window.wglElementorCustomLibraryModal
						.getElements("message")
						.append(window.wglElementorCustomLibraryModal.addElement("content"));
				}
			
				window.wglElementorCustomLibraryModal.getElements("widget").addClass("elementor-templates-modal");
			
				window.wglElementorCustomLibraryModal.show();
			}

			const template = $("#tmpl-elementor-add-section");

			if (0 < template.length && typeof elementor !== undefined) {
				let text = template.text();

				(text = text.replace(
					'<div class="elementor-add-section-drag-title',
					'<div class="elementor-add-section-area-button elementor-add-wgl-elementor-custom-library-button" title="WGL Elementor Custom Library">&nbsp;</div> <div class="elementor-add-section-drag-title'
				)),
					template.text(text),
					elementor.on("preview:loaded", function() {
						$(elementor.$previewContents[0].body).on(
							"click",
							".elementor-add-wgl-elementor-custom-library-button",
							modal
						);
					});
			}
		});
	})(jQuery);
