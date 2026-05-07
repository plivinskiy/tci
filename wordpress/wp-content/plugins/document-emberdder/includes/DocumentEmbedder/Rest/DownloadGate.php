<?php
namespace PPV\Rest;

class DownloadGate {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function register_endpoints() {
        register_rest_route('docembedder/v1', '/gate-download', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_gate_download'],
            'permission_callback' => '__return_true'
        ]);
        
        // Register direct download endpoint for step 6 now as well to avoid duplication.
        register_rest_route('docembedder/v1', '/download/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'handle_direct_download'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function handle_gate_download(\WP_REST_Request $request) {
        global $wpdb;
        $name = sanitize_text_field($request->get_param('name'));
        $email = sanitize_email($request->get_param('email'));
        $document_id = intval($request->get_param('document_id'));

        if (empty($name) || empty($email) || !is_email($email) || empty($document_id)) {
            return new \WP_REST_Response(['success' => false, 'message' => 'Invalid data provided.'], 400);
        }

        $document_title = get_the_title($document_id);

        // Insert into db
        $inserted = $wpdb->insert(
            $wpdb->prefix . 'docembedder_leads',
            [
                'name' => $name,
                'email' => $email,
                'document_id' => $document_id,
                'document_title' => $document_title,
                'downloaded_at' => current_time('mysql'),
                'ip_address' => \PPV\Helper\Functions::get_client_ip()
            ],
            ['%s', '%s', '%d', '%s', '%s', '%s']
        );

        if ($inserted === false) {
            error_log("DE DEBUG: DB Insert FAILED for Gated Download. Error: " . $wpdb->last_error);
        } else {
            error_log("DE DEBUG: DB Insert SUCCESS for Gated Download. ID: " . $wpdb->insert_id);
        }

        // Increment count
        $count = (int) get_post_meta($document_id, '_de_download_count', true);
        update_post_meta($document_id, '_de_download_count', $count + 1);

        // Generate signed, time-limited URL
        $timestamp = time();
        $ip = \PPV\Helper\Functions::get_client_ip();
        $nonce = wp_hash($document_id . '|' . $ip . '|' . 'de_download', 'nonce');
        $url = rest_url("docembedder/v1/download/{$document_id}?de_nonce={$nonce}&t={$timestamp}");

        error_log("DE DEBUG: Gated download link generated: $url for IP: " . \PPV\Helper\Functions::get_client_ip());

        return new \WP_REST_Response(['success' => true, 'url' => $url], 200);
    }

    public function handle_direct_download(\WP_REST_Request $request) {
        $document_id = intval($request->get_param('id'));
        $nonce = $request->get_param('de_nonce');
        $timestamp = $request->get_param('t');
        $is_gate = !empty($timestamp);
        $ip = \PPV\Helper\Functions::get_client_ip();

        // Token Verification (UID-independent)
        $expected_token = wp_hash($document_id . '|' . $ip . '|' . 'de_download', 'nonce');
        if (!hash_equals($expected_token, $nonce)) {
            error_log("DE DEBUG: Token verification failed for doc: $document_id. Expected: $expected_token. Received: $nonce. IP: $ip");
            return new \WP_REST_Response('Forbidden - Invalid Token', 403);
        }

        // Limit Check
        $limit = get_post_meta($document_id, '_de_download_limit', true);
        if (!empty($limit) && (int)$limit > 0) {
            global $wpdb;
            $table = $wpdb->prefix . 'docembedder_leads';
            $downloaded_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$table} WHERE document_id = %d AND ip_address = %s",
                $document_id,
                $ip
            ));

            if ((int)$downloaded_count > (int)$limit) { 
                error_log("DE DEBUG: API limit reached. IP: $ip, Count: $downloaded_count, Limit: $limit");
                return new \WP_REST_Response('Download limit reached.', 403);
            }
        }

        // Note: Counter is already incremented in de_track_download (direct) or handle_gated_download (gated).
        // No need to increment here.

        // Get file path
        $data_array = get_post_meta($document_id, 'ppv', true);
        $doc_url = isset($data_array['doc']) ? $data_array['doc'] : '';
        
        if (empty($doc_url)) {
            return new \WP_REST_Response('File not found', 404);
        }

        $attachment_id = attachment_url_to_postid($doc_url);
        if ($attachment_id) {
            $file_path = get_attached_file($attachment_id);
        } else {
            $upload_dir = wp_upload_dir();
            $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $doc_url);
        }

        if (!file_exists($file_path)) {
            wp_redirect($doc_url);
            exit;
        }

        // Headers
        $custom_filename = isset($data_array['_de_download_filename']) ? $data_array['_de_download_filename'] : '';
        $filename = !empty($custom_filename) ? $custom_filename : basename($file_path);
        
        $behavior = isset($data_array['_de_download_behavior']) ? $data_array['_de_download_behavior'] : 'download';
        $disposition = ($behavior === 'newtab') ? 'inline' : 'attachment';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_path);
        finfo_close($finfo);

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: ' . $disposition . '; filename="' . esc_attr($filename) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        if (ob_get_level()) {
            ob_end_clean();
        }

        readfile($file_path);
        exit;
    }
}
new DownloadGate();
