<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-double-headings.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
    Icons_Manager,
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Border,
    Group_Control_Box_Shadow};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Cursor
};

class WGL_Double_Heading extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-double-heading';
    }

    public function get_title()
    {
        return esc_html__('WGL Double Heading', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-double-heading';
    }

    public function get_script_depends() {
        return ['jquery-appear'];
    }

    public function get_keywords()
    {
        return [ 'double', 'dblh', 'heading', 'title', 'text' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_attr__('ex: About Our Company', 'courto-core'),
                'default' => esc_html__('Double Heading Subtitle', 'courto-core'),
            ]
        );
        $this->add_control(
            'divider',
            [
                'label' => esc_html__('Add Divider', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'render_type' => 'template',
                'condition' => ['subtitle!' => ''],
                'default' => '',
            ]
        );
        $this->add_control(
            'divider_position',
            [
                'label' => esc_html__( 'Divider Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'subtitle!' => '',
                    'divider' => 'yes'
                ],
                'options' => [
                    'left' => esc_html__( 'Left Side', 'courto-core' ),
                    'right' => esc_html__( 'Right Side', 'courto-core' ),
                    'both' => esc_html__( 'Left and Right Side', 'courto-core' ),
                ],
                'default' => 'left',
            ]
        );
        $this->add_control(
            'divider_icon',
            [
                'label' => esc_html__('Divider Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'subtitle!' => '',
                    'divider' => 'yes'
                ],
                'description' => esc_html__('Select icon from available libraries.', 'courto-core'),
                'label_block' => true,
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-icons',
                ],
            ]
        );
        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_part-1',
            [
                'label' => esc_html__('1st Part', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 3,
                'placeholder' => esc_attr__('1st part', 'courto-core'),
                'default' => esc_html__('Double Heading Title', 'courto-core'),
            ]
        );

        $this->add_control(
            'title_part-2',
            [
                'label' => esc_html__('2nd Part', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 3,
                'placeholder' => esc_attr__('2nd part', 'courto-core'),
            ]
        );

        $this->add_control(
            'title_part-3',
            [
                'label' => esc_html__('3rd Part', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 3,
                'placeholder' => esc_attr__('3rd part', 'courto-core'),
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Description Text', 'courto-core'),
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
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
                ],
                'prefix_class' => 'a%s',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Title Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('https://your-link.com', 'courto-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * GENERAL -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );


        /**
         * STYLES -> TITLE
         */

        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_all',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [
                        'default' => [ 'size' => 48, 'unit' => 'px' ],
                        'tablet_default' => [ 'size' => 36, 'unit' => 'px' ],
                        'mobile_default' => [ 'size' => 28, 'unit' => 'px' ]
                    ],
                    'line_height' => ['default' => ['size' => 1.25, 'unit' => 'em']],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .dblh__title-wrapper',
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
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_display',
            [
                'label' => esc_html__( 'Display', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline' => esc_html__( 'Inline', 'courto-core' ),
                    'block' => esc_html__( 'Block', 'courto-core' ),
                    'inline-block' => esc_html__( 'Inline Block', 'courto-core' ),
                ],
                'default' => 'inline',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_1st_heading',
            [
                'label' => esc_html__('1st Part', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-1!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_first',
                'condition' => ['title_part-1!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-1',
            ]
        );

        $this->add_control(
            'title_first_font',
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
                    '{{WRAPPER}} .dblh__title-1' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_first_padding',
            [
                'label' => esc_html__('Padding for 1st Part', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => ['title_part-1!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_first_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'condition' => ['title_part-1!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_first_gradient',
            [
                'label' => esc_html__('Use Gradient for First Part', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'condition' => ['title_part-1!' => ''],
                'prefix_class' => 'title_gradient-',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'tabs_title_first', [
                'condition' => [
                    'title_part-1!' => '',
                ],
            ]
        );
        $this->start_controls_tab(
            'tabs_title_first_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_1st_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-1!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_1st_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-1!' => '',
                    'title_first_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_title_first_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_1st_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-1!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover  .dblh__title-1' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_1st_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-1!' => '',
                    'title_first_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover .dblh__title-1' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'title_2nd_heading',
            [
                'label' => esc_html__('2nd Part', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-2!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_second',
                'condition' => ['title_part-2!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-2',
            ]
        );

        $this->add_control(
            'title_second_font',
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
                    '{{WRAPPER}} .dblh__title-2' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
                'condition' => ['title_part-2!' => ''],
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'title_second_padding',
            [
                'label' => esc_html__('Padding for 2nd Part', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => ['title_part-2!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_second_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'condition' => ['title_part-2!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_second_gradient',
            [
                'label' => esc_html__('Use Gradient for Second Part', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'condition' => ['title_part-2!' => ''],
                'prefix_class' => 'title_gradient-',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'tabs_title_second', [
                'condition' => [
                    'title_part-2!' => '',
                ],
            ]
        );
        $this->start_controls_tab(
            'tabs_title_second_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_2nd_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-2!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_2nd_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-2!' => '',
                    'title_second_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_title_second_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_2nd_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-2!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover  .dblh__title-2' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_2nd_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-2!' => '',
                    'title_second_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover .dblh__title-2' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'title_3rd_heading',
            [
                'label' => esc_html__('3rd Part', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-3!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_third',
                'condition' => ['title_part-3!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-3',
            ]
        );

        $this->add_control(
            'title_third_font',
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
                    '{{WRAPPER}} .dblh__title-3' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
                'condition' => ['title_part-3!' => ''],
            ]
        );

        $this->add_responsive_control(
            'title_third_padding',
            [
                'label' => esc_html__('Padding for 3rd Part', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => ['title_part-3!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_third_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'condition' => ['title_part-3!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_third_gradient',
            [
                'label' => esc_html__('Use Gradient for Third Part', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'condition' => ['title_part-3!' => ''],
                'prefix_class' => 'title_gradient-',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->start_controls_tabs(
            'tabs_title_third', [
                'condition' => [
                    'title_part-3!' => '',
                ],
            ]
        );
        $this->start_controls_tab(
            'tabs_title_third_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_3rd_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-3!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_3rd_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-3!' => '',
                    'title_third_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_title_third_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_3rd_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_part-3!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover  .dblh__title-3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_3rd_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'title_part-3!' => '',
                    'title_third_stroke_size[size]!' => [0, ''],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper:hover .dblh__title-3' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLES -> TITLE GRADIENT
         */

        $this->start_controls_section(
            'style_title_gradient',
            [
                'label' => esc_html__('Gradient for the Entire Widget', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_gradient',
            [
                'label' => esc_html__('Use Gradient', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'prefix_class' => 'title_gradient-',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );
        $this->add_control(
            'title_gradient_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--gradient-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_gradient_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--gradient-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'title_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'title_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'title_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'radial',
                'render_type' => 'ui',
                'condition' => [ 'title_gradient!' => '' ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'title_gradient!' => '',
                    'title_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [
                    'title_gradient!' => '',
                    'title_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_gradient!' => '',
                    'title_gradient_type' => 'radial',
                    'title_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_gradient!' => '',
                    'title_gradient_type' => 'radial',
                    'title_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLES -> TITLE FIRST GRADIENT
         */

        $this->start_controls_section(
            'style_title_first_gradient',
            [
                'label' => esc_html__('Gradient for the First Part', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_first_gradient!' => '' ],
            ]
        );
        $this->add_control(
            'title_first_gradient_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--gradient-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_first_gradient_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--gradient-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'title_first_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'radial',
                'render_type' => 'ui',
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [ 'title_first_gradient_type' => 'linear' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [ 'title_first_gradient_type' => 'radial' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_first_gradient_type' => 'radial',
                    'title_first_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_first_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_first_gradient_type' => 'radial',
                    'title_first_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1 > span' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLES -> TITLE SECOND GRADIENT
         */

        $this->start_controls_section(
            'style_title_second_gradient',
            [
                'label' => esc_html__('Gradient for the Second Part', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_second_gradient!' => '' ],
            ]
        );
        $this->add_control(
            'title_second_gradient_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--gradient-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_second_gradient_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--gradient-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'title_second_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'radial',
                'render_type' => 'ui',
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [ 'title_second_gradient_type' => 'linear' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [ 'title_second_gradient_type' => 'radial' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_second_gradient_type' => 'radial',
                    'title_second_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_second_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_second_gradient_type' => 'radial',
                    'title_second_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2 > span' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLES -> TITLE THIRD GRADIENT
         */

        $this->start_controls_section(
            'style_title_third_gradient',
            [
                'label' => esc_html__('Gradient for the Third Part', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_third_gradient!' => '' ],
            ]
        );
        $this->add_control(
            'title_third_gradient_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--gradient-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_third_gradient_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--gradient-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'title_third_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'radial',
                'render_type' => 'ui',
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [ 'title_third_gradient_type' => 'linear' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [ 'title_third_gradient_type' => 'radial' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_third_gradient_type' => 'radial',
                    'title_third_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_third_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'title_third_gradient_type' => 'radial',
                    'title_third_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3 > span' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['content!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .dblh__content',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '17',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .dblh__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_p_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "p" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__content p:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_li_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "li" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__content li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('content_color_tab');
        $this->start_controls_tab(
            'custom_content_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_content_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .dblh__content' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLES -> SUBTITLE
         */

        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [
                        'default' => [ 'size' => 14, 'unit' => 'px' ],
                    ],
                    'font_weight' => [ 'default' => 600 ],
                    'line_height' => ['default' => ['size' => 1.25, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => -0.02, 'unit' => 'em']],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .dblh__subtitle',
            ]
        );

        $this->add_control(
            'sub_title_tag',
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
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_control(
            'sub_title_font',
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
                    '{{WRAPPER}} .dblh__subtitle' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom'=> '25',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '8',
                    'right' => '15',
                    'bottom' => '8',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'subtitle_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
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
                'selector' => '{{WRAPPER}} .dblh__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'subtitle_shadow',
                'selector' => '{{WRAPPER}} .dblh__subtitle',
            ]
        );

        $ltr = is_rtl() ? 'left' : 'right';
        $rtl = is_rtl() ? 'right' : 'left';
        $this->add_responsive_control(
            'divider_margin',
            [
                'label' => esc_html__('Divider Space', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 200, 'step' => 1]],
                'condition' => ['divider!' => ''],
                'default' => ['size' => 10, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle .divider-icon:first-child' => 'margin-'.$ltr.': {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dblh__subtitle .divider-icon:last-child' => 'margin-'.$rtl.': {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'divider_color',
            [
                'label' => esc_html__('Divider Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'divider!' => '',
                    'divider_type' => 'circle',
                ],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle .divider-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_size',
            [
                'label' => esc_html__('Divider Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'custom'],
                'condition' => [
                    'divider!' => '',
                    'divider_type' => 'circle',
                ],
                'default' => [ 'size' => 560, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle .divider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_v_pos',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom'],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => [ 'size' => 1, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle .divider-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        if (isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });
        }

        if (!!$_s['divider'] && isset($_s['divider_icon']['value']) && !!$_s['divider_icon']['value']) {
            $migrated = isset( $atts['__fa4_migrated']['read_more_icon_fontawesome'] );
            $is_new = Icons_Manager::is_migration_allowed();
            if ( $is_new || $migrated ) {
                ob_start();
                Icons_Manager::render_icon($_s['divider_icon'], ['aria-hidden' => 'true']);
                $divider_icon = '<span class="divider-icon">' . ob_get_clean() . '</span>';
            }
        }

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);

        echo '<div class="wgl-double-heading' . ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' ) . '"' . $cursor_data . '>';

        if ($_s['subtitle']) {
            $pos = $_s['divider_position'] ?? '';
            $icon = $divider_icon ?? '';
            echo '<', $_s['sub_title_tag'], ' class="dblh__subtitle">',
                in_array( $pos, ['left', 'both'], true ) ? $icon : '',
                '<span>'.$_s['subtitle'].'</span>',
                in_array( $pos, ['right', 'both'], true ) ? $icon : '',
            '</', $_s['sub_title_tag'], '>';
        }

        if (
            $_s['title_part-1']
            || $_s['title_part-2']
            || $_s['title_part-3']
        ) {

            if (!empty($_s['link']['url'])) {
                $this->add_render_attribute('link', 'class', 'dblh__link');
                $this->add_link_attributes('link', $_s['link']);

                echo '<a ', $this->get_render_attribute_string('link'), '>';
            }

            echo '<', $_s['title_tag'], ' class="dblh__title-wrapper">',
                $_s['title_part-1'] ? '<span class="dblh__title dblh__title-1"><span>' . $_s['title_part-1'] . '</span></span>' : '',
                $_s['title_part-2'] ? '<span class="dblh__title dblh__title-2"><span>' . $_s['title_part-2'] . '</span></span>' : '',
                $_s['title_part-3'] ? '<span class="dblh__title dblh__title-3"><span>' . $_s['title_part-3'] . '</span></span>' : '',
            '</', $_s['title_tag'], '>';

            if (!empty($_s['link']['url'])) {
                echo '</a>';
            }
        }

        if ($_s['content']) {
            echo '<div class="dblh__content">',
                $_s['content'],
            '</div>';
        }

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
