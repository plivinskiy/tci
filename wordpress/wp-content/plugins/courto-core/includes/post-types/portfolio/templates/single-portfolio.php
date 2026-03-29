<?php

defined('ABSPATH') || exit;

use WGL_Extensions\Templates\WGL_Portfolio;

/**
 * Single Page Template for Portfolio CPT
 *
 * @package wgl-extensions\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();

$sb = WGL_Framework::get_sidebar_data('portfolio_single');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';
$container_class .= $sb['row_class'] != ' sidebar_none' ? ' wgl-container' : '';

echo '<div class="wgl-portfolio-single_wrapper">';
echo '<div class="single_portfolio', apply_filters('wgl/container/class', $container_class), '">';
echo '<div class="row', apply_filters('wgl/row/class', $row_class), '">';
    echo '<div id="main-content" class="wgl_col-', apply_filters('wgl/column/class', $column), '">';

        while (have_posts()) :
            the_post();
            (new WGL_Portfolio())->render_item_single();
        endwhile;
        wp_reset_postdata();

        //* Navigation
        echo '<div class="wgl-container">';
            get_template_part('templates/post/post-navigation');
        echo '</div>';

        //* ↓ Related
        $related_enabled = WGL_Framework::get_option('portfolio_related_switch');
        if (class_exists('RWMB_Loader')) {
            $mb_related_switch = rwmb_meta('mb_portfolio_related_switch');
            if ('on' === $mb_related_switch) {
                $related_enabled = true;
            } elseif ('off' === $mb_related_switch) {
                $related_enabled = false;
            }
        }

        if (
            $related_enabled
            && class_exists('WGL_Extensions_Core') // TODO: maybe `Courto_Core` ?
            && class_exists('Elementor\Plugin')
        ) {
            $mb_pf_cat_r = [];
            if (class_exists('RWMB_Loader')) {
                $mb_pf_cat_r = get_post_meta(get_the_id(), 'mb_pf_cat_r'); // store terms’ IDs in the post meta and doesn’t set post terms.
            }

            $cats = get_the_terms(get_the_id(), 'portfolio-category') ?: [];
            $cat_slugs = [];
            foreach ($cats as $cat) {
                $cat_slugs[] = 'portfolio-category:' . $cat->slug;
            }

            if (!empty($mb_pf_cat_r[0])) {
                $cat_slugs = [];
                $list = get_terms('portfolio-category', ['include' => $mb_pf_cat_r[0]]);
                foreach ($list as $value) {
                    $cat_slugs[] = 'portfolio-category:' . $value->slug;
                }
            }

            $carousel_layout = WGL_Framework::get_mb_option('pf_carousel_r', 'mb_portfolio_related_switch', 'on');
            $columns_amount = WGL_Framework::get_mb_option('pf_column_r', 'mb_portfolio_related_switch', 'on');
            $posts_number = WGL_Framework::get_mb_option('pf_number_r', 'mb_portfolio_related_switch', 'on') ?: '12';
            $cursor_tooltip = WGL_Framework::get_option('pf_tooltip_r') ? 'pf_cursor_tooltip' : 'inside_image';

            $related_atts = [
                'layout' => 'related',
                'title_has_link' => 'yes',
                'image_has_link' => 'yes',
                'link_destination' => 'single',
                'appear_animation_enabled' => '',
                'show_filter' => '',
                'filter_alignment' => 'center',
                'gallery_mode_enabled' => '',
                'description_position' => $cursor_tooltip,
                'description_animation' => 'always_visible',
                'show_icon_image' => '',
                'show_portfolio_title' => 'true',
                'show_meta_categories' => 'true',
                'show_meta_date' => '',
                'show_icon_button' => '',
                'show_content' => '',
                'show_cat_sep' => '',
                'show_portfolio_title_first' => '',
                'add_video_background' => '',
                'grid_gap' => '30',
                'remainings_loading_btn_items_amount' => $columns_amount,
                'img_size_string' => '740x740',
                'img_size_array' => '',
                'img_aspect_ratio' => '',
                'linked_icon' => '',
                'description_alignment' => 'left',
                'title_sticky' => '',
                'fade_animation' => '',
                'animation_style' => 'default',
                // Cursor
                'tooltip_icon_type' => '',
                'tooltip_color' => '#fff',
                'tooltip_color_bg' => 'rgba(0,0,0,0)',
                'tooltip_color_bg_children' => '',
                'cursor_tooltip' => '',
                'cursor_prop' => 'yes',
                'cursor_tooltip_type' => 'custom',
                'tooltip_text' => esc_html__('Read More', 'courto-core'),
                'tooltip_descr' => '',
                'cursor_tooltip_bg' => true,
                // Carousel General
                'mb_pf_carousel_r' => $carousel_layout,
                'posts_per_row' => $columns_amount,
                'autoplay' => true,
                'autoplay_speed' => 5000,
                'animation_triggered_by_mouse' => false,
                'slider_infinite' => false,
                'slide_per_single' => 'yes',
                'variable_width' => '',
                'center_mode' => '',
                'carousel_appear_animation' => '',
                'center_info' => '',
                'adaptive_height' => '',
                'slides_transition' => 800,
                // Carousel Pagination
                'use_pagination' => false,
                'pagination_type' => 'circle_border',
                'pagination_dynamic' => false,
                // Carousel Navigation
                'use_navigation' => '',
                'navigation_position' => '',
                // Carousel Responsive
                'customize_responsive' => true,
                'widescreen_breakpoint' => 1601,
                'widescreen_slides' => '',
                'desktop_breakpoint' => 1201,
                'tablet_slides' => '',
                'tablet_breakpoint' => 768,
                'desktop_slides' => '',
                'mobile_breakpoint' => 280,
                'mobile_slides' => '',
                // Query
                'number_of_posts' => $posts_number,
                'order_by' => 'menu_order',
                'taxonomies' => $cat_slugs,
                'exclude_any' => 'yes',
                'by_posts' => [$post->post_name => $post->post_title] //* exclude current post
            ];

            $portfolio_related = new WGL_Portfolio($related_atts);

            $query = $portfolio_related->formalize_query();
            if ($query->post_count) {
                echo '<section class="wgl-container related_portfolio">';

                    $related_section_title = WGL_Framework::get_mb_option( 'portfolio_related_title', 'mb_portfolio_related_switch', 'on' );
                    if (!empty($related_section_title)) {
                        echo '<div class="courto_module_title">',
                            '<h4>',
                                esc_html($related_section_title),
                            '</h4>',
                        '</div>';
                    }

                    $portfolio_related->render();

                echo '</section>';
            }
        }
        //* ↑ related

        //* Comments
        if (comments_open() || get_comments_number()) {
            echo '<div class="wgl-container">';
                echo '<div class="row">';
                    echo '<div class="wgl_col-12">';
                        comments_template('', true);
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }

    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';
echo '</div>';

get_footer();