<?php
/**
 * Archive Page Template for Team CPT
 *
 * @package courto-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

use WGL_Extensions\Templates\WGL_Team;

$page_id = false;
$query_args = [
    'post_type' => 'elementor_library',
    'post_status' => 'publish',
    'no_found_rows' => true,
    'posts_per_page' => 1,
    'meta_query' => [
        [
            'key' => '_elementor_template_type',
            'value' => 'wgl-team-archive',
        ],
    ],
];
$templates_query = \WGL_Extensions\Includes\WGL_Loop_Settings::cache_query($query_args);

if(!empty($templates_query->posts)){
    $page_id = wgl_dynamic_styles()->multi_language_support($templates_query->posts[0]->ID, 'elementor_library');
}

// Taxonomies
$tax_obj = get_queried_object();
$term_id = $tax_obj->term_id ?? '';
if ($term_id) {
    $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
    $tax_description = $tax_obj->description;
}

$attributes = [
    'layout' => 'hero',
    'posts_per_row' => '3',
    'thumbnail_linked' => true,
    'heading_linked' => true,
    'hide_content' => true,
    'content_limit' => '100',
    'info_align' => 'left',
    'img_size_string' => '',
    'img_size_array' => '',
    'img_aspect_ratio' => '',
    'hide_title' => '',
    'hide_socials' => '',
    'cursor_tooltip' => '',
    'thumbnail_dimensions' => ['width' => '540', 'height' => '620'],
    'socials_official_colors' => ['idle' => false, 'hover' => false],
    'hide_highlited_info' => '',
    'use_carousel' => '',
    // Query
    'post_type' => 'team',
    'number_of_posts' => 'all',
    'order_by' => 'date',
    'taxonomies' => $taxonomies ?? [],
];

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

    if(!empty($page_id)){
        echo \Elementor\Plugin::$instance->frontend->get_builder_content( $page_id, true );
    }else{
        if ($term_id) {
            echo '<div class="archive__heading">',
                '<h4 class="archive__tax_title">',
                    get_the_archive_title(),
                '</h4>',
                (!empty($tax_description) ? '<div class="archive__tax_description">' . esc_html($tax_description) . '</div>' : ''),
            '</div>';
        }
        echo '<div class="archive__team">';
        (new WGL_Team())->render([], $attributes);
        echo '</div>';
    }

    echo '</div>';

echo '</div>';
echo '</div>';

get_footer();
