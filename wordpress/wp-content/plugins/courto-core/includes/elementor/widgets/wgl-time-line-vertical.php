<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-time-line-vertical.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
    Group_Control_Css_Filter,
    Icons_Manager,
    Plugin,
    Utils,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Repeater,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Cursor
};

class WGL_Time_Line_Vertical extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-time-line-vertical';
    }

    public function get_title()
    {
        return esc_html__('WGL Time Line Vertical', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-time-line-vertical';
    }

    public function get_keywords()
    {
        return [ 'time', 'line', 'timeline', 'date', 'history', 'vertical' ];
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
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            [ 'label' => esc_html__('Content', 'courto-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail_idle',
            [
                'label' => esc_html__('Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'thumbnail_switch',
            [
                'label' => esc_html__('Change on hover?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_responsive_control(
            'thumbnail_width',
            [
                'label' => esc_html__( 'Thumbnail Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .tlv__media [class|=tlv__thumbnail]' => 'width: {{SIZE}}{{UNIT}}; --image-width: {{SIZE}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__('Image Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .tlv__item.image .tlv__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $repeater->add_control(
            'thumbnail_hover',
            [
                'label' => esc_html__('Hover Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'condition' => [ 'thumbnail_switch!' => '' ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => ['classic'],
                'fields_options' => [
                    'color' => [ 'label' => esc_html__( 'Background Color', 'courto-core' ) ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}::before',
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'bg_date',
            [
                'label' => esc_html__('Background Date', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'number',
            [
                'label' => esc_html__('Number', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'separator' => 'before',
                'placeholder' => esc_attr__('Your title', 'courto-core'),
            ]
        );


        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque volutpat turpis quis imperdiet ullamcorper. Suspendisse commodo ut risus id egestas. Nullam ac lacus lorem. Quisque arcu dui, rhoncus.', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => esc_html__('The Vision Begins', 'courto-core'),
                        'bg_date' => esc_html__('2004', 'courto-core'),
                        'content' => esc_html__('The idea for a modern, community-focused tennis club is born as a small group of enthusiasts starts hosting regular training sessions.
', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Coaching Team Expands', 'courto-core'),
                        'bg_date' => esc_html__('2010', 'courto-core'),
                        'content' => esc_html__('The coaching team earns regional recognition for developing high-performing juniors and improving adult player results.
', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Junior Academy Launch', 'courto-core'),
                        'bg_date' => esc_html__('2017', 'courto-core'),
                        'content' => esc_html__('A milestone year as the Junior Academy is established, attracting young players and setting a strong foundation for athlete development.
', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Expansion & Growth', 'courto-core'),
                        'bg_date' => esc_html__('2024', 'courto-core'),
                        'content' => esc_html__('Additional courts and new training spaces open, bringing more capacity and elevating the club to a complete tennis center.
', 'courto-core'),
                    ],
                ],
                'title_field' => '{{title}}',
            ]
        );

        $this->add_control(
            'media_position',
            [
                'label' => esc_html__('Media Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    'inner' => esc_html__('Inner', 'courto-core'),
                    'outer' => esc_html__('Outer', 'courto-core'),
                ],
                'default' => 'outer',
                'prefix_class' => 'media_position-',
            ]
        );

        $this->add_responsive_control(
            'items_align_items',
            [
                'label' => esc_html__( 'Align Items', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'style_transfer' => true,
                'condition' => [ 'media_position' => 'outer' ],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-v',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-v',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-v',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tlv__content' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_mobile_breakpoint();

        $this->end_controls_section();

        /**
         * CONTENT -> LINK
         */

        $this->start_controls_section(
            'content_link',
            ['label' => esc_html__('Link', 'courto-core')]
        );

        $this->add_control(
            'add_read_more',
            [
                'label' => esc_html__('\'Read More\' Button', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Use', 'courto-core'),
                'label_off' => esc_html__('Hide', 'courto-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'condition' => [ 'add_read_more' => 'yes' ],
                'default' => esc_html__('READ MORE', 'courto-core'),
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__( 'Button Type', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'style_transfer' => true,
                'options' => [
                    'wgl-button' => esc_html__( 'Default Button Style', 'courto-core' ),
                    'button-read-more' => esc_html__( 'Read More Style', 'courto-core' ),
                ],
                'condition' => [ 'add_read_more' => 'yes' ],
                'default' => 'button-read-more',
            ]
        );
        $this->add_control(
            'read_more_anim',
            [
                'label' => esc_html__('Read More Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'style_transfer' => true,
                'render_type' => 'template',
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'disable' => esc_html__( 'Disable', 'courto-core' ),
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'button_type' => 'button-read-more',
                ],
                'prefix_class' => 'button_animation-',
                'default' => 'default',
            ]
        );
        $this->add_control(
            'read_more_icon_type',
            [
                'label' => esc_html__('Icon Type', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'label_block' => false,
                'condition' => [
                    'add_read_more' => 'yes',
                    'button_type' => 'wgl-button',
                ],
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'read_more_icon_fontawesome',
            [
                'label' => esc_html__('Button Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_type' => 'font',
                    'button_type' => 'wgl-button',
                ],
                'description' => esc_html__('Select icon from available libraries.', 'courto-core'),
                'label_block' => true,
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-icons',
                ],
            ]
        );

        $this->add_control(
            'read_more_icon_align',
            [
                'label' => esc_html__( 'Position', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_type' => 'font',
                    'button_type' => 'wgl-button',
                ],
                'options' => [
                    'top' => esc_html__( 'Top', 'courto-core' ),
                    'left' => esc_html__( 'Before', 'courto-core' ),
                    'right' => esc_html__( 'After', 'courto-core' ),
                ],
                'default' => 'right',
            ]
        );

        $this->add_responsive_control(
            'button_icon_top',
            [
                'label' => esc_html__('Icon Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_text!' => '',
                    'read_more_icon_type' => 'font',
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-translate-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_stroke_size',
            [
                'label' => esc_html__('SVG Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 300],
                ],
                'condition' => [
                    'add_read_more' => 'yes',
                    'read_more_icon_fontawesome[library]' => 'svg',
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon path' => 'stroke-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> APPEARANCE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_animation',
            [ 'label' => esc_html__('Appearance', 'courto-core') ]
        );
        $this->add_control(
            'add_appear',
            [
                'label' => esc_html__('Use Appear Animation?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'style_transfer' => true,
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'add_line_animation',
            [
                'label' => esc_html__('Use Line Animation?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'style_transfer' => true,
                'default' => '',
            ]
        );

        $this->add_control(
            'visibility_ratio',
            [
                'label' => esc_html__('Visibility Ratio', 'courto-core'),
                'description' => esc_html__('Defines how much of an item must be visible before the appearance animation starts. Smaller values = earlier trigger.', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [ 'px' => ['min' => 0, 'max' => 2, 'step' => 0.05] ],
                'condition' => [ 'add_line_animation' => 'yes' ],
                'default' => ['size' => 1.25],
            ]
        );

        $this->add_control(
            'v_appear_line_bg',
            [
                'label' => esc_html__('Vertical Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'style_transfer' => true,
                'condition' => [ 'add_line_animation' => 'yes' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CURVE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_curve',
            [
                'label' => esc_html__('Main Curve', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_gap',
            [
                'label' => esc_html__( 'Items Gap', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 300, 'step' => 1 ],
                ],
                'default' => ['size' => 0],
                'mobile_default' => ['size' => 60],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'curve_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'allowed_dimensions' => 'vertical',
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'curve_gap',
            [
                'label' => esc_html__( 'Curve Gap', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 300, 'step' => 1 ],
                ],
                'default' => ['size' => 64],
                'tablet_default' => ['size' => 35],
                'mobile_default' => ['size' => 20],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => '--curve-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'horizontal_curve_size',
            [
                'label' => esc_html__( 'Horizontal Curve Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
                ],
                'default' => ['size' => 85],
                'tablet_default' => ['size' => 45],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => '--curve-h-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'horizontal_curve_position',
            [
                'label' => esc_html__( 'Horizontal Curve Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -100, 'max' => 100, 'step' => 1 ],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__curve-wrapper::before' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__curve-wrapper::before' => 'right: {{SIZE}}{{UNIT}}; left: auto;',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__curve-wrapper::before' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__curve-wrapper::before' => 'left: {{SIZE}}{{UNIT}}; right: auto;',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__curve-wrapper::before,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__curve-wrapper::before,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__curve-wrapper::before,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__curve-wrapper::before,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__curve-wrapper::before,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__curve-wrapper::before' => 'left: {{SIZE}}{{UNIT}} !important; right: auto !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pointer_size',
            [
                'label' => esc_html__( 'Pointer Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 18 ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => '--dot-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'start_end_curve_color',
            [
                'label' => esc_html__('Start/End Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0),
                'selectors' => [
                    '{{WRAPPER}} .tlv__item-start,
                     {{WRAPPER}} .tlv__item-end' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_pointer' );

        $this->start_controls_tab(
            'tab_pointer_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'v_line_bg_idle',
            [
                'label' => esc_html__('Vertical Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.15),
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'in_pointer_color_idle',
            [
                'label' => esc_html__('Pointer Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'pointer_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical' => '--dot-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'h_line_bg',
            [
                'label' => esc_html__('Horizontal Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(0),
                'selectors' => [
                    '{{WRAPPER}} .tlv__curve-wrapper::before' => '--curve-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pointer_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'v_line_bg_hover',
            [
                'label' => esc_html__('Vertical Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-timeline-vertical .tlv__items-wrapper:hover::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'in_pointer_color_hover',
            [
                'label' => esc_html__('Pointer Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'pointer_size[size]!' => [0, ''] ],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__curve-wrapper .dot' => '--dot-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'h_line_bg_hover',
            [
                'label' => esc_html__('Horizontal Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__curve-wrapper::before' => '--curve-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT & MEDIA WRAPPER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__('Content & Media Wrapper', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wrapper_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Left/Right values are reversed for even items.', 'courto-core' ),
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '40',
                    'right' => '10',
                    'bottom' => '48',
                    'left' => '145',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'tablet_default' => [
                    'top' => '30',
                    'right' => '10',
                    'bottom' => '36',
                    'left' => '115',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '90',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__content-wrapper,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__content-wrapper,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'border-radius: {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__content-wrapper,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__content-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_wrapper',
            [ 'separator' => 'before' ]
        );

        $this->start_controls_tab(
            'tab_wrapper_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );

        $this->add_control(
            'wrapper_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_idle',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .tlv__content-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_idle',
                'selector' => '{{WRAPPER}} .tlv__content-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_blur_idle',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content-wrapper' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_wrapper_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'wrapper_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_hover',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__content-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_hover',
                'selector' => '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__content-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_blur_hover',
            [
                'label' => esc_html__('Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__content-wrapper' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_media',
            [
                'label' => esc_html__('Media', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'media_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Left/Right values are reversed for even items.', 'courto-core' ),
            ]
        );

        $this->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__media' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__media' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_width',
            [
                'label' => esc_html__( 'Thumbnail Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1200 ],
                ],
                'default' => ['size' => '520', 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__media [class|=tlv__thumbnail]' => 'width: {{SIZE}}{{UNIT}}; --image-width: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'media_width_hover',
            [
                'label' => esc_html__( 'Image Width on Hover', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1200 ],
                ],
                'condition' => [ 'media_width[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__media [class|=tlv__thumbnail]' => '--wgl-scale: calc( {{SIZE}} / var(--image-width) );',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 1]
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__item.image' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_media' );
        $this->start_controls_tab(
            'tab_media_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_media_bg_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tlv__media::before',
            ]
        );
        $this->add_responsive_control(
            'media_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__media' => 'border-radius: {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_rotate_idle',
            [
                'label' => esc_html__('Image Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__media img' => '--wgl-rotate: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__media img' => '--wgl-rotate: calc(-1 * {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'media_shadow_idle',
                'selector' => '{{WRAPPER}} .tlv__media',
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'media_css_filters_idle',
                'selector' => '{{WRAPPER}} .tlv__media img',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_media_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_media_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .tlv__media::after',
            ]
        );
        $this->add_responsive_control(
            'media_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd):hover .tlv__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even):hover .tlv__media' => 'border-radius: {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_rotate_hover',
            [
                'label' => esc_html__('Image Rotate', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd):hover .tlv__media img' => '--wgl-rotate: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even):hover .tlv__media img' => '--wgl-rotate: calc(-1 * {{SIZE}}{{UNIT}});',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media img,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media img,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media img,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media img,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media img,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media img' => '--wgl-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'media_shadow_hover',
                'selector' => '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__media,

                    body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media,
                    body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media,
                    body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media,
                    body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media,
                    body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media,
                    body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media',
            ]
        );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'media_css_filters_hover',
                'selector' => '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__media img,

                    body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__media img,
                    body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__media img,
                    body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__media img,
                    body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__media img,
                    body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__media img,
                    body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__media img',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'description' => esc_html__('Note. Left/Right values are reversed for even items.', 'courto-core'),
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__content' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__content,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__content,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__content,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__content,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__content,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',

                ],
            ]
        );
        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__( 'Content Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 768 ],
                ],
                'default' => [ 'size' => 415, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content' => 'max-width: min({{SIZE}}{{UNIT}}, 100%);',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_content_bg' );

        $this->start_controls_tab(
            'tab_content_bg_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );

        $this->add_control(
            'content_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_content_bg_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );

        $this->add_control(
            'content_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title Styles', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .tlv__title',
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1200 ],
                    '%' => ['min' => 5, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__title' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__title,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__title,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_title' );

        $this->start_controls_tab(
            'tab_title_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'heading_text',
            [
                'label' => esc_html__('Text Styles', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_text',
                'selector' => '{{WRAPPER}} .tlv__text',
            ]
        );

        $this->add_responsive_control(
            'content_text_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '22',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '14',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__text' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__text,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__text,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__text,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__text,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__text,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_content' );
        $this->start_controls_tab(
            'tab_content_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_content_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> NUMBER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_number',
            [
                'label' => esc_html__('Number', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number',
                'selector' => '{{WRAPPER}} .tlv__number',
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .tlv__number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_number' );
        $this->start_controls_tab(
            'number_colors_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'number_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_number_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'number_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> DATE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_date',
            [
                'label' => esc_html__('Date', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .tlv__date',
            ]
        );

        $this->add_control(
            'date_font',
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
                    '{{WRAPPER}} .tlv__date' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__date' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '27',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__date' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}}  {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'date_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__date' => 'border-radius: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}}  {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_date' );

        $this->start_controls_tab(
            'date_colors_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );

        $this->add_control(
            'date_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.3),
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_date_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );

        $this->add_control(
            'date_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BACKGROUND DATE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_bg_date',
            [
                'label' => esc_html__('Background Date', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bg_date',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [
                        'default' => [ 'size' => 128, 'unit' => 'px' ],
                        'tablet_default' => [ 'size' => 112, 'unit' => 'px' ],
                        'mobile_default' => [ 'size' => 92, 'unit' => 'px' ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .tlv__bg_date',
            ]
        );

        $this->add_responsive_control(
            'bg_date_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'media_position' => 'outer' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_date_margin_inner_img',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'media_position' => 'inner' ],
                'selectors' => [
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_date_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'bg_date_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'border-radius: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}}  {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(odd) .tlv__bg_date' => 'border-radius: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}}.media_position-inner .tlv__items-wrapper:nth-child(even) .tlv__bg_date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_date_stroke_size',
            [
                'label' => esc_html__('Stroke Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]],
                'selectors' => [
                    '{{WRAPPER}} .tlv__bg_date' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_date_rotate',
            [
                'label' => esc_html__('BG Date Rotate', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'transform: unset; writing-mode: unset;' => [
                        'title' => esc_html__('Default', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'transform: rotate(180deg); writing-mode: vertical-rl;' => [
                        'title' => esc_html__('Rotated -90deg', 'courto-core'),
                        'icon' => 'eicon-arrow-up',
                    ],
                    'transform: unset; writing-mode: vertical-rl;' => [
                        'title' => esc_html__('Rotated +90deg', 'courto-core'),
                        'icon' => 'eicon-arrow-down',
                    ]
                ],
                'default' => 'transform: rotate(180deg); writing-mode: vertical-rl;',
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .tlv__bg_date' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_date_min_width',
            [
                'label' => esc_html__('BG Date Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .tlv__bg_date' => 'min-width: {{SIZE}}{{UNIT}}; text-align: center;',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_date_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 1]
                ],
                'default' => [ 'size' => -2 ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__bg_date' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_bg_date' );
        $this->start_controls_tab(
            'bg_date_colors_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'bg_date_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.16),
                'selectors' => [
                    '{{WRAPPER}} .tlv__bg_date' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_date_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__bg_date' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_date_stroke_color_idle',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_date_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__bg_date' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_bg_date_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'bg_date_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__bg_date' => 'color: {{VALUE}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'bg_date_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__bg_date' => 'background-color: {{VALUE}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'bg_date_stroke_color_hover',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_date_stroke_size[size]!' => [0, ''] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .tlv__bg_date' => '-webkit-text-stroke-color: {{VALUE}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .tlv__bg_date,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .tlv__bg_date,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .tlv__bg_date,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .tlv__bg_date,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .tlv__bg_date,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .tlv__bg_date' => '-webkit-text-stroke-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> BUTTON
         */

        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_custom_fonts',
                'selector' => '{{WRAPPER}} .wgl-widget__button .button__text',
            ]
        );

        $this->add_control(
            'button_font',
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
                    '{{WRAPPER}} .button__content' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_decoration_line_size',
            [
                'label' => esc_html__('Decoration Line Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 20, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_underline_offset',
            [
                'label' => esc_html__('Underline Offset Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => 'underline' ],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => -20, 'max' => 20, 'step' => 1],
                    'em' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '18',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .wgl-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .wgl-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .wgl-button-wrapper,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .wgl-button-wrapper,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .wgl-button-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .wgl-button-wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .wgl-button-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .wgl-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_wrapper_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .wgl-button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .wgl-button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .wgl-button-wrapper,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .wgl-button-wrapper,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .wgl-button-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .wgl-button-wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .wgl-button-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .wgl-button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Inner Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(odd) .wgl-widget__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tlv__items-wrapper:nth-child(even) .wgl-widget__button' => 'border-radius: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',

                    'body[data-elementor-device-mode="widescreen"] {{WRAPPER}} .breakpoint_on-widescreen .wgl-widget__button,
                     body[data-elementor-device-mode="desktop"] {{WRAPPER}} .breakpoint_on-desktop .wgl-widget__button,
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .wgl-widget__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );

        $this->add_responsive_control(
            'read_more_button_width',
            [
                'label' => esc_html__( 'Button Min-Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_alignment!' => ['space-evenly', 'space-around', 'space-between'],
                ],
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'range' => [
                    'px' => ['max' => 500],
                    '%' => ['max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tabs_button' );
        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_idle',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_idle',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_item_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'button_color_item_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_item_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_item_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_item_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_item_hover',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_item_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_hover',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover span' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'button_color_active',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_active',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active span' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_active',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_active',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active',
            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_active',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> BUTTON ICON
         */

        $this->start_controls_section(
            'style_button_icon',
            [
                'label' => esc_html__('Button Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_type' => 'font'
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em', 'rem', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--icon-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'button_animation_style!' => 'icon_size_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon,
                     {{WRAPPER}}.has-icon_size_animation .wgl-widget__button .wgl-icon::before,
                     {{WRAPPER}}.has-moving_icon .wgl-widget__button .wgl-icon::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-widget__button .wgl-icon',
            ]
        );
        $this->start_controls_tabs( 'tabs_icon_button' );
        $this->start_controls_tab(
            'tab_icon_button_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_idle',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_idle',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_idle',
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
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_item_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_item_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_item_hover',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_item_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_item_hover',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_item_hover',
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
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_hover',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_hover',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_hover',
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
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:hover .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'button_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => '--read-more-icon-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_active',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon,
				     {{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_offset_active',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_active',
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
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /** STYLE -> BUTTON ANIMATION */

        $this->start_controls_section(
            'style_button_animation',
            [
                'label' => esc_html__( 'Button Animation', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_type' => 'font'
                ],
            ]
        );

        $this->add_control(
            'button_animation_style',
            [
                'label' => esc_html__('Animation Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'moving_icon' => esc_html__('Moving Icon', 'courto-core'),
                    'letter_animation' => esc_html__('Letter Animation', 'courto-core'),
                    'background_gradient' => esc_html__('Background Gradient', 'courto-core'),
                    'border_gradient' => esc_html__('Border Gradient', 'courto-core'),
                    'separated' => esc_html__('Separated Button', 'courto-core'),
                    'highlight_animation' => esc_html__('Highlight Animation', 'courto-core'),
                    'bg_animation' => esc_html__('Background Animation ', 'courto-core'),
                    'border_animation' => esc_html__('Border Animation', 'courto-core'),
                    'magnetic' => esc_html__('Magnetic', 'courto-core'),
                    'icon_size_animation' => esc_html__('Icon Size Animation', 'courto-core'),
                    'icon_visibility' => esc_html__('Icon Visibility', 'courto-core'),
                ],
                'prefix_class' => 'has-',
            ]
        );

        /** Moving Icon Animation */
        $this->add_responsive_control(
            'moving_icon_wrapper_size',
            [
                'label' => esc_html__('Icon BG Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'moving_icon', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        /** Background Gradient Animation */
        $this->add_responsive_control(
            'background_gradient_location_1',
            [
                'label' => esc_html__('Primary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_location_2',
            [
                'label' => esc_html__('Secondary Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--bg-gradient-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_control(
            'background_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [ 'button_animation_style' => 'background_gradient', ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 90 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__( 'Center Center', 'courto-core' ),
                    'center left' => esc_html__( 'Center Left', 'courto-core' ),
                    'center right' => esc_html__( 'Center Right', 'courto-core' ),
                    'top center' => esc_html__( 'Top Center', 'courto-core' ),
                    'top left' => esc_html__( 'Top Left', 'courto-core' ),
                    'top right' => esc_html__( 'Top Right', 'courto-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'courto-core' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'courto-core' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'courto-core' ),
                    'var(--h-pos) var(--v-pos)' => esc_html__( 'Custom', 'courto-core' ),
                ],
                'default' => 'center center',
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_h_position',
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
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_gradient_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => [ 'min' => -2560, 'max' => 2560 ],
                    '%' => [ 'min' => -100, 'max' => 200 ],
                    'vw' => [ 'min' => -100, 'max' => 100 ],
                ],
                'condition' => [
                    'button_animation_style' => 'background_gradient',
                    'background_gradient_type' => 'radial',
                    'background_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_background_tabs', [
            'condition' => [ 'button_animation_style' => 'background_gradient' ]
        ]);
        $this->start_controls_tab(
            'gradient_background_tab_idle', [
            'label' => esc_html__( 'Idle', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_item_hover', [
            'label' => esc_html__( 'Hover', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_item_hover',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_item_hover',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_hover', [
            'label' => esc_html__( 'Hover', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_hover',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#A96FF3',
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_hover',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#5A76F7',
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_background_tab_active', [
            'label' => esc_html__( 'Active', 'courto-core' )
        ]);
        $this->add_control(
            'gradient_background_primary_active',
            [
                'label' => esc_html__( 'Primary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_background_secondary_active',
            [
                'label' => esc_html__( 'Secondary BG Gradient Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Gradient Animation */
        $this->add_responsive_control(
            'gradient_border_width',
            [
                'label' => esc_html__( 'Border Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'border_gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-mask-border: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'gradient_border_tabs',
            [ 'condition' => [ 'button_animation_style' => 'border_gradient' ] ]
        );
        $this->start_controls_tab(
            'gradient_border_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_idle',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_idle',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_border_tab_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_item_hover',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_item_hover',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'gradient_border_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'gradient_border_color_primary_hover',
            [
                'label' => esc_html__( 'Gradient Color Primary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-primary: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gradient_border_color_secondary_hover',
            [
                'label' => esc_html__( 'Gradient Color Secondary', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Separated Button Animation */
        $this->add_responsive_control(
            'icon_wrapper_size',
            [
                'label' => esc_html__( 'Icon Wrapper Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'separated' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => '--wgl-icon-wrapper: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Highlight Animation */
        $this->add_control(
            'stroke_highlight_color_normal',
            [
                'label' => esc_html__( 'Stroke Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .highlight_svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'stroke_highlight_width_normal',
            [
                'label' => esc_html__( 'Stroke Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'condition' => [ 'button_animation_style' => 'highlight_animation' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 10 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .highlight_svg path' => 'stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /** Background Animation */

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_bg_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'selector' => '{{WRAPPER}} .wgl-widget__button::after',
            ]
        );
        $this->add_control(
            'button_bg_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_animation', [
            'condition' => [ 'button_animation_style' => 'bg_animation' ],
        ] );
        $this->start_controls_tab(
            'button_animation_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_idle',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_idle',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_w',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_item_hover',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_item_hover',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_item_hover',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_size_h_item_hover',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_border_radius_item_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_item_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_item_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_hover',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_bg_y_pos_hover',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_hover',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_hover',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'render_type' => 'template',
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_bg_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus)::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_animation_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_responsive_control(
            'button_bg_x_pos_active',
            [
                'label' => esc_html__( 'Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_y_pos_active',
            [
                'label' => esc_html__( 'Vertical Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => '--pos-y:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_w_active',
            [
                'label' => esc_html__('Background Size - Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_bg_size_h_active',
            [
                'label' => esc_html__('Background Size - Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'button_bg_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [ 'button_animation_style' => 'bg_animation' ],
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'button_bg_bg_active',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_animation_style' => 'bg_animation',
                    'button_bg_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Border Animation */
        $this->add_control(
            'button_border_animation_revers',
            [
                'label' => esc_html__( 'Revers This Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'revers',
                'prefix_class' => '',
                'condition' => [ 'button_animation_style' => 'border_animation' ],
            ]
        );

        $this->add_control(
            'button_border_animation_color',
            [
                'label' => esc_html__( 'Animation Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button' => '--ab-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_offset',
            [
                'label' => esc_html__('Animation Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 20, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 6],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button' => '--ab-offset: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_width',
            [
                'label' => esc_html__('Animation Border Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 10, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button' => '--ab-width: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_border_animation_extend',
            [
                'label' => esc_html__('Mow Much to Extend the Border', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 50, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'border_animation' ],
                'default' => ['size' => 4],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button' => '--ab-extend: {{SIZE}};',
                ],
            ]
        );

        /** Magnetic Threshold */

        $this->add_control(
            'button_magnetic_threshold',
            [
                'label' => esc_html__('Magnetic Threshold', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 1920, 'step' => 1],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 500],
            ]
        );

        $this->add_control(
            'button_magnetic_strong',
            [
                'label' => esc_html__('Magnetic Strong', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'style_transfer' => true,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0.05, 'max' => 1, 'step' => 0.05],
                ],
                'condition' => [ 'button_animation_style' => 'magnetic' ],
                'default' => ['size' => 0.2],
            ]
        );

        /** Icon Size Animation */
        $this->add_control(
            'button_icon_bg_animation',
            [
                'label' => esc_html__( 'Background Size(px)', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100, 'step' => 1 ] ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-size: {{SIZE}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'button_icon_size_animation_tabs', [
            'condition' => [ 'button_animation_style' => 'icon_size_animation' ],
        ] );
        $this->start_controls_tab(
            'button_icon_size_animation_tab_idle',
            [ 'label' => esc_html__( 'Idle', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_idle',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_idle',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_item_hover',
            [ 'label' => esc_html__( 'Item Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_item_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_item_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:hover .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_hover',
            [ 'label' => esc_html__( 'Hover', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_hover',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_hover',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'button_icon_size_animation_tab_active',
            [ 'label' => esc_html__( 'Active', 'courto-core' ) ]
        );
        $this->add_control(
            'button_icon_size_animation_active',
            [
                'label' => esc_html__( 'Icon Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon' => '--icon-scale: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_animation_active',
            [
                'label' => esc_html__( 'Background Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper .wgl-widget__button:active .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        /** Icon Visibility */
        $this->add_control(
            'icon_visibility',
            [
                'label' => esc_html__( 'Icon Visibility', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [ 'button_animation_style' => 'icon_visibility' ],
                'options' => [
                    'default' => esc_html__( 'Default', 'courto-core' ),
                    'revert' => esc_html__( 'Revert', 'courto-core' ),
                ],
                'default' => 'default',
                'prefix_class' => 'icon-visibility-'
            ]
        );

        $this->end_controls_section();
    }

    protected function add_mobile_breakpoint() {
        // The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
        $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
        $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $avaliable_breakpoints = [];
        foreach ( $active_devices as $breakpoint_key ) {
            $label = 'desktop' === $breakpoint_key ? esc_html__( 'Desktop', 'courto-core' ) : $active_breakpoints[ $breakpoint_key ]->get_label();
            $avaliable_breakpoints[$breakpoint_key] = $label;
        }

        $this->add_control(
            'wgl_mobile_breakpoint',
            [
                /* translators: %s: Device Name. */
                'label' => esc_html__( 'Set Mobile Template On:', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => $avaliable_breakpoints,
                'default' => 'mobile',
            ]
        );
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // Allowed HTML tags
        $allowed_html = [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
            'li' => ['id' => true, 'class' => true, 'style' => true],
        ];

        $this->add_render_attribute('timeline-vertical', 'class', 'wgl-timeline-vertical');
        if ($_s[ 'add_appear' ]) $this->add_render_attribute('timeline-vertical', 'class', 'appear_animation');
        if ($_s[ 'add_line_animation' ]){
            $this->add_render_attribute( 'timeline-vertical', [
                'class' => 'v_line_animation',
                'data-visibility-ratio' => (!empty($_s['visibility_ratio']['size'] || 0 === $_s['visibility_ratio']['size']) ? esc_attr( $_s['visibility_ratio']['size']) : 1.25 ),
            ] );
        }

        // Read more button
        $btn_icon = '';
        if ($_s['add_read_more']) {
            $this->add_render_attribute('btn', 'class',
                [
                    'tlw__button',
                    'wgl-widget__button',
                    $_s['button_type'] ?? '',
                    !$_s['read_more_text'] ? 'no_text' : '',
                    ! empty($_s['read_more_icon_align']) ? 'align-icon-' . ($_s['read_more_icon_align']) : '',
                ]
            );

            $btn_icon = '';
            // ↓ Icon|Image
            if ('font' === $_s['read_more_icon_type'] && isset($_s['read_more_icon_fontawesome']['value'])) {
                $migrated = isset( $_s['__fa4_migrated']['read_more_icon_fontawesome'] );
                $is_new = Icons_Manager::is_migration_allowed();
                if ( $is_new || $migrated ) {
                    ob_start();
                    Icons_Manager::render_icon($_s['read_more_icon_fontawesome'], ['class' => 'read-more-icon', 'aria-hidden' => 'true']);
                    $btn_icon = ob_get_clean();
                }
                if ('svg' === $_s['read_more_icon_fontawesome']['library']) {
                    $wrapper_icon = '<span class="read-more-icon read-more-svg">';
                    $wrapper_icon .= $btn_icon;
                    $wrapper_icon .= '</span>';
                    $btn_icon = $wrapper_icon;
                }
                if ( 'moving_icon' === $_s['button_animation_style']) {
                    $btn_icon .= $btn_icon;
                }

                $btn_icon = !!$btn_icon ? '<span class="wgl-icon"> ' . $btn_icon . '</span>' : '';
                $btn_icon = 'wgl-button' === $_s['button_type'] ? '<div class="icon-wrapper">' . $btn_icon . '</div>' : $btn_icon;
            }elseif('button-read-more' === $_s['button_type']){
                $btn_icon = '<span class="read-more-icon"></span>';
            }else{
                $this->add_render_attribute(['btn' => ['class' => [ 'no_media']]]);
            }

            // ↑ icon|image
        }


        // The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
        $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
        $key = array_search($_s['wgl_mobile_breakpoint'], $active_devices);
        $all_breakpoints = array_slice($active_devices, $key);
        foreach($all_breakpoints as $breakpoint){
            $this->add_render_attribute( 'timeline-vertical', [ 'class' => [ 'breakpoint_on-' . $breakpoint ] ] );
        }


        // Read more button
        if (!empty($_s['read_more_text']) && ( 'letter_animation' === $_s[ 'button_animation_style' ] )) {
            $letters = '';
            $len = mb_strlen($_s['read_more_text'], 'UTF-8');
            for ($i = 0; $i < $len; $i++) {
                $value = mb_substr(esc_html($_s['read_more_text']), $i, 1, 'UTF-8');
                $letters .= $value == ' ' ? ' ' : '<span class="letter">' . $value . '</span>';
            }
            $_s['read_more_text'] = $letters;
        }else{
            $_s['read_more_text'] = esc_html($_s['read_more_text']);
        }


        // Render
        echo '<div ', $this->get_render_attribute_string('timeline-vertical'), '>';

        foreach ($_s['items'] as $index => $item) {


            $tlv_button = '';
            if ($_s['add_read_more'] && !empty($item['link']['url'])) {
                // Link
                $link = $this->get_repeater_setting_key('link', 'list', $index);
                $this->add_link_attributes($link, $item['link']);

                $tlv_button = '<div class="wgl-button-wrapper'.('button-read-more' === $_s['button_type'] ? ' rm_btn' : '' ).'">';
                    $tlv_button .= 'button-read-more' === $_s['button_type'] ? '<div class="read-more-wrap">' : '';
                        $tlv_button .= '<a '.$this->get_render_attribute_string($link).' '.$this->get_render_attribute_string('btn').'>';
                            $tlv_button .= 'wgl-button' === $_s['button_type'] ? '<div class="button__content">' : '';
                                $tlv_button .= $btn_icon ?: '';
                                $tlv_button .= $_s['read_more_text'] ? '<span class="button__text">' . $_s['read_more_text'] . '</span>' : '';
                            $tlv_button .= 'wgl-button' === $_s['button_type'] ? '</div>' : '';
                        $tlv_button .= '</a>';
                    $tlv_button .= 'button-read-more' === $_s['button_type'] ? '</div>' : '';
                $tlv_button .= '</div>';
            }

            $tlv__item = $this->get_repeater_setting_key('tlv__item', 'list', $index);
            $this->add_render_attribute([$tlv__item => [
                'class' => [
                    'tlv__items-wrapper',
                    'elementor-repeater-item-'. $item['_id']
                ]
            ]]);
            ?><div <?php echo $this->get_render_attribute_string($tlv__item) ?>><?php

            $thumbnail_idle = $this->get_repeater_setting_key('thumbnail', 'list', $index);
            $url_idle = $item['thumbnail_idle']['url'] ?? false;
            $this->add_render_attribute($thumbnail_idle, [
                'class' => 'tlv__thumbnail--idle',
                'src' => $url_idle ? esc_url($url_idle) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail_idle']),
            ]);

            $thumbnail_hover = $this->get_repeater_setting_key('thumbnail_hover', 'list', $index);
            $url_hover = $item['thumbnail_hover']['url'] ?? false;
            $this->add_render_attribute($thumbnail_hover, [
                'class' => 'tlv__thumbnail--hover',
                'src' => $url_hover ? esc_url($url_hover) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail_hover']),
            ]);
            ?><div class="tlv__item<?php echo $url_idle ? ' has_media' : '';?>">
            <div class="tlv__curve-wrapper">
                <div class="dot"><i class="wgl-svg-icon"><?php echo wgl_dynamic_styles()->wgl_theme_svg()['arrow-triangle']; ?></i></div><?php

                if (!!$item['number']) {
                    ?><span class="tlv__number"><?php
                        echo $item['number'];
                    ?></span><?php
                }

            ?></div>
            <div class="tlv__volume-wrapper">
                <div class="tlv__content-wrapper"><?php

                    if (!!$item['bg_date']) {
                        ?><span class="tlv__bg_date"><?php
                            echo $item['bg_date'];
                        ?></span><?php
                    }

                    ?><div class="tlv__content"><?php

                        if ('outer' !== $_s['media_position'] && $url_idle){

                            if (!!$item['bg_date']) {
                                ?><span class="tlv__bg_date"><?php
                                echo $item['bg_date'];
                                ?></span><?php
                            }

                            ?><div class="tlv__media"><?php
                                if ( $url_hover ) {
                                    echo '<img ', $this->get_render_attribute_string( $thumbnail_hover ), '/>';
                                }
                                echo '<img ', $this->get_render_attribute_string( $thumbnail_idle ), '/>';
                            ?></div><?php
                        }

                        if (!empty($item['date']) || !empty($item['title'])) {
                            echo '<div class="tlv__content-header">';

                                if (!empty($item['date'])) {
                                    ?><div class="tlv__date-wrapper"><?php
                                        ?><span class="tlv__date"><?php
                                            echo $item['date'];
                                        ?></span><?php
                                    ?></div><?php
                                }

                                if (!empty($item['title'])) {
                                    ?><h4 class="tlv__title"><?php
                                        echo $item['title'];
                                    ?></h4><?php
                                }

                            echo '</div>';
                        }

                        if (!empty($item['content']) || !empty($tlv_button)) {
                            echo '<div class="tlv__content-footer">';
                                if (!empty($item['content'])) {
                                    ?><div class="tlv__text"><?php
                                        echo wp_kses( $item['content'], $allowed_html );
                                    ?></div><?php
                                }

                                if (!empty($tlv_button)) {
                                    echo $tlv_button;
                                }
                            echo '</div>';
                        }

                    ?></div>
                </div>
            </div>
            </div><?php

            if ('outer' === $_s['media_position'] && $url_idle){
                ?><div class="tlv__item image"><?php
                    ?><div class="tlv__media"><?php
                        if ( $url_hover ) {
                            echo '<img ', $this->get_render_attribute_string( $thumbnail_hover ), '/>';
                        }
                        echo '<img ', $this->get_render_attribute_string( $thumbnail_idle ), '/>';
                    ?></div><?php
                ?></div><?php
            }

            ?></div><?php
        }

        echo '<div class="tlv__item-start"></div>';
        echo '<div class="tlv__item-end"></div>';
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
