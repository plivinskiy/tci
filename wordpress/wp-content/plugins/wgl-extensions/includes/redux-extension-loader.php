<?php

if (!function_exists('wgl_redux_register_custom_extension_loader')) {
    function wgl_redux_register_custom_extension_loader($ReduxFramework)
    {
        $path    = dirname(__FILE__) . '/extensions/';
        $folders = scandir($path, 1);
        $wgl_importer =  defined('WGL_CORE_PATH') && is_dir(WGL_CORE_PATH  . '/includes/wgl_importer');
        foreach ($folders as $folder) {
            if ('wbc_importer' === $folder && $wgl_importer) {
                continue;
            }
            if ($folder === '.' or $folder === '..' or !is_dir($path . $folder)) {
                continue;
            }

            $extension_class = 'ReduxFramework_Extension_' . $folder;
            if (!class_exists($extension_class)) {
                // In case you wanted override your override, hah.
                $class_file = $path . $folder . '/extension_' . $folder . '.php';
                $class_file = apply_filters('redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $folder, $class_file);
                if ($class_file) {
                    require_once $class_file;
                }
            }
            if (!isset($ReduxFramework->extensions[$folder])) {
                if (class_exists($extension_class)) {
                   $ReduxFramework->extensions[$folder] = new $extension_class($ReduxFramework);
                }
            }
        }
    }

    $opt_name = str_replace('-child', '', wp_get_theme()->get('TextDomain')) . '_set';
    add_action("redux/extensions/{$opt_name}/before", 'wgl_redux_register_custom_extension_loader', 0);
}
