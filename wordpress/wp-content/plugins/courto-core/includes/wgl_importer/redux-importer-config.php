<?php

if (!function_exists('wgl_extended_example')) {
    /**
     * Add menu and rev slider to demo content.
     * Set defaults settings.
     *
     * @package     wgl_Importer - Extension for Importing demo content
     * @author      Webcreations907
     * @version     1.0
     */
    function wgl_extended_example($demo_active_import, $demo_directory_path)
    {

        reset($demo_active_import);
        $current_key = key($demo_active_import);

        /**
         * Menu(s)
         */

        // Set menu name
        $menu_array = [
            'full' => 'Main Menu'
        ];

        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $menu_array)
        ) {
            $top_menu = get_term_by('name', $menu_array[$demo_active_import[$current_key]['directory']], 'nav_menu');
            if (isset($top_menu->term_id)) {
                set_theme_mod('nav_menu_locations', ['main_menu' => $top_menu->term_id]);
            }
        }

        /**
         * Home Page(s)
         */

        // Array of `demos => homepages` to select from
        $home_pages = [
            'full' => 'Homepage 1',
        ];

        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $home_pages)
        ) {
            $query =  new WP_Query(
                array(
                   'post_type'              => 'page',
                   'title'                  => $home_pages[$demo_active_import[$current_key]['directory']],
                   'posts_per_page'         => 1,
                   'no_found_rows'          => true,
                   'ignore_sticky_posts'    => true,
                   'update_post_term_cache' => false,
                   'update_post_meta_cache' => false,
                )
             );
            $page = $query->posts[0];
            wp_reset_postdata();
            if (isset($page->ID)) {
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
            }
        }

        // Array of `demos => blog page` to select from
        $blog_page = [
            'full' => 'BLOG LISTING'
        ];
        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $blog_page)
        ) {
            $query =  new WP_Query(
                array(
                   'post_type'              => 'page',
                   'title'                  => $blog_page[$demo_active_import[$current_key]['directory']],
                   'posts_per_page'         => 1,
                   'no_found_rows'          => true,
                   'ignore_sticky_posts'    => true,
                   'update_post_term_cache' => false,
                   'update_post_meta_cache' => false,
                )
             );
            $page = $query->posts[0];
            wp_reset_postdata();
            if (isset($page->ID)) {
                update_option('page_for_posts', $page->ID);
            }
        }

        /**
         * WooCommerce Page(s)
         */
        $cart_page = [
            'full' => 'Cart',
        ];
        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $cart_page)
        ) {
            $query =  new WP_Query(
                array(
                    'post_type'              => 'page',
                    'title'                  => $cart_page[$demo_active_import[$current_key]['directory']],
                    'posts_per_page'         => 1,
                    'no_found_rows'          => true,
                    'ignore_sticky_posts'    => true,
                    'update_post_term_cache' => false,
                    'update_post_meta_cache' => false,
                )
            );
            $cart = $query->posts[0];
            if (isset($cart->ID)) {
                update_option('woocommerce_cart_page_id', $cart->ID);
            }
        }

        $checkout_page = [
            'full' => 'Checkout',
        ];
        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $checkout_page)
        ) {
            $query =  new WP_Query(
                array(
                    'post_type'              => 'page',
                    'title'                  => $checkout_page[$demo_active_import[$current_key]['directory']],
                    'posts_per_page'         => 1,
                    'no_found_rows'          => true,
                    'ignore_sticky_posts'    => true,
                    'update_post_term_cache' => false,
                    'update_post_meta_cache' => false,
                )
            );
            $checkout = $query->posts[0];
            if (isset($checkout->ID)) {
                update_option('woocommerce_checkout_page_id', $checkout->ID);
            }
        }

        /**
         * Elementor defaults
         */

        // Support all Custom Post Types
        $cpt_support = get_option('elementor_cpt_support');
        if (!$cpt_support) {
            $cpt_support = ['page', 'post', 'portfolio', 'team', 'footer', 'side_panel', 'header', 'property'];
            update_option('elementor_cpt_support', $cpt_support);
        } else {
            $include_cpt = ['portfolio', 'team', 'footer', 'side_panel', 'header', 'property'];
            foreach ($include_cpt as $cpt) {
                if (!in_array($cpt, $cpt_support)) {
                    $cpt_support[] = $cpt;
                }
            }
            update_option('elementor_cpt_support', $cpt_support);
        }

        update_option('elementor_experiment-e_optimized_css_loading', 'inactive');

        update_option('elementor_experiment-additional_custom_breakpoints', 'inactive');

        update_option('elementor_disable_color_schemes', 'yes');

        update_option('elementor_disable_typography_schemes', 'yes');

        update_option('elementor_container_width', 1170);
        // Font Awesome Styles
        update_option('elementor_load_fa4_shim', 'yes');

        // Compare default options
        if(class_exists( 'WPCleverWoosc')){
            $woosc_compare = get_option('woosc_settings') ?: [];
            $woosc_compare['button_type'] = 'link';
            $woosc_compare['button_icon'] = 'left';
            $woosc_compare['button_normal_icon'] = 'woosc-icon-16';
            $woosc_compare['button_added_icon'] = 'woosc-icon-16';
            $woosc_compare['quick_table_enable'] = 'no';
            $woosc_compare['bar_btn_color'] = class_exists('WGL_Extensions_Core') ? \WGL_Extensions\WGL_Framework_Global_Variables::get_primary_color() : '#232323';
            $woosc_compare['menu_action'] = 'open_sidebar';
            update_option('woosc_settings', $woosc_compare);
        }
        // Wishlist default options
        if(class_exists( 'WPCleverWoosw')){
            $woosw_compare = get_option('woosw_settings') ?: [];
            $woosw_compare['button_type'] = 'link';
            $woosw_compare['button_icon'] = 'left';
            $woosw_compare['menu_action'] = 'open_popup';
            update_option('woosw_settings', $woosw_compare);
        }
        // Wishlist default options
        if(class_exists( 'BeRocket_AAPF')){
            $berocket_aapf = get_option('br_filters_options') ?: [];
            $berocket_aapf['products_holder_id'] = 'div.wgl-products';
            update_option('br_filters_options', $berocket_aapf);
        }

        /**
         * WGL Defaults
         * */
        global $wgl_elementor_page_settings;
        global $wpdb;
        if(!empty($GLOBALS['wgl_elementor_page_settings'])){
            $like = '%'.$GLOBALS['wgl_elementor_page_settings'].'%';
            $result = $wpdb->get_row($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='_elementor_page_settings' AND meta_value LIKE %s", $like), ARRAY_N);
            if (!empty($result)) {
                if (
                    defined('ELEMENTOR_VERSION')
                    && version_compare(ELEMENTOR_VERSION, '3.0', '>=')
                ) {
                    if(isset($result[0])){
                        update_option( 'elementor_active_full_import', 'yes' );
                        update_option( 'elementor_active_kit', $result[0] );
                        \Elementor\Plugin::$instance->files_manager->clear_cache();
                    }
                }
            }
            unset($GLOBALS['wgl_elementor_page_settings']);
        }

        // Permalink Structure
        update_option('permalink_structure', "/%postname%/");
    }

    add_action('wgl_importer_after_content_import', 'wgl_extended_example', 10, 2);

    function wgl_default_kits_init($atts)
    {
        if (!get_option('elementor_active_full_import')) {
            $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();

            if(!$kit_id){
                return;
            }

            $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

            $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
            $kit_settings = get_post_meta($kit_id, $meta_key, true);

            $wgl_settings = [];

            $settings = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend();

            $system_items = $settings->get_settings_for_display('system_colors');

            if (!$system_items) {
                $system_items = [];
            }

            $system_items[0]['color'] = '#6EC1E4';
            $system_items[1]['color'] = '#54595F';
            $system_items[2]['color'] = '#7A7A7A';
            $system_items[3]['color'] = '#61CE70';

            $wgl_settings['system_colors'] = $system_items;

            if (!$kit_settings) {
                update_metadata('post', $kit_id, $meta_key, $wgl_settings);
            } else {
                $kit_settings = array_merge($kit_settings, $wgl_settings);
                $page_settings_manager->save_settings($kit_settings, $kit_id);
            }
            update_option( 'elementor_active_full_import', 'yes' );

            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }

    add_action('wgl_importer_elementor_default_kit', 'wgl_default_kits_init' ,10);
}
