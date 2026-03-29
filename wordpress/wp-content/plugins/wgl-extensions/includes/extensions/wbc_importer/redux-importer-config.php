<?php

if (!function_exists('wbc_extended_example')) {
    /**
     * Add menu and rev slider to demo content.
     * Set defaults settings.
     * 
     * @package     WBC_Importer - Extension for Importing demo content
     * @author      Webcreations907
     * @version     1.0
     */
    function wbc_extended_example($demo_active_import, $demo_directory_path)
    {
        reset($demo_active_import);
        $current_key = key($demo_active_import);

        /**
         * Slider(s) Import 
         */
        if (class_exists('RevSlider')) {
            $slider_array = [
                // Set sliders zip name
                'demo' => [
                    '1' => 'home-1.zip',
                    '2' => 'home-2.zip',
                ]
            ];
            if (
                !empty($demo_active_import[$current_key]['directory'])
                && array_key_exists($demo_active_import[$current_key]['directory'], $slider_array)
            ) {
                $slider_import = $slider_array[$demo_active_import[$current_key]['directory']];
                if (is_array($slider_import)) {
                    foreach ($slider_import as $key => $value) {
                        if (file_exists($demo_directory_path . $value)) {
                            $slider[$key] = new RevSlider();
                            $slider[$key]->importSliderFromPost(true, true, $demo_directory_path . $value);
                        }
                    }
                } elseif (file_exists($demo_directory_path . $slider_import)) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost(true, true, $demo_directory_path . $slider_import);
                }
            }
        }


        /**
         * Menu(s)
         */

        // Set menu name
        $menu_array = [
            'demo' => 'main'
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
            'demo' => 'Home',
        ];

        if (
            !empty($demo_active_import[$current_key]['directory'])
            && array_key_exists($demo_active_import[$current_key]['directory'], $home_pages)
        ) {
            $page = get_page_by_title($home_pages[$demo_active_import[$current_key]['directory']]);
            if (isset($page->ID)) {
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
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
            $cpt_support = ['page', 'post', 'portfolio', 'team', 'footer', 'side_panel', 'header'];
            update_option('elementor_cpt_support', $cpt_support);
        } else {
            $include_cpt = ['portfolio', 'team', 'footer', 'side_panel', 'header'];
            foreach ($include_cpt as $cpt) {
                if (!in_array($cpt, $cpt_support)) {
                    $cpt_support[] = $cpt;
                }
            }
            update_option('elementor_cpt_support', $cpt_support);
        }
        update_option('elementor_container_width', 1170);
        // Font Awesome Styles
        update_option('elementor_load_fa4_shim', 'yes');

        /**
         * WGL Defaults
         * */

        // Permalink Structure
        update_option('permalink_structure', "/%postname%/");
    }

    add_action('wbc_importer_after_content_import', 'wbc_extended_example', 10, 2);
}
