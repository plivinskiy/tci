<?php
namespace PPV\Helper;

class Functions
{

    public static function meta($id, $key, $default = false)
    {
        $meta = get_post_meta($id, 'ppv', true);
        if (isset($meta[$key])) {
            return $meta[$key];
        }
        else {
            return $default;
        }
    }

    public static function ppv_lock_field($field, $is_section = false, $has_doc = false)
    {

        if (de_fs()->can_use_premium_code()) {
            return $field;
        }

        // Lock the UI
        $field['class'] = 'ppv-lock-field ' . ($is_section ? 'section' : '') . ($has_doc ? ' has-doc' : '');

        if ($has_doc) {
            $field['after'] = '<a href="https://bplugins.com/docs/document-embedder/getting-started/" target="_blank" class="ppv-doc-link">Read Documentation</a>';
        }

        // Force safe default (prevents DB pollution)
        if (isset($field['default'])) {
            $field['value'] = $field['default'];
        }

        return $field;
    }

    public static function ppv_pro_title($title)
    {
        if (de_fs()->can_use_premium_code()) {
            return esc_html($title);
        }

        return '
            <div class="ppv-field-title">
                <h4>' . esc_html($title) . '</h4>
                <span class="ppv-pro-badge">PRO</span>
            </div>
        ';
    }

    public static function upgrade_section()
    {
        return array(
            'type' => 'content',
            'content' => '<div class="bplde-metabox-upgrade-section">The Ultimate Document Embedder Plugin for WordPress, Loved by Over 10,000+ Users. <a class="button button-bplugins" href="' . admin_url('edit.php?post_type=ppt_viewer&page=document-emberdder-pricing') . '">Upgrade to PRO</a>
            </div>'
        );
    }

    public static function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        else if (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = explode(',', $_SERVER['HTTP_FORWARDED_FOR'])[0];
        else if (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return trim($ipaddress);
    }
}