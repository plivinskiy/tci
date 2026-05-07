<?php
namespace PPV\Services;

class DocTemplate {

    public static function html($data) {

        $parse_dim = function ($prop, $default, $type = 'width') {
            if (is_array($prop)) {
                if (isset($prop[$type]) && $prop[$type] !== '') {
                    return $prop[$type] . (isset($prop['unit']) ? $prop['unit'] : 'px');
                }
                return $default;
            }
            $val = !empty($prop) ? $prop : $default;
            return is_numeric($val) ? $val . 'px' : $val;
        };

        $w_d = $parse_dim(isset($data['width']) ? $data['width'] : '', '100%', 'width');
        $w_t = $parse_dim(isset($data['width_tablet']) ? $data['width_tablet'] : '', $w_d, 'width');
        $w_m = $parse_dim(isset($data['width_mobile']) ? $data['width_mobile'] : '', $w_t, 'width');

        $h_d = $parse_dim(isset($data['height']) ? $data['height'] : '', '600px', 'height');
        $h_t = $parse_dim(isset($data['height_tablet']) ? $data['height_tablet'] : '', $h_d, 'height');
        $h_m = $parse_dim(isset($data['height_mobile']) ? $data['height_mobile'] : '', $h_t, 'height');

        $unique_id = 'de_' . uniqid();

        ob_start();
?>
<style>
    .ppv_container.<?php echo esc_attr($unique_id);

?> {
        width: <?php echo esc_attr($w_d);
?>;
        height: <?php echo esc_attr($h_d);
?>;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    @media (max-width: 991px) {
        .ppv_container.<?php echo esc_attr($unique_id);

?> {
            width: <?php echo esc_attr($w_t);
?>;
            height: <?php echo esc_attr($h_t);
?>;
        }
    }

    @media (max-width: 767px) {
        .ppv_container.<?php echo esc_attr($unique_id);

?> {
            width: <?php echo esc_attr($w_m);
?>;
            height: <?php echo esc_attr($h_m);
?>;
        }
    }

    .<?php echo esc_attr($unique_id);

?>.document-preview {
        width: 100%;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .<?php echo esc_attr($unique_id);

?>iframe {
        width: 100%;
        height: 100%;
        flex: 1;
        border: none;
    }

    .ppv-loading {
        width: inherit;
        position: absolute;
        top: 50%;
        left: 0;
        font-family: sans-serif;
        color: #666;
        z-index: 1;
        display: flex;
        justify-content: center;
    }
</style>
<?php
        $base_url = '//docs.google.com/gview?embedded=true&url=';

        if ($data['doc'] == '') {
            echo '<h2>Ooops... You forgot to Select a document. Please select a file or paste a external document link to show here. </h2>';
        }
        else {
            $is_download_allowed = false;
            $dl_flag = isset($data['download']) ? $data['download'] : false;
            if (in_array($dl_flag, ['1', 1, 'true', true, 'yes'], true)) {
                $is_download_allowed = true;
                $access_denied_msg = '';

                if (isset($data['_de_download_access']) && $data['_de_download_access'] === 'loggedin' && !is_user_logged_in()) {
                    $is_download_allowed = false;
                    $access_denied_msg = isset($data['_de_download_access_message']) ? $data['_de_download_access_message'] : 'Login to download';
                }

                if ($is_download_allowed && isset($data['_de_download_access']) && $data['_de_download_access'] === 'roles') {
                    $is_download_allowed = false; // Default to blocked for roles mode
                    $access_denied_msg = isset($data['_de_download_access_message']) ? $data['_de_download_access_message'] : 'Access Restricted';
                    if (is_user_logged_in()) {
                        $allowed_roles = isset($data['_de_download_access_roles']) ? (array)$data['_de_download_access_roles'] : [];
                        if (!empty($allowed_roles)) {
                            $user = wp_get_current_user();
                            $user_roles = (array)$user->roles;
                            if (!empty(array_intersect($allowed_roles, $user_roles))) {
                                $is_download_allowed = true;
                                $access_denied_msg = '';
                            }
                        }
                    }
                }

                // Check IP limit
                if (isset($data['limit_reached']) && $data['limit_reached']) {
                    $is_download_allowed = false;
                    $access_denied_msg = ''; // IP limit has its own button/message logic
                }
            }

            // Prepare download button HTML
            $download_btn_html = '';
            if (isset($data['limit_reached']) && $data['limit_reached'] && in_array($dl_flag, ['1', 1, 'true', true, 'yes'], true)) {
                $download_btn_html = '<button disabled style="background: transparent; padding: 4px 10px; border-radius: 4px; border: 1px solid #ff4d4d; color: #ff4d4d; cursor: not-allowed;" title="Download limit reached for your IP.">Limit Reached</button>';
                $is_download_allowed = false; // Ensure logic below respects this
            }
            elseif ($is_download_allowed) {
                // Count HTML
                $download_count_html = '';
                $show_count_flag = isset($data['_de_download_show_count']) ? $data['_de_download_show_count'] : false;
                if (in_array($show_count_flag, ['1', 1, 'true', true, 'yes'], true)) {
                    $count = get_post_meta($data['id'], '_de_download_count', true);
                    if (!$count)
                        $count = 0;
                    $download_count_html = '<span class="ppv-download-count" style="font-size: 12px; color: #cececf; border: 1px solid #cececf; padding: 3px 10px; border-radius: 4px;">' . esc_html($count) . ' downloads</span>';
                }

                $btn_label = isset($data['downloadButtonText']) && !empty($data['downloadButtonText']) ? esc_html($data['downloadButtonText']) : 'Download';

                $email_gate_flag = isset($data['_de_email_gate']) ? $data['_de_email_gate'] : false;
                if (in_array($email_gate_flag, ['1', 1, 'true', true, 'yes'], true)) {
                    $download_btn_html = '<button class="ppv_download_bttn ppv-email-gate-btn" data-doc-id="' . esc_attr($data['id']) . '" style="margin-bottom:10px; background: transparent; padding: 4px 10px; border-radius: 4px; border: 1px solid #cececf; color: #cececf; ">' . $btn_label . '</button>';
                }
                else {
                    $dl_attr = '';
                    $target_attr = '';
                    $href = esc_url($data['doc']);
                    $behavior = isset($data['_de_download_behavior']) ? $data['_de_download_behavior'] : 'download';
                    if ($behavior === 'newtab') {
                        $target_attr = ' target="_blank"';
                    }
                    else {
                        if (!empty($data['_de_download_filename'])) {
                            $dl_attr = ' download="' . esc_attr($data['_de_download_filename']) . '"';
                        }
                        else {
                            $dl_attr = ' download';
                        }
                    }
                    $download_btn_html = '<a class="s_pdf_download_link" style="display: flex;"  href="' . $href . '"' . $target_attr . $dl_attr . '><button style="background: transparent; padding: 4px 10px; border-radius: 4px; border: 1px solid #cececf; color: #cececf; cursor: pointer;" class="ppv_download_bttn ppv-direct-download" data-behavior="' . esc_attr($behavior) . '" data-doc-id="' . esc_attr($data['id']) . '">' . $btn_label . '</button></a>';
                }
                $download_btn_html .= $download_count_html;
            }
            elseif (!empty($access_denied_msg)) {
                $download_btn_html = '<span class="de-access-denied-msg" style="font-size: 13px; color: #666; font-style: italic; border: 1px dashed #ccc; padding: 5px 10px; border-radius: 4px; display: inline-block;">' . esc_html($access_denied_msg) . '</span>';
            }


            $has_download_ui = !empty($download_btn_html);
            // Handle Position logic
            $position = isset($data['_de_download_position']) ? $data['_de_download_position'] : 'above';


            $frame_url = $base_url . $data['doc'];
            $is_google = isset($data['googleDrive']) && in_array($data['googleDrive'], ['1', 1, 'true', true, 'yes'], true);
            if ($is_google) {
                $frame_url = str_replace("view", "preview", $data['doc']);
            }

?>
<div class="ppv_container <?php echo esc_attr($unique_id); ?>">

    <?php

            $filename = basename($data['doc']);
            $show_filename_in_toolbar = isset($data['showName']) && $data['showName'];

            // Toolbar rendering
            if ($position === 'toolbar' && ($show_filename_in_toolbar || $has_download_ui)) {
                $justify_content = !$show_filename_in_toolbar ? 'end' : 'space-between';
                echo '<div class="ppv-toolbar" style="justify-content: ' . $justify_content . '">';
                if ($show_filename_in_toolbar) {
                    echo '<span class="ppv-filename">' . esc_html($filename) . '</span>';
                }
                if ($has_download_ui) {
                    echo '<div class="ppv-toolbar-right">' . $download_btn_html . '</div>';
                }
                echo '</div>';
            }
            elseif ($has_download_ui && ($position === 'above' || empty($position))) {
                // If showName is true but not in toolbar position, we still need to show it somewhere if it was removed from above
                if (isset($data['showName']) && $data['showName']) {
                    echo '<p style="padding-left:10px;">' . esc_html($filename) . '</p>';
                }
                echo '<p style="padding-left:10px;">' . $download_btn_html . '</p>';
            }
            elseif (isset($data['showName']) && $data['showName'] && ($position === 'above' || empty($position))) {
                echo '<p style="padding-left:10px;">' . esc_html($filename) . '</p>';
            }
?>
    <div class="ppv-loading">PDF Loading...</div>
    <div class="document-preview" style="height: inherit; width: inherit;">
        <iframe style="width: 100%; height: 100%;" src="<?php echo esc_url($frame_url)?>" frameborder="0"></iframe>
    </div>
    <?php
            // Below element
            if ($position === 'below' && ($show_filename_in_toolbar || $has_download_ui)) {
                // correct way to write this
                $justify_content = !$show_filename_in_toolbar ? 'end' : 'space-between';
                echo '<div class="ppv-toolbar" style="justify-content: ' . $justify_content . '">';
                if ($show_filename_in_toolbar) {
                    echo '<span class="ppv-filename">' . esc_html($filename) . '</span>';
                }
                if ($has_download_ui) {
                    echo '<div class="ppv-toolbar-right">' . $download_btn_html . '</div>';
                }
                echo '</div>';
            }
?>
</div>

<?php

            // Email Gate Modal (Render hidden div)
            $email_gate_flag = isset($data['_de_email_gate']) ? $data['_de_email_gate'] : false;
            if ($is_download_allowed && in_array($email_gate_flag, ['1', 1, 'true', true, 'yes'], true)) {
                $modal_label = isset($data['_de_download_label']) && !empty($data['_de_download_label']) ? $data['_de_download_label'] : (isset($data['downloadButtonText']) ? $data['downloadButtonText'] : 'Download');
                self::render_email_gate_modal($data['id'], $modal_label);
            }
        }
        $output = ob_get_clean();
        return apply_filters('ppv_shortcode_html', $output, $data);

    }

    private static function render_email_gate_modal($id, $label) {
?>
<div class="ppv-email-gate-modal-wrapper" id="ppv-gate-modal-<?php echo esc_attr($id); ?>">
    <div class="ppv-email-gate-modal-content">
        <button type="button" class="ppv-close-modal">&times;</button>
        <h3>Download Document</h3>
        <form class="ppv-email-gate-form">
            <input type="hidden" name="document_id" value="<?php echo esc_attr($id); ?>" />
            <div>
                <label>Name</label>
                <input type="text" name="name" required />
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" required />
            </div>
            <button type="submit">
                <?php echo esc_html($label); ?>
            </button>
            <p class="ppv-gate-secure-text">Your details are saved securely.</p>
        </form>
    </div>
</div>
<?php
    }

}