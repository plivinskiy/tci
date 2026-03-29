<?php
namespace WGL_Elementor_Templates\Custom_Library;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Finder_Shortcuts class.
 */
class Finder_Shortcuts extends Base_Category {
	/**
	 * Get ID.
	 *
	 * @access public
	 * @return string
	 */
	public function get_id() {
		return 'wgl-elementor-custom-library-shortcuts';
	}

	/**
	 * Get title.
	 *
	 * @access public
	 * @return string
	 */
	public function get_title() {
		return __( 'Custom Library for Elementor Shortcuts', 'wgl-extensions' );
	}

	/**
	 * Get category items.
	 *
	 * @access public
	 * @param array $options Old options.
	 * @return array
	 */
	public function get_category_items( array $options = array() ) {
		return array(
			'library'    => array(
				'title'    => __( 'Templates Library', 'wgl-extensions' ),
				'url'      => admin_url( 'admin.php?page=wgl_elementor_custom_library' ),
				'icon'     => 'library-download',
				'keywords' => array( 'library', 'settings' ),
			),
		);
	}
}
