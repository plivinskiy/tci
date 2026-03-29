<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-time-line-horizontal.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{
    Frontend,
    Repeater,
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow};

use WGL_Extensions\{
    Includes\WGL_Elementor_Helper,
    WGL_Framework_Global_Variables as WGL_Globals
};

class Wgl_Time_Line_Horizontal extends Widget_Base
{
    public function get_name() {
        return 'wgl-time-line-horizontal';
    }

    public function get_title() {
        return esc_html__('WGL Time Line Horizontal', 'courto-core');
    }

    public function get_icon() {
        return 'wgl-time-line-horizontal';
    }

    public function get_keywords()
    {
        return [ 'time', 'line', 'timeline', 'date', 'history', 'horizontal', 'carousel' ];
    }

    public function get_script_depends() {
        return ['wgl-widgets', 'swiper'];
    }

    public function get_style_depends()
    {
        return [ 'swiper' ];
    }

    public function get_categories() {
        return [ 'wgl-modules' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_time_line_h_section',
            [
                'label' => esc_html__('General', 'courto-core'),
            ]
        );

        $repeater = new Repeater();

	    $repeater->add_control(
		    'date',
		    [
			    'label' => esc_html__('Date', 'courto-core'),
			    'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
		    ]
	    );

	    $repeater->add_control(
		    'content_type',
		    [
			    'label' => esc_html__('Content Type', 'courto-core'),
			    'type' => Controls_Manager::SELECT,
			    'options' => [
				    'content' => esc_html__('Content', 'courto-core'),
				    'template' => esc_html__('Saved Templates', 'courto-core'),
			    ],
			    'default' => 'content',
		    ]
	    );
	    $repeater->add_control(
		    'content_templates',
		    [
			    'label' => esc_html__('Choose Template', 'courto-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => [ 'content_type' => 'template' ],
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
	    );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
				'default' => esc_html__('This is the heading', 'courto-core'),
				'placeholder' => esc_html__('This is the heading', 'courto-core'),
                'condition' => [ 'content_type' => 'content' ],
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'courto-core'),
                'condition' => [ 'content_type' => 'content' ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('First heading', 'courto-core'),
                        'date' => esc_html__('1997', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Second heading', 'courto-core'),
                        'date' => esc_html__('2000', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Third heading', 'courto-core'),
                        'date' => esc_html__('2014', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Fourth heading', 'courto-core'),
                        'date' => esc_html__('2020', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Fifth heading', 'courto-core'),
                        'date' => esc_html__('2024', 'courto-core'),
                    ],
                ],
                'title_field' => '{{title}}',
            ]
        );

        $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  CONTENT -> CAROUSEL OPTIONS
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'content_section_carousel',
		    [
			    'label' => esc_html__('Carousel Options', 'courto-core'),
		    ]
	    );

        $this->add_control(
            'initial_slide',
            [
                'label' => esc_html__('Index Number of Initial Slide', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'use_navigation',
            [
                'label' => esc_html__('Add Navigation Controls', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'arrows_vertical_position',
            [
                'label' => esc_html__('Arrows Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 600 ],
                ],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'default' => [ 'size' => 26, 'unit' => 'px' ],
                'condition' => [ 'use_navigation!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button' => '--vertical-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_horizontal_position',
            [
                'label' => esc_html__('Arrows Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    '%' => [ 'min' => -10, 'max' => 50 ],
                    'vw' => [ 'min' => -10, 'max' => 50 ],
                ],
                'size_units' => [ '%', 'vw', 'custom'],
                'condition' => [ 'use_navigation!' => '' ],
                'default' => [ 'size' => 0, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button' => '--horizontal-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'shift_arrows_horizontal_position',
            [
                'label' => esc_html__('Shift the Center of the Buttons Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ '%', 'vw', 'custom'],
                'condition' => [ 'use_navigation!' => '' ],
                'range' => [
                    '%' => [ 'min' => -100, 'max' => 100 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => [ 'size' => 0, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-swiper-button' => '--center-horizontal-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'use_pagination',
            [
                'label' => esc_html__('Add Pagination Controls', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'dots_vertical_position',
            [
                'label' => esc_html__('Dots Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 600 ],
                ],
                'size_units' => [ 'px', 'custom'],
                'default' => [ 'size' => 20, 'unit' => 'px' ],
                'condition' => [ 'use_pagination!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => '--dots-vertical-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_horizontal_position',
            [
                'label' => esc_html__('Dots Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    '%' => [ 'min' => -10, 'max' => 110 ],
                    'vw' => [ 'min' => -10, 'max' => 110 ],
                ],
                'size_units' => [ '%', 'vw', 'custom'],
                'condition' => [ 'use_pagination!' => '' ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => '--dots-horizontal-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

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
            'date_overflow',
            [
                'label' => esc_html__('The overflow of Date Section is clipped?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date_container' => 'overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'content_overflow',
            [
                'label' => esc_html__('The overflow of Content Section is clipped?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_container' => 'overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_section_width',
            [
                'label' => esc_html__('Date Section Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 80, 'max' => 500 ],
                ],
                'size_units' => [ 'px', 'vw', 'custom'],
                'default' => [ 'size' => 215, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 18, 'unit' => 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_section_width',
            [
                'label' => esc_html__('Content Section Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 1920 ],
                ],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_wrap' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_section_gap',
            [
                'label' => esc_html__('Content Section Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [ 'px' => [ 'max' => 60 ] ],
                'size_units' => [ 'px', 'custom'],
                'default' => [ 'size' => 30 ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_container' => '--content_gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

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

        $this->add_responsive_control(
            'circle_size',
            [
                'label' => esc_html__('Dots Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 5,'max' => 80 ],
                ],
                'size_units' => [ 'px', 'custom'],
                'default' => [ 'size' => 9, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date' => '--wgl-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'circle_size_active',
            [
                'label' => esc_html__('Dots Size Active', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 5,'max' => 80 ],
                ],
                'size_units' => [ 'px', 'custom'],
                'default' => [ 'size' => 11, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date_wrap' => '--wgl-size-active: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'circle_margin',
            [
                'label' => esc_html__('Dots Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'circle_border_radius',
            [
                'label' => esc_html__('Dots Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-time_line-dot::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'circle_border',
                'render_type' => 'template',
                'fields_options' => [
                    'border' => [
                        'default' => '',
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .wgl-time_line-dot::after',
            ]
        );

        $this->add_responsive_control(
            'circle_gap',
            [
                'label' => esc_html__('Circle Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [ 'px' => [ 'max' => 50 ] ],
                'size_units' => [ 'px', 'custom'],
                'separator' => 'after',
                'default' => [ 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date' => '--wgl-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typo',
                'selector' => '{{WRAPPER}} .wgl-time_line-date',
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__('Date Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '5',
                    'bottom' => '11',
                    'left' => '5',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-time_line-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->start_controls_tabs( 'date_colors' );

        $this->start_controls_tab(
            'date_colors_idle',
            [
                'label' => esc_html__('Idle', 'courto-core'),
            ]
        );

        $this->add_control(
            'date_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.2),
                'selectors' => [
                    '{{WRAPPER}} .wgl-time_line-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_bg_color_idle',
            [
                'label' => esc_html__('Dots Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-time_line-dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'circle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-time_line-dot::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'date_border_color',
            [
                'label' => esc_html__('Date Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date::before,
	                 {{WRAPPER}} .time_line_h-date::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dots_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-time_line-dot::after',
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
            'date_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date:hover .wgl-time_line-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_bg_color_hover',
            [
                'label' => esc_html__('Dots Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date:hover .wgl-time_line-dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'circle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date:hover .wgl-time_line-dot::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dots_shadow_hover',
                'selector' => '{{WRAPPER}} .time_line_h-date:hover .wgl-time_line-dot::after',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'date_colors_active',
            [
                'label' => esc_html__('Active', 'courto-core'),
            ]
        );
        $this->add_control(
            'date_color_active',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date.slide-active .wgl-time_line-date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_bg_color_active',
            [
                'label' => esc_html__('Dots Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date.slide-active .wgl-time_line-dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'circle_border_color_active',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'circle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-date.slide-active .wgl-time_line-dot::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dots_shadow_active',
                'selector' => '{{WRAPPER}} .time_line_h-date.slide-active .wgl-time_line-dot::after',
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
                'selector' => '{{WRAPPER}} .time_line_h-title',
            ]
        );

        $this->start_controls_tabs( 'title_colors' );
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
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .time_line_h-content_wrap:hover .time_line_h-title' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .time_line_h-text',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-content_inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->start_controls_tabs( 'content_colors' );
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
                'selectors' => [
                    '{{WRAPPER}} .time_line_h-text' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .time_line_h-content_inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .time_line_h-content_inner',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} .time_line_h-content_inner',
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
                    '{{WRAPPER}} .time_line_h-content_wrap:hover .time_line_h-text' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .time_line_h-content_wrap:hover .time_line_h-content_inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_hover_border',
                'selector' => '{{WRAPPER}} .time_line_h-content_wrap:hover .time_line_h-content_inner',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_hover_shadow',
                'selector' => '{{WRAPPER}} .time_line_h-content_wrap:hover .time_line_h-content_inner',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // HTML tags allowed for rendering
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

	    $this->add_render_attribute( 'timeline-horizontal', [
		    'class' => [
			    'wgl-timeline-horizontal',
		    ],
	    ] );

        $data_array['initial_slide'] = !!$_s['initial_slide'] && is_int($_s['initial_slide']) ? $_s['initial_slide'] - 1 : 0;
        $data_array['arrows'] = (bool) $_s['use_navigation'];
        $data_array['dots'] = (bool) $_s['use_pagination'];

        $carousel_id = uniqid('wgl_carousel_');
        $data_attribute = " data-swiper='" . json_encode($data_array, true) . "'";
        $data_attribute .= " data-item-carousel='" . $carousel_id . "'";

	    ?><div <?php echo $this->get_render_attribute_string( 'timeline-horizontal' ); ?>>
            <div class="time_line_h-items_wrap"><?php

            $_date = $_content = '';

            foreach ( $_s[ 'items' ] as $index => $item ) {

                $title = $this->get_repeater_setting_key( 'title', 'items' , $index );
                $this->add_render_attribute(
                    $title,
                    [ 'class' => 'time_line_h-title' ]
                );

                $_date .= '<div class="time_line_h-date swiper-slide">';
                    $_date .= '<div class="time_line_h-date_inner">';
                        $_date .= '<span class="wgl-time_line-date">'.$item[ 'date' ].'</span>';
                        $_date .= '<span class="wgl-time_line-dot"></span>';
                    $_date .= '</div>';
                $_date .= '</div>';

	            $_content .= '<div class="time_line_h-content_wrap swiper-slide">';
	            $_content .= '<div class="time_line_h-content_inner">';
	            if ( $item['content_type'] == 'content' ) {
		            if ( ! empty( $item['content'] ) || ! empty( $item['title'] ) ) {
			            if ( ! empty( $item['title'] ) ) {
				            $_content .= '<h3 ' . $this->get_render_attribute_string( $title ) . '>' . $item['title'] . '</h3>';
			            }
			            if ( ! empty( $item['content'] ) ) {
				            $_content .= '<div class="time_line_h-text">' . wp_kses( $item['content'], $allowed_html ) . '</div>';
			            }
		            }
	            } else if ( $item['content_type'] == 'template' ) {
		            $wgl_frontend = new Frontend;
		            $_content .= $wgl_frontend->get_builder_content_for_display( $item['content_templates'] );
	            }
	            $_content .= '</div>';
	            $_content .= '</div>';

            } // end foreach

            echo '<div class="time_line_h-date_container"'.$data_attribute.'><div class="time_line_h-date_wrap swiper-wrapper">',
                $_date,
            '</div></div>';

            echo '<div class="time_line_h-content_container"><div class="time_line_h-content swiper-wrapper">',
                $_content,
            '</div></div>';

            if ($_s['use_navigation']) { ?>
                <div class="wgl-navigation_wrapper">
                    <button class="elementor-swiper-button elementor-swiper-button-prev" data-carousel="<?php echo esc_attr($carousel_id); ?>"><i class="wgl-svg-icon"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['arrow-right']; ?></i></button>
                    <button class="elementor-swiper-button elementor-swiper-button-next" data-carousel="<?php echo esc_attr($carousel_id); ?>"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['arrow-right']; ?></i></button>
                </div><?php
            }

            if ($_s['use_pagination']) {
                echo '<div class="wgl-swiper-pagination-wrapper"><ul class="swiper-pagination" role="tablist" data-carousel="' . esc_attr($carousel_id) . '"></ul></div>';
            } ?>
            </div>
        </div><?php
    }
}
