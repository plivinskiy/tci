<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'plugins_loaded',
	static function () {

		if(!class_exists('\Elementor\Plugin')){
			return;
		}

		// Check if WGL core path and templates exist
		if ( ! defined( 'WGL_CORE_PATH' ) || ! is_dir( WGL_CORE_PATH . '/includes/wgl_elementor_templates' ) ) {
			return;
		}

		define( 'WGL_LIBRARY_PLUGIN_FILE', __FILE__ );
		define( 'WGL_LIBRARY_PLUGIN_URL', plugin_dir_url( WGL_LIBRARY_PLUGIN_FILE ) );
		define( 'WGL_LIBRARY_PLUGIN_DIR', plugin_dir_path( WGL_LIBRARY_PLUGIN_FILE ) );
		define( 'WGL_LIBRARY_PLUGIN_BASE', plugin_basename( WGL_LIBRARY_PLUGIN_FILE ) );

		require_once WGL_CORE_PATH  . '/includes/wgl_elementor_templates/default-options.php' ;
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-plugin.php';
		\WGL_Elementor_Templates\Custom_Library\Plugin::load( WGL_LIBRARY_PLUGIN_FILE );
	}
);
