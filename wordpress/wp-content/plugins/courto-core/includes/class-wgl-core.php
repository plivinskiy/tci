<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 * @link https://themeforest.net/user/webgeniuslab
 *
 * @package courto-core\includes
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Courto_Core
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var Courto_Core_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Custom Fonts
     *
     * @since 1.0.0
     * @var string string of CSS rules
     */
    public $font_css;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (defined('WGL_CORE_VERSION')) {
            $this->version = WGL_CORE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'courto-core';

        $this->load_dependencies();
        $this->define_cpt_hooks();

	    add_action('wgl/widgets_require', [$this, 'get_widgets_locate_template']);

        $this->set_locale();
        $this->add_filters();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Courto_Core_Loader. Orchestrates the hooks of the plugin.
     * - Courto_Core_i18n. Defines internationalization functionality.
     * - Courto_Core_Admin. Defines all hooks for the admin area.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-core-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-core-i18n.php';

        /**
         * Redux Framework Loader
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl_importer/redux-importer-config.php';

        /**
         * WGL Likes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-likes/wgl-extensions-likes.php';

        /**
         * WGL Views
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-views/wgl-extensions-views.php';

        /**
         * WGL Social Shares
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-social/wgl-extensions-social.php';

        /**
         * WGL Post types register
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/post-types/post-types-register.php';

        /**
         * Include Elementor Extensions.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/elementor/init.php';

        $this->loader = new Courto_Core_Loader();
    }

    public function get_widgets_locate_template()
    {
        $template_names = [];
        $template_path = '/wgl-extensions/widgets/templates/';
        $plugin_template_path = plugin_dir_path(dirname(__FILE__))  . 'includes/widgets/templates/';
        $ext_template_path = wgl_extensions_global()->get_ext_dir_path() . 'includes/widgets/templates/';

        foreach (glob($ext_template_path . '*.php') as $file) {
            $template_name = basename($file);
            array_push($template_names, $template_name);
        }

        foreach (glob($plugin_template_path . '*.php') as $file) {
            $template_name = basename($file);
            array_push($template_names, $template_name);
        }

        $files = wgl_extensions_global()->get_locate_template(
            $template_names,
            '/widgets/templates/',
            $template_path,
            realpath(__DIR__ . '/..') . '/includes'
        );

        foreach ((array) $files as $file) {
            require_once $file;
        }
    }

    /**
     * Register 'custom' post type.
     */
    private function define_cpt_hooks()
    {
        $plugin_cpt = WGLPostTypesRegister::getInstance();
        // Add post type.
        $this->loader->add_action('after_setup_theme', $plugin_cpt, 'init');
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Courto_Core_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function set_locale()
    {
        $plugin_i18n = new Courto_Core_i18n();

        $this->loader->add_action('init', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * @since 1.0.0
     * @access private
     */
    private function add_filters()
    {
        /* Add Custom Image Link field to media uploader for WGL Gallery module */
        add_filter('attachment_fields_to_edit', function($form_fields, $post) {
            $form_fields['custom_image_link'] = array(
                'label' => esc_html__( 'Custom Image Link', 'courto-core' ),
                'input' => 'text',
                'value' => get_post_meta($post->ID, 'custom_image_link', true),
                'helps' => esc_html__( 'This option works only for the WGL Gallery and WGL Infinity Carousel modules.', 'courto-core' ),
            );

            return $form_fields;
        }, 10, 2);

        /* Save values of Custom Image Link in media uploader */
        add_filter('attachment_fields_to_save', function ($post, $attachment) {
            if (isset($attachment['custom_image_link']))
                update_post_meta($post['ID'], 'custom_image_link', $attachment['custom_image_link']);

            return $post;
        }, 10, 2);

        add_action('admin_init', function(){
            if (class_exists('WGL_Framework') && WGL_Framework::get_option('wordpress_widgets')) {
                /**
                 * Disable Gutenberg Widgets. That restores the previous (“classic”) WordPress widgets settings screens.
                 */

                // Disables the block editor from managing widgets in the Gutenberg plugin.
                add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
                // Disables the block editor from managing widgets.
                add_filter( 'use_widgets_block_editor', '__return_false' );
            }
        });
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since 1.0.0
     * @return string The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since 1.0.0
     * @return Courto_Core_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since 1.0.0
     * @return string The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
