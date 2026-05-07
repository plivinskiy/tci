<?php
use PPV\Helper\Functions;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CSF')) {
    return;
}

$parent_slug = 'edit.php?post_type=ppt_viewer';
$prefix = '_ppt_';


CSF::createOptions($prefix, [
    'menu_title' => esc_html__('Settings', 'ppv'),
    'menu_slug' => 'settings',
    'menu_type' => 'submenu',
    'menu_parent' => $parent_slug,
    'theme' => 'light',
    'framework_title' => 'Settings',
    'footer_credit' => 'Thanks for being with bPlugins'
]);

CSF::createSection($prefix, array(
    'title' => Functions::ppv_pro_title(esc_html__('Dropbox API', 'ppv-pro')),
    'fields' => array(
        Functions::upgrade_section(),
        Functions::ppv_lock_field(array(
            'id' => 'dropbox_app_key',
            'type' => 'text',
            'title' => esc_html__('Dropbox App Key', 'ppv-pro'),
        ), true, true),

    )
));

CSF::createSection($prefix, array(
    'title' => Functions::ppv_pro_title(esc_html__('Google Drive API', 'ppv-pro')),
    'fields' => array(
        Functions::upgrade_section(),
        Functions::ppv_lock_field(array(
            'id' => 'google_apikey',
            'type' => 'text',
            'title' => esc_html__('Google API key', 'ppv-pro'),
            'before' => '<p><a href="https://console.cloud.google.com/" target="_blank">Click Here</a> To Get Google Credentials</p>',
        ), false, true),
        Functions::ppv_lock_field(array(
            'id' => 'google_client_id',
            'type' => 'text',
            'title' => esc_html__('Google Client ID', 'ppv-pro'),
        ), true),
        Functions::ppv_lock_field(array(
            'id' => 'google_project_number',
            'type' => 'text',
            'title' => esc_html__('Google Project Number', 'ppv-pro'),
        ), true),
    )
));