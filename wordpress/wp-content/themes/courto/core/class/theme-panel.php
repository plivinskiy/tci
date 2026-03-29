<?php
defined('ABSPATH') || exit;

if (!class_exists('WGL_Theme_Panel')) {
    /**
     * WGL Theme Panel
     *
     *
     * @category Class
     * @package courto\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Theme_Panel
    {
        /**
         * @access      private
         * @var         \WGL_Theme_Panel $instance
         * @since       3.0.0
         */
        private static $instance;

        /**
         * Get active instance
         *
         * @access      public
         * @since       3.1.3
         * @return      self::$instance
         */
        public static function instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self;
                self::$instance->hooks();
            }

            return self::$instance;
        }

        // Shim since we changed the function name. Deprecated.
        public static function get_instance()
        {
            if ( ! self::$instance ) {
                self::$instance = new self;
                self::$instance->hooks();
            }

            return self::$instance;
        }

        private function hooks()
        {
            add_filter( 'wgl/plugins_install/additional_info', [ $this, 'theme_plugins_additional_info' ], 10, 2);
            /* ----------------------------------------------------------------------------- */
            /* Add Menu Page */
            /* ----------------------------------------------------------------------------- */
            add_action( 'admin_menu', [ $this, 'theme_panel_admin_menu' ]);
            add_action( 'admin_init', [ $this, 'theme_redirect' ] );

            require_once get_theme_file_path('/core/class/wgl-installer-plugins.php');
        }

        public function theme_plugins_additional_info( $extra_info, $slug ) {

            $slug_additional = $slug === 'wgl-real-estate' || $slug === 'mb-rest-api' ? true : false;
        
            if ( $slug_additional ) {
                // Only for specific plugin
                return ' <span class="extra-info">'.esc_html('Required for real estate template', 'courto').'</span>';
            }
        
            return $extra_info;
        }

        public function theme_panel_admin_menu()
        {
            add_menu_page (
                esc_html__('WebGeniusLab', 'courto'),
                esc_html__('WebGeniusLab', 'courto'),
                'manage_options', // capability
                'wgl-dashboard-panel',  // menu-slug
                [ $this, 'theme_panel_welcome_render' ], // function that will render its output
                get_template_directory_uri() . '/core/admin/img/dashboard/dashboad_icon.svg', // link to the icon that will be displayed in the sidebar
                2 // position of the menu option
            );
            $submenu = [];
            $submenu[] = [
                esc_html__('Welcome', 'courto'), // page_title
                esc_html__('Welcome', 'courto'), // menu_title
                'manage_options', // capability
                'wgl-dashboard-panel', // menu_slug
                [ $this, 'theme_panel_welcome_render' ], // function that will render its output
            ];

            $submenu[] = [
                esc_html__('Requirements', 'courto'), // page_title
                esc_html__('Requirements', 'courto'), // menu_title
                'edit_posts', // capability
                'wgl-status-panel', // menu_slug
                [ $this, 'theme_status' ], // function that will render its output
            ];

            $submenu[] = [
                esc_html__('Activate Theme', 'courto'), // page_title
                esc_html__('Activate Theme', 'courto'), // menu_title
                'edit_posts', // capability
                'wgl-activate-theme-panel', // menu_slug
                [ $this, 'theme_activate' ], // function that will render its output
            ];

            if(WGL_Framework::wgl_theme_activated()){
                if (current_user_can( 'activate_plugins' )):
                    $submenu[] = [
                        esc_html__('Theme Plugins', 'courto'), // page_title
                        esc_html__('Theme Plugins', 'courto'), // menu_title
                        'edit_posts', // capability
                        'wgl-plugins-panel', // menu_slug
                        [ $this, 'theme_plugins' ], // function that will render its output
                    ];
                endif;
            }else{
                $submenu[] = [
                    esc_html__('Theme Plugins', 'courto'), // page_title
                    esc_html__('Theme Plugins', 'courto'), // menu_title
                    'edit_posts', // capability
                    'wgl-check-activation', // menu_slug
                    [ $this, 'redirect_activation' ] // function that will render its output
                ];
            }


            if ( !class_exists( 'WGL_Extensions_Core' ) ||  !class_exists('Courto_Core') || !WGL_Framework::wgl_theme_activated() ) {
                $submenu[] = [
                    esc_html__('Demo Import', 'courto'), // page_title
                    esc_html__('Demo Import', 'courto'), // menu_title
                    'edit_posts', // capability
                    'wgl-check-activation', // menu_slug
                    [ $this, 'redirect_activation' ] // function that will render its output
                ];
            }

            if ( !class_exists( 'WGL_Extensions_Core' ) ||  !class_exists('Courto_Core') || !WGL_Framework::wgl_theme_activated() ) {
                $submenu[] = [
                    esc_html__('Theme Options', 'courto'),
                    esc_html__('Theme Options', 'courto'),
                    'edit_posts',
                    'wgl-check-activation',
                    [ $this, 'redirect_activation' ],
                ];
            }else{
                $submenu[] = [
                    esc_html__('Theme Options', 'courto'), // page_title
                    esc_html__('Theme Options', 'courto'), // menu_title
                    'edit_posts', // capability
                    'wgl-theme-options-panel', // menu_slug
                    [ $this, 'theme_options' ], // function that will render its output
                ];
            }

            $submenu[] = [
                esc_html__('Help Center', 'courto'), // page_title
                esc_html__('Help Center', 'courto'), // menu_title
                'edit_posts', // capability
                'wgl-theme-helper-panel', // menu_slug
                [ $this, 'theme_helper' ], // function that will render its output
            ];

            if(WGL_Framework::wgl_theme_activated()){
                $submenu = apply_filters('wgl_panel_submenu', [ $submenu ] );
            }else{
                $submenu = [ $submenu ];
            }


            foreach ($submenu[0] as $key => $value) {
                add_submenu_page(
                    'wgl-dashboard-panel', // parent menu slug
                    $value[0], // page_title
                    $value[1], // menu_title
                    $value[2], // capability
                    $value[3], // menu_slug
                    function() use ( $value ) { // function that will render its output
                        $this->wizard_wrapper( $value[4] );
                    }
                );
            }
        }

        public function wizard_wrapper( $callback )
        {
            if(isset($callback[1])){
                if('theme_panel_welcome_render' !== $callback[1]){
                    echo '<div class="wgl-theme-wizard">';
                    call_user_func( $callback );
                    echo '</div>';
                }
                if('theme_import' === $callback[1]){
                    $this->theme_dashboard_footer('plugins-panel', 'theme-options-panel');
                }
            }
        }

        public function theme_dashboard_heading()
        {
            global $submenu;

            $menu_items = '';

            if (isset($submenu['wgl-dashboard-panel'])):
              $menu_items = $submenu['wgl-dashboard-panel'];
            endif;

            if (!empty($menu_items)) :
            ?>
              <div class="wrap wgl-wrapper-notify">
                <div class="wgl-nav-title">
                    <?php
                    echo '<h3 class="title">' . esc_html(wp_get_theme()->get('Name')) . '</h3> <span class="version">' . esc_html(wp_get_theme()->get('Version')) . '</span>';
                    ?>
                </div>
                <div class="nav-tab-wrapper">
                  <?php foreach ($menu_items as $item):
                    $class = isset($_GET['page']) && $_GET['page'] == $item[2] ? ' nav-tab-active' : '';
                    ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page='.$item[2].''));?>"
                        class="nav-tab<?php echo esc_attr($class);?>"
                    >
                        <?php echo esc_html($item[0]); ?>

                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif;
        }

        public function theme_dashboard_footer($prev = false, $next = false)
        {
            ?>
                <div class="wgl-theme-wizard-footer">
                    <?php
                        if($prev){
                            $this->get_prev_button($prev);
                        }
                        if($next){
                          $this->get_next_button($next);
                        }
                    ?>
                </div>
            <?php
        }

        public function theme_panel_welcome_render()
        {

            echo '<div class="wgl-theme-wizard">';

            $this->theme_dashboard_heading();

            /**
             * Template View Welcome
             */
            require_once get_theme_file_path('/core/dashboard/tpl-view-weclome.php');


            $this->theme_dashboard_footer(false, 'status-panel');

            echo '</div>';
        }

        public function theme_plugins()
        {

            $this->theme_dashboard_heading();

            /**
             * Template View Plugin
             */
            require_once get_theme_file_path('/core/dashboard/tpl-view-plugins.php');

            ?>
            <div class="wgl-theme-wizard-footer">
                <?php
                    $this->get_prev_button('activate-theme-panel&activate-multi=1');
                    ?>
                    <div class="btn-wrapper">
                        <button class="wgl-button wgl-wizard-all-plugins">
                            <?php esc_html_e( 'install & activate all plugins', 'courto' ); ?>
                        </button>
                        <?php
                        $this->get_next_button('import-panel&activate-multi=1');
                        ?>
                    </div>
            </div>
            <?php
        }

        public function theme_status()
        {

            $this->theme_dashboard_heading();

            /**
             * Template View Plugin
             */
            require_once get_theme_file_path('/core/dashboard/tpl-view-status.php');

            $this->theme_dashboard_footer('dashboard-panel', 'activate-theme-panel');
        }

        public function theme_activate()
        {
            $this->theme_dashboard_heading();

            /**
             * Template View Plugin
             */
            require_once get_theme_file_path('/core/dashboard/tpl-view-activate-theme.php');

            ?>
            <div class="wgl-theme-wizard-footer">
                <?php
                    $this->get_prev_button('status-panel');
                    if(WGL_Framework::wgl_theme_activated()){
                      $this->get_next_button('plugins-panel');
                    }else{
                        echo '<span class="wgl-nav-btn wgl-button-next wgl-disabled-link">';
                            esc_html_e( 'Next step', 'courto' );
                        echo '</span>';
                    }
                ?>
            </div>
          <?php
        }

        public function theme_helper()
        {
            $this->theme_dashboard_heading();

            /**
             * Template View Plugin
             */
            require_once get_theme_file_path('/core/dashboard/tpl-view-theme-helper.php');
        }

        public function theme_options() {}

        public function theme_redirect()
        {
            global $pagenow;
            if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' === $pagenow ) {
                wp_safe_redirect( esc_url(admin_url( 'admin.php?page=wgl-dashboard-panel' )) );
                exit;
            }
            if ( is_admin() && isset( $_GET['page'] ) && 'wgl-check-activation' === $_GET['page'] ) {
                wp_safe_redirect( esc_url(admin_url( 'admin.php?page=wgl-dashboard-panel' )) );
                exit;
            }
        }

        public function redirect_activation(){}

        public function get_page_url( $name ) {
            return admin_url( 'admin.php?page=wgl-' . $name );
        }

        public function get_prev_button( $page ) {
            ?>
            <a class="wgl-nav-btn wgl-button-prev" href="<?php echo esc_url( $this->get_page_url( $page ) ); ?>">
                <?php esc_html_e( 'Previous step', 'courto' ); ?>
            </a>
            <?php
        }

        public function get_next_button( $page) {
            $url     = $this->get_page_url( $page );

            ?>
            <a class="wgl-nav-btn wgl-button-next" href="<?php echo esc_url( $url ); ?>">
                <?php esc_html_e( 'Next step', 'courto' ); ?>
            </a>
            <?php
        }

    }
}

WGL_Theme_Panel::get_instance();


?>