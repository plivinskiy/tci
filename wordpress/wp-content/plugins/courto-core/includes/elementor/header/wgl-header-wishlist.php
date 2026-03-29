<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{
    Group_Control_Border,
    Group_Control_Typography,
    Widget_Base,
    Controls_Manager
};
use WGL_Extensions\Includes\WGL_Cursor;
use WPCleverWoosw;

/**
 * Wishlist widget for Header CPT
 *
 *
 * @category Class
 * @package courto-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Wishlist extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-wishlist';
    }

    public function get_title() {
        return esc_html__('WooWishlist', 'courto-core');
    }

    public function get_icon() {
        return 'wgl-header-wishlist';
    }

    public function get_keywords() {
        return ['wishlist', 'woocommerce'];
    }

    public function get_categories() {
        return [ 'wgl-header-modules' ];
    }

    public function get_script_depends() {
        return [
            'wgl-widgets',
        ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_search_settings',
            [ 'label' => esc_html__('General', 'courto-core') ]
        );

        $this->add_control(
            'icon_disable',
            [
                'label' => esc_html__('Disable Icon', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'display: none',
                ],
            ]
        );

        $this->add_control(
            'text_disable',
            [
                'label' => esc_html__('Disable Text', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'font-size: 0;',
                ],
            ]
        );

        $this->add_control(
            'counter_disable',
            [
                'label' => esc_html__('Disable Counter', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'display: none',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * GENERAL -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'condition' => [ 'icon_disable!' => 'yes' ],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_control(
            'icon_height',
            [
                'label' => esc_html__('Icon Height', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'height: {{VALUE}}px;',
                ],
            ]
        );
        $this->add_control(
            'icon_width',
            [
                'label' => esc_html__('Icon Width', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'width: {{VALUE}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Icon Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'fields_options' => [
                    'width' => ['label' => esc_html__('Icon Border Width', 'courto-core')],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before',
            ]
        );
        $this->start_controls_tabs('icon_style_tabs');

        $this->start_controls_tab(
            'tab_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_idle',
            [
                'label' => esc_html__('Icon Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__( 'Icon Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item a::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist .woosw-menu-item a::before' => 'transition: 0.4s;',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Icon Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item a::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist .woosw-menu-item a::before' => 'transition: 0.4s;',
                ],
            ]
        );
        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__( 'Icon Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item a::before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-wishlist .woosw-menu-item-inner::before,
                     {{WRAPPER}} .wgl-wishlist .woosw-menu-item a::before' => 'transition: 0.4s;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> COUNTER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_counter',
            [
                'label' => esc_html__('Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'counter_disable!' => 'yes' ],
            ]
        );

        $this->add_responsive_control(
            'counter_width',
            [
                'label' => esc_html__('Counter Min-Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'custom'],
                'range' => [ 'px' => ['min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'counter_position',
            [
                'label' => esc_html__('Counter Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => esc_html__('Static', 'courto-core'),
                    'top center' => esc_html__('Top Center', 'courto-core'),
                    'top left' => esc_html__('Top Left', 'courto-core'),
                    'top right' => esc_html__('Top Right', 'courto-core'),
                    'center center' => esc_html__('Center Center', 'courto-core'),
                    'center left' => esc_html__('Center Left', 'courto-core'),
                    'center right' => esc_html__('Center Right', 'courto-core'),
                    'bottom center' => esc_html__('Bottom Center', 'courto-core'),
                    'bottom left' => esc_html__('Bottom Left', 'courto-core'),
                    'bottom right' => esc_html__('Bottom Right', 'courto-core'),
                ],
                'selectors_dictionary' => [
                    'default' =>       '',
                    'static' =>        'margin: 0; position: static',
                    'top center' =>    'margin: 0 auto auto auto',
                    'top left' =>      'margin: 0 auto auto 0',
                    'top right' =>     'margin: 0 0 auto auto',
                    'center center' => 'margin: auto auto auto auto',
                    'center left' =>   'margin: auto auto auto 0',
                    'center right' =>  'margin: auto 0 auto auto',
                    'bottom center' => 'margin: auto auto 0 auto',
                    'bottom left' =>   'margin: auto auto 0 0',
                    'bottom right' =>  'margin: auto 0 0 auto',
                ],
                'default' => 'default',
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => '{{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'counter_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom'],
                'range' => [ 'px' => [ 'min' => -50, 'max' => 50 ] ],
                'condition' => [ 'counter_position!' => 'static' ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'counter_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom'],
                'range' => [ 'px' => [ 'min' => -50, 'max' => 50 ] ],
                'condition' => [ 'counter_position!' => 'static' ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => '--pos-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'counter_position' => 'static' ],
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '10',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_border_radius',
            [
                'label' => esc_html__('Counter Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('counter_style_tabs');
        $this->start_controls_tab(
            'counter_tab_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );

        $this->add_control(
            'counter_color_idle',
            [
                'label' => esc_html__('Items Counter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'counter_bg_idle',
            [
                'label' => esc_html__('Items Counter Background', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'counter_tab_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );

	    $this->add_control(
		    'counter_color_hover',
		    [
			    'label' => esc_html__('Items Counter Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner::after,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'transition: 0.4s;',
			    ],
		    ]
	    );
        $this->add_control(
            'counter_bg_hover',
            [
                'label' => esc_html__('Items Counter Background', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner::after,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woosw-menu-item-inner::after,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) .count' => 'transition: 0.4s;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TEXT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__('Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'text_disable!' => 'yes' ],
            ]
        );

        $this->add_control(
            'text_font_family',
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
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a',
            ]
        );
        $this->add_responsive_control(
            'text_decoration_line_size',
            [
                'label' => esc_html__('Decoration Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'text_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_underline_offset',
            [
                'label' => esc_html__('Underline Offset Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'text_typography_text_decoration' => 'underline' ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20, 'step' => 1],
                    'em' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'text_tabs' );
        $this->start_controls_tab(
            'text_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'text_color_idle',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_decoration_color_idle',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'text_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .woosw-menu-item-inner,
                     {{WRAPPER}} .woosw-menu-item:not(.menu-item-type-woosw) a' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_decoration_color_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'text_typography_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item-inner,
                     {{WRAPPER}} .wgl-wishlist:hover .woosw-menu-item a' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function render()
    {
        if (!class_exists('\WPCleverWoosw')) {
            return;
        }

        $_s = $this->get_settings_for_display();
        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);
        $key = $this->get_key();

        $this->add_render_attribute('wishlist', [
            'class' => [
                'wgl-wishlist',
                'elementor-wishlist',
                'woocommerce',
                ( isset($_s['cursor_tooltip']) && !empty($_s['cursor_tooltip']) ? ' wgl-cursor-text' : '' )
            ],
        ]);

        ?><div <?php echo \WGL_Framework::render_html($this->get_render_attribute_string('wishlist')), $cursor_data ?>>
            <div class="woosw-menu-item"><?php

                ob_start();
                    ?><a href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)) ?>">
                        <span class="count"><?php echo esc_html( WPCleverWoosw::get_count() ); ?></span>
                    </a>
                <?php
                echo ob_get_clean();

            ?></div>
        </div><?php
    }

    /**
     * @return string|null
     */
    public function get_key(){
        if ( ( $user_id = get_current_user_id() ) ) {
            $keys = get_user_meta( $user_id, 'woosw_keys', true ) ?: array();
            if ( is_array( $keys ) && ! empty( $keys ) ) {
                foreach ($keys as $k => $wl) {
                    return esc_attr( $k );
                }
            }
        }
        return 0;
    }
}