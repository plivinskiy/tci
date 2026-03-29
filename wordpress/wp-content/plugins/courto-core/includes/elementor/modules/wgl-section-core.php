<?php

namespace WGL_Extensions\Modules;

defined('ABSPATH') || exit;

use Elementor\{Controls_Manager, Group_Control_Background, Group_Control_Typography, Group_Control_Css_Filter, Repeater, Group_Control_Border, Plugin, Utils};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * WGL Elementor Section
 *
 *
 * @package courto-core\includes\elementor\modules
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Section_Core
{
    public $sections = [];

    public const CACHE_META_KEY = '_elementor_element_cache';

    public function __construct()
    {
        add_action('elementor/init', [$this, 'add_hooks']);
    }

    public function add_hooks()
    {
        if (!class_exists('WGL_Framework')) {
            return;
        }

        add_action('elementor/element/section/section_advanced/before_section_end', [$this, 'wgl_change_min_zindex'], 20, 2);
        add_action('elementor/element/column/section_advanced/before_section_end', [$this, 'wgl_change_min_zindex'], 20, 2);

        // Empowers overflow hidden
        add_action('elementor/element/section/section_layout/before_section_end', [$this, 'empowers_column_overflow'], 20, 2);
        add_action('elementor/element/container/section_layout_additional_options/before_section_end', [$this, 'add_clip_overflow'], 20, 2);

        add_action('elementor/element/common/_section_style/before_section_end', [$this, 'wgl_pointer_events'], 20, 2);
        add_action('elementor/element/section/section_advanced/before_section_end', [$this, 'wgl_pointer_events'], 20, 2);
        add_action('elementor/element/column/section_advanced/before_section_end', [$this, 'wgl_pointer_events'], 20, 2);
        add_action('elementor/element/container/section_layout_additional_options/before_section_end', [$this, 'wgl_pointer_events'], 10, 2);

        add_action('elementor/element/common/_section_responsive/before_section_end', [$this, 'wgl_hide_under'], 20, 2);

        add_filter('wgl_opacity_by_gradient_newest', '__return_true');

        // Add WGL extension control section to Section panel
        add_action('elementor/frontend/section/before_render', [$this, 'extended_row_render'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'extended_row_render'], 10, 1);

        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_animation_options'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_animation_options'], 10, 2);

        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_clip_path'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_clip_path'], 10, 2);

        // Add Compatible for new feature Element Caching
        if ('disable' !== get_option( 'elementor_element_cache_ttl', '' )) {
            add_filter('elementor/frontend/builder_content_data', [ $this, 'cache_page_elementor'], 10, 2);
        }

        add_filter('elementor/controls/animations/additional_animations', [ $this, 'add_extensions_animations']);

        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_scripts']);
    }


    public function wgl_change_min_zindex($element, $args)
    {
        /* @var \Elementor\Widget_Base $element */
        $element->update_control('z_index', [
            'min' => -9999,
        ]);
    }

    public function empowers_column_overflow($element, $args)
    {
        /* @var \Elementor\Widget_Base $element */
        $element->update_control('overflow', [
            'options' => [
                '' => esc_html__('Default', 'courto-core'),
                'hidden' => esc_html__('Hidden', 'courto-core'),
                'hidden !important' => esc_html__('Hidden Important', 'courto-core'),
                'clip' => esc_html__('Clip', 'courto-core'),
            ],
        ]);
    }
    public function add_clip_overflow($element, $args)
    {
        $control_data = $element->get_controls( 'overflow' );

        if ( isset( $control_data['options'] ) ) {
            $control_data['options']['clip'] = esc_html__( 'Clip', 'courto-core' );
            $element->update_control( 'overflow', $control_data );
        }
    }

    public function wgl_pointer_events($element, $args)
    {
        /* @var \Elementor\Widget_Base $element */
        $element->add_control(
            'wgl_pointer_events',
            [
                'label' => esc_html__('WGL Pointer Events', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'auto' => esc_html__('Auto', 'courto-core'),
                    'none' => esc_html__('None', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'pointer-events: {{VALUE}};',
                ],
            ]
        );

        $element->add_control(
            'wgl_easy_sticky',
            [
                'label' => esc_html__('WGL Easy Sticky', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );
        $element->add_responsive_control(
            'wgl_easy_sticky_enable',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['wgl_easy_sticky!' => ''],
                'options' => [
                    'sticky' => esc_html__('Sticky', 'courto-core'),
                    'relative' => esc_html__('Relative', 'courto-core'),
                ],
                'selectors_dictionary' => [
                    'sticky' => 'position: sticky; align-self: flex-start;',
                    'relative' => 'position: relative; align-self: unset;',
                ],
                'default' => 'sticky',
                'tablet_default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}};',
                ],
            ]
        );
        $element->add_responsive_control(
            'wgl_easy_sticky_offset_top',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => ['wgl_easy_sticky!' => ''],
                'size_units' => ['px', 'vw', 'vh', 'custom'],
                'range' => [ 'px' => [ 'min' => -100, 'max' => 1000 ] ],
                'default' => ['size' => 0],
                'tablet_default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $element->add_control(
            'wgl_backdrop_filter_popover_toggle',
            [
                'label' => esc_html__('WGL Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__('Default', 'courto-core'),
                'label_on' => esc_html__('Custom', 'courto-core'),
            ]
        );

        $element->start_popover();
        $element->add_control(
            'wgl_backdrop_filter_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Backdrop Filter may not work in the Elementor editor. Check the effect on the live site.', 'courto-core' ),
            ]
        );
        $element->add_control(
            'wgl_backdrop_filter_idle',
            [
                'label' => esc_html__('Blur Default', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container,
                     {{WRAPPER}} > .e-con-inner,
                     {{WRAPPER}}.e-con-full' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $element->add_control(
            'wgl_backdrop_filter_hover',
            [
                'label' => esc_html__('Blur on Hover', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-widget-container,
                     {{WRAPPER}}:hover > .e-con-inner,
                     {{WRAPPER}}.e-con-full:hover' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $element->add_control(
            'wgl_backdrop_filter_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container,
                     {{WRAPPER}} > .e-con-inner,
                     {{WRAPPER}}.e-con-full' => 'transition: {{SIZE}}s, transform var(--e-transform-transition-duration, {{SIZE}}s)',
                ],
            ]
        );
        $element->end_popover();

    }

    public function wgl_hide_under($element, $args)
    {
        /* @var \Elementor\Widget_Base $element */
        $element->add_control(
            'wgl_hide_el_under_enable',
            [
                'label' => esc_html__('Custom Responsive Visibility', 'courto-core'),
                'description' => esc_html__('Experimental Features from WGL', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );
        $element->add_control(
            'wgl_hide_el_under',
            [
                'label' => esc_html__('Hide Element Under (px)', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [ 'px' => [ 'min' => 280, 'max' => 2560 ] ],
                'condition' => ['wgl_hide_el_under_enable!' => ''],
                'default' => ['size' => 0],
                'selectors' => [
                    '/* {{WRAPPER}}' => '*/@media (max-width:{{wgl_hide_el_under.SIZE}}px){/*',
                    '*/ body:not(.e-preview--show-hidden-elements) {{WRAPPER}}' => 'display: none;',
                    'body.e-preview--show-hidden-elements {{WRAPPER}}' => 'background: repeating-linear-gradient(125deg, rgba(0, 0, 0, .1), rgba(0, 0, 0, .1) 1px, transparent 2px, transparent 9px); border: 1px solid rgba(0, 0, 0, .05); opacity: 0.5; filter: grayscale(1);',
                    '} {{WRAPPER}}' => 'display: block;',
                ],
            ]
        );
    }

    public function theme_section_settings_elementor($settings)
    {

        $new_arr = $cursor_keys = $stretch_section_keys = $parallax_section_keys = [];
        // Cursor
        if (!empty($settings['cursor_tooltip'])) {
            $cursor_keys = [
                'cursor_tooltip',
                'cursor_tooltip_type',
                'tooltip_text',
                'tooltip_descr',
                'cursor_thumbnail',
                'tooltip_thumbnail_width',
                'tooltip_thumbnail_radius',
                'cursor_color_bg',
                'tooltip_duration',
                'cursor_size',
                'cursor_follower_color_bg',
                'cursor_follower_size',
                'cursor_follower_duration',
                'cursor_link_animation',
                'tabs_cursor',
                'tabs_cursor_title',
                'tooltip_title',
                'tooltip_title_width',
                'tooltip_title_alignment',
                'tooltip_title_padding',
                'tooltip_title_radius',
                'tooltip_title_border',
                'tooltip_title_color',
                'tooltip_title_bg',
                'tooltip_descr',
                'tooltip_descr_width',
                'tooltip_descr_alignment',
                'tooltip_descr_padding',
                'tooltip_descr_radius',
                'tooltip_descr_border',
                'tooltip_descr_color',
                'tooltip_descr_bg',
                'cursor_tooltip_bg',
                'cursor_prop',
                'tooltip_bg_width',
                'tooltip_bg_height',
                'tooltip_bg_radius',
                'tooltip_bg_color',
                'tooltip_bg_blur',
            ];
        }

        $all_keys = array_unique(array_merge($cursor_keys, $stretch_section_keys, $parallax_section_keys));

        foreach ($all_keys as $key) {
            if (array_key_exists($key, $settings)) {
                $new_arr[$key] = $settings[$key];
            }
        }

        return $new_arr;
    }

    public function extended_row_render(\Elementor\Element_Base $element)
    {
        if (
            'section' !== $element->get_name()
            && 'container' !== $element->get_name()
        ) {
            // Bailout.
            return;
        }

        $settings = $element->get_settings();
        $data = $element->get_data();
        $settings = $this->theme_section_settings_elementor($settings);

        if (!empty($settings)) {
            $this->sections[$data['id']] = $settings;
        }
    }

    public function enqueue_scripts()
    {
        wp_localize_script(WGL_Globals::get_theme_slug() . '-theme-addons', 'wgl_section_settings', [
            $this->sections,
        ]);
    }

    public function extended_animation_options($widget, $args)
    {
        $this->extended_cursor($widget, $args);
    }

    public function extended_cursor($widget, $args)
    {
        /**
         * GENERAL -> CURSOR
         */

        $widget->start_controls_section(
            'extended_cursor_tooltip',
            [
                'label' => esc_html__('WGL Cursor Tooltip', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'cursor_tooltip',
            [
                'label' => esc_html__('Add Cursor Tooltip', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $widget->add_responsive_control(
            'cursor_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['cursor_tooltip' => 'yes']
            ]
        );
        $widget->add_control(
            'tooltip_transition',
            [
                'label' => esc_html__('Tooltip Transition (sec)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}' => '--transition: {{VALUE}}s;',
                ],
                'condition' => ['cursor_tooltip' => 'yes']
            ]
        );
        $widget->add_control(
            'tooltip_duration',
            [
                'label' => esc_html__('Tooltip Duration (ms)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => 0,
                'max' => 5,
                'step' => 0.01,
                'condition' => ['cursor_tooltip' => 'yes']
            ]
        );
        $widget->add_control(
            'cursor_prop',
            [
                'label' => esc_html__('Cursor Property', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['cursor_tooltip' => 'yes'],
                'default' => 'yes'
            ]
        );

        $widget->add_control(
            'cursor_tooltip_type',
            [
                'label' => esc_html__('Tooltip Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'simple' => esc_html__('Simple', 'courto-core'),
                    'custom' => esc_html__('Custom Text', 'courto-core'),
                    'image' => esc_html__('Image', 'courto-core'),
                ],
                'default' => 'simple',
                'condition' => ['cursor_tooltip' => 'yes']
            ]
        );

        $widget->add_control(
            'tooltip_text',
            [
                'label' => esc_html__('Tooltip Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'condition' => [
                    'cursor_tooltip_type' => 'custom',
                    'cursor_tooltip' => 'yes'
                ],
                'default' => esc_html__('View More', 'courto-core'),
                'label_block' => true,
            ]
        );

        $widget->add_control(
            'tooltip_descr',
            [
                'label' => esc_html__('Tooltip Description', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'condition' => [
                    'cursor_tooltip_type' => 'custom',
                    'cursor_tooltip' => 'yes'
                ],
                'label_block' => true,
            ]
        );

        $widget->add_control(
            'cursor_thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [
                    'cursor_tooltip_type' => 'image',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_thumbnail_width',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 500 ],
                ],
                'size_units' => [ 'px', 'vw', 'custom'],
                'condition' => [
                    'cursor_tooltip_type' => 'image',
                    'cursor_tooltip' => 'yes'
                ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_thumbnail_rotate',
            [
                'label' => esc_html__('Rotate Image', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'deg' => ['min' => 0, 'max' => 360],
                    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'size_units' => ['deg', 'turn'],
                'condition' => [
                    'cursor_tooltip_type' => 'image',
                    'cursor_tooltip' => 'yes'
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js' => '--wgl-cursor-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_control(
            'tooltip_thumbnail_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'cursor_tooltip_type' => 'image',
                    'cursor_tooltip' => 'yes',
                    'tooltip_thumbnail_rotate[size]!' => [0, ''],
                ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; background-color: {{VALUE}}; z-index: -1; transform: rotate(calc(-1 * var(--wgl-cursor-rotate, 0)));',
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js' => 'overflow: unset;',
                ],
            ]
        );

        $widget->add_responsive_control(
            'tooltip_thumbnail_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [
                    'cursor_tooltip_type' => 'image',
                    'cursor_tooltip' => 'yes'
                ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js img,
                     #wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'cursor_color_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );

        $widget->add_control(
            'cursor_size',
            [
                'label' => esc_html__('Cursor Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 500 ],
                ],
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );

        $widget->add_control(
            'cursor_follower_color_bg',
            [
                'label' => esc_html__('Cursor Follower Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );

        $widget->add_control(
            'cursor_follower_size',
            [
                'label' => esc_html__('Cursor Follower Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 500 ],
                ],
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );

        $widget->add_control(
            'cursor_follower_duration',
            [
                'label' => esc_html__('Follower Duration (ms)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => 0,
                'max' => 5,
                'step' => 0.01,
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
            ]
        );

        $widget->add_control(
            'cursor_link_animation',
            [
                'label' => esc_html__('Cursor Link Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none'       => esc_html__('None', 'courto-core'),
                    '1'          => esc_html__('Style 1', 'courto-core'),
                    '2'          => esc_html__('Style 2', 'courto-core'),
                    '3'          => esc_html__('Style 3', 'courto-core'),
                ],
                'condition' => [
                    'cursor_tooltip_type' => 'simple',
                    'cursor_tooltip' => 'yes'
                ],
                'default' => 'none'
            ]
        );

        $widget->start_controls_tabs(
            'tabs_cursor',
            [
                'condition' => [
                    'cursor_tooltip_type' => ['def', 'custom'],
                    'cursor_tooltip' => 'yes'
                ]
            ]
        );
        $widget->start_controls_tab(
            'tabs_cursor_title',
            ['label' => esc_html__('Title', 'courto-core')]
        );
        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_title',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6',
            ]
        );
        $widget->add_responsive_control(
            'tooltip_title_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_title_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
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
                'default' => 'left',
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_control(
            'tooltip_title_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tooltip_title_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__('Border Width', 'courto-core') ],
                    'color' => [ 'label' => esc_html__('Border Color', 'courto-core') ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6',
            ]
        );
        $widget->add_control(
            'tooltip_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'color: {{VALUE}}',
                ],
            ]
        );
        $widget->add_control(
            'tooltip_title_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js h6' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $widget->end_controls_tab();
        $widget->start_controls_tab(
            'tabs_cursor_descr',
            ['label' => esc_html__('Description', 'courto-core')]
        );
        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_descr',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr',
            ]
        );
        $widget->add_responsive_control(
            'tooltip_descr_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_descr_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
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
                'default' => 'left',
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $widget->add_responsive_control(
            'tooltip_descr_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_control(
            'tooltip_descr_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $widget->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tooltip_descr_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__('Border Width', 'courto-core') ],
                    'color' => [ 'label' => esc_html__('Border Color', 'courto-core') ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr',
            ]
        );
        $widget->add_control(
            'tooltip_descr_color',
            [
                'label' => esc_html__('Description Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'color: {{VALUE}}',
                ],
            ]
        );
        $widget->add_control(
            'tooltip_descr_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js .descr' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $widget->end_controls_tab();

        $widget->start_controls_tab(
            'tabs_cursor_bg',
            ['label' => esc_html__('Background', 'courto-core')]
        );

        $widget->add_control(
            'cursor_tooltip_bg',
            [
                'label' => esc_html__('Add Tooltip Background', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $widget->add_responsive_control(
            'tooltip_bg_width',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 1500 ],
                ],
                'size_units' => [ 'px', 'vw', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['cursor_tooltip_bg' => 'yes'],
            ]
        );

        $widget->add_responsive_control(
            'tooltip_bg_height',
            [
                'label' => esc_html__('Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 1500 ],
                ],
                'size_units' => [ 'px', 'vw', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['cursor_tooltip_bg' => 'yes'],
            ]
        );

        $widget->add_control(
            'tooltip_bg_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['cursor_tooltip_bg' => 'yes'],
            ]
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tooltip_bg_color',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before',
                'condition' => ['cursor_tooltip_bg' => 'yes'],
            ]
        );

        $widget->add_control(
            'tooltip_bg_blur',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.1],
                ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.cursor-from-js::before' => 'filter: blur({{SIZE}}px);',
                ],
                'condition' => ['cursor_tooltip_bg' => 'yes'],
            ]
        );

        $widget->end_controls_tab();
        $widget->end_controls_tabs();
        $widget->end_controls_section();
    }

    public function extended_clip_path($widget, $args)
    {
        /**
         * CLIP PATH
         */

        $widget->start_controls_section(
            'extended_clip_path',
            [
                'label' => esc_html__('WGL Clip Path', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'clip_path_pattern',
            [
                'label' => esc_html__('Select the Pattern', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'corner' => esc_html__('Clip the Corner', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => '',
                'prefix_class' => 'wgl-clip-path-',
            ]
        );

        $widget->add_responsive_control(
            'clip_path_corner',
            [
                'label' => esc_html__('Clip Size', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => ['clip_path_pattern' => 'corner'],
                'selectors' => [
                    '{{WRAPPER}}' => '--wgl-clip-size-top: {{TOP}}{{UNIT}}; --wgl-clip-size-right: {{RIGHT}}{{UNIT}}; --wgl-clip-size-bottom: {{BOTTOM}}{{UNIT}}; --wgl-clip-size-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $widget->add_control(
            'clip_path_custom',
            [
                'label' => esc_html__('Custom Clip Path', 'courto-core'),
                'description' => esc_html__('You can use Clip Path Generator from the Internet', 'courto-core'),
                'type' => Controls_Manager::CODE,
                'condition' => ['clip_path_pattern' => 'custom'],
                'language' => 'css',
                'render_type' => 'ui',
                'rows' => 14,
                'selectors' => [
                    '{{WRAPPER}}' => 'clip-path: {{VALUE}};',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    public function extractElements($array, $types = ['container', 'section', 'column'])
    {
        $result = [];

        foreach ($array as $element) {
            if (is_array($element)) {
                if (isset($element['elType']) && in_array($element['elType'], $types)) {
                    $result[] = $element;
                }

                if (isset($element['elements']) && is_array($element['elements'])) {
                    $result = array_merge($result, $this->extractElements($element['elements'], $types));
                }
            }
        }

        return $result;
    }

    public function get_json_meta($key, $ID)
    {
        $meta = get_post_meta($ID, $key, true);

        if (is_string($meta) && ! empty($meta)) {
            $meta = json_decode($meta, true);
        }

        if (empty($meta)) {
            $meta = [];
        }

        return $meta;
    }

    private function get_document_cache($post_id)
    {
        $cache = $this->get_json_meta(static::CACHE_META_KEY, $post_id);

        if (empty($cache['timeout'])) {
            return false;
        }

        if (current_time('timestamp') > $cache['timeout']) {
            return false;
        }

        if (! is_array($cache['value'])) {
            return false;
        }

        return $cache['value'];
    }

    public function cache_page_elementor($data, $post_id)
    {
        $cached_data = $this->get_document_cache($post_id);

        if (false === $cached_data) {
            return $data;
        }
        $extract_settings = $this->extractElements($data);
        foreach ($extract_settings as $extract_setting) {
            $setting_id = $extract_setting['id'] ?? '';
            if ($setting_id) {
                $settings = $this->theme_section_settings_elementor($extract_setting['settings']);

                // Cursor Extensions
                if (!empty($settings['cursor_tooltip'])) {
                    $settings['cursor_tooltip_type']        = $settings['cursor_tooltip_type'] ?? 'text';
                    $settings['tooltip_text']               = $settings['tooltip_text'] ?? esc_html__('View More', 'courto-core');
                    $settings['tooltip_descr']              = $settings['tooltip_descr'] ?? '';
                    $settings['cursor_thumbnail']           = $settings['cursor_thumbnail'] ?? ['url' => ''];
                    $settings['cursor_color_bg']            = $settings['cursor_color_bg'] ?? '';
                    $settings['cursor_size']                = $settings['cursor_size'] ?? '';
                    $settings['cursor_follower_color_bg']   = $settings['cursor_follower_color_bg'] ?? '';
                    $settings['cursor_follower_size']       = $settings['cursor_follower_size'] ?? '';
                    $settings['cursor_follower_duration']   = $settings['cursor_follower_duration'] ?? '';
                    $settings['cursor_link_animation']      = $settings['cursor_link_animation'] ?? 'none';
                    $settings['tooltip_duration']           = $settings['tooltip_duration'] ?? '';
                    $settings['tabs_cursor']                = $settings['tabs_cursor'] ?? '';
                    $settings['cursor_tooltip_bg']          = $settings['cursor_tooltip_bg'] ?? '';
                }

                if (!empty($settings)) {
                    $this->sections[$setting_id] = $settings;
                }
            }
        }
        return $data;
    }

    public function add_extensions_animations($animations)
    {
        $wgl_animations = [
            esc_html__('WGL Animations', 'courto-core') => [
                'wgl_fade_up'     => esc_html__('WGL Fade Up', 'courto-core'),
                'wgl_fade_right'  => esc_html__('WGL Fade Right', 'courto-core'),
                'wgl_fade_left'   => esc_html__('WGL Fade Left', 'courto-core'),
                'wgl_fade_down'   => esc_html__('WGL Fade Down', 'courto-core'),
                'wgl_fade'        => esc_html__('WGL Fade', 'courto-core'),
            ],
        ];

        $wgl_animations = apply_filters('wgl_elementor_additional_animations', $wgl_animations);

        return array_merge($animations, $wgl_animations);
    }
}
