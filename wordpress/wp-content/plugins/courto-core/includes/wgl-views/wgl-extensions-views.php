<?php

if (!class_exists('WGL_Extensions_Views')) {
    /**
     * WGL Extensions Views
     *
     *
     * @package wgl-extensions\includes\wgl-views
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Extensions_Views
    {
        protected static $instance;
        public $post_id;

        /**
         * @var WP_Post
         */

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->post_id = get_the_ID();
        }

        public function get_post_views($postID, $grid = false)
        {
            $count_key = 'post_views_count';
            $counter = get_post_meta($postID, $count_key, true);
            if (empty($counter)) {
                $counter = '0';
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, $counter);
            }
            // If Font Awesome is needed, replace with: <span class="sl-icon far fa-eye"></span>
            return '<div class="post_views wgl-views" title="' . esc_attr__('Total Views', 'courto-core') . '">'
                . '<span class="post_views_inner">'
                    . '<span class="sl-icon far fa-eye"></span>'
                    . '<span class="sl-count">'
                        . esc_html($counter)
                        . '<span class="sl-count-text">'
                            . esc_html(_n('View', 'Views', (int) $counter, 'courto-core'))
                        . '</span>'
                    . '</span>'
                . '</span>'
            . '</div>';
        }

        public function set_post_views($postID)
        {
            if (current_user_can('administrator')) {
                return;
            }

            $user_ip = function_exists('wgl_get_ip') ? wgl_get_ip() : '0.0.0.0';
            $key = $user_ip . 'x' . $postID;
            $value = [$user_ip, $postID];
            $visited = get_transient($key);

            // check to see if the Post ID/IP ($key) address is currently stored as a transient
            if (false === $visited) {
                // store the unique key, Post ID & IP address for 12 hours if it does not exist
                set_transient($key, $value, 60*60*12);

                $count_key = 'post_views_count';
                $count = get_post_meta($postID, $count_key, true);
                if ('' == $count) {
                    $count = 0;
                    delete_post_meta($postID, $count_key);
                    add_post_meta($postID, $count_key, '0');
                } else {
                    $count++;
                    update_post_meta($postID, $count_key, $count);
                }
            }
        }
    }
}

function wgl_extensions_views() {
    return WGL_Extensions_Views::instance();
}

new WGL_Extensions_Views();
