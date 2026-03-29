<?php

/**
 * This template can be overridden by copying it to `courto[-child]/courto-core/elementor/widgets/wgl-button-physics.php`.
 */

namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\WGL_Elementor_Helper;
use Elementor\{Frontend,
    Utils,
    Widget_Base,
    Repeater,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Controls_Manager};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
};

class WGL_Button_Physics extends Widget_Base
{

    public function get_name()
    {
        return 'wgl-button-physics';
    }

    public function get_title()
    {
        return esc_html__('WGL Button Physics', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-button-physics';
    }

    public function get_keywords()
    {
        return ['link', 'button', 'canvas', 'matter', 'physics'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['wgl-widgets', 'matter', 'jquery-appear'];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'content_type',
            [
                'label' => esc_html__( 'Content Type', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__( 'Content', 'courto-core' ),
                    'template' => esc_html__( 'Saved Templates', 'courto-core' ),
                ],
                'default' => 'content',
            ]
        );
        $this->add_control(
            'content_templates',
            [
                'label' => esc_html__( 'Choose Template', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [ 'content_type' => 'template' ],
            ]
        );
        $this->add_control(
            'subtitle_text',
            [
                'label' => esc_html__( 'Subtitle', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [ 'content_type' => 'content' ],
                'default' => '',
            ]
        );
        $this->add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [ 'content_type' => 'content' ],
                'default' => esc_html__( 'We Create World-Class Digital Products', 'courto-core' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'button_title',
            [
                'label' => esc_html__('Button Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Circuit Title', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url'        => '#',
                    'is_external'=> 'true',
                ],
            ]
        );

        $repeater->add_control(
            'button_shape',
            [
                'label'       => esc_html__('Button Shape', 'courto-core'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'circle',
                'options'     => [
                    'circle'  => esc_html__('Circle', 'courto-core'),
                    'square'  => esc_html__('Square', 'courto-core'),
                ],
                'label_block' => true,
            ]
        );

        // Loop requires a re-render so no 'render_type = none'
        $repeater->add_responsive_control(
            'button_width',
            [
                'label' => esc_html__('Circle Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [ 'button_shape' => 'circle' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item' => '--size-circle: {{SIZE}};',
                ],

            ]
        );

        $repeater->add_responsive_control(
            'button_square_width',
            [
                'label' => esc_html__('Button Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [ 'button_shape' => 'square' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item' => '--size-square-width: {{SIZE}};',
                ],

            ]
        );
        $repeater->add_responsive_control(
            'button_square_height',
            [
                'label' => esc_html__('Button Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [ 'button_shape' => 'square' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item' => '--size-square-height: {{SIZE}};',
                ],

            ]
        );
        
        $repeater->add_responsive_control(
            'button_square_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                ],
                'default' => ['size' => 40],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [ 'button_shape' => 'square' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item' => '--size-square-radius: {{SIZE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'button_border_width',
            [
                'label' => esc_html__('Border Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' =>
                        'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $repeater->add_control(
            'button_bg_image',
            [
                'label' => esc_html__('Background Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' =>
                        'background-image: url({{URL}}); background-size: cover; background-position: center;',
                ],
            ]
        );

        $repeater->start_controls_tabs( 'button_tabs' );
        $repeater->start_controls_tab(
            'custom_button_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $repeater->add_control(
            'button_color_idle',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'button_bg_color_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' => 'background: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $repeater->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'custom_button_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $repeater->add_control(
            'button_color_hover',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.hover .wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.hover .wgl-physics_item__inner' => 'background-color: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $repeater->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.hover .wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'custom_button_color_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $repeater->add_control(
            'button_color_active',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.active div.wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'button_bg_color_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.active div.wgl-physics_item__inner' => 'background-color: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $repeater->add_control(
            'button_border_color_active',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.wgl-physics_item.active div.wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $repeater->add_control(
            'hide_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $repeater->add_control(
            'hide_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => ['hide_on_mobile' => 'yes'],
                'default' => 767,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Buttons', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{button_title}}',
                'default' => [
                    [ 'button_title' => esc_html__('Circuit Title 1', 'courto-core') ],
                    [ 'button_title' => esc_html__('Circuit Title 2', 'courto-core') ],
                    [ 'button_title' => esc_html__('Circuit Title 3', 'courto-core') ],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__('Container Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', 'vh', 'custom' ],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1500],
                ],
                'default' => ['size' => 760],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_matter' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'items_restitution',
            [
                'label' => esc_html__('Restitution', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'description' => esc_html__('Defines the restitution (elasticity) of the body', 'courto-core'),
                'default' => ['size' => 0.5 ],
            ]
        );

        $this->add_control(
            'items_friction',
            [
                'label' => esc_html__('Friction', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'description' => esc_html__('Specifies a thin boundary around the body where it is allowed to slightly sink into other bodies.', 'courto-core'),
                'default' => ['size' => 0 ],
            ]
        );

        $this->add_control(
            'items_density',
            [
                'label' => esc_html__('Density', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'description' => esc_html__('Defines the density of the body', 'courto-core'),
                'default' => ['size' => 0.01 ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLES -> General
         */

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'matter_bg_idle',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_h_font_color(0.1),
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-physics_matter',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'matter_border',
                'render_type' => 'template',
                'selector' => '{{WRAPPER}} .wgl-physics_matter',
            ]
        );


        $this->add_responsive_control(
            'matter_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 500] ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_matter' => '--wgl-border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'general_shadow',
                'selector' => '{{WRAPPER}} .wgl-physics_matter',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLES -> ITEMS
         */

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'items_fonts',
                'selector' => '{{WRAPPER}} .wgl-physics_item__inner',
            ]
        );

        // Loop requires a re-render so no 'render_type = none'
        $this->add_responsive_control(
            'items_width',
            [
                'label' => esc_html__('Circles Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'default' => ['size' => 170],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item' => '--size-circle: {{SIZE}};',
                ],
                'description' => esc_html__(
                    'Controls the diameter of the circular physics button.',
                    'courto-core'
                ),
            ]
        );
        
        $this->add_responsive_control(
            'items_square_width',
            [
                'label' => esc_html__('Button Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'default' => ['size' => 190],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item' => '--size-square-width: {{SIZE}};',
                ],
                'description' => esc_html__(
                    'Sets the width of the square physics button.',
                    'courto-core'
                ),

            ]
        );
        $this->add_responsive_control(
            'items_square_height',
            [
                'label' => esc_html__('Button Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 1, 'max' => 1000],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'default' => ['size' => 60],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item' => '--size-square-height: {{SIZE}};',
                ],
                'description' => esc_html__(
                    'Sets the height of the square physics button.',
                    'courto-core'
                ),
            ]
        );
        
        $this->add_responsive_control(
            'items_square_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000],
                ],
                'default' => ['size' => 40],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'render_type' => 'template',
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item' => '--size-square-radius: {{SIZE}};',
                ],
                'description' => esc_html__(
                    'Controls the corner rounding of the square physics button.',
                    'courto-core'
                ),
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => '--wgl-items-gap: calc({{SIZE}}px * 0.5);',
                ],
            ]
        );
        $this->add_responsive_control(
            'items_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'items_border',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .wgl-physics_item__inner',
            ]
        );
        $this->add_control(
            'items_bg_gradient',
            [
                'label' => esc_html__('Enable Animated Gradient', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} div.wgl-physics_item__inner' => 'background-image: -webkit-linear-gradient(0deg, var(--bph-button-first), var(--bph-button-sec));',
                ],
            ]
        );
        $this->add_control(
            'items_heading',
            [
                'label' => esc_html__('Animation', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'items_increase_active',
            [
                'label' => esc_html__('Increase Active', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'items_transition',
            [
                'label' => esc_html__('Visibility Delay', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.01],
                ],
                'default' => ['size' => 0.45],
            ]
        );

        $this->start_controls_tabs( 'tabs_buttons' );
        $this->start_controls_tab(
            'tab_items_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'items_color_idle',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => 'background: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_sec_idle',
            [
                'label' => esc_html__('Gradient Background Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_bg_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => '--bph-button-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_border_border!' => ['', 'none'] ],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'items_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-physics_item__inner',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_items_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'items_color_hover',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.hover .wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.hover .wgl-physics_item__inner' => 'background-color: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_sec_hover',
            [
                'label' => esc_html__('Gradient Background Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_bg_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.hover .wgl-physics_item__inner' => '--bph-button-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_border_border!' => ['', 'none'] ],
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.hover .wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'items_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-physics_item.hover .wgl-physics_item__inner',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_items_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'items_color_active',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.active .wgl-physics_item__inner' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.active .wgl-physics_item__inner' => 'background-color: {{VALUE}}; --bph-button-first: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_bg_color_sec_active',
            [
                'label' => esc_html__('Gradient Background Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_bg_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.active .wgl-physics_item__inner' => '--bph-button-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'items_border_color_active',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_item.active .wgl-physics_item__inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'items_shadow_active',
                'selector' => '{{WRAPPER}} .wgl-physics_item.active .wgl-physics_item__inner',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> TITLE */

        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__( 'Title', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_text!' => '' ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 'clamp(32px, 8vw, 48px)', 'unit' => 'custom'],
                    ],
                    'line_height' => ['default' => ['size' => 1.25, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => -0.04, 'unit' => 'em']],
                ],
                'condition' => [ 'content_type!' => 'template' ],
                'selector' => '{{WRAPPER}} .wgl-physics_title',
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
                    '{{WRAPPER}} .wgl-physics_title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html( '‹h1›' ),
                    'h2' => esc_html( '‹h2›' ),
                    'h3' => esc_html( '‹h3›' ),
                    'h4' => esc_html( '‹h4›' ),
                    'h5' => esc_html( '‹h5›' ),
                    'h6' => esc_html( '‹h6›' ),
                    'div' => esc_html( '‹div›' ),
                ],
                'condition' => [ 'content_type!' => 'template' ],
                'default' => 'h4',
            ]
        );

        $this->add_control(
            'title_align',
            [
                'label' => esc_html__( 'Title Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'courto-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'courto-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [ 'content_type!' => 'template' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_pointer_events',
            [
                'label' => esc_html__( 'Pointer Events', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'auto' => esc_html__( 'Auto', 'courto-core' ),
                    'none' => esc_html__( 'None', 'courto-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'pointer-events: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'content_type!' => 'template' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_position',
            [
                'label' => esc_html__( 'Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'absolute' => esc_html__( 'Absolute', 'courto-core' ),
                    'fixed' => esc_html__( 'Fixed', 'courto-core' ),
                ],
                'default' => 'absolute',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'position: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 100, 'max' => 1200 ],
                ],
                'condition' => [ 'content_type!' => 'template' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'v_alignment',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'align-items: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'h_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'content_type!' => 'template' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'matter_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Normal', 'courto-core' ),
                    'multiply' => esc_html__( 'Multiply', 'courto-core' ),
                    'screen' => esc_html__( 'Screen', 'courto-core' ),
                    'overlay' => esc_html__( 'Overlay', 'courto-core' ),
                    'darken' => esc_html__( 'Darken', 'courto-core' ),
                    'lighten' => esc_html__( 'Lighten', 'courto-core' ),
                    'color-dodge' => esc_html__( 'Color Dodge', 'courto-core' ),
                    'saturation' => esc_html__( 'Saturation', 'courto-core' ),
                    'color' => esc_html__( 'Color', 'courto-core' ),
                    'luminosity' => esc_html__( 'Luminosity', 'courto-core' ),
                    'difference' => esc_html__( 'Difference', 'courto-core' ),
                    'exclusion' => esc_html__( 'Exclusion', 'courto-core' ),
                    'hue' => esc_html__( 'Hue', 'courto-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_title__wrapper' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_heading',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'condition' => [ 'content_type!' => 'template' ],
                'selector' => '{{WRAPPER}} .wgl-physics_subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'content_type!' => 'template' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-physics_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $this->add_render_attribute('button_physics', 'class', 'wgl-button_physics');
        $this->add_render_attribute( 'button_elements', [
            'class' => [
                'wgl-physics_elements',
            ],
            'data-increase-active' => esc_attr($_s['items_increase_active']),
            'data-delay' => esc_attr($_s['items_transition']['size'] ?? '0.45'),
            'data-restitution' => esc_attr($_s['items_restitution']['size'] ?? '0.5'),
            'data-friction' => esc_attr($_s['items_friction']['size'] ?? '0'),
            'data-density' => esc_attr($_s['items_density']['size'] ?? '0.01'),
        ] );

        // Render
        echo '<div ', $this->get_render_attribute_string('button_physics'), '>';
        echo '<div class="wgl-physics_matter">';
        echo '<div class="wgl-physics_canvas">';
        echo '</div>';
        echo '<div ', $this->get_render_attribute_string('button_elements'), '>';
        echo $this->get_canvas_item_html($_s);
        echo '</div>';
        echo $this->get_title_item_html($_s);
        echo '</div>';
        echo '</div>';
    }

    protected function get_title_item_html($_s)
    {
        if ( $_s[ 'content_type' ] === 'content' ) {
            $title_html = '';
            $title = $_s['title_text'];
            $subtitle = $_s['subtitle_text'];

            if ($title || $subtitle){
                $title_html .= '<div class="wgl-physics_title__wrapper">';
                    if ($subtitle) {
                        $title_html .= '<span class="wgl-physics_subtitle">'.
                        do_shortcode( $subtitle ).
                        '</span>';
                    }
                    if ($title) {
                        $title_html .= '<'. $_s['title_tag']. ' class="wgl-physics_title">'.
                        do_shortcode( $title ).
                        '</'. $_s['title_tag']. '>';
                    }
                $title_html .= '</div>';
                return $title_html;
            }
        } else if ( $_s[ 'content_type' ] === 'template' ) {
            $wgl_frontend = new Frontend;
            return '<div class="wgl-physics_title__wrapper">'.$wgl_frontend->get_builder_content_for_display( $_s[ 'content_templates' ] ).'</div>';
        }
        return false;
    }

    protected function get_canvas_item_html($_s)
    {
        $content = $link = '';

        foreach ($_s['items'] as $index => $item) {

            // Link
            $has_link = !empty($item['link']['url']);
            if ($has_link) {
                $link = $this->get_repeater_setting_key('link', 'items', $index);
                $this->add_link_attributes($link, $item['link']);
            }

            $this->add_render_attribute( 'button_' .$item['_id'], [
                'class' => [
                    'shape--' . ($item['button_shape'] ?? 'circle'), 
                    'wgl-physics_item elementor-repeater-item-' . $item['_id'],
                ],
                'data-item-id' => esc_attr($item['_id']),
                'data-shape' => esc_attr($item['button_shape'] ?? 'circle'),
            ] );

            if('yes' === $item['hide_on_mobile']){
                $this->add_render_attribute( 'button_' .$item['_id'], [
                    'class' => [
                        'hide_mobile'
                    ],
                    'data-resolution' => esc_attr($item['hide_mobile_resolution']),
                ]);
            }

            ob_start();

            echo '<div '.$this->get_render_attribute_string('button_' .$item['_id']).'><div class="wgl-physics_item__inner">';

            echo $has_link ? '<a class="wgl-button_physics_link" ' . $this->get_render_attribute_string($link) . '></a>' : '';

            if (!empty($item['button_title'])) {
                echo '<span class="wgl-button_physics_title">' . $item['button_title'] . '</span>';
            }

            echo '</div></div>';

            $content .= ob_get_clean();
        }

        return $content;
    }

    public function wpml_support_module()
    {
        add_filter('wpml_elementor_widgets_to_translate', [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter($widgets)
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this,
            $widgets
        );
    }
}
