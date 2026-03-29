<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-button-widget.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow
};
use WGL_Extensions\{
	Includes\WGL_Icons,
	Templates\WGL_Button,
    Includes\WGL_Cursor
};

class WGL_Button_widget extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-button';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Button', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-button';
    }

    public function get_keywords()
    {
        return [ 'button', 'link', 'icon' ];
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
            'text',
            [
                'label' => esc_html__( 'Text', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
	            'label_block' => true,
                'placeholder' => esc_attr__( 'ex: Read more', 'courto-core' ),
                'default' => esc_html__( 'READ MORE', 'courto-core' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'courto-core' ),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_attr__( 'https://your-link.com', 'courto-core' ),
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'courto-core'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'courto-core'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'courto-core'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                ],
                'default' => 'left',
                'prefix_class' => 'a%s',
                'selectors' => [
                    '{{WRAPPER}} .button__content' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start; text-align: left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center; text-align: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end; text-align: right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [ 'alignment!' => ['space-evenly', 'space-around', 'space-between'] ],
                'default' => 'center; text-align: center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .button__text' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .align-icon-top .button__content' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $extra_controls[ 'icon_align' ] = [
            'label' => esc_html__( 'Position', 'courto-core' ),
            'type' => Controls_Manager::SELECT,
            'condition' => [ 'icon_type!' => '' ],
            'options' => [
                'top' => esc_html__( 'Top', 'courto-core' ),
                'left' => esc_html__( 'Before', 'courto-core' ),
                'right' => esc_html__( 'After', 'courto-core' ),
            ],
            'default' => 'right',
        ];

        $extra_controls_responsive[ 'icon_indent' ] = [
            'label' => esc_html__( 'Icon Offset', 'courto-core' ),
            'type' => Controls_Manager::SLIDER,
            'dynamic' => ['active' => true],
            'condition' => [ 'icon_type!' => '' ],
            'size_units' => ['px'],
            'range' => [
                'px' => [ 'max' => 100 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wgl-widget__button .button__content' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
            ],
        ];

        $extra_controls_responsive[ 'icon_indent_hover' ] = [
            'label' => esc_html__( 'Icon Offset on Hover', 'courto-core' ),
            'type' => Controls_Manager::SLIDER,
            'dynamic' => ['active' => true],
            'condition' => [ 'icon_type!' => '' ],
            'size_units' => ['px'],
            'range' => [
                'px' => [ 'max' => 100 ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .button__content' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
            ],
        ];

        $extra_controls_responsive[ 'button_icon_top' ] = [
            'label' => esc_html__('Icon Top Offset', 'courto-core'),
            'type' => Controls_Manager::SLIDER,
            'dynamic' => ['active' => true],
            'size_units' => ['px', 'em'],
            'range' => [
                'px' => ['min' => -50, 'max' => 50],
                'em' => ['min' => -5, 'max' => 5],
            ],
            'condition' => [ 'icon_type!' => '' ],
            'selectors' => [
                '{{WRAPPER}} .wgl-icon,
                 {{WRAPPER}}.has-icon_size_animation .wgl-icon' => '--icon-translate-y: {{SIZE}}{{UNIT}};'
            ],
        ];

        WGL_Icons::init(
            $this,
            [
                'output' => $extra_controls,
                'output_responsive' => $extra_controls_responsive,
                'section' => true,
                'default' => [
                    'media_type' => 'font',
                    'icon' => [
                        'library' => 'fa-solid',
                        'value' => 'fas fa-icons',
                    ],
                ],
            ]
        );

        /**
         * GENERAL -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );

        /** STYLE -> GENERAl */

        $this->start_controls_section(
            'style_general',
            [
                'label' => esc_html__( 'General', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_font_family',
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
                    '{{WRAPPER}} .wgl-widget__button' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_decoration_line_size',
            [
                'label' => esc_html__('Decoration Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_underline_offset',
            [
                'label' => esc_html__('Underline Offset Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_typography_text_decoration' => 'underline' ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20, 'step' => 1],
                    'em' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_min_width',
            [
                'label' => esc_html__( 'Min Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'em', 'rem', 'custom' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 1200 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_min_height',
            [
                'label' => esc_html__( 'Min Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'em', 'rem', 'custom' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 1200 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_aspect_ratio',
            [
                'label' => esc_html__('Sets a 1:1 Aspect Ratio (square)?', 'courto-core'),
                'description' => esc_html__('You can set a width or height, and the other dimension will adjust automatically', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'aspect-ratio: 1 / 1;',
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
                    '{{WRAPPER}} .wgl-widget__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-button-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'button' );
        $this->start_controls_tab(
            'button_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_idle',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-widget__button',
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
                    '{{WRAPPER}} .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:is(:hover, :focus)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_control(
            'button_color_active',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_active',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_active',
                'selector' => '{{WRAPPER}} .wgl-widget__button:active',
            ]
        );

        $this->add_control(
            'button_blur_active',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> MEDIA */

        $this->start_controls_section(
            'style_media',
            [
                'label' => esc_html__( 'Media (icon/image)', 'courto-core' ),
                'condition' => [ 'icon_type!' => '' ],
                'tab' => Controls_Manager::TAB_STYLE,
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
				    '{{WRAPPER}} .wgl-image-box_img img' => 'max-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'icon_type' => 'image' ],
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_type' => 'font' ],
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'button_animation_style!' => 'icon_size_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-icon::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-icon',
            ]
        );
        $this->start_controls_tabs(
            'tabs_icon',
            [
                'condition' => [ 'icon_type' => 'font' ],
            ]
        );
        $this->start_controls_tab(
            'icon_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_idle',
            [
                'label' => esc_html__( 'Icon Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-icon::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_idle',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_type' => 'font' ],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'icon_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__( 'Icon Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button:is(:hover, :focus) .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button:is(:hover, :focus) .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__( 'Icon Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button:is(:hover, :focus) .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button:is(:hover, :focus) .wgl-icon::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_hover',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_type' => 'font' ],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'default' => [ 'unit' => 'deg' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'icon_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_active',
            [
                'label' => esc_html__( 'Icon Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button:active .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button:active .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_active',
            [
                'label' => esc_html__( 'Icon Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button:active .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button:active .wgl-icon::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_active',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_type' => 'font' ],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'default' => [ 'unit' => 'deg' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> ANIMATION */

        $this->start_controls_section(
            'style_animation',
            [
                'label' => esc_html__( 'Button Animation', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_animation_style',
            [
                'label' => esc_html__('Animation Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'moving_icon' => esc_html__('Moving Icon', 'courto-core'),
                    'letter_animation' => esc_html__('Letter Animation', 'courto-core'),
                    'background_gradient' => esc_html__('Background Gradient', 'courto-core'),
                    'border_gradient' => esc_html__('Border Gradient', 'courto-core'),
                    'separated' => esc_html__('Separated Button', 'courto-core'),
                    'highlight_animation' => esc_html__('Highlight Animation', 'courto-core'),
                    'bg_animation' => esc_html__('Background Animation ', 'courto-core'),
                    'border_animation' => esc_html__('Border Animation', 'courto-core'),
                    'magnetic' => esc_html__('Magnetic', 'courto-core'),
                    'icon_size_animation' => esc_html__('Icon Size Animation', 'courto-core'),
                    'icon_visibility' => esc_html__('Icon Visibility', 'courto-core'),
                ],
                'prefix_class' => 'has-',
            ]
        );

        /** Moving Icon Animation */
        $this->add_responsive_control(
            'moving_icon_wrapper_size',
            [
                'label' => esc_html__('Icon BG Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'moving_icon', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        /** Background Gradient Animation */
        $this->add_responsive_control(
            'background_gradient_location_1',
            [
                'label' => esc_html__('Primary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_location_2',
            [
                'label' => esc_html__('Secondary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'background_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 90 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_position',
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
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_h_position',
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
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_v_position',
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
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_background_tabs', [ 
            'condition' => [ 'button_animation_style' => 'background_gradient' ]
        ]);
        $this->start_controls_tab(
            'gradient_background_tab_idle', [ 
            'label' => esc_html__( 'Idle', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_hover', [
            'label' => esc_html__( 'Hover', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_hover',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_hover',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_active', [
            'label' => esc_html__( 'Active', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_active',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_active',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Gradient Animation */
        $this->add_responsive_control(
            'gradient_border_width',
            [
                'label' => esc_html__( 'Border Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'border_gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-mask-border: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_border_tabs',
            [ 'condition' => [ 'button_animation_style' => 'border_gradient' ] ]
        );
        $this->start_controls_tab(
            'gradient_border_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_border_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_hover',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_hover',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Separated Button Animation */
        $this->add_responsive_control(
            'icon_wrapper_size',
            [
                'label' => esc_html__( 'Icon Wrapper Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'separated' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-wrapper: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Highlight Animation */
		$this->add_control(
			'stroke_highlight_color_normal',
			[
				'label' => esc_html__( 'Stroke Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
				'selectors' => [
					'{{WRAPPER}} .highlight_svg path' => 'stroke: {{VALUE}};',
				],
			]
		);
        $this->add_responsive_control(
            'stroke_highlight_width_normal',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 10 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .highlight_svg path' => 'stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Background Animation */

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_bg_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'selector' => '{{WRAPPER}} .wgl-widget__button::after',
            ]
        );
        $this->add_control(
            'button_bg_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_animation', [
            'condition' => [ 'button_animation_style' => 'bg_animation' ],
        ] );
        $this->start_controls_tab(
            'button_animation_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_idle',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_idle',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_w',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_hover',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_hover',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_hover',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_hover',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_active',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_active',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_w_active',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_active',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'button_bg_bg_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Animation */
        $this->add_control(
            'button_border_animation_revers',
            [
                'label' => esc_html__( 'Revers This Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'revers',
                'prefix_class' => '',
                'condition' => [ 'button_animation_style' => 'border_animation' ],
            ]
        );

        $this->add_control(
            'button_border_animation_color',
            [
                'label' => esc_html__( 'Animation Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_offset',
            [
                'label' => esc_html__('Animation Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 20, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 6],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-offset: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_width',
            [
                'label' => esc_html__('Animation Border Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 10, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-width: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_extend',
            [
                'label' => esc_html__('Mow Much to Extend the Border', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 50, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 4],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-extend: {{SIZE}};',
                ],
            ]
        );

        /** Magnetic Threshold */

        $this->add_control(
            'button_magnetic_threshold',
            [
                'label' => esc_html__('Magnetic Threshold', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 5000, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 2000],
            ]
        );

        $this->add_control(
            'button_magnetic_strong',
            [
                'label' => esc_html__('Magnetic Strong', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0.05, 'max' => 5, 'step' => 0.05],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 1],
            ]
        );

        /** Icon Size Animation */
        $this->add_control(
            'button_icon_bg_animation',
            [
                'label' => esc_html__( 'Background Size(px)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ] ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_icon_size_animation_tabs', [
            'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
        ] );
        $this->start_controls_tab(
            'button_icon_size_animation_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_idle',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_idle',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_item_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_item_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_active',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_active',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();


        /** Icon Visibility */
        $this->add_control(
            'icon_visibility',
                [
                'label' => esc_html__( 'Icon Visibility', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'button_animation_style' => 'icon_visibility' ],
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'revert' => esc_html__( 'Revert', 'courto-core' ),
                ],
                'default' => 'default',
                'prefix_class' => 'icon-visibility-'
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        ( new WGL_Button() )->render(
            $this,
            $this->get_settings_for_display()
        );
    }

    public function wpml_support_module()
    {
        add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );
    }

    public function wpml_widgets_to_translate_filter( $widgets )
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}
