<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/webgeniuslab
 * @since             1.0.0
 * @package           wgl-extensions
 *
 * @wordpress-plugin
 * Plugin Name:       WGL Extensions
 * Plugin URI:        https://themeforest.net/user/webgeniuslab
 * Description:       Core plugin for WGL Theme.
 * Version:           1.0.48
 * Author:            WebGeniusLab
 * Author URI:        https://themeforest.net/user/webgeniuslab
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wgl-extensions
 * Domain Path:       /languages
 */

defined('WPINC') || die; // Abort, if called directly.

/**
 * Current version of the plugin.
 */
$plugin_data = get_file_data(__FILE__, ['version' => 'Version']);
define('WGL_EXTENSIONS_VERSION', $plugin_data['version']);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wgl-extensions-activator.php
 */
function activate_wgl_extensions()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-extensions-activator.php';
    WGL_Extensions_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wgl-extensions-deactivator.php
 */
function deactivate_wgl_extensions()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-extensions-deactivator.php';
    WGL_Extensions_Core_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wgl_extensions');
register_deactivation_hook(__FILE__, 'deactivate_wgl_extensions');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wgl-extensions.php';

if (!function_exists('run_wgl_extensions')) {
    /**
     * Start execution of the plugin.
     *
     *
     * @since 1.0.0
     */
    function run_wgl_extensions()
    {
        do_action('wgl_extensions_plugin_loader');

        (new WGL_Extensions_Core())->run();
    }

    add_action('plugins_loaded', 'run_wgl_extensions', 5);
}
