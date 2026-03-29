<?php
defined('ABSPATH') || exit;

if (!class_exists('Courto_Header_Mobile')) {
    class Courto_Header_Mobile extends Courto_Get_Header
    {
        public function __construct()
        {
            $this->header_vars();
            $this->html_render = 'mobile';

            $header_mobile_background = WGL_Framework::get_option('mobile_background');
            $header_mobile_color = WGL_Framework::get_option('mobile_color');
            $header_mobile_border_color = WGL_Framework::get_option('mobile_border_color');
            $mobile_header_custom =  WGL_Framework::get_option('mobile_header');
            $mobile_sticky = WGL_Framework::get_option('mobile_sticky');

            $mobile_styles = !empty($header_mobile_background['rgba']) ? '--mobile-header-bg-color: '.(esc_attr($header_mobile_background['rgba'])).'; ' : '';
            $mobile_styles .= !empty($header_mobile_color) ? '--mobile-header-color: '.(esc_attr($header_mobile_color)).';' : '';
            $mobile_styles .= !empty($header_mobile_border_color['rgba']) ? '--mobile-header-border-color: '.(esc_attr($header_mobile_border_color['rgba'])).'; ' : '';
            $mobile_styles = !empty($mobile_styles) ? ' style="'.$mobile_styles.'"' : '';
            $mobile_builder = WGL_Framework::get_option('mobile_header_building_tool');

            $elementor_builder = false;
            if (!empty($mobile_header_custom)) {
                if('elementor' === $mobile_builder){
                    $header_drawer_settings = '';
                    if (class_exists('\Elementor\Core\Settings\Manager')) {
                        $header_drawer_settings = \Elementor\Core\Settings\Manager::get_settings_managers('page')
                            ->get_model($this->header_page_select_id)
                            ->get_data('settings')['mobile_drawer_template'] ?? '';
                        $mobile_sticky = \Elementor\Core\Settings\Manager::get_settings_managers('page')
                            ->get_model($this->header_page_select_id)
                            ->get_data('settings')['mobile_sticky'] ?? '';
                    }
                    $active_template = '';
                    if(!empty($header_drawer_settings) && 'wgl_default_template_drawer' !== $header_drawer_settings){
                        $active_template = (int) $header_drawer_settings;
                    }else{
                        $active_template = (int) WGL_Framework::get_option('mobile_drawer_header_page_select');
                    }
                    $page_id = wgl_dynamic_styles()->multi_language_support($active_template , 'elementor_library');
                    if(!empty($page_id)){
                        $elementor_builder = true;
                    }
                }
            }

            echo "<div class='wgl-mobile-header", ($mobile_sticky ? ' wgl-sticky-element' : ''), ($elementor_builder ? ' wgl-elementor-builder' : '') , "'",
                $mobile_styles,
                ($mobile_sticky ? ' data-style="standard"' : ''),
                ">"; ?>
            <div class='container-wrapper'><?php
            if (!empty($mobile_header_custom)) {
                if('elementor' !== $mobile_builder){
                     $this->build_header_layout('mobile');
                }
            } else {
                $this->default_header_mobile();
            }
            $this->build_header_mobile_menu(); ?>
            </div>
            </div><?php
        }

        public function default_header_mobile()
        { ?>
            <div class="wgl-header-row">
            <div class="wgl-container">
            <div class="wgl-header-row_wrapper">
                <div class="header_side display_grow h_align_left">
                <div class="header_area_container">
                <nav class="primary-nav"><?php
                if (has_nav_menu('main_menu')) {
                    wgl_theme_main_menu('main_menu');
                } ?>
                </nav>
                <div class="hamburger-box">
                    <div class="hamburger-inner">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                </div>
                </div>
                <div class="header_side display_grow h_align_center">
                <div class="header_area_container"><?php
                    $this->get_logo(); ?>
                </div>
                </div>
                <div class="header_side display_grow h_align_right">
                    <div class="header_area_container"><?php
                        if (class_exists('WooCommerce')) {
                            global $wgl_woo_cart;
                            $wgl_woo_cart = true;
                            $this->cart('', '');
                        }else{
                            $this->search('mobile', '');
                        }?>
                    </div>
                </div>
            </div>
            </div>
            </div><?php
        }
    }

    new Courto_Header_Mobile();
}
