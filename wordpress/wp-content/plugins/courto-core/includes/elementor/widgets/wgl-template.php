<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-template.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Frontend,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Widget_Base,
    Controls_Manager};
use WGL_Extensions\{
    Includes\WGL_Elementor_Helper
};

class WGL_Template extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-template';
    }

    public function get_title()
    {
        return esc_html__('WGL Template', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-demo-item';
    }

    public function get_keywords()
    {
        return [ 'template', 'content' ];
    }

    public function get_script_depends()
    {
        return ['wgl-widgets'];
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
            ['label' => esc_html__('General' , 'courto-core')]
        );

        $this->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::SELECT2,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );


        $this->end_controls_section();

        /** STYLE -> TEMPLATE */

        $this->start_controls_section(
            'style_template',
            [
                'label' => esc_html__( 'Style', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-template' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'template_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-template',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-template' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'template' );
        $this->start_controls_tab(
            'template_idle',
            [ 'label' => esc_html__( 'Idle' , 'courto-core' ) ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'template_bg_idle',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wgl-template',
            ]
        );
        $this->add_control(
            'template_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'template_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-template' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'template_blur_idle',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-template' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'template_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-template',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'template_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'template_bg_hover',
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wgl-template:hover',
            ]
        );
        $this->add_control(
            'template_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'template_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .template:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'template_blur_hover',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .template:hover' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'template_shadow_hover',
                'selector' => '{{WRAPPER}} .template:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $wgl_frontend = new Frontend;
        echo '<div class="wgl-template">'.$wgl_frontend->get_builder_content_for_display( $_s['content'] ).'</div>';
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
