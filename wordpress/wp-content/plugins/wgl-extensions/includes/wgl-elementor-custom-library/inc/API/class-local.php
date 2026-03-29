<?php
/**
 * APIs.
 *
 * @package WGL_Elementor_Templates\Custom_Library
 */

namespace WGL_Elementor_Templates\Custom_Library\API;

use WGL_Elementor_Templates\Custom_Library\Plugin;
use WGL_Elementor_Templates\Custom_Library\Base;
use WGL_Elementor_Templates\Custom_Library\Options;
use Elementor\TemplateLibrary\WGL_Elementor_Templates_Custom_Library_Importer;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WGL_Elementor_Templates\Custom_Library\Core\Data\Library_Data;

defined( 'ABSPATH' ) || exit;

/**
 * Local APIs.
 *
 * @package WGL_Elementor_Templates\Custom_Library\API
 */
class Local extends Base {
	/**
	 * Local constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ]);
	}

	/**
	 * Register API endpoints.
	 *
	 * @return void
	 */
	public function register_endpoints() {
		$endpoints = array(
			'/templates'               => array(
				WP_REST_Server::READABLE => 'library_templates_list',
			),
			'/mark_favorite/'          => array(
				WP_REST_Server::CREATABLE => 'mark_as_favorite',
			),
			'/get/settings/'           => array(
				WP_REST_Server::READABLE => 'get_settings',
			),
			'/update/settings/'        => array(
				WP_REST_Server::CREATABLE => 'update_setting',
			),
			'/blocks/insert'           => array(
				WP_REST_Server::CREATABLE => 'get_template_content',
			),
		);

		foreach ( $endpoints as $endpoint => $details ) {
			foreach ( $details as $method => $callback ) {
				register_rest_route(
					'wgl-library/v1',
					$endpoint,
					array(
						'methods'             => $method,
						'callback'            => array( $this, $callback ),
						'permission_callback' => array( $this, 'rest_permission_check' ),
						'args'                => array(),
					)
				);
			}
		}
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @return WP_Error|bool
	 */
	public function rest_permission_check() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Mark a template or block as favorite.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function mark_as_favorite( WP_REST_Request $request ) {
		$type = Plugin::$user_meta_prefix;
		if ( ! empty( $request->get_param( 'type' ) ) && 'block' === $request->get_param( 'type' ) ) {
			$type = Plugin::$user_meta_block_prefix;
		}
		$id        = $request->get_param( 'id' );
		$favorite  = $request->get_param( 'favorite' );
		$favorites = get_user_meta( get_current_user_id(), $type, true );

		if ( ! $favorites ) {
			$favorites = array();
		}

		if ( $favorite ) {
			$favorites[ $id ] = $favorite;
		} elseif ( isset( $favorites[ $id ] ) ) {
			unset( $favorites[ $id ] );
		}

		$data                  = array();
		$data['id']            = $id;
		$data['action']        = $favorite;
		$data['update_status'] = update_user_meta( get_current_user_id(), $type, $favorites );
		$data['favorites']     = get_user_meta( get_current_user_id(), $type, true );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Handle local template import.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_template_content( WP_REST_Request $request ) {
		$block  = $request->get_param( 'block' );
		$method = $request->get_param( 'method' );

		if ( ! $block ) {
			return new WP_Error( 'template_import_error', __( 'Invalid Template ID.', 'wgl-extensions' ) );
		}

		$data = $this->process_block_import( $block, $method );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Process block import functionaliities.
	 *  1. Imports the remote template.
	 *  2. Then with retrieved content, creates a page.
	 *
	 * @uses \Elementor\TemplateLibrary\WGL_Elementor_Templates_Custom_Library_Importer
	 *
	 * @param array  $block Block data.
	 * @param string $method Import method.
	 *
	 * @return array|WP_Error
	 */
	protected function process_block_import( $block, $method = 'library' ) {

		$raw_data = Library_Data::prepare_template_content( $block['id'], $method );
		$importer = new WGL_Elementor_Templates_Custom_Library_Importer();

		$data = $importer->get_local_data(
			array(
				'editor_post_id' => false,
			),
			'display',
			$raw_data
		);

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$payload = array( 'data' => $data );

		return $payload;
	}

	/**
	 * Get plugin settings.
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings() {
		$options = Options::get_instance()->get();

		return new WP_REST_Response( $options, 200 );
	}

	/**
	 * Update plugin settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_setting( WP_REST_Request $request ) {
		$key   = $request->get_param( 'key' );
		$value = $request->get_param( 'value' );

		if ( ! $key ) {
			return new WP_Error( 'settings_error', __( 'No options key provided.', 'wgl-extensions' ) );
		}

		Options::get_instance()->set( $key, $value );

		return new WP_REST_Response(
			array( 'message' => __( 'Setting updated.', 'wgl-extensions' ) ),
			200
		);
	}

	/**
	 * Get templates library.
	 *
	 * @param \WP_REST_Request $request WP REST request instance.
	 * @return array
	 */
	public function library_templates_list( \WP_REST_Request $request ) {
		return array(
			'library' => array(
				'blocks'    => Library_Data::templates(),
				'templates' => array(),
			),
		);
	}
}

new Local();
