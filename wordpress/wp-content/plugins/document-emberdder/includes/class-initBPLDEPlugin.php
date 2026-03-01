<?php

class BPLDEDocumentEmbedder {
    
    public function __construct() {
        add_action('plugins_loaded', [$this, 'load_dependencies'], 5);
        add_action('init', [$this, 'init']);
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
