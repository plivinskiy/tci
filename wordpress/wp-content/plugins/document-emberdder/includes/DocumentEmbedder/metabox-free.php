<?php

use PPV\Helper\Functions;

if (class_exists('CSF')) {

    // Set a unique slug-like ID
    $prefix = 'ppv';
    $is_pro = false;
  

    // Create a metabox
    CSF::createMetabox($prefix, array(
      'title'     => __('Document Configuration', 'ppv'),
      'post_type' => 'ppt_viewer',
      'theme'     => 'light'
    ));



    // Create a section
    CSF::createSection($prefix, array(
      'title'  => __('General', 'ppv'),
      'fields' => array(
        [
            'id'    => 'doc',
            'type'  => 'upload',
            'title' => esc_html__('Document File', 'ppv'),
            'attributes' => array('id' => 'picker_field'),
            'desc' => '<p style="margin-top:8px; font-size:13px; color:#6b7280; line-height:1.6;">
                <strong style="color:#111827;">Supported Files:</strong> 
                pdf, doc, docx, ppt, pptx, txt, rtf, csv, odt, ods, odp.
            </p>',
        ],
        [
            'id'    => 'device_preview',
            'type'  => 'button_set',
            'title' => esc_html__('Set Height & Width For', 'ppv'),
            'options' => [
                'desktop' => '<i class="fas fa-desktop"></i> Desktop',
                'tablet'  => '<i class="fas fa-tablet-alt"></i> Tablet',
                'mobile'  => '<i class="fas fa-mobile-alt"></i> Mobile',
            ],
            'default' => 'desktop',
        ],
        [
            'id' => 'width',
            'type' => 'dimensions',
            'title' => esc_html__('Width (Desktop)', 'ppv'),
            'height' => false,
            'default' => ['width' => '100', 'unit' => '%'],
            'dependency' => ['device_preview', '==', 'desktop']
        ],
        [
            'id' => 'width_tablet',
            'type' => 'dimensions',
            'title' => esc_html__('Width (Tablet)', 'ppv'),
            'height' => false,
            'dependency' => ['device_preview', '==', 'tablet']
        ],
        [
            'id' => 'width_mobile',
            'type' => 'dimensions',
            'title' => esc_html__('Width (Mobile)', 'ppv'),
            'height' => false,
            'dependency' => ['device_preview', '==', 'mobile']
        ],
        [
          'id' => 'height',
          'type' => 'dimensions',
          'title' => esc_html__('Height (Desktop)', 'ppv'),
          'width' => false,
          'default' => ['height' => 600, 'unit' => 'px'],
          'dependency' => ['device_preview', '==', 'desktop']
        ],
        [
          'id' => 'height_tablet',
          'type' => 'dimensions',
          'title' => esc_html__('Height (Tablet)', 'ppv'),
          'width' => false,
          'dependency' => ['device_preview', '==', 'tablet']
        ],
        [
          'id' => 'height_mobile',
          'type' => 'dimensions',
          'title' => esc_html__('Height (Mobile)', 'ppv'),
          'width' => false,
          'dependency' => ['device_preview', '==', 'mobile']
        ],
        [
          'id' => 'showName',
          'type' => 'switcher',
          'title' => esc_html__('Display File Name at the Top', 'ppv'),       
          'desc' => 'Not available for Google Drive and Dropbox',
          'default' => 0
        ],
        Functions::ppv_lock_field([     
            'id' => 'disablePopout',
            'type' => 'switcher',
            'title' => Functions::ppv_pro_title(esc_html__('Disable Popout', 'ppv')),
            'desc' => esc_html__('Only available for google drive', 'ppv'),
            'default' => false
        ]),
        Functions::ppv_lock_field([     
            'id' => 'loading_icon',
            'type' => 'switcher',
            'title' => Functions::ppv_pro_title(esc_html__("Enable Loading Icon", "ppv")),    
            'default' => false
        ]),
        Functions::ppv_lock_field([     
            'id' => 'lightbox',
            'type' => 'switcher',
            'title' => Functions::ppv_pro_title(esc_html__("Enable Lightbox", "ppv")),
        ]),
        Functions::ppv_lock_field([         
            'id' => 'lightbox_btn_text',
            'type' => 'text',
            'title' => Functions::ppv_pro_title(esc_html__("Button Text", "ppv")),          
            'default' => 'View Document',
            'dependency' => ['lightbox', '==', '1']
        ]),
        Functions::ppv_lock_field([         
            'id' => 'lightbox_btn_color',
            'type' => 'color',
            'title' => Functions::ppv_pro_title(esc_html__("Button Text Color", "ppv")),
            'default' => '#fff',
            'dependency' => ['lightbox', '==', 1]
        ]),
        Functions::ppv_lock_field([         
            'id' => 'lightbox_btn_background',
            'type' => 'color',
            'title' => Functions::ppv_pro_title(esc_html__("Lightbox Button Background", "ppv")),
            'default' => '#333',
            'dependency' => ['lightbox', '==', 1]
        ]),
        Functions::ppv_lock_field([           
            'id' => 'lightbox_btn_size',
            'type' => 'button_set',
            'title' => Functions::ppv_pro_title(esc_html__("Button Size", "ppv")),
            'options' => [
                'small' => esc_html__('Small', 'ppv'),
                'medium' => esc_html__('Medium', 'ppv'),
                'large' => esc_html__('Large', 'ppv'),
                'extra-large' => esc_html__('Extra Large', 'ppv')
            ],
            'dependency' => ['lightbox', '==', 1],
            'default' => 'medium'
        ])
      )
    ));

    CSF::createSection($prefix, array(
      'title'  => __('Download Management', 'ppv'),
      'fields' => array(
        [     
          'id' => 'download',
          'type' => 'switcher',
          'title' => esc_html__('Show Download Button', 'ppv'),
          'desc' => esc_html__('Not available for Google Drive and Dropbox', 'ppv'),
          'default' => true
        ],
        [
            'id' => 'downloadButtonText',
            'type' => 'text',
            'title' => esc_html__('Download Button Text', 'ppv'),
            'default' => 'Download',
            'dependency' => ['download', '==', 'true'],
        ],
        [
            'id' => '_de_download_position',
            'type' => 'select',
            'title' => esc_html__('Download Position', 'ppv'),
            'options' => [
                'toolbar' => 'Toolbar (Default)',
                'below' => 'Below Embed',
            ],
            'default' => 'toolbar',
            'dependency' => ['download', '==', 'true'],
        ],
        [
            'id' => '_de_download_behavior',
            'type' => 'select',
            'title' => esc_html__('Download Behavior', 'ppv'),
            'options' => [
                'download' => 'Force Save Dialog',
                'newtab' => 'Open in New Tab',
            ],
            'default' => 'download',
            'dependency' => ['download', '==', 'true'],
        ],
        [
            'id' => '_de_download_filename',
            'type' => 'text',
            'title' => esc_html__('Custom Filename', 'ppv'),
            'desc' => esc_html__('Optional custom filename for the download. Note: This will not work if Download Behavior is set to "Open in New Tab".', 'ppv'),
            'dependency' => ['download', '==', 'true'],
        ],
        [
            'id' => '_de_download_show_count',
            'type' => 'switcher',
            'title' => esc_html__('Show Download Count', 'ppv'),
            'desc' => esc_html__('Display the total number of times this document has been downloaded.', 'ppv'),
            'default' => false,
            'dependency' => ['download', '==', 'true'],
        ],
        Functions::ppv_lock_field([
            'id' => '_de_download_access',
            'type' => 'select',
            'title' => Functions::ppv_pro_title(esc_html__("Download Access", "ppv")),
            'options' => [
                'everyone' => 'Everyone',
                'loggedin' => 'Logged In',
                'roles' => 'Specific Roles'
            ],
            'default' => 'everyone',
            'dependency' => ['download', '==', 'true'],
        ]),
        Functions::ppv_lock_field([
            'id' => '_de_email_gate',
            'type' => 'switcher',
            'title' => Functions::ppv_pro_title(esc_html__("Email Gate", "ppv")),
            'desc' => esc_html__('Enable to require users to enter their name and email before downloading. Leads are saved in the Leads menu.', 'ppv'),
            'default' => false,
            'dependency' => ['download', '==', 'true'],
        ]),
        [
            'id' => '_de_download_limit',
            'type' => 'select',
            'title' => esc_html__("Download Limit", "ppv"),
            'desc' => esc_html__('Limit the number of downloads allowed per user IP address.', 'ppv'),
            'options' => [
                '0' => 'No Limit',
                '1' => '1',
                '3' => '3',
                '5' => '5'
            ],
            'default' => '0',
            'dependency' => ['download', '==', 'true'],
        ]
      )
    ));
}
