<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/templates/wgl-button.php`.
 */
namespace WGL_Extensions\Templates;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use WGL_Extensions\{
    Includes\WGL_Cursor,
    Includes\WGL_Icons
};

if ( ! class_exists( 'WGL_Button' ) ) {
    /**
     * WGL Elementor Button Template
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Button
    {
        public function render( $self, $_s )
        {

            $cursor = new WGL_Cursor;
            $cursor_data = $cursor->build($self, $_s);

            if (isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip']) {
                add_filter( 'wgl/courto_module_cursor', function () { return true; });
            }

            $self->add_render_attribute([
                'wrapper' => [
                    'class' => [
                        'wgl-button',
                        'wgl-widget__button',
                        ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' ),
                        ! empty( $_s[ 'icon_align' ] ) ? 'align-icon-' . $_s[ 'icon_align' ] : '',
                    ],
                ],
                'content' => [
                    'class' => [
                        'button__content',
                    ],
                ],
                'text' => [
                    'class' => 'button__text',
                ],
            ] );

            if ( isset($_s['button_animation_style']) && 'magnetic' === $_s[ 'button_animation_style' ] ) {
                $self->add_render_attribute([
                    'wrapper' => [
                        'data-magnetic-threshold' => $_s['button_magnetic_threshold']['size'] ?? 500,
                        'data-magnetic-strong' => $_s['button_magnetic_strong']['size'] ?? 0.5,
                    ],
                ]);
            }

            if ( isset($_s['button_animation_style']) && 'letter_animation' === $_s[ 'button_animation_style' ] ) {
                $button_letters = '';
                $len = mb_strlen($_s['text'], 'UTF-8');
                for ($i = 0; $i < $len; $i++) {
                    $value = mb_substr(esc_html($_s['text']), $i, 1, 'UTF-8');
                    $button_letters .= $value == ' ' ? ' ' : '<span class="letter">' . $value . '</span>';
                }
            }

            if ( ! empty( $_s[ 'hover_animation' ] ) ) {
                $self->add_render_attribute( 'wrapper', 'class', 'elementor-animation-' . $_s[ 'hover_animation' ] );
                $_s[ 'hover_animation' ] = ''; // clear hover_animation for image
            }

            if ( ! empty( $_s[ 'link' ][ 'url' ] ) ) {
                $self->add_link_attributes( 'wrapper', $_s[ 'link' ] );
                $self->add_render_attribute( 'wrapper', 'role', 'button' );
                $tag = 'a';
            }else{
                $tag = 'div';
            }

            $media_prefix = $_s[ 'media_prefix' ] ?? '';

            // Render
            echo '<'.$tag.' ', $self->get_render_attribute_string( 'wrapper' ), $cursor_data, '>';

            if (
                ! empty( $_s[ 'text' ] )
                || ! empty( $_s[ $media_prefix . 'icon_type' ] )
            ) {
                echo '<div ', $self->get_render_attribute_string( 'content' ), '>';

                if ( ! empty( $_s[ $media_prefix . 'icon_type' ] ) ) {
                    echo ( new WGL_Icons )->build( $self, $_s, $media_prefix );
                }

                if (!empty($_s['text'])) {
                    echo '<span ', $self->get_render_attribute_string('text'), '>',
                        $button_letters ?? $_s['text'],
                    '</span>';
                }

                echo '</div>';
            }

            if(isset($_s['button_animation_style']) && $_s['button_animation_style'] === 'highlight_animation'){
                echo '<svg class="highlight_svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 582.6 372.1" preserveAspectRatio="none"><path d="M272.4,78.5c137.7-24.2,257.7,4.4,268.1,63.8s-92.7,127.1-230.4,151.3 C172.5,317.8,52.5,289.2,42,229.8S134.8,102.7,272.4,78.5z"/><path d="M251.9,84.2c130.3-50.5,253.7-45.8,275.4,10.4c21.8,56.2-66.2,142.7-196.6,193.2 S77.1,333.7,55.3,277.5S121.5,134.7,251.9,84.2z"/></svg>';
            }

            echo '</'.$tag.'>';
        }
    }
}
