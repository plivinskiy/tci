<?php

namespace PPV\Model;

class SubMenus {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'ppv_submenus']);
        add_action('admin_menu', [$this, 'move_submenu_to_last'], 9999);
    }

    public function ppv_submenus() {
        add_submenu_page(
            'edit.php?post_type=ppt_viewer', 
            __("Help & Demos", "ppv"), 
            __('<span style="color: #f18500;">Help & Demos</span>', "ppv"),   
            'manage_options',                  
            'bplde-dashboard',              
            [$this, 'render_dashboard_page'],
        );
    }

    public function move_submenu_to_last() {
        global $submenu;

        $parent = 'edit.php?post_type=ppt_viewer';
        $slug   = 'bplde-dashboard';

        if (!isset($submenu[$parent])) {
            return;
        }

        foreach ($submenu[$parent] as $index => $item) {
            if ($item[2] === $slug) {
                $menu_item = $item;
                unset($submenu[$parent][$index]);
                $submenu[$parent][] = $menu_item; // push to end
                break;
            }
        }
    }

    public function render_dashboard_page() {
        ?>
       <style>#wpcontent { padding-left: 0 !important; }</style>
       <div id='bpldeDashboard'
            data-info='<?php echo esc_attr( wp_json_encode( [
                'version' => BPLDE_VER,
                'isPremium' => de_fs()->can_use_premium_code(),
                'hasPro' => BPLDE_HAS_PRO,
                'licenseActiveNonce' => wp_create_nonce( 'bPlLicenseActivation' ),
                'exportLeadsNonce' => wp_create_nonce( 'de_export_leads_csv' )
            ] ) ); ?>'
        ></div>
        <?php
    }
}

new SubMenus();