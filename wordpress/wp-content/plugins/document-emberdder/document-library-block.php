<?php

function bplDeIsPremium() {
	return de_fs()->can_use_premium_code() || false;
}

if (!class_exists('BPLDEPlugin')) {

    class BPLDEPlugin {

    public function __construct() {
        add_action('init', [__CLASS__, 'init']);
        add_action( "enqueue_block_assets", [$this, "bplDeBlockAssets"]);
    }

    public function bplDeBlockAssets() {
        wp_localize_script(
            "bpldl-document-library-editor-script",
            'bpldlData',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('bplde_nonce')
            ]
        );
        wp_localize_script(
            "bpldl-document-library-view-script",
            'bpldlData',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('bplde_nonce')
            ]
        );
    }

    public static function init() {
        register_block_type(__DIR__ . '/build/blocks/document-library');
        wp_set_script_translations('document-embedder', 'document-library', plugin_dir_path(__FILE__) . 'languages');
    }
    }
    new BPLDEPlugin();
}