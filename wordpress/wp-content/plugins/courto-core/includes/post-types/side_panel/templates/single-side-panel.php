<?php
/**
 * Template for Side Panel CPT
 *
 * @package wgl-extensions\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();
the_post();

$sb = WGL_Framework::get_sidebar_data();
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '';
$container_class = $sb['container_class'] ?? '';

?>
<div class="wgl-container<?php echo apply_filters('wgl/container/class', $container_class); ?>">
<div class="row <?php echo apply_filters('wgl/row/class', $row_class); ?>">
    <div id='main-content' class="wgl_col-<?php echo apply_filters('wgl/column/class', $column); ?>">
        <?php

        the_content(esc_html__('Read More!', 'courto-core'));

        WGL_Framework::link_pages();

        if (comments_open() || get_comments_number()) {
            comments_template();
        }

    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();
