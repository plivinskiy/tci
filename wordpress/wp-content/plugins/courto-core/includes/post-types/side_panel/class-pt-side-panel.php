<?php

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

/**
 * Side Panel CPT
 *
 * @package courto-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class SidePanel
{
    private $type = 'side_panel';
    private $slug;
    private $singular_name;
    private $plural_name;

    public function __construct()
    {
        $this->slug = 'side-panel';
        $this->singular_name = esc_html__( 'Side Panel', 'courto-core' );
        $this->plural_name = esc_html__( 'Side Panels', 'courto-core' );

        add_action( 'init', [ $this, 'register_cpt' ] );

        add_filter( 'single_template', [ $this, 'get_custom_pt_single_template' ] );
    }

    public function register_cpt()
    {
        $labels = [
            'name' => $this->singular_name,
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
            'menu_name' => $this->singular_name
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'rewrite' => [ 'slug' => $this->slug ],
            'menu_position' => 11,
            'supports' => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
            'menu_icon' => 'dashicons-admin-page',
        ];

        register_post_type( $this->type, $args );
    }

    /**
     * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
     */
    function get_custom_pt_single_template( $single_template )
    {
        global $post;

        if ( $post->post_type == $this->type ) {

            if ( defined( 'ELEMENTOR_PATH' ) ) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if ( file_exists( $elementor_template ) ) {
                    add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'wrapper_open' ] );
                    add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'wrapper_close' ] );

                    return $elementor_template;
                }
            }

            if ( file_exists( get_template_directory() . '/single-side-panel.php' ) ) {
                return $single_template;
            }

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'side_panel/templates/single-side-panel.php';
        }

        return $single_template;
    }

    public function wrapper_open()
    {
        echo '<section id="side-panel" class="side-panel side-panel_open">',
            '<div class="side-panel_sidebar">';
    }

    public function wrapper_close()
    {
            echo '</div>';
        echo '</section>';
    }
}
