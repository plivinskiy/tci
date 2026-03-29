<?php

/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-tabs-horizontal.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Plugin,
    Widget_Base,
    Group_Control_Image_Size,
    Control_Media,
    Controls_Manager,
    Group_Control_Border,
    Frontend,
    Utils,
    Icons_Manager,
    Repeater,
    Group_Control_Typography};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper
};

class Wgl_Tabs_Horizontal extends Widget_Base {

	public function get_name() {
		return 'wgl-tabs-horizontal';
	}

	public function get_title() {
		return esc_html__('WGL Tabs Horizontal', 'courto-core');
	}

	public function get_icon() {
		return 'wgl-tabs-horizontal';
	}

    public function get_keywords()
    {
        return [ 'tabs', 'horizontal', 'text' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

	protected function register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*  CONTENT -> GENERAL
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_content_general',
			[ 'label' => esc_html__('General', 'courto-core') ]
		);

        $repeater = new Repeater();
        $repeater->add_control(
			'tabs_tab_title',
			[
				'label' => esc_html__('Title', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
				'default' => esc_html__('Tab Title', 'courto-core'),
			]
		);
		$repeater->add_control(
			'tabs_tab_prefix',
			[
				'label' => esc_html__('Prefix', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
				'placeholder' => esc_html__('/', 'courto-core'),
			]
		);
		$repeater->add_control(
			'tabs_tab_icon_type',
			[
				'label' => esc_html__('Add Icon/Image', 'courto-core'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'' => [
						'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
					],
					'font' => [
						'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
					],
					'image' => [
						'title' => esc_html__('Image', 'courto-core'),
                        'icon' => 'far fa-image',
					]
				],
			]
        );
		$repeater->add_control(
			'tabs_tab_icon_fontawesome',
			[
				'label' => esc_html__('Icon', 'courto-core'),
                'description' => esc_html__('Select icon from Fontawesome library.', 'courto-core'),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
                'condition' => [
                    'tabs_tab_icon_type'  => 'font',
                ],
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-arrow-right',
                ],
			]
        );
		$repeater->add_control(
			'tabs_tab_icon_thumbnail',
			[
				'label' => esc_html__('Image', 'courto-core'),
				'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
				'label_block' => true,
				'condition' => [ 'tabs_tab_icon_type' => 'image' ],
				'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			]
		);
		$repeater->add_control(
			'tabs_content_type',
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
			'tabs_content_templates',
			[
				'label' => esc_html__('Choose Template', 'courto-core'),
				'type' => Controls_Manager::SELECT,
				'condition' => [ 'tabs_content_type' => 'template' ],
				'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
			]
        );
		$repeater->add_control(
			'tabs_content',
			[
				'label' => esc_html__('Tab Content', 'courto-core'),
				'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
				'condition' => [ 'tabs_content_type' => 'content' ],
				'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'courto-core'),
			]
        );

		$this->add_control(
			'tabs_tab',
			[
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[ 'tabs_tab_title' => esc_html__('Tab Title 1', 'courto-core'), ],
					[ 'tabs_tab_title' => esc_html__('Tab Title 2', 'courto-core'), ],
					[ 'tabs_tab_title' => esc_html__('Tab Title 3', 'courto-core'), ],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{tabs_tab_title}}',
			]
		);

        $this->add_responsive_control(
            'tabs_tab_v_align',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-justify-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => ' eicon-justify-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-justify-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-justify-space-evenly-v',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal' => 'align-items: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_tab_title_align',
            [
                'label' => esc_html__('Title Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start; text-align: left;' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center; text-align: center;' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end; text-align: right;' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between; text-align: justify;' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'flex-start; text-align: left;',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header' => 'justify-content: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_tab_content_align',
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
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'separator' => 'after',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_content' => 'text-align: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'sticky_heading',
            [
                'label' => esc_html__('Sticky Heading', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [ 'tabs_tab_v_align' => 'flex-start' ],
            ]
        );

        $this->add_responsive_control(
            'tabs_heading_position',
            [
                'label' => esc_html__('Heading Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'row' => esc_html__('Left', 'courto-core'),
                    'column' => esc_html__('Top', 'courto-core'),
                ],
                'default' => 'row',
                'mobile_default' => 'column',
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal' => 'flex-direction: {{VALUE}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_heading_width',
            [
                'label' => esc_html__('Heading Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom' ],
                'range' => [
                    '%' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'vw' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'px' => [ 'min' => 10, 'max' => 960, 'step' => 1 ],
                ],
                'default' => [ 'size' => 300, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_headings' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tabs_tab_lavalamp_enabled',
            [
                'label' => esc_html__( 'Use Lavalamp Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tabs_tab_animation',
            [
                'label' => esc_html__('Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'effect-fade' => esc_html__('Fade', 'courto-core'),
                    'effect-slide' => esc_html__('Slide', 'courto-core'),
                ],
                'default' => 'effect-fade',
            ]
        );
		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> TITLES WRAPPER
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_titles_wrapper',
			[
				'label' => esc_html__('Titles Wrapper', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'titles_wrapper_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '30',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_headings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'titles_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '10',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_headings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'titles_wrapper_border',
                'render_type' => 'template',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => '0',
                            'right' => '1',
                            'bottom' => '0',
                            'left' => '0',
                            'unit' => 'px',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                        'default' => WGL_Globals::get_h_font_color(0.2),
                    ]
                ],
                'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_headings',
            ]
        );
        $this->add_control(
            'titles_wrapper_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_headings' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> TITLE
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__('Title', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_title_typo',
                'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_header',
            ]
        );

        $this->add_control(
            'tabs_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'h4',
            ]
        );

        $this->add_control(
            'tabs_title_font_family',
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
                    '{{WRAPPER}} .wgl-tabs-horizontal_header' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom' ],
                'default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom' ],
                'default' => [
                    'top' => '10',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'tabs_tab_width',
			[
				'label' => esc_html__('Max Width', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px' => [ 'min' => 10, 'max' => 960, 'step' => 1 ],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'tabs_tab_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->start_controls_tabs( 'tabs_header_tabs' );

		$this->start_controls_tab(
			'tabs_header_idle',
			[ 'label' => esc_html__('Idle', 'courto-core') ]
		);

		$this->add_control(
			't_title_color_idle',
			[
				'label' => esc_html__('Title Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			't_title_bg_color_idle',
			[
				'label' => esc_html__('Title Background Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
            't_title_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'tabs_tab_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_title_border',
				'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_header_hover',
			[ 'label' => esc_html__('Hover', 'courto-core') ]
		);
		$this->add_control(
			't_title_color_hover',
			[
				'label' => esc_html__('Title Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover .wgl-tabs-horizontal_header' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			't_title_bg_color_hover',
			[
				'label' => esc_html__('Title Background Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
            't_title_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'tabs_tab_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover .wgl-tabs-horizontal_header' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 't_title_border_hover',
				'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			't_header_active',
			[ 'label' => esc_html__('Active', 'courto-core') ]
		);
		$this->add_control(
			't_title_color_active',
			[
				'label' => esc_html__('Title Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active .wgl-tabs-horizontal_header' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			't_title_bg_color_active',
			[
				'label' => esc_html__('Title Background Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active' => 'background-color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
            't_title_stroke_color_active',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'tabs_tab_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active .wgl-tabs-horizontal_header' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 't_title_border_active',
				'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> PREFIX
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_prefix',
			[
				'label' => esc_html__('Prefix', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'tabs_prefix_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_prefix_tabs' );
		$this->start_controls_tab(
			'tabs_prefix_idle',
			[ 'label' => esc_html__('Idle', 'courto-core') ]
		);
		$this->add_control(
			'tabs_prefix_color',
			[
				'label' => esc_html__('Prefix Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_prefix' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_prefix_hover',
			[ 'label' => esc_html__('Hover', 'courto-core') ]
		);
		$this->add_control(
			'tabs_prefix_color_hover',
			[
				'label' => esc_html__('Prefix Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover .wgl-tabs-horizontal_prefix' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_prefix_active',
			[ 'label' => esc_html__('Active', 'courto-core') ]
		);
		$this->add_control(
			'tabs_prefix_color_active',
			[
				'label' => esc_html__('Prefix Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active .wgl-tabs-horizontal_prefix' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> ICON
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__('Icon', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'tabs_icon_size',
			[
				'label' => esc_html__('Icon Size', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'default' => [ 'size' => 17, 'unit' => 'px' ],
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 200, 'step' => 1 ],
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_icon:not(.wgl-tabs-horizontal_icon-image)' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_icon_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '15',
                    'unit' => 'px',
                    'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_tabs' );
		$this->start_controls_tab(
			'tabs_icon_idle',
			[ 'label' => esc_html__('Idle', 'courto-core') ]
		);
		$this->add_control(
			'tabs_icon_color',
			[
				'label' => esc_html__('Icon Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_icon_hover',
			[ 'label' => esc_html__('Hover', 'courto-core') ]
		);
		$this->add_control(
			'tabs_icon_color_hover',
			[
				'label' => esc_html__('Icon Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap:hover .wgl-tabs-horizontal_icon' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tabs_icon_active',
			[ 'label' => esc_html__('Active', 'courto-core') ]
		);
		$this->add_control(
			'tabs_icon_color_active',
			[
				'label' => esc_html__('Icon Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_header_wrap.active .wgl-tabs-horizontal_icon' => 'color: {{VALUE}};',
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
			'section_style_content',
			[
				'label' => esc_html__('Content', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tabs_content_typo',
				'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_content-wrap',
			]
		);

		$this->add_responsive_control(
			'tabs_content_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_content_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
                'mobile_default' => [
                    'top' => '25',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_content-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tabs_content_color',
			[
				'label' => esc_html__('Content Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_content-wrap' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tabs_content_bg_color',
			[
				'label' => esc_html__('Content Background Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_content-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tabs_content_border_radius',
			[
				'label' => esc_html__('Border Radius', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-tabs-horizontal_content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tabs_content_border',
				'selector' => '{{WRAPPER}} .wgl-tabs-horizontal_content',
			]
		);

		$this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> LAVALAMP
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_lavalamp',
            [
                'label' => esc_html__( 'Lavalamp', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'tabs_tab_lavalamp_enabled' => 'yes' ],
            ]
        );

        $this->add_responsive_control(
            'tabs_lavalamp_width',
            [
                'label' => esc_html__( 'Lavalamp Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 5, 'max' => 20 ],
                ],
                'condition' => ['tabs_tab_lavalamp_enabled' => 'yes'],
                'default' => [ 'size' => 3, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_lavalamp_height',
            [
                'label' => esc_html__( 'Lavalamp Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 100 ],
                ],
                'condition' => [ 'tabs_tab_lavalamp_enabled' => 'yes' ],
                'default' => [ 'size' => 40, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_lavalamp_margin',
            [
                'label' => esc_html__( 'Lavalamp Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => ['bottom', 'left'],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'tabs_tab_lavalamp_enabled' => 'yes' ],
                'default' => [
                    'top' => '0',
                    'right' => '-2',
                    'bottom' => '',
                    'left' => '',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object' => 'margin: {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} auto auto;',
                ],
            ]
        );

        $this->add_control(
            'tabs_lavalamp_align',
            [
                'label' => esc_html__( 'Lavalamp Alignment', 'courto-core' ),
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
                ],
                'default' => 'center',
                'condition' => [ 'tabs_tab_lavalamp_enabled' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tabs_lavalamp_color_active',
            [
                'label' => esc_html__( 'Lavalamp Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'tabs_tab_lavalamp_enabled' => 'yes' ],
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
	}

	protected function render() {

		$_s = $this->get_settings_for_display();
		$id_int = substr( $this->get_id_int(), 0, 3 );
		if (!!$_s['sticky_heading']){
            wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');
        }

		$this->add_render_attribute( 'tabs', [
            'class' => [
                'wgl-tabs-horizontal',
                !empty($_s['tabs_tab_lavalamp_enabled']) ? 'has-lavalamp' : '',
                $_s['tabs_tab_animation'] ?? '',
            ]
        ] );

		?><div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>

			<div class="wgl-tabs-horizontal_headings<?php echo !!$_s['sticky_heading'] ? ' sticky-sidebar' : ''; ?>"><?php
				foreach ( $_s[ 'tabs_tab' ] as $index => $item ) :

					$tab_count = $index + 1;
					$tab_title_key = $this->get_repeater_setting_key( 'tabs_tab_title', 'tabs_tab', $index );
					$this->add_render_attribute(
						$tab_title_key,
						[
							'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
							'class' => [ 'wgl-tabs-horizontal_header_wrap' ],
						]
					);
					$item_prefix = !empty($item[ 'tabs_tab_prefix' ]) ? '<span class="wgl-tabs-horizontal_prefix">'.$item[ 'tabs_tab_prefix' ].'</span>' : '';

					?><div <?php echo $this->get_render_attribute_string( $tab_title_key ); ?>> <<?php echo $_s[ 'tabs_title_tag' ]; ?> class="wgl-tabs-horizontal_header">
						<span class="wgl-tabs-horizontal_title"><?php echo $item_prefix , $item[ 'tabs_tab_title' ] ?></span>

						<?php
						// Tab Icon/image
						if ( $item[ 'tabs_tab_icon_type' ] != '' ) {
							if ( $item[ 'tabs_tab_icon_type' ] == 'font' && (!empty( $item[ 'tabs_tab_icon_fontawesome' ] )) ) {

								$icon_font = $item[ 'tabs_tab_icon_fontawesome' ];
								$icon_out = '';
	                            // add icon migration
	                            $migrated = isset( $item['__fa4_migrated'][$item[ 'tabs_tab_icon_fontawesome' ]] );
	                            $is_new = Icons_Manager::is_migration_allowed();
	                            if ( $is_new || $migrated ) {
	                                ob_start();
	                                Icons_Manager::render_icon( $item[ 'tabs_tab_icon_fontawesome' ], [ 'aria-hidden' => 'true' ] );
	                                $icon_out .= ob_get_clean();
	                            } else {
	                                $icon_out .= '<i class="icon '.esc_attr($icon_font).'"></i>';
	                            }

								?><span class="wgl-tabs-horizontal_icon"><?php
                                    echo $icon_out;
                                ?></span><?php
							 }
							if ( $item[ 'tabs_tab_icon_type' ] == 'image' && !empty( $item[ 'tabs_tab_icon_thumbnail' ] ) ) {
								if ( ! empty( $item[ 'tabs_tab_icon_thumbnail' ][ 'url' ] ) ) {
									$this->add_render_attribute( 'thumbnail', 'src', $item[ 'tabs_tab_icon_thumbnail' ][ 'url' ] );
									$this->add_render_attribute( 'thumbnail', 'alt', Control_Media::get_image_alt( $item[ 'tabs_tab_icon_thumbnail' ] ) );
									$this->add_render_attribute( 'thumbnail', 'title', Control_Media::get_image_title( $item[ 'tabs_tab_icon_thumbnail' ] ) );

									?><span class="wgl-tabs-horizontal_icon wgl-tabs-horizontal_icon-image"><?php
										echo Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'tabs_tab_icon_thumbnail' );
									?></span><?php
								}
							}
						}
						// End Tab Icon/image
						?>

					</<?php echo $_s[ 'tabs_title_tag' ]; ?>></div>

				<?php endforeach;?>
			</div>

			<div class="wgl-tabs-horizontal_content-wrap"><?php
				foreach ( $_s[ 'tabs_tab' ] as $index => $item ){

					$tab_count = $index + 1;
					$tab_content_key = $this->get_repeater_setting_key( 'tab_content', 'tabs_tab', $index );
					$this->add_render_attribute(
						$tab_content_key,
						[
							'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
							'class' => [ 'wgl-tabs-horizontal_content' ],
						]
					);

					?><div <?php echo $this->get_render_attribute_string( $tab_content_key ); ?>><?php

						if ( $item[ 'tabs_content_type' ] == 'content' ) {
							echo do_shortcode( $item[ 'tabs_content' ] );
						} else if ( $item[ 'tabs_content_type' ] == 'template' ) {
							$id = $item[ 'tabs_content_templates' ];
							$wgl_frontend = new Frontend;
							echo $wgl_frontend->get_builder_content_for_display( $id );
						}

					?></div><?php

                }
            ?></div>
		</div><?php
	}
}
