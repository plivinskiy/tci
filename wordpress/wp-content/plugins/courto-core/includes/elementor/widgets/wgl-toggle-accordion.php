<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-toggle-accordion.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Frontend,
    Group_Control_Background,
    Icons_Manager,
    Plugin,
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Repeater};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper
};


class WGL_Toggle_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-toggle-accordion';
    }

    public function get_title()
    {
        return esc_html__('WGL Toggle/Accordion', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-toggle-accordion';
    }

    public function get_keywords()
    {
        return [ 'accordion', 'toggle' ];
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
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'acc_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'accordion' => esc_html__('Accordion', 'courto-core'),
                    'toggle' => esc_html__('Toggle', 'courto-core'),
                ],
                'default' => 'toggle',
            ]
        );

        $this->add_control(
            'acc_trigger',
            [
                'label' => esc_html__('Trigger', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['acc_type' => 'toggle'],
                'options' => [
                    'click' => esc_html__('Click', 'courto-core'),
                    'hover' => esc_html__('Hover', 'courto-core'),
                ],
                'default' => 'click',
            ]
        );

        $this->add_control(
            'acc_overflow',
            [
                'label' => esc_html__('Overflow', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'courto-core'),
                    'overflow: visible;' => esc_html__('Visible', 'courto-core'),
                    'overflow: hidden;' => esc_html__('Hidden', 'courto-core'),
                ],
                'default' => 'overflow: hidden;',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => esc_html__('Icon Settings', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_acc_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'plus' => esc_html__('Plus/Minus', 'courto-core'),
                    'plus_bold' => esc_html__('Plus/Minus Bold', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => 'plus',
            ]
        );

        $this->add_control(
            'acc_icon',
            [
                'label' => esc_html__('Choose Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['enable_acc_icon' => 'custom'],
                'fa4compatibility' => 'icon',
                'recommended' => [
                    'fa-solid' => [
                        'caret-right',
                        'caret-down',
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                    ],
                    'fa-regular' => [
                        'caret-square-down',
                    ],
                ],
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-arrow-right',
                ],
            ]
        );

        $this->add_control(
            'icon_alignment',
            [
                'label' => esc_html__('Icon Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'order: 1;',
                    'right' => 'order: 0; flex-grow: 1;',
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_title' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_v_alignment',
            [
                'label' => esc_html__('Icon Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'courto-core')]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'acc_tab_title',
            [
                'label' => esc_html__('Accordion Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Accordion Title', 'courto-core'),
            ]
        );
        $repeater->add_control(
            'acc_link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'acc_tab_title_pref',
            [
                'label' => esc_html__('Title Prefix', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'acc_tab_def_active',
            [
                'label' => esc_html__('Active as Default', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $repeater->add_control(
            'acc_content_type',
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
            'acc_content_templates',
            [
                'label' => esc_html__('Choose Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'acc_content_type' => 'template',
                ],
            ]
        );
        $repeater->add_control(
            'acc_content',
            [
                'label' => esc_html__('Accordion Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'courto-core'),
                'condition' => [
                    'acc_content_type' => 'content',
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'acc_tab_panel_bg_item',
                'types' => [ 'classic' ],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .wgl-accordion_panel-inner::before',
            ]
        );
        $repeater->add_responsive_control(
            'acc_tab_panel_bg_item_xpos_active',
            [
                'label' => esc_html__( 'X Position on Active', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'separator' => 'before',
                'responsive' => true,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -800, 'max' => 800 ],
                    'em' => [ 'min' => -100, 'max' => 100 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'acc_tab_panel_bg_item_background' => 'classic',
                    'acc_tab_panel_bg_item_position' => 'initial',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.active .wgl-accordion_panel-inner::before' => 'background-position: {{SIZE}}{{UNIT}} {{acc_tab_panel_bg_item_ypos_active.SIZE}}{{acc_tab_panel_bg_item_ypos_active.UNIT}}',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'acc_tab_panel_bg_item_ypos_active',
            [
                'label' => esc_html__( 'Y Position on Active', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'responsive' => true,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -800, 'max' => 800 ],
                    'em' => [ 'min' => -100, 'max' => 100 ],
                    '%' => [ 'min' => -100, 'max' => 100 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'acc_tab_panel_bg_item_background' => 'classic',
                    'acc_tab_panel_bg_item_position' => 'initial',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.active .wgl-accordion_panel-inner::before' => 'background-position: {{acc_tab_panel_bg_item_xpos_active.SIZE}}{{acc_tab_panel_bg_item_xpos_active.UNIT}} {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'acc_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['acc_tab_title' => esc_html__('Accordion Title 1', 'courto-core'), 'acc_tab_def_active' => 'yes'],
                    ['acc_tab_title' => esc_html__('Accordion Title 2', 'courto-core')],
                    ['acc_tab_title' => esc_html__('Accordion Title 3', 'courto-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{acc_tab_title}}',
            ]
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
            'acc_wrapper_width',
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
                    '{{WRAPPER}} .wgl-accordion_panel-inner' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '11',
                    'right' => '0',
                    'bottom' => '12',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_delimiter',
            [
                'label' => esc_html__('Delimiter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 10],
                ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => '--acc-delimiter-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'acc_tab_panel_delimiter_top',
            [
                'label' => esc_html__('Hide Delimiter on Top', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'condition' => ['acc_tab_panel_delimiter[size]!' => ['', 0]],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:first-child::before' => 'border-top: unset !important;',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}}.disable-rotation-yes .media-wrapper,
				     body[data-elementor-device-mode="tablet"] {{WRAPPER}}.disable-rotation-tablet-yes .media-wrapper,
				     body[data-elementor-device-mode="mobile"] {{WRAPPER}}.disable-rotation-mobile-yes .media-wrapper' => 'border-top: unset !important;',
                ],
            ]
        );
        $this->add_control(
            'acc_tab_panel_delimiter_bottom',
            [
                'label' => esc_html__('Hide Delimiter om Bottom', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['acc_tab_panel_delimiter[size]!' => ['', 0]],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:last-child::before' => 'border-bottom: unset !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_delimiter_pos',
            [
                'label' => esc_html__('Delimiter Position', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'allowed_dimensions' => 'horizontal',
                'condition' => ['acc_tab_panel_delimiter[size]!' => ['', 0]],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel::before' => 'right: {{RIGHT}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'acc_tab_panel_overlay',
                'types' => [ 'classic', 'gradient', 'video', 'slideshow' ],
                'fields_options' => [
                    'background' => [ 'label' => esc_html__('Background Overlay', 'courto-core'), ],
                ],
                'selector' => '{{WRAPPER}} .wgl-accordion_panel::after',
            ]
        );
        $this->start_controls_tabs( 'acc_panel_tabs' );
        $this->start_controls_tab(
            'acc_panel_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'acc_tab_panel_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_tab_panel_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel',
            ]
        );
        $this->add_control(
            'acc_tab_panel_delimiter_color_idle',
            [
                'label' => esc_html__('Delimiter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['acc_tab_panel_delimiter[size]!' => ['', 0]],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel::before' => '--acc-delimiter-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-accordion_panel:last-child::before' => '--acc-delimiter-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_tab_panel_overlay_opacity_idle',
            [
                'label' => esc_html__('Background Overlay Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'acc_tab_panel_overlay_background' => [ 'classic', 'gradient', 'video', 'slideshow' ]
                ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_panel_tab_hover',
            ['label' => esc_html__('Hover' , 'courto-core')]
        );
        $this->add_control(
            'acc_tab_panel_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_tab_panel_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel:hover',
            ]
        );
        $this->add_control(
            'acc_tab_panel_delimiter_color_hover',
            [
                'label' => esc_html__('Delimiter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'acc_tab_panel_delimiter[size]!' => ['', 0] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover::before,
                    {{WRAPPER}} .wgl-accordion_panel:hover + .wgl-accordion_panel::before' => '--acc-delimiter-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-accordion_panel:last-child:hover::before' => '--acc-delimiter-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_tab_panel_overlay_opacity_hover',
            [
                'label' => esc_html__('Background Overlay Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'acc_tab_panel_overlay_background' => [ 'classic', 'gradient', 'video', 'slideshow' ]
                ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_panel_tab_active',
            ['label' => esc_html__('Active' , 'courto-core')]
        );
        $this->add_control(
            'acc_tab_panel_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_tab_panel_shadow_active',
                'selector' => '{{WRAPPER}} div.wgl-accordion_panel.active',
            ]
        );
        $this->add_control(
            'acc_tab_panel_delimiter_color_active',
            [
                'label' => esc_html__('Delimiter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active::before,
                    {{WRAPPER}} .wgl-accordion_panel.active + .wgl-accordion_panel::before' => '--acc-delimiter-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-accordion_panel.active:last-child::before' => '--acc-delimiter-color-last: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_tab_panel_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_tab_panel_overlay_opacity_active',
            [
                'label' => esc_html__('Background Overlay Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'acc_tab_panel_overlay_background' => [ 'classic', 'gradient', 'video', 'slideshow' ]
                ],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
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
                'name' => 'acc_title_typo',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 24],
                        'mobile_default' => ['size' => 20],
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
            ]
        );

        $this->add_control(
            'acc_title_tag',
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
                'default' => 'div',
            ]
        );

        $this->add_control(
            'acc_title_font_family',
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
                    '{{WRAPPER}} .wgl-accordion_header' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_control(
            'acc_title_alignment',
            [
                'label' => esc_html__('Title Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'condition' => ['enable_acc_icon' => 'none'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('acc_header_tabs');

        $this->start_controls_tab(
            'acc_header_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'acc_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_padding_idle',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '13',
                    'right' => '0',
                    'bottom' => '13',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '6',
                    'right' => '0',
                    'bottom' => '6',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_margin_idle',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_header_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'acc_title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_padding_hover',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_margin_hover',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_hover',
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_title_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_header_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );

        $this->add_control(
            'acc_title_color_active',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_bg_color_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_padding_active',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_title_margin_active',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'acc_title_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_active',
                'dynamic' => ['active' => true],
                'selector' => '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_title_shadow_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_header',
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
                'name' => 'acc_title_pref_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_title-prefix',
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
                    '{{WRAPPER}} .wgl-accordion_title-prefix' => 'position: {{VALUE}}; top: 0; left: 0;',
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
                    '{{WRAPPER}} .wgl-accordion_title-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('acc_header_pref_tabs');
        $this->start_controls_tab(
            'acc_header_pref_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'acc_title_pref_color_idle',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_header_pref_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_pref_color_hover',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_header_pref_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'acc_title_pref_color_active',
            [
                'label' => esc_html__('Prefix Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
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
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'acc_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => ['enable_acc_icon' => 'custom'],
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 200],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => '--icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_icon_size_plus',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => ['enable_acc_icon' => ['plus', 'plus_bold']],
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => 3, 'max' => 200, 'step' => 1],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => '--icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '11',
                    'right' => '0',
                    'bottom' => '-15',
                    'left' => '18',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'acc_icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_icon_border',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .wgl-accordion_icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('acc_icon_tabs');
        $this->start_controls_tab(
            'acc_icon_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'acc_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_icon',
            ]
        );
        $this->add_responsive_control(
            'icon_rotate_idle',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'default' => ['size' => 90, 'unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => '--wgl-icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_flip_y_idle',
            [
                'label' => esc_html__( 'Flip Vertical', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => esc_html__( 'Default', 'courto-core' ),
                        'icon' => 'eicon-ban',
                    ],
                    '-1' => [
                        'title' => esc_html__( 'Flip Horizontal', 'courto-core' ),
                        'icon' => 'eicon-flip',
                    ],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => '--wgl-icon-scale-y: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_icon_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'acc_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'acc_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon',
            ]
        );
        $this->add_responsive_control(
            'icon_rotate_hover',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon' => '--wgl-icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_flip_y_hover',
            [
                'label' => esc_html__( 'Flip Vertical', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => esc_html__( 'Default', 'courto-core' ),
                        'icon' => 'eicon-ban',
                    ],
                    '-1' => [
                        'title' => esc_html__( 'Flip Horizontal', 'courto-core' ),
                        'icon' => 'eicon-flip',
                    ],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel:hover .wgl-accordion_icon' => '--wgl-icon-scale-y: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'acc_icon_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_active',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'acc_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon',
            ]
        );
        $this->add_responsive_control(
            'icon_rotate_active',
            [
                'label' => esc_html__('Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'default' => ['size' => -90, 'unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon' => '--wgl-icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_flip_y_active',
            [
                'label' => esc_html__( 'Flip Vertical', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '1' => [
                        'title' => esc_html__( 'Default', 'courto-core' ),
                        'icon' => 'eicon-ban',
                    ],
                    '-1' => [
                        'title' => esc_html__( 'Flip Horizontal', 'courto-core' ),
                        'icon' => 'eicon-flip',
                    ],
                ],
                'condition' => ['enable_acc_icon' => 'custom'],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel.active .wgl-accordion_icon' => '--wgl-icon-scale-y: {{VALUE}};',
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
                'name' => 'acc_content_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );
        $this->add_responsive_control(
            'acc_content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '6',
                    'right' => '0',
                    'bottom' => '19',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acc_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acc_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_content_border',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute(
            'accordion',
            [
                'class' => [
                    'wgl-accordion',
                    'icon-' . $_s['enable_acc_icon'],
                ],
                'id' => 'wgl-accordion-' . esc_attr($this->get_id()),
                'data-type' => $_s['acc_type'],
                'data-trigger' => $_s['acc_trigger'],
            ]
        );

        $icon_output = '';
        if ($_s['enable_acc_icon'] === 'custom') {
            $icon_font = $_s['acc_icon'];
            $migrated = isset( $_s['__fa4_migrated']['acc_icon'] );
            $is_new = Icons_Manager::is_migration_allowed();
            if ( $is_new || $migrated ) {
                if ( Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) || 'svg' === $icon_font['library'] ){
                    ob_start();
                    Icons_Manager::render_icon( $_s['acc_icon'], [ 'aria-hidden' => 'true' ] );
                    $icon_output = ob_get_clean();
                    $icon_output = '<span class="wgl-accordion_icon elementor-icon">' . $icon_output . '</span>';
                }else{
                    ob_start();
                    Icons_Manager::render_icon(
                        $_s['acc_icon'], [
                            'class' => 'wgl-accordion_icon elementor-icon',
                            'aria-hidden' => 'true',
                        ]
                    );
                    $icon_output = ob_get_clean();
                }
            } else {
                $icon_output = '<i class="wgl-accordion_icon elementor-icon ' . esc_attr( $icon_font ) . '"></i>';
            }
        }elseif( 'plus' === $_s['enable_acc_icon'] || 'plus_bold' === $_s['enable_acc_icon'] ) {
            $icon_output = '<i class="wgl-accordion_icon elementor-icon"></i>';
        }

        echo '<div ', $this->get_render_attribute_string('accordion'), '>';

        foreach ($_s['acc_tab'] as $index => $item) :

            // Link
            $acc_link = '';
            if(!empty($item['acc_link']['url'])){
                $link = $this->get_repeater_setting_key('link', 'list', $index);
                $this->add_link_attributes($link, $item['acc_link']);
                $acc_link = '<a class="wgl-accordion_title-link" '.$this->get_render_attribute_string($link).'></a>';
            }

            $tab_count = $index + 1;

            $tab_title_key = $this->get_repeater_setting_key('acc_tab_title', 'acc_tab', $index);

            $this->add_render_attribute(
                $tab_title_key,
                [
                    'class' => [
                        'wgl-accordion_panel',
                        'elementor-repeater-item-'. $item['_id'],
                    ],
                    'id' => 'wgl-accordion_panel-' . $id_int . $tab_count,
                ]
            );
            if (!empty($item['acc_tab_def_active'])) $this->add_render_attribute( $tab_title_key, ['data-default' => 'yes'] );

            echo '<div ', $this->get_render_attribute_string($tab_title_key), '>';
            echo '<div class="wgl-accordion_panel-inner">';
            echo '<', $_s['acc_title_tag'], ' class="wgl-accordion_header">';

            if (!empty($acc_link)) {
                echo $acc_link;
            }

            echo '<span class="wgl-accordion_title">';
            if (!empty($item['acc_tab_title_pref'])) {
                echo '<span class="wgl-accordion_title-prefix">',
                $item['acc_tab_title_pref'],
                '</span>';
            }
            echo $item['acc_tab_title'];
            echo '</span>'; // _title

            echo $icon_output;

            echo '</', $_s['acc_title_tag'], '>';

            echo '<div class="wgl-accordion_content">';

            if ($item['acc_content_type'] == 'content') {
                echo do_shortcode($item['acc_content']);
            } elseif ($item['acc_content_type'] == 'template') {
                $id = $item['acc_content_templates'];
                $wgl_frontend = new Frontend;
                echo $wgl_frontend->get_builder_content_for_display($id);
            }

            echo '</div>'; // _content

            echo '</div>'; // _inner

            echo '</div>'; // _panel

        endforeach;

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
