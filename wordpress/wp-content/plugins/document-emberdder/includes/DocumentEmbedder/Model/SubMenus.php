<?php

namespace PPV\Model;

class SubMenus {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'ppv_submenus']);
    }

    public function ppv_submenus() {
        add_submenu_page(
            'edit.php?post_type=ppt_viewer', 
            __("Demo & Help", "ppv"), 
            __("Demo & Help", "ppv"),   
            'manage_options',                  
            'bplde-dashboard',              
            [$this, 'render_dashboard_page']  
        );
    }

    public function render_dashboard_page() {
        ?>
       <style>#wpcontent { padding-left: 0 !important; }</style>
       <div id='bpldeDashboard'
            data-info='<?php echo esc_attr( wp_json_encode( [
                'version' => BPLDE_VER,
                'isPremium' => de_fs()->can_use_premium_code(),
            ] ) ); ?>'
        ></div>
        <?php
    }
}

new SubMenus();