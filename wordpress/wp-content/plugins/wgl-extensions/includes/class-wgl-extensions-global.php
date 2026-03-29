<?php
if (!class_exists('WGL_Extensions_Global')) {
	/**
	 * Register all global variables here
	 *
	 * @link https://themeforest.net/user/webgeniuslab
	 *
	 * @package wgl-extensions\includes
	 * @author WebGeniusLab <webgeniuslab@gmail.com>
	 * @since 1.0.0
	 */
	class WGL_Extensions_Global {

		private $widgets;
		private static $instance = null;

		/**
		 * Creates and returns an instance of the class
		 *
		 * @since 1.0.0
		 * @access public
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

		/**
		 * Get the URL within the wgl-extensions plugin
		 *
		 */
		public function get_ext_url($inc = '')
		{
			return $inc . plugins_url('/', __FILE__);
		}

		/**
		 * Get the filesystem directory path
		 *
		 */
		public function get_ext_dir_path($inc = '')
		{
			return $inc . plugin_dir_path(__FILE__);
		}

		public function __construct()
		{
			add_action('after_setup_theme', [$this, 'load_widgets_helper']);
			add_action('after_setup_theme', [$this, 'set_variables']);
		}

		/**
         * Retrieve the name of the highest priority template file that exists.
         *
         * @param string|array $template_names Template file(s) to search for, in order.
         * @param string       $origin_path    Template file(s) origin path. (../wgl-extensions/...)
         * @param string       $override_path  New template file(s) override path in the theme. (../[`wgl-theme`]/wgl-extensions/...)
         * @param string       $core_path  	   New template file(s) override path in the core. (../[`wgl-theme`-core]/...)
         *
         * @return string The template filename if one is located.
         */
		public function get_locate_template(
            $template_names,
            $origin_path,
			$override_path,
			$core_path = false
        ) {
            $files = [];
			$file = '';
			$core_path = empty($core_path) ? realpath(__DIR__ . '/..') : $core_path;

			foreach ((array)$template_names as $template_name) {
                if (file_exists(get_stylesheet_directory() . $override_path . $template_name)) {
                    $file = get_stylesheet_directory() . $override_path . $template_name;
                } elseif (file_exists(get_template_directory() . $override_path . $template_name)) {
                    $file = get_template_directory() . $override_path . $template_name;
                } elseif (file_exists($core_path . $origin_path . $template_name)) {
                    $file = $core_path . $origin_path . $template_name;
                } elseif (file_exists(realpath(__DIR__ . '/..') . '/includes' . $origin_path . $template_name)) {
                    $file = realpath(__DIR__ . '/..') . '/includes' . $origin_path . $template_name;
                }
                array_push($files, $file);
            }
            return $files;
        }

		/**
		 * Widgets Helper
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function load_widgets_helper()
		{
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/widgets/class-wgl-extensions-widgets-register.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/widgets/class-wgl-extensions-widget-helper.php';
		}

		public function set_variables()
        {
			// * Widgets
			$this->widgets = new WGL_Widgets();
        }

		public function get_loader_widgets()
		{
			return $this->widgets;
		}

		public function add_widget($widget)
		{
			if ( $widget ) {
				$this->get_loader_widgets()->add_widget($widget);
			}

			return $widget;
		}

	}

	if (!function_exists('wgl_extensions_global')) {
		function wgl_extensions_global()
		{
			return WGL_Extensions_Global::get_instance();
		}
		wgl_extensions_global();
	}

}
