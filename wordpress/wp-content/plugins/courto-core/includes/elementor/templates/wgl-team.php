<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-team.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\{
    WGL_Loop_Settings,
    WGL_Carousel_Settings,
    WGL_Elementor_Helper
};
use WGL_Framework;
use WGL_Extensions\Includes\WGL_Cursor;

/**
 * WGL Elementor Team Template
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Team
{
    private static $instance;
    private $attributes;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function render($self, $attributes)
    {
        $this->attributes = $attributes;

        $wrapper_classes = !empty($attributes['info_align']) ? ' a' . $attributes['info_align'] : '';
        $wrapper_classes .= ' display-' . $attributes['layout'];

        $query = $this->formalize_query();
        ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_member_grid($self, $attributes);
            }
            wp_reset_postdata();
        $posts_html = ob_get_clean();

        if ($attributes['use_carousel']) {
            $wrapper_classes .= ' carousel';
            $posts_html = $this->apply_carousel_settings($posts_html);
        }

        echo '<section class="wgl_module_team">',
            '<div class="team__members', esc_attr($wrapper_classes), '">',
                $posts_html,
            '</div>',
        '</section>';
    }

    public function support_archive_tax()
    {
        global $post;
        if(is_tax() && is_archive() && ! empty( $post->post_type ) && 'team' === $post->post_type){
            $tax_obj = get_queried_object();
            $term_id = $tax_obj->term_id ?? '';
            $taxonomies = [];
            if ($term_id) {
                $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
            }
            $this->attributes['taxonomies'] = $taxonomies;
        }
    }

    public function formalize_query()
    {
        $this->support_archive_tax();

        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);
        $query_args['post_type'] = 'team';

        return new \WP_Query($query_args);
    }

    public function render_member_single($attributes)
    {
        $this->attributes = $attributes;

        echo '<div class="team__member"', $this->get_wrapper_style(), '>';
            echo '<div class="member__info">';
                $this->member_highlighted_info();
                $this->member_name();
                $this->member_excerpt();
                $this->member_info();
                $this->member_socials(true);
            echo '</div>';
        echo '</div>';
    }

    public function render_member_single_image($attributes)
    {
        $this->attributes = $attributes;

        $sticky_image = WGL_Framework::get_option('team_single_sticky_image');
        if (class_exists('RWMB_Loader')) {
            $mb_sticky_image = rwmb_meta('mb_team_single_sticky_image');
            if ('yes' === $mb_sticky_image) {
                $sticky_image = true;
            } elseif ('no' === $mb_sticky_image) {
                $sticky_image = false;
            }
        }
        $sticky_class = '';
        if ($sticky_image) {
            $sticky_class = ' wgl-easy-sticky';
        }
        echo '<div class="member__thumbnail-wrap', $sticky_class, '">', $this->get_featured_image(true), '</div>';
    }

    public function render_member_grid($self, $attributes)
    {
        $this->attributes = $attributes;

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($self, $attributes);
        $highlighted_info = get_post_meta(get_the_ID(), 'highlighted_info', true);

        if (isset($attributes['cursor_tooltip']) && '' != $attributes['cursor_tooltip']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });
        }

        echo '<article class="team__member' . ( $this->attributes['use_carousel'] ? ' swiper-slide' : '' ) . '">';
        echo '<div class="member__wrapper">';

            echo '<div class="member__media' . ( isset($this->attributes['cursor_tooltip']) && (bool)$this->attributes['cursor_tooltip'] ? ' wgl-cursor-text' : '' ) . '"' . $cursor_data . '>',
                $this->get_featured_image();
            echo '</div>';

            echo '<div class="member__info-wrapper">';
                echo '<div class="member__info">';
                    echo '<div class="member__info-header">';
                        $this->member_name();
                    echo '</div>';
                    echo '<div class="member__info-footer">';
                        if ($highlighted_info) {
                            $this->member_highlighted_info();
                        }
                        $this->member_excerpt();
                        $this->member_socials();
                    echo '</div>';
                echo '</div>';
            echo '</div>';

        echo '</div>';
        echo '</article>';
    }

    protected function get_featured_image($single = false)
    {
        $id = get_the_ID();
        $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($id));

        if (!$wp_get_attachment_url) {
            // Bailout.
            return;
        }

        extract($this->attributes);

        if (class_exists('WGL_Extensions\Includes\WGL_Elementor_Helper')) {
            $dimensions = $thumbnail_dimensions ?? WGL_Elementor_Helper::get_image_dimensions(
                $img_size_array ?: $img_size_string,
                $img_aspect_ratio
            );
        } else {
            $dimensions = [];
        }

        if (!$dimensions) {
            $img_ratio = 1; // = width / height
            switch ($posts_per_row) {
                default:
                case '1':
                case '2':
                    $dimensions['width'] = 1000;
                    $dimensions['height'] = round($dimensions['width'] / $img_ratio);
                    break;
                case '3':
                case '4':
                case '5':
                case '6':
                    $dimensions['width'] = 740;
                    $dimensions['height'] = 850;
                    break;
            }
        }

        $img_url = aq_resize($wp_get_attachment_url, $dimensions['width'], $dimensions['height'], true, true, true) ?: $wp_get_attachment_url;
        $img_alt = get_post_meta(get_post_thumbnail_id($id), '_wp_attachment_image_alt', true);

        $is_single_page = $single_page ?? '';
        $tag_open = $tag_close = 'div';
        if (
            !$is_single_page
            && $thumbnail_linked
        ) {
            $permalink = esc_url(get_permalink($id));

            $tag_open = 'a href="' . $permalink . '"';
            $tag_close = 'a';
        }

        $featured_html = sprintf(
            '<img src="%s" class="thumbnail__featured" alt="%s">',
            esc_url($img_url),
            esc_attr($img_alt ?: '')
        );

        return sprintf(
            '<%s class="member__thumbnail">%s</%s>',
            $tag_open,
            $featured_html,
            $tag_close
        );
    }

    protected function member_name()
    {
        if ($this->attributes['hide_title']) {
            // Bailout.
            return;
        }

        $is_single_page = $this->attributes['single_page'] ?? '';

        $tag_open = '<span>';
        $tag_close = '</span>';

        $has_link = !$is_single_page && $this->attributes['heading_linked'];
        if ($has_link) {
            $permalink = esc_url(get_permalink(get_the_ID()));

            $tag_open = '<a href="' . $permalink . '">';
            $tag_close = '</a>';
        }

        $member_name = $tag_open . get_the_title() . $tag_close;

        printf(
            '<%1$s class="member__name">%2$s</%1$s>',
            $is_single_page ? 'h1' : 'h2',
            $member_name
        );
    }

    protected function member_highlighted_info()
    {
        if ($this->attributes['hide_highlited_info']) {
            // Bailout.
            return;
        }

        $highlighted_info = get_post_meta(get_the_ID(), 'highlighted_info', true);

        if ($highlighted_info) {
            echo '<div class="member__highlighted">',
                esc_html($highlighted_info),
            '</div>';
        }
    }

    protected function member_excerpt()
    {
        if ($this->attributes['hide_content']) {
            // Bailout.
            return;
        }

        $post = get_post(get_the_ID());

        $is_single_page = $this->attributes['single_page'] ?? '';

        $excerpt = $post->post_excerpt ?: $post->post_content;
        $excerpt = $is_single_page ? $post->post_excerpt : $excerpt;
        $excerpt = preg_replace('~\[[^\]]+\]~', '', $excerpt);
        $excerpt = strip_tags($excerpt);

        if (!empty($this->attributes['content_limit'])) {
            $excerpt = WGL_Framework::modifier_character($excerpt, $this->attributes['content_limit'], '');
        }

        $excerpt && print '<div class="member__excerpt">' . $excerpt . '</div>';
    }

    protected function member_socials($single = false)
    {
        if ($this->attributes['hide_socials']) {
            // Bailout.
            return;
        }

        $extra_class = !empty($this->attributes['socials_official_colors']['idle']) ? ' socials-official-idle' : '';
        $extra_class .= !empty($this->attributes['socials_official_colors']['hover']) ? ' socials-official-hover' : '';

        $socials = '';
        $link_target = get_post_meta(get_the_ID(), 'soc_icon_target', true);
        $target = !empty($link_target) ? ' target="_blank"' : '';

        $mb_socials = get_post_meta(get_the_ID(), 'soc_icon', true);
        if ($mb_socials) {
            for ($i = 0, $count = count($mb_socials); $i < $count; $i++) {
                $icon = $mb_socials[$i];
                $name = $icon['select'] ?: '';
                $href = $icon['link'] ?: '#';
                if ($icon['select']) {
                    $socials .= '<a href="' . $href . '" class="social__icon ' . $name . '" ' . $target . '></a>';
                }
            }
        }
        $socials && print '<div class="member__socials' . $extra_class .'">' . $socials . '</div>';
    }

    protected function member_info()
    {
        $info_array = get_post_meta(get_the_ID(), 'info_items', true);

        if (!$info_array) {
            // Bailout.
            return;
        }

        for ($i = 0, $count = count($info_array); $i < $count; $i++) {
            $info = $info_array[$i];
            $info_name = !empty($info['name']) ? $info['name'] : '';
            $info_description = !empty($info['description']) ? $info['description'] : '';
            $info_link = !empty($info['link']) ? $info['link'] : '';

            if (
                !$info_name
                || !$info_description
            ) {
                continue;
            }

            echo '<div class="info__item">',
                $info_name ? '<h5>' . esc_html($info_name) . '</h5>' : '',
                $info_link ? '<a href="' . esc_url($info_link) . '">' : '',
                    '<span>',
                        esc_html($info_description),
                    '</span>',
                $info_link ? '</a>' : '',
            '</div>';
        }
    }

    protected function get_wrapper_style()
    {
        $bg_id = get_post_meta(get_the_ID(), 'mb_info_bg', true);
        $bg_url = wp_get_attachment_url($bg_id);
        $bg_image_style = $bg_url ? 'background-image: url(' . esc_url($bg_url) . '); ' : '';

        $bg_color = get_post_meta(get_the_ID(), 'info_bg_color', true);
        $bg_color_style = $bg_color ? 'background-color: ' . $bg_color .';' : '';

        $bg_styles = ($bg_image_style || $bg_color_style) ? $bg_image_style . $bg_color_style : '';

        return $bg_styles ? ' style="'.$bg_styles.'"' : '';
    }

    protected function apply_carousel_settings($posts_html)
    {
        $this->attributes['slides_per_row'] = $this->attributes['posts_per_row'];

        return WGL_Carousel_Settings::init($this->attributes, $posts_html);
    }

}
