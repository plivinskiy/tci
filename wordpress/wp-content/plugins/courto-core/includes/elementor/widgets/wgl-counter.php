<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-counter.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Text_Stroke,
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons
};

class WGL_Counter extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-counter';
    }

    public function get_title()
    {
        return esc_html__('WGL Counter', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-counter';
    }

    public function get_keywords()
    {
        return [ 'counter' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_counter_content',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'counter_title',
            [
                'label' => esc_html__('Title Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'label_block' => true,
                'default' => esc_html__('Counter Title', 'courto-core'),
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
        $end = is_rtl() ? 'left' : 'right';
        $this->add_responsive_control(
            'direction',
            [
                'label' => esc_html__('Direction', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Row - horizontal', 'elementor' ),
                        'icon' => 'eicon-arrow-' . $end,
                    ],
                    'column' => [
                        'title' => esc_html__( 'Column - vertical', 'elementor' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Row - reversed', 'elementor' ),
                        'icon' => 'eicon-arrow-' . $start,
                    ],
                    'column-reverse' => [
                        'title' => esc_html__( 'Column - reversed', 'elementor' ),
                        'icon' => 'eicon-arrow-up',
                    ],
                ],
                'default' => 'column',
                'selectors' => [
                    '{{WRAPPER}} .header-wrap' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'justify_content',
            [
                'label' => esc_html__( 'Justify Content', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'elementor' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                ],
                'condition' => ['direction' => ['row', 'row-reverse']],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .header-wrap' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__( 'Align Items', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'elementor' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'elementor' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'elementor' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'elementor' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'condition' => ['direction' => ['row', 'row-reverse']],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .header-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'align_items_column',
            [
                'label' => esc_html__( 'Align Items', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'elementor' ),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'elementor' ),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'elementor' ),
                        'icon' => 'eicon-align-end-h',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'elementor' ),
                        'icon' => 'eicon-align-stretch-h',
                    ],
                ],
                'condition' => ['direction' => ['column', 'column-reverse']],
                'default' => 'stretch',
                'selectors' => [
                    '{{WRAPPER}} .header-wrap' => 'align-items: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'counter_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px'],
                'default' => ['size' => 14, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .header-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Content Alignment', 'courto-core'),
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
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .content-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'start_value',
            [
                'label' => esc_html__('Start Value', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'min' => 0,
                'step' => 1,
                'default' => 0,
            ]
        );

        $this->add_control(
            'end_value',
            [
                'label' => esc_html__('End Value', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 1,
                'step' => 1,
                'default' => 200,
            ]
        );

        $this->add_control(
            'prefix',
            [
                'label' => esc_html__('Counter Prefix', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label' => esc_html__('Counter Suffix', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('ex: +', 'courto-core'),
                'default' => '+',
            ]
        );

        $this->add_control(
            'slidesTransition',
            [
                'label' => esc_html__('Animation Speed', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 100,
                'step' => 100,
                'default' => 1200,
            ]
        );

        $this->add_control(
            'thousand_sep',
            [
                'label' => esc_html__('Thousand Separator', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    '.' => esc_html__('Dot', 'courto-core'),
                    ',' => esc_html__('Сomma', 'courto-core'),
                    ' ' => esc_html__('Space', 'courto-core'),
                ],
                'default' => '.',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'counter_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'counter_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_min_height',
            [
                'label' => esc_html__('Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('counter_color_tab');

        $this->start_controls_tab(
            'custom_counter_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'bg_counter_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_border',
                'label' => esc_html__('Border Type', 'courto-core'),
                'selector' => '{{WRAPPER}} .wgl-counter',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow',
                'selector' => '{{WRAPPER}} .wgl-counter',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_counter_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'bg_counter_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-counter' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_border_hover',
                'label' => esc_html__('Border Type', 'courto-core'),
                'selector' => '{{WRAPPER}}:hover .wgl-counter',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow_hover',
                'selector' => '{{WRAPPER}}:hover .wgl-counter',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Media', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['icon_type!' => ''],
            ]
        );
        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'range' => [
                    'px' => ['min' => 13, 'max' => 200],
                ],
                'default' => ['size' => 30],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_type' => 'image' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 768 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '17',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'counter_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_background',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .media-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_icon_border',
                'selector' => '{{WRAPPER}} .media-wrapper'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_icon_shadow',
                'selector' => '{{WRAPPER}} .media-wrapper',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> VALUE
         */

        $this->start_controls_section(
            'value_style_section',
            [
                'label' => esc_html__('Value', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'value_overflow',
            [
                'label' => esc_html__('Overflow', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'courto-core'),
                    'hidden' => esc_html__('Hidden', 'courto-core'),
                ],
                'default' => 'hidden',
            ]
        );

        $this->add_responsive_control(
            'value_offset',
            [
                'label' => esc_html__('Value Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_font_family',
            [
                'label' => esc_html__('Theme Font Family', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'header' => esc_html__('Headings Font', 'courto-core'),
                    'content' => esc_html__('Content Font', 'courto-core'),
                    'additional' => esc_html__('Additional Font', 'courto-core'),
                ],
                'default' => 'header',
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-wrap' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_value',
                'selector' => '{{WRAPPER}} .wgl-counter__value-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'value_border',
                'selector' => '{{WRAPPER}} .wgl-counter__value-wrap, {{WRAPPER}} .wgl-counter__value-hidden',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-inner' => '-webkit-text-stroke: {{SIZE}}px transparent;',
                ],
            ]
        );
        $this->add_control(
            'value_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'value_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-inner' => 'background: {{VALUE}};  -webkit-background-clip: text;',
                ],
            ]
        );
        $this->add_control(
            'value_stroke_color2',
            [
                'label' => esc_html__('Secondary Stroke Color for Gradient', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'value_stroke_size[size]!' => [0, ''],
                    'value_stroke_color!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__value-inner' => 'background: linear-gradient({{value_stroke_gradient_angle.SIZE}}deg, {{VALUE}}, {{value_stroke_color.VALUE}});',
                ],
            ]
        );
        $this->add_control(
            'value_stroke_gradient_angle',
            [
                'label' => esc_html__('Set Angle for Gradient', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'deg' => ['min' => -360, 'max' => 360] ],
                'size_units' => ['deg'],
                'condition' => [
                    'value_stroke_size[size]!' => [0, ''],
                    'value_stroke_color!' => '',
                    'value_stroke_color2!' => '',
                ],
                'default' => ['size' => 0, 'unit' => 'deg'],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'title_font_family',
            [
                'label' => esc_html__('Theme Font Family', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'header' => esc_html__('Headings Font', 'courto-core'),
                    'content' => esc_html__('Content Font', 'courto-core'),
                    'additional' => esc_html__('Additional Font', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 50, 'max' => 270, 'step' => 1],
                ],
                'default' => ['size' => 180],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-counter_title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> PREFIX
         */

        $this->start_controls_section(
            'prefix_style_section',
            [
                'label' => esc_html__('Prefix', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['prefix!' => ''],
            ]
        );

        $this->add_responsive_control(
            'prefix_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_prefix',
                'selector' => '{{WRAPPER}} .wgl-counter__prefix',
            ]
        );

        $this->add_control(
            'prefix_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> SUFFIX
         */

        $this->start_controls_section(
            'suffix_style_section',
            [
                'label' => esc_html__('Suffix', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['suffix!' => ''],
            ]
        );

        $this->add_responsive_control(
            'suffix_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_suffix',
                'selector' => '{{WRAPPER}} .wgl-counter__suffix',
            ]
        );

        $this->add_control(
            'suffix_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter__suffix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-counter_content',
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        $kses_allowed_html = [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'i' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'small' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
            'li' => ['id' => true, 'class' => true, 'style' => true],
        ];

        $this->add_render_attribute(
            [
                'counter' => [
                    'class' => [ 'wgl-counter' ]
                ],
                'counter-wrap' => [
                    'class' => [ 'wgl-counter_wrap' ],
                ],
                'counter_value' => [
                    'class' => 'wgl-counter__value',
                    'data-start-value' => $_s['start_value'] ?? 0,
                    'data-end-value' => $_s['end_value'] ?? 100,
                    'data-speed' => $_s['speed'] ?? 1200,
                    'data-sep' => $_s['thousand_sep'],
                ],
            ]
        );

        // Title
        $counter_title = $_s['counter_title'] ? '<' . $_s['title_tag'] . ' class="wgl-counter_title">' . $_s['counter_title'] . '</' . $_s['title_tag'] . '>' : '';

        $placeholder = $value = '';
        if (!empty($_s['end_value'])) {

            $_s['prefix'] = !empty($_s['prefix']) ? '<span class="wgl-counter__prefix">'. $_s['prefix']. '</span>' : '';
            $_s['suffix'] = !empty($_s['suffix']) ? '<span class="wgl-counter__suffix">'.$_s['suffix'].'</span>' : '';

            $placeholder = '<span class="wgl-counter__placeholder">' .$_s['prefix'] . preg_replace('/\B(?=(\d{3})+(?!\d))/', $_s['thousand_sep'], $_s['end_value']) . $_s['suffix'] . '</span>';
            $value = '<span ' . $this->get_render_attribute_string('counter_value') . '>' . $_s['start_value'] . '</span>';
            $value = '<div class="wgl-counter__value-inner">' . $_s['prefix'] . $value . $_s['suffix'] . '</div>';

        }

        // Render
        echo '<div '. $this->get_render_attribute_string('counter'). '>';
            echo '<div '. $this->get_render_attribute_string('counter-wrap'). '>';
                echo '<div class="content-wrap">';
                    echo '<div class="header-wrap">';

                        if (!empty($_s['end_value'])) {
                            if ('hidden' === $_s['value_overflow']) {
                                echo '<div class="wgl-counter__value-hidden">';
                            }
                                echo '<div class="wgl-counter__value-wrap">';
                                    echo $placeholder;
                                    echo $value;
                                echo '</div>';
                            if ('hidden' === $_s['value_overflow']) {
                                echo '</div>';
                            }
                        }

                        echo $counter_title;

                    echo '</div>'; // header-wrap

                    if (!empty($_s['counter_content'])) {
                        echo '<div class="wgl-counter_content">'.wp_kses($_s['counter_content'], $kses_allowed_html).'</div>';
                    }

                echo '</div>'; // content-wrap
            echo '</div>';
        echo '</div>';
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
