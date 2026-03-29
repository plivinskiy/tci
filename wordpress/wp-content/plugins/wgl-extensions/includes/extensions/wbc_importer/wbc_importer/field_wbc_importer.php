<?php
/**
 * Extension-Boilerplate
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !defined( 'WGL_CORE_URL' ) ) return;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_wbc_importer' ) ) {

    /**
     * Main ReduxFramework_wbc_importer class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wbc_importer {

        /**
         * Field Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */

        private $parent;
        private $field;
        private $value;
        private $extension_dir;
        private $demo_data_url;
        private $demo_data_dir;

        function __construct( $field = array(), $value ='', $parent = null ) {
            $wgl_importer =  defined('WGL_CORE_PATH') && is_dir(WGL_CORE_PATH  . '/includes/wgl_importer');
            if($wgl_importer){
                return;
            }
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            $class = ReduxFramework_extension_wbc_importer::get_instance();

            if ( !empty( $class->demo_data_dir ) ) {
                $this->demo_data_dir = $class->demo_data_dir;
                $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WGL_CORE_URL ) : WGL_CORE_URL ) ) );
                $dir_name = 'includes/wbc_importer/';
                $this->demo_data_url = trailingslashit( $wp_content_url . $dir_name ) . 'demo-data/';
            }

            if ( empty( $this->extension_dir ) ) {
                $dir_name = WGL_CORE_PATH . 'includes/wbc_importer/';
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', $dir_name ) );

            }
        }

        /**
         * Field Render Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field wbc_importer">';

            $nonce = wp_create_nonce( "redux_{$this->parent->args['opt_name']}_wbc_importer" );

            // No errors please
            $defaults = array(
                'id'        => '',
                'url'       => '',
                'width'     => '',
                'height'    => '',
                'thumbnail' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );

            $imported = false;

            $this->field['wbc_demo_imports'] = apply_filters( "redux/{$this->parent->args['opt_name']}/field/wbc_importer_files", array() );

            echo '<div class="theme-browser"><div class="themes">';

            if ( !empty( $this->field['wbc_demo_imports'] ) ) {

                foreach ( $this->field['wbc_demo_imports'] as $section => $imports ) {
                    $get_licence = get_option( 'wgl_licence_validated' );
                    $get_licence = empty( $get_licence ) ? get_option( WGL_Theme_Verify::get_instance()->item_id ) : $get_licence;

                    if ( empty( $imports ) ) {
                        continue;
                    }

                    if ( !array_key_exists( 'imported', $imports ) ) {
                        if(!empty($get_licence)){
                            $extra_class = 'not-imported';
                        }else{
                            $extra_class = 'not-licence';
                        }

                        $imported = false;
                        $import_message = esc_html__( 'Import Demo', 'framework' );
                    }else {
                        $imported = true;
                        if(!empty($get_licence)){
                            $extra_class = 'active imported';
                        }else{
                            $extra_class = 'not-licence';
                        }
                        $import_message = esc_html__( 'Demo Imported', 'framework' );
                    }
                    echo '<div class="wrap-importer theme '.$extra_class.'" data-demo-id="'.esc_attr( $section ).'"  data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_imports">';

                    echo '<div class="theme-screenshot">';

                    if ( isset( $imports['image'] ) ) {
                        echo '<img class="wbc_image" src="'.esc_attr( esc_url( $this->demo_data_url.$imports['directory'].'/'.$imports['image'] ) ).'"/>';

                    }
                    echo '</div>';

                    echo '<span class="more-details">'.$import_message.'</span>';
                    echo '<h3 class="theme-name">'. esc_html( apply_filters( 'wbc_importer_directory_title', $imports['directory'] ) ) .'</h3>';

                    echo '<div class="theme-actions">';
                    if ( false == $imported ) {
                        if(!empty($get_licence)){
                            echo '<div class="wbc-importer-buttons"><span class="spinner">'.esc_html__( 'Please Wait...', 'framework' ).'</span><span class="button-primary importer-button import-demo-data">' . __( 'Import Demo', 'framework' ) . '</span></div>';
                        }else{
                            echo '<div class="wbc-importer-buttons"><span class="button-primary importer-button import-demo-data"  data-url="'.esc_url( admin_url( 'admin.php?page=wgl-activate-theme-panel' ) ).'">' . __( 'Unlock', 'wgl-extensions' ) . '</span></div>';
                        }

                    }else {
                        if(!empty($get_licence)){
                            echo '<div class="wbc-importer-buttons button-secondary importer-button">'.esc_html__( 'Imported', 'framework' ).'</div>';
                            echo '<span class="spinner">'.esc_html__( 'Please Wait...', 'framework' ).'</span>';
                            echo '<div id="wbc-importer-reimport" class="wbc-importer-buttons button-primary import-demo-data importer-button">'.esc_html__( 'Re-Import', 'framework' ).'</div>';
                        }else{
                            echo '<div class="wbc-importer-buttons"><span class="button-primary importer-button import-demo-data" data-url="'.esc_url( admin_url( 'admin.php?page=wgl-activate-theme-panel' ) ).'">' . __( 'Unlock', 'wgl-extensions' ) . '</span></div>';
                        }

                    }
                    echo '</div>';
                    echo '<div class="importer_status clear" style="opacity:0;"><div class="progressbar"><div class="progressbar_condition"></div><div class="progressbar_val">0%</div></div></div>';
                    echo '</div>';


                }

            } else {
                echo "<h5>".esc_html__( 'No Demo Data Provided', 'framework' )."</h5>";
            }

            echo '</div></div>';
            echo '<div class="clear"></div>';
            
            echo '<div id="info-opt-info-error">';
                echo '<i class="fa fa-exclamation-circle"></i>';
                echo '<div class="error_message"></div>';
                echo '<div class="error_description">';
                    echo '<a target="_blank" href="https://www.wpbeginner.com/wp-tutorials/how-to-fix-curl-error-28-connection-timed-out-after-x-milliseconds/">';
                    echo esc_html__('Read this article to resolve this issue: ', 'wgl-extensions');
                    echo '</a>';
                    echo '<span>';
                    echo esc_html__( 'or install demo content without images' , 'wgl-extensions' );
                    echo '</span>';
                    echo '<div class="error_description-checkbox without_img"><label class="checkbox-error"><input type="checkbox" id="without_image" name="without_image"><span class="checkmark"></span>' . esc_html__('Install demo-content without images', 'wgl-extensions') . '</label></div>';
                echo '</div>';
            echo '</div>';

            echo '<div id="info-opt-info-success" class="hasIcon redux-success   redux-notice-field redux-field-info" style="display:none;padding: 8px;">
                    <p class="redux-info-icon"><i class="el el-ok-circle icon-large"></i></p>
                    <p class="redux-info-desc" style="font-size: 18px;"><b>'.esc_html__( 'Import is completed', 'framework' ).'</b><br></p>
                </div>';
            echo '</fieldset></td></tr>';

        }

        /**
         * Enqueue Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            $dir = Redux_Helpers::cleanFilePath( dirname( __FILE__ ) );
            $_dir = trailingslashit( $dir );

            $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );

            $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
            $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
            $relative_url   = str_replace( $wp_content_dir, '', $_dir );
            /* Fix bitnami url */
            $relative_url   = str_replace("/bitnami/wordpress/wp-content", "", $relative_url);
            $extension_url     = trailingslashit( $wp_content_url . $relative_url );

            wp_enqueue_script(
                'redux-field-wbc-importer-js',
                $extension_url . '/field_wbc_importer.js',
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-wbc-importer-css',
                $extension_url . 'field_wbc_importer.css',
                time(),
                true
            );

        }
    }
}
