<?php

/*
 * Plugin Name: Document Embedder
 * Plugin URI:  http://documentembedder.com/
 * Description: Embed Any document easily in wordpress such as word, excel, powerpoint, pdf and more
 * Version:     2.0.5
 * Author:      bPlugins
 * Author URI:  http://bplugins.com
 * License:     GPLv3
 * Text Domain: document-embedder
 * Domain Path: /i18n
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'de_fs' ) ) {
    de_fs()->set_basename( false, __FILE__ );
} else {
    /* Some Set-up */
    define( 'BPLDE_VER', '2.0.5' );
    define( 'BPLDE_PRO_IMPORT', '1.0.0' );
    define( 'BPLDE_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'BPLDE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    define( 'BPLDE__FILE__', __FILE__ );
    define( 'BPLDE_IMPORT', '1.0.0' );
    if ( !function_exists( 'de_fs' ) ) {
        // Create a helper function for easy SDK access.
        function de_fs() {
            global $de_fs;
            if ( !isset( $de_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $de_fs = fs_dynamic_init( array(
                    'id'             => '19862',
                    'slug'           => 'document-emberdder',
                    'premium_slug'   => 'document-embedder-premium',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_f769b99599446975f5e64d7a6ffbc',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 7,
                        'is_require_payment' => false,
                    ),
                    'menu'           => array(
                        'slug'       => 'edit.php?post_type=ppt_viewer',
                        'first-path' => 'edit.php?post_type=ppt_viewer&page=bplde-dashboard#/welcome',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $de_fs;
        }

        // Init Freemius.
        de_fs();
        // Signal that SDK was initiated.
        do_action( 'de_fs_loaded' );
    }
    require_once BPLDE_PLUGIN_PATH . 'includes/class-initBPLDEPlugin.php';
    new BPLDEDocumentEmbedder();
}