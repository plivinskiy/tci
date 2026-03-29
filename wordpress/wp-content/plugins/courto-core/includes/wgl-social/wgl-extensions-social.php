<?php

if (!class_exists('WGL_Extensions_Social')) {
    /**
     * WGL Extensions Social
     *
     *
     * @package wgl-extensions\includes\wgl-social
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Extensions_Social
    {
        protected static $instance;

        /**
         * @var WP_Post
         */
        private $post_id;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        private function __construct()
        {
            $this->post_id = get_the_ID();
        }

        public function post_share()
        {
            $shares = WGL_Framework::get_option('post_shares');
            $default = [
                'telegram' => '0',
                'reddit' => '0',
                'twitter' => '1',
                'whatsapp' => '0',
                'facebook' => '1',
                'pinterest' => '1',
                'linkedin' => '1',
            ];
            $shares = array_merge($default, array_intersect_key((array) $shares, $default));

            // Render
            echo '<div class="share_social-wpapper">';

            if ($shares['telegram']) {
                echo '<a class="share_link share_telegram"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://telegram.me/share/url?url=<URL>&amp;text=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-telegram-plane">',
                    '<span class="share_name">',
                    esc_html__('Telegram', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['reddit']) {
                echo '<a class="share_link share_reddit"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://reddit.com/submit?url=<URL>&amp;title=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-reddit-alien">',
                    '<span class="share_name">',
                    esc_html__('Reddit', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['whatsapp']) {
                echo '<a class="share_link share_whatsapp"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://wa.me/?text=' . get_permalink().' - '.get_the_title()),
                    '"',
                    '>',
                    '<span class="fa-whatsapp">',
                    '<span class="share_name">',
                    esc_html__('Whatsapp', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['twitter']) {
                echo '<a class="share_link share_twitter"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://twitter.com/intent/tweet?text=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-x-twitter">',
                    '<span class="share_name">',
                    esc_html__('Twitter', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['facebook']) {
                echo '<a class="share_link share_facebook"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://www.facebook.com/sharer/sharer.php?u=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-facebook-f">',
                    '<span class="share_name">',
                    esc_html__('Facebook', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            $img_url = wp_get_attachment_image_url(get_post_thumbnail_id($this->post_id), 'full');
            if ($shares['pinterest'] && $img_url) {
                echo '<a class="share_link share_pinterest"',
                    ' target="_blank"',
                    ' href="' . esc_url('https://pinterest.com/pin/create/button/?url=' . get_permalink() . '&media=' . $img_url) . '"',
                    '>',
                    '<span class="fa-pinterest-p">',
                    '<span class="share_name">',
                    esc_html__('Pinterest', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['linkedin']) {
                echo '<a class="share_link share_linkedin"',
                    ' href="',
                    esc_url('http://www.linkedin.com/shareArticle?mini=true&url=' . substr(urlencode(get_permalink()), 0, 1024)),
                    '&title=',
                    esc_attr(substr(urlencode(html_entity_decode(get_the_title())), 0, 200)),
                    '"',
                    ' target="_blank"',
                    '>',
                    '<span class="fa-linkedin-in">',
                    '<span class="share_name">',
                    esc_html__('Linkedin', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            };

            echo '</div>';
        }

        public function render_post_share()
        {
            ob_start();
                $this->post_share();
            $share = ob_get_clean();

            echo apply_filters('wgl_render_post_share', $share);
        }

        public function post_list_share()
        {
            $shares_arr = WGL_Framework::get_option('post_shares');
            $def_shares = [
                'telegram' => '0',
                'reddit' => '0',
                'whatsapp' => '0',
                'twitter' => '1',
                'facebook' => '1',
                'pinterest' => '1',
                'linkedin' => '1',
            ];
            $shares = array_merge($def_shares, array_intersect_key($shares_arr, $def_shares));

            echo '<div class="share_post-container">';
            echo '<div class="share_social-icon"></div>';
            echo '<div class="share_social-wpapper">';

            if ($shares['telegram']) {
                echo '<a class="share_link share_telegram"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://telegram.me/share/url?url=<URL>&amp;text=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-telegram-plane">',
                    '<span class="share_name">',
                    esc_html__('Telegram', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['reddit']) {
                echo '<a class="share_link share_reddit"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://reddit.com/submit?url=<URL>&amp;title=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-reddit-alien">',
                    '<span class="share_name">',
                    esc_html__('Reddit', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['whatsapp']) {
                echo '<a class="share_link share_whatsapp"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://wa.me/?text=' . get_permalink().' - '.get_the_title()),
                    '"',
                    '>',
                    '<span class="fa-whatsapp">',
                    '<span class="share_name">',
                    esc_html__('Whatsapp', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['twitter']) {
                echo '<a class="share_link share_twitter"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://twitter.com/intent/tweet?text=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-x-twitter">',
                    '<span class="share_name">',
                    esc_html__('Twitter', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['facebook']) {
                echo '<a class="share_link share_facebook"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('https://www.facebook.com/sharer/sharer.php?u=' . get_permalink()),
                    '"',
                    '>',
                    '<span class="fa-facebook-f">',
                    '<span class="share_name">',
                    esc_html__('Facebook', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            $img_url = wp_get_attachment_image_url(get_post_thumbnail_id($this->post_id), 'full');
            if ($shares['pinterest'] && $img_url) {
                echo '<a class="share_link share_pinterest"',
                    ' target="_blank"',
                    ' href="' . esc_url('https://pinterest.com/pin/create/button/?url=' . get_permalink() . '&media=' . $img_url) . '"',
                    '>',
                    '<span class="fa-pinterest-p">',
                    '<span class="share_name">',
                    esc_html__('Pinterest', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            }

            if ($shares['linkedin']) {
                echo '<a class="share_link share_linkedin"',
                    ' href="',
                    esc_url('http://www.linkedin.com/shareArticle?mini=true&url=' . substr(urlencode(get_permalink()), 0, 1024)),
                    '&title=',
                    esc_attr(substr(urlencode(html_entity_decode(get_the_title())), 0, 200)),
                    '"',
                    ' target="_blank"',
                    '>',
                    '<span class="fa-linkedin-in">',
                    '<span class="share_name">',
                    esc_html__('Linkedin', 'courto-core'),
                    '</span>',
                    '</span>',
                    '</a>';
            };

            echo '</div>'; // share_social-wpapper
            echo '</div>'; // share_post-container
        }

        public function render_post_list_share()
        {
            ob_start();
                $this->post_list_share();
            $share = ob_get_clean();

            echo apply_filters('wgl_render_post_list_share', $share);
        }

        public function page_social_shares()
        {
            $visibility = WGL_Framework::get_mb_option('soc_icon_style', 'mb_customize_soc_shares', 'on');
            $facebook = WGL_Framework::get_mb_option('soc_icon_facebook', 'mb_customize_soc_shares', 'on');
            $twitter = WGL_Framework::get_mb_option('soc_icon_twitter', 'mb_customize_soc_shares', 'on');
            $linkedin = WGL_Framework::get_mb_option('soc_icon_linkedin', 'mb_customize_soc_shares', 'on');
            $tumblr = WGL_Framework::get_mb_option('soc_icon_tumblr', 'mb_customize_soc_shares', 'on');

            $pinterest = WGL_Framework::get_mb_option('soc_icon_pinterest', 'mb_customize_soc_shares', 'on');
            if ($pinterest) {
                $img_url = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
                $pinterest = $pinterest && $img_url;
            }

            $offset = WGL_Framework::get_option('soc_icon_offset');
            if (
                class_exists('RWMB_Loader')
                && 0 !== get_queried_object_id()
                && 'on' === rwmb_meta('mb_customize_soc_shares')
            ) {
                $offset = [];
                $offset['margin-top'] = rwmb_meta('mb_soc_icon_offset');
                $offset['units'] = 'percent' === rwmb_meta('mb_soc_icon_offset_units') ? '%' : 'px';
            }

            $share_class = !empty($offset['units']) && '%' === $offset['units'] ? ' fixed' : '';
            $share_class .= 'hovered' === $visibility ? ' appearence-hovered' : '';

            $style = isset($offset['margin-top']) && '' !== $offset['margin-top'] ? 'top: ' . (int) $offset['margin-top'] . $offset['units'] . ';' : '';
            $style = $style ? ' style="' . $style . '"' : '';

            echo '<section class="wgl-page-socials', esc_attr($share_class), '"', $style, '>';
            if ('hovered' === $visibility) {
                echo '<div class="socials__desc">',
                    '<span class="social__icon"><i class="fas fa-share-alt"></i></span>',
                    '<span class="social__name">',
                    apply_filters('wgl_theme/socials/description', esc_html__('Socials', 'courto-core')),
                    '</span>',
                    '</div>';
            }

            echo '<ul class="socials__list">';

            if ($twitter) {
                echo '<li>',
                    '<a class="social__link twitter"',
                    ' href="',
                    esc_url('https://twitter.com/intent/tweet?text=' . get_the_title() . '&amp;url=' . get_permalink()),
                    '"',
                    ' target="_blank"',
                    '>',
                    '<span class="social__icon"><i class="fab fa-x-twitter"></i></span>',
                    '<span class="social__name">',
                    esc_html__('Twitter', 'courto-core'),
                    '</span>',
                    '</a>',
                    '</li>';
            }

            if ($facebook) {
                echo '<li>',
                    '<a class="social__link facebook"',
                    ' href="',
                    esc_url('https://www.facebook.com/share.php?u=' . get_permalink()),
                    '"',
                    ' target="_blank"',
                    '>',
                    '<span class="social__icon"><i class="fab fa-facebook"></i></span>',
                    '<span class="social__name">',
                    esc_html__('Facebook', 'courto-core'),
                    '</span>',
                    '</a>',
                    '</li>';
            }

            if ($pinterest) {
                echo '<li>',
                    '<a class="social__link pinterest"',
                    ' href="' . esc_url('https://pinterest.com/pin/create/button/?url=' . get_permalink() . '&media=' . $img_url) . '"',
                    ' target="_blank"',
                    '>',
                    '<span class="social__icon"><i class="fab fa-pinterest-p"></i></span>',
                    '<span class="social__name">',
                    esc_html__('Pinterest', 'courto-core'),
                    '</span>',
                    '</a>',
                    '</li>';
            }

            if ($linkedin) {
                echo '<li>',
                    '<a class="social__link linkedin"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('http://www.linkedin.com/shareArticle?mini=true&url=' . substr(urlencode(get_permalink()), 0, 1024)),
                    '&title=',
                    esc_attr(substr(urlencode(html_entity_decode(get_the_title())), 0, 200)),
                    '"',
                    '>',
                    '<span class="social__icon"><i class="fab fa-linkedin-in"></i></span>',
                    '<span class="social__name">',
                    esc_html__('Linkedin', 'courto-core'),
                    '</span>',
                    '</a>',
                    '</li>';
            }

            if ($tumblr) {
                echo '<li>',
                    '<a class="social__link tumblr"',
                    ' target="_blank"',
                    ' href="',
                    esc_url('http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()) . '&amp;name=' . urlencode(get_the_title()) . '&amp;description=' . urlencode(get_the_excerpt())),
                    '"',
                    '>',
                    '<span class="social__icon"><i class="fab fa-tumblr"></i></span>',
                    '<span class="social__name">',
                    esc_html__('Tumblr', 'courto-core'),
                    '</span>',
                    '</a>',
                    '</li>';
            }

            $custom_share = WGL_Framework::get_option('add_custom_share');
            if ($custom_share) {
                for ($i = 1; $i <= 6; $i++) {
                    ${'share_name' . $i} = WGL_Framework::get_option('share_name-' . $i);
                    ${'share_link' . $i} = WGL_Framework::get_option('share_link-' . $i);
                    ${'share_icon' . $i} = WGL_Framework::get_option('share_icons-' . $i);

                    if (!empty(${'share_link' . $i})) {
                        echo '<li><a class="social__link custom" href="'.esc_url(${'share_link' . $i}).'">'.
                                '<span class="social__icon"><i class="'.esc_attr(${'share_icon' . $i}).'"></i></span>'.
                                '<span class="social__name">'.esc_html(${'share_name' . $i}).'</span>'.
                            '</a></li>';
                    }
                }
            }

            echo '</ul>';
            echo '</section>';
        }

        public function render_social_shares()
        {
            ob_start();
                $this->page_social_shares();
            $share = ob_get_clean();

            echo apply_filters('wgl_render_social_shares', $share);
        }
    }
}

if (!function_exists('wgl_extensions_social')) {
    function wgl_extensions_social()
    {
        return WGL_Extensions_Social::instance();
    }
    wgl_extensions_social();
}
