<?php
namespace PPV\PostType;

class PPTViewer {
    protected static $_instance = null;
    protected $post_type = 'ppt_viewer';
    
    public function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
        add_action('init', [$this, 'register_post_type']);

        if(is_admin()){
            add_filter("manage_{$this->post_type}_posts_columns", [$this, 'postTypeColumns'], 1);
            add_action("manage_{$this->post_type}_posts_custom_column", [$this, 'postTypeContent'], 10, 2);
            add_action('edit_form_after_title', [$this, 'ppv_shortcode_area']);

            add_filter('post_row_actions', [$this, 'removeRowAction'], 10, 2);
            add_action('admin_head-post.php', [$this, 'ppv_hide_publishing_actions']);
            add_action('admin_head-post-new.php', [$this, 'ppv_hide_publishing_actions']);
            add_filter('gettext', [$this, 'ppv_change_publish_button'], 10, 2);
            add_filter('post_updated_messages', [$this, 'ppv_updated_messages']);
        }
    }

    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function register_post_type(){
        $cpt_title = __('Document Embedder', 'document-embedder');
        $show_in_menu = true;
        
        
        register_post_type( $this->post_type, array(
            'labels' => array(
                'name' => $cpt_title,
                'singular_name' => __( 'Document Embedder' ),
                'add_new' => __( 'Add New Doc' ),
                'add_new_item' => __( 'Add New Doc' ),
                'edit_item' => __( 'Edit' ),
                'new_item' => __( 'New item' ),
                'view_item' => __( 'View item' ),
                'search_items' => __( 'Search'),
                'not_found' => __( 'Sorry, we couldn\'t find the power point file you are looking for.' )
            ),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'menu_position' => 14,
            'show_in_rest' => true,
            'menu_icon' => BPLDE_PLUGIN_DIR .'assets/img/doc.png',
            'has_archive' => false,
            'hierarchical' => false,
            'capability_type' => 'page',
            'rewrite' => array( 'slug' => 'ppt_viewer' ),
            'supports' => array( 'title' ),
            'show_in_menu' => $show_in_menu
        ));
    }

    

    public function register_taxonomy() {
        $post_type = $this->post_type;
        $slug = 'ppv_document_tags';
        $title = 'Tags';
        $is_hierarchical = false;
        register_taxonomy(
            $slug,
            $post_type,
            array(
                'labels' => array(
                    'name' => $title . '',
                    'singular_name' => $title,
                    'search_items' => "Search " . $title . "s",
                    'all_items' => "All " . $title . "s",
                    'edit_item' => "Edit $title",
                    'update_item' => "Update $title",
                    'add_new_item' => "Add New $title",
                    'new_item_name' => "New $title Name",
                    'menu_name' =>  $title . 's'
                ),
                'hierarchical' => $is_hierarchical,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_menu' => false,
                'query_var' => true,
                'rewrite' => array('slug' => $slug),
                'show_in_rest' => true
            )
        );
        register_taxonomy(
            "ppv_file_type",
            $post_type,
            array(
                'labels' => array(
                    'name' => 'File Type',
                    'search_items' => "Search File Type",
                    'all_items' => "All File Types",
                ),
                'hierarchical' => $is_hierarchical,
                'show_ui' => false,
                'show_admin_column' => true,
                'show_in_menu' => false,
                'query_var' => true,
                'rewrite' => array('slug' => $slug),
                'show_in_rest' => true
            )
        );
    }
    
    public function postTypeColumns($columns) {
        $new = [
            'cb'                             => $columns['cb'],
            'title'                          => $columns['title'],
            'shortcode'                      => 'Shortcode',
            'taxonomy-ppv_document_tags'     => 'Tags',
            'taxonomy-ppv_file_type'         => 'File Type',
            'date'                           => $columns['date'],
        ];
        return $new;
    }

    public function postTypeContent($column_name, $post_id) {
        switch ( $column_name ) {
            case 'shortcode':
                echo '<div class="ppv_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[doc id=' . esc_attr($post_id) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
                break;
        }
    }

    function ppv_shortcode_area() {
        global $post;	
        if($post->post_type=='ppt_viewer'){ ?>
            <div class="ppv_playlist_shortcode">
                <div class="shortcode-heading">
                    <div class="icon"><img src="<?php echo esc_url(BPLDE_PLUGIN_DIR .'assets/img/doc.png') ?>" alt=""> <?php _e("Document Embedder", "ppv") ?></div>
                    <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e("Supports", "ppv") ?></a></div>
                </div>
                <div class="shortcode-left">
                    <h3><?php _e("Shortcode", "ppv") ?></h3>
                    <p><?php _e("Copy and paste this shortcode into your posts, pages and widget:", "ppv") ?></p>
                    <div class="shortcode" selectable>[doc id=<?php echo esc_attr($post->ID); ?>]</div>
                </div>
                <div class="shortcode-right">
                    <h3><?php _e("Template Include", "ppv") ?></h3>
                    <p><?php _e("Copy and paste the PHP code into your template file:", "ppv"); ?></p>
                    <div class="shortcode">&lt;?php echo do_shortcode('[doc id=<?php echo esc_html($post->ID); ?>]'); ?&gt;</div>
                </div>
            </div>
            <div style="background:black; color: white;padding:5px; font-size:16px;">
                <?php echo esc_html__('! Important : Document Embedder Plugin does not preview any documents in localhost. No worries, when you will live your site you will see all the document are previewing perfectly.', 'ppv') ?> 
            </div>
        <?php }
    }

    public function removeRowAction($row){
        global $post;
        if ($post->post_type == 'ppt_viewer') {
            unset($row['view']);
            unset($row['inline hide-if-no-js']);
        }
        return $row;
    }

    function ppv_hide_publishing_actions(){
        global $post;
        if($post && $post->post_type == $this->post_type){
            echo '<style type="text/css">#misc-publishing-actions,#minor-publishing-actions{display:none;}</style>';
        }
    }

    function ppv_change_publish_button( $translation, $text ) {
        if ( 'ppt_viewer' == get_post_type() && $text == 'Publish' ) {
            return 'Save';
        }
        return $translation;
    }

    function ppv_updated_messages($messages) {
        $messages['ppt_viewer'][1] = __('Updated', 'ppv');
        return $messages;
    }
}

PPTViewer::instance();
