<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-working-hours.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Border,
    Repeater
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Working_Hours extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-working-hours';
    }

    public function get_title()
    {
        return esc_html__('WGL Working Hours', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-working-hours';
    }

    public function get_keywords()
    {
        return [ 'working', 'hours' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_working_section',
            ['label' => esc_html__('Content', 'courto-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'working_day',
            [
                'label' => esc_html__('Day', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('Monday', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'working_hours',
            [
                'label' => esc_html__('Hours', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'default' => esc_html__('8.00 - 21.00', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'custom_colors',
            [
                'label' => esc_html__('Custom Colors', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'day_color',
            [
                'label' => esc_html__('Day Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_main_font_color(),
                'condition' => ['custom_colors' => 'yes'],
            ]
        );

        $repeater->add_control(
            'hours_color',
            [
                'label' => esc_html__('Hours Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'condition' => ['custom_colors' => 'yes'],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Days', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    ['working_day' => esc_html__('Monday', 'courto-core')],
                    ['working_day' => esc_html__('Tuesday', 'courto-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{working_day}}',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style Section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Styles', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Item Styles', 'courto-core'),
            ]
        );

        $this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '6',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .working-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );

        $this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .working-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'separator' => 'after',
                'selector' => '{{WRAPPER}} .working-item',
            ]
        );

        $this->add_control(
            'day_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Day Styles', 'courto-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_typo',
                'selector' => '{{WRAPPER}} .working-item_day',
            ]
        );

        $this->add_control(
            'day_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .working-item_day' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
			'day_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .working-item_day' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'hours_styles',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Hours Styles', 'courto-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'hours_typo',
                'selector' => '{{WRAPPER}} .working-item_hours',
            ]
        );

        $this->add_control(
            'hours_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .working-item_hours' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
			'hours_margin',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
				'size_units' => ['px', 'em', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .working-item_hours' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
            'line_color',
            [
                'label' => esc_html__('Line Between Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .working-item::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('working-hours', 'class', 'wgl-working-hours');

        ?><div <?php echo $this->get_render_attribute_string('working-hours'); ?>><?php

        foreach ($settings['items'] as $index => $item) {

            $working_day = $this->get_repeater_setting_key('working_day', 'items' , $index);
            $this->add_render_attribute( $working_day, [
                'class' => [
                    'working-item_day',
                ],
                'style' => [
                    ((bool)$item['custom_colors'] ? 'color: ' . esc_attr($item['day_color']) . ';' : ''),
                ]
            ] );

            $working_hours = $this->get_repeater_setting_key('working_hours', 'items' , $index);
            $this->add_render_attribute($working_hours, [
                'class' => [
                    'working-item_hours',
                ],
                'style' => [
                    ((bool)$item['custom_colors'] ? 'color: '.esc_attr($item['hours_color']).';' : ''),
                ]
            ] );

            ?>
            <div class="working-item"><?php
                if (!empty($item['working_day'])) {
                    ?><div <?php echo $this->get_render_attribute_string($working_day); ?>><?php echo esc_html($item['working_day']); ?></div><?php
                }
                if (!empty($item['working_hours'])) {
                    ?><div <?php echo $this->get_render_attribute_string($working_hours); ?>><?php echo esc_html($item['working_hours']); ?></div><?php
                }?>
            </div><?php

        }

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