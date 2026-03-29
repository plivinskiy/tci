<?php
namespace WGL_Extensions\Includes;

defined('ABSPATH') || exit;

use Elementor\{
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background
};

if (!class_exists('WGL_Cursor')) {
    /**
     * WGL Elementor Media Settings
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.0
     */
    class WGL_Cursor
    {
        private static $instance;

        public function build($self, $atts, $repeater_id = '', $pref = '')
        {
            return (new WGL_Cursor_Builder())->build($self, $atts, $repeater_id, $pref);
        }

        /**
         * @since 1.0.0
         * @version 1.0.0
         */
        public static function repeater_init($self, $attrs = [])
        {

            // Variables validation
            $section = false;
            $repeater = '{{CURRENT_ITEM}}';
            $repeater_items = '[class*="elementor-repeater-item-"]';
            $prefix = $attrs['prefix'] ?? '';

            $self->add_control(
                $prefix . 'cursor_tooltip',
                [
                    'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ($attrs['condition'] ?? []),
                ]
            );
            $self->add_responsive_control(
                $prefix . 'cursor_margin',
                [
                    'label' => esc_html__('Margin', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'selectors' => [
                        '#wgl-cursor {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_tooltip_type',
                [
                    'label' => esc_html__('Tooltip Type', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'def' => esc_html__('Default Text', 'courto-core'),
                        'custom' => esc_html__('Custom Text', 'courto-core'),
                        'image' => esc_html__('Image', 'courto-core'),
                    ],
                    'default' => 'def',
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_text',
                [
                    'label' => esc_html__('Tooltip Title', 'courto-core'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => ['active' => true],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'custom',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'label_block' => true,
                ]
            );

            WGL_Icons::init(
                $self,
                [
                    'output' => '',
                    'section' => false,
                    'media_types_options' => [
                        '' => [
                            'title' => esc_html__('None', 'courto-core'),
                            'icon' => 'eicon-ban',
                        ],
                        'font' => [
                            'title' => esc_html__('Icon', 'courto-core'),
                            'icon' => 'far fa-smile',
                        ],
                    ],
                    'default' => [
                        'media_type' => '',
                    ],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'custom',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'prefix' => $prefix . 'tooltip_'
                ]
            );

            $self->add_control(
                $prefix . 'cursor_thumbnail',
                [
                    'label' => esc_html__('Thumbnail', 'courto-core'),
                    'type' => Controls_Manager::MEDIA,
                    'dynamic' => ['active' => true],
                    'label_block' => true,
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_width',
                [
                    'label' => esc_html__('Width', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            if (!empty($attrs['output'])) {
                foreach ($attrs['output'] as $key => $value) {
                    $self->add_control(
                        $key,
                        $value
                    );
                }
            }
        }

        /**
         * @since 1.0.0
         * @version 1.0.0
         */
        public static function repeater_style_init($self, $attrs = [])
        {
            // Variables validation
            $section = false;
            $repeater = '{{CURRENT_ITEM}}';
            //$repeater_items = '.wgl-element-[class*="elementor-repeater-item-"]';
            $repeater_items = '.wgl-element-' . $self->get_id();
            $repeater_inc_image = $repeater_items.'.cursor-image';
            $repeater_exc_image = $repeater_items.':not(.cursor-image)';

            $prefix = $attrs['prefix'] ?? '';

            $self->start_controls_section(
                $prefix . 'add_cursor_tooltip_section',
                [
                    'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                    'condition' => $attrs['condition'] ?? [],
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_transition',
                [
                    'label' => esc_html__('Tooltip Transition (sec)', 'courto-core'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => ['active' => true],
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.'' => '--transition: {{VALUE}}s;',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_duration',
                [
                    'label' => esc_html__('Tooltip Duration (ms)', 'courto-core'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => ['active' => true],
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.01,
                ]
            );

            $self->add_control(
                $prefix . 'cursor_prop',
                [
                    'label' => esc_html__('Cursor Property', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes'
                ]
            );

            $self->add_control(
                $prefix . 'heading_image_cursor',
                [
                    'label' => esc_html__('Image Tooltip Settings', 'courto-core'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                $prefix . 'heading_image_cursor_desc',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => esc_html__(
                        'These settings apply to the tooltip type of the image selected in the repeater.',
                        'courto-core'
                    ),
                    'content_classes' => 'elementor-panel-heading-description',
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_rotate',
                [
                    'label' => esc_html__('Rotate Image', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'deg' => ['min' => 0, 'max' => 360],
                        'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                    ],
                    'size_units' => ['deg', 'turn'],
                    'condition' => [
                    ] + ($attrs['condition'] ?? []),
                    'default' => ['unit' => 'deg'],
                    'tablet_default' => ['unit' => 'deg'],
                    'mobile_default' => ['unit' => 'deg'],
                    'selectors' => [
                        '#wgl-cursor ' . $repeater_inc_image => '--wgl-cursor-rotate: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'courto-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%', 'custom' ],
                    'condition' => [
                    ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_inc_image.' img,
                         #wgl-cursor '.$repeater_inc_image. '::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_thumbnail_animation',
                [
                    'label' => esc_html__('Tooltip Animation', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'zoom' => esc_html__('Zoom', 'courto-core'),
                        'fade' => esc_html__('Fade', 'courto-core'),
                        'slide-in-left' => esc_html__('Slide in Left', 'courto-core'),
                        'slide-in-right' => esc_html__('Slide in Right', 'courto-core'),
                        'slide-in-top' => esc_html__('Slide in Top', 'courto-core'),
                        'slide-in-bot' => esc_html__('Slide in Bottom', 'courto-core'),
                    ],
                    'default' => 'zoom',
                    'condition' => [
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_thumbnail_bg',
                [
                    'label' => esc_html__('Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                            $prefix . 'tooltip_thumbnail_animation' => ['zoom', 'fade'],
                            $prefix . 'tooltip_thumbnail_rotate[size]!' => [0, ''],
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_inc_image.'::before' => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; background-color: {{VALUE}}; z-index: -1; transform: rotate(calc(-1 * var(--wgl-cursor-rotate, 0)));',
                        '#wgl-cursor '.$repeater_inc_image => 'overflow: unset;',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'heading_text_cursor',
                [
                    'label' => esc_html__('Default Tooltip Settings', 'courto-core'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                $prefix . 'heading_text_cursor_desc',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => esc_html__(
                        'These settings apply to the tooltip of the default or custom text selected in the repeater.',
                        'courto-core'
                    ),
                    'content_classes' => 'elementor-panel-heading-description',
                ]
            );

            $self->start_controls_tabs(
                $prefix . 'tabs_cursor'
            );

            $self->start_controls_tab(
                $prefix . 'tabs_cursor_title',
                [
                    'label' => esc_html__('Content', 'courto-core'),
                ]
            );

            $self->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => $prefix . 'tooltip_title',
                    'selector' => '#wgl-cursor '.$repeater_items.' h6',
                ]
            );
            
            $self->add_responsive_control(
                $prefix . 'tooltip_icon_size',
                [
                    'label' => esc_html__('Icon Size', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 200 ],
                    ],
                    'size_units' => [ 'px', 'em', 'custom'],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_responsive_control(
                $prefix . 'tooltip_title_padding',
                [
                    'label' => esc_html__('Padding', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_radius',
                [
                    'label' => esc_html__('Border Radius', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $prefix . 'tooltip_title_border',
                    'fields_options' => [
                        'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                        'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                    ],
                    'selector' => '#wgl-cursor '.$repeater_items.' h6',
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_color',
                [
                    'label' => esc_html__('Title Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' h6' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_icon_color',
                [
                    'label' => esc_html__('Icon Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' .wgl-icon' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_bg',
                [
                    'label' => esc_html__('Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.' h6' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                $prefix . 'tabs_cursor_background',
                [
                    'label' => esc_html__('Tooltip Background', 'courto-core'),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_tooltip_bg',
                [
                    'label' => esc_html__('Add Tooltip Background', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_bg_width',
                [
                    'label' => esc_html__('Width', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 1500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                             ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items => '--tooltip-bg-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_bg_height',
                [
                    'label' => esc_html__('Height', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 1500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items => '--tooltip-bg-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_bg_radius',
                [
                    'label' => esc_html__('Border Radius', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items => '--tooltip-bg-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $prefix . 'tooltip_bg_color',
                    'label' => esc_html__('Background', 'courto-core'),
                    'types' => ['classic', 'gradient'],
                    'fields_options' => [
                        'background' => [ 'label' => esc_html__('Background', 'courto-core'), ],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor  '.$repeater_items.'::before',
                ]
            );
            $self->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $prefix . 'tooltip_additional_bg_color',
                    'label' => esc_html__('Additional Background', 'courto-core'),
                    'types' => ['classic', 'gradient'],
                    'fields_options' => [
                        'background' => [ 'label' => esc_html__('Additional Background', 'courto-core'), ],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor  '.$repeater_items.'::after',
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_bg_blur',
                [
                    'label' => esc_html__('Blur', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => ['max' => 100, 'step' => 0.1],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater_items.'::after' => 'filter: blur({{SIZE}}px);',
                    ],
                ]
            );
            $self->end_controls_tab();
            $self->end_controls_tabs();

            if (!empty($attrs['output'])) {
                foreach ($attrs['output'] as $key => $value) {
                    $self->add_control(
                        $key,
                        $value
                    );
                }
            }

            $self->end_controls_section();
        }

        /**
         * @since 1.0.0
         * @version 1.0.0
         */
        public static function init($self, $attrs = [])
        {

            // Variables validation
            $section = $attrs['section'] ?? true;
            $repeater = isset($attrs['repeater']) && $attrs['repeater'] ? '{{CURRENT_ITEM}}' : '.wgl-element-{{ID}}.cursor-global';
            $prefix = $attrs['prefix'] ?? '';

            if ($section) {
                $self->start_controls_section(
                    $prefix . 'add_cursor_tooltip_section',
                    [
                        'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                        'condition' => $attrs['condition'] ?? [],
                    ]
                );
            }

            $self->add_control(
                $prefix . 'cursor_tooltip',
                [
                    'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ($attrs['condition'] ?? []),
                ]
            );
            $self->add_responsive_control(
                $prefix . 'cursor_margin',
                [
                    'label' => esc_html__('Margin', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'selectors' => [
                        '#wgl-cursor .wgl-element-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '#wgl-cursor {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_transition',
                [
                    'label' => esc_html__('Tooltip Transition (sec)', 'courto-core'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => ['active' => true],
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                    'selectors' => [
                        '#wgl-cursor .wgl-element-{{ID}}' => '--transition: {{VALUE}}s;',
                        '#wgl-cursor {{CURRENT_ITEM}}' => '--transition: {{VALUE}}s;',
                    ],
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_duration',
                [
                    'label' => esc_html__('Tooltip Duration (ms)', 'courto-core'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => ['active' => true],
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.01,
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );
            $self->add_control(
                $prefix . 'cursor_prop',
                [
                    'label' => esc_html__('Cursor Property', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                    'default' => 'yes'
                ]
            );

            $self->add_control(
                $prefix . 'cursor_tooltip_type',
                [
                    'label' => esc_html__('Tooltip Type', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'simple' => esc_html__('Simple', 'courto-core'),
                        'def' => esc_html__('Default Text', 'courto-core'),
                        'custom' => esc_html__('Custom Text', 'courto-core'),
                        'image' => esc_html__('Image', 'courto-core'),
                    ],
                    'default' => 'simple',
                    'condition' => [$prefix . 'cursor_tooltip' => 'yes'] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_text',
                [
                    'label' => esc_html__('Tooltip Title', 'courto-core'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => ['active' => true],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'custom',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'label_block' => true,
                ]
            );

            WGL_Icons::init(
                $self,
                [
                    'output' => '',
                    'section' => false,
                    'media_types_options' => [
                        '' => [
                            'title' => esc_html__('None', 'courto-core'),
                            'icon' => 'eicon-ban',
                        ],
                        'font' => [
                            'title' => esc_html__('Icon', 'courto-core'),
                            'icon' => 'far fa-smile',
                        ],
                    ],
                    'default' => [
                        'media_type' => '',
                    ],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'custom',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'prefix' => $prefix . 'tooltip_'
                ]
            );

            $self->add_control(
                $prefix . 'cursor_thumbnail',
                [
                    'label' => esc_html__('Thumbnail', 'courto-core'),
                    'type' => Controls_Manager::MEDIA,
                    'dynamic' => ['active' => true],
                    'label_block' => true,
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_width',
                [
                    'label' => esc_html__('Width', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_rotate',
                [
                    'label' => esc_html__('Rotate Image', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'deg' => ['min' => 0, 'max' => 360],
                        'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                    ],
                    'size_units' => ['deg', 'turn'],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'default' => ['unit' => 'deg'],
                    'tablet_default' => ['unit' => 'deg'],
                    'mobile_default' => ['unit' => 'deg'],
                    'selectors' => [
                        '#wgl-cursor '.$repeater => '--wgl-cursor-rotate: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_thumbnail_bg',
                [
                    'label' => esc_html__('Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => 'image',
                            $prefix . 'cursor_tooltip' => 'yes',
                            $prefix . 'tooltip_thumbnail_animation' => ['zoom', 'fade'],
                            $prefix . 'tooltip_thumbnail_rotate[size]!' => [0, ''],
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.'::before' => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; background-color: {{VALUE}}; z-index: -1; transform: rotate(calc(-1 * var(--wgl-cursor-rotate, 0)));',
                        '#wgl-cursor '.$repeater => 'overflow: unset;',
                    ],
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_thumbnail_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'courto-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%', 'custom' ],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' img,
                         #wgl-cursor '.$repeater.'::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_thumbnail_animation',
                [
                    'label' => esc_html__('Tooltip Animation', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'zoom' => esc_html__('Zoom', 'courto-core'),
                        'fade' => esc_html__('Fade', 'courto-core'),
                        'slide-in-left' => esc_html__('Slide in Left', 'courto-core'),
                        'slide-in-right' => esc_html__('Slide in Right', 'courto-core'),
                        'slide-in-top' => esc_html__('Slide in Top', 'courto-core'),
                        'slide-in-bot' => esc_html__('Slide in Bottom', 'courto-core'),
                    ],
                    'default' => 'zoom',
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'image',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_color_bg',
                [
                    'label' => esc_html__('Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'simple',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_size',
                [
                    'label' => esc_html__('Size', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 500 ],
                    ],
                    'size_units' => [ 'px'],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'simple',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_follower_color_bg',
                [
                    'label' => esc_html__('Follower Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'simple',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_follower_size',
                [
                    'label' => esc_html__('Follower Size', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 500 ],
                    ],
                    'size_units' => [ 'px'],
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'simple',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_follower_duration',
                [
                    'label' => esc_html__('Follower Duration', 'courto-core'),
                    'type' => Controls_Manager::NUMBER,
                    'dynamic' => ['active' => true],
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.01,
                    'condition' => [
                        $prefix . 'cursor_tooltip_type' => 'simple',
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_link_animation',
                [
                    'label' => esc_html__('Cursor Link Animation', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none'       => esc_html__('None', 'courto-core'),
                        '1'          => esc_html__('Style 1', 'courto-core'),
                        '2'          => esc_html__('Style 2', 'courto-core'),
                        '3'          => esc_html__('Style 3', 'courto-core'),
                    ],
                    'condition' => [
                        $prefix . 'cursor_tooltip' => 'yes'
                    ] + ($attrs['condition'] ?? []),
                ]
            );


            $self->start_controls_tabs(
                $prefix . 'tabs_cursor'
            );
            $self->start_controls_tab(
                $prefix . 'tabs_cursor_title',
                [
                    'label' => esc_html__('Content', 'courto-core'),
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                ]
            );
            $self->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => $prefix . 'tooltip_title',
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor '.$repeater.' h6',
                ]
            );
            $self->add_responsive_control(
                $prefix . 'tooltip_icon_size',
                [
                    'label' => esc_html__('Icon Size', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 0, 'max' => 200 ],
                    ],
                    'size_units' => [ 'px', 'em', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes',
                            $prefix . 'tooltip_icon_type' => 'font'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_responsive_control(
                $prefix . 'tooltip_title_padding',
                [
                    'label' => esc_html__('Padding', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_radius',
                [
                    'label' => esc_html__('Border Radius', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $prefix . 'tooltip_title_border',
                    'fields_options' => [
                        'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                        'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor '.$repeater.' h6',
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_color',
                [
                    'label' => esc_html__('Title Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' h6' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_icon_color',
                [
                    'label' => esc_html__('Icon Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes',
                            $prefix . 'tooltip_icon_type' => 'font'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' .wgl-icon' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_title_bg',
                [
                    'label' => esc_html__('Background Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.' h6' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $self->end_controls_tab();

            $self->start_controls_tab(
                $prefix . 'tabs_cursor_background',
                [
                    'label' => esc_html__('Tooltip Background', 'courto-core'),
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_control(
                $prefix . 'cursor_tooltip_bg',
                [
                    'label' => esc_html__('Add Tooltip Background', 'courto-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_bg_width',
                [
                    'label' => esc_html__('Width', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 1500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater => '--tooltip-bg-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                $prefix . 'tooltip_bg_height',
                [
                    'label' => esc_html__('Height', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => [ 'min' => 10, 'max' => 1500 ],
                    ],
                    'size_units' => [ 'px', 'vw', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater => '--tooltip-bg-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                $prefix . 'tooltip_bg_radius',
                [
                    'label' => esc_html__('Border Radius', 'courto-core'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'custom'],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater => '--tooltip-bg-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $self->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $prefix . 'tooltip_bg_color',
                    'label' => esc_html__('Background', 'courto-core'),
                    'types' => ['classic', 'gradient'],
                    'fields_options' => [
                        'background' => [ 'label' => esc_html__('Background', 'courto-core'), ],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor  '.$repeater.'::before',
                ]
            );
            $self->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $prefix . 'tooltip_additional_bg_color',
                    'label' => esc_html__('Additional Background', 'courto-core'),
                    'types' => ['classic', 'gradient'],
                    'fields_options' => [
                        'background' => [ 'label' => esc_html__('Additional Background', 'courto-core'), ],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selector' => '#wgl-cursor  '.$repeater.'::after',
                ]
            );
            $self->add_control(
                $prefix . 'tooltip_bg_blur',
                [
                    'label' => esc_html__('Blur', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'range' => [
                        'px' => ['max' => 100, 'step' => 0.1],
                    ],
                    'condition' => [
                            $prefix . 'cursor_tooltip_bg' => 'yes',
                            $prefix . 'cursor_tooltip_type' => ['def', 'custom'],
                            $prefix . 'cursor_tooltip' => 'yes'
                        ] + ($attrs['condition'] ?? []),
                    'selectors' => [
                        '#wgl-cursor '.$repeater.'::after' => 'filter: blur({{SIZE}}px);',
                    ],
                ]
            );
            $self->end_controls_tab();
            $self->end_controls_tabs();

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
}

if (!class_exists('WGL_Cursor_Builder')) {
    /**
     * WGL Cursor Build
     *
     *
     * @package courto-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.0
     */
    class WGL_Cursor_Builder
    {
        private static $instance;

        /**
         * @since 1.0.0
         * @version 1.0.0
         */
        public function build($self, $atts, $repeater_id, $pref)
        {
            $prefix = !empty($pref) ? $pref : '';

            $cursor_tooltip = isset($atts[$prefix . 'cursor_tooltip']) ? $atts[$prefix . 'cursor_tooltip'] : false;
            $id = $self ? $self->get_id() : (isset($atts['item_id']) && !empty($atts['item_id']) ? $atts['item_id'] : 0);
            $wrapper_id = $id;

            if (!empty($repeater_id)) {
                $id = $repeater_id;
            }

            if (!$cursor_tooltip) {
                // Bailout.
                return '';
            }

            $cursor_class = 'wgl-element-'.$wrapper_id.' cursor-global elementor-repeater-item-'.$id;
            if('image' !== $atts[$prefix . 'cursor_tooltip_type']){
                $cursor_class .= $atts[$prefix . 'cursor_tooltip_bg'] ? ' tooltip_bg' : '';
            }            
            $cursor_class .= empty($atts[$prefix . 'cursor_prop']) ? ' cursor_center' : '';

            // Icon/Image
            ob_start();
            if (!empty($atts[$prefix . 'tooltip_icon_type'])) {
                $icons = new WGL_Icons;
                echo $icons->build($self, $atts, $prefix . 'tooltip_');
            }
            $tooltip_icon = ob_get_clean();

            switch ($atts[$prefix . 'cursor_tooltip_type']) {
                case 'image':
                    $cursor_image = isset($atts[$prefix . 'cursor_thumbnail']['url']) && !empty($atts[$prefix . 'cursor_thumbnail']['url']) ? '<img src=\'' . esc_url($atts[$prefix . 'cursor_thumbnail']['url']) . '\' alt=\'' . ($atts[$prefix . 'cursor_thumbnail']['alt'] ?? '1') . '\'>' : '';
                    $cursor_class .= ' animation-' . $atts[$prefix . 'tooltip_thumbnail_animation'];
                    $cursor_class .= ' cursor-image';
                    break;

                case 'custom':
                    if ($atts[$prefix . 'tooltip_text']) {
                        $cursor_text = '<h6>'
                            . $atts[$prefix . 'tooltip_text']
                            . ($atts[$prefix . 'tooltip_icon_type'] ? $tooltip_icon : '')
                        . '</h6>';
                    } else if ($atts[$prefix . 'tooltip_icon_type']) {
                        $cursor_text = $tooltip_icon;
                    }
                    break;

                case 'def':
                    $cursor_text = '<h6>'.esc_attr__('More', 'courto-core').'</h6>';
                    break;

                case 'simple':
                    $cursor_color = $atts[$prefix . 'cursor_color_bg'] ?? '';
                    $cursor_size = $atts[$prefix . 'cursor_size']['size'] ?? '';
                    $cursor_follower_color = $atts[$prefix . 'cursor_follower_color_bg'] ?? '';
                    $cursor_follower_size = $atts[$prefix . 'cursor_follower_size']['size'] ?? '';
                    $cursor_follower_duration = $atts[$prefix . 'cursor_follower_duration'] ?? '';

                    break;

                default:
                    $cursor_text = $cursor_image = $cursor_color = '';
                    break;
            }
            $cursor_link_animation = !empty($atts[$prefix . 'cursor_link_animation']) ? $atts[$prefix . 'cursor_link_animation'] : 'none';
            $tooltip_duration = $atts[$prefix . 'tooltip_duration'] ?? '';

            $cursor_class_data = !empty($cursor_class) ? ' data-cursor-class="' . esc_attr($cursor_class) . '"' : '';
            $cursor_text_data = !empty($cursor_text) ? ' data-cursor-text="' . esc_attr($cursor_text) . '"' : '';
            $cursor_image_data = !empty($cursor_image) ? ' data-cursor-image="' . esc_attr($cursor_image) . '"' : '';
            $cursor_color_bg = !empty($cursor_color) ? ' data-cursor-color-bg="' . esc_attr($cursor_color) . '"' : '';
            $cursor_size_render = !empty($cursor_size) ? ' data-cursor-size="' . esc_attr($cursor_size) . 'px"' : '';
            $cursor_follower_color_bg = !empty($cursor_follower_color) ? ' data-cursor-follower-color-bg="' . esc_attr($cursor_follower_color) . '"' : '';
            $cursor_follower_size_render = !empty($cursor_follower_size) ? ' data-cursor-follower-size="' . esc_attr($cursor_follower_size) . 'px"' : '';
            $cursor_follower_duration_render = !empty($cursor_follower_duration) ? ' data-cursor-follower-duration="' . esc_attr($cursor_follower_duration) . '"' : '';
            $cursor_link_animation_render = ' data-cursor-link-animation="link-animation-style-' . esc_attr($cursor_link_animation) . '"';
            $cursor_duration_render = !empty($tooltip_duration) ? ' data-cursor-duration="' . esc_attr($tooltip_duration) . '"' : '';
            $cursor_color_prop = empty($atts[$prefix . 'cursor_prop']) ? ' data-cursor-prop="none"' : '';

            $output = $cursor_color_bg . $cursor_size_render . $cursor_follower_color_bg . $cursor_follower_size_render . $cursor_follower_duration_render . $cursor_link_animation_render . $cursor_duration_render . $cursor_class_data . $cursor_image_data . $cursor_text_data . $cursor_color_prop;

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
