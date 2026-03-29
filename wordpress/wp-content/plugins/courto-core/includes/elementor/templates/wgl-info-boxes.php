<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-infoboxes.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Icons_Manager, Plugin};

use WGL_Extensions\{
    Includes\WGL_Icons,
    Includes\WGL_Cursor
};
/**
 * WGL Elementor Info Boxes Template
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLInfoBoxes
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function render($self, $atts)
    {
        extract($atts);

        if (isset($atts['cursor_tooltip']) && '' != $atts['cursor_tooltip']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });
        }

        $ib_media = $infobox_content = $ib_button = $module_link_html = $infobox_titles = '';

        $wrapper_classes = $layout ? ' wgl-layout-' . $layout : '';

        $kses_allowed_html = [
            'a' => [
                'href' => true, 'title' => true,
                'class' => true, 'style' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'small' => ['class' => true, 'style' => true],
            'p' => ['class' => true, 'style' => true],
            'i' => ['class' => true, 'style' => true],
        ];

        if (!empty($wgl_mobile_breakpoint)){
            $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
            $key = array_search($wgl_mobile_breakpoint, $active_devices);
            $all_breakpoints = array_slice($active_devices, $key);
            foreach($all_breakpoints as $breakpoint){
                $wrapper_classes .= ' breakpoint_on-' . $breakpoint;
            }
        }

        // Media
        if (!empty($icon_type)) {
            $media = new WGL_Icons;
            $ib_media .= '<div class="wgl-infobox-media_wrapper">'.$media->build($self, array_merge((array)$atts, ['button_animation_style' => null]), []).'</div>';
        }

        // Title
        foreach ( $items as $item ) {
            $infobox_titles .= !empty($item['ib_title']) ? '<span class="elementor-repeater-item-'.$item['_id'] . '">'.wp_kses($item['ib_title'], $kses_allowed_html) . '</span>' : '';
        }

        $infobox_title = '<div class="wgl-infobox-title_wrapper">';
            $infobox_title .= !empty($ib_subtitle) ? '<div class="wgl-infobox_subtitle">' . wp_kses($ib_subtitle, $kses_allowed_html) . '</div>' : '';
            $infobox_title .= '<' . esc_attr($title_tag) . ' class="wgl-infobox_title">';
                $infobox_title .= ('top_left' === $layout) ? $ib_media : '' ;
                $infobox_title .= !!$infobox_titles ? '<span class="wgl-infobox_title-idle">' . $infobox_titles . '</span>' : '';
                $infobox_title .= ('top_right' === $layout) ? $ib_media : '' ;
            $infobox_title .= '</' . esc_attr($title_tag) . '>';
        $infobox_title .= '</div>';

        // Content
        if (!empty($ib_content)) {
            $infobox_content = '<div class="wgl-infobox-content_wrapper">';
                $infobox_content .= '<div class="wgl-infobox-content">';
                    $infobox_content .= $ib_content;
                $infobox_content .= '</div>';
            $infobox_content .= '</div>';
        }

	    // BG Text
	    $infobox_bg_text = !empty($ib_bg_text) ? '<div class="wgl-infobox_bg_text">' . wp_kses($ib_bg_text, $kses_allowed_html) . '</div>' : '';

        // Link
        if (!empty($link['url'])) {
            $self->add_link_attributes('link', $link);
        }

        // Read more button
        if ($add_read_more) {
            $self->add_render_attribute('btn', 'class',
                [
                    'wgl-infobox_button',
                    'wgl-widget__button',
                    $button_type ?? '',
                    ! empty( $read_more_icon_align ) ? 'align-icon-' . $read_more_icon_align : '',
                    !$read_more_text ? 'no_text' : ''
                ]
            );

            $btn_icon = '';
            // ↓ Icon
            if ('font' === $read_more_icon_type) {
                if(isset($read_more_icon_fontawesome['value'])) {
                    $migrated = isset( $atts['__fa4_migrated']['read_more_icon_fontawesome'] );
                    $is_new = Icons_Manager::is_migration_allowed();
                    if ( $is_new || $migrated ) {
                        ob_start();
                        Icons_Manager::render_icon($read_more_icon_fontawesome, ['class' => 'read-more-icon', 'aria-hidden' => 'true']);
                        $btn_icon = ob_get_clean();
                    }
                    if ('svg' === $read_more_icon_fontawesome['library']) {
                        $wrapper_icon = '<span class="read-more-icon read-more-svg">';
                        $wrapper_icon .= $btn_icon;
                        $wrapper_icon .= '</span>';
                        $btn_icon = $wrapper_icon;
                    }

                    if ( 'moving_icon' === $button_animation_style){
                        $btn_icon .= $btn_icon;
                    }

                    $btn_icon = !!$btn_icon ? '<span class="wgl-icon"> ' . $btn_icon . '</span>' : '';
                    $btn_icon = 'wgl-button' === $button_type ? '<div class="icon-wrapper">' . $btn_icon . '</div>' : $btn_icon;
                }
            }elseif('button-read-more' === $button_type){
                $btn_icon = '<span class="read-more-icon"></span>';
            }else{
                $self->add_render_attribute(['btn' => ['class' => [ 'no_media']]]);
            }

            // ↑ icon

            if (!empty($read_more_text) && ( 'letter_animation' === $button_animation_style )) {
                $letters = '';
                $len = mb_strlen($read_more_text, 'UTF-8');
                for ($i = 0; $i < $len; $i++) {
                    $value = mb_substr(esc_html($read_more_text), $i, 1, 'UTF-8');
                    $letters .= $value == ' ' ? ' ' : '<span class="letter">' . $value . '</span>';
                }
                $read_more_text = $letters;
            }else{
                $read_more_text = esc_html($read_more_text);
            }

            $ib_button = '<div class="wgl-button-wrapper'.('button-read-more' === $button_type ? ' rm_btn' : '' ).'">';
                $ib_button .= 'button-read-more' === $button_type ? '<div class="read-more-wrap">' : '';
                    $ib_button .= sprintf(
                        '<%s %s %s>',
                        $module_link ? 'div' : 'a',
                        $module_link ? '' : $self->get_render_attribute_string('link'),
                        $self->get_render_attribute_string('btn')
                    );
                        $ib_button .= 'wgl-button' === $button_type ? '<div class="button__content">' : '';
                            $ib_button .= $btn_icon ?: '';
                            $ib_button .= $read_more_text ? '<span class="button__text">' . $read_more_text . '</span>' : '';
                        $ib_button .= 'wgl-button' === $button_type ? '</div>' : '';
                    $ib_button .= $module_link ? '</div>' : '</a>';
                $ib_button .= 'button-read-more' === $button_type ? '</div>' : '';
            $ib_button .= '</div>';
        }

        if ($module_link && !empty($link['url'])) {
            $module_link_html = '<a class="wgl-infobox__link" ' . $self->get_render_attribute_string('link') . '></a>';
        }

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($self, $atts);

        // Render
        echo '<div class="wgl-infobox_bg_wrapper"></div>',
        '<div class="wgl-infobox' . ( isset($atts['cursor_tooltip']) && '' != $atts['cursor_tooltip'] ? ' wgl-cursor-text additional-cursor' : '' ) . '"' . $cursor_data . '>',
            $module_link_html,
            '<div class="wgl-infobox_wrapper', esc_attr($wrapper_classes), '">',

                ('top_left' === $layout || 'top_right' === $layout ? '' : $ib_media),
                '<div class="content_wrapper">',
                    $infobox_title,
                    $infobox_content,
                    $infobox_bg_text,
                    ( 'absolute' !== $button_position ? $ib_button : '' ),
                '</div>',
                ( 'absolute' !== $button_position ? '' : $ib_button ),
            '</div>',
        '</div>';
    }
}
