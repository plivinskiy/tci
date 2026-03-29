<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-pie-chart.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
    Group_Control_Box_Shadow,
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Border};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Pie_Chart extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-pie-chart';
    }

    public function get_title()
    {
        return esc_html__('WGL Pie Chart', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-pie-chart';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_keywords() {
        return ['pie', 'chart', 'animate'];
    }

    public function get_script_depends()
    {
        return [
            'jquery-easypiechart',
            'jquery-appear'
        ];
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
            'value',
            [
                'label' => esc_html__('Value', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'range' => [
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 75, 'unit' => '%'],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'devices' => ['desktop', 'tablet', 'mobile'],
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_def.png',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_left.png',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_right.png',
                    ],
                ],

                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
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
                'default' => 'center',
                'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'sub_title',
            [
                'label' => esc_html__('Sub Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Pie Chart Title', 'courto-core'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'style_chart',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'chart_diameter',
            [
                'label' => esc_html__('Chart Diameter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'frontend_available' => true,
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 50, 'max' => 450],
                ],
                'default' => ['size' => 200],
                'selectors' => [
                    '{{WRAPPER}} .chart' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'track_color',
            [
                'label' => esc_html__('Track Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.2),
                'selectors' => [
                    '{{WRAPPER}} .chart::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'track_width',
            [
                'label' => esc_html__('Track Width', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'min' => 0,
                'step' => 1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .chart' => '--track-width: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => esc_html__('Bar Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
            ]
        );
        $this->add_control(
            'line_width',
            [
                'label' => esc_html__('Bar Width', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'min' => 0,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .chart' => '--line-width: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'track_offset',
            [
                'label' => esc_html__('Track Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .chart' => '--track-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bar_bg_color',
                'label' => esc_html__('Background Color', 'courto-core'),
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(),
                    ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .chart',
            ]
        );

        $this->add_responsive_control(
            'bar_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .chart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bar_border',
	            'selector' => '{{WRAPPER}} .chart',
            ]
        );

        $this->add_control(
            'bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit'  => '%',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_shadow',
                'selector' => '{{WRAPPER}} .chart',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> VALUE
         */

        $this->start_controls_section(
            'style_value',
            [
                'label' => esc_html__('Value', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .chart__percent',
            ]
        );

        $this->add_responsive_control(
            'value_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '2',
                    'right' => '10',
                    'bottom' => '2',
                    'left' => '10',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart__percent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .chart__percent' => '--value-margin: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'value_border',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .chart__percent',
            ]
        );

        $this->start_controls_tabs(
            'tabs_value'
        );
        $this->start_controls_tab(
            'tabs_value_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'value_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__percent' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'value_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => array(
                    '{{WRAPPER}} .chart__percent' => 'background-color: {{VALUE}};',
                ),
            ]
        );
        $this->add_control(
            'value_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'value_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .chart__percent' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'value_shadow_idle',
                'selector' => '{{WRAPPER}} .chart__percent',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_value_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'value_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pie_chart:hover .chart__percent' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'value_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => array(
                    '{{WRAPPER}} .wgl-pie_chart:hover .chart__percent' => 'background-color: {{VALUE}};',
                ),
            ]
        );
        $this->add_control(
            'value_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'value_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pie_chart:hover .chart__percent' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'value_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-pie_chart:hover .chart__percent',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> SUB TITLE
         */

        $this->start_controls_section(
            'style_sub_title',
            [
                'label' => esc_html__('Sub Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .chart__sub_title',
            ]
        );

        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .chart__sub_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__sub_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> TITLE
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
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .chart__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .chart__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> DESCRIPTION
         */

        $this->start_controls_section(
            'style_description',
            [
                'label' => esc_html__('Description', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .chart__description',
            ]
        );
        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .chart__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    public function render()
    {
        extract($this->get_settings_for_display());

        $diameter = (int) esc_attr($chart_diameter['size']);
        $wrapper_classes = $layout ? ' wgl-layout-' . $layout : '';

        $this->add_render_attribute('chart', [
            'class' => 'chart',
            'style' => 'height: ' . $diameter . 'px;',
            'data-percent' => (int) esc_attr($value['size']),
            'data-track-color' => 'transparent',
            'data-bar-color' => esc_attr($bar_color),
            'data-line-width' => isset($line_width) ? (int) esc_attr($line_width) : 1,
            'data-size' => $diameter,
        ]);

        echo '<div class="wgl-pie_chart">';
        echo '<div class="chart__wrapper', esc_attr($wrapper_classes), '">';

            echo '<div ', $this->get_render_attribute_string('chart'), '>',
                '<span class="chart__percent">0</span>',
            '</div>';

            echo '<div class="chart__content content_wrapper">';

            if ($sub_title) {
                echo '<span class="chart__sub_title">',
                    $sub_title,
                '</span>';
            }

            if ($title) {
                echo '<h3 class="chart__title">',
                    $title,
                '</h3>';
            }

            if ($description) {
                echo '<span class="chart__description">',
                    $description,
                '</span>';
            }

            echo '</div>';

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
