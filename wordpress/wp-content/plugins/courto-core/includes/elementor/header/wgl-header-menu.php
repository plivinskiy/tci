<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Plugin,
    Widget_Base,
    Repeater,
    Utils,
    Icons_Manager,
    Controls_Manager
};
use Elementor\{
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;
use WGL_Extensions\Includes\WGL_Elementor_Helper;

/**
 * Menu widget for Header CPT
 *
 *
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Menu extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-menu';
    }

    public function get_title()
    {
        return esc_html__('WGL Menu', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-header-menu';
    }

    public function get_keywords() {
        return ['menu'];
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
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_control(
            'template',
            [
                'label' => esc_html__('Template', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Theme Default', 'courto-core'),
                    'custom' => esc_html__('WordPress Menu', 'courto-core'),
                    'custom_item' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => 'default',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'menu' => esc_html__('Menu', 'courto-core'),
                    'submenu' => esc_html__('Sub Menu', 'courto-core'),
                ],
                'default' => 'menu',
            ]
        );

        $repeater->add_control(
            'content_media_type',
            [
                'label' => esc_html__('Media Type', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'label_block' => false,
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ]
                ],
                'default' => '',
            ]
        );

        $repeater->add_control(
            'content_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['content_media_type' => 'font'],
                'label_block' => true,
                'description' => esc_html__('Select icon from available libraries.', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'content_thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'condition' => ['content_media_type' => 'image'],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'item_text',
            [
                'label' => esc_html__('Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'item_position',
            [
                'label' => esc_html__('Dropdown Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'courto-core'),
                    'left' => esc_html__('Left', 'courto-core'),
                    'right' => esc_html__('Right', 'courto-core'),
                    'center' => esc_html__('Center', 'courto-core'),
                ],
                'default' => 'default',
                'condition' => ['item_type' => 'menu'],
            ]
        );

        $this->add_control(
            'items_menu',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['template' => 'custom_item'],
                'default' => [
                    [
                        'item_text' => esc_html__('Text', 'courto-core'),
                    ],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->add_control(
            'menu',
            [
                'label' => esc_html__('Menu', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['template' => 'custom'],
                'options' => wgl_get_custom_menu(),
            ]
        );

        $this->add_control(
            'submenu_disable',
            [
                'label' => esc_html__('Disable Submenu', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'condition' => ['template!' => 'custom_item'],
            ]
        );

        $this->add_control(
            'marker_disable',
            [
                'label' => esc_html__('Disable Marker', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'vertical_menu',
            [
                'label' => esc_html__('Vertical Menu', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul' => 'display: flex; flex-direction: column;',
                    '{{WRAPPER}} .primary-nav > ul > li' => 'width: 100%;',
                ],
                'return_value' => 'vertical',
                'prefix_class' => 'menu_'
            ]
        );

        $this->add_control(
            'expand_menu',
            [
                'label' => esc_html__('Expand menu on item click', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'expand',
                'prefix_class' => 'menu_',
                'condition' => ['vertical_menu!' => ''],
            ]
        );

        $this->add_control(
            'menu_anim_enable',
            [
                'label' => esc_html__('Top Level Menu Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $this->add_control(
            'lavalamp_active',
            [
                'label' => esc_html__('Lavalamp Marker', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'condition' => ['vertical_menu' => ''],
            ]
        );

        $this->add_control(
            'menu_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_height',
            [
                'label' => esc_html__('Height', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['vertical_menu' => ''],
            ]
        );

        $this->add_control(
            'menu_height',
            [
                'label' => esc_html__('Module Height (px)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'separator' => 'after',
                'min' => 30,
                'default' => 99,
                'selectors' => [
                    '{{WRAPPER}} .primary-nav' => 'height: {{VALUE}}px;',
                ],
                'condition' => ['vertical_menu' => ''],
            ]
        );

        $this->add_control(
            'alignmentt_flex',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'display' => 'inline-flex; width: auto',
                    'vertical_menu' => ''
                ],
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignmentt_block',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'display' => 'block',
                    'vertical_menu' => ''
                ],
                'toggle' => false,
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
                    '{{WRAPPER}} .primary-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignmentt_vertical',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['vertical_menu!' => ''],
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        if('elementor' === \WGL_Framework::get_option('mobile_header_building_tool')){
            $this->add_control(
                'mobile_dropdown_layout',
                [
                    'label' => esc_html__('Dropdown Breakpoint', 'courto-core'),
                    'type' => Controls_Manager::HIDDEN,
                    'label_block' => true,
                    'default' => 'breakpoint',
                    'prefix_class' => 'elementor-mobile-',
                ]
            );
        }
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

        $this->add_responsive_control(
            'menu_padding',
            [
                'label' => esc_html__('Menu Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .primary-nav > ul',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> MENU
         */

        $this->start_controls_section(
            'section_style_menu',
            [
                'label' => esc_html__('Menu', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'items',
                'selector' => '{{WRAPPER}} .primary-nav > div > ul, {{WRAPPER}} .primary-nav > ul',
            ]
        );

        $this->add_responsive_control(
            'menu_items_padding',
            [
                'label' => esc_html__('Items Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_inner_items_padding',
            [
                'label' => esc_html__('Items Inner Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_items_margin',
            [
                'label' => esc_html__('Items Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_items_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .primary-nav > ul > li',
            ]
        );

        $this->start_controls_tabs('tabs_menu');

        $this->start_controls_tab(
            'tab_menu_idle',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );

        $this->add_control(
            'menu_text_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item > a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_border_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_icon_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a > .menu-item__plus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav > ul > li > a > .button_switcher_vertical' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav .lavalamp .lavalamp-item > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_marker_idle',
            [
                'label' => esc_html__('Marker Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['marker_disable' => ''],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a .menu-item_dots' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_open',
            ['label' => esc_html__('Open' , 'courto-core')]
        );

        $this->add_control(
            'menu_text_open',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu > a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_bg_open',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_border_open',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_icon_open',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu > a > .menu-item__plus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu > a > .button_switcher_vertical' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_marker_open',
            [
                'label' => esc_html__('Marker Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['marker_disable' => ''],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li.active_sub_menu > a .menu-item_dots' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'menu_text_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a,
                     {{WRAPPER}} .primary-nav .lavalamp-item.active-lavalamp-menu-item:hover > a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a,
                     {{WRAPPER}} .primary-nav .lavalamp-item.active-lavalamp-menu-item:hover > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_border_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover,
                     {{WRAPPER}} .primary-nav .lavalamp-item.active-lavalamp-menu-item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_icon_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a > .menu-item__plus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a > .button_switcher_vertical' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav .lavalamp-item.active-lavalamp-menu-item:hover > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_marker_hover',
            [
                'label' => esc_html__('Marker Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['marker_disable' => ''],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a .menu-item_dots' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );

        $this->add_control(
            'menu_text_active',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a,
                     {{WRAPPER}} .primary-nav > ul > li > a.active,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item.active-lavalamp-menu-item > a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a,
                     {{WRAPPER}} .primary-nav > ul > li > a.active,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item.active-lavalamp-menu-item > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_overlay_active',
            [
                'label' => esc_html__('Overlay Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                 'condition' => ['vertical_menu!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul li.active_sub_menu' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_border_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'border_border!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a,
                     {{WRAPPER}} .primary-nav > ul > li > a.active,
                     {{WRAPPER}} .primary-nav .lavalamp .lavalamp-item.active-lavalamp-menu-item > a' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_icon_active',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a > .menu-item__plus,
                     {{WRAPPER}} .primary-nav > ul > li > a.active > span > .menu-item__plus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a > .button_switcher_vertical,
                     {{WRAPPER}} .primary-nav > ul > li > a.active > span > .button_switcher_vertical' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav .lavalamp .lavalamp-item.active-lavalamp-menu-item > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'menu_marker_active',
            [
                'label' => esc_html__('Marker Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['marker_disable' => ''],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a .menu-item_dots,
                     {{WRAPPER}} .primary-nav > ul > li > a.active > span .menu-item_dots' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> SUBMENU
         */

        $this->start_controls_section(
            'section_style_submenu',
            [
                'label' => esc_html__('Submenu', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submenu_typo',
                'selector' => '{{WRAPPER}} .primary-nav > div > ul ul.sub-menu, {{WRAPPER}} .primary-nav > ul ul.sub-menu',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .primary-nav ul li ul.sub-menu',
            ]
        );

        $this->add_control(
            'submenu_radius',
            [
                'label' => esc_html__('Container Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu, {{WRAPPER}} .primary-nav ul li div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_items_radius',
            [
                'label' => esc_html__('Items Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li a, {{WRAPPER}} .primary-nav ul li div a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_submenu'
        );

        $this->start_controls_tab(
            'tab_submenu_idle',
            ['label' => esc_html__('Idle' , 'courto-core')]
        );

        $this->add_control(
            'submenu_text_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li ul.sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_item_idle',
            [
                'label' => esc_html__('Item Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li  ul.sub-menu li > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:not(:hover) > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submenu_hover',
            ['label' => esc_html__('Hover' , 'courto-core')]
        );

        $this->add_control(
            'submenu_text_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover ul.sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_item_hover',
            [
                'label' => esc_html__('Item Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li  ul.sub-menu  li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:hover > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submenu_active',
            ['label' => esc_html__('Active' , 'courto-core')]
        );

        $this->add_control(
            'submenu_text_active',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li[class*="current"]:not(:hover) > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"]:not(:hover)  ul.sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_bg_item_active',
            [
                'label' => esc_html__('Item Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li[class*="current"]:not(:hover) > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_active',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li[class*="current"]:not(:hover) > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .primary-nav ul li > ul.sub-menu,{{WRAPPER}} .primary-nav ul li > ul.sub-menu ul.sub-menu,{{WRAPPER}} .primary-nav ul li .mega-menu-container',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_shadow',
                'selector' => '{{WRAPPER}} .primary-nav ul li > ul.sub-menu,{{WRAPPER}} .primary-nav ul li > ul.sub-menu ul.sub-menu,{{WRAPPER}} .primary-nav ul li .mega-menu-container',
            ]
        );

        $this->add_responsive_control(
            'submenu_padding',
            [
                'label' => esc_html__('Container Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > ul.sub-menu' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_items_padding',
            [
                'label' => esc_html__('Items Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu a' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_margin_container',
            [
                'label' => esc_html__('Container Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu, {{WRAPPER}} .primary-nav ul li ul.sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_margin',
            [
                'label' => esc_html__('Items Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li > ul.sub-menu li,
                    {{WRAPPER}} .primary-nav ul li > ul.sub-menu ul.sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .primary-nav ul li > ul.sub-menu li:last-child,
                    {{WRAPPER}} .primary-nav ul li > ul.sub-menu ul.sub-menu li:last-child' => 'margin-bottom: 0px;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['template' => 'custom_item'],
            ]
        );

        $this->start_controls_tabs('tabs_icon');

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'menu_icon_color_idle',
            [
                'label' => esc_html__('Menu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_color_idle',
            [
                'label' => esc_html__('Submenu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu > a .content__media' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu > a .content__media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'menu_icon_color_hover',
            [
                'label' => esc_html__('Menu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a .content__media' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a .content__media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_color_hover',
            [
                'label' => esc_html__('Submenu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:hover > a .content__media' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:hover > a .content__media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'menu_icon_color_active',
            [
                'label' => esc_html__('Menu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"]:not(:hover) > a .content__media,
                     {{WRAPPER}} .primary-nav > ul > li > a.active > span > .content__media' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"]:not(:hover) > a .content__media svg,
                     {{WRAPPER}} .primary-nav > ul > li > a.active > span > .content__media svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'submenu_icon_color_active',
            [
                'label' => esc_html__('Submenu Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li[class*="current"]:not(:hover) > a > .content__media' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li[class*="current"]:not(:hover) > a > .content__media svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->start_controls_tabs('tabs_icon_settings');
        $this->start_controls_tab(
            'tab_menu_icon',
            ['label' => esc_html__('Menu', 'courto-core')]
        );
        $this->add_responsive_control(
            'menu_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 6, 'max' => 60],
                ],
                'default' => ['size' => 14],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_icon_spacing',
            [
                'label' => esc_html__('Horizontal Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media' => is_rtl() ? 'margin-left:' : 'margin-right:' . ' {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_icon_v_spacing',
            [
                'label' => esc_html__('Vertical Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a .content__media' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submenu_menu_icon',
            ['label' => esc_html__('Submenu', 'courto-core')]
        );

        $this->add_responsive_control(
            'submenu_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 6, 'max' => 60],
                ],
                'default' => ['size' => 14],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li > a .content__media i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li:hover > a .content__media svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_icon_spacing',
            [
                'label' => esc_html__('Horizontal Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li > a .content__media' => is_rtl() ? 'margin-left:' : 'margin-right:' . ' {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_icon_v_spacing',
            [
                'label' => esc_html__('Vertical Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul.sub-menu li > a .content__media' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> LAVALAMP
         */

        $this->start_controls_section(
            'section_style_lavalamp',
            [
                'label' => esc_html__('Lavalamp', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['lavalamp_active!' => ''],
            ]
        );

        $this->add_control(
            'lavalamp_color',
            [
                'label' => esc_html__('Lavalamp Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();

        if('elementor' === \WGL_Framework::get_option('mobile_header_building_tool')){

            $this->start_controls_section(
                'section_style_dropdown',
                [
                    'label' => esc_html__('Mobile Settings', 'courto-core'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                'switcher_size',
                [
                    'label' => esc_html__('Font Size', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'size_units' => ['px', 'em', 'rem'],
                    'range' => [
                        'px' => ['min' => 1, 'max' => 300],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav .menu-item .button_switcher::before,
                        {{WRAPPER}} .primary-nav .menu-item .button_switcher_vertical::before' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'switcher_v_position',
                [
                    'label' => esc_html__('Switcher Vertical Position', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'size_units' => [ 'px', 'em', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav .menu-item .button_switcher::before,
                        {{WRAPPER}} .primary-nav .menu-item .button_switcher_vertical::before' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'switcher_h_position',
                [
                    'label' => esc_html__('Switcher Horizontal Position', 'courto-core'),
                    'type' => Controls_Manager::SLIDER,
                    'dynamic' => ['active' => true],
                    'size_units' => [ 'px', 'em', '%', 'custom' ],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav .menu-item .button_switcher::before,
                        {{WRAPPER}} .primary-nav .menu-item .button_switcher_vertical::before' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs('tabs_switcher_menu');

            $this->start_controls_tab(
                'tab_switcher_menu_idle',
                ['label' => esc_html__('Idle' , 'courto-core')]
            );

            $this->add_control(
                'menu_switcher_idle',
                [
                    'label' => esc_html__('Switcher Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav > ul > li > a .button_switcher_vertical:before,
                         {{WRAPPER}} .primary-nav > ul > li > a .button_switcher:before' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_switcher_menu_hover',
                ['label' => esc_html__('Hover', 'courto-core')]
            );
            $this->add_control(
                'menu_switcher_hover',
                [
                    'label' => esc_html__('Switcher Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav > ul li:hover > a .button_switcher_vertical:before,
                        {{WRAPPER}} .primary-nav > ul li:hover > a .button_switcher:before' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tab_switcher_menu_active',
                ['label' => esc_html__('Active', 'courto-core')]
            );
            $this->add_control(
                'menu_switcher_active',
                [
                    'label' => esc_html__('Switcher Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .primary-nav > ul > li > a .button_switcher_vertical.is-active:before,
                        {{WRAPPER}} .primary-nav > ul > li > a .button_switcher.is-active:before' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_control(
                'dropdown_color',
                [
                    'label' => esc_html__('Dropdown Color', 'courto-core'),
                    'type' => Controls_Manager::COLOR,
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        '{{WRAPPER}} .hamburger-box .hamburger-inner span' => 'background: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_section();


            /**
             * STYLE -> MEGAMENU
             */

            $this->start_controls_section(
                'section_style_megamenu',
                [
                    'label' => esc_html__('Megamenu Settings', 'courto-core'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'megamenu_position',
                [
                    'label' => esc_html__('Mega Menu Position', 'courto-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'position: static;' => esc_html__('Static', 'courto-core'),
                        '' => esc_html__('Relative', 'courto-core'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => '{{VALUE}}',
                        '{{WRAPPER}} .primary-nav' => '{{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_section();
        }
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $menu = $menu ?? '';

        if (
            $template === 'default'
            && has_nav_menu('main_menu')
        ) {
            $menu = 'main_menu';
        }

        $nav_classes = $lavalamp_active ? ' menu_line_enable' : '';
        $nav_classes .= $submenu_disable ? ' submenu-disable' : '';
        $nav_classes .= $menu_anim_enable ? ' menu-anim-enable' : '';
        $nav_classes .= $marker_disable ? ' marker-disable' : ' marker-enable'; ?>

        <nav class="primary-nav<?php echo $nav_classes; ?>"><?php
            if('custom_item'  !== $template)
            {
                wgl_theme_main_menu(
                    $menu,
                    false,
                    $submenu_disable ?: null,
                    $menu_anim_enable ?: null
                );
            }else{
                echo $this->get_custom_menu();
            }
            ?>
        </nav><?php
    }

    public function get_custom_menu()
    {
        $settings         = $this->get_settings_for_display();
		$item_output      = '';
		$is_sub_menu_item = false;
        $is_child = false;
        $item_position = 'default';

        $item_output .= '<ul class="menu wgl-custom-menu">';
        foreach ($settings['items_menu'] as $menu => $item) {
            $link          = $this->get_repeater_setting_key('item_link', 'items_menu', $menu);
            if (
                !empty($item['item_link']['url'])
            ) {
                $this->add_link_attributes($link, $item['item_link']);
            }
            // ↓ Icon|Image
            $icon = '';
            if ('' !== $item['content_media_type']) {
                if (
                    'font' === $item['content_media_type']
                    && !empty($item['content_icon'])
                ) {
                    $media_class = ' icon';
                    $migrated = isset($item['__fa4_migrated'][$item['content_icon']]);
                    $is_new = Icons_Manager::is_migration_allowed();
                    if ($is_new || $migrated) {
                        ob_start();
                        Icons_Manager::render_icon($item['content_icon']);
                        $media_html = ob_get_clean();
                    } else {
                        $media_html = '<i class="icon ' . esc_attr($item['content_icon']) . '"></i>';
                    }
                }

                $icon = '<span class="content__media'. ($media_class ?? ''). '">'.($media_html ?? '').'</span>';
            }

            if ('submenu' === $item['item_type']) {
                if (
                    !$is_child
                ) {
                    $item_output .= "<ul class='sub-menu wgl-submenu-position-".(esc_attr($item_position))."'>";
                }

                $this->add_render_attribute(
                    'menu-sub-item' . $item['_id'],
                    'class',
                    'menu-item'
                );

                $item_output .= '<li ' . $this->get_render_attribute_string('menu-sub-item' . $item['_id']) . '>';
                $item_output .= '<a ' . $this->get_render_attribute_string($link) . ">";
                $item_output .= '<span>';
                $item_output .= $icon;
                $item_output .= '<span class="item_text">';
                $item_output .= $item['item_text'];
                $item_output .= '</span>';
                $item_output .= '<span class="menu-item_dots"></span></span>';
                $item_output .= '<i class="menu-item__plus"></i>';
                $item_output .= '</a>';

                $item_output .= '</li>';

                $is_child         = true;
                $is_sub_menu_item = true;
            } else {

                $has_children = isset($settings['items_menu'][$menu+1]) && 'submenu' === $settings['items_menu'][$menu+1]['item_type'] ? true : false;
                $this->add_render_attribute(
                    'menu-item' . $item['_id'],
                    'class',
                    'menu-item' . ($has_children ? ' menu-item-has-children' : '') . ' menu-item-' . $item['_id'] . $this->get_class_by_url($item['item_link']['url']),
                );

                $is_child = false;
                if(!$is_child){
                    $item_position = $item['item_position'];
                }
                if (
                    $is_sub_menu_item
                ) {
                    $is_sub_menu_item = false;
                    $item_output          .= '</ul></li>';
                }

                $item_output .= '<li ' . $this->get_render_attribute_string('menu-item' . $item['_id']) . '>';
                $item_output .= '<a ' . $this->get_render_attribute_string($link) . ">";
                $item_output .= '<span class="item_wrapper_text">';
                $item_output .= $icon;
                $item_output .= '<span class="item_text">';
                $item_output .= $item['item_text'];
                $item_output .= '</span>';
                $item_output .= '<span class="menu-item_dots"></span></span>';
                $item_output .= '<i class="menu-item__plus"></i>';
                $item_output .= '</a>';
            }
        }
        $item_output .= '</ul>';

		return $item_output;
    }

    public function get_class_by_url($url)
    {
        global $wp_rewrite;
        $classes = '';

        $_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
        $front_page_url         = home_url();

        // If it's the customize page then it will strip the query var off the URL before entering the comparison block.
        if ( is_customize_preview() ) {
            $_root_relative_current = strtok( untrailingslashit( $_SERVER['REQUEST_URI'] ), '?' );
        }

        $current_url        = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
        $raw_item_url       = strpos( $url, '#' ) ? substr( $url, 0, strpos( $url, '#' ) ) : $url;
        $item_url           = set_url_scheme( untrailingslashit( $raw_item_url ) );
        $_indexless_current = untrailingslashit( preg_replace( '/' . preg_quote( $wp_rewrite->index, '/' ) . '$/', '', $current_url ) );

        $matches = array(
            $current_url,
            urldecode( $current_url ),
            $_indexless_current,
            urldecode( $_indexless_current ),
            $_root_relative_current,
            urldecode( $_root_relative_current ),
        );

        if ( $raw_item_url && in_array( $item_url, $matches, true ) ) {
            $classes .= ' current-menu-item';

            if ( in_array( home_url(), array( untrailingslashit( $current_url ), untrailingslashit( $_indexless_current ) ), true ) ) {
                // Back compat for home link to match wp_page_menu().
                $classes .= ' current_page_item';
            }
        } elseif ( $item_url == $front_page_url && is_front_page() ) {
            $classes .= ' current-menu-item';
        }

        if ( untrailingslashit( $item_url ) == home_url() ) {
            $classes .= ' menu-item-home';
        }

        return $classes;
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
