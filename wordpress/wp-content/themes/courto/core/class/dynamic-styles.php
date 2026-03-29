<?php

defined( 'ABSPATH' ) || exit;

use WGL_Extensions\Includes\WGL_Elementor_Helper;
use WGL_Extensions\WGL_Framework_Global_Variables;

/**
 * Dynamic Styles
 *
 *
 * @package courto\core\class
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Framework_Dynamic_Styles
{
    protected static $instance;

    private $template_directory_uri;
    private $use_minified;
    private $enqueued_stylesheets = [];
    private $header_page_id;
    private $header_building_tool;
    private $gradient_enabled;

    public function __construct()
    {
        // do nothing.
    }

    public static function instance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function construct()
    {
        $this->template_directory_uri = get_template_directory_uri();
        $this->use_minified = WGL_Framework::get_option('use_minified') ? '.min' : '';
        $this->header_building_tool = WGL_Framework::get_option('header_building_tool');
        $this->gradient_enabled = WGL_Framework::get_mb_option('use-gradient', 'mb_page_colors_switch', 'custom');

        $this->enqueue_styles_and_scripts();
        $this->add_body_classes();
    }

    public function enqueue_styles_and_scripts()
    {
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_stylesheets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'wgl_wp_head_custom_css' ] );

        //* Elementor Compatibility
        add_action( 'wp_enqueue_scripts', [ $this, 'get_elementor_css_theme_builder' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'elementor_column_fix' ] );

        add_action( 'admin_enqueue_scripts', [ $this, 'admin_stylesheets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
    }

    public function get_elementor_css_theme_builder()
    {
        $current_post_id = get_the_ID();
        $css_files = [];

        $locations[] = $this->get_elementor_css_cache_header();
        $locations[] = $this->get_elementor_css_cache_header_sticky();
        $locations[] = $this->get_elementor_css_cache_footer();
        $locations[] = $this->get_elementor_css_cache_side_panel();
        $locations[] = $this->get_elementor_css_cache_mobile_drawer();

        foreach ($locations as $location) {
            //* Don't enqueue current post here (let the preview/frontend components to handle it)
            if ($location && $current_post_id !== $location) {
                $css_file = new \Elementor\Core\Files\CSS\Post($location);
                $css_files[] = $css_file;
            }
        }

        if (!empty($css_files)) {
            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            \Elementor\Plugin::$instance->widgets_manager->enqueue_widgets_styles();
            foreach ($css_files as $css_file) {
                $css_file->enqueue();
            }
        }
    }

    public function get_elementor_css_cache_header()
    {
        if (
            ! apply_filters( 'wgl/header/enable', true )
            || ! class_exists( '\Elementor\Core\Files\CSS\Post' )
        ) {
            // Bailtout.
            return;
        }

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta( 'mb_customize_header_layout' )
            && 'default' !== rwmb_meta( 'mb_header_content_type' )
        ) {
            $this->header_building_tool = 'elementor';
            $this->header_page_id = rwmb_meta( 'mb_customize_header' );
        } else {
            $this->header_page_id = WGL_Framework::get_option( 'header_page_select' );
        }

        // Shop Catalog custom header template
        if (
            function_exists('is_woocommerce') && is_woocommerce()
            || function_exists('is_cart') && is_cart()
            || function_exists('is_checkout') && is_checkout()
            || function_exists('is_account_page') && is_account_page()
        ) {
            if ('0' == WGL_Framework::get_option('shop_catalog_header_conditional')) {
                $this->header_page_id = WGL_Framework::get_option('shop_catalog_header_page_select') ?: $this->header_page_id;
            }
        }

        if ( 'elementor' === $this->header_building_tool ) {
            return $this->multi_language_support( $this->header_page_id, 'header' );
        }
    }

    public function get_elementor_css_cache_header_sticky()
    {
        if (
            ! apply_filters( 'wgl/header/enable', true )
            || 'elementor' !== $this->header_building_tool
            || ! class_exists( '\Elementor\Core\Files\CSS\Post' )
        ) {
            // Bailtout.
            return;
        }

        $header_sticky_page_id = '';

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta( 'mb_customize_header_layout' )
            && 'default' !== rwmb_meta( 'mb_sticky_header_content_type' )
        ) {
            $header_sticky_page_id = rwmb_meta( 'mb_customize_sticky_header' );
        } elseif ( WGL_Framework::get_option( 'header_sticky' ) ) {
            $header_sticky_page_id = WGL_Framework::get_option( 'header_sticky_page_select' );
        }

        return $this->multi_language_support( $header_sticky_page_id, 'header' );
    }

    public function get_elementor_css_cache_footer()
    {
        $footer = apply_filters( 'wgl/footer/enable', true );
        $footer_switch = $footer[ 'footer_switch' ] ?? '';

        if (
            ! $footer_switch
            || 'elementor' !== WGL_Framework::get_mb_option( 'footer_building_tool', 'mb_footer_switch', 'on' )
            || ! class_exists( '\Elementor\Core\Files\CSS\Post' )
        ) {
            // Bailout.
            return;
        }

        $footer_page_id = WGL_Framework::get_mb_option( 'footer_page_select', 'mb_footer_switch', 'on' );

        return $this->multi_language_support( $footer_page_id, 'footer' );
    }

    public function get_elementor_css_cache_side_panel()
    {
        if (
            !WGL_Framework::get_option('side_panel_enabled')
            || 'elementor' !== WGL_Framework::get_mb_option('side_panel_building_tool', 'mb_customize_side_panel', 'custom')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return;
        }

        $sp_page_id = WGL_Framework::get_mb_option('side_panel_page_select', 'mb_customize_side_panel', 'custom');

        return $this->multi_language_support($sp_page_id, 'side_panel');
    }

    public function get_elementor_css_cache_mobile_drawer()
    {
        $mobile_header_custom = WGL_Framework::get_option('mobile_header');
        if (
            !empty($mobile_header_custom) && 'elementor' !==  WGL_Framework::get_option('mobile_drawer_header_building_tool')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return;
        }

        $header_drawer_settings = \Elementor\Core\Settings\Manager::get_settings_managers('page')->get_model($this->header_page_id)->get_data('settings')['mobile_drawer_template'] ?? '';
        $active_template = '';
        if(!empty($header_drawer_settings) && 'wgl_default_template_drawer' !== $header_drawer_settings){
            $active_template = (int) $header_drawer_settings;
        }else{
            $active_template = (int) WGL_Framework::get_option('mobile_drawer_header_page_select');
        }

        if(empty($active_template)){
            // Bailout.
            return;
        }

        return $this->multi_language_support($active_template, 'elementor_library');
    }

    public function multi_language_support($page_id, $page_type)
    {
        if (!$page_id) {
            // Bailout.
            return;
        }

        $page_id = intval($page_id);

        if (class_exists('Polylang') && function_exists('pll_current_language')) {
            $currentLanguage = pll_current_language();
            $translations = PLL()->model->post->get_translations($page_id);

            $polylang_id = $translations[$currentLanguage] ?? '';
            $page_id = $polylang_id ?: $page_id;
        }

        if (class_exists('SitePress')) {
            $wpml_id = wpml_object_id_filter($page_id, $page_type, false, ICL_LANGUAGE_CODE);
            if (
                $wpml_id
                && 'trash' !== get_post_status($wpml_id)
            ) {
                $page_id = $wpml_id;
            }
        }

        return $page_id;
    }

    public function elementor_column_fix()
    {
        $css = '.elementor-container > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-container > .elementor-column > .elementor-element-populated {'
                . 'padding-top: 0;'
                . 'padding-bottom: 0;'
            . '}';

        $css .= '.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-column-gap-default > .elementor-column > .theiaStickySidebar > .elementor-element-populated,'
            . '.elementor-column-gap-default > .elementor-column > .elementor-element-populated {'
                . 'padding-left: 15px;'
                . 'padding-right: 15px;'
            . '}';

        wp_add_inline_style('elementor-frontend', $css);
    }

    public function frontend_stylesheets()
    {
        wp_enqueue_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-info',
            get_bloginfo('stylesheet_url'),
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $this->enqueue_css_variables();
        $this->enqueue_additional_styles();
        $this->enqueue_theme_stylesheet( 'main' . (is_rtl() ? '-rtl' : ''), '/css/' );
        $this->enqueue_pluggable_styles();
        $this->enqueue_theme_stylesheet( 'responsive' . (is_rtl() ? '-rtl' : ''), '/css/', $this->enqueued_stylesheets );
        $this->enqueue_theme_stylesheet( 'dynamic' . (is_rtl() ? '-rtl' : ''), '/css/', $this->enqueued_stylesheets );

        if (is_rtl()) {
            wp_enqueue_style(
                'rtl-mod',
                $this->template_directory_uri . '/css/rtl-mod.css',
                [],
                WGL_Framework_Global_Variables::get_theme_version()
            );
        }
    }

    public function enqueue_css_variables()
    {
        return wp_add_inline_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-info',
            $this->retrieve_css_variables_and_extra_styles()
        );
    }

    public function enqueue_additional_styles()
    {
        wp_enqueue_style(
            'select2',
            $this->template_directory_uri . '/js/select2/css/select2.css',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        wp_enqueue_style(
            'font-awesome-5-all',
            $this->template_directory_uri . '/css/font-awesome-5.min.css',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );
    }

    public function wgl_wp_head_custom_css()
    {
        if (class_exists('WGL_Framework')) {
            $header_custom_code = WGL_Framework::get_option('custom_css');
        }
        echo isset($header_custom_code) ? '<style>'.$header_custom_code.'</style>' : '';
    }

    public function retrieve_css_variables_and_extra_styles()
    {
        if (class_exists('Redux')) {
            // Customizer
            if (!empty($GLOBALS['courto_set'])) {
                new WGL_Framework_Global_Variables();
            }
        }

        $root_vars = $extra_css = '';

        /**
         * Color Variables
         */
        if (
            class_exists('RWMB_Loader')
            && 'custom' === WGL_Framework::get_mb_option('page_colors_switch')
        ) {
            $theme_primary_color = WGL_Framework::get_mb_option('theme-primary-color');
            $theme_secondary_color = WGL_Framework::get_mb_option('theme-secondary-color');
            $theme_tertiary_color = WGL_Framework::get_mb_option('theme-tertiary-color');
            $theme_quaternary_color = WGL_Framework::get_mb_option('theme-quaternary-color');

            $main_font_color = WGL_Framework::get_mb_option( 'theme-content-color' );
            $h_font_color = WGL_Framework::get_mb_option( 'theme-headings-color' );

            $form_bg_color = WGL_Framework::get_mb_option( 'form-bg-color' );
            $form_border_color = WGL_Framework::get_mb_option( 'form-border-color' );

            $button_color_idle = WGL_Framework::get_mb_option( 'button-color-idle' );
            $button_bg_idle = WGL_Framework::get_mb_option( 'button-bg-idle' );
            $button_border_idle = WGL_Framework::get_mb_option( 'button-border-idle' );
            $button_color_hover = WGL_Framework::get_mb_option( 'button-color-hover' );
            $button_bg_hover = WGL_Framework::get_mb_option( 'button-bg-hover' );
            $button_border_hover = WGL_Framework::get_mb_option( 'button-border-hover' );

            $cursor_point_color = !WGL_Framework::get_option('cursor_switch') ? 'transparent' : (WGL_Framework::get_option('cursor_color')['rgba'] ?? '');

            $scroll_up_arrow_color = WGL_Framework::get_mb_option('scroll_up_arrow_color');
            $scroll_up_arrow_color_bg = WGL_Framework::get_mb_option('scroll_up_arrow_color_bg');
            $scroll_up_arrow_color_border = WGL_Framework::get_mb_option('scroll_up_arrow_color_border');

            $this->gradient_enabled && $theme_gradient_from = WGL_Framework::get_mb_option('theme-gradient-from');
            $this->gradient_enabled && $theme_gradient_to = WGL_Framework::get_mb_option('theme-gradient-to');
        } else {
            $theme_primary_color = WGL_Framework_Global_Variables::get_primary_color();
            $theme_secondary_color = WGL_Framework_Global_Variables::get_secondary_color();
            $theme_tertiary_color = WGL_Framework_Global_Variables::get_tertiary_color();
            $theme_quaternary_color = WGL_Framework_Global_Variables::get_quaternary_color();

            $main_font_color = WGL_Framework_Global_Variables::get_main_font_color();
            $h_font_color = WGL_Framework_Global_Variables::get_h_font_color();

            $button_color_idle = WGL_Framework_Global_Variables::get_btn_color_idle();
            $button_bg_idle = WGL_Framework_Global_Variables::get_btn_bg_idle();
            $button_border_idle = WGL_Framework_Global_Variables::get_btn_border_idle();
            $button_color_hover = WGL_Framework_Global_Variables::get_btn_color_hover();
            $button_bg_hover = WGL_Framework_Global_Variables::get_btn_bg_hover();
            $button_border_hover = WGL_Framework_Global_Variables::get_btn_border_hover();

            $cursor_point_color = WGL_Framework_Global_Variables::get_cursor_point_color();

            $form_bg_color = WGL_Framework::get_option( 'form-bg-color' );
            $form_border_color = WGL_Framework::get_option( 'form-border-color' );

            $scroll_up_arrow_color = WGL_Framework::get_option('scroll_up_arrow_color');
            $scroll_up_arrow_color_bg = WGL_Framework::get_option('scroll_up_arrow_color_bg');
            $scroll_up_arrow_color_border = WGL_Framework::get_option('scroll_up_arrow_color_border');

            $this->gradient_enabled && $theme_gradient = WGL_Framework::get_option('theme-gradient');
        }

	    $root_vars .= '--courto-primary-color: ' . esc_attr( $theme_primary_color ?: 'unset' ) . ';';
	    $root_vars .= '--courto-secondary-color: ' . esc_attr( $theme_secondary_color ?: 'unset' ) . ';';
	    $root_vars .= '--courto-tertiary-color: ' . esc_attr( $theme_tertiary_color ?: 'unset' ) . ';';
	    $root_vars .= '--courto-quaternary-color: ' . esc_attr( $theme_quaternary_color ?: 'unset' ) . ';';

	    $root_vars .= '--courto-button-color-idle: ' . esc_attr( $button_color_idle ?: 'unset' ) . ';';
	    $root_vars .= '--courto-button-bg-idle: ' . esc_attr( $button_bg_idle ?: 'unset' ) . ';';
	    $root_vars .= '--courto-button-border-idle: ' . esc_attr( $button_border_idle ?: 'unset' ) . ';';
	    $root_vars .= '--courto-button-color-hover: ' . esc_attr( $button_color_hover ?: 'unset' ) . ';';
	    $root_vars .= '--courto-button-bg-hover: ' . esc_attr( $button_bg_hover ?: 'unset' ) . ';';
	    $root_vars .= '--courto-button-border-hover: ' . esc_attr( $button_border_hover ?: 'unset' ) . ';';

	    $root_vars .= '--courto-button-color-rgb-idle: ' . ( $button_color_idle ? esc_attr(WGL_Framework::hex_to_rgb($button_color_idle)) : 'unset' ) . ';';
	    $root_vars .= '--courto-button-bg-rgb-idle: ' . ( $button_bg_idle ? esc_attr(WGL_Framework::hex_to_rgb($button_bg_idle)) : 'unset' ) . ';';
	    $root_vars .= '--courto-button-border-rgb-idle: ' . ( $button_border_idle ? esc_attr(WGL_Framework::hex_to_rgb($button_border_idle)) : 'unset' ) . ';';
	    $root_vars .= '--courto-button-color-rgb-hover: ' . ( $button_color_hover ? esc_attr(WGL_Framework::hex_to_rgb($button_color_hover)) : 'unset' ) . ';';
	    $root_vars .= '--courto-button-bg-rgb-hover: ' . ( $button_bg_hover ? esc_attr(WGL_Framework::hex_to_rgb($button_bg_hover)) : 'unset' ) . ';';
	    $root_vars .= '--courto-button-border-rgb-hover: ' . ( $button_border_hover ? esc_attr(WGL_Framework::hex_to_rgb($button_border_hover)) : 'unset' ) . ';';

        $root_vars .= '--courto-form-bg-color: ' . ( $form_bg_color ? esc_attr($form_bg_color) : 'unset' ) . ';';
        $root_vars .= '--courto-form-bg-color-rgb: ' . ( $form_bg_color ? esc_attr(WGL_Framework::hex_to_rgb($form_bg_color)) : '255,255,255' ) . ';';
        $root_vars .= '--courto-form-border-color: ' . ( $form_border_color ? esc_attr($form_border_color) : 'unset' ) . ';';
        $root_vars .= '--courto-form-border-color-rgb: ' . ( $form_border_color ? esc_attr(WGL_Framework::hex_to_rgb($form_border_color)) : '255,255,255' ) . ';';

	    $root_vars .= '--courto-back-to-top-color: ' . ( $scroll_up_arrow_color ? esc_attr($scroll_up_arrow_color) : 'unset' ) . ';';
	    $root_vars .= '--courto-back-to-top-color-bg: ' . ( $scroll_up_arrow_color_bg ? esc_attr($scroll_up_arrow_color_bg) : 'unset' ) . ';';
        if (!empty($scroll_up_arrow_color_border)){
            $root_vars .= '--courto-back-to-top-border: 1px solid ' . esc_attr($scroll_up_arrow_color_border) . ';';
        }

        $root_vars .= '--courto-primary-rgb: ' . ( $theme_primary_color ? esc_attr(WGL_Framework::hex_to_rgb($theme_primary_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-secondary-rgb: ' . ( $theme_secondary_color ? esc_attr(WGL_Framework::hex_to_rgb($theme_secondary_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-tertiary-rgb: ' . ( $theme_tertiary_color ? esc_attr(WGL_Framework::hex_to_rgb($theme_tertiary_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-quaternary-rgb: ' . ( $theme_quaternary_color ? esc_attr(WGL_Framework::hex_to_rgb($theme_quaternary_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-content-rgb: ' . ( $main_font_color ? esc_attr(WGL_Framework::hex_to_rgb($main_font_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-header-rgb: ' . ( $h_font_color ? esc_attr(WGL_Framework::hex_to_rgb($h_font_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-form-bg-rgb: ' . ( $form_bg_color ? esc_attr(WGL_Framework::hex_to_rgb($form_bg_color)) : 'unset' ) . ';';

        $cart_overlay = WGL_Framework::get_option('cart_overlay_color')['rgba'] ?? '';
        if (!empty($cart_overlay)){
            $root_vars .= '--courto-cart-overlay: ' . esc_attr($cart_overlay) . ';';
        }else{
            $root_vars .= '--courto-cart-overlay-visibility: none;';
        }

        $body_bg = WGL_Framework::get_option('body_color_bg');
        $body_bg = !empty($body_bg['background-color']) ? $body_bg['background-color'] : '#fff';

        $root_vars .= '--body-background-color: ' . $body_bg . ';';

        $shop_products_overlay = WGL_Framework::get_option('shop_products_overlay')['rgba'] ?? 'transparent';
        $root_vars .= '--courto-shop-products-overlay: ' . ( !empty($shop_products_overlay) ? esc_attr($shop_products_overlay) : 'transparent' ) . ';';

        $body_lines_color = WGL_Framework::get_mb_option('body_lines_color', 'mb_body_lines_switch', 'on') ?? 'unset';
        $body_lines_color = is_array($body_lines_color) ? $body_lines_color['rgba'] : $body_lines_color;
        $root_vars .= '--courto-body-lines-color: ' . esc_attr($body_lines_color) . ';';
        //* ↑ color variables


        /**
         * Product Filter Columns Width
         */
        for ($i = 1; $i <= 8; $i++) { // Columns 1-8
            ${'col' . $i} = WGL_Framework::get_option('filters_columns_' . $i);
            if (isset(${'col' . $i})){
                $root_vars .= '--courto-filters-columns-'.$i.': ' . (${'col'.$i}['width'] ? esc_attr( ${'col'.$i}['width'] ) : '') . ';';
            }
        }
        //* ↑ product filter columns width

        /**
         * Headings Variables
         */
        $header_font = WGL_Framework::get_option( 'header-font' );
        $root_vars .= '--courto-header-font-family: ' . ( !empty($header_font['font-family']) ? esc_attr($header_font['font-family']) : 'unset' ) . ';';
        $root_vars .= '--courto-header-font-weight: ' . ( !empty($header_font['font-weight']) ? esc_attr($header_font['font-weight']) : 'unset' ) . ';';
        $root_vars .= '--courto-header-font-style: ' . ( !empty($header_font['font-style']) ? esc_attr($header_font['font-style']) : 'normal' ) . ';';
        $root_vars .= '--courto-header-font-color: ' . $h_font_color . ';';
        $root_vars .= '--courto-header-letter-spacing: ' . ( !empty($header_font['letter-spacing']) ? esc_attr(floatval($header_font['letter-spacing'])).'em' : 'normal' ) . ';';

        for ($i = 1; $i <= 6; $i++) { // H1 - H6
            ${'h' . $i} = WGL_Framework::get_option('header-h' . $i);

            $root_vars .= '--courto-h'.$i.'-font-family: ' . (!empty(${'h'.$i}['font-family']) ? esc_attr( ${'h'.$i}['font-family'] ) : 'unset') . ';';
            $root_vars .= '--courto-h'.$i.'-font-size: ' . (!empty(${'h'.$i}['font-size']) ? esc_attr( ${'h'.$i}['font-size'] ) : 'unset') . ';';
            $root_vars .= '--courto-h'.$i.'-line-height: ' . (!empty(${'h'.$i}['line-height']) ? esc_attr( ${'h'.$i}['line-height'] ) : 'unset') . ';';
            $root_vars .= '--courto-h'.$i.'-font-weight: ' . (!empty(${'h'.$i}['font-weight']) ? esc_attr( ${'h'.$i}['font-weight'] ) : 'unset') . ';';
            $root_vars .= '--courto-h'.$i.'-font-style: ' . (!empty(${'h'.$i}['font-style']) ? esc_attr( ${'h'.$i}['font-style'] ) : 'normal') . ';';
            $root_vars .= '--courto-h'.$i.'-text-transform: ' . (!empty(${'h'.$i}['text-transform']) ? esc_attr( ${'h'.$i}['text-transform'] ) : 'unset') . ';';
            $root_vars .= '--courto-h'.$i.'-letter-spacing: ' . (!empty(${'h'.$i}['letter-spacing']) ? esc_attr(floatval( ${'h'.$i}['letter-spacing'] )).'em' : 'normal') . ';';
        }
        //* ↑ headings variables

        /**
         * Content Variables
         */
        $main_font = WGL_Framework::get_option( 'main-font' );
        $content_line_height = !empty($main_font['line-height']) && !empty($main_font['font-size']) ? round(((int)$main_font['line-height'] / (int)$main_font['font-size']), 3) : '';

	    $root_vars .= '--courto-content-font-family: ' . ( !empty($main_font['font-family']) ? esc_attr($main_font['font-family']) : 'unset') . ';';
	    $root_vars .= '--courto-content-font-size: ' . ( !empty($main_font['font-size']) ? esc_attr($main_font['font-size']) : 'unset') . ';';
	    $root_vars .= '--courto-content-line-height: ' . esc_attr($content_line_height) . ';';
	    $root_vars .= '--courto-content-font-weight: ' . ( !empty($main_font['font-weight']) ? esc_attr($main_font['font-weight']) : 'unset') . ';';
        $root_vars .= '--courto-content-font-style: ' . ( !empty($main_font['font-style']) ? esc_attr($main_font['font-style']) : 'normal') . ';';
	    $root_vars .= '--courto-content-color: ' . ( $main_font_color ? esc_attr($main_font_color) : 'unset') . ';';
        $root_vars .= '--courto-content-letter-spacing: ' . ( !empty($main_font['letter-spacing']) ? esc_attr(floatval($main_font['letter-spacing'])).'em' : 'normal' ) . ';';
        //* ↑ content variables

        /**
         * Additional Variables
         */
        $extra_font = WGL_Framework::get_option('additional-font');
        $root_vars .= '--courto-additional-font-family: ' . ( !empty($extra_font['font-family']) ? esc_attr($extra_font['font-family']) : 'unset') . ';';
        $root_vars .= '--courto-additional-font-weight: ' . ( !empty($extra_font['font-weight']) ? esc_attr($extra_font['font-weight']) : 'unset') . ';';
        $root_vars .= '--courto-additional-font-style: ' . ( !empty($extra_font['font-style']) ? esc_attr($extra_font['font-style']) : 'normal') . ';';
        $root_vars .= '--courto-additional-letter-spacing: ' . ( !empty($extra_font['letter-spacing']) ? esc_attr(floatval($extra_font['letter-spacing'])).'em' : 'normal' ) . ';';
        //* ↑ additional variables

        /**
         * Button Variables
         */
        $button_font = WGL_Framework::get_option('button-font');
        $root_vars .= '--courto-button-font-family: ' . ( !empty($button_font['font-family']) ? esc_attr($button_font['font-family']) : 'unset') . ';';
        $root_vars .= '--courto-button-font-size: ' . ( !empty($button_font['font-size']) ? esc_attr($button_font['font-size']) : 'unset') . ';';
        $root_vars .= '--courto-button-font-weight: ' . ( !empty($button_font['font-weight']) ? esc_attr($button_font['font-weight']) : 'unset') . ';';
        $root_vars .= '--courto-button-font-style: ' . ( !empty($button_font['font-style']) ? esc_attr($button_font['font-style']) : 'normal') . ';';
        $root_vars .= '--courto-button-line-height: ' . ( !empty($button_font['line-height']) ? esc_attr($button_font['line-height']) : 'unset') . ';';
        $root_vars .= '--courto-button-letter-spacing: ' . ( !empty($button_font['letter-spacing']) ? esc_attr(floatval($button_font['letter-spacing'])).'em' : 'normal' ) . ';';

        $button_font_m = WGL_Framework::get_option('button-font-mobile');
        if(!empty($button_font_m['font-size'])) $root_vars .= '--courto-btn-fs-m: ' . esc_attr($button_font_m['font-size']) . ';';
        if(!empty($button_font_m['line-height'])) $root_vars .= '--courto-btn-lh-m: ' . esc_attr($button_font_m['line-height']) . ';';
        if(!empty($button_font_m['letter-spacing'])) $root_vars .= '--courto-btn-ls-m: ' . esc_attr($button_font_m['letter-spacing']) . ';';


        $btn_p = WGL_Framework::get_option('button-padding');
        $btn_p  = [
            $btn_p['padding-top'] ?? '0',
            $btn_p['padding-right'] ?? '0',
            $btn_p['padding-bottom'] ?? '0',
            $btn_p['padding-left'] ?? '0',
        ];
        if (array_sum(array_map('intval', $btn_p)) > 0) {
            $root_vars .= '--courto-button-padding:' . implode(' ', $btn_p) . ';';
        }

        $btn_p_m = WGL_Framework::get_option('button-padding-mobile');
        $btn_p_m  = [
            $btn_p_m['padding-top'] ?? '0',
            $btn_p_m['padding-right'] ?? '0',
            $btn_p_m['padding-bottom'] ?? '0',
            $btn_p_m['padding-left'] ?? '0',
        ];
        if (array_sum(array_map('intval', $btn_p_m)) > 0) {
            $root_vars .= '--courto-btn-p-m:' . implode(' ', $btn_p_m) . ';';
        }

        $root_vars .= '--courto-button-border-width:' . (int) WGL_Framework::get_option('button-border-width') . 'px;';
        $root_vars .= '--courto-button-border-radius:' . (int) WGL_Framework::get_option('button-radius') . 'px;';


        //* ↑ button variables

        /**
         * Menu Variables
         */
        $menu_font = WGL_Framework::get_option( 'menu-font' );
        $root_vars .= '--courto-menu-font-family: ' . ( !empty($menu_font['font-family']) ? esc_attr($menu_font['font-family']) : 'unset') . ';';
        $root_vars .= '--courto-menu-font-size: ' . ( !empty($menu_font['font-size']) ? esc_attr($menu_font['font-size']) : 'unset') . ';';
        $root_vars .= '--courto-menu-line-height: ' . ( !empty($menu_font['line-height']) ? esc_attr($menu_font['line-height']) : 'unset') . ';';
        $root_vars .= '--courto-menu-font-weight: ' . ( !empty($menu_font['font-weight']) ? esc_attr($menu_font['font-weight']) : 'unset') . ';';
        $root_vars .= '--courto-menu-font-style: ' . ( !empty($menu_font['font-style']) ? esc_attr($menu_font['font-style']) : 'normal') . ';';
        $root_vars .= '--courto-menu-letter-spacing: ' . ( !empty($menu_font['letter-spacing']) ? esc_attr(floatval($menu_font['letter-spacing'])) . 'em' : 'normal') . ';';
        //* ↑ menu variables

        /**
         * Submenu Variables
         */
        $sub_menu_font = WGL_Framework::get_option('sub-menu-font');
        $root_vars .= '--courto-submenu-font-family: ' . ( !empty($sub_menu_font['font-family']) ? esc_attr($sub_menu_font['font-family']) : 'unset') . ';';
        $root_vars .= '--courto-submenu-font-size: ' . ( !empty($sub_menu_font['font-size']) ? esc_attr($sub_menu_font['font-size']) : 'unset') . ';';
        $root_vars .= '--courto-submenu-line-height: ' . ( !empty($sub_menu_font['line-height']) ? esc_attr($sub_menu_font['line-height']) : 'unset') . ';';
        $root_vars .= '--courto-submenu-font-weight: ' . ( !empty($sub_menu_font['font-weight']) ? esc_attr($sub_menu_font['font-weight']) : 'normal') . ';';
        $root_vars .= '--courto-submenu-font-style: ' . ( !empty($sub_menu_font['font-style']) ? esc_attr($sub_menu_font['font-style']) : 'normal') . ';';
        $root_vars .= '--courto-submenu-letter-spacing: ' . ( !empty($sub_menu_font['letter-spacing']) ? esc_attr(floatval($sub_menu_font['letter-spacing'])) . 'em' : 'normal') . ';';

        $sub_menu_color = WGL_Framework::get_option('sub_menu_color')['color'] ?? 'unset';
        $sub_menu_bg = WGL_Framework::get_option('sub_menu_background')['rgba'] ?? 'unset';
        $root_vars .= '--courto-submenu-color: ' . ( $sub_menu_color ? esc_attr($sub_menu_color) : 'unset' ) . ';';
        $root_vars .= '--courto-submenu-color-rgb: ' . ( $sub_menu_color ? esc_attr(WGL_Framework::hex_to_rgb($sub_menu_color)) : 'unset' ) . ';';
        $root_vars .= '--courto-submenu-background: ' . ( $sub_menu_bg ? esc_attr($sub_menu_bg) : 'unset' ) . ';';

        $mob_sub_menu_color = WGL_Framework::get_option('mobile_sub_menu_color') ?? 'unset';
        $mob_sub_menu_color_active = WGL_Framework::get_option('mobile_sub_menu_color_active') ?? 'unset';
        $mob_sub_menu_bg = WGL_Framework::get_option('mobile_sub_menu_background')['rgba'] ?? 'unset';
        $mob_sub_menu_overlay = WGL_Framework::get_option('mobile_sub_menu_overlay')['rgba'] ?? 'unset';
        $root_vars .= '--courto-submenu-mobile-color: ' . esc_attr($mob_sub_menu_color) . ';';
        $root_vars .= '--courto-submenu-mobile-color-active: ' . esc_attr($mob_sub_menu_color_active) . ';';
        $root_vars .= '--courto-submenu-mobile-background: ' . esc_attr($mob_sub_menu_bg) . ';';
        $root_vars .= '--courto-submenu-mobile-overlay: ' . esc_attr($mob_sub_menu_overlay) . ';';

        $sub_menu_border = WGL_Framework::get_option('header_sub_menu_bottom_border');
        if ($sub_menu_border) {
            $sub_menu_border_height = WGL_Framework::get_option('header_sub_menu_border_height')['height'] ?? '0';
            $sub_menu_border_color = WGL_Framework::get_option('header_sub_menu_bottom_border_color')['rgba'] ?? 'unset';

            $extra_css .= '.primary-nav ul li ul li:not(:last-child),'
                . '.sitepress_container > .wpml-ls ul ul li:not(:last-child) {'
                    . ($sub_menu_border_height ? 'border-bottom-width: ' . esc_attr($sub_menu_border_height) . 'px;' : '')
                    . ($sub_menu_border_color ? 'border-bottom-color: ' . esc_attr($sub_menu_border_color) . ';' : '')
                    . 'border-bottom-style: solid;'
                . '}';
        }
        //* ↑ submenu variables

        /**
         * Header Mobile
         */
        $header_mobile_height = ((bool)WGL_Framework::get_option('mobile_header') && WGL_Framework::get_option('header_mobile_height')['height']) ? WGL_Framework::get_option('header_mobile_height')['height'] : '60px';

        $root_vars .= '--courto-header-mobile-height: ' . esc_attr($header_mobile_height) . ';';
        //* ↑ Header Mobile

        /**
         * Footer Variables
         */
        if (
            WGL_Framework::get_option('footer_switch')
            && 'widgets' === WGL_Framework::get_option('footer_building_tool')
        ) {
	        $footer_text_color = WGL_Framework::get_option('footer_text_color') ?? 'unset';
	        $footer_heading_color = WGL_Framework::get_option('footer_heading_color') ?? 'unset';
	        $copyright_text_color = WGL_Framework::get_mb_option('copyright_text_color', 'mb_copyright_switch', 'on') ?? 'unset';
            $root_vars .= '--courto-footer-content-color: ' . esc_attr($footer_text_color) . ';';
            $root_vars .= '--courto-footer-heading-color: ' . esc_attr($footer_heading_color) . ';';
            $root_vars .= '--courto-copyright-content-color: ' . esc_attr($copyright_text_color) . ';';
        }
        //* ↑ footer variables

        /**
         * Side Panel Variables
         */
        $sidepanel_title_color = WGL_Framework::get_mb_option('side_panel_title_color', 'mb_customize_side_panel', 'custom') ?? 'unset';
        $root_vars .= '--courto-sidepanel-title-color: ' . esc_attr($sidepanel_title_color) . ';';
        //* ↑ side panel variables

        /**
         * Encoded SVG variables
         */
        $svg_h_font_color = $h_font_color ? esc_attr($h_font_color) : '#000';
        $svg_tertiary_color = $theme_tertiary_color ? esc_attr($theme_tertiary_color) : '#fff';
        $root_vars .= '--courto-bg-caret-h: url(\'data:image/svg+xml; utf8, '.$this->bg_caret($svg_h_font_color).'\');';
        $root_vars .= '--courto-bg-caret-w: url(\'data:image/svg+xml; utf8, '.$this->bg_caret($svg_tertiary_color ? esc_attr($svg_tertiary_color) : '#fff').'\');';
        $root_vars .= '--courto-button-loading: url(\'data:image/svg+xml; utf8, '.$this->wgl_button_loading($svg_tertiary_color ? esc_attr($svg_tertiary_color) : '#fff').'\');';
        $root_vars .= '--courto-button-success: url(\'data:image/svg+xml; utf8, '.$this->wgl_button_success($svg_tertiary_color ? esc_attr($svg_tertiary_color) : '#fff').'\');';
        $root_vars .= '--courto-notice-info: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_tertiary_color, 1, true)['notice-info'].'\');';
        $root_vars .= '--courto-notice-warning: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_tertiary_color, 1, true)['notice-warning'].'\');';
        $root_vars .= '--courto-notice-success: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_tertiary_color, 1, true)['notice-success'].'\');';
        $root_vars .= '--courto-notice-error: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_tertiary_color, 1, true)['notice-error'].'\');';
        $root_vars .= '--courto-search: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['search'].'\');';
        $root_vars .= '--courto-search-shop: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['search-shop'].'\');';
        $root_vars .= '--courto-arrow-right: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['arrow-right'].'\');';
        $root_vars .= '--courto-link-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['link'].'\');';
        $root_vars .= '--courto-quote-blog-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['quote-blog'].'\');';
        $root_vars .= '--courto-quote-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['quote'].'\');';
        $root_vars .= '--courto-search-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['search'].'\');';
        $root_vars .= '--courto-check-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['check'].'\');';
        $root_vars .= '--courto-check-circle-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['check-circle'].'\');';
        $root_vars .= '--courto-share-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['share'].'\');';
        $root_vars .= '--courto-close-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['close'].'\');';
        $root_vars .= '--courto-trash: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['trash'].'\');';
        $root_vars .= '--courto-cart-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)[$this->extracted()].'\');';
        $root_vars .= '--courto-cart-bag: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['cart-bag'].'\');';
        $root_vars .= '--courto-cart-basket: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['cart-basket'].'\');';
        $root_vars .= '--courto-heart-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['heart'].'\');';
        $root_vars .= '--courto-compare-h: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['compare'].'\');';
        $root_vars .= '--courto-caret-down-fill: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['caret-down-fill'].'\');';
        $root_vars .= '--courto-arrow-drop-down: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['arrow-drop-down'].'\');';
        $root_vars .= '--courto-heart-pulse: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['heart-pulse'].'\');';
        $root_vars .= '--courto-like: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['like'].'\');';
        $root_vars .= '--courto-liked: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['liked'].'\');';
        $root_vars .= '--courto-like-curved: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg('#ffffff', 1, true)['like-curved'].'\');';
        $root_vars .= '--courto-chevron-down: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['chevron-down'].'\');';
        $root_vars .= '--courto-plus: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg('#ffffff', 1, true)['plus'].'\');';
        $root_vars .= '--courto-dots: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($theme_primary_color, 1, true)['dots'].'\');';
        $root_vars .= '--courto-level: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['level'].'\');';
        $root_vars .= '--courto-alarm: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['alarm'].'\');';
        $root_vars .= '--courto-students: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['students'].'\');';
        $root_vars .= '--courto-document: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['document'].'\');';
        $root_vars .= '--courto-star: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_h_font_color, 1, true)['star'].'\');';
        $root_vars .= '--courto-comments: url(\'data:image/svg+xml; utf8, '.$this->wgl_theme_svg($svg_tertiary_color ? esc_attr($svg_tertiary_color) : '#fff', 1, true)['comments'].'\');';
        //* ↑ encoded SVG variables
	    /**
	     * Title variables
	     */
	    $root_vars .= '--wgl_price_label: "' . esc_html__( 'Price:', 'courto' ) . '";';
	    $root_vars .= '--wgl_cart_change_address: "' . esc_html__( 'Change address', 'courto' ) . '";';

	    //* ↑ encoded Title variables

        /**
         * Cart variables
         */

        $cart_offset = WGL_Framework::get_option('cart_offset');
        $root_vars .= !empty($cart_offset['top']) ? '--wgl-positioning-cart-top:' . (int) $cart_offset['top'] . ';' : '';
        $root_vars .= !empty($cart_offset['right']) ? '--wgl-positioning-cart-right:' . (int) $cart_offset['right'] . ';' : '';

        $cart_offset_m = WGL_Framework::get_option('cart_offset_m');
        $root_vars .= !empty($cart_offset_m['top']) ? '--wgl-m-positioning-cart-top:' . (int) $cart_offset_m['top'] . ';' : '';
        $root_vars .= !empty($cart_offset_m['right']) ? '--wgl-m-positioning-cart-right:' . (int) $cart_offset_m['right'] . ';' : '';

        //* ↑ encoded Cart variables

        /**
         * Cursor Variables
         */

        $cursor_color_follower = !WGL_Framework::get_option('cursor_switch') ? 'transparent' : (WGL_Framework::get_option('cursor_follower_color')['rgba'] ?? '');
        $cursor_size = isset(WGL_Framework::get_option('cursor_size')['height']) ? (int) WGL_Framework::get_option('cursor_size')['height'] : '';
        $cursor_follower_size = isset(WGL_Framework::get_option('cursor_follower_size')['height']) ? (int) WGL_Framework::get_option('cursor_follower_size')['height'] : '';
        $blend_mode = WGL_Framework::get_option('cursor_blend_mode') ? WGL_Framework::get_option('cursor_blend_mode') : 'normal';
        $cursor_duration = WGL_Framework::get_option('cursor_duration') ? WGL_Framework::get_option('cursor_duration') : 0.35;
        $cursor_follower_duration = WGL_Framework::get_option('cursor_follower_duration') ? WGL_Framework::get_option('cursor_follower_duration') : 0.9;
        $cursor_animation = 'link-animation-style-' . (WGL_Framework::get_option('cursor_apply_animation') ? WGL_Framework::get_option('cursor_apply_animation') : 'none');

        $root_vars .= '--courto-cursor-point-color: ' . ( $cursor_point_color ? esc_attr($cursor_point_color) : 'unset' ) . ';';
        $root_vars .= '--courto-cursor-follower-color: ' .( $cursor_color_follower ? esc_attr($cursor_color_follower) : 'transparent' ) . ';';
        $root_vars .= '--courto-cursor-point-size: ' . esc_attr($cursor_size) . 'px;';
        $root_vars .= '--courto-cursor-follower-size: ' . esc_attr($cursor_follower_size) . 'px;';
        $root_vars .= '--courto-cursor-duration: ' .(float) esc_attr($cursor_duration) . ';';
        $root_vars .= '--courto-cursor-follower-duration: ' .(float) esc_attr($cursor_follower_duration) . ';';
        $root_vars .= '--courto-cursor-blend: ' . esc_attr($blend_mode) . ';';
        $root_vars .= '--courto-cursor-link-animation: ' . esc_attr($cursor_animation) . ';';

        //* ↑ Cursor variables

        /**
         * Adding font-variant-ligatures for "Rethink Sans". Disable font ligatures (e.g. prevent (1) from turning into ①)
         */
        if ( apply_filters( 'wgl/font_variant_ligatures', true ) ) {
            $font_keys = [
                'buttons_options', 'main-font', 'header-font', 'additional-font',
                'menu-font', 'sub-menu-font', 'header-h1', 'header-h2',
                'header-h3', 'header-h4', 'header-h5', 'header-h6',
            ];
            foreach ( $font_keys as $key ) {
                $font = WGL_Framework::get_option( $key );
                if ( is_array($font) && isset($font['font-family']) && strtolower($font['font-family']) === 'rethink sans' ) {
                    $extra_css .= 'body { font-variant-ligatures: none; }';
                    break;
                }
            }
        }
        //* ↑ Adding font-variant-ligatures for "Rethink Sans"


        /**
         * Elementor Container
         */
        $root_vars .= '--courto-elementor-container-width: ' . $this->get_elementor_container_width() . 'px;';
        //* ↑ elementor container


        /**
         * Dark Theme Check
         */
        $body_bg_color = ltrim($body_bg ?? '#fff', '#');
        if (strlen($body_bg_color) === 3) $body_bg_color = preg_replace('/(.)/','$1$1',$body_bg_color);
        [$r,$g,$b] = [hexdec(substr($body_bg_color,0,2)),hexdec(substr($body_bg_color,2,2)),hexdec(substr($body_bg_color,4,2))];
        $root_vars .= (($r*299 + $g*587 + $b*114)/1000) < 128 ? 'color-scheme: dark;' : '';
        //* ↑ dark theme check


        $css_variables = ':root {' . $root_vars . '}';

        $extra_css .= $this->get_mobile_header_extra_css();
        $extra_css .= $this->get_page_title_responsive_extra_css();
        $extra_css .= $this->get_breakpoints_extra_css();
        if (
            class_exists('\Elementor\Plugin')
            && version_compare(ELEMENTOR_VERSION, '3.4', '>')
        ) {
            $extra_css .= $this->init_additional_breakpoints();
        }
        return $css_variables . $this->minify_css($extra_css);
    }

    public function bg_caret($fill = '#000', $opacity = '1'){
        $output = '<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: '.esc_attr($opacity).';"><path d="M1 1L5 5L9 1" stroke="'.esc_attr($fill).'" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        return rawurlencode( $output );
    }

    public function wgl_button_loading($fill = '#fff', $opacity = '1'){
        $output = '<svg xmlns="http://www.w3.org/2000/svg" width="489.698px" height="489.698px" viewBox="0 0 489.698 489.698" preserveAspectRatio="none" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M468.999,227.774c-11.4,0-20.8,8.3-20.8,19.8c-1,74.9-44.2,142.6-110.3,178.9c-99.6,54.7-216,5.6-260.6-61l62.9,13.1    c10.4,2.1,21.8-4.2,23.9-15.6c2.1-10.4-4.2-21.8-15.6-23.9l-123.7-26c-7.2-1.7-26.1,3.5-23.9,22.9l15.6,124.8    c1,10.4,9.4,17.7,19.8,17.7c15.5,0,21.8-11.4,20.8-22.9l-7.3-60.9c101.1,121.3,229.4,104.4,306.8,69.3    c80.1-42.7,131.1-124.8,132.1-215.4C488.799,237.174,480.399,227.774,468.999,227.774z"/><path d="M20.599,261.874c11.4,0,20.8-8.3,20.8-19.8c1-74.9,44.2-142.6,110.3-178.9c99.6-54.7,216-5.6,260.6,61l-62.9-13.1    c-10.4-2.1-21.8,4.2-23.9,15.6c-2.1,10.4,4.2,21.8,15.6,23.9l123.8,26c7.2,1.7,26.1-3.5,23.9-22.9l-15.6-124.8    c-1-10.4-9.4-17.7-19.8-17.7c-15.5,0-21.8,11.4-20.8,22.9l7.2,60.9c-101.1-121.2-229.4-104.4-306.8-69.2    c-80.1,42.6-131.1,124.8-132.2,215.3C0.799,252.574,9.199,261.874,20.599,261.874z"/></svg>';
        return rawurlencode( $output );
    }

    public function wgl_button_success($fill = '#fff', $opacity = '1'){
        $output = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" preserveAspectRatio="none" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0 7.44669L4.79421 12.2501L14 3.05337L12.6784 1.75L4.79421 9.62499L1.30334 6.13414L0 7.44669Z"/></svg>';
        return rawurlencode( $output );
    }

    /**
     * @param string $fill
     * @param string $opacity
     * @param boolean $clear
     * @return array
     */
    public function wgl_theme_svg($fill = 'currentColor', $opacity = '1', $clear = false){
        $output['notice-info'] = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';

        $output['notice-warning'] = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';

        $output['notice-success'] = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';

        $output['notice-error'] = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';

        $output['like'] = '<svg width="1em" height="1em" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>';

        $output['liked'] = '<svg width="1em" height="1em" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';

        $output['link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path><line x1="8" y1="12" x2="16" y2="12"></line></svg>';

        $output['chevron-down'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><polyline points="6 9 12 15 18 9"></polyline></svg>';

        $output['quote-blog'] = '<svg width="1em" height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388q0-.527.062-1.054.093-.558.31-.992t.559-.683q.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 9 7.558V11a1 1 0 0 0 1 1zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612q0-.527.062-1.054.094-.558.31-.992.217-.434.559-.683.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 3 7.558V11a1 1 0 0 0 1 1z"/></svg>';

        $output['heart-pulse'] = '<svg width="1em" height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z"/><path d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z"/></svg>';

        $output['quote'] = '<svg width="1em" height="1em" viewBox="0 0 26 20" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0 0V19.5L9.75 9.75V0H0Z"/><path d="M16.25 0V19.5L26 9.75V0H16.25Z"/></svg>';

        $output['check'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/></svg>';

        $output['check-circle'] = '<svg width="1em" height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>';

        $output['close'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';

        $output['trash'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/></svg>';

        $output['cart'] = '<svg width="1em" height="1em" viewBox="0 0 21 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 20C5.45 20 4.97917 19.8042 4.5875 19.4125C4.19583 19.0208 4 18.55 4 18C4 17.45 4.19583 16.9792 4.5875 16.5875C4.97917 16.1958 5.45 16 6 16C6.55 16 7.02083 16.1958 7.4125 16.5875C7.80417 16.9792 8 17.45 8 18C8 18.55 7.80417 19.0208 7.4125 19.4125C7.02083 19.8042 6.55 20 6 20ZM16 20C15.45 20 14.9792 19.8042 14.5875 19.4125C14.1958 19.0208 14 18.55 14 18C14 17.45 14.1958 16.9792 14.5875 16.5875C14.9792 16.1958 15.45 16 16 16C16.55 16 17.0208 16.1958 17.4125 16.5875C17.8042 16.9792 18 17.45 18 18C18 18.55 17.8042 19.0208 17.4125 19.4125C17.0208 19.8042 16.55 20 16 20ZM5.15 4L7.55 9H14.55L17.3 4H5.15ZM4.2 2H20.7L15.725 11H7.1L6 13H18V15H2.625L5.6 9.6L2 2H0V0H3.25L4.2 2Z"/></svg>';

        $output['cart-bag'] = ' <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M3.75 17.9167V5.41667H6.875V5.20834C6.875 4.34514 7.18 3.60847 7.79 2.99834C8.40014 2.38834 9.13681 2.08334 10 2.08334C10.8632 2.08334 11.5999 2.38834 12.21 2.99834C12.82 3.60847 13.125 4.34514 13.125 5.20834V5.41667H16.25V17.9167H3.75ZM5 16.6667H15V6.66667H13.125V9.16667H11.875V6.66667H8.125V9.16667H6.875V6.66667H5V16.6667ZM8.125 5.41667H11.875V5.20834C11.875 4.68597 11.6931 4.24285 11.3294 3.87896C10.9656 3.51521 10.5225 3.33334 10 3.33334C9.4775 3.33334 9.03438 3.51521 8.67063 3.87896C8.30688 4.24285 8.125 4.68597 8.125 5.20834V5.41667Z"/></svg>';

        $output['cart-basket'] = '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h660L669-440H324l-44 80h480v80H145l119-216-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>';

        $output['share'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>';

        $output['search'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';

        $output['search-shop'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 25.2 26" fill="none" stroke="'.esc_attr($fill).'" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';

        $output['heart'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="'.esc_attr($fill).'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: '.esc_attr($opacity).';"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';

        $output['arrow-right'] = '<svg width="1em" height="1em" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0 6.78247C0 6.52555 0.102065 6.27914 0.283741 6.09746C0.465416 5.91579 0.711822 5.81372 0.96875 5.81372H12.1927L8.03288 1.65585C7.85097 1.47394 7.74878 1.22723 7.74878 0.969974C7.74878 0.712721 7.85097 0.466004 8.03288 0.284099C8.21478 0.102194 8.4615 0 8.71875 0C8.976 0 9.22272 0.102194 9.40463 0.284099L15.2171 6.0966C15.3073 6.18659 15.3789 6.29349 15.4278 6.41118C15.4766 6.52888 15.5017 6.65505 15.5017 6.78247C15.5017 6.9099 15.4766 7.03607 15.4278 7.15376C15.3789 7.27146 15.3073 7.37836 15.2171 7.46835L9.40463 13.2808C9.22272 13.4628 8.976 13.5649 8.71875 13.5649C8.4615 13.5649 8.21478 13.4628 8.03288 13.2808C7.85097 13.0989 7.74878 12.8522 7.74878 12.595C7.74878 12.3377 7.85097 12.091 8.03288 11.9091L12.1927 7.75122H0.96875C0.711822 7.75122 0.465416 7.64916 0.283741 7.46748C0.102065 7.28581 0 7.0394 0 6.78247Z"/></svg>';

        $output['caret-down-fill'] = '<svg width="1em" height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>';

        $output['arrow-drop-down'] = '<svg width="24" height="24" viewBox="0 -960 960 960" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M480-360 280-560h400L480-360Z"/></svg>';

        $output['compare'] = '<svg width="1em" height="1em" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M0.83 6.67C0.83 6.45 0.92 6.23 1.08 6.08C1.23 5.92 1.45 5.83 1.67 5.83H7.99L6.08 3.92C5.99 3.85 5.93 3.75 5.89 3.65C5.85 3.55 5.82 3.44 5.82 3.33C5.82 3.22 5.84 3.11 5.89 3.01C5.93 2.91 5.99 2.81 6.07 2.73C6.15 2.66 6.24 2.59 6.34 2.55C6.44 2.51 6.55 2.49 6.66 2.49C6.77 2.49 6.88 2.51 6.99 2.56C7.09 2.6 7.18 2.66 7.26 2.74L10.59 6.08C10.75 6.23 10.83 6.45 10.83 6.67C10.83 6.89 10.75 7.1 10.59 7.26L7.26 10.59C7.18 10.67 7.09 10.73 6.99 10.78C6.88 10.82 6.77 10.84 6.66 10.84C6.55 10.84 6.44 10.82 6.34 10.78C6.24 10.74 6.15 10.68 6.07 10.6C5.99 10.52 5.93 10.43 5.89 10.33C5.84 10.22 5.82 10.11 5.82 10C5.82 9.89 5.85 9.78 5.89 9.68C5.93 9.58 5.99 9.49 6.08 9.41L7.99 7.5H1.67C1.45 7.5 1.23 7.41 1.08 7.26C0.92 7.1 0.83 6.89 0.83 6.67ZM18.33 12.5H12.01L13.92 10.59C14.07 10.43 14.16 10.22 14.16 10C14.15 9.78 14.07 9.58 13.91 9.42C13.76 9.27 13.55 9.18 13.33 9.18C13.11 9.18 12.9 9.26 12.74 9.41L9.41 12.74C9.25 12.9 9.17 13.11 9.17 13.33C9.17 13.55 9.25 13.77 9.41 13.92L12.74 17.26C12.9 17.41 13.11 17.49 13.33 17.49C13.55 17.49 13.76 17.4 13.91 17.25C14.07 17.09 14.15 16.88 14.16 16.66C14.16 16.45 14.07 16.23 13.92 16.08L12.01 14.17H18.33C18.55 14.17 18.77 14.08 18.92 13.92C19.08 13.77 19.17 13.55 19.17 13.33C19.17 13.11 19.08 12.9 18.92 12.74C18.77 12.59 18.55 12.5 18.33 12.5Z"/></svg>';

        $output['user'] = '<svg width="1em" height="1em" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M288.38 275C288.38 281.9 282.79 287.5 275.88 287.5S263.38 281.9 263.38 275C263.38 230.89 237.99 191.84 198.4 173.37C184.7 182.27 168.41 187.5 150.89 187.5C133.31 187.5 116.95 182.24 103.22 173.27C91.4 178.85 80.45 186.36 71.34 195.47C50.09 216.72 38.38 244.97 38.38 275C38.38 281.9 32.79 287.5 25.88 287.5S13.38 281.9 13.38 275C13.38 238.29 27.69 203.77 53.66 177.79C62.35 169.09 72.46 161.64 83.29 155.49C70.87 140.38 63.39 121.05 63.39 100C63.39 51.75 102.65 12.5 150.89 12.5S238.39 51.75 238.39 100C238.39 121 230.95 140.28 218.57 155.37C261.48 179.53 288.38 224.67 288.38 275zM150.89 37.5C116.43 37.5 88.39 65.54 88.39 100S116.43 162.5 150.89 162.5S213.39 134.46 213.39 100S185.35 37.5 150.89 37.5z"/></svg>';

        $output['like-curved'] = '<svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: '.esc_attr($opacity).';"><path fill="'.esc_attr($fill).'" d="M8.29681 20C6.80061 17.5027 4.82009 15.3415 3.1464 12.9621C1.47271 10.5826 0.073194 7.85936 0.00247995 4.94093C-0.0300203 3.60113 0.252465 2.19324 1.11365 1.17399C1.97483 0.154742 3.5117 -0.347722 4.68898 0.269392C5.49599 0.692448 5.99789 1.53204 6.38441 2.3636C7.18208 4.07967 7.66698 5.94361 7.80767 7.83409C8.96989 5.62051 9.92879 1.58895 13.2621 2.37005C14.4639 2.65169 15.4535 3.69666 15.8108 4.87622C16.5989 7.47927 14.7554 10.0448 13.3956 12.0686C11.658 14.6549 9.69974 17.2023 8.29681 20Z"/></svg>';

        $output['plus'] = '<svg width="1em" height="1em" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="'.esc_attr($fill).'"  style="opacity: '.esc_attr($opacity).';" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="2" x2="9" y2="16"></line><line x1="2" y1="9" x2="16" y2="9"></line></svg>';

        $output['dots'] = '<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4" fill="'.esc_attr($fill).'"/></svg>';

        $output['level'] = '<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.9375 1.24989C12.7159 1.24989 12.4965 1.29354 12.2917 1.37834C12.087 1.46315 11.901 1.58745 11.7443 1.74415C11.5876 1.90085 11.4633 2.08687 11.3785 2.29161C11.2936 2.49635 11.25 2.71578 11.25 2.93739C11.25 3.159 11.2936 3.37843 11.3785 3.58317C11.4633 3.7879 11.5876 3.97393 11.7443 4.13063C11.901 4.28733 12.087 4.41163 12.2917 4.49644C12.4965 4.58124 12.7159 4.62489 12.9375 4.62489C13.3851 4.62489 13.8143 4.4471 14.1307 4.13063C14.4472 3.81416 14.625 3.38494 14.625 2.93739C14.625 2.48984 14.4472 2.06061 14.1307 1.74415C13.8143 1.42768 13.3851 1.24989 12.9375 1.24989ZM10.1813 2.37489C10.3104 1.7391 10.6553 1.1675 11.1576 0.756927C11.6599 0.346357 12.2887 0.12207 12.9375 0.12207C13.5863 0.12207 14.2151 0.346357 14.7174 0.756927C15.2197 1.1675 15.5646 1.7391 15.6937 2.37489H18V3.49989H15.6937C15.5646 4.13568 15.2197 4.70728 14.7174 5.11785C14.2151 5.52842 13.5863 5.75271 12.9375 5.75271C12.2887 5.75271 11.6599 5.52842 11.1576 5.11785C10.6553 4.70728 10.3104 4.13568 10.1813 3.49989H0V2.37489H10.1813ZM5.0625 6.87489C4.61495 6.87489 4.18573 7.05268 3.86926 7.36915C3.55279 7.68561 3.375 8.11484 3.375 8.56239C3.375 9.00994 3.55279 9.43916 3.86926 9.75563C4.18573 10.0721 4.61495 10.2499 5.0625 10.2499C5.51005 10.2499 5.93928 10.0721 6.25574 9.75563C6.57221 9.43916 6.75 9.00994 6.75 8.56239C6.75 8.11484 6.57221 7.68561 6.25574 7.36915C5.93928 7.05268 5.51005 6.87489 5.0625 6.87489ZM2.30625 7.99989C2.43535 7.3641 2.78029 6.7925 3.2826 6.38193C3.78492 5.97136 4.41374 5.74707 5.0625 5.74707C5.71126 5.74707 6.34008 5.97136 6.8424 6.38193C7.34471 6.7925 7.68965 7.3641 7.81875 7.99989H18V9.12489H7.81875C7.68965 9.76068 7.34471 10.3323 6.8424 10.7429C6.34008 11.1534 5.71126 11.3777 5.0625 11.3777C4.41374 11.3777 3.78492 11.1534 3.2826 10.7429C2.78029 10.3323 2.43535 9.76068 2.30625 9.12489H0V7.99989H2.30625ZM12.9375 12.4999C12.4899 12.4999 12.0607 12.6777 11.7443 12.9941C11.4278 13.3106 11.25 13.7398 11.25 14.1874C11.25 14.6349 11.4278 15.0642 11.7443 15.3806C12.0607 15.6971 12.4899 15.8749 12.9375 15.8749C13.3851 15.8749 13.8143 15.6971 14.1307 15.3806C14.4472 15.0642 14.625 14.6349 14.625 14.1874C14.625 13.7398 14.4472 13.3106 14.1307 12.9941C13.8143 12.6777 13.3851 12.4999 12.9375 12.4999ZM10.1813 13.6249C10.3104 12.9891 10.6553 12.4175 11.1576 12.0069C11.6599 11.5964 12.2887 11.3721 12.9375 11.3721C13.5863 11.3721 14.2151 11.5964 14.7174 12.0069C15.2197 12.4175 15.5646 12.9891 15.6937 13.6249H18V14.7499H15.6937C15.5646 15.3857 15.2197 15.9573 14.7174 16.3678C14.2151 16.7784 13.5863 17.0027 12.9375 17.0027C12.2887 17.0027 11.6599 16.7784 11.1576 16.3678C10.6553 15.9573 10.3104 15.3857 10.1813 14.7499H0V13.6249H10.1813Z" fill="'.esc_attr($fill).'"/></svg>';

        $output['alarm'] = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_323_12413)"><path d="M9.5625 6.1875C9.5625 6.03832 9.50323 5.89524 9.39774 5.78975C9.29225 5.68426 9.14918 5.625 9 5.625C8.85081 5.625 8.70774 5.68426 8.60225 5.78975C8.49676 5.89524 8.4375 6.03832 8.4375 6.1875V9.96975L6.82987 12.6472C6.75304 12.7752 6.73021 12.9285 6.76639 13.0734C6.80257 13.2182 6.89481 13.3427 7.02281 13.4196C7.15081 13.4964 7.30409 13.5192 7.44892 13.483C7.59376 13.4469 7.71829 13.3546 7.79512 13.2266L9.48262 10.4141C9.53492 10.3268 9.56253 10.2268 9.5625 10.125V6.1875Z" fill="'.esc_attr($fill).'"/><path d="M7.3125 0C7.16332 0 7.02024 0.0592632 6.91475 0.164752C6.80926 0.270242 6.75 0.413316 6.75 0.5625C6.75 0.711684 6.80926 0.854758 6.91475 0.960248C7.02024 1.06574 7.16332 1.125 7.3125 1.125H7.875V2.32875C6.34306 2.55007 4.91029 3.21788 3.75567 4.24875C2.60106 5.27963 1.77579 6.62786 1.38295 8.12502C0.990118 9.62219 1.04713 11.2019 1.54688 12.6669C2.04663 14.1318 2.96695 15.417 4.19287 16.362L3.51562 17.0392C3.41 17.1447 3.35061 17.2878 3.3505 17.4371C3.35039 17.5864 3.40959 17.7296 3.51506 17.8352C3.62054 17.9408 3.76365 18.0002 3.91291 18.0003C4.06218 18.0004 4.20538 17.9412 4.311 17.8358L5.15025 16.9965C6.32589 17.6564 7.65182 18.002 9 18C10.3482 18.002 11.6741 17.6564 12.8497 16.9965L13.689 17.8358C13.7946 17.9412 13.9378 18.0004 14.0871 18.0003C14.2364 18.0002 14.3795 17.9408 14.4849 17.8352C14.5904 17.7296 14.6496 17.5864 14.6495 17.4371C14.6494 17.2878 14.59 17.1447 14.4844 17.0392L13.8083 16.362C15.0342 15.417 15.9545 14.1317 16.4542 12.6666C16.9539 11.2016 17.0108 9.62179 16.6178 8.12459C16.2249 6.62739 15.3995 5.27918 14.2447 4.24838C13.0899 3.21758 11.657 2.5499 10.125 2.32875V1.125H10.6875C10.8367 1.125 10.9798 1.06574 11.0852 0.960248C11.1907 0.854758 11.25 0.711684 11.25 0.5625C11.25 0.413316 11.1907 0.270242 11.0852 0.164752C10.9798 0.0592632 10.8367 0 10.6875 0L7.3125 0ZM8.48025 3.39525C8.82623 3.36853 9.17377 3.36853 9.51975 3.39525C11.2598 3.52963 12.8801 4.3327 14.0408 5.63597C15.2014 6.93924 15.8122 8.64139 15.745 10.3853C15.6777 12.1292 14.9377 13.7792 13.6801 14.9893C12.4225 16.1993 10.7452 16.8752 9 16.8752C7.25482 16.8752 5.57746 16.1993 4.31988 14.9893C3.0623 13.7792 2.32225 12.1292 2.25501 10.3853C2.18777 8.64139 2.79857 6.93924 3.95924 5.63597C5.11992 4.3327 6.74025 3.52963 8.48025 3.39525ZM2.84948e-09 3.9375C2.84948e-09 4.78463 0.374625 5.54513 0.9675 6.06038C1.83603 4.35055 3.22555 2.96103 4.93538 2.0925C4.55949 1.65997 4.06045 1.35258 3.50506 1.21148C2.94967 1.07037 2.36442 1.10228 1.82766 1.30294C1.29091 1.50359 0.828249 1.86342 0.501619 2.33426C0.174989 2.80509 -2.57854e-05 3.36446 2.84948e-09 3.9375ZM15.1875 1.125C14.3404 1.125 13.5799 1.49962 13.0646 2.0925C14.7745 2.96103 16.164 4.35055 17.0325 6.06038C17.465 5.68449 17.7724 5.18545 17.9135 4.63006C18.0546 4.07466 18.0227 3.48942 17.8221 2.95266C17.6214 2.41591 17.2616 1.95325 16.7907 1.62662C16.3199 1.29999 15.7605 1.12497 15.1875 1.125Z" fill="'.esc_attr($fill).'"/></g><defs><clipPath id="clip0_323_12413"><rect width="18" height="18" fill="white"/></clipPath></defs></svg>';

        $output['students'] = '<svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.875 13.75C16.875 13.75 18 13.75 18 12.625C18 11.5 16.875 8.125 12.375 8.125C7.875 8.125 6.75 11.5 6.75 12.625C6.75 13.75 7.875 13.75 7.875 13.75H16.875ZM7.89975 12.625L7.875 12.6205C7.87612 12.3235 8.06287 11.4618 8.73 10.6855C9.351 9.95763 10.4423 9.25 12.375 9.25C14.3066 9.25 15.3979 9.95875 16.02 10.6855C16.6871 11.4618 16.8727 12.3246 16.875 12.6205L16.866 12.6228L16.8503 12.625H7.89975ZM12.375 5.875C12.9717 5.875 13.544 5.63795 13.966 5.21599C14.3879 4.79403 14.625 4.22174 14.625 3.625C14.625 3.02826 14.3879 2.45597 13.966 2.03401C13.544 1.61205 12.9717 1.375 12.375 1.375C11.7783 1.375 11.206 1.61205 10.784 2.03401C10.3621 2.45597 10.125 3.02826 10.125 3.625C10.125 4.22174 10.3621 4.79403 10.784 5.21599C11.206 5.63795 11.7783 5.875 12.375 5.875ZM15.75 3.625C15.75 4.06821 15.6627 4.50708 15.4931 4.91656C15.3235 5.32603 15.0749 5.69809 14.7615 6.01149C14.4481 6.32488 14.076 6.57348 13.6666 6.74309C13.2571 6.9127 12.8182 7 12.375 7C11.9318 7 11.4929 6.9127 11.0834 6.74309C10.674 6.57348 10.3019 6.32488 9.98851 6.01149C9.67512 5.69809 9.42652 5.32603 9.25691 4.91656C9.0873 4.50708 9 4.06821 9 3.625C9 2.72989 9.35558 1.87145 9.98851 1.23851C10.6214 0.605579 11.4799 0.25 12.375 0.25C13.2701 0.25 14.1286 0.605579 14.7615 1.23851C15.3944 1.87145 15.75 2.72989 15.75 3.625ZM7.803 8.44C7.35273 8.29927 6.88894 8.20614 6.41925 8.16213C6.15529 8.13638 5.89021 8.12399 5.625 8.125C1.125 8.125 0 11.5 0 12.625C0 13.375 0.375 13.75 1.125 13.75H5.868C5.7013 13.3988 5.61813 13.0137 5.625 12.625C5.625 11.4888 6.04913 10.3277 6.85125 9.358C7.12463 9.02725 7.443 8.71787 7.803 8.44ZM5.535 9.25C4.86959 10.2506 4.50995 11.4234 4.5 12.625H1.125C1.125 12.3325 1.3095 11.4663 1.98 10.6855C2.59313 9.97 3.6585 9.2725 5.535 9.25113V9.25ZM1.6875 4.1875C1.6875 3.29239 2.04308 2.43395 2.67601 1.80101C3.30895 1.16808 4.16739 0.8125 5.0625 0.8125C5.95761 0.8125 6.81605 1.16808 7.44899 1.80101C8.08192 2.43395 8.4375 3.29239 8.4375 4.1875C8.4375 5.08261 8.08192 5.94105 7.44899 6.57399C6.81605 7.20692 5.95761 7.5625 5.0625 7.5625C4.16739 7.5625 3.30895 7.20692 2.67601 6.57399C2.04308 5.94105 1.6875 5.08261 1.6875 4.1875ZM5.0625 1.9375C4.46576 1.9375 3.89347 2.17455 3.47151 2.59651C3.04955 3.01847 2.8125 3.59076 2.8125 4.1875C2.8125 4.78424 3.04955 5.35653 3.47151 5.77849C3.89347 6.20045 4.46576 6.4375 5.0625 6.4375C5.65924 6.4375 6.23153 6.20045 6.65349 5.77849C7.07545 5.35653 7.3125 4.78424 7.3125 4.1875C7.3125 3.59076 7.07545 3.01847 6.65349 2.59651C6.23153 2.17455 5.65924 1.9375 5.0625 1.9375Z" fill="'.esc_attr($fill).'"/></svg>';

        $output['document'] = '<svg  width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="m28.072 5.765-3.815-3.815c-.548-.548-1.276-.85-2.05-.85h-15.228c-2.15 0-3.9 1.75-3.9 3.9v22c0 2.15 1.75 3.9 3.9 3.9h18.043c2.15 0 3.9-1.75 3.9-3.9v-19.185c-.001-.775-.302-1.503-.85-2.05zm-5.087-2.543 3.815 3.815c.208.208.322.484.322.778v.718h-4.525c-.606 0-1.1-.494-1.1-1.1v-4.533h.711c.289 0 .572.118.777.322zm2.036 25.878h-18.042c-1.158 0-2.1-.942-2.1-2.1v-22c0-1.158.942-2.1 2.1-2.1h12.718v4.533c0 1.599 1.301 2.9 2.9 2.9h4.525v16.667c0 1.158-.943 2.1-2.101 2.1z"/><path d="m21.934 15.299-2.168 1.747-.569-.725c-.306-.391-.872-.459-1.263-.153s-.46.872-.153 1.263l1.131 1.443c.148.189.367.312.606.339.034.004.068.006.102.006.205 0 .404-.069.564-.199l2.878-2.319c.387-.312.448-.878.136-1.265-.311-.387-.878-.448-1.264-.137z"/><path d="m15.165 16.535h-5.663c-.497 0-.9.403-.9.9s.403.9.9.9h5.663c.497 0 .9-.403.9-.9s-.403-.9-.9-.9z"/><path d="m21.934 20.414-2.168 1.747-.569-.725c-.306-.391-.872-.46-1.263-.153s-.46.872-.153 1.263l1.131 1.443c.148.189.367.312.606.339.034.004.068.006.102.006.205 0 .404-.069.564-.199l2.878-2.319c.387-.312.448-.878.136-1.265-.311-.388-.878-.449-1.264-.137z"/><path d="m15.165 21.649h-5.663c-.497 0-.9.403-.9.9s.403.9.9.9h5.663c.497 0 .9-.403.9-.9s-.403-.9-.9-.9z"/></svg>';

        $output['star'] = '<svg  width="512" height="512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="m256 16c-10.7 220.5-19.5 229.3-240 240 220.5 10.7 229.3 19.5 240 240 10.7-220.5 19.5-229.3 240-240-220.5-10.7-229.3-19.5-240-240z"/></svg>';

        $output['comments'] = '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20" fill="none" style="opacity: '.esc_attr($opacity).';"><path fill="'.esc_attr($fill).'" d="M3.3475 14.8675C3.47725 14.9977 3.57667 15.155 3.63868 15.3281C3.70069 15.5011 3.72376 15.6857 3.70625 15.8687C3.61818 16.7157 3.45164 17.5526 3.20875 18.3687C4.9525 17.965 6.0175 17.4975 6.50125 17.2525C6.77563 17.1135 7.09161 17.0806 7.38875 17.16C8.24037 17.3879 9.1184 17.5023 10 17.5C14.995 17.5 18.75 13.9912 18.75 10C18.75 6.00875 14.995 2.5 10 2.5C5.005 2.5 1.25 6.01 1.25 10C1.25 11.835 2.02125 13.5375 3.3475 14.8675ZM2.73125 19.7487C2.43507 19.8074 2.13795 19.8611 1.84 19.91C1.59 19.95 1.4 19.69 1.49875 19.4575C1.60944 19.1956 1.71117 18.9301 1.80375 18.6612L1.8075 18.6487C2.1175 17.7487 2.37 16.7137 2.4625 15.75C0.92875 14.2125 0 12.2 0 10C0 5.1675 4.4775 1.25 10 1.25C15.5225 1.25 20 5.1675 20 10C20 14.8325 15.5225 18.75 10 18.75C9.00951 18.7522 8.02306 18.6236 7.06625 18.3675C6.41625 18.6962 5.0175 19.295 2.73125 19.7487Z"/></svg>';

        $output['arrow-triangle'] = '<svg width="1em" height="1em" viewBox="0 0 16 18" xmlns="http://www.w3.org/2000/svg" fill="'.esc_attr($fill).'" style="opacity: '.esc_attr($opacity).';"><path d="M3.0003 0.271142C1.66697 -0.498973 0 0.463259 0 2.00302V15.0734C0 16.6132 1.66697 17.5754 3.00031 16.8053L14.315 10.2701C15.6479 9.5002 15.6479 7.57621 14.315 6.80633L3.0003 0.271142Z"/></svg>';

        $output = apply_filters('wgl_filter_theme_svg', $output);
        return $clear ? array_map('rawurlencode', $output) : $output;
    }


    public function init_additional_breakpoints()
    {
        $extra_css = '';
        $breakpoints = array_reverse(\Elementor\Plugin::$instance->breakpoints->get_active_breakpoints());
        $extra_css .= $this->content_alignment_responsive();
        $extra_css .= $this->media_content_responsive();
        $extra_css .= $this->hide_element_responsive(false, '-desktop');
        foreach ( $breakpoints as $breakpoint_name => $breakpoint ) {
            $extra_css .= $this->content_alignment_responsive($breakpoints[$breakpoint_name]->get_value(), '-'. $breakpoint_name);
            $extra_css .= $this->media_content_responsive($breakpoints[$breakpoint_name]->get_value(), '-'. $breakpoint_name);
            $extra_css .= $this->media_alignment_responsive($breakpoints[$breakpoint_name]->get_value(), '-'. $breakpoint_name);
            $extra_css .= $this->hide_element_responsive($breakpoints[$breakpoint_name]->get_value(), '-'. $breakpoint_name);
		}
        return $extra_css;
    }

    public function hide_element_responsive( $value = false, $media = '' )
    {
        $resolution = '-widescreen' === $media ? 'min' : 'max';
        $extra_css = $value ? '@media ('.$resolution.'-width: '.$value.'px) {' : '';
        $extra_css .= '
            .wgl-hidden'.$media.' {
                display: none;
            }';
        $extra_css .= $value ? '}' : '';

        return $extra_css;
    }

    public function content_alignment_responsive( $value = false, $media = '' )
    {
        $resolution = '-widescreen' === $media ? 'min' : 'max';
        $extra_css = $value ? '@media ('.$resolution.'-width: '.$value.'px) {' : '';
        $extra_css .= '
            body .a'.$media.'left {
                text-align: left;
            }
            body .a'.$media.'center {
                text-align: center;
            }
            body .a'.$media.'right {
                text-align: right;
            }
            body .a'.$media.'justify {
                text-align: justify;
            }';
        $extra_css .= $value ? '}' : '';

        return $extra_css;
    }

    public function media_alignment_responsive( $value = false, $media = '' )
    {
        $resolution = '-widescreen' === $media ? 'min' : 'max';
        $extra_css = $value ? '@media ('.$resolution.'-width: '.$value.'px) {' : '';
        $extra_css .= '
            body .a'.$media.'center .wgl-layout-left{
                justify-content: center;
            }
            body .a'.$media.'center .wgl-layout-right{
                justify-content: center;
            }
            body .a'.$media.'left .wgl-layout-left {
                justify-content: flex-start;
            }
            body .a'.$media.'left .wgl-layout-right {
                justify-content: flex-end;
            }

            body .a'.$media.'right .wgl-layout-left{
                justify-content: flex-end;
            }
            body .a'.$media.'right .wgl-layout-right{
                justify-content: flex-start;
            }';
        $extra_css .= $value ? '}' : '';

        return $extra_css;
    }

    public function media_content_responsive( $value = false, $media = '' )
    {
        $resolution = '-widescreen' === $media ? 'min' : 'max';
        $extra_css = $value ? '@media ('.$resolution.'-width: '.$value.'px) {' : '';
        $extra_css .= '
            body .wgl-layout'.$media.'-top {
                flex-direction: column;
            }
            body .wgl-layout'.$media.'-bottom {
                flex-direction: column-reverse;
            }
            body .wgl-layout'.$media.'-left {
                flex-direction: row;
            }
            body .wgl-layout'.$media.'-right {
                flex-direction: row-reverse;
            }';
        $extra_css .= $value ? '}' : '';

        return $extra_css;
    }

    public function get_elementor_container_width()
    {
        $container_width = '';
        if (
            did_action('elementor/loaded')
            && defined('ELEMENTOR_VERSION')
        ) {
            $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();
            $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
            $kit_settings = get_post_meta($kit_id, $meta_key, true);
            $container_width = $kit_settings['container_width']['size'] ?? 1140;
        }

        return $container_width ? $container_width : 1200;
    }

    protected function get_mobile_header_extra_css()
    {
        $extra_css = '';

        $this->get_elementor_css_cache_header();

        if (WGL_Framework::get_option('mobile_header') && 'elementor' !== WGL_Framework::get_option('mobile_header_building_tool')) {
            $mobile_background = WGL_Framework::get_option('mobile_background')['rgba'] ?? '';
            $mobile_color = WGL_Framework::get_option('mobile_color');
            $mobile_border_color = WGL_Framework::get_option('mobile_border_color')['rgba'] ?? '';

            $extra_css .= '.wgl-theme-header {'
                    . 'background-color: ' . esc_attr($mobile_background) . ' !important;'
                    . 'color: ' . esc_attr($mobile_color) . ' !important;'
                    . 'border-bottom: 1px solid ' . esc_attr($mobile_border_color) . ' !important;'
                . '}';
        }

        if ('elementor' !== WGL_Framework::get_option('mobile_header_building_tool')) {
            $extra_css .= 'header.wgl-theme-header .wgl-mobile-header {'
                    . 'display: block;'
                . '}'
                . '.wgl-site-header,'
                . '.wgl-theme-header .primary-nav {'
                    . 'display: none;'
                . '}'
                . '.wgl-theme-header .hamburger-box {'
                    . 'display: inline-flex;'
                . '}'
                . 'header.wgl-theme-header .mobile_nav_wrapper .primary-nav {'
                    . 'display: block;'
                . '}';
        }else{
            $extra_css .= '.wgl-menu-outer_content .elementor-mobile-breakpoint .primary-nav,
            body.single-header.elementor-editor-active .wgl-site-header .wgl-menu-outer_content .elementor-mobile-breakpoint .primary-nav{
                display:block;
            }
            .elementor-mobile-breakpoint .hamburger-box,
            body.single-header.elementor-editor-active .wgl-site-header .elementor-mobile-breakpoint .hamburger-box{
                display:block;
            }
            .wgl-menu-outer_content .elementor-mobile-breakpoint .hamburger-box,
            body.single-header.elementor-editor-active .wgl-site-header .wgl-menu-outer_content .elementor-mobile-breakpoint .hamburger-box{
                display:none;
            }
            body .wgl-mobile-header{
                display:block;
            }
            .wgl-theme-header .hamburger-box{
                display:inline-flex;
            }
            body .wgl-mobile-header .wgl-header-row{
                display: none;
            }';
        }

        $extra_css .= '.wgl-theme-header .wgl-sticky-header {'
                . 'display: none;'
            . '}'
            . '.wgl-page-socials {'
                . 'display: none;'
            . '}'
            . '.wgl-body-bg {'
                . 'top: var(--courto-header-mobile-height) !important;'
            . '}';

        $mobile_sticky = WGL_Framework::get_option('mobile_sticky');
        $mobile_over_content = WGL_Framework::get_option('mobile_over_content');

        if ('elementor' === WGL_Framework::get_option('mobile_header_building_tool')) {
            if (
                !empty($this->header_page_id)
                && did_action('elementor/loaded')
            ) {
                // Get the page settings manager
                $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

                // Get the settings model for header post
                $page_settings_model = $page_settings_manager->get_model($this->header_page_id);

                $mobile_sticky = $page_settings_model->get_data('settings')['mobile_sticky'] ?? '';
                $mobile_over_content = $page_settings_model->get_data('settings')['header_on_bg'] ?? '';
            }
        }

        if ($mobile_over_content) {
            $extra_css .= 'body .wgl-theme-header {'
                    . 'position: absolute;'
                    . 'z-index: 1001;'
                    . 'width: 100%;'
                    . 'left: 0;'
                    . 'top: 0;'
                . '}';

            if ($mobile_sticky) {
                $extra_css .= 'body .wgl-theme-header .wgl-mobile-header {'
                        . 'position: absolute;'
                        . 'left: 0;'
                        . 'width: 100%;'
                    . '}';
            }

        }

        if ( $mobile_sticky ) {
            $extra_css .= 'body .wgl-theme-header,'
                . 'body .wgl-theme-header.header_overlap {'
                .   'position: sticky;'
                .   'top: 0;'
                . '}'
                . '.admin-bar .wgl-theme-header{'
                .   'top: var(--admin-bar-height);'
                . '}'
                . 'body.mobile_switch_on{'
                .   'position: static !important;'
                . '}'
                . 'body.admin-bar .sticky_mobile .wgl-menu_outer{'
                .   'top: 0px;'
                .   'height: 100vh;'
                . '}'
                . '.wgl-theme-header .wgl_notices_wrapper{'
                .   'transform: translateY(calc(var(--height) + var(--admin-bar-height))) !important;'
                . '}';
        }

        $extra_css .= 'body{'
            . '--courto-button-padding-mobile: var(--courto-btn-p-m);'
            . '--courto-button-font-size-mobile: var(--courto-btn-fs-m);'
            . '--courto-button-line-height-mobile: var(--courto-btn-lh-m);'
            . '--courto-button-letter-spacing-mobile: var(--courto-btn-ls-m);'
            . '}';

        $extra_css .= 'body .wgl-theme-header .mini_cart-overlay{'
                . 'top: calc(-1px * var(--wgl-m-positioning-cart-top, var(--positioning-size)));'
                . 'right: calc(-1px * var(--wgl-m-positioning-cart-right, var(--positioning-size)));'
            . '}';
        $extra_css .= 'body .wgl-theme-header .wgl_notices_wrapper{'
                . '--positioning-size: 0;'
                . 'max-width: calc(100% - calc(var(--wgl-m-positioning-cart-right, var(--positioning-size)) * 2px));'
                . 'padding-right: 0;'
                . 'top: calc(1px * var(--wgl-m-positioning-cart-top, var(--positioning-size)));'
                . 'right: calc(1px * var(--wgl-m-positioning-cart-right, var(--positioning-size)));'
            . '}';
        $extra_css .= 'body div.wc-block-components-notice-banner{'
                . 'margin-bottom: calc(1px * var(--wgl-m-positioning-cart-right, var(--positioning-size)));'
            . '}';
        $extra_css .= 'body .wgl-theme-header .wgl_notices_wrapper.stick_top{'
                . 'top: max(1px * var(--wgl-m-positioning-cart-top, var(--positioning-size)), 0px);'
            . '}';

        $extra_css2 = '.wgl-theme-header .wgl-sticky-header.sticky_active ~ .wgl_notices_wrapper {'
                . 'transform: translateY(calc(var(--sticky-height) + var(--admin-bar-height)));'
            . '}';

        return '@media only screen and (max-width: ' . $this->get_header_mobile_breakpoint() . 'px) {' . $extra_css . '}'.
            '@media only screen and (min-width: ' . ((int)$this->get_header_mobile_breakpoint() + 1) . 'px) {' . $extra_css2 . '}';
    }

    protected function get_header_mobile_breakpoint()
    {
        $elementor_breakpoint = '';

        if (
            'elementor' === $this->header_building_tool
            && $this->header_page_id
            && did_action('elementor/loaded')
        ) {
            $settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
            $settings_model = $settings_manager->get_model($this->header_page_id);

            $elementor_breakpoint = $settings_model->get_data('settings')['mobile_breakpoint'] ?? '';
        }

        return $elementor_breakpoint ?: (int) WGL_Framework::get_option('header_mobile_queris');
    }

    protected function get_elementor_breakpoint()
    {
        $values = '';
        if(class_exists('\Elementor\Plugin')){
            $values = array_map( function( $breakpoint ) {
                return $breakpoint->get_value();
            }, \Elementor\Plugin::$instance->breakpoints->get_breakpoints() );
        }

        return $values;
    }

    protected function get_breakpoints_extra_css()
    {
        $elementor_breakpoint_tablet = isset($this->get_elementor_breakpoint()['tablet']) ? $this->get_elementor_breakpoint()['tablet'] : '1200';
        $elementor_breakpoint_mobile = isset($this->get_elementor_breakpoint()['mobile']) ? $this->get_elementor_breakpoint()['mobile'] : '767';
        $extra_css_tablet = $extra_css_mobile = '';

        $extra_css_tablet .= 'body .wgl-container {'
            . 'max-width: calc(var(--content-width, 1024px) + var(--wgl-container-padding-tablet));'
            . 'padding-left: var(--wgl-container-padding-tablet);'
            . 'padding-right: var(--wgl-container-padding-tablet);'
        . '}';

        // page 404
        $page_404__padding_resp = WGL_Framework::get_option('404_page_main_padding_responsive');
        $extra_css_tablet .= 'body.error404 .page_404_wrapper > .wgl-container {'
            . (!empty($page_404__padding_resp['padding-top']) ? 'padding-top: ' . esc_attr((int) $page_404__padding_resp['padding-top']) . 'px !important;' : '')
            . (!empty($page_404__padding_resp['padding-bottom']) ? 'padding-bottom: ' . esc_attr((int) $page_404__padding_resp['padding-bottom']) . 'px !important;' : '')
        . '}';

        $extra_css_mobile .= 'body .wgl-container {'
            . 'padding-left: var(--wgl-container-padding-mobile);'
            . 'padding-right: var(--wgl-container-padding-mobile);'
        . '}';

        return '@media only screen and (max-width: ' . $elementor_breakpoint_tablet . 'px) {' . $extra_css_tablet . '}'.
            '@media only screen and (max-width: ' . $elementor_breakpoint_mobile . 'px) {' . $extra_css_mobile . '}';
    }

    protected function get_page_title_responsive_extra_css()
    {
        $queried_post_type = get_post_type();
        $responsive_disabled = ! WGL_Framework::get_option( 'page_title_resp_switch' );

        if (
            $this->RWMB_is_active()
            && 'on' === rwmb_meta('mb_page_title_switch')
            && rwmb_meta('mb_page_title_resp_switch')
        ) {
            $responsive_disabled = false;
        }

        if ( $responsive_disabled ) {
            // Bailout.
            return;
        }

        $pt_padding = WGL_Framework::get_mb_option('page_title_resp_padding', 'mb_page_title_resp_switch', true);
        $pt_margin = WGL_Framework::get_mb_option('page_title_resp_margin', 'mb_page_title_resp_switch', true);

        $extra_css = '.page-header {'
            . (!empty($pt_padding['padding-top']) ? 'padding-top: ' . esc_attr((int) $pt_padding['padding-top']) . 'px !important;' : '')
            . (!empty($pt_padding['padding-bottom']) ? 'padding-bottom: ' . esc_attr((int) $pt_padding['padding-bottom']) . 'px !important;' : '')
            . (!empty($pt_margin['margin-bottom']) ? 'margin-bottom: ' . esc_attr((int) $pt_margin['margin-bottom']) . 'px !important;' : '')
            . 'min-height: auto !important;'
        . '}';

        $breadcrumbs_switch = WGL_Framework::get_mb_option('page_title_resp_breadcrumbs_switch', 'mb_page_title_resp_switch', true);

        //* Title
        $pt_font = WGL_Framework::get_mb_option('page_title_resp_font', 'mb_page_title_resp_switch', true);
        $pt_color = !empty($pt_font['color']) ? 'color: ' . esc_attr($pt_font['color']) . ' !important;' : '';
        $pt_f_size = !empty($pt_font['font-size']) ? ' font-size: ' . esc_attr((int) $pt_font['font-size']) . 'px !important;' : '';
        $pt_line_height = !empty($pt_font['line-height']) ? ' line-height: ' . esc_attr((int) $pt_font['line-height']) . 'px !important;' : '';
        $pt_additional_style = !(bool) $breadcrumbs_switch ? ' margin-bottom: 0 !important;' : '';
        $title_style = $pt_color . $pt_f_size . $pt_line_height . $pt_additional_style;

        $extra_css .= '.page-header_content .page-header_title {' . $title_style . '}';

        //* Breadcrumbs
        $breadcrumbs_font = WGL_Framework::get_mb_option('page_title_resp_breadcrumbs_font', 'mb_page_title_resp_switch', true);
        $breadcrumbs_color = !empty($breadcrumbs_font['color']) ? 'color: ' . esc_attr($breadcrumbs_font['color']) . ' !important;' : '';
        $breadcrumbs_f_size = !empty($breadcrumbs_font['font-size']) ? 'font-size: ' . esc_attr((int) $breadcrumbs_font['font-size']) . 'px !important;' : '';
        $breadcrumbs_line_height = !empty($breadcrumbs_font['line-height']) ? 'line-height: ' . esc_attr((int) $breadcrumbs_font['line-height']) . 'px !important;' : '';
        $breadcrumbs_display = !(bool) $breadcrumbs_switch ? 'display: none !important;' : '';
        $breadcrumbs_style = $breadcrumbs_color . $breadcrumbs_f_size . $breadcrumbs_line_height . $breadcrumbs_display;

        $extra_css .= '.page-header_content .page-header_breadcrumbs {' . $breadcrumbs_style . '}';

        //* Blog Single Type 3
        if (
            is_single()
            && 'post' === get_post_type()
            && '3' === WGL_Framework::get_mb_option('post_single_type_layout', 'mb_post_layout_conditional', 'custom')
        ) {
            $blog_t3_padding = WGL_Framework::get_option('single_padding_layout_3');
            $blog_t3_p_top = $blog_t3_padding[ 'padding-top' ] ?? '';
            $blog_t3_p_bottom = $blog_t3_padding[ 'padding-bottom' ] ?? '';
            $blog_t3_p_top_responsive = $blog_t3_p_top > $blog_t3_p_bottom ? 80 + (int) $blog_t3_p_bottom : (int) $blog_t3_p_top;
            $blog_t3_p_top_responsive = $blog_t3_p_top_responsive > 160 ? 160 : $blog_t3_p_top_responsive;
            $blog_t3_style = 'padding-top: ' . $blog_t3_p_top_responsive . 'px !important;';
            $blog_t3_style .= 'padding-bottom: 40px !important;';

            $extra_css .= '.single-post .post_featured_bg > .blog-post {' . esc_attr( $blog_t3_style ) . '}';
        }

        $pt_breakpoint = (int) WGL_Framework::get_mb_option('page_title_resp_resolution', 'mb_page_title_resp_switch', true);

        return '@media (max-width: ' . $pt_breakpoint . 'px) {' . $extra_css . '}';
    }

    /**
     * Enqueue theme stylesheets
     *
     * Function keeps track of already enqueued stylesheets and stores them in `enqueued_stylesheets[]`
     *
     * @param string   $tag      Unprefixed handle.
     * @param string   $file_dir Optional. Path to stylesheet folder, relative to theme root folder.
     * @param string[] $deps     Optional. An array of registered stylesheet handles this stylesheet depends on.
     */
    public function enqueue_theme_stylesheet( String $tag, $file_dir = '/css/pluggable/', $deps = [] )
    {
        $prefixed_tag = WGL_Framework_Global_Variables::get_theme_slug() . '-' . $tag;

        wp_enqueue_style(
            $prefixed_tag,
            $this->template_directory_uri . $file_dir . $tag . $this->use_minified . '.css',
            $deps,
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $this->enqueued_stylesheets[] = $prefixed_tag;
    }

    public function enqueue_pluggable_styles()
    {
        //* Preloader
        WGL_Framework::get_option( 'preloader' ) && $this->enqueue_theme_stylesheet( 'preloader' . (is_rtl() ? '-rtl' : '') );

        //* Page 404|Search
        ( is_404() || is_search() ) && $this->enqueue_theme_stylesheet( 'page-404' . (is_rtl() ? '-rtl' : '') );

        //* Gutenberg
        WGL_Framework::get_option( 'disable_wp_gutenberg' )
            ? wp_dequeue_style( 'wp-block-library' )
            : $this->enqueue_theme_stylesheet( 'gutenberg' . (is_rtl() ? '-rtl' : '') );

        //* Post Single
        if ( is_single() ) {
            $post_type = get_post()->post_type;
            if (
                'post' === $post_type
                || 'portfolio' === $post_type
                || 'courses' === $post_type
                || 'lp_course' === $post_type
            ) {
                $this->enqueue_theme_stylesheet( 'blog-post-single' . (is_rtl() ? '-rtl' : '') );
            } elseif ( 'team' === $post_type ) {
                $this->enqueue_theme_stylesheet( 'team-post-single' . (is_rtl() ? '-rtl' : '') );
            }
        }

        //* WooCommerce Plugin
        class_exists( 'WooCommerce' ) && $this->enqueue_theme_stylesheet( 'woocommerce' . (is_rtl() ? '-rtl' : '') );

        //* Side Panel
        if (
            WGL_Framework::get_option( 'side_panel_enabled' )
            || 'side_panel' === ( get_queried_object()->post_type ?? '' )
            || (class_exists('RWMB_Loader') && 'default' != rwmb_meta('mb_customize_side_panel'))
        ) {
            $this->enqueue_theme_stylesheet( 'side-panel' . (is_rtl() ? '-rtl' : '') );
        }

        //* WPML plugin
        class_exists( 'SitePress' ) && $this->enqueue_theme_stylesheet( 'wpml' . (is_rtl() ? '-rtl' : '') );

        //* Polylang plugin
        if (function_exists('pll_the_languages')) {
            $this->enqueue_theme_stylesheet('polylang' . (is_rtl() ? '-rtl' : ''));
        }

        if (
            did_action('elementor/loaded')
        ) {
            $id = get_the_ID();
            $p_s = get_post_meta($id, '_elementor_page_settings');
            if (isset($p_s[0]) && is_array($p_s[0]) && isset($p_s[0]['use_webgl_fluid'])){
                $fluid_animation = $p_s[0]['use_webgl_fluid'];
                if (!!$fluid_animation) {
                    $this->enqueue_theme_stylesheet( 'webgl_fluid' );
                }
            }
        }
    }

    public function frontend_scripts()
    {

        wp_enqueue_script(
            'select2',
            $this->template_directory_uri . '/js/select2/js/select2.full.min.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        wp_enqueue_script(
            'gsap',
            $this->template_directory_uri . '/js/gsap-core.min.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        if (WGL_Framework::get_option('smooth_scroll_switch')) {
            wp_enqueue_script(
                'lenis',
                $this->template_directory_uri . '/js/lenis.min.js',
                ['jquery'],
                WGL_Framework_Global_Variables::get_theme_version(),
                true
            );
        }

        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-addons',
            $this->template_directory_uri . '/js/theme-addons' . $this->use_minified . '.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme',
            $this->template_directory_uri . '/js/theme.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        $smooth_scroll_options = [
            'enabled'      => esc_js((bool) WGL_Framework::get_option('smooth_scroll_switch')),
            'speed'        => esc_js(floatval(WGL_Framework::get_option('smooth_scroll_speed') ?: 1.2)),
            'lerp'         => esc_js(floatval(WGL_Framework::get_option('smooth_scroll_lag') ?: 0.1)),
            'disableMac'   => esc_js((bool) WGL_Framework::get_option('smooth_scroll_mac_disable')),
            'customEasing' => esc_js(WGL_Framework::get_option('smooth_scroll_custom_easing') ?: 'linear'),
        ];

        wp_localize_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme',
            'wgl_core',
            [
                'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
                'nonce' => esc_js( wp_create_nonce('wgl_ajax_nonce') ),
                'smoothScroll' => $smooth_scroll_options,
            ]
        );

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    public function admin_stylesheets()
    {
        wp_enqueue_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            $this->template_directory_uri . '/core/admin/css/admin.css',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $this->enqueue_additional_styles();

        wp_enqueue_style( 'wp-color-picker' );
    }

    public function admin_scripts()
    {
        wp_enqueue_media();

        wp_enqueue_script('wp-color-picker');
	    wp_localize_script('wp-color-picker', 'wpColorPickerL10n', [
		    'clear' => esc_html__('Clear', 'courto'),
		    'clearAriaLabel' => esc_html__('Clear color', 'courto'),
		    'defaultString' => esc_html__('Default', 'courto'),
		    'defaultAriaLabel' => esc_html__('Select default color', 'courto'),
		    'pick' => esc_html__('Select', 'courto'),
		    'defaultLabel' => esc_html__('Color value', 'courto'),
        ]);

        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            $this->template_directory_uri . '/core/admin/js/admin.js',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $currentTheme = wp_get_theme();
        $theme_name = false == $currentTheme->parent()
            ? wp_get_theme()->get('Name')
            : wp_get_theme()->parent()->get('Name');
        $theme_name = trim($theme_name);

        $purchase_code = $email = '';
        if (WGL_Framework::wgl_theme_activated()) {
            $theme_details = get_option('wgl_licence_validated');
            $purchase_code = $theme_details['purchase'] ?? '';
            $email = $theme_details['email'] ?? '';
        }

        wp_localize_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            'wgl_verify',
            [
                'ajaxurl' => esc_js(admin_url('admin-ajax.php')),
                'wglUrlActivate' => esc_js(WGL_Theme_Verify::get_instance()->api . 'verification'),
                'wglUrlReset' => esc_js(WGL_Theme_Verify::get_instance()->api . 'reset_activation'),
                'wglUrlDeactivate' => esc_js(WGL_Theme_Verify::get_instance()->api . 'deactivate'),
                'domainUrl' => esc_js(site_url('/')),
                'themeName' => esc_js($theme_name),
                'purchaseCode' => esc_js($purchase_code),
                'email' => esc_js($email),
                'message' => esc_js(esc_html__('Thank you, your license has been validated', 'courto')),
                'ajax_nonce' => esc_js(wp_create_nonce('_notice_nonce')),
                'titleCodeRigistered' => esc_js(esc_html__('This purchase code has been registered', 'courto')),
                'messageCodeRigistered' => esc_js(esc_html__('Please go to your previous working environment and deactivate the purchase code to use it again (WP dashboard -> WebGeniusLab -> Activate Theme -> click on the button "Deactivate" )', 'courto')),
                'messageLostCode' => esc_js(esc_html__('Lost access to your previous site?', 'courto')),
                'activate_plugin_btn_text' => esc_js(esc_html__( 'Activate', 'courto' )),
                'update_plugin_btn_text' => esc_js(esc_html__( 'Update', 'courto' )),
                'deactivate_plugin_btn_text' => esc_js(esc_html__( 'Deactivate', 'courto' )),
                'install_plugin_btn_text' => esc_js(esc_html__( 'Install', 'courto' )),
                'activate_process_plugin_btn_text'  => esc_js(esc_html__( 'Activating', 'courto' )),
                'update_process_plugin_btn_text' => esc_js(esc_html__( 'Updating', 'courto' )),
                'deactivate_process_plugin_btn_text' => esc_js(esc_html__( 'Deactivating', 'courto' )),
                'install_process_plugin_btn_text' => esc_js(esc_html__( 'Installing', 'courto' )),
                'install_activation_error' => esc_js(sprintf(
                    '<p><a href="%s" target="_blank">%s</a></p>',
                    admin_url('themes.php?page=tgmpa-install-plugins'),
                    esc_html__('Something went wrong.', 'courto'),
                )),
            ]
        );
    }

    protected function add_body_classes()
    {
        add_filter( 'body_class', function ( Array $classes ) {
            if ( !WGL_Framework::get_option('wgl_input_style') ) {
                $classes[] = 'wgl-style-input';
            }

            if ( $this->gradient_enabled ) {
                $classes[] = 'theme-gradient';
            }

            if (
                is_single()
                && 'post' === get_post_type( get_queried_object_id() )
                && '3' === WGL_Framework::get_mb_option( 'post_single_type_layout', 'mb_post_layout_conditional', 'custom' )
            ) {
                $classes[] = WGL_Framework_Global_Variables::get_theme_slug() . '-blog-type-overlay';
            }

            return $classes;
        } );

        add_filter( 'wgl/header/mobile_width', function ($data) {
            return $this->get_header_mobile_breakpoint();
        } );
    }

    public function RWMB_is_active()
    {
        $id = ! is_archive() ? get_queried_object_id() : 0;

        return class_exists( 'RWMB_Loader' ) && 0 !== $id;
    }

    public function minify_css($css = null)
    {
        $css = str_replace(',{', '{', $css);
        $css = str_replace(', ', ',', $css);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        $css = trim($css);

        return $css;
    }

    /**
     * @return string
     */
    public function extracted(): string
    {
        return 'cart';
    }
}

function wgl_dynamic_styles()
{
    return WGL_Framework_Dynamic_Styles::instance();
}

wgl_dynamic_styles()->construct();
