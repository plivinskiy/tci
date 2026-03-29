<?php
/**
 * Class for importing a template.
 *
 * @package WGL_Elementor_Templates\Custom_Library
 */

namespace Elementor\TemplateLibrary;

use WGL_Elementor_Templates\Custom_Library\Core\Data\Library_Data;
use WGL_Elementor_Templates\Custom_Library\Plugin;

/**
 * Class WGL_Elementor_Templates_Custom_Library_Importer.
 *
 * @package Elementor\TemplateLibrary
 */
class WGL_Elementor_Templates_Custom_Library_Importer extends Source_Remote {
	/**
	 * Get local template data.
	 *
	 * @inheritDoc
	 *
	 * @param array       $args    Custom template arguments.
	 * @param string      $context Optional. The context. Default is `display`.
	 * @param object|bool $data Template/block import data.
	 *
	 * @return array Remote Template data.
	 */
	public function get_local_data( array $args, $context = 'display', $data = false ) {
		if ( ! $data ) {
			$data = Library_Data::prepare_template_content( $args['template_id'] );
		}

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		Plugin::elementor()->editor->set_edit_mode( true );

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::elementor()->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}
		
		$data['content'] = self::convert_string_to_boolean( $data['content'] );

		return $data;
	}

	/**
	 * Convert string to boolean.
	 *
	 * @param array $data Array object.
	 * @return array
	*/
	public static function convert_string_to_boolean( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		array_walk_recursive(
			$data,
			function ( &$value, $key ) {
				if ( 'isInner' === $key || 'isLinked' === $key ) {
					$value = (bool) $value;
				}
			}
		);

		return $data;
	}
}
