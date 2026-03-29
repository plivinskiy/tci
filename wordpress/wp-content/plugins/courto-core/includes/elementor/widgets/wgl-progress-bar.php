<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-progress-bar.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Progress_Bar extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-progress-bar';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Progress Bar', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-progress-bar';
    }

    public function get_keywords()
    {
        return [ 'progress', 'bar' ];
    }

    public function get_categories()
    {
        return [ 'wgl-modules' ];
    }

    public function get_script_depends()
    {
        return [
            'jquery-appear'
        ];
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'content_general',
            [ 'label' => esc_html__( 'General', 'courto-core' ) ]
        );

        $this->add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_attr__( 'ex: Progress Bar Title', 'courto-core' ),
                'default' => esc_html__( 'Progress Bar Title', 'courto-core' ),
            ]
        );

        $this->add_control(
            'value_progress',
            [
                'label' => esc_html__( 'Progress Value', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 0,
                'placeholder' => 60,
                'default' => 60,
            ]
        );

        $this->add_control(
            'value_maximum',
            [
                'label' => esc_html__( 'Maximum Value', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 1,
                'placeholder' => 100,
                'default' => 100,
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

        $this->add_control(
            'units_text',
            [
                'label' => esc_html__( 'Units', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_attr__( 'ex: %, px, points, etc.', 'courto-core' ),
                'default' => esc_html__( '%', 'courto-core' ),
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-progress-bar.init .content__value' => 'transition: {{VALUE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'value_position',
            [
                'label' => esc_html__( 'Value Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    'fixed' => esc_html__( 'Top Fixed', 'courto-core' ),
                    'dynamic' => esc_html__( 'Top Dynamic', 'courto-core' ),
                    'dynamic_middle' => esc_html__( 'Middle Dynamic', 'courto-core' ),
                    'aside' => esc_html__( 'Aside', 'courto-core' ),
                ],
                'default' => 'fixed',
            ]
        );

        $this->add_control(
            'units_position',
            [
                'label' => esc_html__( 'Units Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '-1' => esc_html__( 'Before', 'courto-core' ),
                    '1' => esc_html__( 'After', 'courto-core' ),
                ],
                'condition' => [ 'units_text!' => '' ],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .placeholder__unit,
                     {{WRAPPER}} .value__unit' => 'order: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'condition' => [ 'value_position' => 'fixed' ],
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
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'courto-core' ),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .progress__content' => 'justify-content: {{VALUE}}',
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
                    '{{WRAPPER}} .wgl-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-progress-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_bg',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );
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
                'selector' => '{{WRAPPER}} .progress__content, {{WRAPPER}} .progress__value',
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
                'default' => 'div',
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
                    '{{WRAPPER}} .progress__content, {{WRAPPER}} .progress__value' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '12',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .content__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_bg',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> VALUE */

        $this->start_controls_section(
            'style_value',
            [
                'label' => esc_html__( 'Value', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .content__value',
            ]
        );

        $this->add_responsive_control(
            'value_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '-2',
                    'left' => '10',
                    'right' => '0',
                    'bottom' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .content__value,
                     {{WRAPPER}} .value__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_width',
            [
                'label' => esc_html__('Value Wrapper Width (px)', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'condition' => [ 'value_position' => 'dynamic_middle' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 37, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'max-width: calc(100% - {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .wgl-progress-bar.init .content__value' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'value_bg',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> UNITS */

        $this->start_controls_section(
            'style_units',
            [
                'label' => esc_html__( 'Units', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'units_text!' => '' ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'units_typography',
                'selector' => '{{WRAPPER}} .value__unit',
            ]
        );

        $this->add_responsive_control(
            'units_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'horizontal',
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .value__unit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'units_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .value__unit' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> PROGRESS BAR */

        $this->start_controls_section(
            'style_bar',
            [
                'label' => esc_html__( 'Bar', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_empty_height',
            [
                'label' => esc_html__( 'Empty Bar Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 30 ],
                ],
                'default' => [ 'size' => 1 ],
                'condition' => [ 'value_position!' => 'dynamic_middle' ],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bar_empty_bg',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => [ 'classic', 'gradient' ],
                'dynamic' => ['active' => true],
                'condition' => [ 'value_position!' => 'dynamic_middle' ],
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                    ],
                    'image' => [ 'type' => Controls_Manager::HIDDEN ],

                ],
                'selector' => '{{WRAPPER}} .bar__empty',
            ]
        );

        $this->add_control(
            'bar_filled_height',
            [
                'label' => esc_html__( 'Filled Bar Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 30 ],
                ],
                'default' => [ 'size' => 6 ],
                'selectors' => [
                    '{{WRAPPER}} .bar__filled,
                     {{WRAPPER}} .layout-dynamic_middle .progress__bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_filled_offset',
            [
                'label' => esc_html__( 'Filled Bar Vertical Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => -15, 'max' => 15, 'step' => 0.5 ],
                ],
                'default' => [ 'size' => -3 ],
                'selectors' => [
                    '{{WRAPPER}} .bar__filled' => 'transform: translateY({{SIZE}}{{UNIT}}); top: 0;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bar_filled_bg',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => [ 'classic', 'gradient' ],
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} .bar__filled',
            ]
        );

        $this->add_responsive_control(
            'bar_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'allowed_dimensions' => 'vertical',
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '20',
                    'right' => '',
                    'bottom' => '11',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bar_border',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'selectors' => [ '{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .bar__empty',
            ]
        );

        $this->add_control(
            'bar_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .bar__empty,
                     {{WRAPPER}} .bar__filled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_shadow',
                'selector' => '{{WRAPPER}} .bar__empty',
            ]
        );

        $this->add_control(
            'bar_transition_duration',
            [
                'label' => esc_html__( 'Animation Duratiom', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'label_block' => true,
                'size_units' => [ 's', 'ms' ],
                'range' => [
                    's' => [ 'min' => 0.1, 'max' => 4, 'step' => 0.1 ],
                    'ms' => [ 'min' => 100, 'max' => 4000 ],
                ],
                'default' => [ 'unit' => 's' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-progress-bar' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $pos = $this->get_settings_for_display( 'value_position' );
        $this->add_render_attribute( 'wrapper', 'class', [
            'wgl-progress-bar',
            'layout-' . $pos
        ] );

        echo '<div ', $this->get_render_attribute_string( 'wrapper' ), '>';

        if ( 'aside' === $pos ) {
            $this->render_aside_layout();
        } else if ( 'dynamic_middle' === $pos ) {
            $this->render_dynamic_middle_layout();
        } else {
            $this->render_default_layout();
        }

        echo '</div>';
    }

    protected function render_default_layout()
    {

        $title_tag = $this->get_settings_for_display( 'title_tag' );

        echo '<'. esc_attr( $title_tag ). ' class="progress__content">'.
            $this->render_title().
            $this->render_default_value().
        '</'. esc_attr( $title_tag ). '>';

        $this->render_bar();
    }

    protected function render_dynamic_middle_layout()
    {

        $title_tag = $this->get_settings_for_display( 'title_tag' );

        echo '<'. esc_attr( $title_tag ). ' class="progress__content">'.
            $this->render_title().
        '</'. esc_attr( $title_tag ). '>';

        $this->render_bar();
    }

    protected function render_aside_layout()
    {
        $title_tag = $this->get_settings_for_display( 'title_tag' );

        echo '<div class="aside__wrapper">';

            echo '<'. esc_attr( $title_tag ). ' class="progress__content">'.
                $this->render_title().
            '</'. esc_attr( $title_tag ). '>';

            $this->render_bar();

        echo '</div>';

        $this->render_aside_value();
    }

    protected function render_bar()
    {
        $_s = $this->get_settings_for_display();
        $value_progress = 0 === $_s['value_progress'] ? 0 : ( $_s['value_progress'] ?: 50 );
        $value_maximum = $_s['value_maximum'] ?: 100;
        $this->add_render_attribute( 'bar-filled', [
            'class' => 'bar__filled',
            'data-value' => esc_attr( $value_progress ),
            'data-max-value' => esc_attr( $value_maximum ),
            'data-sep' => $_s['thousand_sep'],
            'data-speed' => $_s['speed'] ?? '1200',
        ] );

        ?><div class="progress__bar">
            <div class="bar__empty"></div>
            <div <?php echo $this->get_render_attribute_string( 'bar-filled' ); ?>><?php

                if ('dynamic_middle' === $_s['value_position']){
                    echo '<'. esc_attr( $_s['title_tag'] ). ' class="progress__value">'.
                        $this->render_default_value().
                        '</'. esc_attr( $_s['title_tag'] ). '>';
                }

                ?></div>
        </div><?php
    }

    protected function render_title()
    {
        $title_text = $this->get_settings_for_display( 'title_text' );

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
            'p' => ['class' => true, 'style' => true]
        ];

        if ( empty( $title_text ) ) {
            // Bailout.
            return;
        }

        return '<span class="content__label">'.
            wp_kses($title_text, $kses_allowed_html).
        '</span>';
    }

    protected function render_default_value()
    {
        $_s = $this->get_settings_for_display();

        return '<span class="content__value">'.
            '<span class="value__digit">0</span>'.
            ( $_s['units_text'] ? '<span class="value__unit">' . esc_html( $_s['units_text'] ) . '</span>' : '' ).
        '</span>';
    }

    protected function render_aside_value()
    {
        $_s = $this->get_settings_for_display();
        $units = $_s['units_text'];
        $title_tag = $_s['title_tag'];
        $value_progress = 0 === $_s['value_progress'] ? 0 : ( $_s['value_progress'] ?: 50 );

        echo '<'. esc_attr( $title_tag ). ' class="progress__value"><span class="content__value">',
            '<span class="placeholder__digit">', esc_html( $value_progress ), '</span>',
            ( $units ? '<span class="placeholder__unit">' . esc_html( $units ) . '</span>' : '' ),

            '<span class="value__wrapper">',
                '<span class="value__digit">0</span>',
                ( $units ? '<span class="value__unit">' . esc_html( $units ) . '</span>' : '' ),
            '</span>',
        '</span></'. esc_attr( $title_tag ). '>';
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
