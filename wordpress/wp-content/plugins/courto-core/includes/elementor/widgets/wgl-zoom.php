<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-zoom.php`.
 */
namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Frontend, Group_Control_Background, Group_Control_Border, Repeater, Widget_Base, Controls_Manager};

use WGL_Extensions\{
    Includes\WGL_Elementor_Helper
};

class WGL_Zoom extends Widget_Base
{
    public function get_name() {
        return 'wgl-zoom';
    }

    public function get_title() {
        return esc_html__('WGL Scroll Animation', 'courto-core');
    }

    public function get_icon() {
        return 'wgl-zoom';
    }

    public function get_keywords()
    {
        return [ 'zoom', 'scale', 'scroll', 'animation' ];
    }

    public function get_script_depends() {
        return ['wgl-widgets'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_zoom_section',
            [
                'label' => esc_html__('General', 'courto-core'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Animation Section Size', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '0' => esc_html__('Default (is equal to 2vh)', 'courto-core'),
                    '1' => esc_html__('3vh', 'courto-core'),
                    '2' => esc_html__('4vh', 'courto-core'),
                    '3' => esc_html__('5vh', 'courto-core'),
                    '4' => esc_html__('6vh', 'courto-core'),
                    '5' => esc_html__('7vh', 'courto-core'),
                ],
                'default' => '0',
                'selectors' => [
                    '{{WRAPPER}}' => '--wgl-anim-speed: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_height',
            [
                'label' => esc_html__( 'Items Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 100, 'max' => 1200, 'step' => 1 ],
                    '%' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'vw' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'vh' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 100, 'unit' => 'vh' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--wgl-items-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'description' => esc_html__('This option helps to configure the widget. You will not see this title on the frontend.', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'content_templates',
            [
                'label' => esc_html__('Choose Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );
        $repeater->add_responsive_control(
            'wgl_zoom_items_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top left' => esc_html__('Top Left', 'courto-core'),
                    'top center' => esc_html__('Top Center', 'courto-core'),
                    'top right' => esc_html__('Top Right', 'courto-core'),
                    'center left' => esc_html__('Center Left', 'courto-core'),
                    'center center' => esc_html__('Center Center', 'courto-core'),
                    'center right' => esc_html__('Center Right', 'courto-core'),
                    'bottom left' => esc_html__('Bottom Left', 'courto-core'),
                    'bottom center' => esc_html__('Bottom Center', 'courto-core'),
                    'bottom right' => esc_html__('Bottom Right', 'courto-core'),
                ],
                'selectors_dictionary' => [
                    'top left' =>      '0 auto auto 0',
                    'top center' =>    '0 auto auto auto',
                    'top right' =>     '0 0 auto auto',
                    'center left' =>   'auto auto auto 0',
                    'center center' => 'auto auto auto auto',
                    'center right' =>  'auto 0 auto auto',
                    'bottom left' =>   'auto auto 0 0',
                    'bottom center' => 'auto auto 0 auto',
                    'bottom right' =>  'auto 0 0 auto',
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-zoom_item' => 'margin: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wgl_zoom_items_bg',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'selectors' => [ '{{SELECTOR}}' => 'content: \'\'' ], ],
                ],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}::before',
            ]
        );
        $repeater->add_responsive_control(
            'wgl_zoom_items_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => -5,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $repeater->start_controls_tabs( 'wgl_zoom_tabs' );
        $repeater->start_controls_tab(
            'wgl_zoom_start',
            ['label' => esc_html__('Start', 'courto-core')]
        );
        $repeater->add_control(
            'time_from',
            [
                'label' => esc_html__( 'Starting Animation Point on Screen', 'courto-core' ),
                'description' => esc_html__( 'Vertical position on the screen when all animation effects are applied to the current item( 0 - middle of a screen, 100 - very bottom, -100 - top of a screen)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 200, 'step' => 1 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'scale_from',
            [
                'label' => esc_html__( 'Scale From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [ 'min' => -10, 'max' => 50, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_control(
            'opacity_from',
            [
                'label' => esc_html__( 'Opacity From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -2, 'max' => 5, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'blur_from',
            [
                'label' => esc_html__( 'Blur From', 'courto-core' ),
                'description' => esc_html__( 'We recommend not using blur on tablet and mobile devices for better animation playback.', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'tablet_default' => ['size' => 0],
                'mobile_default' => ['size' => 0],
                'range' => [
                    'px' => [ 'min' => -10, 'max' => 50, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'vert_pos_from',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'hor_pos_from',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => -1200, 'max' => 1200 ],
                    '%' => [ 'min' => -200, 'max' => 200 ],
                ],
            ]
        );
        $repeater->add_control(
            'wgl_zoom_items_pointer_events_from',
            [
                'label' => esc_html__( 'Pointer Events', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'auto' => esc_html__( 'Auto', 'courto-core' ),
                    'none' => esc_html__( 'None', 'courto-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'pointer-events: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'wgl_zoom_end',
            ['label' => esc_html__('End', 'courto-core')]
        );
        $repeater->add_control(
            'time_to',
            [
                'label' => esc_html__( 'Ending Animation Point on Screen', 'courto-core' ),
                'description' => esc_html__( 'Vertical endpoint position on the screen for applying all animation effects (0 - middle of the screen, 100 - very bottom, -100 - top of the screen)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 200, 'step' => 1 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'scale_to',
            [
                'label' => esc_html__( 'Scale To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [ 'min' => -10, 'max' => 50, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_control(
            'opacity_to',
            [
                'label' => esc_html__( 'Opacity To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -2, 'max' => 5, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'blur_to',
            [
                'label' => esc_html__( 'Blur To', 'courto-core' ),
                'description' => esc_html__( 'We recommend not using blur on tablet and mobile devices for better animation playback.', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'tablet_default' => ['size' => 0],
                'mobile_default' => ['size' => 0],
                'range' => [
                    'px' => [ 'min' => -10, 'max' => 50, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'vert_pos_to',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px'],
                'range' => [ 'px' => [ 'min' => -1200, 'max' => 1200 ] ],
            ]
        );
        $repeater->add_responsive_control(
            'hor_pos_to',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px'],
                'range' => [ 'px' => [ 'min' => -1200, 'max' => 1200 ] ],
            ]
        );
        $repeater->add_control(
            'wgl_zoom_items_pointer_events_to',
            [
                'label' => esc_html__( 'Pointer Events', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'auto' => esc_html__( 'Auto', 'courto-core' ),
                    'none' => esc_html__( 'None', 'courto-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .zoom_completed {{CURRENT_ITEM}}' => 'pointer-events: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'wgl_zoom_background', [
                'label' => esc_html__('Background', 'courto-core'),
                'condition' => ['wgl_zoom_items_bg_background!' => ''],
            ]
        );
        $repeater->add_control(
            'bg_time_from',
            [
                'label' => esc_html__( 'Starting Animation Point on Screen', 'courto-core' ),
                'description' => esc_html__( 'Vertical position on the screen when all animation effects are applied to the current item( 0 - middle of a screen, 100 - very bottom, -100 - top of a screen)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 200, 'step' => 1 ],
                ],
            ]
        );
        $repeater->add_control(
            'bg_time_to',
            [
                'label' => esc_html__( 'Ending Animation Point on Screen', 'courto-core' ),
                'description' => esc_html__( 'Vertical endpoint position on the screen for applying all animation effects (0 - middle of the screen, 100 - very bottom, -100 - top of the screen)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 200, 'step' => 1 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'bg_scale_from',
            [
                'label' => esc_html__( 'Background Scale From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
                ],
            ]
        );
        $repeater->add_responsive_control(
            'bg_scale_to',
            [
                'label' => esc_html__( 'Background Scale To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
                ],
            ]
        );
        $repeater->add_control(
            'bg_opacity_from',
            [
                'label' => esc_html__( 'Background Opacity From', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->add_control(
            'bg_opacity_to',
            [
                'label' => esc_html__( 'Background Opacity To', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => esc_html__('Template:', 'courto-core') . ' {{{ content_templates }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();
        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_overflow',
            [
                'label' => esc_html__('Item Overflow', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'courto-core'),
                    'overflow: visible;' => esc_html__('Visible', 'courto-core'),
                    'overflow: hidden;' => esc_html__('Hidden', 'courto-core'),
                    'overflow: clip;' => esc_html__('Clip', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-zoom_item__wrapper' => '{{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-zoom_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-zoom_item__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .wgl-zoom_item__wrapper',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $this->add_render_attribute( 'zoom', [
            'class' => [ 'wgl-zoom' ],
            'data-speed' => !!$_s['speed'] ? $_s['speed'] + 1 : 1,
        ] );

        ?><div <?php echo $this->get_render_attribute_string( 'zoom' ); ?>>
        <div class="wgl-zoom_wrapper"><?php

            foreach ( $_s[ 'items' ] as $index => $item ) {

                $data_array['time_from'] = !!$item['time_from']['size'] ? $item['time_from']['size'] / 100 : 0;
                $data_array['time_to'] = !!$item['time_to']['size'] ? $item['time_to']['size'] / 100 : 1;

                $data_array['bg_time_from'] = !!$item['bg_time_from']['size'] ? $item['bg_time_from']['size'] / 100 : $data_array['time_from'];
                $data_array['bg_time_to'] = !!$item['bg_time_to']['size'] ? $item['bg_time_to']['size'] / 100 : $data_array['time_to'];

                $fields = [
                    'scale_from',
                    'scale_to',
                    'scale_from_tablet',
                    'scale_to_tablet',
                    'scale_from_mobile',
                    'scale_to_mobile',

                    'opacity_from',
                    'opacity_to',

                    'blur_from',
                    'blur_to',
                    'blur_from_tablet',
                    'blur_to_tablet',
                    'blur_from_mobile',
                    'blur_to_mobile',

                    'hor_pos_from',
                    'hor_pos_to',
                    'hor_pos_from_tablet',
                    'hor_pos_to_tablet',
                    'hor_pos_from_mobile',
                    'hor_pos_to_mobile',

                    'vert_pos_from',
                    'vert_pos_to',
                    'vert_pos_from_tablet',
                    'vert_pos_to_tablet',
                    'vert_pos_from_mobile',
                    'vert_pos_to_mobile',

                    'bg_scale_from',
                    'bg_scale_to',
                    'bg_scale_from_tablet',
                    'bg_scale_to_tablet',
                    'bg_scale_from_mobile',
                    'bg_scale_to_mobile',

                    'bg_opacity_from',
                    'bg_opacity_to',
                ];
                foreach ($fields as $field) {
                    if (isset($item[$field]['size']) && '' !== $item[$field]['size']) $data_array[$field] = $item[$field]['size'];
                }

                $units = [
                    'hor_pos_from',
                    'hor_pos_from_tablet',
                    'hor_pos_from_mobile',
                    'vert_pos_from',
                    'vert_pos_from_tablet',
                    'vert_pos_from_mobile',
                ];
                foreach ($units as $unit) {
                    if (isset($item[$unit]['unit']) && '' !== $item[$unit]['unit']) $data_array[$unit.'_unit'] = $item[$unit]['unit'];
                }

                $zoom_items = $this->get_repeater_setting_key( 'zoom_items', 'items' , $index );
                $this->add_render_attribute( $zoom_items, [
                    'class' => [ 'wgl-zoom_item__wrapper', 'elementor-repeater-item-'.$item['_id'] ],
                    'data-zoom' => json_encode($data_array, true),
                ] );

                ?><div <?php echo $this->get_render_attribute_string( $zoom_items ); ?>><div class="wgl-zoom_item"><?php

                    $wgl_frontend = new Frontend;
                    echo $wgl_frontend->get_builder_content_for_display( $item['content_templates'] );

                    ?></div></div><?php

            } // end foreach

            ?></div>
        </div><?php
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
