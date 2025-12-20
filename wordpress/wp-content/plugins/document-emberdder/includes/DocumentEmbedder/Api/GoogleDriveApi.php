<?php

class GoogleDriveApi{

    private $clientId = null;
    private $developerKey = null;
    private $appId = null;
    private $container = 'picker_container';
    private $fieldId = 'picker_field';
    private $buttonId = 'drive_button';

    public function __construct($developerKey, $clientId = '', $appId){
        $this->clientId = $clientId;
        $this->developerKey = $developerKey;
        $this->appId = $appId;
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_footer', [$this, 'initializePicker'], 100, 2);
        add_filter('script_loader_tag', [$this, 'tde_script_type_load'] , 10, 3);
    }

    public function setContainerId($containerId){
        $this->container = $containerId;
    }

    public function setFieldId($fieldId){
        $this->fieldId = $fieldId;
    }

    public function enqueueScripts(){
        wp_enqueue_script('google-drive-api');
        wp_enqueue_script('google-drive-client');
    }


    public function tde_script_type_load($tag, $handle, $src){
        if('google-drive-api' === $handle || 'google-drive-client' === $handle){
            return $tag = '<script async defer id="'.$handle.'" src="' . esc_url( $src ) . '"></script>';
        }
        return $tag;
    }

    public function initializePicker(){
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                function googleDrivePicker(options) {
                    let oauthToken;

                    // Create a button inside the given container
                    if (options.container) {
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.innerText = "Select from Google Drive";
                        btn.className = "google-drive-picker-btn"; 
                        options.container.appendChild(btn);

                        // Bind click → start auth + picker
                        btn.addEventListener("click", () => {
                            const client = google.accounts.oauth2.initTokenClient({
                                client_id: options.clientId,
                                scope: "https://www.googleapis.com/auth/drive.file",
                                callback: (response) => {
                                    oauthToken = response.access_token;
                                    gapi.load("picker", { callback: createPicker });
                                },
                            });
                            client.requestAccessToken();
                        });
                    }

                    function createPicker() {
                        if (!oauthToken) return;

                        const picker = new google.picker.PickerBuilder()
                            .addView(google.picker.ViewId.DOCS)
                            .setOAuthToken(oauthToken)
                            .setDeveloperKey(options.developerKey)
                            .setAppId(options.appId)
                            .setCallback(pickerCallback)
                            .build();
                        picker.setVisible(true);
                    }

                    function pickerCallback(data) {
                        if (data[google.picker.Response.ACTION] === google.picker.Action.PICKED) {
                            const fileId = data[google.picker.Response.DOCUMENTS][0][google.picker.Document.ID];
                            options.field.value = data.docs[0].embedUrl;
                        }
                    }
                }
                if(typeof googleDrivePicker != 'undefined'){
                    <?php
                    if(!empty($this->clientId) && !empty($this->developerKey) && !empty($this->appId)) {
                    ?>
                    googleDrivePicker({
                        appId: "<?php echo esc_html($this->appId) ?>",
                        clientId: "<?php echo esc_html($this->clientId)  ?>",
                        developerKey: "<?php echo esc_html($this->developerKey) ?>",
                        container: document.getElementById('<?php echo esc_html($this->container); ?>'),
                        field: document.getElementById('<?php echo esc_html($this->fieldId) ?>')
                    });
                    <?php } ?>
                }
            })
        </script>
        <?php
    }

}