<?php
namespace PPV\Model;

use PPV\Helper\Functions;
use PPV\Helper\DefaultArgs;
use PPV\Services\DocTemplate;

class AnalogSystem
{

    public static function html($id, $atts = [])
    {
        $data = self::doc($id, $atts);
        $data = DefaultArgs::parseArgs($data);
        return DocTemplate::html($data);
    }

    public static function doc($id, $atts = []) {
        $width = Functions::meta($id, 'width', ['width' => '100', 'unit' => '%']);
        $height = Functions::meta($id, 'height', ['height' => 600, 'unit' => 'px']);

        $result = [
            'doc' => Functions::meta($id, 'doc', ''),
            'width' => $width['width'] . $width['unit'],
            'height' => $height['height'] . $height['unit'],
            'width_tablet' => Functions::meta($id, 'width_tablet', ''),
            'width_mobile' => Functions::meta($id, 'width_mobile', ''),
            'height_tablet' => Functions::meta($id, 'height_tablet', ''),
            'height_mobile' => Functions::meta($id, 'height_mobile', ''),
            'showName' => Functions::meta($id, 'showName'),
            'download' => Functions::meta($id, 'download', '0'),
            'downloadButtonText' => Functions::meta($id, 'downloadButtonText', Functions::meta($id, '_de_download_label', 'Download')),
            '_de_download_position' => Functions::meta($id, '_de_download_position', 'toolbar'),
            '_de_download_behavior' => Functions::meta($id, '_de_download_behavior', 'download'),
            '_de_download_filename' => Functions::meta($id, '_de_download_filename', ''),
            '_de_download_show_count' => Functions::meta($id, '_de_download_show_count', '0'),
            '_de_download_access' => Functions::meta($id, '_de_download_access', 'everyone'),
            '_de_download_access_roles' => Functions::meta($id, '_de_download_access_roles', []),
            '_de_download_access_message' => Functions::meta($id, '_de_download_access_message', 'Access Denied'),
            '_de_email_gate' => Functions::meta($id, '_de_email_gate', '0'),
            '_de_download_limit' => Functions::meta($id, '_de_download_limit', '0'),
            'googleDrive' => Functions::meta($id, 'googleDrive', '0'),
            'disablePopout' => Functions::meta($id, 'disablePopout', '0'),
            'id' => $id
        ];

        if (empty($result['_de_download_label'])) {
            $result['_de_download_label'] = $result['downloadButtonText'] ? $result['downloadButtonText'] : 'Download';
        }

        $override_map = [
            'download' => 'download',
            'download_label' => '_de_download_label',
            'download_position' => '_de_download_position',
            'download_behavior' => '_de_download_behavior',
            'download_filename' => '_de_download_filename',
            'email_gate' => '_de_email_gate',
            'googleDrive' => 'googleDrive',
            'disablePopout' => 'disablePopout',
            'download_access' => '_de_download_access',
            'download_limit' => '_de_download_limit',
            'show_count' => '_de_download_show_count',
        ];
        foreach ($override_map as $att_key => $data_key) {
            if (isset($atts[$att_key])) {
                if (in_array($atts[$att_key], ['yes', 'true', '1'], true)) {
                    $result[$data_key] = '1';
                }
                elseif (in_array($atts[$att_key], ['no', 'false', '0'], true)) {
                    $result[$data_key] = '0';
                }
                else {
                    $result[$data_key] = $atts[$att_key];
                }
            }
        }

        // Check Limit per IP
        $limit = (int)$result['_de_download_limit'];
        $result['limit_reached'] = false;
        if ($limit > 0) {
            global $wpdb;
            $ip = Functions::get_client_ip();
            $table = $wpdb->prefix . 'docembedder_leads';
            $downloaded_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$table} WHERE document_id = %d AND ip_address = %s",
                $id,
                $ip
            ));

            if ((int)$downloaded_count >= $limit) {
                $result['limit_reached'] = true;
            }
            error_log("DE DEBUG: Limit Check for ID $id. IP: $ip, Count: $downloaded_count, Limit: $limit, Reached: " . ($result['limit_reached'] ? 'YES' : 'NO'));
        }
        else {
            error_log("DE DEBUG: Limit Check for ID $id SKIPPED (Limit is 0)");
        }

        return apply_filters('ppv_doc_data', $result, $id);
    }
}