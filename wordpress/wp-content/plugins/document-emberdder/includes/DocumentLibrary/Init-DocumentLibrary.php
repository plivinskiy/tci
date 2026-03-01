<?php

class BPLDocumentLibrary {

    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('plugins_loaded', [$this, 'load_dependencies']);
        add_action('admin_enqueue_scripts', [$this, 'bplde_admin_scripts']);
        add_shortcode('document_library', [$this, 'bplde_document_library_shortcode']);

        // AJAX actions
        add_action('wp_ajax_bplde_save_document_library', [$this, 'bplde_save_document_library']);   
        add_action('wp_ajax_bplde_get_single', [$this, 'bplde_get_single']);
        add_action('wp_ajax_bplde_delete_document_library', [$this, 'bplde_delete_document_library']);
        add_action('wp_ajax_bplde_get_all', [$this, 'bplde_get_all']);
    }

    public function load_dependencies() {
        require_once BPLDE_PLUGIN_PATH . 'document-library-block.php';
        require_once BPLDE_PLUGIN_PATH . 'includes/functions.php';
        require_once BPLDE_PLUGIN_PATH . 'includes/DocumentLibrary/DocumentLibrary.php';
    }

    public function register_post_type() {
        register_post_type('document_library', [
            'label'        => 'Document Library',
            'public'       => true,
            'menu_position'=> 20,
            'supports'     => ['title'],
            'show_in_rest' => true,
            'show_in_menu' => false,
        ]);
    }

    public function bplde_admin_scripts($screen) {
        if ($screen === 'ppt_viewer_page_document-library') {
            $current_user_id = get_current_user_id();
            $nickname        = get_user_meta($current_user_id, 'nickname', true);

            wp_enqueue_script(
                'bplde-all-library-script',
                BPLDE_PLUGIN_DIR . 'build/all-library.js',
                ['react', 'react-dom', 'wp-media-utils', 'wp-components'],
                BPLDE_VER,
                true
            );
            wp_enqueue_style(
                'bplde-all-library-style',
                BPLDE_PLUGIN_DIR. 'build/all-library.css',
                ['wp-components'],
                BPLDE_VER
            );

            wp_localize_script('bplde-all-library-script', 'bpldeSettings', [
                'ajaxUrl'   => admin_url('admin-ajax.php'),
                'athorName' => $nickname,
                'adminUrl'  => admin_url(),
                'nonce'     => wp_create_nonce('bplde_nonce'),
            ]);
        }
    }

    public function bplde_save_document_library() {
        check_ajax_referer('bplde_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Unauthorized.']);
        }
    
        $id       = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $title    = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : 'Untitled';
        $settings = isset($_POST['settings']) ? json_decode(stripslashes($_POST['settings']), true) : [];

        $post_data = [
            'post_title'  => $title,
            'post_type'   => 'document_library',
            'post_status' => 'publish',
        ];
    
        if ($id > 0) {
            if (!current_user_can('edit_post', $id)) {
                wp_send_json_error(['message' => 'Unauthorized to edit this document.']);
            }

            $post_data['ID'] = $id;
            $result = wp_update_post($post_data, true);
        } else {
            $result = wp_insert_post($post_data, true);
        }
    
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        } else {
            update_post_meta($result, 'bplde_settings', $settings);

            wp_send_json_success([
                'id'       => $result,
                'settings' => $settings,
                'created'  => get_the_date('Y/m/d \a\t g:i a', $result)
            ]);
        }
    }

    public function bplde_get_single() {
        check_ajax_referer('bplde_nonce', 'nonce');

        $id = intval($_GET['id'] ?? 0);
    
        if (!$id) {
            wp_send_json_error(['message' => 'Invalid ID']);
        }

        $post = get_post($id);

        if (!$post) {
            wp_send_json_error(['message' => 'Post not found']);
        }
        
        if (!current_user_can('edit_post', $id)) {
            wp_send_json_error(['message' => 'Unauthorized.']);
        }
    
        $settings = get_post_meta($id, 'bplde_settings', true);
    
        wp_send_json_success([
            'id'       => $id,
            'title'    => $post->post_title,
            'settings' => $settings,
            'created'  => get_the_date('Y/m/d \a\t g:i a', $id)
        ]);
    }

    public function bplde_get_all() {
        check_ajax_referer('bplde_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Unauthorized.']);
        }
    
        $query = new WP_Query([
            'post_type'      => 'document_library',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'author'         => get_current_user_id(),
        ]);
    
        $items = [];
        foreach ($query->posts as $post) {
            $settings = get_post_meta($post->ID, 'bplde_settings', true);
    
            $items[] = [
                'id'       => $post->ID,
                'title'    => $post->post_title,
                'settings' => $settings,
                'created'  => get_the_date('Y/m/d \a\t g:i a', $post)
            ];
        }
    
        wp_send_json_success($items);
    }

    public function bplde_delete_document_library() {
        check_ajax_referer('bplde_nonce', 'nonce');
       
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(['message' => 'Invalid ID']);
        }

        if (!current_user_can('delete_post', $id)) {
            wp_send_json_error(['message' => 'Unauthorized to delete this document.']);
        }
        
        wp_delete_post($id, true);
        wp_send_json_success();
    }

    public function bplde_document_library_shortcode($atts) {
        $atts = shortcode_atts(['id' => 0], $atts);
        $id   = (int) $atts['id'];

        $post = get_post($id);

        if (!$post || $post->post_type !== 'document_library') {
            return '<p>Document Library not found.</p>';
        }

        $block = [
            'blockName'    => 'bpldl/document-library', 
            'attrs'        => ['selectedPostId' => $id],
            'innerBlocks'  => [],
            'innerHTML'    => '',
            'innerContent' => [],
        ];
        
        return render_block($block);
    }
}
