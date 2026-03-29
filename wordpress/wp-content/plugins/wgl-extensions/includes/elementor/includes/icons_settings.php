<?php
namespace WGL_Extensions\Includes;

defined( 'ABSPATH' ) || exit;

use Elementor\{
    Controls_Manager,
    Control_Media,
    Utils,
    Icons_Manager,
    Group_Control_Image_Size
};
use WGL_Extensions\Includes\WGL_Elementor_Helper;

if ( ! class_exists( 'WGL_Icons' ) ) {
    /**
     * WGL Elementor Media Settings
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.6
     */
    class WGL_Icons
    {
        private static $instance;

        public function build($self, $atts, $pref = [])
        {
            return (new WGL_Icon_Builder())->build($self, $atts, $pref);
        }

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public static function init( $self, $attrs = [] )
        {
            if ( ! $self ) {
                // Bailout.
                return;
            }

            // Variables validation
            $section = $attrs['section'] ?? false;
            $prefix = $attrs['prefix'] ?? '';
            $section_label_suffix = $attrs['label'] ?? '';
            $use_group_control_image_size = $attrs['use_group_control_image_size'] ?? true;

            if ($section) {
                $self->start_controls_section(
                    $prefix . 'add_icon_image_section',
                    [
                        'label' => sprintf(esc_html__('%s Icon/Image', 'wgl-extensions'), $section_label_suffix),
                        'condition' => $attrs['condition'] ?? [],
                    ]
                );
            }

            $media_types_options = [
                '' => [
                    'title' => esc_html__( 'None', 'wgl-extensions' ),
                    'icon' => 'eicon-ban'
                ],
                'font' => [
                    'title' => esc_html__( 'Icon', 'wgl-extensions' ),
                    'icon' => 'far fa-smile',
                ],
                'image' => [
                    'title' => esc_html__( 'Image', 'wgl-extensions' ),
                    'icon' => 'fa fa-image',
                ],
            ];

            $self->add_control(
                $prefix . 'icon_type',
                [
                    'label' => esc_html__( 'Media Type', 'wgl-extensions' ),
                    'type' => Controls_Manager::CHOOSE,
                    'condition' => $attrs[ 'condition' ] ?? [],
                    'options' => $media_types_options,
                    'default' => $attrs[ 'default' ][ 'media_type' ] ?? '',
                ]
            );

            $self->add_control(
                $prefix . 'icon_fontawesome',
                [
                    'label' => esc_html__('Icon', 'wgl-extensions'),
                    'type' => Controls_Manager::ICONS,
                    'condition' => [$prefix . 'icon_type' => 'font'] + ($attrs['condition'] ?? []),
                    'label_block' => true,
                    'default' => $attrs['default']['icon'] ?? [],
                ]
            );

            $self->add_control(
                $prefix . 'icon_render_class',
                [
                    'label' => esc_html__('Icon Class', 'wgl-extensions'),
                    'type' => Controls_Manager::HIDDEN,
                    'condition' => [$prefix . 'icon_type' => 'font'] + ($attrs['condition'] ?? []),
                    'prefix_class' => 'elementor-widget-icon-box ',
                    'default' => 'wgl-icon-box',
                ]
            );

            $self->add_control(
                $prefix . 'thumbnail',
                [
                    'label' => esc_html__('Image', 'wgl-extensions'),
                    'type' => Controls_Manager::MEDIA,
			    'dynamic' => [  'active' => true],
                    'condition' => [$prefix . 'icon_type' => 'image'] + ($attrs['condition'] ?? []),
                    'label_block' => true,
                    'default' => ['url' => Utils::get_placeholder_image_src()],
                ]
            );

            $self->add_control(
                $prefix . 'image_render_class',
                [
                    'label' => esc_html__('Image Class', 'wgl-extensions'),
                    'type' => Controls_Manager::HIDDEN,
                    'condition' => [$prefix . 'icon_type' => 'image'] + ($attrs['condition'] ?? []),
                    'default' => 'wgl-image-box',
                    'prefix_class' => 'elementor-widget-image-box ',
                ]
            );

            if ($use_group_control_image_size) {
                $self->add_group_control(
                    Group_Control_Image_Size::get_type(),
                    [
                        'name' => $prefix . 'thumbnail',
                        'condition' => [$prefix . 'icon_type' => 'image'] + ($attrs['condition'] ?? []),
                        'default' => 'full',
                    ]
                );
            }

            if (!empty($attrs['output'])) {
                foreach ($attrs['output'] as $key => $value) {
                    $self->add_control(
                        $key,
                        $value
                    );
                }
            }

            if ($section) {
                $self->end_controls_section();
            }
        }

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }

    new WGL_Icons();
}

if ( ! class_exists( 'WGL_Icon_Builder' ) ) {
    /**
     * WGL Icon Build
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.6
     */
    class WGL_Icon_Builder
    {
        private static $instance;

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public function build($self, $atts, $pref)
        {
            $prefix = !empty($pref) ? $pref : '';

            $media_type = $atts[$prefix . 'icon_type'];
            $icon_fontawesome = $atts[$prefix . 'icon_fontawesome'];
            $thumbnail = $atts[$prefix . 'thumbnail'];

            if (
                'font' === $media_type && empty($icon_fontawesome)
                || 'image' === $media_type && empty($thumbnail)
            ) {
                // Bailout.
                return '';
            }

            $self->add_render_attribute( $prefix . 'media--icon', 'class', 'wgl-icon' );
            if ( ! empty( $atts[ 'hover_animation_icon' ] ) ) {
                $self->add_render_attribute( $prefix . 'media--icon', 'class', 'elementor-animation-' . $atts[ 'hover_animation_icon' ] );
            }

            // Wrapper Class
            $wrapper_class = $atts['wrapper_class'] ?? '';
            if ('image' === $media_type) { $wrapper_class .= 'img-wrapper'; }
            if ('font' === $media_type) { $wrapper_class .= 'icon-wrapper'; }
            $self->add_render_attribute($prefix . 'media--wrapper', 'class', [
                'media-wrapper',
                $wrapper_class
            ]);

            $media_tag = 'span';

            if ( ! empty( $atts[ 'link_t' ][ 'url' ] ) ) {
                $media_tag = 'a';
                $self->add_link_attributes($prefix . 'media--link', $atts['link_t']);
            }

            $icon_attributes = $self->get_render_attribute_string($prefix . 'media--icon');
            $link_attributes = $self->get_render_attribute_string($prefix . 'media--link');

            // Render
            $output = '<div ' . $self->get_render_attribute_string($prefix . 'media--wrapper') . '>';

            if (
                'font' === $media_type
                && ! empty( $icon_fontawesome[ 'value' ] )
            ) {
                $output .= '<';
                    $output .= implode(' ', [$media_tag, $icon_attributes, $link_attributes]);
                $output .= '>';

                if ('svg' === $icon_fontawesome['library']) {
                    $output .= '<span class="icon elementor-icon">';
                }

                // Icon migration
                $migrated = isset($atts['__fa4_migrated'][$prefix . 'icon_fontawesome']);
                $is_new = Icons_Manager::is_migration_allowed();
                if ($is_new || $migrated) {
                    ob_start();
                    Icons_Manager::render_icon($icon_fontawesome, ['class' => 'icon elementor-icon', 'aria-hidden' => 'true']);
                    $output .= ob_get_clean();
                } else {
                    $output .= '<i class="icon elementor-icon ' . esc_attr($icon_fontawesome['value']) . '"></i>';
                }

                if ('svg' === $icon_fontawesome['library']) {
                    $output .= '</span>';
                }

                $output .= '</' . $media_tag . '>';
            }

            if (
                'image' === $media_type
                && ! empty( $thumbnail[ 'url' ] )
            ) {
                $img_size_string = $atts['img_size_string'] ?? null;
                $img_size_array = $atts['img_size_array'] ?? null;
                $use_wgl_resizer = $img_size_string || $img_size_array;

                if (
                    $use_wgl_resizer
                    && $thumbnail[ 'id' ]
                ) {
                    $attachment_image_src = wp_get_attachment_image_src( $thumbnail[ 'id' ], 'full' );

                    if ( $attachment_image_src ) {
                        $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                            $img_size_array ?: $img_size_string,
                            $atts[ 'img_aspect_ratio' ] ?? '',
                            $attachment_image_src
                        );

                        $resized_img_url = aq_resize(
                            $thumbnail[ 'url' ],
                            $dimensions[ 'width' ],
                            $dimensions[ 'height' ],
                            true,
                            true,
                            true
                        ) ?: $thumbnail[ 'url' ];

                        $self->add_render_attribute(
                            'thumbnail',
                            [
                                'src' => esc_url( $resized_img_url ),
                                'alt' => Control_Media::get_image_alt( $thumbnail ),
                                'title' => Control_Media::get_image_title( $thumbnail ),
                                'loading' => 'lazy',
                            ]
                        );

                        $resized_img_html = '<img ' . $self->get_render_attribute_string( 'thumbnail' ) . '>';
                    }
                }

                if ( !empty( $atts[ 'hover_animation_image' ] ) ) {
                    $figure_class = ' elementor-animation-' . $atts[ 'hover_animation_image' ];
                }

                $output .= '<figure class="wgl-image-box_img' . ( $figure_class ?? '' ) . '">';

                $output .= '<' . $media_tag . ' ' . $link_attributes . '>';

                    $output .= $resized_img_html ?? Group_Control_Image_Size::get_attachment_image_html( $atts, 'thumbnail', $prefix . 'thumbnail' );

                $output .= '</' . $media_tag . '>';

                $output .= '</figure>';
            }

            $output .= '</div>';

            return $output;
        }

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }
}
