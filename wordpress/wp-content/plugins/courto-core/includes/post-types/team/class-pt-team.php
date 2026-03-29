<?php

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

/**
 * Team CPT
 *
 *
 * @package courto-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Team
{
    private $type = 'team';
    private $slug;
    private $team_singular;
    private $team_archive;
    private $name;
    private $singular_name;
    private $plural_name;

    public function __construct()
    {
        $this->name = esc_html__( 'Team', 'courto-core' );
        $this->singular_name = esc_html__( 'Team Member', 'courto-core' );
        $this->plural_name = esc_html__( 'Team Members', 'courto-core' );

        $this->slug = WGL_Framework::get_option( 'team_slug' ) ?: 'team';
        $this->team_singular = (bool)WGL_Framework::get_option('team_singular');
        $this->team_archive = (bool)WGL_Framework::get_option('team_archives');

        add_action( 'init', [ $this, 'register_taxonomy_category' ] );
        add_action( 'init', [ $this, 'register_cpt' ] );

        if ((!$this->team_singular || !$this->team_archive)) {
            add_action( 'template_redirect', [$this, 'team_redirect'] );
        }

        add_filter( 'single_template', [ $this, 'get_cpt_single_template' ] );
        add_filter( 'archive_template', [ $this, 'get_cpt_archive_template' ] );
    }

    public function register_cpt()
    {
        $labels = [
            'name' => $this->name,
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
            'menu_name' => $this->name
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'rewrite' => [ 'slug' => $this->slug ],
            'capability_type' => 'post',
            'menu_position' => 14,
            'menu_icon' => 'dashicons-groups',
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'page-attributes',
            ],
            'taxonomies' => [
                $this->type . '_category',
            ],
            'has_archive' => true,
        ];

        register_post_type( $this->type, $args );
    }

    public function team_redirect()
    {
        if (
            class_exists('Elementor\Plugin')
            && \Elementor\Plugin::$instance->preview->is_preview_mode(get_the_ID())
        ) {
            return;
        }

        if (!$this->team_archive && !$this->team_singular) {
            if ( !is_singular($this->slug) && !is_post_type_archive($this->slug))
                return;

            wp_redirect( home_url() );
        }

        if (!$this->team_singular) {
            if ( !is_singular($this->slug) )
                return;

            wp_redirect( get_post_type_archive_link($this->slug), 301 );
        }

        if (!$this->team_archive) {
            if ( !is_post_type_archive($this->slug) )
                return;

            wp_redirect( home_url() );
        }

        exit;
    }

    public function register_taxonomy_category()
    {
        $labels = [
            'name' => sprintf( esc_html__( '%1$s Categories', 'courto-core' ), $this->name ),
            'menu_name' => sprintf( esc_html__( '%1$s Categories', 'courto-core' ), $this->name ),
            'singular_name' => sprintf( esc_html__( '%1$s Category', 'courto-core' ), $this->name ),
            'search_items' =>  sprintf( esc_html__( 'Search %1$s Categories', 'courto-core' ), $this->name ),
            'all_items' => sprintf( esc_html__( 'All %1$s Categories', 'courto-core' ), $this->name ),
            'parent_item' => sprintf( esc_html__( 'Parent %1$s Category', 'courto-core' ), $this->name ),
            'parent_item_colon' => sprintf( esc_html__( 'Parent %1$s Category:', 'courto-core' ), $this->name ),
            'new_item_name' => sprintf( esc_html__( 'New %1$s Category Name', 'courto-core' ), $this->name ),
            'add_new_item' => sprintf( esc_html__( 'Add New %1$s Category', 'courto-core' ), $this->name ),
            'edit_item' => sprintf( esc_html__( 'Edit %1$s Category', 'courto-core' ), $this->name ),
            'update_item' => sprintf( esc_html__( 'Update %1$s Category', 'courto-core' ), $this->name ),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'publicly_queryable'  => $this->team_archive,
            'rewrite' => [ 'slug' => $this->slug . '-category' ],
        ];

        register_taxonomy( $this->type . '_category', [ $this->type ], $args );
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
    function get_cpt_single_template( $single_template )
    {
        global $post;

        if ( $post->post_type == $this->type ) {
            if ( file_exists( get_template_directory() . '/single-team.php' ) ) {
                return $single_template;
            }

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/single-team.php';
        }

        return $single_template;
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template */
    function get_cpt_archive_template( $archive_template )
    {
        global $post;

        if (
            is_post_type_archive( $this->type )
            || is_archive() && ! empty( $post->post_type ) && $this->type === $post->post_type
        ) {
            if ( file_exists( get_template_directory() . '/archive-team.php' ) ) {
                return $archive_template;
            }

            $archive_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/archive-team.php';
        }

        return $archive_template;
    }
}
