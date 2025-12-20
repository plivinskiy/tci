<?php
namespace PPVP\Services;

class DocTemplate{
    protected static $_instance = null;
    protected static $uniqid = null;
    protected static $data = null;
    protected static $meta = null;

    public function __construct(){
        $this->register();
    }

    public function register(){
        add_filter('ppv_shortcode_html', [$this, 'html'], 10, 2);
    }

    public static function instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function html($content, $data){
        self::$uniqid = uniqid();
        self::$data = $data;
        $id = $data['id'];
	    ob_start();

        $frame_style = 'width:'.$data['width'].'; '. 'height:'. $data['height']. ';';

        $lightbox = self::meta($id, 'lightbox', false);
        $lightbox_btn_text = self::meta($id, 'lightbox_btn_text', 'view Document');
        $lightbox_btn_color = self::meta($id, 'lightbox_btn_color', '#fff');
        $lightbox_btn_background = self::meta($id, 'lightbox_btn_background', '#333');
        $lightbox_btn_size = self::meta($id, 'lightbox_btn_size', 'medium');
        $loading_icon = self::meta($id, 'loading_icon', '0');

        if($data['doc'] == ''){ 
            echo '<h2>Ooops... You forgot to Select a document. Please select a file or paste a external document link to show here. </h2>';
        } else{ ?>
           
            <div id="<?php echo esc_attr("ppv_frame_wrapper".$data['id']) ?>" data-lightbox="<?php echo esc_attr((boolean) $lightbox) ?>" class="ppv_container">

                <?php if($lightbox){?>
                    <style>
                        <?php echo esc_attr("#ppv_frame_wrapper".$data['id']) ?> .ppv-lightbox-btn{
                            color: <?php echo esc_attr($lightbox_btn_color); ?>;
                            background: <?php echo esc_attr($lightbox_btn_background); ?>
                        }
                    </style>
                    <button class="ppv-lightbox-btn <?php echo esc_attr($lightbox_btn_size) ?>"><?php echo esc_html($lightbox_btn_text) ?></button>

                    <div class="ppv-lightbox-overlay">
                        <div class="bplde-lightbox" >
                            <span class="bplde-lightbox-close">&times;</span>
                            
                            <div class="bplde-lightbox-body">

                                <?php if($loading_icon === '1'){ ?>
                                    <div class="ppv-lightbox-loading"></div>
                                <?php } ?>

                                <?php
                                if(strpos($data['doc'], 'dropbox.com')){
                                    wp_enqueue_script('dropbox-picker');
                                    self::$data['doc'] = $data['doc'];
                                    self::useDropbox();
                                }else if(strpos($data['doc'], 'drive.google.com') !== false || strpos($data['doc'], 'docs.google.com') !== false) {
                                    self::$data['doc'] = $data['doc'];
                                    self::useGoogleDrive();
                                } else {
                                    self::useLibrary();
                                }
                                ?>  
                            </div>
                        </div>
                    </div>
                <?php } else {?>
                    <div class="bplde-document-wrapper">
                                
                        <?php if($loading_icon === '1'){ ?>
                            <div class="ppv-lightbox-loading"></div>
                        <?php } ?>

                        <?php
                        if(strpos($data['doc'], 'dropbox.com')){
                            ?>
                            <div class="dropbox-preview" style="<?php echo $frame_style; ?>">
                                <?php
                                    wp_enqueue_script('dropbox-picker');
                                    self::$data['doc'] = $data['doc'];
                                    self::useDropbox();
                                ?>
                            </div>
                            <?php
                        } else if(strpos($data['doc'], 'drive.google.com') !== false || strpos($data['doc'], 'docs.google.com') !== false) {
                            ?>
                            <div class="drive-preview" style="<?php echo $frame_style ?>">
                            <?php
                                self::$data['doc'] = $data['doc'];
                                self::useGoogleDrive();
                            ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="all-type-preview">
                            <?php
                                // Show file name
                                if(!empty($data['showName'])) {
                                    echo '<p style="padding-left:10px;">File Name: ' . esc_html(basename($data['doc'])) . '</p>';
                                }
                                // Download button
                                if(!empty($data['download'])) { 
                                    $down_btn_color = '';
                                    echo '<p style="padding-left:10px;">
                                            <a class="s_pdf_download_link" href="'.esc_url($data['doc']).'" download>
                                                <button style="margin-bottom:10px; background-color:'.esc_attr($down_btn_color).';" class="ppv_download_bttn">'.
                                                    esc_html($data['downloadButtonText']).
                                                '</button>
                                            </a>
                                        </p>';
                                }
                                ?>
                                <div class="document-preview" style="<?php echo $frame_style; ?>">
                                <?php
                                    self::useLibrary();
                                ?>
                                </div>
                                <?php
                                ?>
                            </div>
                            <?php
                        }
                        ?> 
                    </div>
                <?php }?>

            </div>
            <script>
                
            </script>
            <?php 
            
        }
        return $output = ob_get_clean();
        
    
    }

    public static function useDropbox() {
        ?>
            <a href="<?php echo esc_url(self::$data['doc']) ;?>" class="dropbox-embed" data-height="100%" data-width="100%">
            </a>
        <?php
    }

    public static function useGoogleDrive(){
        ?>
            <iframe
                id="<?php echo esc_attr("frame-".self::$uniqid); ?>"
                height="100%"
                width="100%"
                src="<?php echo esc_url(self::$data['doc']); ?>"
                frameborder="0">
            </iframe>
            <?php 
                if(self::$data['disablePopout']){?> <div style="width: 80px; height: 80px; position: absolute; opacity: 0; right: 0px; top: 0px;"></div><?php }
            ?>
        </div>
        <?php
    }

    public static function useLibrary(){
        $data = self::$data;
        $file_url = isset($data['doc']) ? $data['doc'] : '';
        $file_ext = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));
    
        ?>
        <?php 
            // Preview by file type
            if(in_array($file_ext, ['jpg','jpeg','png','gif','webp'])) {
                echo '<img src="'.esc_url($file_url).'" class="bplDl-preview-image" style="width:100%; height:100%;" />';
            }
            elseif(in_array($file_ext, ['mp4','webm','ogg'])) {
                echo '<video controls src="'.esc_url($file_url).'" class="bplDl-preview-video" style="width:100%; height:100%;"></video>';
            }
            elseif($file_ext === 'pdf') {
                $frame_url = '//docs.google.com/gview?embedded=true&url=' . rawurlencode($file_url);
                echo '<iframe src="'.esc_url($frame_url).'" frameborder="0" class="bplDl-preview-iframe" style="width:100%; height:100%;"></iframe>';
            }
            elseif(in_array($file_ext, ['ppt','pptx','xls','xlsx','doc','docx'])) {
                $frame_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($file_url);
                echo '<iframe src="'.esc_url($frame_url).'" frameborder="0" class="bplDl-preview-iframe" style="width:100%; height:100%;"></iframe>';
            }
            else {
                echo '<p style="padding-left:10px;">Preview not available for this file type.</p>';
            }
    
            // Disable popout (overlay)
            if(!empty($data['disablePopout'])) {
                echo '<div style="width: 80px; height: 80px; position: absolute; opacity: 0; right: 22px; top: 25px;"></div>';
            }
        ?>
        <?php
    }
    

    public static function meta($id, $key, $default = false){
        if(!self::$meta){
            self::$meta = get_post_meta($id, 'ppv', true);
        }
        if(isset(self::$meta[$key])){
            return self::$meta[$key];
        }else {
            return $default;
        }
    }
}

Doctemplate::instance();