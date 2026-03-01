<?php
use PPV\Services\Import;
use PPV\Helper\Functions;

class BPLProDocumentEmbedder {

    public function __construct() {
        // Hooks
        add_action('admin_init', [$this, 'run_import']);
        // add_action('add_meta_boxes', [$this, 'add_love_metabox']);
        add_action( 'add_meta_boxes', [$this, 'add_love_metabox'] );
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'public_enqueue_assets']);
        add_action('admin_head', [$this, 'custom_admin_styles']);

        // Filters
        add_filter('ppv_pro_metabox', [$this, 'customize_metabox_fields']);
        add_filter('ppv_data_import', [$this, 'override_imported_data'], 10, 2);
        add_filter('ppv_doc_data', [$this, 'override_doc_data'], 10, 2);
        add_filter('ppv_settings', [$this, 'register_settings_page']);

        // Load other plugin parts
        $this->load_dependencies();
    }

    public function run_import() {
        if (get_option('ppv_pro_import', '0') < BPLDE_PRO_IMPORT) {
            if (class_exists('PPV\Services\Import')) {
                Import::import(true);
                update_option('ppv_pro_import', BPLDE_PRO_IMPORT);
            }
        }
    }

    public function add_love_metabox() {
        add_meta_box(
            'lovebox',
            esc_html__('Please show some love', 'ppv-pro'),
            [$this, 'bplde_lovebox_callback'],
            'ppt_viewer',
            'side'
        );
    }
    public function bplde_lovebox_callback() {
        echo 'If you like <strong>Document Embedder </strong> Plugin, please leave us a <a href="https://wordpress.org/support/plugin/document-emberdder/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733; rating.</a>';
        echo '<p>Need some improvement? <a href="mailto:abuhayat.du@gmail.com">Let me know</a> how we can improve.</p>';
    }

    public function customize_metabox_fields($fields) {
        $option = get_option( '_ppt_' );

        require_once BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Api/DropboxApi.php';
        require_once BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/Api/GoogleDriveApi.php';

        new \DropboxApi($option['dropbox_app_key'] ?? '');
        new \GoogleDriveApi(
            $option['google_apikey'] ?? '',
            $option['google_client_id'] ?? '',
            $option['google_project_number'] ?? ''
        );

        $metaboxs = [
            [
                'id' => 'dropbox_button',
                'type' => 'content',
                'content' => '<div id="picker_container"></div>'
            ],
            [
                'id'    => 'doc',
                'type'  => 'upload',
                'title' => esc_html__('Document', 'ppv-pro'),
                'attributes' => array('id' => 'picker_field')
            ],
            [
                'id' => 'googleDrive',
                'type' => 'switcher',
                'title' => esc_html__("I want to Use google drive file", 'ppv-pro')
            ],
            [
                'id' => 'width',
                'type' => 'dimensions',
                'title' => esc_html__('Width', 'ppv-pro'),
                'height' => false,
                'default' => ['width' => '100', 'unit' => '%']
            ],
            [
                'id' => 'height',
                'type' => 'dimensions',
                'title' => esc_html__('Height', 'ppv-pro'),
                'width' => false,
                'default' => ['height' => 600, 'unit' => 'px']
            ],
            [
                'id' => 'showName',
                'type' => 'switcher',
                'title' => esc_html__('Display File Name at the Top', 'ppv-pro'),
                'desc' => 'Not available for Google Drive and Dropbox',
                'default' => 0
            ],
            [
                'id' => 'download',
                'type' => 'switcher',
                'title' => esc_html__('Show Download Button', 'ppv-pro'),
                'desc' => esc_html__('Not available for Google Drive and Dropbox', 'ppv-pro'),
                'default' => false
            ],
            [
                'id' => 'downloadButtonText',
                'type' => 'text',
                'title' => esc_html__("Download Button Text", "ppv-pro"),
                'default' => "Download File",
                'dependency' => ['download', '==', 'true'],
            ],
            [
                'id' => 'disablePopout',
                'type' => 'switcher',
                'title' => esc_html__('Disable Popout', 'ppv-pro'),
                'desc' => esc_html__('Only available for google drive', 'ppv-pro'),
                'default' => false
            ],
            [
                'id' => 'loading_icon',
                'type' => 'switcher',
                'title' => esc_html__("Enable Loading Icon", "ppv-pro"),
            ],
            [
                'id' => 'lightbox',
                'type' => 'switcher',
                'title' => esc_html__("Enable Lightbox", "ppv-pro"),
            ],
            [
                'id' => 'lightbox_btn_text',
                'type' => 'text',
                'title' => esc_html__("Button Text"),
                'default' => 'View Document',
                'dependency' => ['lightbox', '==', '1']
            ],
            [
                'id' => 'lightbox_btn_color',
                'type' => 'color',
                'title' => esc_html__("Button Text Color", "ppv-pro"),
                'default' => '#fff',
                'dependency' => ['lightbox', '==', 1]
    
            ],
            [
                'id' => 'lightbox_btn_background',
                'type' => 'color',
                'title' => esc_html__("Lightbox Button Background", "ppv-pro"),
                'default' => '#333',
                'dependency' => ['lightbox', '==', 1]
            ],
            [
                'id' => 'lightbox_btn_size',
                'type' => 'button_set',
                'title' => esc_html__("Button Size", "ppv-pro"),
                'options' => [
                    'small' => esc_html__('Small', 'ppv-pro'),
                    'medium' => esc_html__('Medium', 'ppv-pro'),
                    'large' => esc_html__('Large', 'ppv-pro'),
                    'extra-large' => esc_html__('Extra Large', 'ppv-pro')
                ],
                'dependency' => ['lightbox', '==', 1],
                'default' => 'medium'
            ]
        ];

        return $metaboxs;
    }

    public function override_imported_data($data, $docs) {
        $output = [];
        while ($docs->have_posts()) {
            $docs->the_post();
            $id = get_the_ID();
            $googleDrive = get_post_meta($id, 'conditinal_fields', true);

            $output[$id] = wp_parse_args([
                'download' => get_post_meta($id, 'ppt_ppv_download', true),
                'disablePopout' => get_post_meta($id, 'ppt_ppv_disable', true),
                'downloadButtonText' => get_post_meta($id, 'ppt_ppv_download_btn_text', true),
                'googleDrive' => isset($googleDrive['enabled']),
                'doc' => $googleDrive['_groupped_ppv_gdrive'] ?? $data[$id]['doc']
            ], $data[$id]);
        }
        return $output;
    }

    public function override_doc_data($data, $id) {
        return array_merge($data, [
            'download' => Functions::meta($id, 'download', false),
            'googleDrive' => Functions::meta($id, 'googleDrive', false),
            'downloadButtonText' => Functions::meta($id, 'downloadButtonText', 'Download File'),
            'disablePopout' => Functions::meta($id, 'disablePopout', false),
        ]);
    }

    public function register_settings_page() {
        $parent_slug = 'edit.php?post_type=ppt_viewer';

        $prefix = '_ppt_';
        CSF::createOptions($prefix, [
            'menu_title' => esc_html__('Settings', 'ppv-pro'),
            'menu_slug' => 'settings',
            'menu_type' => 'submenu',
            'menu_parent' => $parent_slug,
            'theme' => 'light',
            'framework_title' => 'Settings <small>by bPlugins</small>',
            'footer_credit' => 'Thanks for being with bPlugins'
        ]);

        CSF::createSection( $prefix, array(
            'title'  => esc_html__('Cloud API', 'ppv-pro'),
            'fields' => apply_filters('ppv_pro_settings', array(
                array(
                    'type' => 'content',
                    'content' => esc_html__('Dropbox APP key', 'ppv-pro'),
                    'class' => 'csf-field-subheading',
                ),
                array(
                    'id' => 'dropbox_app_key',
                    'type' => 'text',
                    'title' => esc_html__('Dropbox App Key', 'ppv-pro'),
                ),
                array(
                    'type' => 'content',
                    'content' => esc_html__('Google API Setup', 'ppv-pro'),
                    'class' => 'csf-field-subheading',
                ),
                array(
                    'id' => 'google_apikey',
                    'type' => 'text',
                    'title' => esc_html__('Google API key', 'ppv-pro'),
                    'before' => '<p><a href="https://console.cloud.google.com/" target="_blank">Click Here</a> To Get Google Credentials</p>',
                ),
                array(
                    'id' => 'google_client_id',
                    'type' => 'text',
                    'title' => esc_html__('Google Client ID', 'ppv-pro'),
                ),
                array(
                    'id' => 'google_project_number',
                    'type' => 'text',
                    'title' => esc_html__('Google Project Number', 'ppv-pro'),
                ),
            ))
        ) );

    }

    public function admin_enqueue_assets() {
        $screen = get_current_screen();
        if (isset($screen->post_type) && $screen->post_type === 'ppt_viewer') {
            wp_register_script('google-drive-api', 'https://apis.google.com/js/api.js');
            wp_register_script('google-drive-client', 'https://accounts.google.com/gsi/client');
            wp_enqueue_script('ppt-admin', BPLDE_PLUGIN_DIR . 'assets/js/script.js', ['google-drive-api', 'google-drive-client'], BPLDE_VER, true);
        }
    }

    public function public_enqueue_assets() {
        wp_register_script('dropbox-picker', 'https://www.dropbox.com/static/api/2/dropins.js');
    }

    public function custom_admin_styles() {
        ?>
        <style>
        .drive-picker-button,
        .google-drive-picker-btn,
        .dropbox-dropin-btn:link.dropbox-dropin-default,
        .dropbox-dropin-btn:link.dropbox-dropin-success {
            margin: 0 10px;
            padding: 7px 20px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
        }
        </style>
        <?php
    }

    public function load_dependencies() {
        require_once BPLDE_PLUGIN_PATH . 'includes/DocumentEmbedder/premium-files/DocTemplate.php';
    }

    
}
