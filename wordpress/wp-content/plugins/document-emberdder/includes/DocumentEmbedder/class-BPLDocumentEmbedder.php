<?php

class BPLDocumentEmbedder {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'load_dependencies']);
        add_action('admin_init', [$this, 'assign_file_type_to_all']);
        add_action('plugins_loaded', [__CLASS__, 'load_textdomain']);
        add_action('admin_enqueue_scripts', [$this, 'ppv_admin_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'ppv_public_scripts']);
    }

    public function load_dependencies() {
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Model/SubMenus.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Helper/Functions.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Services/DocTemplate.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Services/Import.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Model/AnalogSystem.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Helper/DefaultArgs.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/PostType/PPTViewer.php');
        
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Services/Shortcode.php');
        require_once(BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Rest/getMeta.php');

        include_once BPLDE_PLUGIN_PATH . 'blocks.php';

        if (!class_exists('CSF')) {
            require_once BPLDE_PLUGIN_PATH . 'frameworks/Codestar/framework.php';
        }
        require_once BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/metabox.php';
        
    }

    public static function load_textdomain() {
        load_plugin_textdomain('ppv', false, dirname(__FILE__) . '/languages');
    }

    public function ppv_admin_scripts($page) {
        global $post;
        $screen = get_current_screen();
        if ($page === 'plugins.php' || $screen->post_type === 'ppt_viewer') {
            wp_enqueue_script('ppv-admin',  BPLDE_PLUGIN_DIR . 'assets/js/script.js', array(), BPLDE_VER);

            wp_localize_script('ppv-admin', 'ppvAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php')
            ));
            wp_enqueue_style('ppv-admin',  BPLDE_PLUGIN_DIR . 'assets/css/style.css', array(), BPLDE_VER);
        }

        if("ppt_viewer_page_bplde-dashboard" === $page) {
            wp_enqueue_script('bplde-dashboard',  BPLDE_PLUGIN_DIR . 'build/admin-dashboard.js', ['react', 'react-dom'], BPLDE_VER);
            wp_enqueue_style('bplde-dashboard',  BPLDE_PLUGIN_DIR . 'build/admin-dashboard.css', array(), BPLDE_VER);
        }

    }

    public function ppv_public_scripts() {
        wp_enqueue_script('ppv-public', BPLDE_PLUGIN_DIR . 'build/public.js', array(), BPLDE_VER);
        wp_enqueue_style('ppv-public', BPLDE_PLUGIN_DIR . 'build/public.css', array(), BPLDE_VER);
    }

    public function assign_file_type_to_all() {
        $args = array(
            'post_type'      => "ppt_viewer",
            'posts_per_page' => -1,
            'post_status'    => 'any',
        );
        $posts = get_posts($args);
    
        foreach ($posts as $post) {
            $data = get_post_meta($post->ID, 'ppv', true);
            $file_url = isset($data['doc']) ? $data['doc'] : 'Not Uploaded';
            if ($file_url) {
                $ext = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));
                if ($ext) {
                    wp_set_object_terms($post->ID, $ext, 'ppv_file_type', false);
                }
            }
        }
    }

}
