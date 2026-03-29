<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/webgeniuslab
 * @since      1.0.0
 *
 * @package    WGL_Extensions_Core
 * @subpackage WGL_Extensions_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WGL_Extensions_Core
 * @subpackage WGL_Extensions_Core/admin
 * @author     WebGeniusLab <webgeniuslab@gmail.com>
 */
class WGL_Extensions_Core_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WGL_Extensions_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WGL_Extensions_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wgl-extensions-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WGL_Extensions_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WGL_Extensions_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wgl-extensions-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_media(); //Enable the WP media uploader

		wp_enqueue_script(
            'jquery-migrate',
            plugin_dir_url(__FILE__) . 'js/jquery-migrate-3.3.0.min.js',
            ['jquery'],
            '3.3.0'
		);

		wp_enqueue_script(
            'wgl-extensions-upload-img',
            plugin_dir_url(__FILE__) . 'js/img_upload.js',
            ['jquery'],
            wp_get_theme()->get('Version') ?? false
		);

		wp_enqueue_script(
            'wgl-extensions-metaboxes',
            plugin_dir_url(__FILE__) . 'js/metaboxes.js',
            ['jquery'],
            wp_get_theme()->get('Version') ?? false
        );

	}

}
