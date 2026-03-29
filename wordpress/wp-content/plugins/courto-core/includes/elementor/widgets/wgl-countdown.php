<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-countdown.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Widget_Base,
	Controls_Manager,
	Group_Control_Typography
};
use WGL_Extensions\{
	WGL_Framework_Global_Variables as WGL_Globals,
	Templates\WGLCountDown
};

class WGL_CountDown extends Widget_Base
{
	public function get_name()
	{
		return 'wgl-countdown';
	}

	public function get_title()
	{
		return esc_html__('WGL Countdown Timer', 'courto-core');
	}

	public function get_icon()
	{
		return 'wgl-countdown';
	}

    public function get_keywords()
    {
        return [ 'countdown', 'timer', 'date', 'coming', 'soon' ];
    }

	public function get_categories()
	{
		return ['wgl-modules'];
	}

	public function get_script_depends()
	{
		return [
			'jquery-countdown',
			'wgl-widgets',
		];
	}

	protected function register_controls()
	{

		/*-----------------------------------------------------------------------------------*/
		/*  CONTENT -> GENERAL
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_content_general',
			['label' => esc_html__('General', 'courto-core')]
		);

		$this->add_control(
			'h_tip',
			[
				'label' => esc_html__('Choose the specific date:', 'courto-core'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'countdown_year',
			[
				'label' => esc_html__('Year', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__('Example: 2026', 'courto-core'),
				'default' => esc_html__('2026', 'courto-core'),
			]
		);

		$this->add_control(
			'countdown_month',
			[
				'label' => esc_html__('Month', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__('Example: 12', 'courto-core'),
				'default' => esc_html__('12', 'courto-core'),
			]
		);

		$this->add_control(
			'countdown_day',
			[
				'label' => esc_html__('Day', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__('Example: 31', 'courto-core'),
				'default' => esc_html__('31', 'courto-core'),
			]
		);

		$this->add_control(
			'countdown_hours',
			[
				'label' => esc_html__('Hours', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__('Example: 24', 'courto-core'),
				'default' => esc_html__('24', 'courto-core'),
			]
		);

		$this->add_control(
			'countdown_min',
			[
				'label' => esc_html__('Minutes', 'courto-core'),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__('Example: 59', 'courto-core'),
				'default' => esc_html__('0', 'courto-core'),
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__('Alignment', 'courto-core'),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'before',
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
						'title' => esc_html__('Full Width', 'courto-core'),
						'icon' => 'eicon-text-align-justify',
					],
				],
                'description' => esc_html__('Too large a font size can affect alignment', 'courto-core'),
                'default' => 'center',
				'prefix_class' => 'a%s',
			]
		);

        $this->add_control(
            'demo',
            [
                'label' => esc_html__('Demo Mode', 'courto-core'),
                'description' => esc_html__('XX Days Left Until the New Year', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'render_type' => 'template',
            ]
        );

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  CONTENT -> CONTENT
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_content_content',
			[ 'label' => esc_html__('Content', 'courto-core') ]
		);

		$this->add_control(
			'show_value_names',
			[
				'label' => esc_html__('Show Title?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'show_title_',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_separating',
			[
				'label' => esc_html__('Show Separating Dots?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
                'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount::before,
                	 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount::after' => 'visibility: visible;'
				]
			]
		);

		$this->add_control(
			'hide_day',
			[
				'label' => esc_html__('Hide Days?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_hours',
			[
				'label' => esc_html__('Hide Hours?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_minutes',
			[
				'label' => esc_html__('Hide Minutes?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_seconds',
			[
				'label' => esc_html__('Hide Seconds?', 'courto-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> SECTION
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'countdown_style_section',
			[
				'label' => esc_html__('Section', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'countdown_section_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'vw', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 2 ],
                ],
				'default' => [
					'top' => '0',
					'right' => '0.55',
					'bottom' => '0',
					'left' => '0.55',
					'unit' => 'em',
					'isLinked' => false
				],
				'tablet_default' => [
					'top' => '0',
					'right' => '0.3',
					'bottom' => '0',
					'left' => '0.3',
					'unit' => 'em',
					'isLinked' => false
				],
				'mobile_default' => [
					'top' => '0',
					'right' => '0.3',
					'bottom' => '0',
					'left' => '0.3',
					'unit' => 'em',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'general_font_size',
			[
				'label' => esc_html__('Font Size', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 200,
					],
					'vw' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'size_units' => ['px', 'vw', 'custom'],
                'default' => [
                    'size' => 128,
                    'unit' => 'px',
                ],
                'tablet_extra_default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 70,
                    'unit' => 'px',
                ],
                'mobile_extra_default' => [
                    'size' => 8,
                    'unit' => 'vw',
                ],
                'mobile_default' => [
                    'size' => 8,
                    'unit' => 'vw',
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> NUMBERS
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'countdown_style_numbers',
			[
				'label' => esc_html__('Numbers', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'courto-core'),
				'name' => 'custom_fonts_number',
				'selector' => '{{WRAPPER}} .wgl-countdown .countdown-amount',
			]
		);

        $this->add_control(
            'numbers_font',
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
                    '{{WRAPPER}} .wgl-countdown .countdown-amount' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

		$this->add_control(
			'number_color_idle',
			[
				'label' => esc_html__('Text Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_bg_idle',
			[
				'label' => esc_html__('Background Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'number_width',
			[
				'label' => esc_html__('Min Width(em)', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
					'em' => [
						'min' => 0.8,
						'max' => 3,
						'step' => 0.1,
					],
				],
                'default' => [
                    'size' => 1.25,
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'size' => 1.6,
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'size' => 2.2,
                    'unit' => 'em',
                ],
				'condition' => ['alignment!' => 'justify'],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> TITLES
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_titles',
			[
				'label' => esc_html__('Titles', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'courto-core'),
				'name' => 'custom_fonts_titles',
				'selector' => '{{WRAPPER}} .wgl-countdown .countdown-period',
			]
		);

        $this->add_control(
            'titles_font',
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
                    '{{WRAPPER}} .wgl-countdown .countdown-period' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

		$this->add_responsive_control(
			'titles_alignment',
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-period' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
            'titles_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-countdown .countdown-period' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'titles_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '24',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '18',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '12',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-period' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'titles_color_idle',
			[
				'label' => esc_html__('Text Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-period' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> DOTS
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__('Dots', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['show_separating!' => ''],
			]
		);

		$this->add_control(
			'dots_color_idle',
			[
				'label' => esc_html__('Dots Color', 'courto-core'),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount::before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount::after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'dots_shape',
			[
				'label' => esc_html__('Dots Shape', 'courto-core'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'font' => esc_html__('Current Font', 'courto-core'),
					'circle' => esc_html__('Circle', 'courto-core'),
					'rhombus' => esc_html__('Rhombus', 'courto-core'),
					'square' => esc_html__('Square', 'courto-core'),
				],
				'default' => 'circle',
				'prefix_class' => 'dots_style-',
			]
		);


		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__('Dots Size', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 30 ],
                ],
				'condition' => ['dots_shape!' => ['font', 'rectangle']],
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount' => '--dots-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_interval',
			[
				'label' => esc_html__('Dots Interval', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
				'condition' => ['dots_shape!' => 'font'],
				'default' => [
					'size' => 0.23,
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount' => '--dots-interval: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_v_position',
			[
				'label' => esc_html__('Dots Vertical Position', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
				'range' => [
					'%' => [ 'min' => 0, 'max' => 100 ],
					'px' => [ 'min' => 0, 'max' => 100 ],
					'em' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
				],
				'condition' => ['dots_shape!' => 'font'],
				'default' => [
					'size' => 0.33,
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount' => '--dots-v-pos: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_h_position',
			[
				'label' => esc_html__('Dots Horizontal Position', 'courto-core'),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'%' => [ 'min' => -100, 'max' => 100 ],
					'px' => [ 'min' => -100, 'max' => 100 ],
				],
				'condition' => ['dots_shape!' => 'font'],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount' => '--dots-h-pos: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$atts = $this->get_settings_for_display();

		$countdown = new WGLCountDown();
		$countdown->render($this, $atts);
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
