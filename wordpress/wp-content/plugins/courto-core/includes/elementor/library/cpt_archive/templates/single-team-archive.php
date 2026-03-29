<?php
/**
 * Archive Page Template for Team CPT
 *
 * @package nico-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */


// Render
get_header();

echo '<div class="wgl-container">';
    echo '<div id="main-content">';
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
echo '</div>';

get_footer();
