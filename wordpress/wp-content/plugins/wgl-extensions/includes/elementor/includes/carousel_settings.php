<?php
namespace WGL_Extensions\Includes;

defined( 'ABSPATH' ) || exit;

use Elementor\{
    Frontend,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Box_Shadow
};

if ( ! class_exists( 'WGL_Carousel_Settings' ) ) {
    /**
     * WGL Elementor Carousel Settings
     *
     *
     * @package wgl-extensions\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     * @version 1.0.6
     */
    class WGL_Carousel_Settings
    {
        private static $instance;

        /**
         * @since 1.0.0
         * @version 1.0.5
         */
        public static function add_controls($self, $extra_fields = [])
        {
            $self->start_controls_section(
                'content_carousel',
                ['label' => esc_html__('Carousel Options', 'wgl-extensions')]
            );

            $self->add_control(
                'use_carousel',
                [
                    'label' => esc_html__('Use Carousel', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                ]
            );

            self::add_general_controls($self, [
                'slider_container_padding' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                'slides_transition' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                '3d_animation_options' => !empty($extra_fields['3d_animation_options']) ? $extra_fields['3d_animation_options'] : '',
                'animation_style' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                    'default' => ($extra_fields['animation_style']['default'] ?? null),
                ],
                'animation_vertical_height' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                'animation_triggered_by_mouse' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                'autoplay' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                'slider_infinite' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                    'default' => ($extra_fields['slider_infinite']['default'] ?? null),
                ],
                'slide_per_single' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                    'default' => ($extra_fields['slide_per_single']['default'] ?? null),
                ],
                'fade_animation' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
                'center_mode' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                ],
            ]);

            $self->add_control(
                'pagination_divider',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'use_pagination!' => '',
                        'use_carousel!' => '',
                    ],
                ]
            );

            self::add_pagination_controls($self, [
                'use_pagination' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                        'animation_style' => 'default',
                    ],
                    'default' => ($extra_fields['use_pagination']['default'] ?? ''),
                ],
                'pagination_type' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                        'animation_style' => 'default',
                    ],
                    'default' => ($extra_fields['pagination_type']['default'] ?? 'circle'),
                ],
                'pagination_alignment' => [
                    'condition' => [
                        'animation_style' => 'default',
                        'use_carousel!' => '',
                    ],
                ],
                'pagination_margin' => [
                    'condition' => [
                        'animation_style' => 'default',
                        'use_carousel!' => '',
                    ],
                    'range' => [
                        'px' => ($extra_fields['pagination_margin']['range']['px'] ?? null),
                    ],
                    'default' => [
                        'size' => ($extra_fields['pagination_margin']['default']['size'] ?? null),
                    ],
                ],
                'pagination_custom_colors' => [
                    'condition' => [
                        'animation_style' => 'default',
                        'use_carousel!' => '',
                    ],
                ],
                'pagination_style' => [
                    'condition' => [
                        'animation_style' => 'default',
                        'use_carousel!' => '',
                    ],
                ]
            ]);

            $self->add_control(
                'pagination_navigation_divider',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [[
                            'terms' => [
                                [
                                    'name' => 'use_pagination',
                                    'operator' => '!=',
                                    'value' => '',
                                ], [
                                    'name' => 'use_carousel',
                                    'operator' => '!=',
                                    'value' => '',
                                ]
                            ]
                        ], [
                            'terms' => [
                                [
                                    'name' => 'use_navigation',
                                    'operator' => '!=',
                                    'value' => '',
                                ], [
                                    'name' => 'use_carousel',
                                    'operator' => '!=',
                                    'value' => '',
                                ]
                            ]
                        ],],
                    ],
                ]
            );

            self::add_navigation_controls($self, [
                'use_navigation' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                    ],
                    'default' => ($extra_fields['use_navigation']['default'] ?? null),
                ],
            ]);

            $self->add_control(
                'navigation_responsive_divider',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [[
                            'terms' => [
                                [
                                    'name' => 'use_navigation',
                                    'operator' => '!=',
                                    'value' => '',
                                ], [
                                    'name' => 'use_carousel',
                                    'operator' => '!=',
                                    'value' => '',
                                ]
                            ]
                        ], [
                            'terms' => [
                                [
                                    'name' => 'customize_responsive',
                                    'operator' => '!=',
                                    'value' => '',
                                ], [
                                    'name' => 'use_carousel',
                                    'operator' => '!=',
                                    'value' => '',
                                ]
                            ]
                        ],],
                    ],
                ]
            );

            self::add_responsive_controls($self, [
                'customize_responsive' => [
                    'condition' => [
                        'use_carousel' => 'yes',
                        'animation_style' => 'default',
                    ]
                ],
            ]);

            $self->end_controls_section();
        }

        /**
         * @since 1.0.0
         * @version 1.0.5
         */
        public static function add_general_controls( $self, $extra_fields = [] )
        {
            $self->add_responsive_control(
                'slider_container_padding',
                [
                    'label' => esc_html__('Container Padding', 'wgl-extensions'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'condition' => ($extra_fields['slider_container_padding']['condition'] ?? []),
                    'render_type' => 'template',
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wgl-carousel_wrapper,
                         {{WRAPPER}} .wgl-carousel.animation-style-3d' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                'slides_transition',
                [
                    'label' => esc_html__( 'Animation Duration', 'wgl-extensions' ),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ( $extra_fields[ 'slides_transition' ][ 'condition' ] ?? [] ),
                    'placeholder' => '300',
                    'min' => 1,
                    'default' => ( $extra_fields[ 'slides_transition' ][ 'default' ] ?? '' ),
                ]
            );

            $self->add_control(
                'animation_style',
                [
                    'label' => esc_html__('Animation Style', 'wgl-extensions'),
                    'type' => !empty($extra_fields['3d_animation_options']) ? Controls_Manager::SELECT : Controls_Manager::HIDDEN,
                    'condition' => ($extra_fields['animation_style']['condition'] ?? []),
                    'options' => [
                        'default' => esc_html__('Horizontal Default', 'wgl-extensions'),
                        'horizontal' => esc_html__('Horizontal 3D', 'wgl-extensions'),
                        'vertical' => esc_html__('Vertical 3D', 'wgl-extensions'),
                    ],
                    'default' => ($extra_fields['animation_style']['default'] ?? 'default'),
                ]
            );

            $self->add_responsive_control(
                'animation_vertical_height',
                [
                    'label' => esc_html__('Carousel Height', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['animation_style' => 'vertical'] + ($extra_fields['animation_vertical_height']['condition'] ?? []),
                    'render_type' => 'template',
                    'range' => [
                        'px' => ['min' => 300, 'max' => 1000],
                    ],
                    'default' => ['size' => 600, 'unit' => 'px'],
                    'selectors' => [
                        '{{WRAPPER}} .wgl-carousel.animation-style-3d' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                'animation_triggered_by_mouse',
                [
                    'label' => esc_html__('Triggered by Mouse Wheel', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['animation_style!' => 'default'] + ($extra_fields['animation_triggered_by_mouse']['condition'] ?? []),
                    'default' => 'yes',
                ]
            );

            $self->add_control(
                'autoplay_divider_before',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'autoplay' => 'yes',
                        'animation_style' => 'default',
                    ] + ($extra_fields['autoplay']['condition'] ?? [])
                      + ($extra_fields['autoplay_divider_before']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'autoplay',
                [
                    'label' => esc_html__('Autoplay', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['animation_style' => 'default'] + ($extra_fields['autoplay']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'autoplay_speed',
                [
                    'label' => esc_html__( 'Autoplay Interval', 'wgl-extensions' ),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => [
                        'autoplay' => 'yes',
                        'animation_style' => 'default',
                    ] + ( $extra_fields['autoplay']['condition'] ?? [] )
                      + ( $extra_fields['autoplay_speed']['condition'] ?? [] ),
                    'placeholder' => '3000',
                    'min' => 0,
                    'default' => 3000,
                ]
            );

            $self->add_control(
                'autoplay_pause',
                [
                    'label' => esc_html__('Pause On Hover', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'autoplay' => 'yes',
                        'animation_style' => 'default',
                    ] + ($extra_fields['autoplay']['condition'] ?? [])
                      + ($extra_fields['autoplay_pause']['condition'] ?? []),
                    'default' => ($extra_fields['autoplay_pause']['default'] ?? 'yes'),
                ]
            );

            $self->add_control(
                'autoplay_reverse',
                [
                    'label' => esc_html__( 'Reverse Direction', 'wgl-extensions' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'autoplay' => 'yes',
                        'animation_style' => 'default',
                    ] + ( $extra_fields[ 'autoplay' ][ 'condition' ] ?? [] )
                      + ( $extra_fields[ 'autoplay_reverse' ][ 'condition' ] ?? [] ),
                    'default' => ( $extra_fields[ 'autoplay_reverse' ][ 'default' ] ?? '' ),
                ]
            );

            $self->add_control(
                'autoplay_divider_after',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'autoplay' => 'yes',
                        'animation_style' => 'default',
                    ] + ($extra_fields['autoplay']['condition'] ?? [])
                      + ($extra_fields['autoplay_divider_after']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'slider_infinite',
                [
                    'label' => esc_html__('Infinite Loop', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['animation_style' => 'default'] + ($extra_fields['slider_infinite']['condition'] ?? []),
                    'default' => ($extra_fields['slider_infinite']['default'] ?? ''),
                ]
            );

            $self->add_control(
                'slide_per_single',
                [
                    'label' => esc_html__('Slide per single item', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['animation_style' => 'default'] + ($extra_fields['slide_per_single']['condition'] ?? []),
                    'default' => ($extra_fields['slide_per_single']['default'] ?? ''),
                ]
            );

            $self->add_control(
                'fade_animation',
                [
                    'label' => esc_html__('Fade Animation', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'posts_per_row' => '1',
                        'animation_style' => 'default',
                    ] + ($extra_fields['fade_animation']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'center_mode',
                [
                    'label' => esc_html__('Center Mode', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['animation_style' => 'default'] + ($extra_fields['center_mode']['condition'] ?? []),
                ]
            );
        }

        public static function add_pagination_controls($self, $extra_fields = [])
        {
            $self->add_control(
                'use_pagination',
                [
                    'label' => esc_html__('Add Pagination Controls', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ($extra_fields['use_pagination']['condition'] ?? []),
                    'default' => ($extra_fields['use_pagination']['default'] ?? ''),
                ]
            );

            $self->add_control(
                'pagination_type',
                [
                    'label' => esc_html__('Type', 'wgl-extensions'),
                    'type' => 'wgl-radio-image',
                    'condition' => ['use_pagination' => 'yes'] + ($extra_fields['pagination_type']['condition'] ?? []),
                    'options' => self::get_pagination_type_options(),
                    'default' => ($extra_fields['pagination_type']['default'] ?? 'circle'),
                ]
            );

            $self->add_responsive_control(
                'pagination_alignment',
                [
                    'label' => esc_html__('Horizontal Alignment', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['use_pagination' => 'yes'] + ($extra_fields['pagination_alignment']['condition'] ?? []),
                    'size_units' => ['%'],
                    'range' => [
                        '%' => ['min' => 0, 'max' => 100],
                    ],
                    'default' => ['size' => 50, 'unit' => '%'],
                    'selectors' => [
                        '{{WRAPPER}} .swiper-pagination' => 'margin-left: {{SIZE}}%; transform: translateX(-{{SIZE}}%);',
                    ],
                ]
            );

            $self->add_control(
                'pagination_margin',
                [
                    'label' => esc_html__('Margin Top', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['use_pagination' => 'yes'] + ($extra_fields['pagination_margin']['condition'] ?? []),
                    'range' => [
                        'px' => ($extra_fields['pagination_margin']['range']['px'] ?? ['min' => -500, 'max' => 1000]),
                    ],
                    'default' => [
                        'size' => ($extra_fields['pagination_margin']['default']['size'] ?? ''),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wgl-carousel .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                'pagination_custom_colors',
                [
                    'label' => esc_html__('Customize Colors', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ['use_pagination' => 'yes'] + ($extra_fields['pagination_custom_colors']['condition'] ?? []),
                ]
            );

            $self->start_controls_tabs(
                'pagination_style',
                [
                    'condition' => [
                        'pagination_custom_colors!' => '',
                        'use_pagination!' => '',
                    ] + ($extra_fields['pagination_style']['condition'] ?? []),
                ]
            );

            $self->start_controls_tab(
                'pagination_idle',
                ['label' => esc_html__('Idle', 'wgl-extensions')]
            );

            $self->add_control(
                'pagination_color_idle',
                [
                    'label' => esc_html__('Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'default' => ($extra_fields['pagination_color_idle']['default'] ?? ''),
                    'selectors' => self::get_pagination_color_selectors('idle'),
                ]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'pagination_hover',
                ['label' => esc_html__('Hover', 'wgl-extensions')]
            );

            $self->add_control(
                'pagination_color_hover',
                [
                    'label' => esc_html__('Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'default' => ($extra_fields['pagination_color_hover']['default'] ?? ''),
                    'selectors' => self::get_pagination_color_selectors('hover'),
                ]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'pagination_active',
                ['label' => esc_html__('Active', 'wgl-extensions')]
            );

            $self->add_control(
                'pagination_color_active',
                [
                    'label' => esc_html__('Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'default' => ($extra_fields['pagination_color_active']['default'] ?? ''),
                    'selectors' => self::get_pagination_color_selectors('active'),
                ]
            );

            $self->end_controls_tab();
            $self->end_controls_tabs();
        }

        public static function get_pagination_type_options()
        {
            return  [
                'circle' => [
                    'title' => esc_html__('Circle', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle.png',
                ],
                'circle_border' => [
                    'title' => esc_html__('Empty Circle', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle_border.png',
                ],
                'square' => [
                    'title' => esc_html__('Square', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_square.png',
                ],
                'square_border' => [
                    'title' => esc_html__('Empty Square', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_square_border.png',
                ],
                'line' => [
                    'title' => esc_html__('Line', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_line.png',
                ],
                'line_circle' => [
                    'title' => esc_html__('Line - Circle', 'wgl-extensions'),
                    'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/pag_line_circle.png',
                ],
            ];
        }

        public static function get_pagination_color_selectors(String $state)
        {
            if ('idle' === $state) {
                return [
                    '{{WRAPPER}} .pagination_circle .swiper-pagination li button,
                     {{WRAPPER}} .pagination_line .swiper-pagination li button:before,
                     {{WRAPPER}} .pagination_line_circle .swiper-pagination li button,
                     {{WRAPPER}} .pagination_square .swiper-pagination li button,
                     {{WRAPPER}} .pagination_circle_border .swiper-pagination li button:before' => 'background-color: {{VALUE}};',

                    '{{WRAPPER}} .swiper-pagination li button' => 'opacity: 1;',
                ];
            }

            if ('hover' === $state) {
                return [
                    '{{WRAPPER}} .pagination_circle .swiper-pagination li:hover button,
                     {{WRAPPER}} .pagination_line .swiper-pagination li:hover button:before,
                     {{WRAPPER}} .pagination_line_circle .swiper-pagination li:hover button,
                     {{WRAPPER}} .pagination_square .swiper-pagination li:hover button,
                     {{WRAPPER}} .pagination_square_border .swiper-pagination li:hover button:before,
                     {{WRAPPER}} .pagination_circle_border .swiper-pagination li:hover button:before' => 'background-color: {{VALUE}};',
                ];
            }

            if ('active' === $state) {
                return [
                    '{{WRAPPER}} .pagination_circle .swiper-pagination li.swiper-pagination-bullet-active button,
                     {{WRAPPER}} .pagination_line .swiper-pagination li.swiper-pagination-bullet-active button:before,
                     {{WRAPPER}} .pagination_line_circle .swiper-pagination li.swiper-pagination-bullet-active button,
                     {{WRAPPER}} .pagination_square .swiper-pagination li.swiper-pagination-bullet-active button,
                     {{WRAPPER}} .pagination_square_border .swiper-pagination li.swiper-pagination-bullet-active button:before,
                     {{WRAPPER}} .pagination_circle_border .swiper-pagination li.swiper-pagination-bullet-active button:before' => 'background-color: {{VALUE}};',

                    '{{WRAPPER}} .pagination_circle_border .swiper-pagination li.swiper-pagination-bullet-active button,
                     {{WRAPPER}} .pagination_square_border .swiper-pagination li.swiper-pagination-bullet-active button' => 'border-color: {{VALUE}};',
                ];
            }
        }

        /**
         * @since 1.0.0
         * @version 1.0.6
         */
        public static function add_navigation_controls( $self, $extra_fields = [] )
        {
            $self->add_control(
                'use_navigation',
                [
                    'label' => esc_html__( 'Add Navigation Controls', 'wgl-extensions' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] ),
                    'default' => ( $extra_fields[ 'use_navigation' ][ 'default' ] ?? '' ),
                ]
            );

            $self->add_responsive_control(
                'navigation_diameter',
                [
                    'label' => esc_html__( 'Buttons Diameter', 'wgl-extensions' ),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_navigation' => 'yes',
                    ] + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                      + ( $extra_fields[ 'navigation_diameter' ][ 'condition' ] ?? [] ),
                    'size_units' => [ 'px', 'rem' ],
                    'range' => [
                        'px' => [ 'min' => 30, 'max' => 100 ],
                        'rem' => [ 'min' => 2, 'max' => 8 ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button,
                         {{WRAPPER}} .motion-arrow' => '--wgl-swiper-button-diameter: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_control(
                'navigation_position',
                [
                    'label' => esc_html__( 'Positioning', 'wgl-extensions' ),
                    'type' => Controls_Manager::SELECT,
                    'condition' => [ 'use_navigation' => 'yes' ]
                        + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                        + ( $extra_fields[ 'navigation_position' ][ 'condition' ] ?? [] ),
                    'options' => (
                        $extra_fields[ 'navigation_position' ][ 'options' ] ?? [
                            '' => esc_html__( 'Opposite sides', 'wgl-extensions' ),
                            'nearby' => esc_html__( 'Nearby', 'wgl-extensions' ),
                        ]
                    ),
                    'default' => '',
                ]
            );

            $self->add_responsive_control(
                'navigation_distance',
                [
                    'label' => esc_html__( 'Buttons Distance', 'wgl-extensions' ),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => [
                        'use_navigation' => 'yes',
                        'navigation_position' => 'nearby'
                    ] + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                      + ( $extra_fields[ 'navigation_distance' ][ 'condition' ] ?? [] ),
                    'range' => [
                        'px' => [ 'max' => 100 ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .navigation-posiiton-nearby' => '--wgl-swiper-buttons-distance: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $self->add_responsive_control(
                'navigation_vertical_offset',
                [
                    'label' => esc_html__( 'Vertical Offset', 'wgl-extensions' ),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => [ 'use_navigation' => 'yes' ]
                        + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                        + ( $extra_fields[ 'navigation_vertical_offset' ][ 'condition' ] ?? [] ),
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [ 'max' => 500 ],
                        '%' => [ 'min' => -10, 'max' => 110 ],
                    ],
                    'default' => [ 'unit' => '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button,
                         {{WRAPPER}} .motion-arrow' => 'top: calc({{SIZE}}{{UNIT}} - var(--wgl-swiper-button-diameter) / 2);',

                        '{{WRAPPER}} .navigation-posiiton-nearby .animation-direction-vertical .motion-prev' => 'top: calc({{SIZE}}{{UNIT}} - var(--wgl-swiper-button-diameter) / 2 - var(--wgl-swiper-buttons-distance) / 2);',
                        '{{WRAPPER}} .navigation-posiiton-nearby .animation-direction-vertical .motion-next' => 'top: calc({{SIZE}}{{UNIT}} - var(--wgl-swiper-button-diameter) / 2 + var(--wgl-swiper-buttons-distance) / 2);',
                    ],
                ]
            );

            $self->add_responsive_control(
                'navigation_horizontal_offset',
                [
                    'label' => esc_html__( 'Horizontal Offset', 'wgl-extensions' ),
                    'type' => Controls_Manager::SLIDER,
			    'dynamic' => [  'active' => true],
                    'condition' => [ 'use_navigation' => 'yes' ]
                        + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                        + ( $extra_fields[ 'navigation_horizontal_offset' ][ 'condition' ] ?? []),
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [ 'min' => -600, 'max' => 600 ],
                        '%' => [ 'min' => -60, 'max' => 60 ],
                    ],
                    'default' => [ 'unit' => '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button-prev,
                         {{WRAPPER}} .motion-prev' => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-swiper-button-next,
                         {{WRAPPER}} .motion-next' => 'right: {{SIZE}}{{UNIT}};',

                        '{{WRAPPER}} .navigation-posiiton-nearby .elementor-swiper-button-prev,
                         {{WRAPPER}} .navigation-posiiton-nearby .motion-prev' => 'left: calc({{SIZE}}{{UNIT}} + 50% - var(--wgl-swiper-button-diameter) - var(--wgl-swiper-buttons-distance) / 2);',
                        '{{WRAPPER}} .navigation-posiiton-nearby .elementor-swiper-button-next,
                         {{WRAPPER}} .navigation-posiiton-nearby .motion-next' => 'left: calc({{SIZE}}{{UNIT}} + 50% + 0px + var(--wgl-swiper-buttons-distance) / 2);',

                        '{{WRAPPER}} .navigation-posiiton-nearby .animation-direction-vertical .motion-prev' => 'left: calc({{SIZE}}{{UNIT}} + 50% - var(--wgl-swiper-button-diameter));',
                        '{{WRAPPER}} .navigation-posiiton-nearby .animation-direction-vertical .motion-next' => 'left: calc({{SIZE}}{{UNIT}} + 50%);',
                    ],
                ]
            );

            $self->add_control(
                'navigation_customize_colors',
                [
                    'label' => esc_html__( 'Customize Colors', 'wgl-extensions' ),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [ 'use_navigation' => 'yes' ]
                        + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] )
                        + ( $extra_fields[ 'navigation_customize_colors' ][ 'condition' ] ?? [] ),
                ]
            );

            $self->start_controls_tabs(
                'navigation_style',
                [
                    'condition' => [
                        'navigation_customize_colors' => 'yes',
                        'use_navigation' => 'yes',
                    ] + ( $extra_fields[ 'use_navigation' ][ 'condition' ] ?? [] ),
                ]
            );

            $self->start_controls_tab(
                'navigation_idle',
                [ 'label' => esc_html__( 'Idle', 'wgl-extensions' ) ]
            );

            $self->add_control(
                'navigation_color_idle',
                [
                    'label' => esc_html__( 'Icon Color', 'wgl-extensions' ),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:after,
                         {{WRAPPER}} .motion-arrow:after' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'navigation_bg_idle',
                [
                    'label' => esc_html__( 'Background Color', 'wgl-extensions' ),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button,
                         {{WRAPPER}} .motion-arrow' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'navigation_border_idle',
                    'fields_options' => [
                        'width' => [
                            'label' => esc_html__( 'Border Width', 'wgl-extensions' ),
                            'selectors' => [
                                '{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                                                . 'width: calc(var(--wgl-swiper-button-diameter) + {{LEFT}}{{UNIT}} + {{RIGHT}}{{UNIT}});'
                                                . 'height: calc(var(--wgl-swiper-button-diameter) + {{TOP}}{{UNIT}} + {{BOTTOM}}{{UNIT}});',
                            ],
                        ],
                        'color' => [
                            'label' => esc_html__( 'Border Color', 'wgl-extensions' )
                        ]
                    ],
                    'selector' => '{{WRAPPER}} .elementor-swiper-button,'
                                . '{{WRAPPER}} .motion-arrow',

                ]
            );

            $self->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'navigation_shadow_idle',
                    'selector' => '{{WRAPPER}} .elementor-swiper-button,'
                                . '{{WRAPPER}} .motion-arrow',
                ]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'navigation_hover',
                [ 'label' => esc_html__( 'Hover', 'wgl-extensions' ) ]
            );

            $self->add_control(
                'navigation_color_hover',
                [
                    'label' => esc_html__( 'Icon Color', 'wgl-extensions' ),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:hover:after,
                         {{WRAPPER}} .motion-arrow:hover:after' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'navigation_bg_hover',
                [
                    'label' => esc_html__('Background Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:hover,
                         {{WRAPPER}} .motion-arrow:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'navigation_border_hover',
                    'fields_options' => [
                        'width' => [ 'label' => esc_html__( 'Border Width', 'wgl-extensions' ) ],
                        'color' => [ 'label' => esc_html__( 'Border Color', 'wgl-extensions' ) ],
                    ],
                    'selector' => '{{WRAPPER}} .elementor-swiper-button:hover,'
                                . '{{WRAPPER}} .motion-arrow:hover',
                ]
            );

            $self->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'navigation_shadow_hover',
                    'selector' => '{{WRAPPER}} .elementor-swiper-button:hover',
                ]
            );

            $self->end_controls_tab();

            $self->start_controls_tab(
                'navigation_active',
                [ 'label' => esc_html__( 'Active', 'wgl-extensions' ) ]
            );

            $self->add_control(
                'navigation_color_active',
                [
                    'label' => esc_html__('Icon Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:active:after' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_control(
                'navigation_bg_active',
                [
                    'label' => esc_html__('Background Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
			    'dynamic' => [  'active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-swiper-button:active' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $self->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'navigation_border_active',
                    'fields_options' => [
                        'width' => [ 'label' => esc_html__( 'Border Width', 'wgl-extensions' ) ],
                        'color' => [ 'label' => esc_html__( 'Border Color', 'wgl-extensions' ) ],
                    ],
                    'selector' => '{{WRAPPER}} .elementor-swiper-button:active,'
                                . '{{WRAPPER}} .motion-arrow:active',
                ]
            );

            $self->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'navigation_shadow_active',
                    'selector' => '{{WRAPPER}} .elementor-swiper-button:active',
                ]
            );

            $self->end_controls_tab();
            $self->end_controls_tabs();
        }

        public static function add_responsive_controls($self, $extra_fields = [])
        {
            $default_breakpoints = self::get_default_responsive_breakpoints();

            $self->add_control(
                'customize_responsive',
                [
                    'label' => esc_html__('Customize Responsive', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => ($extra_fields['customize_responsive']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'heading_desktop',
                [
                    'label' => esc_html__('Desktop Settings', 'wgl-extensions'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['heading_desktop']['condition'] ?? []),
                ]
            );

            $self->add_control(
                'desktop_breakpoint',
                [
                    'label' => esc_html__('Screen Breakpoint', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['desktop_breakpoint']['condition'] ?? []),
	                'description' => esc_html__('Equal or greater width screens are targeted.', 'wgl-extensions'),
                    'placeholder' => esc_attr($default_breakpoints['desktop']),
                    'min' => 500,
                    'default' => ($extra_fields['desktop_breakpoint']['default'] ?? $default_breakpoints['desktop']),
                ]
            );

            $self->add_control(
                'desktop_slides',
                [
                    'label' => ($extra_fields['desktop_slides']['label'] ?? esc_html__('Slides to show', 'wgl-extensions')),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['desktop_slides']['condition'] ?? []),
                    'min' => 1,
                    'max' => ($extra_fields['desktop_slides']['max'] ?? ''),
                ]
            );

            $self->add_control(
                'heading_tablet',
                [
                    'label' => esc_html__('Tablet Settings', 'wgl-extensions'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['heading_tablet']['condition'] ?? []),
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                'tablet_breakpoint',
                [
                    'label' => esc_html__('Screen Breakpoint', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['tablet_breakpoint']['condition'] ?? []),
	                'description' => esc_html__('Equal or greater width screens are targeted.', 'wgl-extensions'),
                    'placeholder' => esc_attr($default_breakpoints['tablet']),
                    'min' => 400,
                    'default' => ($extra_fields['tablet_breakpoint']['default'] ?? $default_breakpoints['tablet']),
                ]
            );

            $self->add_control(
                'tablet_slides',
                [
                    'label' => ($extra_fields['tablet_slides']['label'] ?? esc_html__('Slides to show', 'wgl-extensions')),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['tablet_slides']['condition'] ?? []),
                    'min' => 1,
                    'max' => ($extra_fields['tablet_slides']['max'] ?? ''),
                ]
            );

            $self->add_control(
                'heading_mobile',
                [
                    'label' => esc_html__('Mobile Settings', 'wgl-extensions'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['heading_mobile']['condition'] ?? []),
                    'separator' => 'before',
                ]
            );

            $self->add_control(
                'mobile_breakpoint',
                [
                    'label' => esc_html__('Screen Breakpoint', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['mobile_breakpoint']['condition'] ?? []),
	                'description' => esc_html__('Equal or greater width screens are targeted.', 'wgl-extensions'),
                    'placeholder' => esc_attr($default_breakpoints['mobile']),
                    'min' => 280,
                    'default' => ($extra_fields['mobile_breakpoint']['default'] ?? $default_breakpoints['mobile']),
                ]
            );

            $self->add_control(
                'mobile_slides',
                [
                    'label' => ($extra_fields['mobile_slides']['label'] ?? esc_html__('Slides to show', 'wgl-extensions')),
                    'type' => Controls_Manager::NUMBER,
			    'dynamic' => [  'active' => true],
                    'condition' => ['customize_responsive' => 'yes']
                        + ($extra_fields['customize_responsive']['condition'] ?? [])
                        + ($extra_fields['mobile_slides']['condition'] ?? []),
                    'min' => 1,
                    'max' => ($extra_fields['mobile_slides']['max'] ?? ''),
                ]
            );
        }

        /**
         * @since 1.0.0
         * @version 1.0.5
         */
        public static function init($atts, $items = [], $templates = false)
        {
            // ↓ Attributes validation
            extract(
                shortcode_atts([
                    // General
                    'slides_transition' => 300,
                    'animation_style' => 'default',
                    'animation_triggered_by_mouse' => 'yes',
                    'slides_per_row' => 1,
                    'autoplay' => true,
                    'autoplay_speed' => 3000,
                    'autoplay_pause' => true,
                    'autoplay_reverse' => false,
                    'slide_per_single' => false,
                    'slider_infinite' => false,
                    'adaptive_height' => false,
                    'fade_animation' => false,
                    'variable_width' => false,
                    'center_mode' => false,
                    'extra_class' => '',
                    // Pagination
                    'use_pagination' => true,
                    'pagination_type' => 'circle',
                    // Navigation
                    'use_navigation' => false,
                    'navigation_position' => '',
                    // Responsive
                    'customize_responsive' => false,
                    'desktop_slides' => '',
                    'tablet_slides' => '',
                    'mobile_slides' => '',
                    'responsive_gap' => false,
                ], $atts)
            );

            $breakpoints = [
                'desktop' => $atts['desktop_breakpoint'] ?? '',
                'tablet' => $atts['tablet_breakpoint'] ?? '',
                'mobile' => $atts['mobile_breakpoint'] ?? '',
            ];
            // ↑ attributes validation

            if ('default' === $animation_style) {
                $slides_per_row = (int) $slides_per_row ? (int) $slides_per_row : 'auto';
                $slide_per_single = (int) (bool) $slide_per_single;

                $data_array['animationDuration'] = $slides_transition;
                $data_array['infinite'] = (bool) $slider_infinite;
                $data_array['variableWidth'] = (bool) $variable_width;
                $data_array['autoplay'] = (bool) $autoplay;
                $data_array['autoplaySpeed'] = $autoplay_speed;
                $data_array['autoplayPause'] = (bool) $autoplay_pause;
                $data_array['autoplayReverse'] = (bool) $autoplay_reverse;
	            $data_array['watchOverflow'] = true;
                if ($center_mode) {
                    $data_array['centerMode'] = true;
                    $data_array['centerPadding'] = '0px';
                }
                $data_array['arrows'] = (bool) $use_navigation;
                $data_array['dots'] = (bool) $use_pagination;
                $data_array['adaptiveHeight'] = (bool) $adaptive_height;

                // ↓ Responsive
                $default_breakpoints = self::get_default_responsive_breakpoints();
                $default_slides = [
                    'desktop' => $slides_per_row,
                    'tablet' => $slides_per_row > 1 ? 2 : 1,
                    'mobile' => 1,
                ];

                $desktop_breakpoint = $breakpoints['desktop'] ?: $default_breakpoints['desktop'];
                $tablet_breakpoint = $breakpoints['tablet'] ?: $default_breakpoints['tablet'];
                $mobile_breakpoint = $breakpoints['mobile'] ?: $default_breakpoints['mobile'];

                $desktop_slides = $customize_responsive && $desktop_slides ? $desktop_slides : $default_slides['desktop'];
                $tablet_slides = $customize_responsive && $tablet_slides ? $tablet_slides : $default_slides['tablet'];
                $mobile_slides = $customize_responsive && $mobile_slides ? $mobile_slides : $default_slides['mobile'];

                $xxl_breakpoint = 1600;
                if ($xxl_breakpoint > $desktop_breakpoint) {
                    $data_array['responsive'][] = [
                        'breakpoint' => $xxl_breakpoint,
                        'slidesToShow' => esc_attr($default_slides['desktop']),
                        'slidesToScroll' => $slide_per_single ?: esc_attr($default_slides['desktop']),
                    ];
                    if ($responsive_gap) {
                        $gap_xxl = (int) esc_attr($responsive_gap['desktop_gap']['size']);
                        $data_array['responsive'][count($data_array['responsive']) - 1]['gap'] = $gap_xxl;
                    }
                }

                $data_array['responsive'][] = [
                    'breakpoint' => (int) $desktop_breakpoint,
                    'slidesToShow' => esc_attr($desktop_slides),
                    'slidesToScroll' => $slide_per_single ?: esc_attr($desktop_slides),
                ];
                if ($responsive_gap && !empty($responsive_gap['desktop_gap']['size'])) {
                    $gap_lg = (int) esc_attr($responsive_gap['desktop_gap']['size']);
                    $data_array['responsive'][count($data_array['responsive']) - 1]['gap'] = $gap_lg;
                }

                $data_array['responsive'][] = [
                    'breakpoint' => (int) $tablet_breakpoint,
                    'slidesToShow' => esc_attr($tablet_slides),
                    'slidesToScroll' => $slide_per_single ?: esc_attr($tablet_slides),
                ];
                if ($responsive_gap && !empty($responsive_gap['tablet_gap']['size'])) {
                    $gap_md = (int) esc_attr($responsive_gap['tablet_gap']['size']);
                    $data_array['responsive'][count($data_array['responsive']) - 1]['gap'] = $gap_md;
                }

                $data_array['responsive'][] = [
                    'breakpoint' => (int) $mobile_breakpoint,
                    'slidesToShow' => esc_attr($mobile_slides),
                    'slidesToScroll' => $slide_per_single ?: esc_attr($mobile_slides),
                ];
                if ($responsive_gap && !empty($responsive_gap['mobile_gap']['size'])) {
                    $gap_sm = (int) esc_attr($responsive_gap['mobile_gap']['size']);
                    $data_array['responsive'][count($data_array['responsive']) - 1]['gap'] = $gap_sm;
                }
                // ↑ responsive

                $carousel_id = uniqid('wgl_carousel_');

                $data_attribute = " data-swiper='" . json_encode($data_array, true) . "'";
                $data_attribute .= " data-item-carousel='" . $carousel_id . "'";

                // Classes
                $navigation_position_class = $use_navigation && $navigation_position ? ' navigation-posiiton-' . $navigation_position : '';
                $wrapper_classes = $navigation_position_class;

                $carousel_classes = $use_pagination ? ' pagination_' . $pagination_type : '';
                $carousel_classes .= $center_mode ? ' center-mode' : '';
                $carousel_classes .= $variable_width ? ' variable-width' : '';
                $carousel_classes .= $fade_animation ? ' fade_swiper' : '';
                $carousel_classes .= ' ' . $extra_class;

                // Render
                $output = '<div class="wgl-carousel_wrapper' . esc_attr($wrapper_classes) . '">';
                $output .= '<div class="wgl-carousel swiper wgl-carousel_swiper swiper-container' . esc_attr($carousel_classes) . '"' . $data_attribute . '>';
                $output .= '<div class="swiper-wrapper">';

                if (!empty($templates)) {
                    if (!empty($items)) {
                        ob_start();
                        foreach ($items as $id) if ($id) {
                            echo '<div class="item swiper-slide">',
                                (new Frontend())->get_builder_content_for_display($id, true),
                            '</div>';
                        }
                        $output .= ob_get_clean();
                    }
                } else {
                    $output .= $items;
                }

                $output .= '</div>';

                if ($use_pagination) {
                    $output .= '<ul class="swiper-pagination" role="tablist" data-carousel="' . $carousel_id . '"></ul>';
                }

                $output .= '</div>';

                if ($use_navigation) {
                    $output .= '<div class="wgl-navigation_wrapper">';
                        $output .= '<button class="elementor-swiper-button elementor-swiper-button-prev" data-carousel="' . $carousel_id . '"></button>';
                        $output .= '<button class="elementor-swiper-button elementor-swiper-button-next" data-carousel="' . $carousel_id . '"></button>';
                    $output .= '</div>';
                }

                $output .= '</div>';

            } else {

                // Classes
                $wrapper_classes   = $use_navigation && $navigation_position ? ' navigation-posiiton-' . $navigation_position : '';

                $carousel_classes  = ' animation-style-3d';
                $carousel_classes .= ' animation-direction-' . $animation_style;
                $carousel_classes .= $animation_triggered_by_mouse ? ' animated-by-mouse-wheel' : '';
                $carousel_classes .= $extra_class;

                // Render
                $output = '<div class="wgl-carousel_wrapper' . esc_attr($wrapper_classes) . '">';
                    $output .= '<div class="wgl-carousel' . esc_attr($carousel_classes) . '">';
                        $output .= $use_navigation ? '<button class="motion-prev motion-arrow" type="button"></button>' : '';
                        $output .= '<div class="wgl-carousel_wrap">';

                        if (!empty($templates)) {
                            if (!empty($items)) {
                                ob_start();
                                foreach ($items as $id) if ($id) {
                                    echo '<div class="item">',
                                        (new Frontend())->get_builder_content_for_display($id, true),
                                    '</div>';
                                }
                                $output .= ob_get_clean();
                            }
                        } else {
                            $output .= $items;
                        }

                        $output .= '</div>';
                        $output .= $use_navigation ? '<button class="motion-next motion-arrow" type="button"></button>' : '';
                    $output .= '</div>';
                $output .= '</div>';
            }

            return $output;
        }

        public static function get_default_responsive_breakpoints()
        {
            $elementor_container_width = (int) wgl_dynamic_styles()->get_elementor_container_width();

            return [
                'desktop' => $elementor_container_width ? $elementor_container_width + 1 : 1201,
                'tablet' => 768,
                'mobile' => 280,
            ];
        }

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }

    new WGL_Carousel_Settings();
}
