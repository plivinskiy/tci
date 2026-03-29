<?php

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

/**
 * Header Template CPT
 *
 * @package courto-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Header
{
    private $type = 'header';
    private $slug;
    private $singular_name;
    private $plural_name;

    public function __construct()
    {
        $this->slug = 'header';
        $this->singular_name = esc_html__( 'Header Template', 'courto-core' );
        $this->plural_name = esc_html__( 'Header Templates', 'courto-core' );

        add_action( 'init', [ $this, 'register_cpt' ] );

        add_filter( 'single_template', [ $this, 'get_cpt_single_template' ] );
    }

    public function register_cpt()
    {
        $labels = [
            'name' => $this->plural_name,
            'singular_name' => $this->singular_name,
            'add_new' => sprintf( esc_html__( 'Add New %1$s', 'courto-core' ), $this->singular_name ),
            'add_new_item' => sprintf( esc_html__( 'Add New %1$s', 'courto-core' ), $this->singular_name ),
            'edit_item' => sprintf( esc_html__( 'Edit %1$s', 'courto-core' ), $this->singular_name ),
            'new_item' => sprintf( esc_html__( 'New %1$s', 'courto-core' ), $this->singular_name ),
            'all_items' => sprintf( esc_html__( 'All %1$s', 'courto-core' ), $this->plural_name ),
            'view_item' => sprintf( esc_html__( 'View %1$s', 'courto-core' ), $this->singular_name ),
            'search_items' => sprintf( esc_html__( 'Search %1$s', 'courto-core' ), $this->plural_name ),
            'not_found' => sprintf( esc_html__( 'No %1$s found' , 'courto-core' ), strtolower( $this->plural_name ) ),
            'not_found_in_trash' => sprintf( esc_html__( 'No %1$s found in Trash', 'courto-core' ), strtolower( $this->plural_name ) ),
            'parent_item_colon' => '',
            'menu_name' => $this->plural_name
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'rewrite' => [ 'slug' => $this->slug ],
            'menu_position' => 10,
            'supports' => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
            'menu_icon' => 'dashicons-admin-page',
        ];

        register_post_type( $this->type, $args );
    }

    public function wrapper_header_open()
    {
        global $post;

        if ( $post->post_type == $this->type ) {
            echo "<header class='wgl-theme-header'>";
                echo "<div class='wgl-site-header'>";
                    echo "<div class='container-wrapper'>";
        }
    }

    public function wrapper_header_close()
    {
        global $post;

        if ( $post->post_type == $this->type ) {
                    echo '</div>';
                echo '</div>';
            echo '</header>';
        }
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
    function get_cpt_single_template( $single_template )
    {
        global $post;

        if ( $post->post_type == $this->type ) {

            if ( defined( 'ELEMENTOR_PATH' ) ) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if ( file_exists( $elementor_template ) ) {
                    add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'wrapper_header_open' ] );
                    add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'wrapper_header_close' ] );

                    return $elementor_template;
                }
            }

            if ( file_exists( get_template_directory() . '/single-header.php' ) ) {
                return $single_template;
            }

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'header/templates/single-header.php';
        }

        return $single_template;
    }
}
