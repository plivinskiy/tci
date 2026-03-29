<?php

defined('ABSPATH') || exit;

/**
 * Template for Page 404
 *
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package courto
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();

$layout_building_tool = WGL_Framework::get_option('404_building_tool');
if (
    'elementor' === $layout_building_tool
    && did_action('elementor/loaded')
) {

    $selected_page_id = WGL_Framework::get_option('404_template_select');
    $selected_page_id = wgl_dynamic_styles()->multi_language_support($selected_page_id, 'elementor_library');

    if (class_exists('\Elementor\Core\Files\CSS\Post')) {
        (new \Elementor\Core\Files\CSS\Post($selected_page_id))->enqueue();
    }

    echo \Elementor\Plugin::$instance->frontend->get_builder_content($selected_page_id);

} else {
    $styles = $section_padding_html = '';
    $bg_image = WGL_Framework::bg_render('404_page_main');
    $section_padding = WGL_Framework::get_option('404_page_main_padding');

    $section_padding_html .= !empty($section_padding['padding-top']) ? ' padding-top:' . (int) $section_padding['padding-top'] . 'px;' : '';
    $section_padding_html .= !empty($section_padding['padding-bottom']) ? ' padding-bottom:' . (int) $section_padding['padding-bottom'] . 'px;' : '';

    $styles .= $bg_image ?: '';
    $styles .= $section_padding_html ?: '';
    $styles_html = $styles ? ' style="' . esc_attr($styles) . '"' : "";

    ?><div class="wgl-container full-width"<?php echo WGL_Framework::render_html($styles_html); ?>>
        <div class="row">
            <div class="wgl_col-12">
                <section class="page_404_wrapper">
                    <div class="page_404_wrapper-container">
                        <div class="error_page row">
                            <div class="wgl_col-12"><?php
                                echo '<div class="error_page__num">';
                                    echo '<div class="error_page__img">',
                                        '<img',
                                        ' src="', esc_url(get_template_directory_uri() . '/img/404.webp'), '"',
                                        ' alt="', esc_attr__('404 decoration', 'courto'), '"',
                                        '>',
                                    '</div>';
                                echo '</div>';?>
                                <h2 class="error_page__title"><?php
                                    esc_html_e('LOST IN CYBERSPACE: 404 EDITION', 'courto'); ?>
                                </h2>

                                <p class="error_page__description"><?php
                                    esc_html_e('The page you are looking for was moved, removed, renamed or never existed.', 'courto'); ?>
                                </p>

                                <div class="courto_404_search"><?php
                                    get_search_form(); ?>
                                </div>

                                <div class="courto_404__button elementor-widget-wgl-button">
                                    <a class="wgl-button btn-size-xl" href="<?php echo esc_url(home_url('/')); ?>">
                                        <div class="button__content">
                                            <span class="button__text"><?php
                                                esc_html_e('BACK TO HOME', 'courto'); ?>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div><?php
}

get_footer();