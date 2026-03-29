/* global redux_change, wp */

(function($) {
    "use strict";

    redux.field_objects = redux.field_objects || {};

    $('#wgl-import-elementor-templates').off('click').on('click', function (e) {
        e.preventDefault();

        if (!confirm(wglElementorLibrary.message)) return;

        var $button = $(this);
        var templates = wglElementorLibrary.templates;
        var total = templates.length;
        var current = 0;

        var $progressBar = $('<div class="wgl-import-progress"><div class="progress-bar"></div></div>');
        $('.wgl-import-notice').remove();

        $button.text(wglElementorLibrary.activeImportText).prop('disabled', true);
        $button.after($progressBar);

        function importNext() {
            if (current >= total) {
                var $notice = $('<div class="wgl-import-notice notice notice-success"><p>' + wglElementorLibrary.completeMessage + '</p></div>');
                $button.before($notice);

                $progressBar.remove();
                $button.text(wglElementorLibrary.defaultText).prop('disabled', false);
                return;
            }

            var template = templates[current];

            $.ajax({
                url: wglElementorLibrary.ajaxurl,
                type: 'POST',
                data: {
                    action: 'wgl_import_elementor_templates',
                    security: wglElementorLibrary.security,
                    template_file: template
                },
                dataType: 'json',
                success: function (response) {
                    current++;

                    var percent = Math.round((current / total) * 100);
                    $progressBar.find('.progress-bar').css('width', percent + '%');

                    if (!response.success) {
                        var $notice = $('<div class="wgl-import-notice notice notice-error"><p>' + response.message + '</p></div>');
                        $button.before($notice);
                    }

                    importNext();
                },
                error: function () {
                    var $notice = $('<div class="wgl-import-notice notice notice-error"><p>Import failed at ' + template + '</p></div>');
                    $button.before($notice);
                    $progressBar.remove();
                    $button.text(wglElementorLibrary.defaultText).prop('disabled', false);
                }
            });
        }

        importNext();
    });
})(jQuery);