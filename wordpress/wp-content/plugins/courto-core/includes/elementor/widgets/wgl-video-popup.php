<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-video-popup.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Embed,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Background
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * Video Popup Widget
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Video_Popup extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-video-popup';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Video Popup', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-video-popup';
    }

    public function get_keywords()
    {
        return [ 'video', 'player', 'embed', 'youtube', 'vimeo' ];
    }

    public function get_categories()
    {
        return [ 'wgl-modules' ];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'content_general',
            [ 'label' => esc_html__( 'General', 'courto-core' ) ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Video Link', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'description' => esc_html__( 'Link from youtube or vimeo.', 'courto-core' ),
                'placeholder' => esc_attr__( 'ex: https://www.youtube.com/watch?v=', 'courto-core' ),
                'default' => 'https://www.youtube.com/watch?v=TKnufs85hXk',
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_html__( 'Video Popup Title', 'courto-core' ),
                'default' => esc_html__( 'Watch Us', 'courto-core' ),
            ]
        );

        $this->add_control(
            'title_position',
            [
                'label' => esc_html__( 'Title Position', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [ 'title_text!' => '' ],
                'options' => [
                    'top' => [
                        'title' => esc_html__( 'Top', 'courto-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'courto-core' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'bot' => [
                        'title' => esc_html__( 'Bottom', 'courto-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'left' => [
                        'title' => esc_html__( 'Left', 'courto-core' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->add_responsive_control(
            'button_pos',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
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
                    'inline' => [
                        'title' => esc_html__( 'Inline', 'courto-core' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'button_align%s-',
            ]
        );

        $this->end_controls_section();

        /** CONTENT -> ANIMATION */

        $this->start_controls_section(
            'content_animation',
            [ 'label' => esc_html__( 'Animation', 'courto-core' ) ]
        );

        $this->add_control(
            'animation_style',
            [
                'label' => esc_html__( 'Animation Style', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'scale' => esc_html__( 'Scale', 'courto-core' ),
                    'ring_static' => esc_html__( 'Static Ring', 'courto-core' ),
                    'ring_pulse' => esc_html__( 'Pulsing Ring', 'courto-core' ),
                    'circles' => esc_html__( 'Divergent Rings', 'courto-core' ),
                    'disable' => esc_html__( 'No animation', 'courto-core' ),
                ],
                'default' => 'scale',
            ]
        );

        $this->start_controls_tabs(
            'animation_scale',
            [
                'condition' => [ 'animation_style' => 'scale' ],
            ]
        );

        $this->start_controls_tab(
            'scale_animation_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );

        $this->add_control(
            'scale_animation_transform_idle',
            [
                'label' => esc_html__( 'Scale Value', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'x' ],
                'range' => [
                    'x' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.01 ],
                ],
                'default' => [ 'unit' => 'x' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'transform: scale( {{SIZE}} );',
                ],
            ]
        );

        $this->add_control(
            'scale_animation_transition_duration',
            [
                'label' => esc_html__( 'Animation Duratiom', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 's', 'ms' ],
                'range' => [
                    's' => [ 'min' => 0.1, 'max' => 4, 'step' => 0.1 ],
                    'ms' => [ 'min' => 100, 'max' => 4000 ],
                ],
                'default' => [ 'unit' => 's' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'transition: 0.4s, transform {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'scale_animation_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );

        $this->add_responsive_control(
            'scale_animation_transform_hover',
            [
                'label' => esc_html__( 'Scale Value', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'animation_style' => 'scale' ],
                'size_units' => [ 'x' ],
                'range' => [
                    'x' => [ 'min' => 0.5, 'max' => 2, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1.1, 'unit' => 'x' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link::before,
                     {{WRAPPER}} .videobox_content:focus .videobox_link::before' => 'transform: scale( {{SIZE}} );',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'anim_color',
            [
                'label' => esc_html__( 'Animation Element Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'animation_style' => [ 'ring_static', 'ring_pulse', 'circles' ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_animation' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .animation_ring_pulse .videobox_animation' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'always_run_animation',
            [
                'label' => esc_html__( 'Run Animation Until Hover', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'animation_style' => [ 'ring_pulse', 'circles' ]
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> CONTAINER */

        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__( 'Container', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .videobox_content',
            ]
        );

        $this->add_control(
            'container_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .videobox_content',
            ]
        );

        $this->start_controls_tabs( 'container' );

        $this->start_controls_tab(
            'container_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );

        $this->add_control(
            'container_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'container_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_bg_idle',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .videobox_content',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'container_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'container_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'container_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_bg_hover',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .videobox_content:hover',
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
                'condition' => [ 'title_text!' => '' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => ['default' => [ 'size' => 14, 'unit' => 'px' ]],
                    'font_weight' => ['default' => 500],
                    'letter_spacing' => ['default' => [ 'size' => 0 ]],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .title',
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
                    'span' => esc_html( 'span' ),
                    'div' => esc_html( '‹div›' ),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'title_font',
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
                    '{{WRAPPER}} .title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> BUTTON */

        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__( 'Button', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'triangle_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'range' => [
                    'px' => [ 'max' => 200 ],
                ],
                'default' => [ 'size' => 16 ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'triangle_rounded',
            [
                'label' => esc_html__( 'Icon Rounded Corners', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'triangle_stroke_width',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50, 'step' => 0.1 ],
                    'em' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_icon' => 'stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_diameter',
            [
                'label' => esc_html__( 'Button Diameter', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 200 ],
                ],
                'default' => [ 'size' => 78 ],
                'mobile_default' => [ 'size' => 50 ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '80',
                    'right' => '80',
                    'bottom' => '80',
                    'left' => '80',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .videobox_link::before',
            ]
        );

        $this->start_controls_tabs( 'button' );

        $this->start_controls_tab(
            'button_idle',
            [ 'label' => esc_html__( 'Idle' , 'courto-core' ) ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_stroke_color_idle',
            [
                'label' => esc_html__( 'Icon Stroke Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'triangle_rounded' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_color_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_blur_idle',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_link::before' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_idle',
                'selector' => '{{WRAPPER}} .videobox_link::before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_stroke_color_hover',
            [
                'label' => esc_html__( 'Icon Stroke Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'triangle_rounded' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_blur_hover',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .videobox_content:hover .videobox_link::before' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .videobox_content:hover .videobox_link::before',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $animation_style = $this->get_settings_for_display( 'animation_style' );
        $title_position = $this->get_settings_for_display( 'title_position' );

        $this->add_render_attribute( 'video-wrap', 'class', [
            'wgl-video-popup',
            $animation_style ? 'animation-' . $animation_style : '',
            $title_position ? 'title_pos-' . $title_position : '',
            $this->get_settings_for_display( 'always_run_animation' ) ? 'idle-animation' : '',
        ] );

        $animated_element = '';
        switch ( $animation_style ) {
            case 'circles':
                $animated_element .= '<div class="videobox_animation circle_1"></div>';
                $animated_element .= '<div class="videobox_animation circle_2"></div>';
                $animated_element .= '<div class="videobox_animation circle_3"></div>';
                break;
            case 'ring_pulse':
            case 'ring_static':
                $animated_element .= '<div class="videobox_animation"></div>';
                break;
        }

        $lightbox_options = [
            'type' => 'video',
            'videoType' => 'vimeo',
            'url' => Embed::get_embed_url( $this->get_settings_for_display( 'link' ), ['loop' => 0] ),
            'modalOptions' => [
                'id' => 'elementor-lightbox-' . $this->get_id(),
            ],
        ];

        $this->add_render_attribute( 'lightbox', [
            'class' => 'videobox_content',
            'data-elementor-open-lightbox' => 'yes',
            'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
        ] );

        // Render
        echo '<div ', $this->get_render_attribute_string( 'video-wrap' ), '>';
        echo '<div ', $this->get_render_attribute_string( 'lightbox' ) , '>';

        if ( $title = $this->get_settings_for_display( 'title_text' ) ) {
            echo '<', $this->get_settings_for_display( 'title_tag' ), ' class="title">',
            esc_html( $title ),
            '</', $this->get_settings_for_display( 'title_tag' ), '>';
        }

        echo '<div class="videobox_link">',
        $this->get_icon_svg(),
        $animated_element,
        '</div>';

        echo '</div>'; // videobox_content
        echo '</div>';
    }

    protected function get_icon_svg()
    {
        $class = 'videobox_icon';

        if ( $this->get_settings_for_display( 'triangle_rounded' ) ) {
            return '<svg class="' . $class . '" viewBox="-20 -20 272 272"><path d="M203,99L49,2.3c-4.5-2.7-10.2-2.2-14.5-2.2 c-17.1,0-17,13-17,16.6v199c0,2.8-0.07,16.6,17,16.6c4.3,0,10,0.4,14.5-2.2 l154-97c12.7-7.5,10.5-16.5,10.5-16.5S216,107,204,100z"/></svg>';
        }

        return '<svg class="' . $class . '" viewBox="0 0 10 10"><polygon points="1,0 1,10 9,5"/></svg>';
    }

    public function wpml_support_module()
    {
        add_filter( 'wpml_elementor_widgets_to_translate',  [ $this, 'wpml_widgets_to_translate_filter' ] );
    }

    public function wpml_widgets_to_translate_filter( $widgets )
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}
