<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !defined( 'WGL_CORE_URL' ) ) exit;

if ( !class_exists( 'WGL_Importer_Controller' ) ) {
    /**
     * 
     * @package     WGL_Importer_Controller - Models for Importing demo content
     * @author      WebGeniusLab
     * @version     1.0.0
     * @since       1.0.7
     */
    class WGL_Importer_Controller
    {
        public static $instance;
        static $version = "1.0.0";
        protected $parent;
        private $filesystem = [];
        public $extension_dir;
        public $demo_data_dir;
        public $wgl_import_files = [];
        public $active_import_id;
        public $active_import;
        public $skins_import = false;

        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct()
        {
            if ( !is_admin() ) return;

            if ( empty( $this->extension_dir ) ) {
                $dir_name = WGL_CORE_PATH . 'includes/wgl_importer/';
                $demo_dir = trailingslashit( str_replace( '\\', '/', $dir_name ) );
                if(isset($_REQUEST['active_skin'])){
                    $this->demo_data_dir = apply_filters( 'wgl_importer_dir_path', $demo_dir . 'demo-data/skins/' );
                }else{
                    $this->demo_data_dir = apply_filters( 'wgl_importer_dir_path', $demo_dir . 'demo-data/' );
                }
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __DIR__ ) ) );
            }

            if(isset($_REQUEST['active_skin'])){
                $this->skins_import = apply_filters( 'wgl_importer_skins_demo', true );
            }else{
                $this->skins_import = apply_filters( 'wgl_importer_skins_demo', false );
            }
            add_action( 'admin_init', [$this, 'getImports'] );
            add_filter( 'wgl_importer_files', [ $this, 'addImportFiles' ] );  

            add_action( 'wp_ajax_wgl_importer', [ $this, 'ajax_importer' ] );
            add_action( 'wp_ajax_nopriv_wgl_importer', [ $this, 'ajax_importer' ]);
                                    
            //Remove Elementor Filter
            remove_filter( 'wp_import_post_meta', 'Elementor\Compatibility::on_wp_import_post_meta');

            add_filter( 'wp_import_post_meta', [ $this, 'wgl_wp_import_post_meta' ] );
        
            add_action('wgl_importer_custom_page_slider', [ $this, 'wgl_slider_importer' ], 10, 1);
        }

        /**
         * Process post meta before WGL WP import.
         *
         * Normalize Elementor post meta on import, We need the `wp_slash` in order
         * to avoid the unslashing during the `add_post_meta`.
         *
         * Fired by `wp_import_post_meta` filter.
         *
         * @since 1.0.0
         * @access public
         *
         * @param array $post_meta Post meta.
         *
         * @return array Updated post meta.
         */
        public function wgl_wp_import_post_meta( $post_meta ) {

            $is_wp_importer = self::is_wp_importer();

            if ( $is_wp_importer ) {
                foreach ( $post_meta as &$meta ) {
                    if ( '_elementor_data' === $meta['key'] ) {
                        $meta['value'] = wp_slash( $meta['value'] );
                        break;
                    }
                }
            }
    
            return $post_meta;
        }

        public static function is_wp_importer() {
            $wp_importer = get_plugins( '/wordpress-importer' );
    
            if ( ! empty( $wp_importer ) ) {
                $wp_importer_version = $wp_importer['wordpress-importer.php']['Version'];
                if ( version_compare( $wp_importer_version, '0.7', '>' ) && isset($_REQUEST['import']) && !empty($_REQUEST['import']) && 'wordpress' === $_REQUEST['import'] ) {
                    return false;
                }
            }
    
            return true;
        }

        /**
         * Get the demo folders/files
         * Provided fallback where some host require FTP info
         *
         * @return array list of files for demos
         */
        public function demoFiles()
        {
            global $wp_filesystem;
            if(!$wp_filesystem){
                return;
            }
            $dir_array = $wp_filesystem->dirlist( $this->demo_data_dir, false, true );

            if ( !empty( $dir_array ) && is_array( $dir_array ) ) {
                uksort( $dir_array, 'strcasecmp' );

                return $dir_array;
            } else {
                $dir_array = [];

                $demo_directory = array_diff( scandir( $this->demo_data_dir ), array( '..', '.' ) );

                if ( !empty( $demo_directory ) && is_array( $demo_directory ) ) {
                    foreach ( $demo_directory as $key => $value ) {
                        if ( is_dir( $this->demo_data_dir.$value ) ) {

                            $dir_array[$value] = array( 'name' => $value, 'type' => 'd', 'files'=> [] );

                            $demo_content = array_diff( scandir( $this->demo_data_dir.$value ), array( '..', '.' ) );

                            foreach ( $demo_content as $d_key => $d_value ) {
                                if ( is_file( $this->demo_data_dir.$value.'/'.$d_value ) ) {
                                    $dir_array[$value]['files'][$d_value] = array( 'name'=> $d_value, 'type' => 'f' );
                                }
                            }
                        }
                    }

                    uksort( $dir_array, 'strcasecmp' );
                }
            }
            return $dir_array;
        }

        public function demoFilesDirectory($name = false)
        {
            global $wp_filesystem;
            if(!$wp_filesystem && !$name){
                return;
            }
            $dir_array = $wp_filesystem->dirlist( $this->demo_data_dir .'/'. $name, false, true );

            if ( !empty( $dir_array ) && is_array( $dir_array ) ) {
                uksort( $dir_array, 'strcasecmp' );
                return $dir_array;
            } else {
                $dir_array = [];

                $demo_directory = array_diff( scandir( $this->demo_data_dir . $name ), array( '..', '.' ) );

                if ( !empty( $demo_directory ) && is_array( $demo_directory ) ) {
                    
                    foreach ( $demo_directory as $key => $value ) {
                        if ( is_dir( $this->demo_data_dir. $name .'/'.$value ) ) {

                            $dir_array[$value] = array( 'name' => $value, 'type' => 'd', 'files'=> [] );

                            $demo_content = array_diff( scandir( $this->demo_data_dir.$name.'/'.$value ), array( '..', '.' ) );

                            foreach ( $demo_content as $d_key => $d_value ) {
                                if ( is_file( $this->demo_data_dir.$name.'/'.$value.'/'.$d_value ) ) {
                                    $dir_array[$value]['files'][$d_value] = array( 'name'=> $d_value, 'type' => 'f' );
                                }
                            }
                        }
                    }

                    uksort( $dir_array, 'strcasecmp' );
                }        
            }
            return $dir_array;
        }

        public function getImports()
        {
            if (!empty($this->wgl_import_files)) {
                return $this->wgl_import_files;
            }

            $imports = $this->demoFiles();

            $imported = get_option( 'wgl_imported_demos' );

            if (!empty($imports) && is_array($imports)) {
                $x = 1;
                foreach ( $imports as $import ) {

                    if (empty( $import['files'])) {
                        continue;
                    }

                    if ( $import['type'] == "d" && !empty( $import['name'] ) ) {
                        $this->wgl_import_files['wbc-import-'.$x] = $this->wgl_import_files['wbc-import-'.$x] ?? [];
                        $this->wgl_import_files['wbc-import-'.$x]['directory'] = $import['name'];

                        if (
                            !empty($imported)
                            && is_array($imported)
                            && array_key_exists('wbc-import-'.$x, $imported)
                        ) {
                            $this->wgl_import_files['wbc-import-'.$x]['imported'] = 'imported';
                        }

                        $this->prepareFiles($import, $x);

                        if (
                            'cpt' !== $import['name']
                            && 'full' !== $import['name']
                            && 'pages' !== $import['name']
                            && 'skins' !== $import['name']
                            && !$this->skins_import
                        ) {
                            $another_demo = $this->demoFilesDirectory($import['name']);
                            if (!empty($another_demo) && is_array($another_demo)) {
                                foreach ( $another_demo as $import ) {
                                    $this->prepareFiles($import, $x);
                                }
                            }
                        }

                        if($this->skins_import)
                        {                      
                            $another_demo = $this->demoFilesDirectory($import['name']);
                            if (!empty($another_demo) && is_array($another_demo)) {
                                foreach ( $another_demo as $import ) {
                                    $this->prepareFiles($import, $x);
                                }
                            }
                        }

                        if ( !isset( $this->wgl_import_files['wbc-import-'.$x]['content_file'] ) ) {
                            unset( $this->wgl_import_files['wbc-import-'.$x] );
                            if ($x > 1) $x--;
                        }
                    }

                    $x++;
                }
            }
        }


        public function prepareFiles($import, $x)
        {
            $this->wgl_import_files['wbc-import-'.$x]['content_file'] = 'content.xml';
            $content_list = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
            foreach ($content_list as $key => $value) {
                $key_s = $key + 1;
                $this->wgl_import_files['wbc-import-'.$x]['content_file'.$key_s] = 'content_'.$value.'.xml';
            }

            if('pages' === $import['name']){
                foreach ( $import['files'] as $f ) {
                    $this->wgl_import_files['wbc-import-'.$x]['custom_pages'][] = $f['name'];
                }                        
            }

            if('cpt' === $import['name']){
                foreach ( $import['files'] as $f ) {
                    $this->wgl_import_files['wbc-import-'.$x]['cpt'][] = $f['name'];
                }                        
            }
        
            if(isset($import['files'])){
                foreach ( $import['files'] as $file ) {
                    switch ( $file['name'] ) {
                        case 'theme-options.txt':
                        case 'theme-options.json':
                            $this->wgl_import_files['wbc-import-'.$x]['theme_options'] = $file['name'];
                            break;

                        case 'widgets.json':
                        case 'widgets.txt':
                            $this->wgl_import_files['wbc-import-'.$x]['widgets'] = $file['name'];
                            break;

                        case 'screen-image.png':
                        case 'screen-image.webp':
                        case 'screen-image.jpg':
                        case 'screen-image.gif':
                            $this->wgl_import_files['wbc-import-'.$x]['image'] = $file['name'];
                            break;
                    }
                }                
            }

        }

        public function addImportFiles( $wgl_import_files )
        {
            if ( !is_array( $wgl_import_files ) || empty( $wgl_import_files ) ) {
                $wgl_import_files = [];
            }

            $wgl_import_files = wp_parse_args( $wgl_import_files, $this->wgl_import_files );

            return $wgl_import_files;
        }

        public function ajax_importer()
        {
            if (
                !isset($_REQUEST['nonce'])
                || !wp_verify_nonce($_REQUEST['nonce'], "wgl_importer")
            ) {
                die( 0 );
            }

            if (
                isset($_REQUEST['type'])
                && $_REQUEST['type'] == "import-demo-content"
            ) {
                
                if(isset($_REQUEST['without_image'])){
                    global $skip_image_demo_content;
                    $skip_image_demo_content = true;
                }

                if(isset($_REQUEST['type_option'])){
                    $option = $_REQUEST['demo_import_pages_id'] ?? [];
                }else{
                    $option = $_REQUEST['demo_import_full_id'] ?? [];
                }
                
                if(isset($_REQUEST['demo_content'])){
                    global $demo_url;
                    $demo_url = $_REQUEST['demo_content'];
                    $option = $_REQUEST['demo_import_'.$_REQUEST['demo_content'].'_id'] ?? [];
                }     
               
                $this->active_import_id = $option; 

                if(isset($_REQUEST['demo_content'])){
                    $this->wgl_import_files[$this->active_import_id]['directory'] = $_REQUEST['demo_content'] . '/' . 'full';
                }

                $import_parts = $this->wgl_import_files[$this->active_import_id];
       
                if(!isset($_REQUEST['custom_pages'])){
                    $import_parts['content_file'] = $import_parts['content_file'.$_REQUEST['content']] ?? null;
                }else{
                    $length = 0;
                    if(isset($_REQUEST['selectedPages']) && !empty($_REQUEST['selectedPages'])){
                        $length = count($_REQUEST['selectedPages']);
                        if($length >= (int) $_REQUEST['content']){
                            $import_parts['content_file'] = $_REQUEST['selectedPages'][(int) $_REQUEST['content'] - 1].'.xml';
                            $this->wgl_import_files[$this->active_import_id]['content_file'] = $_REQUEST['selectedPages'][(int) $_REQUEST['content'] - 1].'.xml';
                            if(isset($_REQUEST['demo_content'])){
                                $this->wgl_import_files[$this->active_import_id]['directory'] = $_REQUEST['demo_content'] . '/' . 'pages';
                            }
                        }else{
                            $this->wgl_import_files[$this->active_import_id]['wgl_content_end_importer'] = true;
                        }    
                    }
                    
                    if(isset($_REQUEST['selectedCPT']) && !empty($_REQUEST['selectedCPT'])){
                        
                        if($length < (int) $_REQUEST['content']){

                            $length += count($_REQUEST['selectedCPT']);
                            
                            $allValues = array_values($_REQUEST['selectedCPT']);
                            
                            $this->active_import_id = $_REQUEST['demo_import_cpt_id'] ?? [];
                            $import_parts = $this->wgl_import_files[$this->active_import_id];
                            if(isset($_REQUEST['demo_content'])){
                                $this->wgl_import_files[$this->active_import_id]['directory'] = $_REQUEST['demo_content'] . '/' . 'cpt';
                            }

                            if($length >= (int) $_REQUEST['content']){
                                $import_parts['content_file'] = $allValues[$length -  (int) $_REQUEST['content']].'.xml';
                                $this->wgl_import_files[$this->active_import_id]['content_file'] = $allValues[$length - (int) $_REQUEST['content']].'.xml';                        
                            }else{
                                $this->wgl_import_files[$this->active_import_id]['wgl_content_end_importer'] = true;
                            }      
                        }else{
                           $this->wgl_import_files[$this->active_import_id]['wgl_content_end_importer'] = false;
                        }
                    }

                    if(!(isset($_REQUEST['selectedPages']) && !empty($_REQUEST['selectedPages']))
                        && !(isset($_REQUEST['selectedCPT']) && !empty($_REQUEST['selectedCPT']))){
                        $this->wgl_import_files[$this->active_import_id]['wgl_content_end_importer'] = true;
                    }
                }
                
                $this->active_import = array( $this->active_import_id => $import_parts );

                if(isset($_REQUEST['type_option']) && is_array($_REQUEST['type_option'])){
                    foreach ($_REQUEST['type_option'] as $key => $value) {
                        switch ($value) {
                            case 'options':
                                $this->wgl_import_files[$this->active_import_id]['theme_options'] = 'theme-options.json';        
                                $this->wgl_import_files[$this->active_import_id]['type'][] = 'options';   
                                $this->wgl_import_files[$this->active_import_id]['custom_directory'] = isset($_REQUEST['demo_content']) ? $_REQUEST['demo_content'] . '/' . 'full' : 'full';                   
                                break;
                            case 'widgets':
                                $this->wgl_import_files[$this->active_import_id]['widgets'] = 'widgets.json';       
                                $this->wgl_import_files[$this->active_import_id]['type'][] = 'widgets';    
                                $this->wgl_import_files[$this->active_import_id]['custom_directory'] = isset($_REQUEST['demo_content']) ? $_REQUEST['demo_content'] . '/' . 'full' : 'full';                 
                                    break;
                            case 'rev_slider':
                                $this->wgl_import_files[$this->active_import_id]['rev-slider'] = true;       
                                $this->wgl_import_files[$this->active_import_id]['type'][] = 'rev-slider';   
                                $this->wgl_import_files[$this->active_import_id]['custom_directory'] = isset($_REQUEST['demo_content']) ? $_REQUEST['demo_content'] . '/' . 'full' : 'full';           
                                break;
                                
                        }
                    }
                }   
                
                $custom_import = false;
                if(isset($_REQUEST['custom_pages'])){
                    $count_pages = (int) $_REQUEST['count_pages'];
                    $content = (int) $_REQUEST['content'];
                    if($count_pages === $content){
                        $custom_import = true;
                        $this->wgl_import_files[$this->active_import_id]['wgl_custom_end_importer'] = true;
                        do_action('wgl_importer_elementor_default_kit');
                    }
                }

                $active_skin_import = $install_posts_and_pages = $set_homepage = false;
                if(isset($_REQUEST['active_skin'])){
                    if(isset($_REQUEST['type_skin_option']) && is_array($_REQUEST['type_skin_option'])){
                        foreach ($_REQUEST['type_skin_option'] as $key => $value) {
                            switch ($value) {
                                case 'content':   
                                    $install_posts_and_pages = true;               
                                    break;
                                case 'homepage':   
                                    $set_homepage = true;               
                                    break;
                                case 'options':   
                                    $this->wgl_import_files[$this->active_import_id]['type'][] = 'options';                 
                                    break;
                                case 'widgets':   
                                    $this->wgl_import_files[$this->active_import_id]['type'][] = 'widgets';                
                                        break;
                                case 'rev_slider':
                                    $this->wgl_import_files[$this->active_import_id]['rev-slider'] = true;       
                                    $this->wgl_import_files[$this->active_import_id]['type'][] = 'rev-slider';           
                                    break;
                                    
                            }
                        }
                    }else{
                        echo '<p class="notice notice-error choose-skin">', esc_html__('Please choose what you want to import', 'wgl-extensions') . '</p>';
                        die();
                    } 

                    $count_pages = (int) $_REQUEST['count_pages'];
                    $content = (int) $_REQUEST['content'];

                    if(10 < $content && $install_posts_and_pages){
                        $active_skin_import = true;
                    } 
                
                    if ($count_pages === $content){
                        $this->wgl_import_files[$this->active_import_id]['wgl_custom_end_importer'] = true;
                        $this->wgl_import_files[$this->active_import_id]['wgl_content_end_importer'] = true;
                        if($set_homepage){
                            $this->wgl_import_files[$this->active_import_id]['active_set_homepage'] = true;
                        }
                    }else{
                        $this->wgl_import_files[$this->active_import_id]['wgl_custom_exclude_import'] = true;
                    }

                    if( !$install_posts_and_pages ){
                        $active_skin_import = true;
                    }

                    $this->wgl_import_files[$this->active_import_id]['active_skin_importer'] = true;
                }

                if(isset($_REQUEST['re_import_item'])){
                    $this->wgl_import_files[$this->active_import_id]['wgl_custom_end_importer'] = false;
                }
            
                include $this->extension_dir.'inc/init-installer.php';
            
                $import = isset($_REQUEST['custom_pages']) && !empty($_REQUEST['custom_pages']) ? 0 : $_REQUEST['content'];
                if($active_skin_import){
                    $import = 0;
                }
                
                $installer = new Radium_Theme_Demo_Data_Importer( $this, $import);

                if($custom_import){
                    if (!class_exists('Elementor')) {
                        \Elementor\Plugin::$instance->files_manager->clear_cache();
                    }                   
                }

                die();
            }

            die();
        }

        function wgl_slider_importer($slide)
        { 
            /**
             * Slider(s) Import
             */
            if (class_exists('RevSlider')) {
                $rev_slider_template_path = $this->demo_data_dir . 'full';
                if (is_array($slide)) {
                    foreach ($slide as $key => $value) {
                        if (file_exists($rev_slider_template_path . $value . '.zip')) {
                            $slider[$key] = new RevSlider();
                            $slider[$key]->importSliderFromPost(true, true, $rev_slider_template_path . $value . '.zip');
                        }
                    }
                } elseif (file_exists($rev_slider_template_path . $slide . '.zip')) {
                    $slider = new RevSlider();
                    $slider->importSliderFromPost(true, true, $rev_slider_template_path . $slide . '.zip');
                }
            }
    
        }

        public static function get_instance()
        {
            return self::$instance;
        }

    } // class
} // if

new WGL_Importer_Controller();
