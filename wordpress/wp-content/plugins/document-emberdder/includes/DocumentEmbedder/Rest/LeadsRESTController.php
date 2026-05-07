<?php
namespace PPV\Rest;

class LeadsRESTController {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_action('wp_ajax_de_export_leads_csv', [$this, 'handle_export_csv']);
    }

    public function register_endpoints() {
        register_rest_route('docembedder/v1', '/leads/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_leads'],
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ]);
    }

    public function get_leads($request) {
        global $wpdb;
        $doc_id = intval($request['id']);
        $table = $wpdb->prefix . 'docembedder_leads';

        $leads = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE document_id = %d ORDER BY downloaded_at DESC",
            $doc_id
        ), ARRAY_A);

        return new \WP_REST_Response([
            'success' => true,
            'data'    => $leads,
            'doc_title' => get_the_title($doc_id)
        ], 200);
    }

    public function handle_export_csv() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $doc_id = isset($_GET['doc_id']) ? intval($_GET['doc_id']) : 0;
        if (!$doc_id) {
            wp_die('Invalid document ID');
        }

        check_admin_referer('de_export_leads_csv', 'nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'docembedder_leads';
        
        $leads = $wpdb->get_results($wpdb->prepare(
            "SELECT id, name, email, document_id, document_title, downloaded_at, ip_address FROM {$table} WHERE document_id = %d ORDER BY downloaded_at DESC",
            $doc_id
        ), ARRAY_A);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="doc-leads-' . $doc_id . '-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Email', 'Document ID', 'Document Title', 'Downloaded At', 'IP Address']);

        foreach ($leads as $lead) {
            fputcsv($output, $lead);
        }

        fclose($output);
        exit;
    }
}
