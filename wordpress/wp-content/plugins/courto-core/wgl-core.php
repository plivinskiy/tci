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
 * @package           courto-core
 *
 * @wordpress-plugin
 * Plugin Name:       Courto Core
 * Plugin URI:        https://themeforest.net/user/webgeniuslab
 * Description:       Core plugin for Courto Theme.
 * Version:           1.0.0
 * Author:            WebGeniusLab
 * Author URI:        https://themeforest.net/user/webgeniuslab
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       courto-core
 * Domain Path:       /languages
 */

defined('WPINC') || die; // Abort, if called directly.

define('WGL_CORE_URL', plugins_url('/', __FILE__));
define('WGL_CORE_PATH', plugin_dir_path(__FILE__));
define('WGL_CORE_FILE', __FILE__);

/**
 * Current version of the plugin.
 */
$plugin_data = get_file_data(__FILE__, ['version' => 'Version']);
define('WGL_CORE_VERSION', $plugin_data['version']);

class Courto_CorePlugin
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'check_wgl_theme_installed'], 90);
        add_action('admin_init', [$this, 'check_wgl_extensions_installed'], 90);

        if (!self::theme_is_compatible()) {
            return;
        }
    }
    public function check_wgl_extensions_installed(){
        if (
            !self::wgl_extensions_ckeck()
            && is_plugin_active(plugin_basename(__FILE__))
        ) {
            add_action( 'admin_notices', 'Courto_CorePlugin::wgl_plugin_notice');
        }
    }

    public function check_wgl_theme_installed()
    {
        if (
            !self::theme_is_compatible()
            && is_plugin_active(plugin_basename(__FILE__))
        ) {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action('admin_notices', 'Courto_CorePlugin::wgl_theme_notice');
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }

    public static function activation_check()
    {
        if (!self::theme_is_compatible()) {
            add_action( 'admin_notices', 'Courto_CorePlugin::wgl_theme_notice');
            deactivate_plugins(plugin_basename(__FILE__));
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
    }

    public static function wgl_plugin_notice()
    {
        ?><div class="error"><p><?php echo esc_html__('WGL Extensions plugin is required for Courto Core plugin to work properly. Please activate WGL Extensions to use Courto Core.', 'courto-core');?></p></div><?php
    }

    public static function wgl_theme_notice()
    {
        ?><div class="error"><p><?php echo esc_html__('Courto Core plugin compatible with Courto theme only!.', 'courto-core');?></p></div><?php
    }

    public static function theme_is_compatible()
    {
        $plugin_name = trim(dirname(plugin_basename(__FILE__)));
        $theme_name = self::get_theme_slug();

        return false !== stripos($plugin_name, $theme_name);
    }

    public static function wgl_extensions_ckeck()
    {
        return class_exists('WGL_Extensions_Core');
    }

    public static function get_theme_slug()
    {
        return str_replace('-child', '', wp_get_theme()->get('TextDomain'));
    }
}

new Courto_CorePlugin();

register_activation_hook(__FILE__, ['Courto_CorePlugin', 'activation_check']);


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wgl-core-activator.php
 */
function activate_courto_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-core-activator.php';
    Courto_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wgl-core-deactivator.php
 */
function deactivate_courto_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wgl-core-deactivator.php';
    Courto_Core_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_courto_core');
register_deactivation_hook(__FILE__, 'deactivate_courto_core');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wgl-core.php';

/**
 * Start execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */

 if (!function_exists( 'run_courto_core'))
{
    function run_courto_core()
    {
        (new Courto_Core())->run();
    }

    add_action('wgl_extensions_plugin_loader', 'run_courto_core');
}
