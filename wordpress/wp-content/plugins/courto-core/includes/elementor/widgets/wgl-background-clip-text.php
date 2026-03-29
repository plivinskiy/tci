<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-background-clip-texts.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Utils, Widget_Base, Controls_Manager, Group_Control_Typography, Group_Control_Background};
use WGL_Extensions\Includes\WGL_Cursor;
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Background_Clip_Text extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-background-clip-text';
    }

    public function get_title()
    {
        return esc_html__('WGL Background Clip Text', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-background-clip-text';
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
            'title',
            [
                'label' => esc_html__('Text', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
                'rows' => 3,
                'default' => esc_html__('GET IN TOUCH', 'courto-core'),
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
                'name' => 'title_typo',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [ 'default' => [ 'size' => 'clamp(32px, 16vw, 300px)', 'unit' => 'custom' ] ],
                    'font_weight' => [ 'default' => 800 ],
                    'line_height' => ['default' => ['size' => 1.25, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => -0.04, 'unit' => 'em']],
                ],
                'selector' => '{{WRAPPER}} .clip-text__title',
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
                    '{{WRAPPER}} .clip-text__title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
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
                    '{{WRAPPER}} .clip-text__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_appearance',
            [
                'label' => esc_html__('Appearance', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Unset', 'courto-core' ),
                    'text_stroke' => esc_html__( 'Text Stroke', 'courto-core' ),
                    'mask_image' => esc_html__( 'Mask Image', 'courto-core' ),
                ],
                'render_type' => 'ui',
                'separator' => 'before',
                'prefix_class' => 'wgl-title-appearance-',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'title_appearance!' => 'mask_image' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_color_gradient',
            [
                'label' => esc_html__('Use Paint Order?', 'courto-core'),
                'description' => esc_html__('This option can fix bug with problematic fonts. Do not use "Text Color" transparency.', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'title_appearance' => 'text_stroke',
                    'title_stroke_size[size]!' => [0, ''],
                    'title_stroke_gradient!' => 'yes',
                ],
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => 'paint-order: stroke fill;',
                ],
            ]
        );
        $this->end_controls_section();


        /**
         * STYLE -> TEXT STROKE
         */

        $this->start_controls_section(
            'style_stroke',
            [
                'label' => esc_html__('Text Stroke', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title_appearance' => 'text_stroke',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_size',
            [
                'label' => esc_html__('Text Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'title_stroke_size[size]!' => [0, ''],
                    'title_stroke_gradient!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_gradient',
            [
                'label' => esc_html__('Use Gradient?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'courto-core'),
                'label_off' => esc_html__('No', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '-webkit-background-clip: text; -webkit-text-stroke-color: transparent;',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_gradient_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--gradient-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_gradient_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--gradient-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'title_stroke_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'title_stroke_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'radial',
                'render_type' => 'ui',
                'condition' => [ 'title_stroke_gradient!' => '' ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_angle',
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
                    'title_stroke_gradient!' => '',
                    'title_stroke_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_position',
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
                    'title_stroke_gradient!' => '',
                    'title_stroke_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--gradient-color-1) var(--gradient-location-1), var(--gradient-color-2) var(--gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_h_position',
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
                    'title_stroke_gradient!' => '',
                    'title_stroke_gradient_type' => 'radial',
                    'title_stroke_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_gradient_v_position',
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
                    'title_stroke_gradient!' => '',
                    'title_stroke_gradient_type' => 'radial',
                    'title_stroke_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .clip-text__title' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


        /**
         * STYLE -> MASK IMAGE
         */

        $this->start_controls_section(
            'style_mask_image',
            [
                'label' => esc_html__('Text Mask Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'title_appearance' => 'mask_image' ],
            ]
        );
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_mask_background',
				'label' => esc_html__('Background', 'courto-core'),
				'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'condition' => [ 'background' => [ 'gradient' ] ],
                        'default' => WGL_Globals::get_secondary_color(),
                    ],
                    'color_b' => [
                        'label' => esc_html__( 'Second Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(),
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 90,
                        ],
                    ],
                    'image' => [ 'default' => [ 'url' => Utils::get_placeholder_image_src() ] ],
                    'position' => [ 'default' => 'center center' ],
                    'repeat' => [ 'default' => 'no-repeat' ],
                    'size' => [ 'default' => 'cover' ],
                ],
                'selector' => '{{WRAPPER}} .clip-text__title',
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

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);

        echo '<div class="wgl-background-clip-text' . ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' ) . '"' . $cursor_data . '>';

        if (
            $_s['title']
        ) {
            if (!empty($_s['link']['url'])) {
                $this->add_render_attribute('link', 'class', 'clip-text__link');
                $this->add_link_attributes('link', $_s['link']);

                echo '<a ', $this->get_render_attribute_string('link'), '>';
            }

            echo '<', $_s['title_tag'], ' class="clip-text__title">', $_s['title'], '</', $_s['title_tag'], '>';

            if (!empty($_s['link']['url'])) {
                echo '</a>';
            }
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
