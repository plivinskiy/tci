<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-steps.php`.
 */

namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Utils,
    Icons_Manager,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Repeater,
    Group_Control_Image_Size,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography
};
use WGL_Extensions\Includes\WGL_Carousel_Settings;
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class Wgl_Steps extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-steps';
    }

    public function get_title()
    {
        return esc_html__('WGL Steps', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-steps';
    }

    public function get_keywords()
    {
        return ['time', 'line', 'timeline', 'date', 'history', 'steps', 'carousel'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['swiper', 'wgl-widgets'];
    }

    public function get_style_depends()
    {
        return [ 'swiper' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_steps_section',
            [
                'label' => esc_html__('General', 'courto-core'),
            ]
        );

        $this->add_control(
            'steps_columns',
            [
                'label' => esc_html__('Columns', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => [ 'size' => 3 ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'default' => ['size' => 30],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .steps-dot .steps-dot-line' => '--gap:{{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'line_position',
            [
                'label' => esc_html__( 'Line Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'middle' => esc_html__( 'Middle', 'courto-core' ),
                    'bottom' => esc_html__( 'Bottom', 'courto-core' ),
                ],
                'default' => 'top',
                'prefix_class' => 'line-position-'
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap' => 'text-align: {{VALUE}}; justify-items: {{VALUE}};',
                    '{{WRAPPER}} .steps-dot' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'invert',
            [
                'label' => esc_html__('Invert the Image and Content', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'steps_icon_type',
            [
                'label' => esc_html__('Add Image', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__( 'None', 'courto-core' ),
                        'icon' => 'eicon-ban',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'courto-core' ),
                        'icon' => 'far fa-image',
                    ]
                ],
                'default' => '',
            ]
        );

        $repeater->add_control(
            'steps_icon_thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => ['steps_icon_type' => 'image'],
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['steps_icon_type' => 'image'],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 400, 'step' => 1],
                ],
                'default' => ['size' => 160, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .steps-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_html__('This is the date', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_html__('This is the number', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('This is the heading', 'courto-core'),
                'placeholder' => esc_html__('This is the heading', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 'courto-core'),
            ]
        );

        $repeater->add_responsive_control(
            'tab_image_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .steps-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->start_controls_tabs( 'tabs_image' );
        $repeater->start_controls_tab(
            'image_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $repeater->add_control(
            'tab_image_bg_color_idle',
            [
                'label' => esc_html__('Image Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .steps-image img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_image_shadow_item_idle',
                'label' => esc_html__('Image Box Shadow', 'courto-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .steps-image img',
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'image_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $repeater->add_control(
            'tab_image_bg_color_hover',
            [
                'label' => esc_html__('Image Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.steps-single-wrap:hover .steps-image img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_image_shadow_item_hover',
                'label' => esc_html__('Image Box Shadow', 'courto-core'),
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.steps-single-wrap:hover .steps-image img',
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'date' => esc_html__('1989', 'courto-core'),
                        'title' => esc_html__('First Heading', 'courto-core'),
                        'invert' => 'yes',
                    ],
                    [
                        'date' => esc_html__('1990', 'courto-core'),
                        'title' => esc_html__('Second Heading', 'courto-core'),
                    ],
                    [
                        'date' => esc_html__('1992', 'courto-core'),
                        'title' => esc_html__('Third Heading', 'courto-core'),
                        'invert' => 'yes',
                    ],
                    [
                        'date' => esc_html__('1995', 'courto-core'),
                        'title' => esc_html__('Fourth Heading', 'courto-core'),
                    ],
                    [
                        'date' => esc_html__('1998', 'courto-core'),
                        'title' => esc_html__('Fifth Heading', 'courto-core'),
                        'invert' => 'yes',
                    ]
                ],
                'title_field' => '{{title}}',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CAROUSEL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'carousel_section',
            [
                'label' => esc_html__('Carousel', 'courto-core'),
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this,[
            'slider_alignment_v' => [ 'default' => 'center' ],
            'slider_container_padding' => [
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ],
            'animation_style_disable' => true,
        ]);
        WGL_Carousel_Settings::add_pagination_controls($this);
        WGL_Carousel_Settings::add_navigation_controls($this, [
            'use_navigation' => [
                'default' => 'yes',
            ],
            'navigation_position' => [ 'default' => 'nearby' ],
            'navigation_alignment_h' => [ 'default' => 'center' ],
            'navigation_alignment_v' => [ 'default' => 'flex-end' ],
            'navigation_margin' => [
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '-94',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '-50',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ],
        ]);
        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general_style_section',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'line_full_width',
            [
                'label' => esc_html__('Set a Line Full Width', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .steps-dot .steps-dot-line' => '--gap-right: {{RIGHT}}{{UNIT}}; --gap-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'line_size',
            [
                'label' => esc_html__('Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                ],
                'size_units' => ['px'],
                'default' => ['size' => 1, 'unit' => 'px'],
                'description' => esc_html__('Enter line height in pixels.', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .steps-dot-line::before, {{WRAPPER}} .steps-dot-line::after' => 'height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'line_color_1',
            [
                'label' => esc_html__('Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .steps-dot-line' => '--color-1: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_offset',
            [
                'label' => esc_html__('Dot Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => ['min' => 10, 'max' => 200, 'step' => 1],
                ],
                'size_units' => ['px'],
                'default' => ['size' => 48, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap' => '--grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('dot_tabs');
        $this->start_controls_tab( 'dot_idle', [
            'label' => esc_html__('Idle', 'courto-core'),
        ] );
        $this->add_control(
            'dot_color_idle',
            [
                'label' => esc_html__('Dot Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dot::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_size_idle',
            [
                'label' => esc_html__('Dot Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 5, 'max' => 100, 'step' => 1],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-steps' => '--dot-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab( 'dot_hover', [
            'label' => esc_html__('Hover', 'courto-core'),
        ] );
        $this->add_control(
            'dot_color_hover',
            [
                'label' => esc_html__('Dot Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .dot::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_size_hover',
            [
                'label' => esc_html__('Dot Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 5, 'max' => 100, 'step' => 1],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-steps' => '--dot-size-hover: {{SIZE}}px;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> DATE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'date_style_section',
            [
                'label' => esc_html__('Date', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typo',
                'selector' => '{{WRAPPER}} .steps-date',
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '4',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .steps-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-date span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'date_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .steps-date span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'date_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .steps-date span',
            ]
        );

        $this->start_controls_tabs('date_colors');
        $this->start_controls_tab(
            'date_colors_idle',
            [
                'label' => esc_html__('Idle', 'courto-core'),
            ]
        );
        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'date_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-date span' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'date_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'date_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .steps-date span' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'date_colors_hover',
            [
                'label' => esc_html__('Hover', 'courto-core'),
            ]
        );
        $this->add_control(
            'date_hover_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'date_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-date span' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'date_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'date_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-date span' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> NUMBER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'number_style_section',
            [
                'label' => esc_html__('Number', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typo',
                'selector' => '{{WRAPPER}} .steps-number',
            ]
        );

        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('number_colors');
        $this->start_controls_tab(
            'number_colors_idle',
            [
                'label' => esc_html__('Idle', 'courto-core'),
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .steps-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'number_colors_hover',
            [
                'label' => esc_html__('Hover', 'courto-core'),
            ]
        );
        $this->add_control(
            'number_hover_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .steps-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '28',
                    'right' => '0',
                    'bottom' => '22',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .steps-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('title_colors');

        $this->start_controls_tab(
            'title_colors_idle',
            [
                'label' => esc_html__('Idle', 'courto-core'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .steps-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_colors_hover',
            [
                'label' => esc_html__('Hover', 'courto-core'),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typo',
                'selector' => '{{WRAPPER}} .steps-text',
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => esc_html__( 'Max Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom'],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000 ],
                ],
                'default' => [
                    'size' => 285,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .steps-text' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '13',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .steps-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('content_colors');
        $this->start_controls_tab(
            'content_colors_idle',
            [
                'label' => esc_html__('Idle', 'courto-core'),
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .steps-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-content_wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .steps-content_wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} .steps-content_wrap',
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'content_colors_hover',
            [
                'label' => esc_html__('Hover', 'courto-core'),
            ]
        );

        $this->add_control(
            'content_hover_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-content_wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_hover_border',
                'selector' => '{{WRAPPER}} .steps-single-wrap:hover .steps-content_wrap',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_hover_shadow',
                'selector' => '{{WRAPPER}} .steps-single-wrap:hover .steps-content_wrap',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> Image
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '16',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .steps-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .steps-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'images_shadow',
                'selector' => '{{WRAPPER}} .steps-image img',
            ]
        );

        $this->start_controls_tabs( 'tabs_image' );
        $this->start_controls_tab(
            'image_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'image_bg_color_idle',
            [
                'label' => esc_html__('Image Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-image img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'v_line_color_idle',
            [
                'label' => esc_html__('Vertical Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'image_bg_color_hover',
            [
                'label' => esc_html__('Image Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .steps-image img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'v_line_color_hover',
            [
                'label' => esc_html__('Vertical Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .steps-single-wrap:hover .dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // Allowed HTML tags
        $allowed_html = [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
            'li' => ['id' => true, 'class' => true, 'style' => true],
        ];

        $this->add_render_attribute('steps', [
            'class' => [
                'wgl-steps',
            ],
        ]);

        ?>
    <div <?php echo $this->get_render_attribute_string('steps'); ?>>
        <div class="wgl-steps-wrap"><?php

            ob_start();
            foreach ($_s['items'] as $index => $item) {
                $_content = $_media = $_dot = '';

                $steps_wrap = $this->get_repeater_setting_key('steps_wrap', 'items', $index);
                $this->add_render_attribute(
                    $steps_wrap, [
                        'class' => [
                            'steps-item',
                            'elementor-repeater-item-' . $item['_id'],
                            $item['invert'] === 'yes' ? 'steps-single-invert' : '',
                            'swiper-slide',
                        ]
                    ]
                );

                $date = $this->get_repeater_setting_key('date', 'items', $index);
                $this->add_render_attribute( $date,
                    ['class' => 'steps-date']
                );

                $title = $this->get_repeater_setting_key('title', 'items', $index);
                $this->add_render_attribute( $title,
                    ['class' => 'steps-title']
                );

                if (
                    $item['steps_icon_type'] !== '' &&
                    $item['steps_icon_type'] === 'image' &&
                    !empty($item['steps_icon_thumbnail']) &&
                    !empty($item['steps_icon_thumbnail']['url'])
                ) {

                    $this->add_render_attribute('thumbnail', 'src', $item['steps_icon_thumbnail']['url']);
                    $this->add_render_attribute('thumbnail', 'alt', Control_Media::get_image_alt($item['steps_icon_thumbnail']));
                    $this->add_render_attribute('thumbnail', 'title', Control_Media::get_image_title($item['steps_icon_thumbnail']));

                    $_media .= '<div class="steps-same_height steps-media steps-image">' . Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'steps_icon_thumbnail') . '</div>';

                } else {
                    $_media .= '<div class="steps-same_height steps-media steps-empty"></div>';
                }
                // End Tab Icon/image

                $_dot .= '<div class="steps-dot">';
                    $_dot .= '<i class="steps-dot-line"></i>';
                    $_dot .= '<span class="dot">'.(!empty($item['number']) ? '<span class="steps-number">'.$item['number'].'</span>' : '' ).'</span>';
                $_dot .= '</div>';

                $_content .= '<div class="steps-same_height steps-content_wrap">';
                if (!empty($item['date'])) {
                    $_content .= '<h4 ' . $this->get_render_attribute_string($date) . '><span>' . $item['date'] . '</span></h4>';
                }
                if (!empty($item['title'])) {
                    $_content .= '<h3 ' . $this->get_render_attribute_string($title) . '>' . $item['title'] . '</h3>';
                }
                if (!empty($item['content'])) {
                    $_content .= '<div class="steps-text">' . wp_kses($item['content'], $allowed_html) . '</div>';
                }
                $_content .= '</div>';

                echo '<div ' . $this->get_render_attribute_string($steps_wrap) . '>',
                    '<div class="steps-single-wrap">',
                        $_media,
                        $_dot,
                        $_content,
                    '</div>',
                '</div>';

            } // end foreach

            $content = ob_get_clean();

            echo $this->apply_carousel_settings($content);

            if (!empty($_s['line_full_width'])) { ?><i class="steps-dot-line"></i><?php }
        ?></div>
        </div><?php
    }

    protected function apply_carousel_settings($content)
    {
        $_s = $this->get_settings_for_display();
        $_s['slides_per_row'] = $_s['steps_columns']['size'] ?? 4;
        $_s['gap'] = !empty($_s['gap']['size']) ? $_s['gap'] : ['size' => 0];
        $_s['responsive_gap'] = [
            'desktop_gap' => $_s['gap'],
            'tablet_gap' => !empty($_s['gap_tablet']['size']) ? $_s['gap_tablet'] : $_s['gap'],
            'mobile_gap' => !empty($_s['gap_mobile']['size']) ? $_s['gap_mobile'] : $_s['gap'],
        ];

        return WGL_Carousel_Settings::init($_s, $content);
    }

    public function wpml_support_module() {
        add_filter( 'wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter( $widgets ){
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}
