<?php

defined('ABSPATH') || exit;
/**
 * Archive Page Template for Portfolio CPT
 *
 * @package nico-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */


// Sidebar parameters
$sb = WGL_Framework::get_sidebar_data('portfolio_list');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

// Render
get_header();

echo '<div class="wgl-container', apply_filters('wgl/container/class', $container_class), '">';
echo '<div class="row', apply_filters('wgl/row/class', $row_class), '">';

    echo '<div id="main-content" class="wgl_col-', apply_filters('wgl/column/class', $column), '">';

	do_action( 'elementor/page_templates/canvas/before_content' );

	\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

	/**
	 * After canvas page template content.
	 *
	 * Fires after the content of Elementor canvas page template.
	 *
	 * @since 1.0.0
	 */
	do_action( 'elementor/page_templates/canvas/after_content' );


    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();

