<?php
/**
 * Main class for the plugin.
 *
 * @package WGL_Elementor_Templates\Custom_Library
 */

namespace WGL_Elementor_Templates\Custom_Library;

/**
 * Class WGL_Elementor_Templates\Custom_Library\Plugin.
 */
final class Plugin {

	/**
	 * Main instance of the plugin.
	 *
	 * @var Plugin|null
	 */
	private static $instance;

	/**
	 * Holds key for Favorite templates user meta.
	 *
	 * @var string
	 */
	public static $user_meta_prefix = 'wgl_elementor_custom_library_favorites';

	/**
	 * Holds key for Favorite blocks user meta.
	 *
	 * @var string
	 */
	public static $user_meta_block_prefix = 'wgl_elementor_custom_library_block_favorites';

	/**
	 * Sets the plugin main file.
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 */
	public function __construct( $main_file ) {
		$this->includes();
	}

	/**
	 * Registers the plugin with WordPress.
	 */
	public function register() {
		add_filter( 'wgl/elementor/library/app/strings', array( self::$instance, 'send_strings_to_app' ) );
	}

	/**
	 * Prepare text strings to be sent to app.
	 *
	 * @param array $domains List of translatable strings.
	 *
	 * @return array
	 */
	public function send_strings_to_app( $domains ) {
		if ( ! is_array( $domains ) ) {
			$domains = array();
		}

		$options = Options::get_instance();

		$favorites       = get_user_meta( get_current_user_id(), self::$user_meta_prefix, true );
		$block_favorites = get_user_meta( get_current_user_id(), self::$user_meta_block_prefix, true );

		if ( ! $favorites ) {
			$favorites = array();
		}
		if ( ! $block_favorites ) {
			$block_favorites = array();
		}

		$plugins = get_option( 'active_plugins' );
		$plugins = array_map( array( $this, 'filter_plugins' ), $plugins );

		$library_placeholder_img_id  = $options->get( 'default-placeholder-thumb' );
		$library_placeholder_img_url = '';

		if ( $library_placeholder_img_id && wp_attachment_is_image( $library_placeholder_img_id ) ) {
			$library_placeholder_img_url = wp_get_attachment_image_url( $library_placeholder_img_id, 'full' );
		}

		$new_domains = array(
			'ajaxurl'                            => admin_url( 'admin-ajax.php' ),
			'favorites'                          => $favorites,
			'blockFavorites'                     => $block_favorites,

			'elementorURL'                       => admin_url( 'edit.php?post_type=elementor_library' ),
			'pluginURL'                          => WGL_LIBRARY_PLUGIN_URL,
			'adminURL'                           => admin_url(),
			'siteURL'                            => get_site_url(),
			'isContainer'                        => self::is_container(),
			'activePlugins'                      => array_values( $plugins ),
			'wp_version'                         => get_bloginfo( 'version' ),

			// Settings UI toggles.
			'libraryPlaceholderImgURL'           => $library_placeholder_img_url,
			'libraryTemplateCols'                => $options->get( 'library_template_columns' ),
			'libraryCategoriesLocation'          => $options->get( 'library_categories_location' ),
			'showLibraryCategoriesTemplateCount' => $options->get( 'show_library_categories_template_count' ),
		);

		$domains += $new_domains;

		return $domains;
	}

	/**
	 * Returns true if Container experiment is on.
	 *
	 * @return bool
	 */
	public static function is_container() {
		$flexbox_container           = get_option( 'elementor_experiment-container' );
		$is_flexbox_container_active = \Elementor\Core\Experiments\Manager::STATE_ACTIVE === $flexbox_container;

		if ( 'default' === $flexbox_container ) {
			$experiments                 = new \Elementor\Core\Experiments\Manager();
			$is_flexbox_container_active = $experiments->is_feature_active( 'container' );
		}

		if ( ! $is_flexbox_container_active ) {
			return false;
		}

		return true;
	}

	/**
	 * Filter plugin name.
	 *
	 * @param string $plugin Plugin name.
	 * @return string
	 */
	public function filter_plugins( $plugin ) {
		$plugin = explode( '/', $plugin );
		return $plugin[0];
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @return void
	 */
	private function includes() {
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-base.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-options.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/API/class-local.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-wgl-elementor-custom-library-importer.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-template-importer.php';

		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/Core/Data/class-base-db.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/Core/Data/class-templates-db.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/Core/Data/class-library-data.php';
		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/Core/class-library-manager.php';

		require_once WGL_LIBRARY_PLUGIN_DIR . 'inc/class-elementor.php';
	}

	/**
	 * Returns Elementor instance.
	 *
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Load plugin language files.
	 *
	 * @access public
	 * @return void
	 */

	/**
	 * Retrieves the main instance of the plugin.
	 *
	 * @return Plugin Plugin main instance.
	 */
	public static function instance() {
		return self::$instance;
	}

	/**
	 * Loads the plugin main instance and initializes it.
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 */
	public static function load( $main_file ) {
		if ( null !== self::$instance ) {
			return false;
		}

		self::$instance = new self( $main_file );
		self::$instance->register();

		do_action( 'wgl_elementor_custom_library_loaded' );

		return true;
	}
}
