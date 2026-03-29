<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-highlight-board.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Plugin,
    Utils,
    Widget_Base,
    Controls_Manager,
    Icons_Manager,
    Control_Media,
    Group_Control_Image_Size,
    Repeater,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Background};

use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper,
    Includes\WGL_Cursor,
};

class WGL_Highlight_Board extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-highlight-board';
    }

    public function get_title()
    {
        return esc_html__('WGL Highlight Board', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-highlight-board';
    }

    public function get_keywords()
    {
        return [ 'highlight', 'board', 'title' ];
    }

    public function get_script_depends()
    {
        return [
            'wgl-widgets',
        ];
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

        $repeater = new Repeater();
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_attr__( 'https://your-link.com', 'courto-core' ),
                'default' => [ 'url' => '#' ],
            ]
        );
        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Highlight Board Title', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'link_active',
            [
                'label' => esc_html__( 'Active by Default', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        WGL_Cursor::repeater_init(
            $repeater,
            [
                'section' => false,
                'repeater' => true,
                'prefix' => 'highlight-board_',
            ]
        );
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'link_active' => 'yes',
                        'title' => esc_html__( 'Indoor Court', 'courto-core' ),
                    ],
                    [ 'title' => esc_html__( 'Outdoor Court', 'courto-core' ) ],
                    [ 'title' => esc_html__( 'Panoramic Court', 'courto-core' ) ],
                    [ 'title' => esc_html__( 'Single Court', 'courto-core' ) ],
                    [ 'title' => esc_html__( 'Portable Court', 'courto-core' ) ],
                ],
            ]
        );
        $this->add_responsive_control(
            'items_align_items',
            [
                'label' => esc_html__( 'Align Items', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'style_transfer' => true,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .hlb__item' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Text Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'style_transfer' => true,
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
                'selectors' => [
                    '{{WRAPPER}} .hlb__item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'force_link',
            [
                'label' => esc_html__('Force Link for Mobile', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .hlb__link' => 'height: 100% !important;',
                ],
            ]
        );
        $this->add_mobile_breakpoint();
        $this->end_controls_section();


        /**
         * STYLE -> ITEM CONTAINER
         */

        $this->start_controls_section(
            'style_item_container',
            [
                'label' => esc_html__('Item Container', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__( 'Items Gap', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => ['px', 'custom'],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'default' => ['size' => 8, 'unit' => 'px'],
                'mobile_default' => ['size' => 35, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-highlight-board' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_divider',
            [
                'label' => esc_html__( 'Divider Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => ['px'],
                'range' => [ 'px' => ['min' => 0, 'max' => 10] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-highlight-board' => '--divider-width: {{SIZE}}px; --divider-opacity: 1;',
                ],
            ]
        );
        $this->add_control(
            'item_divider_top',
            [
                'label' => esc_html__('Hide Divider on Top', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['item_divider[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:first-child::after' => 'border-top: unset !important;',
                ],
            ]
        );
        $this->add_control(
            'item_divider_bottom',
            [
                'label' => esc_html__('Hide Divider om Bottom', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['item_divider[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:last-child::after' => 'border-bottom: unset !important;',
                ],
            ]
        );
        $this->add_control(
            'item_divider_position',
            [
                'label' => esc_html__('Divider Position', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => ['top', 'right', 'left'],
                'size_units' => ['px', '%', 'vw', 'custom'],
                'default' => [
                    'bottom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item::after' => 'top: min({{top}}{{UNIT}}, 0px); right: {{RIGHT}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .hlb__item:last-child::after' => 'bottom: min({{top}}{{UNIT}}, 0px);',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_divider_z_index',
            [
                'label' => esc_html__( 'Divider Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => -5,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}} .hlb__item::after' => 'z-index: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'item_tabs' );
        $this->start_controls_tab(
            'item_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_idle',
                'selector' => '{{WRAPPER}} .hlb__background::before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_idle',
                'selector' => '{{WRAPPER}} .hlb__item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_idle',
                'selector' => '{{WRAPPER}} .hlb__item',
            ]
        );
        $this->add_control(
            'item_divider_color_idle',
            [
                'label' => esc_html__('Divider Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['item_divider[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item' => '--divider-color: {{VALUE}}; --divider-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_tab_hover',
            ['label' => esc_html__('Hover/Active' , 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_hover',
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(),
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 90,
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .hlb__background::after',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'selector' => '{{WRAPPER}} .hlb__item:is(.active, :hover)',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'selector' => '{{WRAPPER}} .hlb__item:is(.active, :hover)'
            ]
        );
        $this->add_control(
            'item_divider_color_hover',
            [
                'label' => esc_html__('Divider Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['item_divider[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover),
                     {{WRAPPER}} .hlb__item:is(.active, :hover) + .hlb__item' => '--divider-color: {{VALUE}};',
                    '{{WRAPPER}} .hlb__item:last-child:is(.active, :hover)' => '--divider-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.4],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item' => 'transition: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_tab_responsive',
            ['label' => esc_html__('Responsive' , 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__background::after,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__background::after,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__background::after,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__background::after',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item'
            ]
        );
        $this->add_control(
            'item_divider_color_responsive',
            [
                'label' => esc_html__('Divider Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['item_divider[size]!' => ['', 0]],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item' => '--divider-color: {{VALUE}}; --divider-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> Title
         */

        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_font_title',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 'clamp(32px, 6vw, 96px)', 'unit' => 'custom'],
                        'mobile_default' => ['size' => 'clamp(32px, 6vw, 96px)', 'unit' => 'custom'],
                    ],
                    'line_height' => ['default' => ['size' => 1, 'unit' => 'em']],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .hlb__title',
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
                    '{{WRAPPER}} .hlb__title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__( 'Title Width', 'courto-core' ),
                'description' => esc_html__( 'Title Width has Priority', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', '%', 'vw', 'custom'],
                'mobile_default' => ['size' => 100, 'unit' => '%'],
                'range' => [ 'px' => ['max' => 1200] ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'title_tabs' );
        $this->start_controls_tab(
            'title_color_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.2),
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'title_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'title_stroke_size[size]!' => ['', 0] ],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew_idle',
            [
                'label' => esc_html__('Skew the title', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => ['min' => -45, 'max' => 45],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'transform: skew({{SIZE}}deg)',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_pos_idle',
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
                'default' => [ 'size' => 0, 'unit' => '%' ],
                'mobile_default' => [ 'size' => 0, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_tab_hover',
            ['label' => esc_html__('Hover/Active' , 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_size[size]!' => ['', 0] ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew_hover',
            [
                'label' => esc_html__('Skew the title', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => ['min' => -45, 'max' => 45],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__title' => 'transform: skew({{SIZE}}deg)',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_pos_hover',
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
                'default' => [ 'size' => 0, 'unit' => '%' ],
                'mobile_default' => [ 'size' => 0, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__title' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'title_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.4],
                'selectors' => [
                    '{{WRAPPER}} .hlb__title' => 'transition: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_tab_responsive',
            ['label' => esc_html__('Responsive' , 'courto-core')]
        );
        $this->add_control(
            'title_color__responsive',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__title' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'title_bg_responsive',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color_responsive',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'title_stroke_size[size]!' => ['', 0] ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew_responsive',
            [
                'label' => esc_html__('Skew the title', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => ['min' => -45, 'max' => 45],
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__title' => 'transform: skew({{SIZE}}deg)',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        
        /**
         * STYLE -> Subtitle
         */

        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_font_subtitle',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 15],
                        'tablet_default' => ['size' => 14],
                    ],
                    'line_height' => ['default' => ['size' => 1.1, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => 0, 'unit' => 'em']],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .hlb__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_font',
            [
                'label' => esc_html__('Theme Font Family', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'header' => esc_html__('Headings Font', 'courto-core'),
                    'content' => esc_html__('Content Font', 'courto-core'),
                    'additional' => esc_html__('Additional Font', 'courto-core'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '18',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '10',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'subtitle_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .hlb__subtitle',
            ]
        );
        $this->add_responsive_control(
            'subtitle_width',
            [
                'label' => esc_html__( 'Subtitle Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', '%', 'vw', 'custom'],
                'range' => [
                    'px' => ['max' => 1200],
                    '%' => ['step' => 0.5],
                    'vw' => ['step' => 0.5],
                ],
                'separator' => 'before',
                'default' => [
                    'size' => 'clamp(200px, 15%, 250px)',
                    'unit' => 'custom',
                ],
                'mobile_default' => [
                    'size' => '100',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'subtitle_tabs' );
        $this->start_controls_tab(
            'subtitle_tab',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );
        $this->add_control(
            'subtitle_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0),
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'subtitle_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'subtitle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__subtitle' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'subtitle_hover_tab',
            ['label' => esc_html__('Hover/Active' , 'courto-core')]
        );
        $this->add_control(
            'subtitle_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__subtitle' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'subtitle_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'subtitle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__subtitle' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'subtitle_tab_responsive',
            ['label' => esc_html__('Responsive' , 'courto-core')]
        );
        $this->add_control(
            'subtitle_responsive',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__subtitle' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color_responsive',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__subtitle' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'subtitle_border_color_responsive',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'subtitle_border_border!' => ['', 'none'] ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__subtitle' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /**
         * STYLE -> IMAGE
         */
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'courto-core'),
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'courto-core'),
                    '300' => esc_html__('300x300 - Medium', 'courto-core'),
                    '768' => esc_html__('768x768 - Medium Large', 'courto-core'),
                    '1024' => esc_html__('1024x1024 - Large', 'courto-core'),
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => 'full',
            ]
        );
        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'courto-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [ 'img_size_string' => 'custom' ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'courto-core'),
            ]
        );
        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('No Crop', 'courto-core'),
                    '1:1' => esc_html__('1:1', 'courto-core'),
                    '3:2' => esc_html__('3:2', 'courto-core'),
                    '4:3' => esc_html__('4:3', 'courto-core'),
                    '6:5' => esc_html__('6:5', 'courto-core'),
                    '9:16' => esc_html__('9:16', 'courto-core'),
                    '16:9' => esc_html__('16:9', 'courto-core'),
                    '21:9' => esc_html__('21:9', 'courto-core'),
                ],
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'image_custom_height',
            [
                'label' => esc_html__( 'Image Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet' ],
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => [ 'px', 'vh', 'vw', 'custom'],
                'default' => [ 'size' => 172 ],
                'tablet_default' => [ 'size' => 90 ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__gallery-inner' => '--height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sticky_area',
            [
                'label' => esc_html__('Increase the Sticky Area', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet' ],
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '13',
                    'right' => '0',
                    'bottom' => '13',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__gallery' => 'inset: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_custom_width',
            [
                'label' => esc_html__( 'Image Width (only for mobile)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'devices' => [ 'mobile' ],
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => [ 'px', 'vh', 'vw', 'custom'],
                'mobile_default' => [ 'size' => 45, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item .hlb__image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_full_width_wrapper',
            [
                'label' => esc_html__('Move Image to Full-Width Wrapper (only for mobile)', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .hlb__item_inner' => 'flex-wrap: wrap',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'mobile' ],
                'size_units' => ['px', '%', 'custom'],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item .hlb__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .hlb__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .hlb__image',
            ]
        );

        $this->start_controls_tabs('image');
        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'image_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__image' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .hlb__image',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover/Active', 'courto-core')]
        );
        $this->add_control(
            'image_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__image' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .hlb__item:is(.active, :hover) .hlb__image',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_responsive',
            ['label' => esc_html__('Responsive', 'courto-core')]
        );
        $this->add_control(
            'image_border_color_responsive',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__image,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__image,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__image,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__image' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .hlb__item .hlb__image,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .hlb__item .hlb__image,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .hlb__item .hlb__image,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .hlb__item .hlb__image',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        
        WGL_Cursor::repeater_style_init(
            $this,
            [
                'section' => false,
                'repeater' => true,
                'prefix' => 'highlight-board_',
            ]
        );
    }

    protected function add_mobile_breakpoint() {
        // The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
        $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
        $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $avaliable_breakpoints = [];
        foreach ( $active_devices as $breakpoint_key ) {
            if( array_key_exists($breakpoint_key, $active_breakpoints) ) {
                $avaliable_breakpoints[$breakpoint_key] = $active_breakpoints[$breakpoint_key]->get_label();
            }
        }

        $this->add_control(
            'wgl_mobile_breakpoint',
            [
                /* translators: %s: Device Name. */
                'label' => esc_html__( 'Set Mobile Template On', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => $avaliable_breakpoints + [ '' => esc_html__('Disable', 'courto-core') ],
                'default' => 'mobile',
            ]
        );
    }

    protected function render()
    {
        // Build structure
        $_s = $this->get_settings_for_display();

        $kses_allowed_html = [
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'small' => ['class' => true, 'style' => true]
        ];

        // Variables validation
        $img_size_string = $_s['img_size_string'] ?? '';
        $img_size_array = $_s['img_size_array'] ?? [];
        $img_aspect_ratio = $_s['img_aspect_ratio'] ?? '';

        $hl_board__wrapper = $gallery = '';
        foreach ($_s['items'] as $index => $item) {

            // Fields validation
            $title = $item['title'] ?? '';
            $subtitle = $item['subtitle'] ?? '';
            $link = $item['link'] ?? '';

            if (!$title) return;

            $has_link = !empty($link['url']);

            if ($has_link) {
                $link = $this->get_repeater_setting_key('link', 'items', $index);
                $this->add_link_attributes($link, $item['link']);
            }

            //Cursor
            if (isset($item['highlight-board_cursor_tooltip']) && '' != $item['highlight-board_cursor_tooltip']) {
                add_filter( 'wgl/courto_module_cursor', function () { return true; });
            }
            $cursor = new WGL_Cursor;
            $cursor_data = $cursor->build($this, array_merge($_s, $item), $item['_id'], 'highlight-board_');

            $hl_board__item = $this->get_repeater_setting_key( 'item_link_active', 'items', $index );
            $this->add_render_attribute( $hl_board__item, [
                'class' => [
                    'hlb__item',
                    'elementor-repeater-item-'. $item['_id'],
                    $item[ 'link_active' ] ? 'active' : '',
                    isset($item['highlight-board_cursor_tooltip']) && !empty($item['highlight-board_cursor_tooltip']) ? 'wgl-cursor-text' : ''
                ],
            ] );

            //* Image size
            $image = '';
            if($thumbnail = $item['thumbnail']){
                $dim = null;
                $image_data = wp_get_attachment_image_src($thumbnail['id'], 'full');

                if ($image_data) {
                    $dim = WGL_Elementor_Helper::get_image_dimensions(
                        $img_size_array ?: $img_size_string,
                        $img_aspect_ratio,
                        $image_data
                    );
                }
                if($dim){
                    $image_url = aq_resize($image_data[0], $dim['width'], $dim['height'], true, true, true) ?: $image_data[0];

                    $this->add_render_attribute('image' . $index, [
                        'class' => 'image',
                        'src' => $image_url,
                        'alt' => get_post_meta($thumbnail['id'], '_wp_attachment_image_alt', true)
                    ]);

                    $this->add_render_attribute('hlb__image' . $index, [
                        'class' => 'hlb__image hlb__image-' . $index + 1,
                    ]);

                    $image .= '<span '.$this->get_render_attribute_string('hlb__image' . $index).'>';
                        $image .= '<img '. $this->get_render_attribute_string('image' . $index). '>';
                    $image .= '</span>';
                }
            }

            $title = '<' . esc_attr($_s['title_tag']) . ' class="hlb__title">' . wp_kses($title, $kses_allowed_html) . '</' . esc_attr($_s['title_tag']) . '>';
            $subtitle = !empty($subtitle) ? '<span class="hlb__subtitle">'. wp_kses($subtitle, $kses_allowed_html) .'</span>' : '';

            $hl_board__wrapper .= '<div '. $this->get_render_attribute_string( $hl_board__item ). ' ' . $cursor_data . '>';

                $hl_board__wrapper .= '<div class="hlb__item_inner">';

                    $hl_board__wrapper .= '<div class="hlb__background"></div>';
                    $hl_board__wrapper .= $has_link ? '<a class="hlb__link" ' . $this->get_render_attribute_string($link) . '></a>' : '';

                    $hl_board__wrapper .= $image;

                    $hl_board__wrapper .= '<div class="hlb__content">';
                        $hl_board__wrapper .= $subtitle;
                        $hl_board__wrapper .= $title;
                    $hl_board__wrapper .= '</div>';

                $hl_board__wrapper .= '</div>';

            $hl_board__wrapper .= '</div>';

            $gallery .= $image;
        }

        $this->add_render_attribute( 'general', 'class', 'wgl-highlight-board' );

        if (!empty($_s['wgl_mobile_breakpoint'])){
            $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();
            $this->add_render_attribute( 'general', [
                'data-breakpoint' => $active_breakpoints[ $_s['wgl_mobile_breakpoint'] ]->get_value()
            ] );

            $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
            $key = array_search($_s['wgl_mobile_breakpoint'], $active_devices);
            $all_breakpoints = array_slice($active_devices, $key);
            foreach($all_breakpoints as $breakpoint){
                $this->add_render_attribute( 'general', [ 'class' => [ 'breakpoint_on-' . $breakpoint ] ] );
            }
        }

        ?><div <?php echo $this->get_render_attribute_string('general') ?>><?php
            echo $hl_board__wrapper;
            echo $gallery ? '<div class="hlb__gallery"><div class="hlb__gallery-inner">'.$gallery.'</div></div>' : '';
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
