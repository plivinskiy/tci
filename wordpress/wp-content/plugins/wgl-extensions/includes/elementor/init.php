<?php

define('WGL_EXTENSIONS_ELEMENTOR_URL', plugins_url('/', __FILE__));
define('WGL_EXTENSIONS_ELEMENTOR_PATH', plugin_dir_path(__FILE__));
define('WGL_EXTENSIONS_ELEMENTOR_FILE', __FILE__);

use Elementor\{
    Plugin,
    Core\Base\Document
};

if (!class_exists('WGL_Extensions_Elementor')) {
    /**
     * WGL Elementor Extenstion
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Extensions_Elementor
    {
        /**
         * @var string The defualt path to elementor dir on this plugin.
         */
        private $dir_path;

        public static $typography_1 = '1';
        public static $typography_2 = '2';
        public static $typography_3 = '3';
        public static $typography_4 = '4';

        private static $instance;
        private $admin_bar_edit_documents;
        private $documents;

        public function __construct()
        {
            $this->dir_path = plugin_dir_path(__FILE__);

            add_action('plugins_loaded', [$this, 'elementor_setup']);
            add_action('elementor/init', [$this, 'elementor_libraries']);

            add_action('elementor/init', [$this, 'elementor_widgets_translatable']);

            add_action('elementor/init', [$this, '_v_3_0_0_compatible']);

            add_filter('admin_bar_menu', [$this, 'replace_elementor_admin_bar_title'], 400);
            add_action('elementor/css-file/post/enqueue', [$this, 'add_document_to_admin_bar']);
            add_action('wp_before_admin_bar_render', [$this, 'remove_admin_bar_node']);
            add_action('wp_enqueue_scripts', [$this, 'admin_bar_style']);

            add_action('elementor/frontend/get_builder_content', [$this, 'add_builder_to_admin_bar'], 10, 2);
            add_filter('elementor/frontend/admin_bar/settings', [$this, 'add_menu_in_admin_bar']);

            add_filter('template_include', [$this, 'modify_page_structure_for_saved_templates'], 12); // after Elementors hook
        }

        public function elementor_setup()
        {
            /**
             * Check if Elementor installed and activated
             * @see https://developers.elementor.com/creating-an-extension-for-elementor/
             */
            if (!did_action('elementor/loaded')) {
                return;
            }

            $this->init_helpers();
            $this->init_addons();
        }

        public function init_helpers()
        {
            require_once $this->dir_path . 'helper/plugin_helper.php';
        }

        public function elementor_widgets_translatable()
        {
            if (
                class_exists('\SitePress')
            ) {
                require_once $this->dir_path . 'helper/wpml_translate.php';
            }
        }

        /**
         * Load required file for addons integration
         */
        public function init_addons()
        {
            add_action('elementor/controls/register', [$this, 'controls_area']);

            $this->init_all_modules();
        }

        public function elementor_libraries()
        {
            $this->init_mega_menu();
        }

        public function init_mega_menu()
        {
            if(class_exists('WGL_Mega_Menu_Walker')){
                require_once $this->dir_path . 'library/mega_menu/wgl-mega-menu.php';
                add_action('elementor/documents/register', [ $this, 'register_mega_menu_types' ], 0);
                add_filter('single_template', [ \WGL_Extensions\Library\WGL_Mega_Menu::get_class_full_name(), 'get_single_template' ]);                        
            }
        }

        public function register_mega_menu_types()
        {
            Plugin::instance()->documents->register_document_type(\WGL_Extensions\Library\WGL_Mega_Menu::$name, \WGL_Extensions\Library\WGL_Mega_Menu::get_class_full_name());
        }

        public function init_all_modules()
        {
            foreach (glob($this->dir_path . 'modules/' . '*.php') as $file_name) {
                $base = basename(str_replace('.php', '', $file_name));
                $class = ucwords(str_replace('-', ' ', $base));
                $class = str_replace(' ', '_', $class);
                $class = sprintf('WGL_Extensions\Modules\%s', $class);

                // Class File
                require_once $file_name;

                if (class_exists($class)) {
                    new $class();
                }
            }
        }

        /**
         * Load controls require function
         */
        public function controls_area()
        {
            $this->controls_register();
        }

        /**
         * Requires controls files
         */
        private function controls_register()
        {
            foreach (glob($this->dir_path . 'controls/' . '*.php') as $file_name) {
                $this->register_controls_addon($file_name);
            }
        }

        /**
         * Register addon by file name.
         */
        public function register_controls_addon($file_name)
        {
            $controls_manager = Plugin::$instance->controls_manager;

            $base = basename(str_replace('.php', '', $file_name));
            $class = ucwords(str_replace('-', ' ', $base));
            $class = str_replace(' ', '_', $class);
            $class = sprintf('WGL_Extensions\Controls\%s', $class);

            // Class Constructor File
            require_once $file_name;

            if (class_exists($class)) {
                $controls_manager->register(new $class);
            }
        }

        /**
         * Move WGL Theme Option settings to the Elementor global settings
         */
        public function _v_3_0_0_compatible()
        {
            if (
                defined('ELEMENTOR_VERSION')
                && version_compare(ELEMENTOR_VERSION, '3.0', '>=')
            ) {
                if (!$wgl_option = get_option('wgl_system_status')) {
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
                    $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();

                    $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
                    $kit_settings = get_post_meta($kit_id, $meta_key, true);

                    $wgl_settings = [];
                    $wgl_settings['container_width'] = ['size' => '1200', 'unit' => 'px'];

                    $items_color = $this->_get_elementor_settings( 'system_colors' );
                    $items_fonts = $this->_get_elementor_settings( 'system_typography' );

                    $reduxArgs = new Redux;
                    $reduxArgs = $reduxArgs::$args;
                    $keys = array_keys($reduxArgs);
                    $opt_name = $keys[ 0 ] ?? '';
                    $wgl_theme_option = get_option( $opt_name );

                    if (empty($wgl_theme_option)) {
                        return;
                    }

                    $header_font = $wgl_theme_option['header-font'] ?? 'initial';
                    $main_font   = $wgl_theme_option['main-font'] ?? 'initial';
                    $theme_color = $wgl_theme_option['theme-primary-color'] ?? 'initial';
                    $header_font_color = $header_font['color'] ?? 'initial';
                    if(empty($header_font_color)){
                        $header_font_color = $wgl_theme_option['theme-headings-color'] ?? 'initial';
                    }
                    $main_font_color = $main_font['color'] ?? 'initial';
                    if(empty($main_font_color)){
                        $main_font_color = $wgl_theme_option['theme-content-color'] ?? 'initial';
                    }
                
                    $items_color[0]['color'] = esc_attr($theme_color);
                    $items_color[1]['color'] = esc_attr($header_font_color);
                    $items_color[2]['color'] = esc_attr($main_font_color);
                    $items_color[3]['color'] = esc_attr($theme_color);
                    $wgl_settings['system_colors'] = $items_color;

                    $items_fonts[0]['typography_font_family'] = esc_attr($header_font['font-family']);
                    $items_fonts[0]['typography_font_weight'] = esc_attr($header_font['font-weight']);
                    $items_fonts[1]['typography_font_family'] = esc_attr($header_font['font-family']);
                    $items_fonts[1]['typography_font_weight'] = esc_attr($header_font['font-weight']);
                    $items_fonts[2]['typography_font_family'] = esc_attr($main_font['font-family']);
                    $items_fonts[2]['typography_font_weight'] = esc_attr($main_font['font-weight']);
                    $items_fonts[3]['typography_font_family'] = esc_attr($main_font['font-family']);
                    $items_fonts[3]['typography_font_weight'] = esc_attr($main_font['font-weight']);

                    $wgl_settings['system_typography'] = $items_fonts;
                    update_option('elementor_disable_typography_schemes', 'yes');
                    update_option('wgl_system_status', 'yes');

                    if (!$kit_settings) {
                        update_metadata('post', $kit_id, $meta_key, $wgl_settings);
                    } else {
                        $kit_settings = array_merge($kit_settings, $wgl_settings);
                        $page_settings_manager->save_settings($kit_settings, $kit_id);
                    }

                    Plugin::$instance->files_manager->clear_cache();
                }

                $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
                $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();
                
                if ($kit_id !== get_option('wgl_optimized_controls_v2')) {
                    $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
                    $kit_settings = get_post_meta($kit_id, $meta_key, true);
                    if(empty($kit_settings)){
                        return;
                    }
    
                    $items_color = $this->_get_elementor_settings( 'system_colors' );
                    foreach ($items_color as &$item) {
                        if (!isset($item['color']) || 'transparent' === $item['color']) {
                            $item['color'] = 'initial';
                        }
                    }
    
                    unset($item);
    
                    $kit_settings['system_colors'] = $items_color;
                    
                    update_option('wgl_optimized_controls_v2', $kit_id);
                    $page_settings_manager->save_settings($kit_settings, $kit_id);
    
                    Plugin::$instance->files_manager->clear_cache();
                }
            } elseif (!$wgl_option = get_option('wgl_system_status_old_e')) {
                update_option('elementor_disable_typography_schemes', 'yes');
                update_option('wgl_system_status_old_e', 'yes');
                Plugin::$instance->files_manager->clear_cache();
            }
        }

        public function _get_elementor_settings($value = 'system_colors')
        {
            $kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();

            $system_items = $kit->get_settings_for_display($value);

            if (!$system_items) {
                $system_items = [];
            }

            return $system_items;
        }

        /**
         * Remove elementor node in the admin bar
         */
        public function remove_admin_bar_node()
        {
            global $wp_admin_bar;

            $wp_admin_bar->remove_node( 'elementor_app_site_editor' );

            if (empty($this->admin_bar_edit_documents)) {
                return;
            }

            foreach ($this->admin_bar_edit_documents as $document) {
                $wp_admin_bar->remove_node('elementor_edit_doc_' . $document->get_main_id());
            }
        }

        /**
         * @param Post_CSS $css_file
         */
        public function add_document_to_admin_bar($css_file)
        {
            $document = Plugin::$instance->documents->get( $css_file->get_post_id() );

            if (
                $document::get_property('show_on_admin_bar')
                && $document->is_editable_by_current_user()
            ) {
                $this->admin_bar_edit_documents[$document->get_main_id()] = $document;
            }
        }

        /**
         * Replace elementor node in the admin bar
         */
        public function replace_elementor_admin_bar_title( \WP_Admin_Bar $wp_admin_bar )
        {
            if (empty($this->admin_bar_edit_documents)) {
                return;
            }

            $queried_object_id = get_queried_object_id();

            if (is_singular() && isset($this->admin_bar_edit_documents[$queried_object_id])) {
                $menu_args['href'] = $this->admin_bar_edit_documents[$queried_object_id]->get_edit_url();
                unset($this->admin_bar_edit_documents[$queried_object_id]);
            }

            foreach ($this->admin_bar_edit_documents as $document) {
                $title_bar = $document->get_post()->post_type && $document->get_post()->post_type !== 'elementor_library'
                    ? $document->get_post()->post_type
                    : $document::get_title();

                $wp_admin_bar->add_menu([
                    'id' => 'wgl_elementor_edit_doc_' . $document->get_main_id(),
                    'parent' => 'elementor_edit_page',
                    'title' => sprintf('<span class="elementor-edit-link-title">%s</span><span class="elementor-edit-link-type">%s</span>', $document->get_post()->post_title, $title_bar),
                    'href' => $document->get_edit_url(),
                ]);
            }

            if (
                defined('ELEMENTOR_VERSION')
                && version_compare(ELEMENTOR_VERSION, '3.0', '>=')
            ) {
                $wp_admin_bar->add_menu([
                    'id' => 'wgl_elementor_app_site_editor',
                    'parent' => 'elementor_edit_page',
                    'title' => esc_html__('Open Theme Builder', 'wgl-extensions'),
                    'href' => Plugin::$instance->app->get_settings('menu_url'),
                    'meta' => ['class' => 'elementor-app-link'],
                ]);
            }
        }

        /**
         * Add custom css to the admin bar
         */
        public function admin_bar_style()
        {
            if (
                is_admin_bar_showing()
                && defined('ELEMENTOR_VERSION')
                && version_compare(ELEMENTOR_VERSION, '3.0', '>=')
            ) {
                $css = '#wpadminbar #wp-admin-bar-wgl_elementor_app_site_editor a.ab-item:before {'
                        . 'content: "\e91d";'
                        . 'font-family: eicons;'
                        . 'top: 4px;'
                        . 'font-size: 13px;'
                        . 'color: inherit;'
                    . '}';

                $css .= '#wpadminbar #wp-admin-bar-wgl_elementor_app_site_editor a.ab-item:hover {'
                        . 'background: #4ab7f4;'
                        . 'color: #fff'
                    . '}';

                $css .= '#wpadminbar #wp-admin-bar-wgl_elementor_app_site_editor a.ab-item:hover:before {'
                        . 'color: #fff'
                    . '}';

                wp_add_inline_style('elementor-frontend', $css);
            }
        }

        public function add_builder_to_admin_bar( Document $document, $is_excerpt )
        {
            if (
                $is_excerpt
                || !$document::get_property('show_on_admin_bar')
                || !$document->is_editable_by_current_user()
            ) {
                return;
            }

            $this->documents[$document->get_main_id()] = $document;
        }

        public function add_menu_in_admin_bar( $admin_bar_config )
        {
            if (empty($this->documents)) {
                return;
            }

            $_key = array_keys($this->documents);
            foreach ($_key as $condition) {
                unset($admin_bar_config['elementor_edit_page']['children'][$condition]);
            }

            $queried_object_id = get_queried_object_id();
            if (is_singular() && isset($this->documents[$queried_object_id])) {
                unset($this->documents[$queried_object_id]);
            }

            $admin_bar_config['elementor_edit_page']['children'] = array_map(function ($document) {
                return [
                    'id' => "wgl_elementor_edit_doc_{$document->get_main_id()}",
                    'title' => $document->get_post()->post_title,
                    'sub_title' => $document->get_post()->post_type && $document->get_post()->post_type !== 'elementor_library'
                        ? $document->get_post()->post_type
                        : $document::get_title(),
                    'href' => $document->get_edit_url(),
                ];
            }, $this->documents);

            return $admin_bar_config;
        }

        public function modify_page_structure_for_saved_templates($template)
        {
            if (
                'elementor_library' === get_post_type()
                && ($documents = Plugin::$instance->documents)
            ) {
                $current_doc = $documents->get(get_the_ID());

                if (
                    is_a($current_doc, 'Elementor\Modules\Library\Documents\Page')
                    || is_a($current_doc, 'Elementor\Modules\Library\Documents\Section')
                    || is_a($current_doc, 'Elementor\Modules\Library\Documents\Container')
                    || is_a($current_doc, 'ElementorPro\Modules\ThemeBuilder\Documents\Section')
                    || is_a($current_doc, 'ElementorPro\Modules\ThemeBuilder\Documents\Container')
                ) {
                    $elementor_templates = Plugin::$instance->modules_manager->get_modules('page-templates');
                    $elementor_template_path = $elementor_templates->get_template_path($elementor_templates::TEMPLATE_HEADER_FOOTER);

                    $template = $elementor_template_path ?: get_page_template(); //* prevent rendering through `single.php`
                }
            }

            return $template;
        }

        /**
         * Creates and returns an instance of the class
         *
         * @return object
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }
    }
}

if (!function_exists('wgl_extensions_elementor')) {
    function wgl_extensions_elementor()
    {
        return WGL_Extensions_Elementor::get_instance();
    }
    wgl_extensions_elementor();
}
