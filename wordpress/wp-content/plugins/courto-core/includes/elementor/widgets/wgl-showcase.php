<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-showcase.php`.
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

class WGL_Showcase extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-showcase';
    }

    public function get_title()
    {
        return esc_html__('WGL Showcase', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-showcase';
    }

    public function get_keywords()
    {
        return [ 'showcase', 'title' ];
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
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Showcase Title', 'courto-core'),
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
            'bg_text',
            [
                'label' => esc_html__('Background Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.', 'courto-core'),
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
                'prefix' => 'showcase_',
            ]
        );

        $repeater->start_controls_tabs( 'items_tabs' );
        $repeater->start_controls_tab(
            'items_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $repeater->add_control(
            'items_bg_idle_color',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .showcase__background::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'items_bg_idle_image',
            [
                'label' => esc_html__( 'Background Image', 'courto-core' ),
                'type'  => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .showcase__background::before' => 'background-image: url({{URL}});',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'items_tab_hover',
            ['label' => esc_html__('Hover/Active' , 'courto-core')]
        );

        $repeater->add_control(
            'items_bg_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .showcase__background::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'items_bg_hover_image',
            [
                'label' => esc_html__( 'Background Image', 'courto-core' ),
                'type'  => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .showcase__background::after' => 'background-image: url({{URL}});',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'items_tab_responsive',
            ['label' => esc_html__('Responsive' , 'courto-core')]
        );

        $repeater->add_control(
            'items_bg_responsive_color',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra {{CURRENT_ITEM}} .showcase__background::after' => 'background-color: {{VALUE}};',
                    'body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet {{CURRENT_ITEM}} .showcase__background::after' => 'background-color: {{VALUE}};',
                    'body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra {{CURRENT_ITEM}} .showcase__background::after' => 'background-color: {{VALUE}};',
                    'body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile {{CURRENT_ITEM}} .showcase__background::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'items_bg_responsive_image',
            [
                'label' => esc_html__( 'Background Image', 'courto-core' ),
                'type'  => Controls_Manager::MEDIA,
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra {{CURRENT_ITEM}} .showcase__background::after' => 'background-image: url({{URL}});',
                    'body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet {{CURRENT_ITEM}} .showcase__background::after' => 'background-image: url({{URL}});',
                    'body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra {{CURRENT_ITEM}} .showcase__background::after' => 'background-image: url({{URL}});',
                    'body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile {{CURRENT_ITEM}} .showcase__background::after' => 'background-image: url({{URL}});',
                ],
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'subtitle' => esc_html__( '01', 'courto-core' ),
                        'title' => esc_html__( 'Junior Academy', 'courto-core' ),
                        'content' => esc_html__( 'A structured development pathway for young players focusing on technique, footwork, coordination, and progressive match play.', 'courto-core' ),
                    ],
                    [
                        'subtitle' => esc_html__( '02', 'courto-core' ),
                        'title' => esc_html__( 'Kids Program', 'courto-core' ),
                        'content' => esc_html__( 'Fun, foundational lessons for the youngest players using age-appropriate courts, rackets, and balls to develop coordination.', 'courto-core' ),
                    ],
                    [
                        'subtitle' => esc_html__( '03', 'courto-core' ),
                        'title' => esc_html__( 'Adult Training', 'courto-core' ),
                        'content' => esc_html__( 'Group and individual lessons designed to improve technique, strategy, and overall fitness for adult players of all levels.', 'courto-core' ),
                        'link_active' => 'yes'
                    ],
                    [
                        'subtitle' => esc_html__( '04', 'courto-core' ),
                        'title' => esc_html__( 'Advanced Program', 'courto-core' ),
                        'content' => esc_html__( 'High-intensity training for experienced players seeking to refine technique, develop advanced tactics, and compete in league.', 'courto-core' ),
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'items_justify_content',
            [
                'label' => esc_html__( 'Justify Items', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'style_transfer' => true,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__( 'Space Around', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__( 'Space Evenly', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .showcase__item_inner' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_justify',
            [
                'label' => esc_html__( 'Justify Content', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'style_transfer' => true,
                'options' => [
                    '0' => [
                        'title' => esc_html__( 'Default', 'courto-core' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    '0 auto' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                ],
                'condition' => ['items_justify_content' => 'flex-start'],
                'default' => '0 auto',
                'selectors' => [
                    '{{WRAPPER}} .showcase__content_wrapper' => 'margin: {{VALUE}};',
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
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'default' => 'center',
                'mobile_default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .showcase__item,
                     {{WRAPPER}} .showcase__item_inner' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .showcase__item,
                     {{WRAPPER}} .showcase__content_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_animation',
            [
                'label' => esc_html__('Content Animation', 'courto-core'),
                'description' => esc_html__('Show Content on Hover', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('True', 'courto-core'),
                'label_off' => esc_html__('False', 'courto-core'),
                'default' => '',
            ]
        );
        $this->add_mobile_breakpoint();
        $this->end_controls_section();

        /**
         * CONTENT -> BUTTON
         */

        $this->start_controls_section(
            'section_style_link',
            [
                'label' => esc_html__('Link', 'courto-core'),
            ]
        );

        $this->add_control(
            'add_item_link',
            [
                'label' => esc_html__('Whole Item Link', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => 'yes',
            ]
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
                'condition' => [ 'add_read_more' => 'yes' ],
                'label_block' => true,
                'placeholder' => esc_html__('Read More', 'courto-core'),
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
                'default' => 'wgl-button',
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
                'default' => 'font',
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
         * STYLE -> ITEM CONTAINER
         */

        $this->start_controls_section(
            'style_item_container',
            [
                'label' => esc_html__('Item Container', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_overflow',
            [
                'label' => esc_html__('Items Overflow', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'courto-core'),
                    'overflow: visible;' => esc_html__('Visible', 'courto-core'),
                    'overflow: hidden;' => esc_html__('Hidden', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => '{{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_height',
            [
                'label' => esc_html__( 'Items Min Height', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'vh', 'vw', 'custom'],
                'range' => ['px' => ['max' => 500]],
                'default' => ['size' => 152, 'unit' => 'px'],
                'tablet_default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin (px)', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '20',
                    'right' => '50',
                    'bottom' => '20',
                    'left' => '38',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-showcase' => 'gap: {{SIZE}}{{UNIT}};',
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
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-showcase' => '--divider-width: {{SIZE}}px; --divider-opacity: 1;',
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
                    '{{WRAPPER}} .showcase__item:first-child::after' => 'border-top: unset !important;',
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
                    '{{WRAPPER}} .showcase__item:last-child::after' => 'border-bottom: unset !important;',
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
                'condition' => ['item_divider[size]!' => ['', 0]],
                'default' => [
                    'bottom' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item::after' => 'top: min({{top}}{{UNIT}}, 0px); right: {{RIGHT}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .showcase__item:last-child::after' => 'bottom: min({{top}}{{UNIT}}, 0px);',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_divider_z_index',
            [
                'label' => esc_html__( 'Divider Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => ['item_divider[size]!' => ['', 0]],
                'min' => -5,
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} .showcase__item::after' => 'z-index: {{VALUE}};',
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
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [
                        'default' => '#F5F5F5',
                    ],
                    'position' => [ 'default' => 'center center' ],
                    'repeat' => [ 'default' => 'no-repeat' ],
                    'size' => [ 'default' => 'cover' ],
                ],
                'selector' => '{{WRAPPER}} .showcase__background::before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_idle',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'default' => WGL_Globals::get_secondary_color() ]
                ],
                'selector' => '{{WRAPPER}} .showcase__item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_idle',
                'selector' => '{{WRAPPER}} .showcase__item',
            ]
        );
        $this->add_control(
            'item_divider_color_idle',
            [
                'label' => esc_html__('Divider Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['item_divider[size]!' => ['', 0]],
                'default' => WGL_Globals::get_h_font_color(0.2),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item' => '--divider-color: {{VALUE}}; --divider-color-last: {{VALUE}};',
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
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'default' => WGL_Globals::get_secondary_color(),
                    ],
                    'position' => [ 'default' => 'center center' ],
                    'repeat' => [ 'default' => 'no-repeat' ],
                    'size' => [ 'default' => 'cover' ],
                ],
                'selector' => '{{WRAPPER}} .showcase__background::after',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'default' => WGL_Globals::get_secondary_color(0) ]
                ],
                'selector' => '{{WRAPPER}} .showcase__item:is(.active, :hover)',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'selector' => '{{WRAPPER}} .showcase__item:is(.active, :hover)'
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover),
                     {{WRAPPER}} .showcase__item:is(.active, :hover) + .showcase__item' => '--divider-color: {{VALUE}};',
                    '{{WRAPPER}} .showcase__item:last-child:is(.active, :hover)' => '--divider-color-last: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item' => 'transition: {{SIZE}}s',
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
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'default' => WGL_Globals::get_secondary_color(),
                    ],
                    'position' => [ 'default' => 'center center' ],
                    'repeat' => [ 'default' => 'no-repeat' ],
                    'size' => [ 'default' => 'cover' ],
                ],
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__background::after,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__background::after,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__background::after,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__background::after',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_responsive',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'default' => WGL_Globals::get_secondary_color(0) ]
                ],
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item'
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item' => '--divider-color: {{VALUE}}; --divider-color-last: {{VALUE}};',
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
        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__( 'Title Width', 'courto-core' ),
                'description' => esc_html__( 'Title Width has Priority', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', '%', 'vw', 'custom'],
                'default' => ['size' => 35, 'unit' => '%'],
                'tablet_default' => ['size' => 100, 'unit' => '%'],
                'range' => [ 'px' => ['max' => 1200] ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_align_self',
            [
                'label' => esc_html__( 'Align Self', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'align-self: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__('Self Alignment', 'courto-core'),
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
                'selectors_dictionary' => [
                    'left' =>   'flex-start; text-align: left',
                    'center' => 'center; text-align: center',
                    'right' =>  'flex-end; text-align: right',
                ],
                'tablet_default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .wgl-showcase.breakpoint_on-mobile .showcase__title' => 'align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => -5,
                'selectors' => [
                    '{{WRAPPER}} .showcase__title, {{WRAPPER}} .title' => 'z-index: {{VALUE}}',
                ],
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
                'separator' => 'before',
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
                        'default' => ['size' => 32, 'unit' => 'px'],
                    ],
                    'line_height' => [ 'default' => ['size' => 1.25, 'unit' => 'em'] ],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .title',
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
                    '{{WRAPPER}} .title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};--title-p-r: {{RIGHT}}{{UNIT}}',
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
                    '{{WRAPPER}} .title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'title_tabs' );
        $this->start_controls_tab(
            'title_color_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_control(
            'title_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [ 'title_stroke_size[size]!' => ['', 0] ],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_skew',
            [
                'label' => esc_html__('Skew the title', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => ['min' => -45, 'max' => 45],
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'transform: skew({{SIZE}}deg)',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_animation_offset_idle',
            [
                'label' => esc_html__('Animation Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom' ],
                'range' => [
                    'px' => ['min' => -200, 'max' => 500],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'mobile_default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__title' => 'transform: translateX({{SIZE}}{{UNIT}})',
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__title' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .title' => '-webkit-text-stroke-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .title' => 'transform: skew({{SIZE}}deg)',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_animation_offset_hover',
            [
                'label' => esc_html__('Animation Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom' ],
                'range' => [
                    'px' => ['min' => -200, 'max' => 500],
                ],
                'tablet_default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__title' => 'transform: translateX({{SIZE}}{{UNIT}})',
                    '{{WRAPPER}} .showcase__title' => ' --title-a-o: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__title,
                     {{WRAPPER}} .title' => 'transition: {{SIZE}}s',
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .title' => 'color: {{VALUE}};'
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__title' => 'background-color: {{VALUE}};',
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .title' => '-webkit-text-stroke-color: {{VALUE}};',
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .title,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .title,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .title,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .title' => 'transform: skew({{SIZE}}deg)',
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
        $this->add_control(
            'subtitle_position',
            [
                'label' => esc_html__('Subtitle Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'courto-core'),
                    'left_w' => esc_html__('Left of the Title Wrapper', 'courto-core'),
                    'right_w' => esc_html__('Right of the Title Wrapper', 'courto-core'),
                    'top' => esc_html__('Top of the Title', 'courto-core'),
                    'bottom' => esc_html__('Bottom of the Title', 'courto-core'),
                    'left' => esc_html__('Left of the Title', 'courto-core'),
                    'right' => esc_html__('Right of the Title', 'courto-core'),
                ],
                'default' => 'left',
                'prefix_class' => 'subtitle_position-'
            ]
        );
        $this->add_responsive_control(
            'subtitle_visibility',
            [
                'label' => esc_html__('Subtitle Visibility', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('Hide', 'courto-core'),
                    'inline-flex' => esc_html__('Show', 'courto-core'),
                ],
                'default' => 'inline-flex',
                'tablet_default' => 'none',
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'display: {{VALUE}};',
                ],
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
                'default' => [ 'size' => 50, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_align_self',
                [
                'label' => esc_html__( 'Align Self', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'align-self: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_alignment',
            [
                'label' => esc_html__('Self Alignment', 'courto-core'),
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_font_subtitle',
                'separator' => 'before',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [ 'default' => [ 'size' => 32, 'unit' => 'px' ] ],
                    'font_weight' => [ 'default' => 700 ],
                    'line_height' => ['default' => ['size' => 1.25, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => -0.04, 'unit' => 'em']],
                    'font_style' => [ 'default' => 'italic' ],
                ],
                'selector' => '{{WRAPPER}} .showcase__subtitle',
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
                'default' => 'header',
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'transform: translateY( {{SIZE}}{{UNIT}} );',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_offset',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'custom'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'condition' => [ 'subtitle_position' => 'right' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_offset_right',
            [
                'label' => esc_html__('Right Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'custom'],
                'range' => [
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'condition' => [ 'subtitle_position' => 'left' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'margin-right: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .showcase__subtitle',
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
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'color: {{VALUE}};'
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
                    '{{WRAPPER}} .showcase__subtitle' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .showcase__subtitle' => 'border-color: {{VALUE}}'
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__subtitle' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__subtitle' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__subtitle' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'subtitle_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .showcase__subtitle' => 'transition: {{SIZE}}s',
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__subtitle' => 'color: {{VALUE}};'
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__subtitle' => 'background-color: {{VALUE}}',
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__subtitle,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__subtitle' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> Background Text
         */

        $this->start_controls_section(
            'style_bg_text',
            [
                'label' => esc_html__('Background Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'bg_text_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'min' => -5,
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_font_bg_text',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_size' => [
                        'default' => ['size' => 'clamp(56px, 14vw, 170px)', 'unit' => 'custom'],
                    ],
                    'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
                ],
                'selector' => '{{WRAPPER}} .showcase__bg_text',
            ]
        );

        $this->add_control(
            'bg_text_font',
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
                    '{{WRAPPER}} .showcase__bg_text' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => 'auto',
                    'right' => 'auto',
                    'bottom' => 'auto',
                    'left' => '0',
                    'unit' => 'custom',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'bg_text_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '10',
                    'unit' => '%',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '3',
                    'unit' => '%',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'bg_text_tabs' );
        $this->start_controls_tab(
            'bg_text_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'bg_text_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'bg_text_tab_hover',
            ['label' => esc_html__('Hover/Active' , 'courto-core')]
        );
        $this->add_control(
            'bg_text_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#EFEFEF',
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__bg_text' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bg_text_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.4],
                'selectors' => [
                    '{{WRAPPER}} .showcase__bg_text' => 'transition: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'bg_text_tab_responsive',
            ['label' => esc_html__('Responsive' , 'courto-core')]
        );
        $this->add_control(
            'bg_text_responsive',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#EFEFEF',
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__bg_text,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__bg_text,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__bg_text,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__bg_text' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__( 'Content Width', 'courto-core' ),
                'description' => esc_html__( 'Title Width has Priority', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem', '%', 'vw', 'custom'],
                'range' => [ 'px' => ['max' => 1200] ],
                'default' => ['size' => '500', 'unit' => 'px'],
                'tablet_default' => ['size' => '100', 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__content_wrapper,
                     {{WRAPPER}} .showcase__content' => '--sc-content-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_align_self',
            [
                'label' => esc_html__( 'Align Self', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .showcase__content_wrapper' => 'align-self: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .showcase__content_wrapper',
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'vw', 'custom'],
                'default' => [
                    'top' => '3',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '11',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('content_color_tab');
        $this->start_controls_tab(
            'custom_content_color_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__content_wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_content_color_hover',
            ['label' => esc_html__('Hover/Active', 'courto-core')]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__content_wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_transition',
            [
                'label' => esc_html__('Transition', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .showcase__content_wrapper' => 'transition: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_content_color_responsive',
            ['label' => esc_html__('Responsive', 'courto-core')]
        );
        $this->add_control(
            'content_color_responsive',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__content_wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__content_wrapper,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__content_wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__content_wrapper' => 'color: {{VALUE}};',
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
            'image_custom_width',
            [
                'label' => esc_html__( 'Image Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => [ 'px', 'vh', 'vw', 'custom'],
                'default' => ['size' => 'min(270px, 15vw)', 'unit' => 'custom'],
                'tanlet_default' => ['size' => 'min(270px, 20vw)', 'unit' => 'custom'],
                'mobile_default' => ['size' => 26, 'unit' => 'vw'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .showcase__image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label' => esc_html__( 'Image Horizontal Position', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    '%' => [ 'min' => -150, 'max' => 150 ],
                    'px' => [ 'min' => -1920, 'max' => 1920 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'default' => [ 'size' => 0, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .showcase__image' => '--wgl-image-position: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_near_title',
            [
                'label' => esc_html__( 'Place Image Near Title', 'mindy-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );
        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'tablet_default' => [
                    'top' => '0',
                    'right' => '30',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '20',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .showcase__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .showcase__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .showcase__item .showcase__image',
            ]
        );
        $this->add_responsive_control(
            'image_visibility',
            [
                'label' => esc_html__( 'Static Image Visibility', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'block' => esc_html__( 'Show', 'courto-core' ),
                    'none' => esc_html__( 'Hide', 'courto-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .image_on_responsive' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_absolute_visibility',
            [
                'label' => esc_html__( 'Absolute Image Visibility', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'courto-core' ),
                    'block' => esc_html__( 'Show', 'courto-core' ),
                    'none' => esc_html__( 'Hide', 'courto-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .image_on_default' => 'display: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .showcase__item .showcase__image' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .showcase__item .showcase__image',
            ]
        );
        $this->add_responsive_control(
            'image_rotation_idle',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .showcase__image' => '--wgl-image-rotate: {{SIZE}}deg;',
                ],
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__image' => 'border-color: {{VALUE}}'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__image',
            ]
        );
        $this->add_responsive_control(
            'image_rotation_hover',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .showcase__image' => '--wgl-image-rotate: {{SIZE}}deg;',
                ],
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__image' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_responsive',
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__image',
            ]
        );
        $this->add_responsive_control(
            'image_rotation_responsive',
            [
                'label' => esc_html__( 'Rotation', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                ],
                'default' => [ 'size' => 0, 'unit' => 'deg' ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .showcase__image,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .showcase__image' => '--wgl-image-rotate: {{SIZE}}deg;',
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
            'button_style_section',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );
        $this->add_responsive_control(
            'button_align_self',
            [
                'label' => esc_html__( 'Align Self', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-center-v',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Stretch', 'courto-core' ),
                        'icon' => 'eicon-flex eicon-align-stretch-v',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'align-self: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => ['min' => -100, 'max' => 100],
                    '%' => ['min' => -100, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'top: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_custom_fonts',
                'separator' => 'before',
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
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline']
                 ],
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
                    'button_custom_fonts_text_decoration' => 'underline'
                ],
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
                'tablet_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '-10',
                    'unit' => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'button_padding',
		    [
			    'label' => esc_html__('Inner Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                    'isLinked' => true
                ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-widget__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'border' => [ 'default' => 'none' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
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
                'size_units' => ['px', 'em', 'rem', '%', 'custom'],
                'range' => [
                    'px' => ['max' => 500],
                    '%' => ['max' => 100],
                ],
                'default' => ['size' => 'fit-content', 'unit' => 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button' );
        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle' , 'courto-core') ]
        );
        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0),
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
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline']
                ],
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
                    'button_border_border!' => ['', 'none'],
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
            'button_button_opacity_idle',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%'],
                'range' => [
                    '%' => ['min' => 0, 'max' => 1, 'step' => 0.02],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'opacity: {{SIZE}};',
                ],
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_item_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => 'background-color: {{VALUE}};',
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
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline']
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => 'border-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button',
            ]
        );

        $this->add_responsive_control(
            'button_button_opacity_hover_item',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%'],
                'range' => [
                    '%' => ['min' => 0, 'max' => 1, 'step' => 0.02],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => 'opacity: {{SIZE}};',
                ],
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [ 'label' => esc_html__( 'Hover Button', 'courto-core' ) ]
        );
        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover' => 'background-color: {{VALUE}};',
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
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline']
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover .button__text' => 'text-decoration-color: {{VALUE}};',
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
                    'button_border_border!' => ['', 'none'],
                ],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover' => 'border-color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover',

            ]
        );
        $this->add_responsive_control(
            'button_opacity_hover',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%'],
                'range' => [
                    '%' => ['min' => 0, 'max' => 1, 'step' => 0.02],
                ],
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover' => 'opacity: {{SIZE}};',
                ],
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
                    '{{WRAPPER}} .showcase__item .wgl-button-wrapper .wgl-widget__button:hover' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_responsive',
            [ 'label' => esc_html__( 'Responsive', 'courto-core' ) ]
        );
        $this->add_control(
            'button_color_responsive',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_responsive',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_decoration_color_responsive',
            [
                'label' => esc_html__('Decoration Line Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'button_custom_fonts_text_decoration' => ['line-through', 'overline', 'underline']
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-widget__button .button__text,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-widget__button .button__text,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-widget__button .button__text,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_responsive',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_border_border!' => ['', 'none'],
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_responsive',
                'condition' => [
                    'button_type' => 'wgl-button',
                ],
                'selector' => 'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button',

            ]
        );
        $this->add_responsive_control(
            'button_bg_backdrop_filter_responsive',
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_opacity_responsive',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['%'],
                'range' => [
                    '%' => ['min' => 0, 'max' => 1, 'step' => 0.02],
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => 'opacity: {{SIZE}};',
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
                'default' => WGL_Globals::get_h_font_color(),
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon::before' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:is(.active, :hover) .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
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
                'default' => WGL_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover' => '--read-more-icon-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon::before' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-widget__button:hover .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_button_responsive',
            ['label' => esc_html__('Responsive', 'courto-core')]
        );

        $this->add_control(
            'button_icon_color_responsive',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',

                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => '--read-more-icon-color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_bg_color_responsive',
            [
                'label' => esc_html__('Icon BG Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'button_type' => 'wgl-button',
                    'read_more_icon_fontawesome[value]!' => ''
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button::before' => 'background-color: {{VALUE}};',
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
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     
                     body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button::before,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button::before' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_icon_offset_responsive',
            [
                'label' => esc_html__( 'Icon Offset', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'max' => 250 ],
                ],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'button_icon_rotation_responsive',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['min' => -360, 'max' => 360],
                    'turn' => ['min' => -1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'tablet_default' => ['unit' => 'deg'],
                'mobile_default' => ['unit' => 'deg'],
                'selectors' => [
                    'body[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .breakpoint_on-tablet_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .breakpoint_on-tablet .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .breakpoint_on-mobile_extra .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .breakpoint_on-mobile .showcase__item .wgl-button-wrapper .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button .wgl-icon' => '--icon-bg-size: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--bg-gradient-location-1: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--bg-gradient-location-2: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .wgl-showcase__button' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
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
                    '{{WRAPPER}} .wgl-showcase__button' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--wgl-bg-gradient-primary) var(--bg-gradient-location-1), var(--wgl-bg-gradient-secondary) var(--bg-gradient-location-2));',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--h-pos: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--v-pos: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--wgl-mask-border: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button' => '--wgl-icon-wrapper: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .wgl-showcase__button::after',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'z-index: {{VALUE}}',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus)::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button' => '--ab-color: {{VALUE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button' => '--ab-offset: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button' => '--ab-width: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button' => '--ab-extend: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button .wgl-icon' => '--icon-bg-size: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-showcase__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item:hover .wgl-showcase__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus) .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:is(:hover, :focus) .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .showcase__item .wgl-showcase__button:active .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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

        WGL_Cursor::repeater_style_init(
            $this,
            [
                'section' => false,
                'repeater' => true,
                'prefix' => 'showcase_',
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
                'default' => 'tablet',
            ]
        );
    }

    protected function render()
    {
        // Build structure
        $_s = $this->get_settings_for_display();

        // Variables validation
        $img_size_string = $_s['img_size_string'] ?? '';
        $img_size_array = $_s['img_size_array'] ?? [];
        $img_aspect_ratio = $_s['img_aspect_ratio'] ?? '';

        $showcase__wrapper = '';
        foreach ($_s['items'] as $index => $item) {

            // Fields validation
            $title = $item['title'] ?? '';
            $subtitle = $item['subtitle'] ?? '';
            $link = $item['link'] ?? '';
            $content = $item['content'] ?? '';
            $bg_text = $item['bg_text'] ?? '';

            if (!$title && !$content) return;

            $has_link = !empty($link['url']);

            if ($has_link) {
                $link = $this->get_repeater_setting_key('link', 'items', $index);
                $this->add_link_attributes($link, $item['link']);
            }

            //Cursor
            if (isset($item['showcase_cursor_tooltip']) && '' != $item['showcase_cursor_tooltip']) {
                add_filter( 'wgl/courto_module_cursor', function () { return true; });
            }
            $cursor = new WGL_Cursor;
            $cursor_data = $cursor->build($this, array_merge($_s, $item), $item['_id'], 'showcase_');

            $showcase__item = $this->get_repeater_setting_key( 'item_link_active', 'items', $index );
            $this->add_render_attribute( $showcase__item, [
                'class' => [
                    'showcase__item',
                    'elementor-repeater-item-'. $item['_id'],
                    $item[ 'link_active' ] ? 'active' : '',
                    isset($item['showcase_cursor_tooltip']) && !empty($item['showcase_cursor_tooltip']) ? 'wgl-cursor-text' : ''
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

                    $this->add_render_attribute('showcase__image' . $index, [
                        'class' => 'showcase__image',
                    ]);

                    $image .= '<span '.$this->get_render_attribute_string('showcase__image' . $index).'>';
                        $image .=  '<img '. $this->get_render_attribute_string('image' . $index). '>';
                    $image .= '</span>';
                }
            }

            $subtitle = !empty($subtitle) ? '<span class="showcase__subtitle">'. $subtitle .'</span>' : '';
            $title = ('left_w' === $_s['subtitle_position'] ? $subtitle : '' )
                . '<div class="showcase__title">'
                    . ('left' === $_s['subtitle_position'] || 'top' === $_s['subtitle_position'] ? $subtitle : '' )
                    . (!empty($title) ? '<'.esc_attr($_s['title_tag']).' class="title">'.esc_html($title).'</'.esc_attr($_s['title_tag']).'>' : '')
                    . ('yes' === $_s['image_near_title'] ? '<span class="image_on_default">'.$image.'</span>' : '' )
                    . ('right' === $_s['subtitle_position'] || 'bottom' === $_s['subtitle_position'] ? $subtitle : '' )
                . '</div>'
                . ('right_w' === $_s['subtitle_position'] ? $subtitle : '' );

            if (!empty($content)) {
                $content = '<div class="showcase__content_wrapper"><div class="showcase__content">' . $content . '</div></div>';
            }

            if (!empty($bg_text)) {
                $bg_text = '<div class="showcase__bg_text">' . $bg_text . '</div>';
            }

            $showcase__wrapper .= '<div '. $this->get_render_attribute_string( $showcase__item ). ' ' . $cursor_data . '>';

                $showcase__wrapper .= '<div class="showcase__background"></div>';

                $showcase__wrapper .= $bg_text;

                if ($_s['add_item_link'] && $has_link) {
                    $showcase__wrapper .= '<a class="showcase__link" ' . $this->get_render_attribute_string($link) . '></a>';
                }

                $showcase__wrapper .= 'default' === $_s['subtitle_position'] ? $subtitle : '';

                $showcase__wrapper .= '<div class="image_on_responsive">'.$image.'</div>';

                $showcase__wrapper .= '<div class="showcase__item_inner">';
                    $showcase__wrapper .= $title;
                    $showcase__wrapper .= $content;
                    $showcase__wrapper .= $this->read_more_button($index, $has_link, $link, $_s);
                $showcase__wrapper .= '</div>';

                $showcase__wrapper .= 'yes' !== $_s['image_near_title'] ? '<div class="image_on_default">'.$image.'</div>' : '';
            $showcase__wrapper .= '</div>';
        }

        $this->add_render_attribute( 'general', [
            'class' => [
                'wgl-showcase',
                $_s['content_animation'] ? 'content_animation-yes' : '',
            ],
        ] );

        if (!empty($_s['wgl_mobile_breakpoint'])){
            $active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
            $key = array_search($_s['wgl_mobile_breakpoint'], $active_devices);
            $all_breakpoints = array_slice($active_devices, $key);
            foreach($all_breakpoints as $breakpoint){
                $this->add_render_attribute( 'general', [ 'class' => [ 'breakpoint_on-' . $breakpoint ] ] );
            }
        }

        ?><div <?php echo $this->get_render_attribute_string('general') ?>><?php
            echo $showcase__wrapper;
        ?></div><?php
    }

    public function read_more_button($index, $has_link, $link_item, $_s)
    {
        $s_button = $btn_icon = '';

        // Read more button
        if ($_s['add_read_more']) {
            $this->add_render_attribute('btn' . $index, 'class', ['wgl-showcase__button', 'wgl-widget__button', $_s['button_type'] ?? '', !empty($_s['read_more_icon_align']) ? 'align-icon-' . ($_s['read_more_icon_align']) : '']);

            // Media
            if ('font' === $_s['read_more_icon_type'] && isset($_s['read_more_icon_fontawesome']["value"])) {
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

                $btn_icon = !!$btn_icon ? '<div class="icon-wrapper"><div class="wgl-icon">' . $btn_icon . '</div></div>' : '';
            }elseif('button-read-more' === $_s['button_type']){
                $btn_icon = '<span class="read-more-icon"></span>';
            }else{
                $this->add_render_attribute(['btn' => ['class' => [ 'no_media']]]);
            }


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

            if (!empty($btn_icon) || $_s['read_more_text']) {
                $s_button = '<div class="wgl-button-wrapper'.('button-read-more' === $_s['button_type'] ? ' rm_btn' : '' ).'">';
                    $s_button .= 'button-read-more' === $_s['button_type'] ? '<div class="read-more-wrap">' : '';
                        $s_button .= sprintf('<%s %s %s>',
                            !$has_link ? 'div' : 'a',
                            $has_link ? $this->get_render_attribute_string($link_item) : '',
                            $this->get_render_attribute_string('btn' . $index)
                        );
                            $s_button .= 'wgl-button' === $_s['button_type'] ? '<div class="button__content">' : '';
                                $s_button .= $btn_icon ?: '';
                                $s_button .= $_s['read_more_text'] ? '<span class="button__text">'.$_s['read_more_text'].'</span>' : '';
                            $s_button .= 'wgl-button' === $_s['button_type'] ? '</div>' : '';
                        $s_button .= !$has_link ? '</div>' : '</a>';
                    $s_button .= 'button-read-more' === $_s['button_type'] ? '</div>' : '';
                $s_button .= '</div>';
            }
        }

        return $s_button;
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
