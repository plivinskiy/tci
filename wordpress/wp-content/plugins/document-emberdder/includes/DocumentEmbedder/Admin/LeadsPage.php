<?php
namespace PPV\Admin;

class LeadsPage {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'handle_export']);
        add_action('admin_init', [$this, 'handle_bulk_delete']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    public function enqueue_styles($hook) {
        if ($hook !== 'ppt_viewer_page_ppv-download-leads') {
            return;
        }
        
        // Add inline styles for the minimal custom table
        wp_add_inline_style('wp-admin', '
            .ppv-custom-table-container { 
                margin-top: 20px; 
                background: #fff; 
                border: 0; 
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); 
                border-radius: 8px;
                padding: 24px;
            }
            .ppv-header-actions { 
                display: flex; 
                justify-content: space-between; 
                align-items: center;
                margin-bottom: 24px;
            }
            .ppv-header-left, .ppv-header-right {
                display: flex;
                gap: 12px;
                align-items: center;
            }
            .ppv-btn { 
                display: inline-flex; 
                align-items: center; 
                justify-content: center; 
                height: 38px; 
                padding: 0 18px; 
                border-radius: 6px; 
                font-weight: 500; 
                font-size: 13px; 
                text-decoration: none; 
                cursor: pointer; 
                transition: all 0.2s ease; 
                border: 1px solid transparent;
            }
            .ppv-btn-back { 
                background: #f3f4f6; 
                color: #374151; 
                border-color: #d1d5db;
            }
            .ppv-btn-back:hover { 
                background: #e5e7eb; 
                color: #1f2937;
            }
            .ppv-btn-title { 
                background: #eff6ff; 
                color: #1d4ed8; 
                border-color: #bfdbfe;
            }
            .ppv-btn-title:hover { 
                background: #dbeafe; 
                color: #1e3a8a;
            }
            .ppv-btn-export { 
                background: #10b981; 
                color: #fff; 
            }
            .ppv-btn-export:hover { 
                background: #059669; 
                color: #fff;
            }
            .ppv-btn-filter {
                background: #3b82f6; 
                color: #fff;
            }
            .ppv-btn-filter:hover {
                background: #2563eb; 
                color: #fff;
            }
            .ppv-btn-clear {
                background: transparent;
                color: #6b7280;
                border-color: #d1d5db;
            }
            .ppv-btn-clear:hover {
                color: #374151;
                background: #f9fafb;
            }
            .ppv-btn-delete {
                background: transparent;
                color: #ef4444;
                border: 1px solid #fca5a5;
            }
            .ppv-btn-delete:hover {
                background: #fee2e2;
                color: #dc2626;
            }
            .ppv-filters-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 20px;
                border-bottom: 1px solid #f3f4f6;
            }
            .ppv-filters-left {
                display: flex;
                gap: 12px;
                align-items: center;
            }
            .ppv-input {
                height: 38px;
                border-radius: 6px;
                border: 1px solid #d1d5db;
                padding: 0 12px;
                font-size: 13px;
                box-shadow: none;
                color: #374151;
            }
            .ppv-input:focus {
                border-color: #3b82f6;
                outline: none;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            .ppv-custom-table { 
                width: 100%; 
                border-collapse: separate; 
                border-spacing: 0;
                text-align: left; 
            }
            .ppv-custom-table th { 
                background: transparent; 
                padding: 14px 16px; 
                font-weight: 600; 
                color: #6b7280; 
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: 1px solid #e5e7eb; 
            }
            .ppv-custom-table td { 
                padding: 16px 16px; 
                border-bottom: 1px solid #f3f4f6; 
                color: #1f2937; 
                vertical-align: middle; 
                font-size: 14px;
            }
            .ppv-custom-table tbody tr {
                transition: background-color 0.15s ease;
            }
            .ppv-custom-table tbody tr:hover { 
                background: #f9fafb; 
            }
            .ppv-custom-table tbody tr:last-child td {
                border-bottom: none;
            }
            .ppv-checkbox {
                accent-color: #3b82f6;
                width: 16px;
                height: 16px;
                cursor: pointer;
            }
            .ppv-pagination { 
                margin-top: 24px;
                display: flex; 
                justify-content: flex-end; 
                gap: 6px; 
            }
            .ppv-pagination a, .ppv-pagination span { 
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 32px;
                height: 32px;
                padding: 0 8px; 
                border: 1px solid #e5e7eb; 
                background: #fff; 
                border-radius: 6px; 
                text-decoration: none; 
                color: #4b5563; 
                font-size: 13px;
                font-weight: 500;
                transition: all 0.2s ease;
            }
            .ppv-pagination .current { 
                background: #3b82f6; 
                color: #fff; 
                border-color: #3b82f6; 
            }
            .ppv-pagination a:hover { 
                border-color: #d1d5db;
                color: #1f2937;
            }
        ');
    }

    public function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=ppt_viewer',
            'Download Leads',
            'Download Leads',
            'manage_options',
            'ppv-download-leads',
            [$this, 'render_page']
        );
        
        remove_submenu_page('edit.php?post_type=ppt_viewer', 'ppv-download-leads');
    }
    
    public function handle_bulk_delete() {
        if (isset($_POST['delete_selected']) && isset($_POST['bulk_delete']) && is_array($_POST['bulk_delete'])) {
            if (!current_user_can('manage_options')) {
                return;
            }
            
            check_admin_referer('ppv_bulk_delete_leads');

            global $wpdb;
            $table = $wpdb->prefix . 'docembedder_leads';
            $ids = array_map('intval', $_POST['bulk_delete']);
            $ids_sql = implode(',', $ids);
            
            if ($ids_sql) {
                $wpdb->query("DELETE FROM {$table} WHERE id IN ($ids_sql)");
            }
            
            // Redirect back to clean up POST state but intentionally keeping any active GET parameters
            $redirect_url = remove_query_arg(['paged'], wp_get_referer());
            wp_safe_redirect($redirect_url);
            exit;
        }
    }

    public function handle_export() {
        if (isset($_GET['page']) && $_GET['page'] === 'ppv-download-leads' && isset($_GET['export_leads'])) {
            if (!current_user_can('manage_options')) {
                return;
            }

            global $wpdb;
            $table = $wpdb->prefix . 'docembedder_leads';
            
            $sql = "SELECT id, name, email, document_id, document_title, downloaded_at, ip_address FROM {$table} WHERE 1=1";
            
            if (!empty($_GET['filter_document_id'])) {
                $sql .= $wpdb->prepare(" AND document_id = %d", intval($_GET['filter_document_id']));
            }
            if (!empty($_GET['email_search'])) {
                $search = '%' . $wpdb->esc_like(sanitize_text_field($_GET['email_search'])) . '%';
                $sql .= $wpdb->prepare(" AND (email LIKE %s OR name LIKE %s)", $search, $search);
            }
            if (!empty($_GET['date_filter'])) {
                $date = sanitize_text_field($_GET['date_filter']);
                $sql .= $wpdb->prepare(" AND DATE(downloaded_at) = %s", $date);
            }
            
            $sql .= " ORDER BY downloaded_at DESC";
            
            $leads = $wpdb->get_results($sql, ARRAY_A);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="document-leads-' . date('Y-m-d') . '.csv"');
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

    public function render_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'docembedder_leads';
        
        $document_filter = isset($_REQUEST['filter_document_id']) ? intval($_REQUEST['filter_document_id']) : '';
        $email_search    = isset($_REQUEST['email_search']) ? sanitize_text_field($_REQUEST['email_search']) : '';
        $date_filter     = isset($_REQUEST['date_filter']) ? sanitize_text_field($_REQUEST['date_filter']) : '';
        
        $doc_title = '';
        if ($document_filter) {
            $doc_title = get_the_title($document_filter);
            if (!$doc_title) {
                $doc_title = "Document #" . $document_filter;
            }
        }

        $per_page = 20;
        $page_number = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$table} WHERE 1=1";
        if ($document_filter) {
            $sql .= $wpdb->prepare(" AND document_id = %d", $document_filter);
        }
        if ($email_search) {
            $search_term = '%' . $wpdb->esc_like($email_search) . '%';
            $sql .= $wpdb->prepare(" AND (email LIKE %s OR name LIKE %s)", $search_term, $search_term);
        }
        if ($date_filter) {
            $sql .= $wpdb->prepare(" AND DATE(downloaded_at) = %s", $date_filter);
        }
        
        $sql .= " ORDER BY downloaded_at DESC";
        $sql .= " LIMIT %d OFFSET %d";
        
        $leads = $wpdb->get_results($wpdb->prepare($sql, $per_page, ($page_number - 1) * $per_page), ARRAY_A);
        $total_items = $wpdb->get_var("SELECT FOUND_ROWS()");
        $total_pages = ceil($total_items / $per_page);
        
        // Export URL
        $export_args = ['export_leads' => '1'];
        if ($document_filter) $export_args['filter_document_id'] = $document_filter;
        if ($email_search) $export_args['email_search'] = $email_search;
        if ($date_filter) $export_args['date_filter'] = $date_filter;
        $export_url = add_query_arg($export_args, admin_url('edit.php?post_type=ppt_viewer&page=ppv-download-leads'));
        
        $back_url = admin_url('edit.php?post_type=ppt_viewer');
        $doc_edit_url = $document_filter ? get_edit_post_link($document_filter) : '#';

        ?>
        <div class="wrap" style="background: #f0f0f1;">
            <h1 class="wp-heading-inline">Download Leads</h1>
            <hr class="wp-header-end">
            
            <div class="ppv-custom-table-container">
                <form method="post" action="">
                    <?php wp_nonce_field('ppv_bulk_delete_leads'); ?>
                    <div class="ppv-header-actions">
                    <div class="ppv-header-left">
                        <a href="<?php echo esc_url($back_url); ?>" class="ppv-btn ppv-btn-back">
                            &larr; Back To Doc List
                        </a>
                        
                        <?php if ($document_filter && $doc_title): ?>
                            <a href="<?php echo esc_url($doc_edit_url); ?>" class="ppv-btn ppv-btn-title">
                                <?php echo esc_html($doc_title); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ppv-header-right">
                        <button type="submit" name="delete_selected" class="ppv-btn ppv-btn-delete"
                            onclick="return confirm('Are you sure you want to delete the selected leads? This cannot be undone.');">
                            Delete Selected
                        </button>
                        
                        <a href="<?php echo esc_url($export_url); ?>" class="ppv-btn ppv-btn-export">
                            <span style="margin-right: 6px;">&darr;</span> Export Data
                        </a>
                        
                    </div>
                </div>

                <div class="ppv-filters-row">
                        <div class="ppv-filters-left">
                            <input type="date" name="date_filter" value="<?php echo esc_attr($date_filter); ?>" class="ppv-input">
                            <input type="text" name="email_search" value="<?php echo esc_attr($email_search); ?>" placeholder="Search Email or Name..." class="ppv-input" style="width: 240px;">
                            <button type="submit" formaction="" formmethod="get" class="ppv-btn ppv-btn-filter">Filter</button>
                            
                            <!-- Important hidden inputs for GET method -->
                            <input type="hidden" name="post_type" value="ppt_viewer">
                            <input type="hidden" name="page" value="ppv-download-leads">
                            <?php if ($document_filter): ?>
                                <input type="hidden" name="filter_document_id" value="<?php echo esc_attr($document_filter); ?>">
                            <?php endif; ?>
                            
                            <?php if ($email_search || $date_filter): ?>
                                <?php 
                                    $clear_url = admin_url('edit.php?post_type=ppt_viewer&page=ppv-download-leads');
                                    if ($document_filter) {
                                        $clear_url = add_query_arg('filter_document_id', $document_filter, $clear_url);
                                    }
                                ?>
                                <a href="<?php echo esc_url($clear_url); ?>" class="ppv-btn ppv-btn-clear">Clear Filters</a>
                            <?php endif; ?>
                        </div>
                        
                        
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="ppv-custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" class="ppv-checkbox" onclick="document.querySelectorAll('.ppv-row-checkbox').forEach(cb => cb.checked = this.checked);"></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <?php if (!$document_filter): ?>
                                        <th>Document Title</th>
                                    <?php endif; ?>
                                    <th>IP Address</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($leads)): ?>
                                    <tr>
                                        <td colspan="<?php echo $document_filter ? 6 : 7; ?>" style="text-align: center; padding: 60px; color: #9ca3af;">
                                            No leads found matching your criteria.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($leads as $lead): ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <input type="checkbox" name="bulk_delete[]" value="<?php echo esc_attr($lead['id']); ?>" class="ppv-checkbox ppv-row-checkbox">
                                            </td>
                                            <td><strong style="color: #6b7280;">#<?php echo esc_html($lead['id']); ?></strong></td>
                                            <td><span style="font-weight: 500; color: #111827;"><?php echo esc_html($lead['name']); ?></span></td>
                                            <td><a href="mailto:<?php echo esc_attr($lead['email']); ?>" style="color: #3b82f6; text-decoration: none;"><?php echo esc_html($lead['email']); ?></a></td>
                                            <?php if (!$document_filter): ?>
                                                <td>
                                                    <?php 
                                                    $link = get_edit_post_link($lead['document_id']);
                                                    if ($link) {
                                                        printf('<a href="%s" style="color: #4b5563; text-decoration: none; font-weight: 500;">%s</a>', esc_url($link), esc_html($lead['document_title']));
                                                    } else {
                                                        echo esc_html($lead['document_title']);
                                                    }
                                                    ?>
                                                </td>
                                            <?php endif; ?>
                                            <td><span class="code" style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 12px; color: #4b5563;"><?php echo esc_html($lead['ip_address']); ?></span></td>
                                            <td><?php echo esc_html(wp_date(get_option('date_format') . ' \a\t ' . get_option('time_format'), strtotime($lead['downloaded_at']))); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                
                <?php if ($total_pages > 1): ?>
                    <div class="ppv-pagination">
                        <?php
                        echo paginate_links([
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&larr; Prev',
                            'next_text' => 'Next &rarr;',
                            'total' => $total_pages,
                            'current' => $page_number
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

new LeadsPage();

