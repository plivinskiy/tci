<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 * @package wgl-extensions\includes
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @link https://themeforest.net/user/webgeniuslab
 * @since 1.0.0
 */
class WGL_Extensions_Core
{
    /**
     * Loader for maintaining and registering all hooks that power.
     *
     * @var WGL_Extensions_Core_Loader
     */
    protected $loader;

    protected $plugin_name;
    protected $plugin_version;

    public $font_css;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->plugin_version = defined('WGL_CORE_VERSION') ? WGL_CORE_VERSION : '1.0.0';
        $this->plugin_name = 'wgl-extensions';

        $this->load_dependencies();
        $this->set_locale();

        $this->define_admin_hooks();
        $this->define_public_hooks();

        add_filter('the_content', [$this, 'fix_shortcodes_autop']);

        // Add Custom Fonts
		add_action('init', [$this, 'custom_fonts_setup']);
		add_action('admin_head', [$this, 'admin_css_reg'] );
    }

    /**
     * Setup Custom Fonts
     */
    public function custom_fonts_setup()
    {
        if (
            class_exists('Redux')
            && class_exists('Bsf_Custom_Fonts_Taxonomy')
        ) {
            $reduxArgs = new Redux;
            $reduxArgs = $reduxArgs::$args;
            $keys = array_keys($reduxArgs);
			$opt_name = $keys[0];
            add_filter('redux/' . $opt_name . '/field/typography/custom_fonts', [$this, 'fonts_redux_list']);
        }
    }

    public function fonts_redux_list($custom_fonts)
    {
        if (class_exists('Bsf_Custom_Fonts_Taxonomy')) {
            $all_fonts = (new Bsf_Custom_Fonts_Render)->get_existing_font_posts();
            if ( ! empty( $all_fonts ) ) {
                foreach ( $all_fonts as $key => $post_id ) {
                    $fontsData = get_post_meta( $post_id, 'fonts-data', true );
                    $custom_fonts['Custom Fonts'][$fontsData['font_name']] = $fontsData['font_name'];
                }
            }

            return $custom_fonts;
        }
    }

    private function render_font_css($font)
    {
        if (class_exists('Bsf_Custom_Fonts_Taxonomy')) {
            $fonts = Bsf_Custom_Fonts_Taxonomy::get_links_by_name($font);

            foreach ($fonts as $font => $links) {
                $css = '@font-face { font-family:' . esc_attr($font) . ';';
                $css .= 'src:';
                $arr = [];
                if (isset($links['font_woff_2'])) {
                    $arr[] = 'url(' . esc_url($links['font_woff_2']) . ") format('woff2')";
                }
                if (isset($links['font_woff'])) {
                    $arr[] = 'url(' . esc_url($links['font_woff']) . ") format('woff')";
                }
                if (isset($links['font_ttf'])) {
                    $arr[] = 'url(' . esc_url($links['font_ttf']) . ") format('truetype')";
                }
                if (isset($links['font_otf'])) {
                    $arr[] = 'url(' . esc_url($links['font_otf']) . ") format('opentype')";
                }
                if (isset($links['font_svg'])) {
                    $arr[] = 'url(' . esc_url($links['font_svg']) . '#' . esc_attr(strtolower(str_replace(' ', '_', $font))) . ") format('svg')";
                }
                $css .= join(', ', $arr);
                $css .= ';';
                $css .= 'font-display: ' .(isset($links['font-display'])) ? esc_attr($links['font-display']) : 'auto' . ';';
                $css .= '}';
            }
        }

        $this->font_css .= $css;
    }

    public function admin_css_reg()
    {
        if (class_exists('Bsf_Custom_Fonts_Taxonomy')) {
            $fonts = Bsf_Custom_Fonts_Taxonomy::get_fonts();
            if (!empty($fonts)) {
                foreach ($fonts as $load_font_name => $load_font) {
                    $this->render_font_css($load_font_name);
                }

                echo '<style>',
                    wp_strip_all_tags($this->font_css), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                '</style>';
            }
        }
    }

    /**
     * Create the ajax functionality of the plugin.
     */
    public function wgl_ajax_init()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wgl_ajax_nonce')) {
            wp_send_json_error(['message' => 'Unauthorized request'], 403);
        }

        // Global variables blog
        global $wgl_blog_atts;
        global $wgl_event_atts;
        global $wgl_products_atts;
        global $wgl_query_vars;

        $data = isset($_POST['data']) ? wp_unslash($_POST['data']) : [];
        $offset_items = isset($data['offset_items']) ? intval($data['offset_items']) : '';
        $items_load = isset($data['remainings_loading_btn_items_amount']) ? intval($data['remainings_loading_btn_items_amount']) : '';
        $js_offset = isset($data['js_offset']) ? intval($data['js_offset']) : '';
        $blog_style = isset($data['blog_style']) ? sanitize_text_field($data['blog_style']) : 'standard';
        $out = '';
        $allowed_post_types = ['post', 'portfolio', 'product', 'event', 'jobpost'];
        $post_type = isset($data['query_args']['post_type']) && in_array($data['query_args']['post_type'], $allowed_post_types, true) ? 
            $data['query_args']['post_type'] : 'post';

        $atts = $_POST['data']['atts'] ?? $_POST['data'];

        foreach($atts as $k => $v) {
            switch ($k) {
                case 'show_sale_products':
                    if ( ! empty( $v ) ) {
                        $atts['post_meta'] = true;
                        $meta_query = [
                            [
                                'key' => '_sale_price',
                                'value' => 0,
                                'compare' => '>',
                                'type' => 'numeric'
                            ],
                            [
                                'key' => '_min_variation_sale_price',
                                'value' => 0,
                                'compare' => '>',
                                'type' => 'numeric'
                            ]
                        ];
                        $atts['meta_query'] = $meta_query;                        
                    }
                    break;
            }
        }
        list($query_args) = \WGL_Extensions\Includes\WGL_Loop_Settings::buildQuery($atts);

        $query_args['post_type'] = $post_type;
        $query_args['order'] = $query_args['order'] ?? 'DESC';
        $query_args['orderby'] = $query_args['orderby'] ?? 'date';
        $query_args['offset'] = $offset_items;
        $query_args['post_status'] = 'publish';
        $query_args['posts_per_page'] = $items_load;

        $js_offset = (int) $js_offset + (int) $items_load;

        $query_args['update_post_meta_cache'] = false;
        $query_args['update_post_term_cache'] = false;

        switch ($post_type) {
            case 'product':
                $tax = [];
                $product_catalog_terms = wc_get_product_visibility_term_ids();
                $product_not_in = [$product_catalog_terms['exclude-from-catalog']];
                if (!empty($product_not_in)) {
                    $tax[] = [
                        'taxonomy' => 'product_visibility',
                        'field' => 'term_taxonomy_id',
                        'terms' => $product_not_in,
                        'operator' => 'NOT IN',
                    ];
                }

                if (!empty($_GET['orderby'])) {
                    $orderby_value = isset($_GET['orderby'])
                        ? wc_clean($_GET['orderby'])
                        : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

                    // Get order + orderby args from string
                    $orderby_value = explode('-', $orderby_value);
                    $orderby = esc_attr($orderby_value[0]);
                    $order = !empty($orderby_value[1]) ? $orderby_value[1] : $data['order'];

                    $orderby = strtolower($orderby);
                    $order = strtoupper($order);

                    $ordering_args = WC()->query->get_catalog_ordering_args($orderby, $order);
                    $meta_query = WC()->query->get_meta_query();

                    $query_args['orderby'] = $ordering_args['orderby'];
                    $query_args['order'] = $ordering_args['order'];

                    if ($ordering_args['meta_key']) {
                        $query_args['meta_key'] = $ordering_args['meta_key'];
                    }

                    if ('price' === $_GET['orderby']) {
                        $query_args['order'] = 'ASC';
                    }
                }

                $query_args['tax_query'][] = $tax;
                break;
        }

        $q = new WP_Query($query_args);

        if ($offset_items + $items_load >= (int) $q->found_posts) {
            $out .= "<div class='hidden_load_more'></div>";
        }

        $out .= "<div class='js_offset_items' data-offset='" . esc_attr($js_offset) . "'></div>";

        switch ($post_type) {
            case 'portfolio':
                $portfolio = new \WGL_Extensions\Templates\WGL_Portfolio($_POST['data'], false, $q);
                $out .= $portfolio->get_posts_html($offset_items);
                break;

            case 'jobpost':
                $jobpost = new \WGL_Extensions\Templates\WGL_Job_Board($_POST['data'], false, $q);
                $out .= $jobpost->get_posts_html();
                break;

            case 'event':
                $wgl_event_atts = $_POST['data'];
                $wgl_query_vars = $q;
                $out .= get_template_part('templates/events/events-list');
                break;

            case 'product':
                $wgl_products_atts = $_POST['data'];
                $wgl_query_vars = $q;
                $out .= get_template_part('templates/shop/products', 'grid');

                $out .= '<p class="woocommerce-result-count">';

                $paged = max(1, $q->get('paged'));
                $per_page = $offset_items + $items_load;
                $total = $q->found_posts;
                $first = ($per_page * $paged) - $per_page + 1;
                $last = min($total, ($offset_items + $items_load) * $paged);

                if (1 == $total) {
                    $out .= esc_html__('Showing the single result', 'wgl-extensions');
                } elseif ($total <= $per_page || -1 == $per_page) {
                    $out .= sprintf(esc_html__('Showing all %d results', 'wgl-extensions'), $total);
                } else {
                    $out .= sprintf(_x('Showing <strong>%1$d&ndash;%2$d</strong> of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'wgl-extensions'), $first, $last, $total);
                }

                $out .= '</p>';
                break;

            default:
                $wgl_blog_atts = $data;
                $wgl_query_vars = $q;
    
                $allowed_blog_styles = ['standard', 'hero', 'navigation', 'list', 'tiny_img', 'mega_menu', 'medium_img'];
                $blog_style = in_array($blog_style, $allowed_blog_styles, true) ? $blog_style : 'standard';
    
                ob_start();
                get_template_part('templates/post/post', $blog_style);
                $out .= ob_get_clean();
                break;
        }

        wp_reset_postdata();
        echo $out;

        unset($wgl_blog_atts);
        unset($wgl_event_atts);
        unset($wgl_products_atts);
        unset($wgl_query_vars);

        wp_die();
    }

    /**
     * Create the ajax functionality mega menu of the plugin.
     */
    public function wgl_mega_menu_load_ajax()
    {
        extract($_POST);

        // Global variables blog
        global $wgl_blog_atts;
        global $wgl_query_vars;

        $out = '';
        list($query_args) = \WGL_Extensions\Includes\WGL_Loop_Settings::buildQuery($_POST);

        $query_args['cat'] = $id;
        $query_args['order'] = 'DESC';
        $query_args['orderby'] = 'date';
        $query_args['post_status'] = 'publish';
        $query_args['posts_per_page'] = $posts_count;

        $query_args['no_found_rows'] = true;
        $query_args['update_post_meta_cache'] = false;
        $query_args['update_post_term_cache'] = false;

        $q = \WGL_Extensions\Includes\WGL_Loop_Settings::cache_query($query_args);

        $wgl_blog_atts = $_POST;
        $wgl_query_vars = $q;
        $out .= get_template_part('templates/post/post', 'mega_menu');

        wp_reset_postdata();

        $out .= "<div class='items_id' data-identifier='" . esc_attr($id) . "'></div>";
        echo $out;

        unset($wgl_blog_atts);
        unset($wgl_query_vars);

        wp_die();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        /** WGL Extensions Global Variables */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-extensions-global.php';

        /** Actions and filters of the core plugin. */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-extensions-loader.php';

        /** Internationalization functionality of the plugin. */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-extensions-i18n.php';

        /** Admin area actions. */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wgl-extensions-admin.php';

        /** Redux Framework. */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/framework/class.redux-plugin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/framework/init.php';

        /**
         * Redux Framework Loader
         * @see https://github.com/reduxframework/redux-extensions-loader
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/redux-extension-loader.php';

        /** WGL Importer 2.0 */
        if(defined('WGL_CORE_PATH') && is_dir(WGL_CORE_PATH  . '/includes/wgl_importer')){
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dashboard/class-wgl-importer.php';
        }

        /**
         * MetaBoxes IO.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box/meta-box.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/social_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/select_icon_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/heading_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/background_field/background_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/offset_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/font_field.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/meta-box-extensions/image-select_field.php';

        /**
         * Theme Helper
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/theme-helper-functions.php';

        /** Aqua Resizer */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/aq_resizer/aq_resizer.php';

        /** Elementor Plugin */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/elementor/init.php';

        /**
         * Include Elementor Custom Library.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-elementor-custom-library/wgl-elementor-custom-library.php';

        /**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname( __FILE__ )) . 'public/class-wgl-extensions-public.php';

        $this->loader = new WGL_Extensions_Core_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WGL_Extensions_Core_i18n class in order to set the domain and to register the hook
     * with WordPress.
     */
    private function set_locale()
    {
        $plugin_i18n = new WGL_Extensions_Core_i18n();

        $this->loader->add_action('init', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area.
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new WGL_Extensions_Core_Admin($this->get_plugin_name(), $this->get_plugin_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     */
    private function define_public_hooks()
    {
        $plugin_public = new WGL_Extensions_Core_Public($this->get_plugin_name(), $this->get_plugin_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        add_action('wp_ajax_wgl_ajax', [$this, 'wgl_ajax_init']);
        add_action('wp_ajax_nopriv_wgl_ajax', [$this, 'wgl_ajax_init']);

        add_action('wp_ajax_wgl_mega_menu_load_ajax', [$this, 'wgl_mega_menu_load_ajax']);
        add_action('wp_ajax_nopriv_wgl_mega_menu_load_ajax', [$this, 'wgl_mega_menu_load_ajax']);
    }

    /**
     * Fix Shortcode
     */
    public function fix_shortcodes_autop($content)
    {
        $array = [
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']'
        ];

        $content = strtr($content, $array);

        return $content;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return string The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return WGL_Extensions_Core_Loader
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * @return string The version number of the plugin.
     */
    public function get_plugin_version()
    {
        return $this->plugin_version;
    }
}