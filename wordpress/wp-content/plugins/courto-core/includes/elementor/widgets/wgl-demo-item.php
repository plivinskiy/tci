<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-demo-item.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border,
    Icons_Manager,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Box_Shadow,
    Group_Control_Background,
    Group_Control_Typography,
    Utils};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Cursor
};

class WGL_Demo_Item extends Widget_Base
{

    public function get_name()
    {
        return 'wgl-demo-item';
    }

    public function get_title()
    {
        return esc_html__('WGL Demo Item', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-demo-item';
    }

    public function get_keywords()
    {
        return [ 'demo', 'item' ];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  Content
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_demo_item_section',
            ['label' => esc_html__('Demo Item Settings', 'courto-core')]
        );

        $this->add_control(
            'demo_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => '<span>01</span>'.esc_html__('This is the heading', 'courto-core'),
                'default' => esc_html__('This is the heading', 'courto-core'),
            ]
        );

        $this->add_control(
            'demo_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [
                    'demo_title!' => '',
                    'demo_title_icon[value]' => '',
                ],
            ]
        );

        $this->add_control(
            'demo_title_icon',
            [
                'label' => esc_html__('Add Icon for Title', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'demo_title!' => '',
                    'demo_subtitle' => '',
                ],
            ]
        );


        $this->add_responsive_control(
            'title_v_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Middle', 'courto-core' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_responsive_control(
            'image_alignment',
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
                'default' => 'center',
                'condition' => ['thumbnail[url]!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'lift_on_hover',
            [
                'label' => esc_html__('Lift Up on Hover', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
            ]
        );

        $this->add_control(
            'demo_link',
            [
                'label' => esc_html__('Demo Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
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
        /*  General styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .demo-item',
            ]
        );

        $this->start_controls_tabs( 'item_tab' );
        $this->start_controls_tab(
            'item_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_idle',
                'selector' => '{{WRAPPER}} .demo-item',
            ]
        );
        $this->add_control(
            'item_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'item_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow_idle',
                'selector' => '{{WRAPPER}} .demo-item',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_hover',
                'selector' => '{{WRAPPER}} .wgl-demo-item:hover .demo-item',
            ]
        );
        $this->add_control(
            'item_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'item_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .demo-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-demo-item:hover .demo-item',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Image styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['thumbnail[url]!' => ''],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Image Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 10, 'max' => 500 ],
                    '%' => ['min' => 10, 'max' => 100 ],
                ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'image_order',
            [
                'label' => esc_html__('Image Order (Position)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => -10,
                'max' => 10,
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image-wrap' => 'order: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'image_tab' );
        $this->start_controls_tab(
            'image_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow_idle',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes'
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 9,
                            'vertical' => 11,
                            'blur' => 29,
                            'spread' => 0,
                            'color' => 'rgba(0, 0, 0, 0.15)',
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .demo-item_image',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-demo-item:hover .demo-item_image',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Title styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['demo_title!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .demo-item_title',
            ]
        );

        $this->add_responsive_control(
            'title_decoration_line_size',
            [
                'label' => esc_html__('Decoration Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'custom_fonts_title_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_underline_offset',
            [
                'label' => esc_html__('Underline Offset Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'custom_fonts_title_text_decoration' => 'underline' ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20, 'step' => 1],
                    'em' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
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
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__('Title Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
                'options' => [
                    'flex-start; text-align: left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center; text-align: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end; text-align: right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between; text-align: justify' => [
                        'title' => esc_html__('Justify', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'space-between; text-align: justify',
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '15',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'title_tab' );
        $this->start_controls_tab(
            'title_tab_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_decoration_color_idle',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'custom_fonts_title_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .demo-item_title' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_tab_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .demo-item_title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_decoration_color_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'custom_fonts_title_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .demo-item_title' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Icon Styles
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['demo_title_icon[value]!' => ''],
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
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'icon_tab' );
        $this->start_controls_tab(
            'icon_tab_idle',
            [ 'label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_idle',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'icon_tab_hover',
            [ 'label' => esc_html__('Hover' , 'courto-core') ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .wgl-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_rotation_hover',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
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
            'demo_subtitle_style',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['demo_subtitle!' => ''],
            ]
        );

	    $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'demo_subtitle_custom_fonts',
                'selector' => '{{WRAPPER}} .subtitle',
            ]
        );

	    $this->add_responsive_control(
            'demo_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_demo_subtitle_styles');
	    $this->start_controls_tab(
            'tab_demo_subtitle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
	    $this->add_control(
            'demo_subtitle_color_idle',
            [
                'label' => esc_html__('BG Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
	    $this->end_controls_tab();
	    $this->start_controls_tab(
            'tab_demo_subtitle_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
	    $this->add_control(
            'demo_subtitle_color_hover',
            [
                'label' => esc_html__('BG Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-demo-item:hover .subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->end_controls_section();


    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $cursor = new WGL_Cursor;
        $cursor_data = $cursor->build($this, $_s);
        $icon = $subtitle = '';

        if (isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });
        }

        $this->add_render_attribute('demo', 'class', [
            'wgl-demo-item',
            ( isset($_s['cursor_tooltip']) && '' != $_s['cursor_tooltip'] ? ' wgl-cursor-text additional-cursor' : '' ),
            !!$_s['lift_on_hover'] ? 'lift_on_hover' : ''
        ]);


        if (!empty($_s['demo_link']['url'])) {
            $this->add_link_attributes('demo_link', $_s['demo_link']);
        }
        if ( isset($_s['demo_title_icon']['value']) ) {
            $migrated = isset( $atts['__fa4_migrated']['demo_title_icon'] );
            $is_new = Icons_Manager::is_migration_allowed();
            if ( $is_new || $migrated ) {
                ob_start();
                Icons_Manager::render_icon($_s['demo_title_icon'], ['class' => 'read-more-icon', 'aria-hidden' => 'true']);
                $icon = ob_get_clean();
            }
            if ('svg' === $_s['demo_title_icon']['library']) {
                $icon = '<span class="read-more-icon read-more-svg">'.$icon.'</span>';
            }

            $icon = !!$icon ? '<span class="icon-wrapper"><span class="wgl-icon"> ' . $icon . '</span></span>' : '';
        }

        $this->add_render_attribute('demo_img', [
            'class' => 'demo-item_image',
            'src' => isset($_s['thumbnail']['url']) ? esc_url($_s['thumbnail']['url']) : '',
            'alt' => Control_Media::get_image_alt( $_s['thumbnail'] ),
        ]);

        $link_tag = !empty($_s['demo_link']['url']) ? 'a' : 'div';

        echo '<div ', $this->get_render_attribute_string('demo'), $cursor_data, '>';
        echo '<', $link_tag, ' ', $this->get_render_attribute_string('demo_link'), ' class="demo-item">';
        if (!empty($_s['demo_subtitle'])) {
            $subtitle = '<span class="subtitle">'.$_s['demo_subtitle'].'</span>';
        }
        if (!empty($_s['thumbnail'])) {
            echo '<div class="demo-item_image-wrap"><img ', $this->get_render_attribute_string('demo_img'), '/></div>';
        }
        if (!empty($_s['demo_title'])) {
            echo '<', $_s['title_tag'], ' class="demo-item_title">',
                $_s['demo_title'],
                $icon,
                $subtitle,
            '</', $_s['title_tag'], '>';
        }
        echo '</', $link_tag, '>';
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
