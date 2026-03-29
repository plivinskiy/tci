<?php

/**
 * Load Theme Dependencies
 */
require_once get_theme_file_path('/core/class/theme-dependencies.php');

/**
 * Sequence of theme specific actions
 */

add_action('after_setup_theme', function() {
    $content_width = $content_width ?? 940;
}, 0);

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
	add_theme_support( 'html5', [
		'gallery',
		'caption',
	]);
});

add_action('init', function() {
    add_post_type_support('page', 'excerpt');
});

/** Add a pingback url auto-discovery for single posts, pages or attachments. */
add_action('wp_head', function() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
});

add_action( 'current_screen', function() {
    add_editor_style( 'css/font-awesome-5.min.css' );
} );

add_action( 'wgl/preloader', 'WGL_Framework::preloader' );

add_action( 'wgl/after_main_content', 'courto_after_main_content' );

add_action( 'wp_footer', 'courto_cursor' );

/**
 * Sequence of theme specific filters
 */

add_filter( 'wgl/header/enable', 'courto_header_enable' );

add_filter( 'wgl/page_title/enable', 'courto_page_title_enable' );

add_filter( 'wgl/footer/enable', 'courto_footer_enable' );

add_filter( 'comment_form_fields', 'courto_comment_form_fields' );

add_filter('mce_buttons_2', function($buttons) {
	array_unshift($buttons, 'styleselect');
    return $buttons;
});

add_filter('wgl/redux/letter_spacing_unit', function($unit) {
    return 'em';
});

add_filter('tiny_mce_before_init', 'courto_tiny_mce_before_init');
add_filter('mce_buttons_2', 'courto_tiny_mce_buttons_2' );

add_filter('wp_list_categories', 'courto_categories_postcount_filter');
add_filter('woocommerce_layered_nav_term_html', 'courto_categories_postcount_filter');

add_filter('get_archives_link', 'courto_render_archive_widgets', 10, 6);

add_filter('wgl/enqueue_shortcode_css', function( $styles ) {
    global $courto_dynamic_css;
    if ( ! isset( $courto_dynamic_css[ 'style' ] ) ) {
        $courto_dynamic_css = [];
        $courto_dynamic_css['style'] = $styles;
    } else {
        $courto_dynamic_css['style'] .= $styles;
    }
});

add_filter('widget_types_to_hide_from_legacy_widget_block', function () {
    return [];
}, 10);

add_filter( 'wpcf7_autop_or_not', '__return_false');

add_filter('woocommerce_create_pages', function ($pages) {
    unset($pages['checkout']);
    unset($pages['cart']);
    return $pages;
}, 10);

add_filter('elementor/icons_manager/additional_tabs', function ($additional_tabs) {
    if (isset($additional_tabs['wgl_icons'])) {
        unset($additional_tabs['wgl_icons']);
    }
    return $additional_tabs;
}, 10);
