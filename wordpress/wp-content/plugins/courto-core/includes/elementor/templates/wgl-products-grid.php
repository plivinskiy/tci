<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-products-grid.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\{WGL_Elementor_Helper, WGL_Loop_Settings, WGL_Carousel_Settings};
use WGL_Framework;

/**
 * WGL Elementor Products Grid Template
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLProductsGrid
{
    private $attributes;

    public function render($attributes = [], $self = false)
    {
        $this->attributes = $attributes;
        $_s = $this->attributes; // assign shorthand for attributes array
        $query = $this->formalize_query($_s);

        $wgl_def_atts = array(
            'query' => $query,
            // General
            'products_layout' => '',
            'products_title' => '',
            'products_subtitle' => '',
            // Content
            'products_columns' => '',
            'remainings_loading_btn_items_amount'  => '4',
            'products_style' => 'grid',
        );

        global $wgl_products_atts;
        $wgl_products_atts = array_merge($wgl_def_atts ,array_intersect_key($_s, $wgl_def_atts));
        $wgl_products_atts['post_count'] = $query->post_count;
        $wgl_products_atts['query_args'] = $query->query_vars;
        $wgl_products_atts['atts'] = $_s;

        echo '<div class="wgl_cpt_section wgl-products-grid woocommerce">';

        $this->render_header_section($_s);

        // Load the template orderby

        if((bool) $_s['show_header_products']){
            echo '<div class="wgl-woocommerce-sorting">';

            if((bool) $_s['show_res_count']){
                // Load the template result count.
                wc_get_template('addons/addons-result-count.php', [
                    'query' => $query,
                ]);
            }

            if((bool) $_s['show_sorting']){
                // Load the template orderby
                wc_get_template('addons/addons-orderby.php', [
                    'query' => $query,
                ]);
            }

            echo '</div>';
        }

        echo '<div class="wgl-products-catalog wgl-products-wrapper', $this->_get_wrapper_classes($_s), '">';

        echo '<div class="wgl-products container-grid', $this->_get_isotope_classes($_s), '">';

        if ('carousel' === $_s['products_layout']) {
            ob_start();
            get_template_part('templates/shop/products', 'grid');
            $products_items = ob_get_clean();
            echo $this->apply_carousel_settings($products_items, $_s);
        }else{
            get_template_part('templates/shop/products', 'grid');
        }

        echo '</div>';

        echo '</div>';

        $this->render_navigation_section($_s, $query);

        echo '</div>';

        unset($wgl_products_atts); // clear global var
    }

    public function render_header_section($_s) {

        ob_start();
        $this->products_double_headings($_s);
        $double_headings = ob_get_clean();

        $class = $_s['filter_alignment'] ? ' filter-' . $_s['filter_alignment'] : '';
        $class .= !empty($_s['filter_alignment_tablet']) ? ' filter-tablet-' . $_s['filter_alignment_tablet'] : '';
        $class .= !empty($_s['filter_alignment_mobile']) ? ' filter-mobile-' . $_s['filter_alignment_mobile'] : '';

        if (($_s['isotope_filter'] || !empty($double_headings)) && 'carousel' !== $_s['products_layout']) {
            echo '<div class="wgl-products_header wgl-cpt_header', esc_attr($class), '">',
            !empty($double_headings) ? $double_headings : '';
            if ($_s['isotope_filter']) {
                echo WGL_Framework::render_html($this->_render_filter($_s));
            }
            echo '</div>';
        }

    }

    public function support_archive_tax($atts = [])
    {
        global $post;
        if(is_tax() && is_archive() && ! empty( $post->post_type ) && 'product' === $post->post_type){
            $tax_obj = get_queried_object();
            $term_id = $tax_obj->term_id ?? '';
            $taxonomies = [];
            if ($term_id) {
                $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
            }
            $atts['taxonomies'] = $taxonomies;
        }

        return $atts;
    }

    protected function formalize_query($_s)
    {
        $_s = $this->support_archive_tax($_s);

        list($query_args) = WGL_Loop_Settings::buildQuery($_s);

        $query_args['post_type'] = 'product';

        //* Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = get_query_var('page') ?: 1;
        }
        $query_args['paged'] = $paged;

        $tax = array();
        $product_catalog_terms  = wc_get_product_visibility_term_ids();
        $product_not_in = array($product_catalog_terms['exclude-from-catalog']);
        if ( ! empty( $product_not_in ) ) {
            $tax[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_not_in,
                'operator' => 'NOT IN',
            );
        }

        if(isset($_GET['orderby']) && !empty($_GET['orderby'])){
            $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

            // Get order + orderby args from string
            $orderby_value = explode('-', $orderby_value);
            $orderby = esc_attr($orderby_value[0]);
            $order = ! empty( $orderby_value[1] ) ? $orderby_value[1] : '';

            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            $ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );
            $meta_query    = WC()->query->get_meta_query();

            $query_args['orderby'] = $ordering_args['orderby'];
            $query_args['order'] = $ordering_args['order'];

            if ( $ordering_args['meta_key'] ) {
                $query_args['meta_key']       = $ordering_args['meta_key'];
            }

            if ('price' === $_GET['orderby']) {
                $query_args['order'] = 'ASC';
            }
        }

        $query_args['tax_query'][] = $tax;

        return WGL_Loop_Settings::cache_query($query_args);
    }

    protected function _get_wrapper_classes($_s)
    {
        $class = 'carousel' === $_s['products_layout'] ? ' carousel' : '';

        return esc_attr($class);
    }

    protected function _get_isotope_classes($_s)
    {
        $class = '';
        if ('masonry' === $_s['products_layout'] || $_s['isotope_filter'] || $_s['products_navigation'] == 'load_more') {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
            $class = ' isotope';
        }
        $class .= 'grid' === $_s['products_layout'] ? ' fit_rows' : '';

        return esc_attr($class);
    }

    public function products_double_headings($_s)
    {
        $module_title = $_s['products_title'] ?? '';
        $module_subtitle = $_s['products_subtitle'] ?? '';

        if (!$module_title && !$module_subtitle) {
            // Bailout.
            return;
        }

        echo '<div class="item_title">';

        if ($module_subtitle) {
            echo '<div class="products_subtitle">', $module_subtitle, '</div>';
        }

        if ($module_title) {
            echo '<h3 class="products_title">', $module_title, '</h3>';
        }

        echo '</div>';
    }

    protected function _render_filter($_s)
    {
        list($query_args) = WGL_Loop_Settings::buildQuery($_s);
        $data_category = $query_args['tax_query'] ?? [];
        $include = $exclude = [];
        $class = $_s['filter_alignment'] ? ' filter-' . $_s['filter_alignment'] : '';
        $class .= !empty($_s['filter_alignment_tablet']) ? ' filter-tablet-' . $_s['filter_alignment_tablet'] : '';
        $class .= !empty($_s['filter_alignment_mobile']) ? ' filter-mobile-' . $_s['filter_alignment_mobile'] : '';
        $class .= $_s['filter_counter_enabled'] ? ' has_filter_counter' : '';

        if ( isset($data_category[0]) ) {
            foreach ($data_category[0]['terms'] as $value) {
                $idObj = get_term_by( 'slug', $value, 'product_cat' );
                $id_list[] = $idObj ? $idObj->term_id : '';
            }
            switch ($data_category[0]['operator']) {
                case 'NOT IN':
                    $exclude = implode(',', $id_list);
                    break;
                case 'IN':
                    $include = implode(',', $id_list);
                    break;
            }
        }
        $cats = get_terms( [
            'taxonomy' => 'product_cat',
            'include' => $include,
            'exclude' => $exclude,
            'hide_empty' => true
        ] );

        $page_transitions = '';
        if (class_exists('\ElementorPro\Modules\ThemeBuilder\Module')) {
            $page_transitions = ' data-e-disable-page-transition="true"';
        }

        $filter = '<div class="wgl-filter_wrapper product__filter isotope-filter'. esc_attr($class) . '">';
        $filter .= '<div class="swiper wgl-filter_swiper_wrapper">';
        $filter .= '<div class="swiper-wrapper">';
        $filter .= '<a href="#"'.$page_transitions.' data-filter=".product" class="swiper-slide active"><span class="cat_title">'.esc_html__('All', 'courto-core') .'</span>' . '<span class="filter_counter"></span></a>';
        foreach ( $cats as $cat ) {
            if ( $cat->count > 0 ) {
                $filter .= '<a class="swiper-slide" href="'.get_term_link($cat->term_id, 'product_cat').'"'.$page_transitions.' data-filter=".product_cat-'.$cat->slug.'">';
                $filter .= '<span class="cat_title">'.$cat->name.'</span>';
                $filter .= '<span class="filter_counter"></span>';
                $filter .= '</a>';
            }
        }
        $filter .= '</div>';
        $filter .= '</div>';
        $filter .= '</div>';

        return $filter;
    }

    protected function apply_carousel_settings($product_items, $_s)
    {
        $_s['products_gap'] = !empty($_s['products_gap']['size']) ? $_s['products_gap'] : ['size' => '30'];
        $_s['slides_per_row'] = $_s['grid_columns'];
        $_s['responsive_gap'] = [
            'desktop_gap' => $_s['products_gap'],
            'tablet_gap' => !empty($_s['products_gap_tablet']['size']) ? $_s['products_gap_tablet'] : $_s['products_gap'],
            'mobile_gap' => !empty($_s['products_gap_mobile']['size']) ? $_s['products_gap_mobile'] : $_s['products_gap'],
        ];

        return WGL_Carousel_Settings::init($_s, $product_items);
    }

    protected function render_navigation_section($_s, $query)
    {
        if ('pagination' === $_s['products_navigation']) {
            ?><nav class="woocommerce-pagination"><?php
            echo WGL_Framework::pagination($query);
            ?></nav><?php
        }

        if ('load_more' === $_s['products_navigation']) {
            global $wgl_products_atts;
            $wgl_products_atts['load_more_text'] = $_s['name_load_more'];
            $wgl_products_atts['load_more_media_type'] = $_s['load_more_media_type'];
            $wgl_products_atts['load_more_media_icon'] = $_s['load_more_media_icon'];

            WGL_Framework::render_load_more_button($wgl_products_atts);
        }
    }
}