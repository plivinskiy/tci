<?php
/**
 * Elementor core integration.
 *
 * @package WGL_Elementor_Templates\Custom_Library
 */

namespace WGL_Elementor_Templates\Custom_Library;

use WGL_Elementor_Templates\Custom_Library\Core\Library_Manager;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;

/**
 * Intializes scripts/styles needed for WGL_Elementor_Templates modal on Elementor editing page.
 */
class Elementor {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Initiate Library.
		Library_Manager::get_instance();

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_editor_scripts' ) );

		add_action(
			'elementor/finder/register',
			static function ( Categories_Manager $categories_manager ) {
				include_once WGL_LIBRARY_PLUGIN_DIR . 'inc/Elementor/class-finder-shortcuts.php';
				$categories_manager->register( new Finder_Shortcuts() );
			}
		);

		add_filter( 'elementor/editor/templates', array( $this, 'register_template_overrides' ) );
	}

	/**
	 * Load styles and scripts for Elementor modal.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		// Independent components.
		wp_enqueue_style( 'wgl-elementor-custom-library-components-css', WGL_LIBRARY_PLUGIN_URL . 'assets/css/library-components.css', array(), filemtime( WGL_LIBRARY_PLUGIN_DIR . 'assets/css/library-components.css' ) );

		wp_enqueue_script( 'wgl-elementor-custom-library-elementor-modal', WGL_LIBRARY_PLUGIN_URL . 'assets/js/elementor-modal.js', array( 'jquery' ), filemtime( WGL_LIBRARY_PLUGIN_DIR . 'assets/js/elementor-modal.js' ), false );
		wp_enqueue_style( 'wgl-elementor-custom-library-elementor-modal', WGL_LIBRARY_PLUGIN_URL . 'assets/css/elementor-modal.css', array( 'dashicons' ), filemtime( WGL_LIBRARY_PLUGIN_DIR . 'assets/css/elementor-modal.css' ) );

		wp_enqueue_script(
			'wgl-elementor-custom-library-app',
			WGL_LIBRARY_PLUGIN_URL . 'assets/js/app/index.js',
			array(
				'react',
				'react-dom',
				'jquery',
				'wp-components',
				'wp-hooks',
				'wp-i18n',
				'wp-api-fetch',
				'wp-html-entities',
			),
			filemtime( WGL_LIBRARY_PLUGIN_DIR . 'assets/js/app/index.js' ),
			true
		);
		wp_set_script_translations( 'wgl-elementor-custom-library-app', 'wgl-extensions', WGL_LIBRARY_PLUGIN_DIR . 'languages' );

		wp_enqueue_style( 'wp-components' );

		$l10n = apply_filters( // phpcs:ignore
			'wgl/elementor/library/app/strings',
			array(
				'is_settings_page'   => false,
				'library_title_text' => __( 'WGL Library', 'wgl-extensions' ),
			)
		);

		wp_localize_script( 'wgl-elementor-custom-library-app', 'WGL_LIBRARY', $l10n );

		do_action( 'wgl_elementor_custom_library_loaded_scripts_styles' );
	}

	/**
	 * Editor template overrides.
	 *
	 * @param array $templates List of templates.
	 * @return mixed
	 */
	public function register_template_overrides( $templates ) {
		Plugin::elementor()->common->add_template( WGL_LIBRARY_PLUGIN_DIR . 'inc/Elementor/editor-templates/templates.php' );
		return $templates;
	}
}

new Elementor();
