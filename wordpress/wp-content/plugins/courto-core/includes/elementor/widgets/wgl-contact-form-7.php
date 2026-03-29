<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-contact-form-7.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use WGL_Extensions\Includes\WGL_Elementor_Helper;
use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background,
    Icons_Manager
};

class WGL_Contact_Form_7 extends Widget_Base
{
    protected $forms;
    protected static $icon_html = '';
    protected static $button_class = '';

    public function get_name()
    {
        return 'wgl-contact-form-7';
    }

    public function get_title()
    {
        return esc_html__( 'WGL Contact Form 7', 'courto-core' );
    }

    public function get_icon()
    {
        return 'wgl-contact-form-7';
    }

    public function get_keywords() {
        return ['contact', 'form', '7', 'WPCF7'];
    }

    public function get_categories()
    {
        return [ 'wgl-modules' ];
    }

    protected function get_availbale_forms($id = false)
    {
        if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
            return [];
        }

        $forms = \WPCF7_ContactForm::find( [
            'orderby' => 'date',
            'order' => 'ASC',
        ] );

        if ( empty( $forms ) ) {
            return [];
        }

        $result = [];

        foreach ( $forms as $item ) {
            $key = sprintf( '%1$s::%2$s', $item->name(), $item->title() );
            $result[ $key ] = $item->title();
            $this->forms[$item->name()] = $item->id();
        }

        return $result;
    }

    protected function register_controls()
    {
        /** CONTENT -> GENERAL */

        $this->start_controls_section(
            'section_content_general',
            [
                'label' => esc_html__( 'General', 'courto-core' ),
            ]
        );

        $avaliable_forms = $this->get_availbale_forms();

        $active_form = '';

        if ( ! empty( $avaliable_forms ) ) {
            $active_form = array_keys( $avaliable_forms )[ 0 ];
        }

        $this->add_control(
            'form_shortcode',
            [
                'label' => esc_html__( 'Select Form', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => $active_form,
                'options' => $avaliable_forms,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'general_typo',
                'selector' => '{{WRAPPER}} .wpcf7-form',
            ]
        );

        $this->add_control(
            'general_color',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'additional_color',
            [
                'label' => esc_html__( 'Additional Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} input[type="radio"] + label::before,
                     {{WRAPPER}} input[type="radio"] + span::before,
                     {{WRAPPER}} input[type="checkbox"] + label::before,
                     {{WRAPPER}} input[type="checkbox"] + span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    ]
                ],
                'prefix_class' => 'a%s',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'form_inline',
            [
                'label' => esc_html__( 'Form Inline', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control-wrap,
                     {{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'display: inline-block; vertical-align: top;',

                    '{{WRAPPER}} .wpcf7-form-control-wrap + br' => 'display: none;',
                ]
            ]
        );

        $this->add_responsive_control(
            'form_inline_width',
            [
                'label' => esc_html__( 'Inputs Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'form_inline!' => '' ],
                'size_units' => [ 'px', '%', 'vw', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 200, 'max' => 600 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


        /**  CONTENT -> ICON */
        $this->start_controls_section( 'icon',
            [ 'label' => esc_html__( 'Submit Icon', 'courto-core' ) ]
        );
        $this->add_control(
            'submit_icon',
            [
                'label' => esc_html__( 'Icon', 'courto-core' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ]
        );
        $this->add_control(
            'read_more_icon_align',
            [
                'label' => esc_html__( 'Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'submit_icon[value]!' => '' ],
                'options' => [
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'left' => esc_html__( 'Before', 'courto-core' ),
                    'right' => esc_html__( 'After', 'courto-core' ),
                ],
                'default' => 'right',
            ]
        );

        $this->add_control(
            'button_icon_top',
            [
                'label' => esc_html__('Icon Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                    'em' => ['min' => -5, 'max' => 5],
                ],
                'condition' => [ 'submit_icon[value]!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => '--icon-translate-y: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_section();

        /**  STYLE -> INPUTS */
        $this->start_controls_section(
            'style_inputs',
            [
                'label' => esc_html__( 'Inputs', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'inputs_typography',
                'selector' => '{{WRAPPER}} input[type="text"],'
                            . '{{WRAPPER}} input[type="url"],'
                            . '{{WRAPPER}} input[type="search"],'
                            . '{{WRAPPER}} input[type="email"],'
                            . '{{WRAPPER}} input[type="password"],'
                            . '{{WRAPPER}} input[type="tel"],'
                            . '{{WRAPPER}} input[type="time"],'
                            . '{{WRAPPER}} input[type="number"],'
                            . '{{WRAPPER}} input[type="date"],'
                            . '{{WRAPPER}} select,'
                            . '{{WRAPPER}} textarea,'
                            . '{{WRAPPER}} input.wpcf7-form-control::placeholder,'
                            . '{{WRAPPER}} select.wpcf7-select::placeholder,'
                            . '{{WRAPPER}} textarea.wpcf7-textarea::placeholder',
            ]
        );

        $this->add_responsive_control(
            'inputs_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpcf7-form-control-wrap input,
                     {{WRAPPER}} .wpcf7-form-control-wrap select,
                     {{WRAPPER}} .wpcf7-form-control-wrap textarea' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'inputs_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"],
                     {{WRAPPER}} input[type="url"],
                     {{WRAPPER}} input[type="search"],
                     {{WRAPPER}} input[type="email"],
                     {{WRAPPER}} input[type="password"],
                     {{WRAPPER}} input[type="tel"],
                     {{WRAPPER}} input[type="time"],
                     {{WRAPPER}} input[type="number"],
                     {{WRAPPER}} input[type="date"],
                     {{WRAPPER}} select,
                     {{WRAPPER}} textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'inputs_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"],
                     {{WRAPPER}} input[type="url"],
                     {{WRAPPER}} input[type="search"],
                     {{WRAPPER}} input[type="email"],
                     {{WRAPPER}} input[type="password"],
                     {{WRAPPER}} input[type="tel"],
                     {{WRAPPER}} input[type="time"],
                     {{WRAPPER}} input[type="number"],
                     {{WRAPPER}} input[type="date"],
                     {{WRAPPER}} select,
                     {{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'inputs_height',
            [
                'label' => esc_html__( 'Inputs Height', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'min' => 30,
                'selectors' => [
                    '{{WRAPPER}} input[type="text"],
                     {{WRAPPER}} input[type="url"],
                     {{WRAPPER}} input[type="search"],
                     {{WRAPPER}} input[type="email"],
                     {{WRAPPER}} input[type="password"],
                     {{WRAPPER}} input[type="tel"],
                     {{WRAPPER}} input[type="time"],
                     {{WRAPPER}} input[type="number"],
                     {{WRAPPER}} input[type="date"],
                     {{WRAPPER}} select' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_height',
            [
                'label' => esc_html__( 'Textarea Height', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 60,
                'selectors' => [
                    '{{WRAPPER}} textarea' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs( 'inputs_colors_tabs' );

        $this->start_controls_tab(
            'inputs_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );

        $this->add_control(
            'inputs_placeholder_color_idle',
            [
                'label' => esc_html__( 'Placeholder Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} input.wpcf7-form-control::placeholder,
                     {{WRAPPER}} select.wpcf7-select::placeholder,
                     {{WRAPPER}} textarea.wpcf7-textarea::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'inputs_idle_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"],
                     {{WRAPPER}} input[type="url"],
                     {{WRAPPER}} input[type="search"],
                     {{WRAPPER}} input[type="email"],
                     {{WRAPPER}} input[type="password"],
                     {{WRAPPER}} input[type="tel"],
                     {{WRAPPER}} input[type="time"],
                     {{WRAPPER}} input[type="number"],
                     {{WRAPPER}} input[type="date"],
                     {{WRAPPER}} select,
                     {{WRAPPER}} textarea' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_idle_bg',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} input[type="text"],'
                    . '{{WRAPPER}} input[type="url"],'
                    . '{{WRAPPER}} input[type="search"],'
                    . '{{WRAPPER}} input[type="email"],'
                    . '{{WRAPPER}} input[type="password"],'
                    . '{{WRAPPER}} input[type="tel"],'
                    . '{{WRAPPER}} input[type="time"],'
                    . '{{WRAPPER}} input[type="number"],'
                    . '{{WRAPPER}} input[type="date"],'
                    . '{{WRAPPER}} select,'
                    . '{{WRAPPER}} textarea',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_idle_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} input[type="text"],'
                            . '{{WRAPPER}} input[type="url"],'
                            . '{{WRAPPER}} input[type="search"],'
                            . '{{WRAPPER}} input[type="email"],'
                            . '{{WRAPPER}} input[type="password"],'
                            . '{{WRAPPER}} input[type="tel"],'
                            . '{{WRAPPER}} input[type="time"],'
                            . '{{WRAPPER}} input[type="number"],'
                            . '{{WRAPPER}} input[type="date"],'
                            . '{{WRAPPER}} select,'
                            . '{{WRAPPER}} textarea',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'inputs_idle_shadow',
                'selector' => '{{WRAPPER}} input[type="text"],'
                    . '{{WRAPPER}} input[type="url"],'
                    . '{{WRAPPER}} input[type="search"],'
                    . '{{WRAPPER}} input[type="email"],'
                    . '{{WRAPPER}} input[type="password"],'
                    . '{{WRAPPER}} input[type="tel"],'
                    . '{{WRAPPER}} input[type="time"],'
                    . '{{WRAPPER}} input[type="number"],'
                    . '{{WRAPPER}} input[type="date"],'
                    . '{{WRAPPER}} select,'
                    . '{{WRAPPER}} textarea',
            ]
        );

        $this->add_control(
            'select_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'inputs_focus',
            [ 'label' => esc_html__( 'Focus', 'courto-core' ) ]
        );

        $this->add_control(
            'inputs_focus_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} input[type="text"]:focus,
                     {{WRAPPER}} input[type="url"]:focus,
                     {{WRAPPER}} input[type="search"]:focus,
                     {{WRAPPER}} input[type="email"]:focus,
                     {{WRAPPER}} input[type="password"]:focus,
                     {{WRAPPER}} input[type="tel"]:focus,
                     {{WRAPPER}} input[type="time"]:focus,
                     {{WRAPPER}} input[type="number"]:focus,
                     {{WRAPPER}} input[type="date"]:focus,
                     {{WRAPPER}} select:focus,
                     {{WRAPPER}} textarea:focus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_focus_bg',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} input[type="text"]:focus,'
                    . '{{WRAPPER}} input[type="url"]:focus,'
                    . '{{WRAPPER}} input[type="search"]:focus,'
                    . '{{WRAPPER}} input[type="email"]:focus,'
                    . '{{WRAPPER}} input[type="password"]:focus,'
                    . '{{WRAPPER}} input[type="tel"]:focus,'
                    . '{{WRAPPER}} input[type="time"]:focus,'
                    . '{{WRAPPER}} input[type="number"]:focus,'
                    . '{{WRAPPER}} input[type="date"]:focus,'
                    . '{{WRAPPER}} select:focus,'
                    . '{{WRAPPER}} textarea:focus',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_focus_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} input[type="text"]:focus,'
                    . '{{WRAPPER}} input[type="url"]:focus,'
                    . '{{WRAPPER}} input[type="search"]:focus,'
                    . '{{WRAPPER}} input[type="email"]:focus,'
                    . '{{WRAPPER}} input[type="password"]:focus,'
                    . '{{WRAPPER}} input[type="tel"]:focus,'
                    . '{{WRAPPER}} input[type="time"]:focus,'
                    . '{{WRAPPER}} input[type="number"]:focus,'
                    . '{{WRAPPER}} input[type="date"]:focus,'
                    . '{{WRAPPER}} select:focus,'
                    . '{{WRAPPER}} textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'inputs_focus_shadow',
                'selector' => '{{WRAPPER}} input[type="text"]:focus,'
                    . '{{WRAPPER}} input[type="url"]:focus,'
                    . '{{WRAPPER}} input[type="search"]:focus,'
                    . '{{WRAPPER}} input[type="email"]:focus,'
                    . '{{WRAPPER}} input[type="password"]:focus,'
                    . '{{WRAPPER}} input[type="tel"]:focus,'
                    . '{{WRAPPER}} input[type="time"]:focus,'
                    . '{{WRAPPER}} input[type="number"]:focus,'
                    . '{{WRAPPER}} input[type="date"]:focus,'
                    . '{{WRAPPER}} select:focus,'
                    . '{{WRAPPER}} textarea:focus',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'inputs_not_valid',
            [ 'label' => esc_html__( 'Not Valid', 'courto-core' ) ]
        );

        $this->add_control(
            'inputs_not_valid_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_not_valid_bg',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wpcf7-not-valid',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_not_valid_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wpcf7-not-valid',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'inputs_not_valid_shadow',
                'selector' => '{{WRAPPER}} .wpcf7-not-valid',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> SUBMIT */

        $this->start_controls_section(
            'section_style_submit',
            [
                'label' => esc_html__( 'Submit Button', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typography',
                'selector' => '{{WRAPPER}} .wpcf7-submit'
            ]
        );

        $this->add_responsive_control(
            'submit_full_width',
            [
                'label' => esc_html__( 'Full Width', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .wgl-button-cf7' => 'width: 100%;',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_min_width',
            [
                'label' => esc_html__( 'Button min Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .wgl-button-cf7' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit ~ i',
            ]
        );

        $this->add_responsive_control(
            'submit_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'submit' );
        $this->start_controls_tab(
            'submit_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'submit_color_idle',
            [
                'label' => esc_html__( 'Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit,
                     {{WRAPPER}} .wgl-button-cf7::before,
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit ~ i,
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit ~ span:not(.wpcf7-spinner)' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control(
            'submit_bg_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submit_boder_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'submit_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit,
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit ~ i' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_shadow_idle',
                'selector' => '{{WRAPPER}} .wpcf7-submit',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );

        $this->add_control(
            'submit_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus, :active),
                     {{WRAPPER}} .wgl-button-cf7:is(:hover, :focus, :active)::before,
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit:is(:hover, :focus, :active) ~ i,
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit:is(:hover, :focus, :active) ~ span:not(.wpcf7-spinner)' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control(
            'submit_bg_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus, :active)' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submit_boder_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'submit_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus, :active),
                     {{WRAPPER}} .wgl-button-cf7 .wpcf7-submit:is(:hover, :focus, :active) ~ i' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submit_shadow_hover',
                'selector' => '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus, :active)'
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> ICON */

        $this->start_controls_section(
            'style_media',
            [
                'label' => esc_html__( 'Submit Icon', 'courto-core' ),
                'condition' => [ 'submit_icon[value]!' => '' ],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'submit_icon[value]!' => '' ],
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
                    '{{WRAPPER}} .wgl-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'condition' => [ 'submit_icon[value]!' => '' ],
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
				     {{WRAPPER}} .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_idle',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_idle',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'submit_icon[value]!' => '' ],
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
				     {{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_hover',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_hover',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'submit_icon[value]!' => '' ],
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
				     {{WRAPPER}} .wgl-widget__button:active .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_offset_active',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_active',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'submit_icon[value]!' => '' ],
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
                'separator' => 'before',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'moving_icon' => esc_html__('Moving Icon', 'courto-core'),
                    'background_gradient' => esc_html__('Background Gradient', 'courto-core'),
                    'magnetic' => esc_html__('Magnetic', 'courto-core'),
                    'separated' => esc_html__('Separated Button', 'courto-core'),
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
                    '{{WRAPPER}} .wpcf7-submit .wgl-icon' => '--icon-bg-size: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--bg-gradient-location-1: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--bg-gradient-location-2: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wpcf7-submit' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
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
                    '{{WRAPPER}} .wpcf7-submit' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--h-pos: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--v-pos: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wpcf7-submit' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wpcf7-submit:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wpcf7-submit:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wpcf7-submit:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

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
                    'px' => ['min' => 10, 'max' => 1920, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 500],
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
                    'px' => ['min' => 0.05, 'max' => 1, 'step' => 0.05],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 0.2],
            ]
        );


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

        $this->end_controls_section();

        /** STYLE -> NOT VALID TIP */

        $this->start_controls_section(
            'section_style_tip',
            [
                'label' => esc_html__( 'Not Valid Tip', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tip_alignment',
            [
                'label' => esc_html__( 'Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    ]
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tip_typo',
                'selector' => '{{WRAPPER}} .wpcf7-not-valid-tip',
            ]
        );

        $this->add_responsive_control(
            'tip_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tip_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /** STYLE -> RESPONSE */

        $this->start_controls_section(
            'section_style_response',
            [
                'label' => esc_html__( 'Alert', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'response_typo',
                'selector' => '{{WRAPPER}} .wpcf7-response-output',
            ]
        );

        $this->add_responsive_control(
            'response_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'response_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'response_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'response_bg',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wpcf7-response-output',
            ]
        );

        $this->add_control(
            'response_color',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $item = !empty($self) ? $self : $this;
        if (!!$_s['select_icon_color']){
            $styles = '.elementor-element-' . esc_attr( $item->get_id() ) . ' select{ ';
            $styles .= '--courto-bg-caret-2: url(\'data:image/svg+xml; utf8, '.wgl_dynamic_styles()->bg_caret_2($_s['select_icon_color']).'\');';
            $styles .= '}';
            WGL_Elementor_Helper::enqueue_css( $styles, false );
        }

        if (isset($_s['submit_icon']['value']) && !!($_s['submit_icon']['value'])){
            ob_start();
            Icons_Manager::render_icon( $_s['submit_icon'], [ 'aria-hidden' => 'true' ] );
            $icon = '<span class="icon elementor-icon">'.ob_get_clean().'</span>';
            if ( isset($_s['button_animation_style']) && 'moving_icon' === $_s['button_animation_style']) {
                $icon .= $icon;
            }
        }else{
            $icon = '';
        }

        self::$icon_html = $icon ? '<span class="icon-wrapper"><span class="wgl-icon">'.$icon.'</span></span>' : '';
        self::$button_class = ! empty( $_s['read_more_icon_align'] ) ? ' align-icon-' . $_s['read_more_icon_align'] : '';

        if (!has_filter('wpcf7_form_elements', [__CLASS__, 'filter_wpcf7_submit'])) {
            add_filter('wpcf7_form_elements', [__CLASS__, 'filter_wpcf7_submit'], 10, 1);
        }

        $this->add_render_attribute('wrapper', 'class', 'wgl-contact-form-7');

        if ( isset($_s['button_animation_style']) && 'magnetic' === $_s[ 'button_animation_style' ] ) {
            $this->add_render_attribute([
                'wrapper' => [
                    'class' => 'has-magnetic',
                    'data-magnetic-threshold' => $_s['button_magnetic_threshold']['size'] ?? 500,
                    'data-magnetic-strong' => $_s['button_magnetic_strong']['size'] ?? 0.5,
                ],
            ]);
        }

        $avaliable_forms = $this->get_availbale_forms();
        $shortcode = $this->get_settings( 'form_shortcode' );

        if ( ! array_key_exists( $shortcode, $avaliable_forms ) ) {
            $shortcode = array_keys( $avaliable_forms )[ 0 ];
        }

        $data = explode( '::', $shortcode );

        if ( ! empty( $data ) && 2 === count( $data ) ) {

            if(function_exists('wpcf7_contact_form')){
                if ( ! $contact_form = wpcf7_contact_form( $this->forms[ $data[ 0 ] ] ) ) {
                    $contact_form = wpcf7_get_contact_form_by_title( $data[ 1 ] );
                }

                $atts = [];
                $atts['id'] = $this->forms[ $data[ 0 ] ];
                $atts['title'] = $data[ 1 ];

                echo '<div '.$this->get_render_attribute_string('wrapper').'>' . $contact_form->form_html( $atts ) . '</div>';
            }
        }
    }
    public static function filter_wpcf7_submit($form) {
        $icon = self::$icon_html;

        $form = preg_replace_callback(
            '~<input\b([^>]*\bclass=["\'][^"\']*\bwpcf7-submit\b[^"\']*[^>]*)>~i',
            function ($matches) use ($icon) {
                $attrs = rtrim($matches[1], '/ >');

                preg_match('/\bvalue=["\']([^"\']+)["\']/', $attrs, $valueMatch);
                $value = $valueMatch[1] ?? 'Submit';

                $attrs = preg_replace('/\s*\bvalue=["\'][^"\']*["\']/', '', $attrs);

                $attrs = preg_replace_callback(
                    '/\bclass=["\']([^"\']*)["\']/',
                    function ($classMatch) {
                        $icon = self::$icon_html;
                        $wrap_class = $icon ? ' icon-yes' : '';
                        $classes = $classMatch[1];
                        if (!preg_match('/\bwgl-widget__button wgl-button wgl-wpcf7-button\b/', $classes)) {
                            $button_class = self::$button_class;
                            $classes .= ' wgl-widget__button wgl-button wgl-wpcf7-button' . $button_class;
                        }
                        return 'class="' . trim($classes) . $wrap_class . '"';
                    },
                    $attrs
                );

                return '<button ' . $attrs . '><span class="button__content">' . $icon . '<span>' . $value . '</span></span></button>';
            },
            $form
        );

        return '<div class="wgl-wpcf7-wrapper">' . $form . '</div>';
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