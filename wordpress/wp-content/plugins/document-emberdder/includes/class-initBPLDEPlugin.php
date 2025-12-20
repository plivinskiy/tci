<?php

class BPLDEDocumentEmbedder {
    
    public function __construct() {
        add_filter( 'plugin_row_meta', [$this, 'pluginRowMeta'], 10, 2 );
        add_action('plugins_loaded', [$this, 'load_dependencies'], 5);
        add_action('init', [$this, 'init']);
    }

    function pluginRowMeta( $plugin_meta, $plugin_file ) {
        if ( strpos( $plugin_file, 'document-emberdder' ) !== false && time() < strtotime( '2025-12-06' ) ) {
            $new_links = array(
                'deal' => "<a href='https://bplugins.com/coupons/?from=plugins.php&plugin=document-embedder' target='_blank' style='font-weight: 600; color: #146ef5;'>🎉 Black Friday Sale - Get up to 80% OFF Now!</a>"
            );
            
            $plugin_meta = array_merge( $plugin_meta, $new_links );
        }
    
        return $plugin_meta;
    }

    public function load_dependencies() {
            $documentEmbedder = BPLDE_PLUGIN_PATH. 'includes/DocumentEmbedder/class-BPLDocumentEmbedder.php';
            $proDocumentEmbedder = BPLDE_PLUGIN_PATH. 'includes/DocumentEmbedder/class-BPLProDocumentEmbedder.php';
            $documentLibrary = BPLDE_PLUGIN_PATH. 'includes/DocumentLibrary/Init-DocumentLibrary.php';
            
            if ( file_exists($proDocumentEmbedder) && file_exists($documentEmbedder) && file_exists($documentLibrary) ) {
                require_once $documentEmbedder;
                require_once $documentLibrary;
                new BPLDocumentLibrary();
                new BPLDocumentEmbedder();
                if (de_fs()->can_use_premium_code()) {
                    require_once $proDocumentEmbedder;
                    new BPLProDocumentEmbedder();
                }
            }
            
    }

    public function init() {
    //    echo "BPLDEDocumentEmbedder Working Fine";

    }
}
