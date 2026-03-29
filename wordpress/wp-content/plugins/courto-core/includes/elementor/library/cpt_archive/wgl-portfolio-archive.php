<?php
namespace WGL_Extensions\Library;

use Elementor\{
    TemplateLibrary\Source_Local,
	Modules\Library\Documents\Library_Document
};

defined('ABSPATH') || exit;

/**
 * WGL Elementor Portfolio Archive
 *
 *
 * @package nico-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.12
 * @version 1.0.0
 */
class WGL_Portfolio_Archive extends Library_Document
{
	/**
	* Elementor template-library post-type slug.
	*/
	const CPT = 'elementor_library';


	public static function get_type() {
		return 'wgl_portfolio_archive';
	}

	/**
	* WGL Library name.
	*/
	public static $name = 'wgl-portfolio-archive';

	public function __construct( array $data = [] ) {
		if ( $data ) {
			$template = get_post_meta( $data['post_id'], '_wp_page_template', true );

			if ( empty( $template ) ) {
				$template = 'default';
			}

			$data['settings']['template'] = $template;
		}

		parent::__construct( $data );
	}

	public static function get_properties(){
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'theme';
		$properties['location'] = 'wgl_portfolio_archive';
		$properties['condition_type'] = 'wgl_portfolio_archive';

		$properties['support_kit'] = true;
		return $properties;
	}

	public function get_name(){
		return self::$name;
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return esc_html__( 'WGL Portfolio Archive', 'courto-core' );
	}

	public static function get_plural_title() {
		return esc_html__( 'WGL Portfolio Archive', 'courto-core' );
	}

	/** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
	public static function get_single_template($single_template){

		global $post;
		$template_type = Source_Local::get_template_type($post->ID);

		if(self::CPT === $post->post_type && self::$name === $template_type) {
			$single_template = plugin_dir_path(  __FILE__  ) . 'templates/single-portfolio-archive.php';
		}

		//\Elementor\Plugin::$instance->files_manager->clear_cache();

		return $single_template;
	}

	public static function get_class_full_name() {
		return get_called_class();
	}
}