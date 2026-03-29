<?php

/**
 * This template can be overridden by copying it to `yourtheme[-child]/courto-core/elementor/widgets/wgl-horizontal-scroll.php`.
 */

namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Frontend, Group_Control_Background, Group_Control_Border, Repeater, Widget_Base, Controls_Manager};
use WGL_Extensions\{
    Includes\WGL_Elementor_Helper
};

class WGL_Horizontal_Scroll extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-horizontal-scroll';
    }

    public function get_title()
    {
        return esc_html__('WGL Horizontal Scroll', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-horizontal-scroll';
    }

    public function get_keywords()
    {
        return [ 'horizontal', 'scale', 'scroll', 'animation' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [
            'imagesloaded',
            'gsap',
            'gsap-scroll-trigger',
            'wgl-widgets',
        ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'Content',
            array(
                'label' => esc_html__('Content', 'courto-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
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

        $repeater->add_control(
            'bg_transition',
            array(
                'label'     => esc_html__('Background Transition', 'courto-core'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default'   => '',
                'separator' => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
            )
        );
        $repeater->add_control(
            'bg_color',
            array(
                'label'     => esc_html__('Color', 'courto-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.wgl-bg-horizontal-scroll-{{ID}}.wgl-bg-horizontal-scroll .bg-scroll{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ),
                'condition'   => array(
                    'bg_transition' => 'yes',
                    'css_transform_animation!' => 'yes' 
                ),
            ),
        );
        $repeater->add_control(
            'image',
            array(
                'label' => esc_html__('Choose Image', 'courto-core'),
                'type'  => Controls_Manager::MEDIA,
                'condition'   => array(
                    'bg_transition' => 'yes',
                    'css_transform_animation!' => 'yes' 
                ),
            )
        );
        $repeater->add_control(
            'hs_background',
            array(
                'label'        => esc_html__('Backdrop Filter', 'courto-core'),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_off'    => __('Default', 'courto-core'),
                'label_on'     => __('Custom', 'courto-core'),
                'return_value' => 'yes',
                'condition'   => array(
                    'bg_transition' => 'yes',
                    'css_transform_animation!' => 'yes' 
                ),
            )
        );
        $repeater->start_popover();
        $repeater->add_control(
            'bg_blur',
            array(
                'label'      => esc_html__('Background Blur', 'courto-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'max'  => 10,
                        'min'  => 0,
                        'step' => 1,
                    ),
                ),
                'default'    => array(
                    'unit' => 'px',
                    'size' => 1,
                ),
                'selectors'  => array(
                    '.wgl-bg-horizontal-scroll-{{ID}}.wgl-bg-horizontal-scroll .bg-scroll{{CURRENT_ITEM}}:before' => '-webkit-backdrop-filter:blur({{bg_blur.SIZE}}{{bg_blur.UNIT}});backdrop-filter:blur({{bg_blur.SIZE}}{{bg_blur.UNIT}});',
                ),
                'condition'   => array(
                    'bg_transition' => 'yes',
                    'css_transform_animation!' => 'yes' 
                ),
            )
        );
        $repeater->add_responsive_control(
            'background_overlay',
            array(
                'label'     => esc_html__('Background Overlay', 'courto-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '.wgl-bg-horizontal-scroll-{{ID}}.wgl-bg-horizontal-scroll .bg-scroll{{CURRENT_ITEM}}:before' => 'background-color: {{VALUE}}',
                ),
                'condition'   => array(
                    'bg_transition' => 'yes',
                    'css_transform_animation!' => 'yes' 
                ),
            )
        );
        $repeater->end_popover();

        $repeater->add_responsive_control(
            'item_width',
            [
                'label' => esc_html__('Item Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'vw' => ['min' => 1, 'max' => 1000 ],
                ],
                'frontend_available' => true,
                'render_type'        => 'none',
                'size_units' => ['vw'],
                'label_block' => true,
                'default' => ['size' => 100, 'unit' => 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-horizontal_scroll_item{{CURRENT_ITEM}}' => 'width: {{SIZE}}vw;flex: 0 0 {{SIZE}}vw;',
                ],
            ]
        );

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

        $this->start_controls_section(
            'advanced_options',
            array(
                'label' => esc_html__('Advanced Options', 'courto-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'css_transform_animation',
            [
                'label' => esc_html__('CSS Transfom Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom' ],
                'condition' => [ 'css_transform_animation' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-horizontal-scroll-wrapper .wgl-horizontal_scroll_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'each_item_padding',
            [
                'label' => esc_html__( 'Items Inner Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'vw', 'custom' ],
                'condition' => [ 'css_transform_animation' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-horizontal-scroll-wrapper .wgl-horizontal_scroll_wrapper .wgl-horizontal_scroll_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => esc_html__( 'Spacing', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'vw' => ['min' => -100, 'max' => 100 ],
                ],
                'size_units' => ['vw'],
                'label_block' => true,
                'condition' => [ 'css_transform_animation' => 'yes' ],
                'default' => ['size' => 40, 'unit' => 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-horizontal-scroll-wrapper .wgl-horizontal_scroll_wrapper' => '--progress-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bg_duration_change',
            [
                'label' => esc_html__('Change Transition', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Use', 'courto-core'),
                'label_off' => esc_html__('Hide', 'courto-core'),
                'default' => 'yes',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'bg_duration',
            array(
                'label'      => esc_html__('Background Transition', 'courto-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 's' ),
                'range'      => array(
                    's' => array(
                        'min'  => 0,
                        'max'  => 2,
                        'step' => 0.1,
                    ),
                ),
                'default'    => array(
                    'unit' => 's',
                    'size' => 0.7,
                ),
                'selectors'  => array(
                    '.wgl-bg-horizontal-scroll-{{ID}}.wgl-bg-horizontal-scroll .bg-scroll' => 'transition:all {{SIZE}}{{UNIT}} linear;',
                ),
                'condition'   => array(
                    'bg_duration_change' => 'yes',
                    'css_transform_animation!' => 'yes'
                ),
            )
        );

        $this->add_control(
            'sticky_element',
            array(
                'label'     => esc_html__('Sticky Section', 'courto-core'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default'   => '',
                'separator' => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
            )
        );
        $this->add_control(
            'sticky_template',
            array(
                'label'       => esc_html__('Select Template', 'courto-core'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '0',
                'options'     => Wgl_Elementor_Helper::get_instance()->get_elementor_templates(),
                'label_block' => 'true',
                'classes'     => 'wgl-template-create-btn',
                'condition'   => array(
                    'sticky_element' => 'yes',
                    'css_transform_animation!' => 'yes'
                ),
            )
        );
        $this->add_control(
            'opacity_scroll',
            array(
                'label'     => esc_html__('Opacity Prev/Next Element', 'courto-core'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default'   => '',
                'separator' => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
            )
        );
        $this->add_control(
            'opacity_val',
            array(
                'label'     => esc_html__('Value', 'courto-core'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 1,
                'step'      => 0.1,
                'default'   => 0.5,
                'condition' => array(
                    'opacity_scroll' => 'yes',
                    'css_transform_animation!' => 'yes'
                ),
            )
        );

        $this->add_control(
            'h_scroll_start_position',
            [
                'label'              => esc_html__('Start', 'courto-core'),
                'description'        => esc_html__('The first value sets the element’s original placement in the document. The second value controls how and where that element is displayed on screen during scrolling.', 'courto-core'),
                'type'               => Controls_Manager::SELECT,
                'separator'          => 'before',
                'default'            => 'default',
                'frontend_available' => true,
                'options'            => [
                    'default'       => esc_html__('Default', 'courto-core'),
                    'top top'       => esc_html__('Top on Top', 'courto-core'),
                    'top center'    => esc_html__('Top on Center', 'courto-core'),
                    'top bottom'    => esc_html__('Top on Bottom', 'courto-core'),
                    'center top'    => esc_html__('Center on Top', 'courto-core'),
                    'center center' => esc_html__('Center on Center', 'courto-core'),
                    'center bottom' => esc_html__('Center on Bottom', 'courto-core'),
                    'bottom top'    => esc_html__('Bottom on Top', 'courto-core'),
                    'bottom center' => esc_html__('Bottom on Center', 'courto-core'),
                    'bottom bottom' => esc_html__('Bottom on Bottom', 'courto-core'),
                    'custom'        => esc_html__('custom', 'courto-core'),
                ],
                'render_type'        => 'none',
                'separator'   => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],

            ]
        );

        $this->add_control(
            'h_scroll_custom_start_position',
            [
                'label'              => esc_html__('Custom', 'courto-core'),
                'type'               => Controls_Manager::TEXT,
                'default'            => esc_html__('top top', 'courto-core'),
                'description'        => esc_html__('You can write like: top top+=100', 'courto-core'),
                'frontend_available' => true,
                'render_type'        => 'none',
                'ai'                 => false,
                'condition'          => [
                    'css_transform_animation!' => 'yes',
                    'h_scroll_start_position'   => 'custom',
                ],
            ]
        );

        $this->add_responsive_control(
            'h_scroll_speed',
            array(
                'label'       => esc_html__('Scroll Speed', 'courto-core'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                'max'         => 100,
                'step'        => 1,
                'default'     => 4,
                'separator'   => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
            )
        );

        $this->add_responsive_control(
            'distance_last_slide',
            array(
                'label'       => esc_html__('Distance after last slide', 'courto-core'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 0,
                'max'         => 1000,
                'step'        => 10,
                'default'     => 0,
                'description' => esc_html__('Set the space (in pixels) after the final slide.', 'courto-core'),
                'separator'   => 'before',
                'condition' => [ 'css_transform_animation!' => 'yes' ],
            )
        );
        $this->add_control(
            'responsive_width',
            array(
                'label'     => esc_html__('Responsive Visibility', 'courto-core'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default'   => 'no',
                'separator' => 'before',
                'description' => esc_html__(
                    'Set the pixel width at which Horizontal Scroll will be disabled.',
                    'courto-core'
                ),
            )
        );
        $this->add_control(
            'responsive',
            array(
                'label'     => esc_html__('Width', 'courto-core'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 2500,
                'step'      => 5,
                'default'   => 300,
                'condition' => array(
                    'responsive_width' => 'yes',
                ),
            )
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id                   = $this->get_id();
        $sticky_template           = ! empty($_s['sticky_template']) ? $_s['sticky_template'] : 0;
        $opacity_scroll            = ! empty($_s['opacity_scroll']) ? 1 : 0;
        $sticky_section            = ! empty($_s['sticky_element']) ? 1 : 0;
        $distance_last_slide       = ! empty($_s['distance_last_slide']) ? $_s['distance_last_slide'] : '0';
        $distlastslide_tab         = ! empty($_s['distance_last_slide_tablet']) ? $_s['distance_last_slide_tablet'] : $distance_last_slide;
        $distlastslide_mob         = ! empty($_s['distance_last_slide_mobile']) ? $_s['distance_last_slide_mobile'] : $distlastslide_tab;
        $hscroll_speed             = ! empty($_s['h_scroll_speed']) ? $_s['h_scroll_speed'] : 4;
        $start_position            = ! empty($_s['h_scroll_start_position']) ? $_s['h_scroll_start_position'] : 'default';
        $custom_start_position     = ! empty($_s['h_scroll_custom_start_position']) ? $_s['h_scroll_custom_start_position'] : '';
        $hscroll_speed_tab         = ! empty($_s['h_scroll_speed_tablet']) ? $_s['h_scroll_speed_tablet'] : $hscroll_speed;
        $hscroll_speed_mob         = ! empty($_s['h_scroll_speed_mobile']) ? $_s['h_scroll_speed_mobile'] : $hscroll_speed_tab;
        $responsive                = ! empty($_s['responsive_width']) ? 1 : 0;
        $responsive_width          = ! empty($_s['responsive']) ? $_s['responsive'] : '';
        $bg_transition             = 0;
        $finaldata                 = '';

        $this->add_render_attribute('horizontal_scroll', 'id', 'wgl_horizontal_scroll_' . esc_attr(uniqid()));
        $this->add_render_attribute('horizontal_scroll', 'class',
            [
                'wgl-horizontal-scroll-wrapper',
                'wgl-horizontal-scroll-wrapper-' . esc_attr($id),
                !!$_s['css_transform_animation'] ? 'css_transform' : '',
            ]
        );

        $horizontal_data = array(
            'id'                   => $id,
            'responsive'           => $responsive,
            'responsiveWidth'      => $responsive_width,
        );

        if (! empty($opacity_scroll)) {
            $opacity_val     = ! empty($_s['opacity_val']) ? $_s['opacity_val'] : 0;
            $horizontal_data = array_merge(
                $horizontal_data,
                array(
                    'opacityScroll' => $opacity_scroll,
                    'opacityVal'     => $opacity_val,
                )
            );
        }

        if (! empty($distance_last_slide)) {
            $horizontal_data['distanceLastslide'] = $distance_last_slide;
            $horizontal_data['distlastslideTab']  = $distlastslide_tab;
            $horizontal_data['distlastslideMob']  = $distlastslide_mob;
        }

        if (! empty($hscroll_speed)) {
            $horizontal_data['speed']    = $hscroll_speed;
            $horizontal_data['speedTab'] = $hscroll_speed_tab;
            $horizontal_data['speedMob'] = $hscroll_speed_mob;
        }

        if(! empty($start_position)) {
            if('custom' === $start_position){
                $horizontal_data['startPosition'] = !empty($custom_start_position) ? $custom_start_position : $start_position;
            }else{
                $horizontal_data['startPosition'] = $start_position;
            }
        }

        $render_bg_transition = false;
        $render_html = '';

        if (!empty($_s[ 'items' ])) {
            $render_html = '<div class="wgl-horizontal_scroll_wrapper">';

            $bg_transition_render = '';

            foreach ($_s[ 'items' ] as $index => $item) {
                if (! empty($item['bg_transition'])) {
                    $render_bg_transition = true;
                    $bg_img = ! empty($item['image']['url']) ? $item['image']['url'] : '';
                    $r_id   = ! empty($item['_id']) ? $item['_id'] : '';
                    if (! empty($bg_img)) {
                        $bg_transition_render .= '<div class="bg-scroll elementor-repeater-item-' . esc_attr($r_id) . '" style="background-image:url(' . esc_url($bg_img) . ')"></div>';
                    } else {
                        $bg_transition_render .= '<div class="bg-scroll elementor-repeater-item-' . esc_attr($r_id) . '"></div>';
                    }
                }
            }
            if ($render_bg_transition && !$_s['css_transform_animation']) {
                $render_html .= '<div class="wgl-bg-horizontal-scroll-' . esc_attr($id) . ' wgl-bg-horizontal-scroll">';
                $render_html .= $bg_transition_render;
                $render_html .= '</div>';
            }

            foreach ($_s[ 'items' ] as $index => $item) {
				$width_desktop = (isset($item['item_width']['size']) && $item['item_width']['size'] !== '') 
					? $item['item_width']['size'] . 'vw' 
					: '';

				$width_tablet = (isset($item['item_width_tablet']['size']) && $item['item_width_tablet']['size'] !== '') 
					? $item['item_width_tablet']['size'] . 'vw' 
					: $width_desktop;

				$width_mobile = (isset($item['item_width_mobile']['size']) && $item['item_width_mobile']['size'] !== '') 
					? $item['item_width_mobile']['size'] . 'vw'
					: $width_tablet;

                $horizontal_scroll_items = $this->get_repeater_setting_key('horizontal_scroll_items', 'items', $index);
                $this->add_render_attribute($horizontal_scroll_items, [
                    'class' => [ 'wgl-horizontal_scroll_item', 'elementor-repeater-item-'.$item['_id'] ],
					'data-width-desktop'   => $width_desktop,
					'data-width-tablet'    => $width_tablet,
					'data-width-mobile'    => $width_mobile,
                ]);

                $render_html .= '<div ' . $this->get_render_attribute_string($horizontal_scroll_items) . '>';

                $wgl_frontend = new Frontend();
                $render_html .= $wgl_frontend->get_builder_content_for_display($item['content_templates']);

                $render_html .= '</div>';
            }
            $render_html .= '</div>';
        }
        if ($render_bg_transition) {
            $horizontal_data['bg_transition'] = 1;
        }

        $finaldata = ' data-result="' . htmlspecialchars(wp_json_encode($horizontal_data, true), ENT_QUOTES, 'UTF-8') . '"';
        $output    = '';

        if (! empty($sticky_section)) {

            $output  = '<div class="wgl-sticky-section-scroll wgl-sticky-section-' . esc_attr($id) . '">';

            if (!empty($sticky_template)) {
                $wgl_frontend = new Frontend();
                $output .= $wgl_frontend->get_builder_content_for_display($sticky_template);
            }

            $output .= '</div>';
        }

        $output .= '<div ' . $this->get_render_attribute_string('horizontal_scroll') . $finaldata . ' data-active-slide="0" >';

        if (! empty($render_html)) {
            $output .= $render_html;
        }

        $output .= '</div>';

        echo $output;
    }

    public function wpml_support_module()
    {
        add_filter('wpml_elementor_widgets_to_translate', [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter($widgets)
    {
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this,
            $widgets
        );
    }
}
