<?php
/**
 * Template Importer Class
 *
 * @package WGL_Elementor_Templates\Custom_Library
 */

namespace WGL_Elementor_Templates\Custom_Library;
use WGL_Elementor_Templates\Custom_Library\Core\Library_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Template_Importer
 */
class Template_Importer {
    /**
	 * Holds Template DB instance.
	 *
	 * @var Templates_DB $templates_db
	 */
	public Library_Manager $templates_db;

    /**
     * Instance of this class.
     *
     * @var Template_Importer
     */
    private static $instance = null;

    /**
     * Get instance of this class.
     *
     * @return Template_Importer
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('admin_enqueue_scripts',  [ $this, 'enqueue' ]);
        add_action('wp_ajax_wgl_import_elementor_templates', [ $this, 'wgl_import_elementor_templates' ]);
    }

    protected static function sanitize_recursive( $data ) {
        if ( is_array( $data ) ) {
            return array_map( [ __CLASS__, 'sanitize_recursive' ], $data );
        }

        return sanitize_text_field( $data );
    }

    function wgl_import_elementor_templates() {
        check_ajax_referer('wgl_import_demo_nonce', 'security');
        
        try {
            if (!isset($_POST['template_file'])) {
                throw new \Exception(__('Template file not specified.', 'wgl-extensions'));
            }

            $template_file = self::sanitize_recursive( $_POST['template_file'] );
            
            // Initiate Library.
            $this->templates_db = Library_Manager::get_instance();
        
            // Import one template only
            $this->import_single_template($template_file);

            wp_send_json([
                'success' => true,
                'message' => esc_html__('Template imported: ', 'wgl-extensions') . basename($template_file['elementor_template']),
            ]);
        } catch (\Exception $e) {
            wp_send_json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        wp_die();
    }

    function upload_and_replace_images($content) {
        if (is_array($content)) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        }

        if (preg_match_all('/https?:\/\/[^\"\'\s]+(?:jpg|jpeg|png|gif|webp)/i', $content, $matches)) {
            foreach ($matches[0] as $image_url) {
                $new_url = $this->upload_image_to_media_library($image_url);
                if ($new_url) {
                    $content = str_replace($image_url, $new_url, $content);
                }
            }
        }

        // Convert back to array if it was originally an array
        if (is_array(json_decode($content, true))) {
            $content = json_decode($content, true);
        }

        return $content;

    }

    function upload_image_to_media_library($image_url) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
    
        // Download the image and get the local path
        $temp_file = download_url($image_url);
        if (is_wp_error($temp_file)) {
            return false;
        }
    
        $file_info = pathinfo($image_url);
        $file_name = $file_info['basename'];
        $file_type = wp_check_filetype($file_name);
    
        // Prepare the file array
        $file = [
            'name'     => $file_name,
            'type'     => $file_type['type'],
            'tmp_name' => $temp_file,
            'error'    => 0,
            'size'     => filesize($temp_file),
        ];
    
        // Upload to media library
        $attachment_id = media_handle_sideload($file, 0);
    
        if (is_wp_error($attachment_id)) {
            @unlink($temp_file);
            return false;
        }
    
        return wp_get_attachment_url($attachment_id);
    }

    public function custom_elementor_placeholder_image() {
        return plugins_url( 'assets/images/placeholder.png', __FILE__ );
    }

    /**
     * Import a single template file.
     *
     * @param string $template_file Path to template file.
     */
    public function import_single_template( $json ) {
        // Get the template filename and meta information
        $template_filename = $json['elementor_template'];
        $meta_info = $json['meta'];
        
        if ( empty( $template_filename ) ) {
            return;
        }
    
        // Get the Elementor Template Manager
        $template_manager = \Elementor\Plugin::$instance->templates_manager;
    
        // Load the template content (assuming the template is stored in a specific location)
        $template_path = WGL_CORE_PATH . 'includes/wgl_elementor_templates/json/' . $template_filename;

        // Check if the template file exists
        if ( ! file_exists( $template_path ) ) {
            return;
        }

        // Get the contents and decode JSON
        $json_content = file_get_contents( $template_path );
        $template_data = json_decode( $json_content, true );
        
        if ( empty( $template_data['title'] ) ) {
            return; // No title found in JSON, skip import
        }
        
        $template_title = $template_data['title'];
        
        // Check if a template with the same title already exists using WP_Query
        $existing = new \WP_Query([
            'post_type' => 'elementor_library',
            'title'     => $template_title,
            'posts_per_page' => 1,
            'fields'    => 'ids',
        ]);

       
        if ( $existing->have_posts() ) {
            // Template with this title already exists
            return;
        }
    
        $file_name = basename($template_path);

        // Add the filter only for this AJAX request
        add_filter('elementor/utils/get_placeholder_image_src', [ $this, 'custom_elementor_placeholder_image' ]);

        $post_id = \Elementor\Plugin::$instance->templates_manager->import_template([
            'fileData' => base64_encode(file_get_contents($template_path)),
            'fileName' => $file_name
        ]);
    
        if ( is_wp_error( $post_id ) ) {
            return;
        }

        $post_id = $post_id[0]['template_id'];       
    
        // Set terms from tags if they exist
        if ( ! empty( $meta_info['tags'] ) && is_array( $meta_info['tags'] ) ) {
            $taxonomy = 'elementor_library_category';
    
            // Create terms if they don't exist
            foreach ( $meta_info['tags'] as $tag ) {
                if ( ! term_exists( $tag, $taxonomy ) ) {
                    wp_insert_term( $tag, $taxonomy );
                }
            }
    
            // Set terms using names directly
            wp_set_object_terms( $post_id, $meta_info['tags'], $taxonomy );
        }
    
        $this->replace_url($post_id);

        // Import featured image if exists
        if ( ! empty( $meta_info['thumbnail'] ) ) {
            $thumbnail_url = $meta_info['thumbnail'];
            $full_path = WGL_CORE_PATH . 'includes/wgl_elementor_templates/images/' . $thumbnail_url;
            
            if ( file_exists( $full_path ) ) {
                // Insert image into Media Library
                $filetype = wp_check_filetype( basename( $full_path ), null );

                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name( basename( $full_path ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $upload_dir = wp_upload_dir();
                $destination = trailingslashit( $upload_dir['path'] ) . basename( $full_path );

                if ( ! file_exists( $destination ) ) {
                    copy( $full_path, $destination );
                }

                $attachment_id = wp_insert_attachment( $attachment, $destination, $post_id );

                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $attach_data = wp_generate_attachment_metadata( $attachment_id, $destination );
                wp_update_attachment_metadata( $attachment_id, $attach_data );

                if ( ! is_wp_error( $attachment_id ) ) {
                    set_post_thumbnail( $post_id, $attachment_id );
                }
            }else{
                $image_id = media_sideload_image( $thumbnail_url, $post_id, 'Featured Image', 'id' );
                if ( ! is_wp_error( $image_id ) ) {
                    set_post_thumbnail( $post_id, $image_id );
                }                
            }
        }
    
        // Optionally, you can add the template to the library manually if needed
        $this->templates_db->add_template_to_library( $post_id );
    }

    public function replace_url( $post_id ) {
        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
        $elementor_data = json_decode( $elementor_data, true );
    
        if ( ! is_array( $elementor_data ) ) {
            return;
        }
    
        $elementor_data = $this->replace_links_recursive( $elementor_data );
    
        update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $elementor_data ) ) );
    
        \Elementor\Plugin::$instance->posts_css_manager->clear_cache( $post_id );
    }

    private function replace_links_recursive( array &$elements ) {
        foreach ( $elements as $key => &$value ) {
    
            // Recursively process nested arrays
            if ( is_array( $value ) ) {
                $this->replace_links_recursive( $value );
            }
    
            // Replace raw URL strings
            elseif ( is_string( $value ) && filter_var( $value, FILTER_VALIDATE_URL ) ) {
                if (
                    strpos( $value, 'wgl-dsites.net' ) !== false &&
                    strpos( $value, '/wp-content/uploads' ) === false
                ) {
                    $value = '#';
                }
            }
    
            // Replace URLs in anchor tags
            elseif ( is_string( $value ) && strpos( $value, '<a ' ) !== false ) {
                $value = preg_replace_callback(
                    '/<a\s+[^>]*href=([\'"])(https?:\/\/[^\'"]+)\1/i',
                    function ( $matches ) {
                        $url = $matches[2];
                        if (
                            strpos( $url, 'wgl-dsites.net' ) !== false &&
                            strpos( $url, '/wp-content/uploads' ) === false
                        ) {
                            return str_replace( $url, '#', $matches[0] );
                        }
                        return $matches[0];
                    },
                    $value
                );
            }
        }
    
        return $elements;
    }

    /**
     * Import template images.
     *
     * @param int   $post_id Post ID.
     * @param array $images  Images data.
     */
    public function import_template_images( $post_id, $images ) {
        foreach ( $images as $image ) {
            $image_id = media_sideload_image( $image['url'], $post_id, $image['title'], 'id' );
            if ( ! is_wp_error( $image_id ) ) {
                $content = get_post_meta( $post_id, '_elementor_data', true );
                $content = str_replace( $image['url'], wp_get_attachment_url( $image_id ), $content );
                update_post_meta( $post_id, '_elementor_data', $content );
            }
        }
    }

    public function enqueue($hook)
    {
        if ($hook !== 'toplevel_page_wgl-theme-options-panel') {
            return;
        }

        wp_enqueue_script(
            'redux-field-elementor-library-importer',
            WGL_LIBRARY_PLUGIN_URL . 'assets/js/import-elementor-templates.js',
            array('jquery'),
            time(),
            true
        );
        
        $vars = array(
            'message'   => esc_html__( 'Are you sure you want to import default templates?', 'wgl-extensions' ),
            'defaultText'   => esc_html__( 'Import', 'wgl-extensions' ),
            'activeImportText'   => esc_html__( 'Importing...', 'wgl-extensions' ),
            'ajaxurl'  => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('wgl_import_demo_nonce'),
            'completeMessage'   => esc_html__('All WGL Elementor templates have been imported successfully!', 'wgl-extensions'),
            'templates'         => get_option('wgl_elementor_custom_library_default'),
        );

        wp_localize_script( 'redux-field-elementor-library-importer', 'wglElementorLibrary', $vars );

        wp_enqueue_style(
            'redux-field-elementor-library-importer',
            WGL_LIBRARY_PLUGIN_URL . 'assets/css/import-elementor-templates.css',
            time(),
            true
        );
    }
} 

new Template_Importer();