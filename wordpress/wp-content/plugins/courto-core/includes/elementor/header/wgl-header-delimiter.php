<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Widget_Base, Controls_Manager, Group_Control_Background};

/**
 * Delimiter widget for Header CPT
 *
 *
 * @category Class
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Delimiter extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-delimiter';
    }

    public function get_title()
    {
        return esc_html__('WGL Delimiter', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-header-delimiter';
    }

    public function get_keywords() {
        return ['delimiter'];
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends()
    {
        return [
            'wgl-widgets',
        ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'delimiter_height',
            [
                'label' => esc_html__('Delimiter Height', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'min' => 0,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .delimiter-wrapper .delimiter' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'delimiter_width',
            [
                'label' => esc_html__('Delimiter Width', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => 0,
                'default' => 1,
                'description' => esc_html__('Values in pixels', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .delimiter-wrapper .delimiter' => 'width: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'delimiter_align',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'after',
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
                    '{{WRAPPER}} .delimiter-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'delimiter_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .delimiter-wrapper .delimiter',
            ]
        );

        $this->add_control(
            'delimiter_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'delimiter_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    { ?>
        <div class="delimiter-wrapper">
            <div class="delimiter"></div>
        </div><?php
    }
}
