<?php

class DocumentLibrary {
    protected static $_instance = null;
    
    public function __construct() {
        add_action('admin_menu', [$this, 'bplde_submenu']);
    }

    
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function bplde_submenu(){
        add_submenu_page(
            'edit.php?post_type=ppt_viewer', 
            __("Document Library", "document-embedder"), 
            __("Document Library", "document-embedder"),   
            'manage_options',                  
            'document-library',              
            [$this, 'render_document_library_page']  
        );
    }

    public function render_document_library_page() {
        ?>
         <div class="bplde-" id="bpldeDocumentLibraryWrapper" data-is-premium="<?php echo de_fs()->can_use_premium_code(); ?>">
         </div>
        <?php
    }
}
DocumentLibrary::instance();