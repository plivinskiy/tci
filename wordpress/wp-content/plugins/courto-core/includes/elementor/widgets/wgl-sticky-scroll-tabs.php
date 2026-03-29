<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-sticky-scroll-tabs.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Frontend,
    Group_Control_Background,
    Icons_Manager,
    Plugin,
    Utils,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Image_Size,
    Repeater};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper
};

class WGL_Sticky_Scroll_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-sticky-scroll-tabs';
    }

    public function get_title()
    {
        return esc_html__('WGL Sticky Scroll Tabs', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-sticky-scroll-tabs';
    }

    public function get_keywords()
    {
        return [ 'sticky scroll tabs', 'toggle', 'tabs', 'scroll', 'sticky' ];
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
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'courto-core')]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'ast_tab_title',
            [
                'label' => esc_html__('Sticky Scroll Tabs Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Sticky Scroll Tabs Title', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'ast_tab_icon_type',
            [
                'label' => esc_html__( 'Add Icon/Image', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__( 'None', 'courto-core' ),
                        'icon' => 'eicon-ban',
                    ],
                    'font' => [
                        'title' => esc_html__( 'Icon', 'courto-core' ),
                        'icon' => 'fa fa-smile',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'courto-core' ),
                        'icon' => 'far fa-image',
                    ]
                ],
                'default' => '',
            ]
        );
        $repeater->add_control(
            'ast_tab_icon_fontawesome',
            [
                'label' => esc_html__( 'Icon', 'courto-core' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'ast_tab_icon_type' => 'font',
                ],
                'description' => esc_html__( 'Select icon from Fontawesome library.', 'courto-core' ),
            ]
        );
        $repeater->add_control(
            'ast_tab_icon_thumbnail',
            [
                'label' => esc_html__( 'Image', 'courto-core' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [
                    'ast_tab_icon_type' => 'image',
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'ast_link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'ast_tab_title_pref',
            [
                'label' => esc_html__('Title Prefix', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'ast_content_type',
            [
                'label' => esc_html__('Content Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__('Content', 'courto-core'),
                    'template' => esc_html__('Saved Templates', 'courto-core'),
                ],
                'default' => 'content',
            ]
        );
        $repeater->add_control(
            'ast_content_templates',
            [
                'label' => esc_html__('Choose Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'ast_content_type' => 'template',
                ],
            ]
        );
        $repeater->add_control(
            'ast_content',
            [
                'label' => esc_html__('Sticky Scroll Tabs Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'courto-core'),
                'condition' => [
                    'ast_content_type' => 'content',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ast_tab_panel_bg_item',
                'seperator' => 'before',
                'types' => [ 'classic' ],
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => 'classic',
                        'type' => Controls_Manager::HIDDEN,
                    ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                    ],
                    'image' => [
                        'label' => esc_html__( 'Background Image', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-sticky-scroll-tabs_panel-inner::before',
            ]
        );
        $repeater->start_controls_tabs( 'repeater_ast_icon_tabs' );
        $repeater->start_controls_tab(
            'repeater_ast_icon_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $repeater->add_control(
            'repeater_ast_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-sticky-scroll-tabs_header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'repeater_ast_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-ast_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-ast_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'repeater_ast_icon_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $repeater->add_control(
            'repeater_ast_title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'repeater_ast_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();


        $this->add_control(
            'ast_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['ast_tab_title' => esc_html__('Title 1', 'courto-core')],
                    ['ast_tab_title' => esc_html__('Title 2', 'courto-core')],
                    ['ast_tab_title' => esc_html__('Title 3', 'courto-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{ast_tab_title}}',
            ]
        );

        $this->add_control(
            'responsive_width',
            array(
                'label'     => esc_html__('Responsive Visibility', 'courto-core'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default'   => 'yes',
                'separator' => 'before',
                'description' => esc_html__(
                    'Set the pixel width at which Sticky Scroll Tabs will be disabled.',
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
                'max'       => 99999999,
                'step'      => 1,
                'default'   => 1200,
                'condition' => array(
                    'responsive_width' => 'yes',
                ),
            )
        );
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'ast_wrapper_width',
            [
                'label' => esc_html__('Wrapper Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 2560 ],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel-inner' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ast_wrapper_distance',
            [
                'label' => esc_html__('Start Scroll Distance', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 500],
                ],
                'default' => ['size' => 80],
            ]
        );

        $this->add_responsive_control(
            'ast_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel .wgl-sticky-scroll-tabs_panel-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_tab_panel_gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel:not(:last-child) .wgl-sticky-scroll-tabs_panel-inner' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_tab_panel_delimiter',
            [
                'label' => esc_html__('Delimiter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 10],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel .wgl-sticky-scroll-tabs_panel-inner' => '--ast-delimiter-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'ast_tab_panel_delimiter_top',
            [
                'label' => esc_html__('Hide Delimiter on Top', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'condition' => ['ast_tab_panel_delimiter[size]!' => ['', 0]],
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel:first-child::before' => 'border-top: unset !important;',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}}.disable-rotation-yes .media-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}}.disable-rotation-tablet-yes .media-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}}.disable-rotation-mobile-yes .media-wrapper' => 'border-top: unset !important;',
                ],
            ]
        );
        $this->add_control(
            'ast_tab_panel_delimiter_bottom',
            [
                'label' => esc_html__('Hide Delimiter on Bottom', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['ast_tab_panel_delimiter[size]!' => ['', 0]],
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel:last-child::before' => 'border-bottom: unset !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_tab_panel_delimiter_pos',
            [
                'label' => esc_html__('Delimiter Position', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'allowed_dimensions' => 'horizontal',
                'condition' => ['ast_tab_panel_delimiter[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel::before' => 'right: {{RIGHT}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ast_tab_panel_overlay',
                'types' => [ 'classic', 'gradient', 'video', 'slideshow' ],
                'fields_options' => [
                    'background' => [ 'label' => esc_html__('Background Overlay', 'courto-core'), ],
                ],
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel::after',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ast_tab_panel_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel',
            ]
        );
        $this->add_control(
            'ast_tab_panel_delimiter_color_idle',
            [
                'label' => esc_html__('Delimiter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['ast_tab_panel_delimiter[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel::before' => '--ast-delimiter-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel:last-child::before' => '--ast-delimiter-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_tab_panel_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ast_tab_panel_overlay_opacity_idle',
            [
                'label' => esc_html__('Background Overlay Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'ast_tab_panel_overlay_background' => [ 'classic', 'gradient', 'video', 'slideshow' ]
                ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> Heading
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_heading',
            [
                'label' => esc_html__('Heading', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'ast_heading_width',
            [
                'label' => esc_html__('Heading Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 2560 ],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ast_heading_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__('Title Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
                'options' => [
                    'left; text-align: left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center; text-align: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right; text-align: right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between; text-align: justify' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left; text-align: left',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ast_heading_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ast_title_typo',
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_header',
            ]
        );

        $this->add_control(
            'ast_title_tag',
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
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'h4',
            ]
        );

        $this->add_control(
            'ast_title_font_family',
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
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->start_controls_tabs('ast_header_tabs');

        $this->start_controls_tab(
            'ast_header_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'ast_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.45),
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ast_title_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_title_padding_idle',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_title_margin_idle',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ast_title_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ast_title_border',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_header',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_header',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'ast_header_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'ast_title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ast_title_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_title_padding_hover',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ast_title_margin_hover',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ast_title_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ast_title_border_hover',
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ast_title_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE PREFIX
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title_pref',
            [
                'label' => esc_html__('Title Prefix', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ast_title_pref_typo',
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_title-prefix',
            ]
        );

        $this->add_control(
            'title_pref_position',
            [
                'label' => esc_html__('Prefix Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'relative' => esc_html__('Default', 'courto-core'),
                    'absolute' => esc_html__('Absolute', 'courto-core'),
                ],
                'default' => 'relative',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_title-prefix' => 'position: {{VALUE}}; top: 0; left: 0;',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_pref_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_title-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('ast_header_pref_tabs');
        $this->start_controls_tab(
            'ast_header_pref_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'ast_title_pref_color_idle',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel .wgl-sticky-scroll-tabs_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'ast_header_pref_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_pref_color_hover',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel:hover .wgl-sticky-scroll-tabs_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'ast_header_pref_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'ast_title_pref_color_active',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_panel.active .wgl-sticky-scroll-tabs_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ast_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-ast_icon:not(.wgl-ast_icon-image)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

         $this->add_responsive_control(
            'ast_tabs_icon_position',
            [
                'label' => esc_html__( 'Icon/Image Position', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'column-reverse' => [
                        'title' => esc_html__( 'Top', 'courto-core' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'row' => [
                        'title' => esc_html__( 'Right', 'courto-core' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Bottom', 'courto-core' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__( 'Left', 'courto-core' ),
                        'icon' => 'eicon-h-align-left',
                    ]
                ],
                'default' => 'column-reverse',
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-ast_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'ast_icon_tabs' );
        $this->start_controls_tab(
            'ast_icon_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'ast_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-ast_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-ast_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'ast_icon_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'ast_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_header:hover .wgl-sticky-scroll-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ast_content_typo',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [ 'default' => [ 'size' => 18, 'unit' => 'px' ] ],
                    'line_height' => [ 'default' => [ 'size' => 1.6667, 'unit' => 'em' ] ],
                ],
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content',
            ]
        );
        $this->add_responsive_control(
            'ast_content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '44',
                    'right' => '70',
                    'bottom' => '63',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '22',
                    'right' => '10',
                    'bottom' => '34',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ast_content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ast_content_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ast_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ast_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ast_content_border',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 0,
                            'right' => 0,
                            'bottom' => 1,
                            'left' => 0,
                        ],
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                        'default' => WGL_Globals::get_h_font_color(),
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-sticky-scroll-tabs_content .content',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $responsive                = ! empty($_s['responsive_width']) ? 1 : 0;
        $responsive_width          = ! empty($_s['responsive']) ? $_s['responsive'] : '';

        $this->add_render_attribute(
            'sticky_scroll_tabs',
            [
                'class' => [
                    'wgl-sticky-scroll-tabs',
                ],
                'id' => 'wgl-sticky-scroll-tabs-' . esc_attr($this->get_id()),
                'data-distance' => esc_attr($_s['ast_wrapper_distance']['size']),
                'data-responsive' => esc_attr($responsive),
                'data-responsive-width' => esc_attr($responsive_width)
            ]
        );

        echo '<div ', $this->get_render_attribute_string('sticky_scroll_tabs'), '>';
        echo '<div class="wgl-sticky-scroll-tabs__wrapper">';
        foreach ($_s['ast_tab'] as $index => $item) :

            $icon_output = '';
            if ( !empty( $item['ast_tab_icon_type'] ) ) {
                if ( $item['ast_tab_icon_type'] === 'font' && !empty( $item['ast_tab_icon_fontawesome'] ) ) {
                    $icon_font = $item['ast_tab_icon_fontawesome'];
                    $migrated  = isset( $item['__fa4_migrated'][ $icon_font ] );
                    $is_new    = Icons_Manager::is_migration_allowed();
                    if ( $is_new || $migrated ) {
                        if ( Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) || 'svg' === $icon_font['library'] ) {
                            ob_start();
                            Icons_Manager::render_icon( $item['ast_tab_icon_fontawesome'], [ 'aria-hidden' => 'true' ] );
                            $icon_output = ob_get_clean();
                            $icon_output = '<span class="wgl-ast_icon elementor-icon">' . $icon_output . '</span>';
                        } else {
                            ob_start();
                            Icons_Manager::render_icon(
                                $item['ast_tab_icon_fontawesome'],
                                [
                                    'class'        => 'wgl-ast_icon elementor-icon',
                                    'aria-hidden'  => 'true',
                                ]
                            );
                            $icon_output = ob_get_clean();
                        }
                    } else {
                        $icon_output = '<i class="wgl-sticky-scroll-tabs_icon elementor-icon ' . esc_attr( $icon_font ) . '"></i>';
                    }
                }

                if ( $item['ast_tab_icon_type'] === 'image' && !empty( $item['ast_tab_icon_thumbnail']['url'] ) ) {
                    $this->add_render_attribute(
                        'thumbnail',
                        [
                            'src'   => $item['ast_tab_icon_thumbnail']['url'],
                            'alt'   => Control_Media::get_image_alt( $item['ast_tab_icon_thumbnail'] ),
                            'title' => Control_Media::get_image_title( $item['ast_tab_icon_thumbnail'] ),
                        ]
                    );

                    $icon_output = sprintf(
                        '<span class="wgl-ast_icon wgl-ast_icon-image">%s</span>',
                        Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'ast_tab_icon_thumbnail' )
                    );
                }
            }

            // Link
            $ast_link = '';
            if(!empty($item['ast_link']['url'])){
                $link = $this->get_repeater_setting_key('link', 'list', $index);
                $this->add_link_attributes($link, $item['ast_link']);
                $ast_link = '<a class="wgl-sticky-scroll-tabs_title-link" '.$this->get_render_attribute_string($link).'></a>';
            }

            $tab_count = $index + 1;

            $tab_title_key = $this->get_repeater_setting_key('ast_tab_title', 'ast_tab', $index);

            $this->add_render_attribute(
                $tab_title_key,
                [
                    'class' => [
                        'wgl-sticky-scroll-tabs_panel',
                        'elementor-repeater-item-'. $item['_id'],
                    ],
                    'id' => 'wgl-sticky-scroll-tabs_panel-' . $id_int . $tab_count,
                ]
            );

            echo '<div ', $this->get_render_attribute_string($tab_title_key), '>';
                echo '<div class="wgl-sticky-scroll-tabs_panel-inner">';
                    echo '<', $_s['ast_title_tag'], ' class="wgl-sticky-scroll-tabs_header">';

                        if (!empty($ast_link)) {
                            echo $ast_link;
                        }

                        echo '<span class="wgl-sticky-scroll-tabs_title">';
                        if (!empty($item['ast_tab_title_pref'])) {
                            echo '<span class="wgl-sticky-scroll-tabs_title-prefix">',
                                $item['ast_tab_title_pref'],
                                '</span>';
                        }
                        echo $item['ast_tab_title'];
                        echo '</span>'; // _title

                        echo $icon_output;
                    echo '</', $_s['ast_title_tag'], '>';

                    echo '<div class="wgl-sticky-scroll-tabs_content">';
                        echo '<div class="content">';
                        if ($item['ast_content_type'] == 'content') {
                            echo do_shortcode($item['ast_content']);
                        } elseif ($item['ast_content_type'] == 'template') {
                            $id = $item['ast_content_templates'];
                            $wgl_frontend = new Frontend;
                            echo $wgl_frontend->get_builder_content_for_display($id);
                        }
                        echo '</div>';

                    echo '</div>';

                echo '</div>';

            echo '</div>';

        endforeach;
        echo '</div>';
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