<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-text-path.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border,
    Group_Control_Box_Shadow,
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Modules\Shapes\Module as Shapes_Module};

use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons,
};

class WGL_Text_Path extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
	}

	public function get_script_depends() {
		return [ 'wgl-text-path' ];
	}

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_name()
    {
        return 'wgl-text-path';
    }

    public function get_title()
    {
        return esc_html__('WGL Text Path', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-text-path';
    }

	public function get_keywords() {
		return [ 'text path', 'word path', 'text on path', 'wordart', 'word art' ];
	}

	protected function register_content_tab() {
		$wgl_shape = [];
		$wgl_shape['wgl_wave'] = esc_html__( 'WGL Wave', 'courto-core' );
		$wgl_shape['wgl_line_simple'] = esc_html__( 'WGL Line', 'courto-core' );

		$this->start_controls_section(
			'section_content_text_path',
			[
				'label' => esc_html__( 'Text Path', 'courto-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'courto-core' ),
				'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
				'rows' => 3,
				'label_block' => true,
				'default' => esc_html__( 'Curvy Text', 'courto-core' ),
				'frontend_available' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'path',
			[
				'label' => esc_html__( 'Path Type', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => $wgl_shape + Shapes_Module::get_paths(),
				'default' => 'wgl_line_simple',
			]
		);

		$this->add_control(
			'custom_path',
			[
				'label' => esc_html__( 'SVG', 'courto-core' ),
				'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
				'media_types' => [
					'svg',
				],
				'condition' => [ 'path' => 'custom' ],
				'description' => sprintf( esc_html__( 'Want to create custom text paths with SVG?' , 'courto-core' ).' <a target="_blank" href="%s">Learn More</a>', 'https://go.elementor.com/text-path-create-paths' ),
			]
		);

		$this->add_control(
			'svg_animation',
			[
				'label' => esc_html__( 'Animation', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'scroll' => esc_html__('Scroll', 'courto-core'),
                    'loop' => esc_html__('Loop', 'courto-core'),
                ],
				'default' => 'loop',
			]
		);

		$this->add_control(
            'stop_hover',
            [
                'label' => esc_html__('Stop on Hover', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'courto-core' ),
				'label_off' => esc_html__( 'Off', 'courto-core' ),
				'default' => 'yes',
				'condition' => [
					'svg_animation' => 'loop',
				],
            ]
        );

		$this->add_control(
			'divider_text',
			[
				'label' => esc_html__( 'Add Divider', 'courto-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'courto-core' ),
				'label_off' => esc_html__( 'Off', 'courto-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label' => esc_html__( 'Type', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'line' => esc_html__('Line', 'courto-core'),
                    'star' => esc_html__('Star', 'courto-core'),
					'arrow' => esc_html__('Arrow', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
				'condition' => [
					'divider_text' => 'yes',
				],
				'default' => 'custom',
			]
		);

		$this->add_control(
			'divider_custom',
			[
				'label' => esc_html__( 'Divider Custom', 'courto-core' ),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'label_block' => true,
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'divider_type' => 'custom',
					'divider_text' => 'yes',
				],
				'default' => esc_html__( '-', 'courto-core' ),
			]
		);

		$this->add_control(
			'clone_text',
			[
				'label' => esc_html__( 'Clone Text', 'courto-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'courto-core' ),
				'label_off' => esc_html__( 'Off', 'courto-core' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
            'backspace_count',
            [
                'label' => esc_html__('Space After Cloned Element', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 0,
				'condition' => [
					'clone_text' => 'yes',
					'path!' => 'wgl_line_simple',
				],
                'default' => 1,
            ]
        );

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'courto-core' ),
				'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
				'label_block' => true,
				['active' => true],
				'placeholder' => esc_html__( 'Paste URL or type', 'courto-core' ),
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'courto-core' ),
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
				'selectors' => [
					'{{WRAPPER}}' => '--alignment: {{VALUE}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_path',
			[
				'label' => esc_html__( 'Show Path', 'courto-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'courto-core' ),
				'label_off' => esc_html__( 'Off', 'courto-core' ),
				'separator' => 'before',
				'condition' => [ 'path!' => 'wgl_line_simple' ],
				'selectors' => [
					'{{WRAPPER}}' => '--path-stroke: {{VALUE}}; --path-fill: transparent;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls under style tab.
	 */
	protected function register_style_tab() {

		$this->start_controls_section(
			'section_style_svg',
			[
				'label' => esc_html__( 'SVG', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .simple_line' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();
		/**
		 * Text Path styling section.
		 */
		$this->start_controls_section(
			'section_style_text_path',
			[
				'label' => esc_html__( 'Text Path', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
            'text_path_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
 				'condition' => [ 'path' => 'wgl_line_simple' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-text-path' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'min' => 0,
						'max' => 8000,
						'step' => 50,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rotation',
			[
				'label' => esc_html__( 'Rotate', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ 'deg' ],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					],
				],
				'default' => [ 'unit' => 'deg' ],
				'selectors' => [
					'{{WRAPPER}}' => '--rotate: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_heading',
			[
				'label' => esc_html__( 'Text', 'courto-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
            'text_font_family',
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
                    '{{WRAPPER}}' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_responsive_control(
			'word_spacing',
			[
				'label' => esc_html__( 'Word Spacing', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 20,
						'step' => 1,
					],
				],
                'default' => [ 'unit' => 'deg' ],
				'selectors' => [
					'{{WRAPPER}}' => '--word-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'animation_speed',
            [
                'label' => esc_html__('Animation Duration', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 1,
                'default' => 30000,
				'condition' => [
					'svg_animation' => 'loop',
				],
				'frontend_available' => true,
				'render_type' => 'none',
            ]
        );

		$this->add_responsive_control(
			'start_point',
			[
				'label' => esc_html__( 'Starting Point', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ '%', 'custom' ],
				'range' => [
                    '%' => ['min' => -1000, 'max' => 1000],
                ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--start-point: {{SIZE}}%;',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'end_point',
			[
				'label' => esc_html__( 'Ending Point', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'size_units' => [ '%', 'custom' ],
				'range' => [
                    '%' => ['min' => -1000, 'max' => 1000],
                ],
				'default' => [
					'unit' => '%',
					'size' => -10,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--end-point:{{SIZE}}%;',
				],
				'frontend_available' => true,
				'condition' => [
					'svg_animation!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'text_style' );

		/**
		 * Normal tab.
		 */
		$this->start_controls_tab(
			'text_normal',
			[
				'label' => esc_html__( 'Normal', 'courto-core' ),
			]
		);

		$this->add_control(
			'text_color_normal',
			[
				'label' => esc_html__( 'Text Color / Fill Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => '#EFEFEF',
				'selectors' => [
					'{{WRAPPER}} svg text' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .text--word' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
            'stroke_text_width_normal',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 10 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} svg textPath' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; stroke-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .text--word' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'stroke_text_color_normal',
			[
				'label' => esc_html__( 'Stroke Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} svg textPath, {{WRAPPER}} svg text' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .text--word' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		/**
		 * Hover tab.
		 */
		$this->start_controls_tab(
			'text_hover',
			[
				'label' => esc_html__( 'Hover', 'courto-core' ),
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => esc_html__( 'Fill Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} svg text:hover' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .text--word:hover .text' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
            'stroke_text_width_hover',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 10 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} svg text:hover textPath,
                     {{WRAPPER}} svg text:hover' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; stroke-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .text--word:hover .text' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'stroke_text_color_hover',
			[
				'label' => esc_html__( 'Stroke Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} svg text:hover textPath,
					 {{WRAPPER}} svg text:hover' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .text--word:hover .text' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'courto-core' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'default' => [
					'size' => 0.3,
					'unit' => 's',
				],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--transition: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		/**
		 * Path styling section.
		 */
		$this->start_controls_section(
			'section_style_divider',
			[
				'label' => esc_html__( 'Divider', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'divider_text!' => '',
				],
			]
		);


        $this->add_responsive_control(
            'top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -100,
                'max' => 100,
				'step' => 1,
				'default' => '-3',
				'selectors' => [
					'{{WRAPPER}}' => '--top-offset: {{VALUE}};',
					'{{WRAPPER}} .text--word .divider' => 'top: {{VALUE}}px;',
				],
			]
        );

		$this->add_responsive_control(
            'left_offset',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -100,
                'max' => 100,
				'step' => 1,
				'default' => '0',
				'selectors' => [
					'{{WRAPPER}}' => '--left-offset: {{VALUE}};',
					'{{WRAPPER}} .text--word .divider' => 'left: {{VALUE}}px;',
				],
			]
        );

		$this->add_control(
		    'divider_arrow_rotate',
		    [
			    'label' => esc_html__('Rotate', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['deg', 'turn'],
			    'range' => [
				    'deg' => ['max' => 360],
				    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
			    ],
			    'default' => ['unit' => 'deg', 'size' => 45],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
				'condition' => [
					'divider_type' => 'arrow',
				],
			    'selectors' => [
				    '{{WRAPPER}} .divider svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
			    ],
		    ]
	    );

		$this->add_responsive_control(
            'divider_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
	            'default' => [
		            'top' => '',
		            'right' => '23',
		            'bottom' => '',
		            'left' => '11',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%', 'custom'],
				'condition' => [ 'path' => 'wgl_line_simple' ],
                'selectors' => [
                    '{{WRAPPER}} .text--word .divider' => 'margin-right: {{RIGHT}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'arrow_size',
            [
                'label' => esc_html__('Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'condition' => [
					'divider_type' => 'arrow',
				],
                'range' => [
                    'px' => ['min' => 0, 'max' => 250 ],
                ],
				'default' => ['size' => 91],
                'selectors' => [
                    '{{WRAPPER}} .divider svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'divider_typography',
				'condition' => [
					'divider_type!' => 'arrow',
				],
				'selector' => '{{WRAPPER}} tspan.divider, {{WRAPPER}} .divider svg, {{WRAPPER}} .text--word .divider',
			]
		);


		$this->start_controls_tabs( 'divider_style' );

		/**
		 * Normal tab.
		 */
		$this->start_controls_tab(
			'divider_normal',
			[
				'label' => esc_html__( 'Normal', 'courto-core' ),
			]
		);

		$this->add_control(
			'divider_fill_normal',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} tspan.divider,
					 {{WRAPPER}} .divider svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .text--word .divider' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_divider_heading_normal',
			[
				'label' => esc_html__( 'Stroke', 'courto-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'stroke_divider_color_normal',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} tspan.divider,
					 {{WRAPPER}} .divider svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .text--word .divider' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_divider_width_normal',
			[
				'label' => esc_html__( 'Width', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} tspan.divider' => 'stroke-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .divider svg' => 'stroke-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .text--word .divider' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		/**
		 * Hover tab.
		 */
		$this->start_controls_tab(
			'divider_hover',
			[
				'label' => esc_html__( 'Hover', 'courto-core' ),
			]
		);

		$this->add_control(
			'divider_fill_hover',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}}:hover tspan.divider,
					 {{WRAPPER}}:hover .divider svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}}:hover .text--word .divider' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_divider_heading_hover',
			[
				'label' => esc_html__( 'Stroke', 'courto-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'stroke_divider_color_hover',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}:hover .divider svg' => 'stroke-color: {{VALUE}};',
					'{{WRAPPER}}:hover .text--word .divider' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_divider_width_hover',
			[
				'label' => esc_html__( 'Width', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => [ 'unit' => 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover tspan.divider' => 'stroke-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}:hover .divider svg' => 'stroke-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}:hover .text--word .divider' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
            'stroke_divider_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['s'],
                'range' => [
                    's' => ['min' => 0, 'max' => 2, 'step' => 0.1 ],
                ],
                'default' => ['size' => 0.4, 'unit' => 's'],
                'selectors' => [
                    '{{WRAPPER}} tspan.divider, {{WRAPPER}} .divider svg' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Path styling section.
		 */
		$this->start_controls_section(
			'section_style_path',
			[
				'label' => esc_html__( 'Path', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_path!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'path_style' );

		/**
		 * Normal tab.
		 */
		$this->start_controls_tab(
			'path_normal',
			[
				'label' => esc_html__( 'Normal', 'courto-core' ),
			]
		);

		$this->add_control(
			'path_fill_normal',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => '--path-fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_heading_normal',
			[
				'label' => esc_html__( 'Stroke', 'courto-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'stroke_color_normal',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}}' => '--stroke-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_width_normal',
			[
				'label' => esc_html__( 'Width', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		/**
		 * Hover tab.
		 */
		$this->start_controls_tab(
			'path_hover',
			[
				'label' => esc_html__( 'Hover', 'courto-core' ),
			]
		);

		$this->add_control(
			'path_fill_hover',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}}' => '--path-fill-hover: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_heading_hover',
			[
				'label' => esc_html__( 'Stroke', 'courto-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'stroke_color_hover',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}}' => '--stroke-color-hover: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'stroke_width_hover',
			[
				'label' => esc_html__( 'Width', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => [ 'unit' => 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--stroke-width-hover: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'stroke_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'default' => [
					'size' => 0.3,
					'unit' => 's',
				],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--stroke-transition: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

        /**
         * ICONS
         */
        $this->start_controls_section(
            'section_style_media',
            [
                'label' => esc_html__( 'Media', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        WGL_Icons::init(
            $this,
            [
                'label' => esc_html__('Media', 'courto-core'),
                'output' => '',
                'section' => false,
                'default' => [
                    'icon' => [
                        'library' => 'solid',
                        'value' => 'fas fa-icons'
                    ],
                ],
                'media_types_options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'number' => [
                        'title' => esc_html__('Number', 'courto-core'),
                        'icon' => 'fa fa-list-ol',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'courto-core'),
                        'icon' => 'far fa-image',
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'icon_type' => 'image',
                    'thumbnail[url]!' => '',
                ],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 800 ],
                    '%' => ['min' => 5, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typo',
                'condition' => ['icon_type' => 'number'],
                'selector' => '{{WRAPPER}} .wgl-icon',
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => 'font'],
                'range' => [
                    'px' => ['min' => 16, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'icon_type!' => '' ],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}} .wgl-image-box_img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'icon_type!' => '' ],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}} .wgl-image-box_img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'icon_type!' => '' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}} .wgl-image-box_img,
                     {{WRAPPER}} .wgl-image-box_img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tabs_media',[
            'condition' => [ 'icon_type!' => '' ]
        ] );
        $this->start_controls_tab(
            'tab_icon_idle', [
                'label' => esc_html__( 'Idle', 'courto-core' ),
            ]
        );
        $this->add_control(
            'icon_rotate_idle',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'condition' => ['icon_type' => 'font'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => ['font', 'icon']],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}} .wgl-image-box_img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-icon, {{WRAPPER}} .wgl-image-box_img',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border_idle',
                'selector' => '{{WRAPPER}} .wgl-icon, {{WRAPPER}} .wgl-image-box_img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_hover', [
                'label' => esc_html__( 'Hover', 'courto-core' ),
            ]
        );
        $this->add_control(
            'icon_rotate_hover',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'condition' => ['icon_type' => 'font'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-text-path:hover .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_type' => ['font', 'icon']],
                'selectors' => [
                    '{{WRAPPER}} .wgl-text-path:hover .wgl-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-text-path:hover .wgl-icon,
                     {{WRAPPER}} .wgl-text-path:hover .wgl-image-box_img' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-text-path:hover .wgl-icon, {{WRAPPER}} .wgl-text-path:hover .wgl-image-box_img',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border_hover',
                'selector' => '{{WRAPPER}} .wgl-text-path:hover .wgl-icon, {{WRAPPER}} .wgl-text-path:hover .wgl-image-box_img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

	/**
	 * Register Text Path widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_tab();
		$this->register_style_tab();
	}

	/**
	 * Render Text Path widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$_s = $this->get_settings_for_display();

		if ( 'wgl_wave' === $_s['path'] ) {
			$path_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080"><path d="M-724.4,671.5c47.9,17.5,93.6,25.9,139.6,25.9c105.3,0,186.5-43.2,272.5-88.9c86.4-45.9,175.7-93.4,290.1-93.4s203.8,47.5,290.1,93.4c86,45.7,167.2,88.9,272.5,88.9c105.3,0,186.5-43.2,272.5-88.9c86.4-45.9,175.7-93.4,290.1-93.4c114.4,0,203.7,47.5,290.1,93.4c86,45.7,167.2,88.9,272.5,88.9c105.3,0,186.5-43.2,272.5-88.9c86.4-45.9,175.7-93.4,290.1-93.4c114.4,0,203.8,47.5,290.1,93.4c86,45.7,167.2,88.9,272.5,88.9c46,0,91.6-8.5,139.6-25.9"/></svg>';
		}elseif ( 'wgl_line_simple' === $_s['path'] ) {
			$path_svg = '<div class="simple_line"></div>';
		}
		elseif ( 'custom' !== $_s['path'] ) {
			$path_svg = method_exists('Elementor\Modules\Shapes\Module', 'get_path_url') ? Shapes_Module::get_path_url( $_s['path'] ) : '';
			$path_svg = file_get_contents( $path_svg );
		} else {
			$url = $_s['custom_path']['url'];
			$path_svg = ( 'svg' === pathinfo( $url, PATHINFO_EXTENSION ) ) ? file_get_contents( $url ) : '';
		}

        // Icon/Image
        ob_start();
        if (!empty($_s['icon_type'])) {
            $icons = new WGL_Icons;
            echo $icons->build($this, $_s, []);
        }
        $media = ob_get_clean();

		$this->add_render_attribute(
            'text_path',
            [
                'class' => [
                    'wgl-text-path',
                    'none' !== $_s[ 'svg_animation' ] ? $_s[ 'svg_animation' ] . '_animation' : '',
					!empty($_s['hover_animation']) ? 'elementor-animation-' . $_s['hover_animation'] : '',
					!empty($_s['clone_text']) ? 'clone_text' : '',
					!empty($_s['divider_text']) ? 'add_divider' : '',
					'loop' === $_s[ 'svg_animation' ] && !empty($_s['stop_hover']) ? 'stop_on_hover' : '',
                ],
            ]
		);

		if ( ! empty( $_s['clone_text'] ) ) {
			$this->add_render_attribute( 'text_path', 'data-backspace-count', $_s['backspace_count'] );
		}

		if ( ! empty( $_s['divider_text'] ) ) {
			$this->add_render_attribute( 'text_path', 'data-d-type', $_s['divider_type'] );
			if ( 'custom' === $_s['divider_type'] ) {
				$this->add_render_attribute( 'text_path', 'data-d-custom', !empty($_s['divider_custom']) ? $_s['divider_custom'] : '.');
			}
		}

        ?><div <?php echo $this->get_render_attribute_string( 'text_path' ); ?> data-type-svg="<?php echo $_s['path']; ?>" data-text="<?php echo $_s['text']; ?>"><?php
            echo $path_svg;
            echo $media;
        ?></div><?php
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
