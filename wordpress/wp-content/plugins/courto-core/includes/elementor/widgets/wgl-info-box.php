<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-infobox.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border,
    Plugin,
    Repeater,
    Widget_Base,
    Controls_Manager,
    Utils,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background,
    Group_Control_Css_Filter};
use WGL_Extensions\{
	WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons,
    Templates\WGLInfoBoxes,
    Includes\WGL_Cursor
};

class WGL_Info_Box extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-infobox';
    }

    public function get_title()
    {
        return esc_html__('WGL Info Box', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-infobox';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_keywords() {
        return ['info', 'box', 'infobox', 'icon', 'button'];
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

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'condition' => ['icon_type!' => ''],
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_def.png',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_left.png',
                    ],
                    'top_left' => [
                        'title' => esc_html__('Top Left', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_left_top.png',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_right.png',
                    ],
                    'top_right' => [
                        'title' => esc_html__('Top Right', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_right_top.png',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_bottom.png',
                    ],
                ],
                'default' => 'top',
            ]
        );

        $this->add_mobile_breakpoint();

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'left',
                'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'title_alignment',
            [
                'label' => esc_html__('Title Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['layout' => ['top_left', 'top_right']],
                'toggle' => false,
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
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'courto-core'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'courto-core'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'courto-core'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                ],
                'default' => 'space-between',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * CONTENT -> ICON/IMAGE
         */

        $output = [];

        $output['view'] = [
            'label' => esc_html__('View', 'courto-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => ['icon_type' => ['font', 'number', 'image']],
            'options' => [
                'default' => esc_html__('Default', 'courto-core'),
                'bubble'   => esc_html__('Bubble', 'courto-core'),
            ],
            'default' => 'default',
            'prefix_class' => 'wgl-view-',
        ];

        WGL_Icons::init(
            $this,
            [
                'output' => $output,
                'section' => true,
                'media_types_options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'number' => [
                        'title' => esc_html__('Number', 'courto-core'),
                        'icon' => 'fa fa-list-ol',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'courto-core'),
                        'icon' => 'far fa-image',
                    ],
                ],
                'default' => [
                    'media_type' => 'font',
                    'icon' => [
                        'library' => 'solid',
                        'value' => 'fas fa-icons'
                    ],
                ],
            ]
        );

        /**
         * CONTENT -> CONTENT
         */

        $this->start_controls_section(
            'content_content',
            ['label' => esc_html__('Content', 'courto-core')]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'ib_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('Info Box Title', 'courto-core'),
            ]
        );
        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'items_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-infobox_title {{CURRENT_ITEM}}',
            ]
        );
        $repeater->add_control(
            'items_title_color_idle',
            [
                'label' => esc_html__('Title Color Idle', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ],
            ]
        );
        $repeater->add_control(
            'items_title_color_hover',
            [
                'label' => esc_html__('Title Color Hover', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'items_title_color_idle!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition: .4s;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'items_title_width',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'separator' => 'before',
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; display: inline-block; vertical-align: middle;',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'items_margin_title',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'items',
            [
                'label' => esc_html__('Titles', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['ib_title' => esc_html__('Info Box Title', 'courto-core')],
                ],
                'title_field' => '{{{ ib_title }}}',
            ]
        );

        $this->add_control(
            'ib_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,

			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

	    $this->add_control(
		    'ib_bg_text',
		    [
			    'label' => esc_html__('Background Text', 'courto-core'),
			    'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
			    'label_block' => true,
                'placeholder' => esc_attr__('ex: 01', 'courto-core'),
		    ]
	    );

        $this->add_control(
            'ib_content',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Description Text', 'courto-core'),
                'label_block' => true,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'courto-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINK
         */

        $this->start_controls_section(
            'content_link',
            ['label' => esc_html__('Link', 'courto-core')]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'module_link',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'add_read_more',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
                'label_block' => true,
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'module_link',
            [
                'label' => esc_html__('Whole Module Link', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
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
         * CONTENT -> CURSOR
         */

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
            ]
        );

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'style_general',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_min_height',
            [
                'label' => esc_html__('General Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1000],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_v_alignment',
            [
                'label' => esc_html__('General Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_wrapper' => 'justify-content: {{VALUE}}; align-items: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'general_padding',
            [
                'label' => esc_html__('General Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_min_height',
            [
                'label' => esc_html__('Content Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1000],
                ],
                'selectors' => [
                    '{{WRAPPER}} .content_wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_v_alignment',
            [
                'label' => esc_html__('Content Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'content_min_height[size]',
                            'operator' => '>',
                            'value' => '0'
                        ],
                    ]
                ],
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .content_wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__('Content Wrapper Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .content_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'style_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['icon_type' => 'font'],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 6, 'max' => 300],
                ],
                'default' => ['size' => 40],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_rotate',
            [
                'label' => esc_html__('Icon Rotate', 'courto-core'),
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
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_control(
            'icon_gradient',
            [
                'label' => esc_html__('Enable Icon Gradient', 'courto-core'),
                'description' => esc_html__('Not working with svg', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon .icon' => 'background: -webkit-linear-gradient(var(--ib-icon-clr-first), var(--ib-icon-clr-sec)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );

	    $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
		            'top' => '0',
		            'right' => '0',
		            'bottom' => '22',
		            'left' => '0',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'dynamic' => ['active' => true],
                'fields_options' => [ 'color' => [ 'type' => Controls_Manager::HIDDEN ] ],
                'selector' => '{{WRAPPER}} .media-wrapper .wgl-icon',
            ]
        );
        $this->start_controls_tabs( 'tabs_icons' );
        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'icon_primary_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .media-wrapper .wgl-icon .icon' => '--ib-icon-clr-first: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'icon_primary_sec_color_idle',
            [
                'label' => esc_html__('Gradient Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'icon_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon .icon' => '--ib-icon-clr-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_bg_color_idle',
                'condition' => ['view!' => 'bubble'],
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [ 'default' => WGL_Globals::get_primary_color() ],
                ],
                'selector' => '{{WRAPPER}} .media-wrapper .wgl-icon',
            ]
        );
        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_idle',
                'selector' => '{{WRAPPER}} .media-wrapper .wgl-icon',
            ]
        );
        $this->add_responsive_control(
            'icon_backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-icon' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'icon_primary_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon .icon' => '--ib-icon-clr-first: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_primary_sec_color_hover',
            [
                'label' => esc_html__('Gradient Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'icon_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon .icon' => '--ib-icon-clr-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_bg_color_hover',
                'condition' => ['view!' => 'bubble'],
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon',
            ]
        );
        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover',
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon',
            ]
        );
        $this->add_responsive_control(
            'icon_backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-icon' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> Number
         */

		$this->start_controls_section(
			'section_style_number',
			[
				'label' => esc_html__('Number', 'courto-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [ 'icon_type'  => 'number' ],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typo',
                'selector' => '{{WRAPPER}} .wgl-number',
            ]
        );

		$this->add_responsive_control(
			'number_space',
			[
				'label' => esc_html__('Margin', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
		            'top' => '0',
		            'right' => '0',
		            'bottom' => '20',
		            'left' => '0',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
				'selectors' => [
					'{{WRAPPER}} .wgl-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '16',
                    'right' => '20',
                    'bottom' => '16',
                    'left' => '20',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_border_width',
			[
				'label' => esc_html__('Border Width', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wgl-number' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->add_responsive_control(
			'number_border_radius',
			[
				'label' => esc_html__('Border Radius', 'courto-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'number_min_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} .number-wrapper' => 'min-width: {{SIZE}}{{UNIT}}; text-align: center;',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -5,
                'selectors' => [
                    '{{WRAPPER}} .wgl-number' => 'z-index: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'number_separator',
		    [
			    'label' => esc_html__('Show Separator?', 'courto-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'separator' => 'before',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .number-wrapper::after' => 'display: block;',
                ],
		    ]
	    );

	    $this->add_responsive_control(
		    'number_separator_margin',
		    [
			    'label' => esc_html__('Margin Separator', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'rem' ],
			    'condition' => [ 'number_separator!' => '' ],
                'default' => [
                    'top' => '23',
                    'right' => '0',
                    'bottom' => '29',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
			    'selectors' => [
				    '{{WRAPPER}} .number-wrapper::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'number_separator_width',
		    [
			    'label' => esc_html__('Width Separator', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 1, 'max' => 800],
				    '%' => ['min' => .1, 'max' => 100],
			    ],
                'condition' => [ 'number_separator!' => '' ],
                'selectors' => [
				    '{{WRAPPER}} .number-wrapper::after' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'number_separator_height',
		    [
			    'label' => esc_html__('Height Separator', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 1, 'max' => 800],
				    '%' => ['min' => .1, 'max' => 100],
			    ],
                'condition' => [ 'number_separator!' => '' ],
			    'selectors' => [
				    '{{WRAPPER}} .number-wrapper::after' => 'height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'number_rotation',
            [
                'label' => esc_html__('Enable Rotation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'return_value' => 'yes',
                'prefix_class' => 'enable-rotation%s-',
            ]
        );

        $this->start_controls_tabs( 'number_colors' );
        $this->start_controls_tab(
            'number_colors_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'primary_number_color',
            [
                'label' => esc_html__('Number Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .wgl-number' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'number_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(0.05),
                'selectors' => [
                    '{{WRAPPER}} .wgl-number' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'number_separator_color',
            [
                'label' => esc_html__('Separator Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'number_separator!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .number-wrapper::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'primary_number_border',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-number' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'number_shadow',
                'selector' => '{{WRAPPER}} .wgl-number',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'number_colors_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );

        $this->add_control(
            'number_primary_color_hover',
            [
                'label' => esc_html__('Number Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_tertiary_color(),
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'number_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#0F3548',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-number' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'number_separator_color_hover',
            [
                'label' => esc_html__('Separator Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'number_separator!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .number-wrapper::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'number_primary_border_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-number' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'number_hover_shadow',
                'selector' =>  '{{WRAPPER}} .elementor-widget-container:hover .wgl-number',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
		$this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['icon_type' => 'image'],
            ]
        );

        $this->add_responsive_control(
            'image_space',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper.img-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 800],
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_z_index',
            [
                'label' => esc_html__('Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'min' => -5,
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img img' => 'z-index: {{VALUE}}; position: relative;',
                ],
            ]
        );

	    $this->start_controls_tabs('image_effects');

        $this->start_controls_tab(
            'Idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

	    $this->add_control(
		    'image_bg_color_idle',
		    [
			    'label' => esc_html__('Image BG Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .wgl-image-box_img img',
            ]
        );

        $this->add_responsive_control(
            'image_opacity',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper .wgl-image-box_img,
				     {{WRAPPER}} .media-wrapper .wgl-image-box_img img,
				     {{WRAPPER}} .media-wrapper .wgl-image-box_img::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'image_shadow_idle',
			    'selector' => '{{WRAPPER}} .wgl-image-box_img img',
		    ]
	    );

        $this->add_control(
            'image_scale',
            [
                'label' => esc_html__('Scale Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%' ],
                'range' => [
                    '%' => ['min' => 0.8, 'max' => 1.2, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper img' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->add_control(
            'background_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => ['size' => 0.5],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

	    $this->add_control(
		    'image_bg_color_hover',
		    [
			    'label' => esc_html__('Image BG Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img',
            ]
        );

        $this->add_responsive_control(
            'image_opacity_hover',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-image-box_img,
				     {{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-image-box_img img,
				     {{WRAPPER}} .elementor-widget-container:hover .media-wrapper .wgl-image-box_img::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'image_shadow_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img',
		    ]
	    );

        $this->add_control(
            'image_scale_hover',
            [
                'label' => esc_html__('Scale Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ '%' ],
                'range' => [
                    '%' => ['min' => 0.8, 'max' => 1.2, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .media-wrapper img' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
	    $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> Bubble
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_bubble',
            [
                'label' => esc_html__('Bubble', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'icon_type' => ['font', 'number', 'image'],
                    'view' => 'bubble',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -200, 'max' => 200],
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 1],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_left_offset',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -200, 'max' => 200],
                    'px' => ['min' => -1000, 'max' => 1000, 'step' => 1],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bubble_size',
            [
                'label' => esc_html__('Bubble Diameter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => ['max' => 700],
                ],
                'default' => ['size' => 70, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
		    'icon_animation',
		    [
			    'label' => esc_html__('Bubble Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'icon_type' => ['font', 'number', 'image'],
                    'view' => 'bubble',
                ],
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'bubble',
                'prefix_class' => 'animation_',
                'default' => 'yes'
		    ]
	    );

        $this->add_responsive_control(
            'bubble_animation_top_offset',
            [
                'label' => esc_html__('Top Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 1],
                ],
                'condition' => [ 'icon_animation' => 'bubble' ],
                'default' => ['size' => -13, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-image-box_img::after' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_animation_left_offset',
            [
                'label' => esc_html__('Left Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['%', 'px', 'custom'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 1],
                ],
                'default' => ['size' => -13, 'unit' => 'px'],
                'condition' => [ 'icon_animation' => 'bubble' ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-image-box_img::after' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bubble_animation_size',
            [
                'label' => esc_html__('Bubble Diameter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => ['max' => 700],
                ],
                'condition' => [ 'icon_animation' => 'bubble' ],
                'default' => ['size' => '96', 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-image-box_img::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'bubble_z_index',
            [
                'label' => esc_html__( 'Z-Index', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'step' => 1,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->start_controls_tabs( 'tabs_bubble_styles' );
        $this->start_controls_tab(
            'tab_bubble_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'bubble_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bubble_radius_idle',
            [
                'label' => esc_html__('Bubble Radius', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => ['max' => 700],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble .wgl-image-box_img::after' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_bubble_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'bubble_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover .wgl-image-box_img::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'bubble_radius_hover',
            [
                'label' => esc_html__('Bubble Radius', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => ['max' => 700],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover  .wgl-icon::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover  .wgl-number .number::after,
                     {{WRAPPER}}.elementor-widget-wgl-infobox.wgl-view-bubble:hover  .wgl-image-box_img::after' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dynamic_title_display',
            [
                'label' => esc_html__( 'Display', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Unset', 'courto-core' ),
                    'inline' => esc_html__( 'Inline', 'courto-core' ),
                    'block' => esc_html__( 'Block', 'courto-core' ),
                    'inline-block' => esc_html__( 'Inline Block', 'courto-core' ),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title-idle > span' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_title_v_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'unset' => [
                        'title' => esc_html__('Default', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => ['dynamic_title_display' => 'inline-block'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title-idle > span' => 'vertical-align: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [ 'default' => [ 'size' => 32, 'unit' => 'px' ] ],
                    'line_height' => [ 'default' => [ 'size' => 1.4, 'unit' => 'em' ] ],
                    'text_transform' => ['default' => 'uppercase'],
                ],
                'selector' => '{{WRAPPER}} .wgl-infobox_title',
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

        $this->add_control(
            'title_font_family',
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
                    '{{WRAPPER}} .wgl-infobox_title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
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
                    '{{WRAPPER}} .wgl-infobox-title_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'title_border',
			    'render_type' => 'template',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
			    'selector' => '{{WRAPPER}} .wgl-infobox_title',
		    ]
	    );

        $this->add_responsive_control(
            'title_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', '%', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title-idle' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox_title' => 'z-index: {{VALUE}}; position: relative;',
                ],
            ]
        );

        $this->add_control(
            'title_separator',
            [
                'label' => esc_html__('Show Separator?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => 'display: inline-block;',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_separator_position',
            [
                'label' => esc_html__('Separator Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
                'condition' => ['title_separator!' => ''],
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-justify-end-v',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'position: absolute; left: 0; top: 0;',
                    'bottom' => 'position: static;',
                    'right' => 'position: absolute; right: 0; top: 0;',
                ],
                'default' => 'bottom',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_separator_margin',
            [
                'label' => esc_html__('Margin Separator', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem', 'custom' ],
                'condition' => ['title_separator!' => ''],
                'default' => [
                    'top' => '26',
                    'right' => '0',
                    'bottom'=> '17',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_separator_width',
            [
                'label' => esc_html__('Width Separator', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 800],
                    '%' => ['min' => 1, 'max' => 100],
                ],
                'condition' => ['title_separator!' => ''],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_separator_height',
            [
                'label' => esc_html__('Height Separator', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 800],
                    '%' => ['min' => 1, 'max' => 100],
                ],
                'condition' => ['title_separator!' => ''],
                'default' => ['size' => 1, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'title',
            [ 'separator' => 'before' ]
        );
        $this->start_controls_tab(
            'title_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox_title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'title_shadow_idle',
			    'selector' => '{{WRAPPER}} .wgl-infobox_title',
		    ]
	    );

        $this->add_control(
            'separator_color_idle',
            [
                'label' => esc_html__('Separator Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['title_separator!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-title_wrapper::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
	            'condition' => [ 'title_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_bg_blur_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'title_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
		    'title_hover_shift',
		    [
			    'label' => esc_html__('Lift up the title', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => [ 'px', 'custom' ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'transform: translateY({{SIZE}}{{UNIT}})',
			    ],
		    ]
	    );
        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'title_shadow_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title',
		    ]
	    );
	    $this->add_control(
		    'separator_color_hover',
		    [
			    'label' => esc_html__('Separator Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['title_separator!' => ''],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox-title_wrapper::after' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );
        $this->add_control(
            'title_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
	            'condition' => [
	            	'title_border_border!' => ['', 'none']
	            ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_bg_blur_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> SUBTITLE
         */

        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['ib_subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wgl-infobox_subtitle',
            ]
        );

        $this->add_control(
            'subtitle_font_family',
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
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
	                'top' => '0',
	                'right' => '0',
	                'bottom' => '10',
	                'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .wgl-infobox_subtitle',
            ]
        );

        $this->start_controls_tabs('tabs_subtitle_styles');
        $this->start_controls_tab(
            'tab_subtitle_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'subtitle_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_subtitle_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_bg_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_subtitle' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'subtitle_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_subtitle' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

	    /**
	     * STYLE -> BG Text
	     */

	    $this->start_controls_section(
		    'background_text',
		    [
			    'label' => esc_html__('Background Text', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['ib_bg_text!' => ''],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'bg_text_custom_fonts',
			    'selector' => '{{WRAPPER}} .wgl-infobox_bg_text',
		    ]
	    );

        $this->add_control(
            'bg_text_font_family',
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
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_text_alignment',
            [
                'label' => esc_html__('Background Text Alignment', 'courto-core'),
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
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'text-align: {{VALUE}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'bg_text_width',
		    [
			    'label' => esc_html__('BG Text Wrapper Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'vw', 'custom' ],
                'range' => [
                    '%' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'vw' => [ 'min' => 10, 'max' => 100, 'step' => 1 ],
                    'px' => [ 'min' => 10, 'max' => 960, 'step' => 1 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_stroke_size',
		    [
			    'label' => esc_html__('Stroke Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', 'em'],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 2, 'step' => 0.1],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_padding',
		    [
			    'label' => esc_html__('Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_radius',
		    [
			    'label' => esc_html__('Border Radius', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'bg_text_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
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
                    'top center' =>    '0 auto auto auto',
                    'top left' =>      '0 auto auto 0',
                    'top right' =>     '0 0 auto auto',
                    'center center' => 'auto auto auto auto',
                    'center left' =>   'auto auto auto 0',
                    'center right' =>  'auto 0 auto auto',
                    'bottom center' => 'auto auto 0 auto',
                    'bottom left' =>   'auto auto 0 0',
                    'bottom right' =>  'auto 0 0 auto',
                ],
                'default' => 'top left',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'margin: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_text_v_position',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1000, 'max' => 1000 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => '--pos-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bg_text_h_position',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => -1000, 'max' => 1000 ],
                    '%' => ['min' => -100,'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
		    'bg_text_z_index',
		    [
			    'label' => esc_html__('Z-Index', 'courto-core' ),
			    'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
			    'min' => -5,
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'z-index: {{VALUE}};',
			    ],
		    ]
	    );
        $this->add_control(
            'bg_text_gradient',
            [
                'label' => esc_html__('Enable Background Text Gradient', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'background: -webkit-linear-gradient(var(--ib-icon-clr-first), var(--ib-icon-clr-sec)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_bg_text_styles');
	    $this->start_controls_tab(
		    'tab_bg_text',
		    ['label' => esc_html__('Idle', 'courto-core')]
	    );
	    $this->add_control(
		    'bg_text_color_idle',
		    [
			    'label' => esc_html__('BG Text Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'color: {{VALUE}}; --ib-icon-clr-first: {{VALUE}}',
			    ],
		    ]
	    );
        $this->add_control(
            'bg_text_sec_color_idle',
            [
                'label' => esc_html__('BG Text Gradient Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_text_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => '--ib-icon-clr-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bg_text_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_text_gradient' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_text' => 'background-color: {{VALUE}};',
                ],
            ]
        );
	    $this->add_control(
		    'bg_text_stroke_idle',
		    [
			    'label' => esc_html__('Stroke Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['bg_text_stroke_size!' => ''],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => '-webkit-text-stroke-color: {{VALUE}};',
			    ],
		    ]
	    );
	    $this->end_controls_tab();
	    $this->start_controls_tab(
		    'tab_bg_text_hover',
		    ['label' => esc_html__('Hover', 'courto-core')]
	    );
	    $this->add_control(
		    'bg_text_color_hover',
		    [
			    'label' => esc_html__('BG Text Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_text' => 'color: {{VALUE}}; --ib-icon-clr-first: {{VALUE}}',
			    ],
		    ]
	    );
        $this->add_control(
            'bg_text_sec_color_hover',
            [
                'label' => esc_html__('BG Text Gradient Color Secondary', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_text_gradient!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_text' => '--ib-icon-clr-sec: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bg_text_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'bg_text_gradient' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_text' => 'background-color: {{VALUE}};',
                ],
            ]
        );
	    $this->add_control(
		    'bg_text_stroke_hover',
		    [
			    'label' => esc_html__('Stroke Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['bg_text_stroke_size!' => ''],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_text' => '-webkit-text-stroke-color: {{VALUE}};',
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
                'condition' => ['ib_content!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-infobox-content',
            ]
        );

        $this->add_responsive_control(
            'content_offset',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '9',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'content_width',
            [
                'label' => esc_html__('Content Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'vw', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1000, 'step' => 1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content_wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_p_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "p" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content_wrapper p:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_li_bottom_margin',
            [
                'label' => esc_html__('Margin Bottom for "li" selector', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50, 'step' => 1],
                    'em' => ['min' => 0, 'max' => 2, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content_wrapper li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_link_color',
            [
                'label' => esc_html__('Link Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-content a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'custom_content_color_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_link_color_item_hover',
            [
                'label' => esc_html__('Link Color (Item Hover)', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox-content a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_link_color_hover',
            [
                'label' => esc_html__('Link Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-infobox-content a:is(:hover, :focus, :active)' => 'color: {{VALUE}};',
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
                    'top' => '26',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
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
				    'color' => ['type' => Controls_Manager::HIDDEN],
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

        $this->add_responsive_control(
            'button_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'courto-core'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'courto-core'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'courto-core'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                ],
                'condition' => [ 'button_type' => 'wgl-button' ],
                'default' => 'left',
                'prefix_class' => 'button%s-',
                'selectors' => [
                    '{{WRAPPER}} .wgl-widget__button' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-align-end-h',
                    ],
                ],
                'condition' => [ 'button_type' => 'button-read-more' ],
                'default' => 'left',
                'prefix_class' => 'button%s-',
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'read_more_v_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-align-end-v',
                    ],
                ],
                'default' => 'bottom',
                'condition' => [ 'button_position!' => 'relative' ],
            ]
        );

        $this->add_control(
            'button_position',
            [
                'label' => esc_html__('Button Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'relative' => esc_html__('Default', 'courto-core'),
                    'absolute' => esc_html__('Absolute', 'courto-core'),
                ],
                'default' => 'relative',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .wgl-button-wrapper' => 'position: {{VALUE}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_position_offset_x',
			[
				'label' => esc_html__( 'Offset X', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vw', 'vh', 'custom' ],
                'condition' => [ 'button_position!' => 'relative' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-button-wrapper' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'button_position_offset_y',
			[
				'label' => esc_html__( 'Offset Y', 'courto-core' ),
				'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw', 'custom' ],
                'condition' => [
                    'button_position!' => 'relative',
                ],
				'selectors' => [
					'{{WRAPPER}} .wgl-button-wrapper' => '{{read_more_v_alignment.VALUE}}: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .button__text' => 'text-decoration-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => 'border-color: {{VALUE}}'
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
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover span' => 'text-decoration-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => 'border-color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active span' => 'text-decoration-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => 'border-color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => '--read-more-icon-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon::before' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-widget__button .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => '--read-more-icon-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon::before' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:hover .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => '--read-more-icon-color: {{VALUE}};',
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
                    'read_more_icon_fontawesome[value]!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon::before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon,
				     {{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon::before' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active' => '--wgl-icon-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button:active .wgl-icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /**
         * STYLE -> BACKGROUND
         */

        $this->start_controls_section(
            'style_background',
            [
                'label' => esc_html__('Background', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_overflow',
            [
                'label' => esc_html__('Module Overflow', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'courto-core'),
                    'overflow: visible;' => esc_html__('Visible', 'courto-core'),
                    'overflow: hidden;' => esc_html__('Hidden', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ib_bg_first',
                'fields_options' => [
                    'background' => [ 'label' => esc_html__( 'First Background', 'courto-core' ) ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .wgl-infobox_bg_wrapper::before',
            ]
        );
        $this->add_control(
            'ib_bg_first_z_index',
            [
                'label' => esc_html__('First Background z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'condition' => [ 'ib_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_wrapper::before' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ib_bg_second',
                'fields_options' => [
                    'background' => [ 'label' => esc_html__( 'Second Background', 'courto-core' ) ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .wgl-infobox_bg_wrapper::after',
            ]
        );
        $this->add_control(
            'ib_bg_second_z_index',
            [
                'label' => esc_html__('Second Background z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'condition' => [ 'ib_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_wrapper::after' => 'z-index: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
		    'ib_bg_position',
		    [
			    'label' => esc_html__('Position for Background', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
			    'size_units' => ['px', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_wrapper' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_idle',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .wgl-infobox_bg_wrapper',
            ]
        );
	    $this->start_controls_tabs('tabs_background');
        $this->start_controls_tab(
            'tab_bg_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_responsive_control(
            'ib_bg_first_idle',
            [
                'label' => esc_html__('Opacity for First Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'ib_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_wrapper::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ib_bg_second_idle',
            [
                'label' => esc_html__('Opacity for Second Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'ib_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_wrapper::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'item_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'item_border_idle_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_bg_wrapper' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'container_shadow_idle',
			    'selector' => '{{WRAPPER}} .elementor-widget-container',

		    ]
	    );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'container_blur_idle',
                'selector' => '{{WRAPPER}} .wgl-infobox_bg_wrapper',
            ]
        );
	    $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_bg_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'ib_bg_first_hover',
            [
                'label' => esc_html__('Opacity for First Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'ib_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_wrapper::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'ib_bg_second_hover',
            [
                'label' => esc_html__('Opacity for Second Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'ib_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_wrapper::after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_control(
            'item_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'item_border_idle_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_wrapper' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'container_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'container_shadow_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover',
		    ]
	    );
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'container_blur_hover',
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_bg_wrapper',
            ]
        );
        $this->add_control(
            'item_bg_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.4],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container,
                     {{WRAPPER}} .wgl-infobox_bg_wrapper' => 'transition: {{SIZE}}s',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * CONTENT -> HOVER ANIMATION
         */

        $this->start_controls_section(
            'content_animation',
            [
                'label' => esc_html__('Hover Animation', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'hover_lifting',
            [
                'label' => esc_html__('Lift Up the Item', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'hover_toggling' => '',
                    'hover_toggling_icon' => ''
                ],
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'lifting',
                'prefix_class' => 'animation_'
            ]
        );

        $this->add_control(
            'hover_toggling_icon',
            [
                'label' => esc_html__('Toggle Icon Visibility', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'background_animation' => '',
                    'hover_lifting' => '',
                    'hover_toggling' => '',
                    'layout' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'toggling_icon',
                'prefix_class' => 'animation_'
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_icon_offset',
            [
                'label' => esc_html__('Animation Distance', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'background_animation' => '',
                    'hover_lifting' => '',
                    'hover_toggling' => '',
                    'hover_toggling_icon!' => '',
                    'layout' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['size' => 40],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling_icon .content_wrapper' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.animation_toggling_icon .elementor-widget-container:hover .content_wrapper' => 'padding-left: {{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.animation_toggling_icon .media-wrapper' => 'transform: translateX(-{{SIZE}}{{UNIT}}) scale(0.5);',
                ],
            ]
        );

        $this->add_control(
            'hover_toggling',
            [
                'label' => esc_html__('Toggle Visibility', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'content' => esc_html__('Content Visibility', 'courto-core'),
                    'content movement' => esc_html__('Content Visibility with Movement', 'courto-core'),
                    'button' => esc_html__('Button Visibility', 'courto-core'),
                    'button movement' => esc_html__('Button Visibility with Movement', 'courto-core'),
                ],
                'condition' => [
                    'hover_lifting' => '',
                    'hover_toggling_icon' => '',
                ],
                'default' => '',
                'prefix_class' => 'animation_'
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'render_type' => 'template',
                'condition' => [ 'hover_toggling!' => '' ],
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1],
                ],
                'default' => ['size' => 0.5],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling .wgl-infobox-content_wrapper,
                     {{WRAPPER}}.animation_button .wgl-button-wrapper' => '--dur: {{SIZE}}s;',
                ],
            ]
        );

        $this->add_control(
            'background_animation',
            [
                'label' => esc_html__('Background Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => esc_html__('None', 'courto-core'),
                    'borders' => esc_html__('Borders', 'courto-core'),
                    'gradient' => esc_html__('Gradient', 'courto-core'),
                ],
                'frontend_available' => true,
                'condition' => ['hover_toggling_icon' => ''],
                'prefix_class' => 'bg_animation-',
            ]
        );

        $this->add_control(
            'container_animation_color',
            [
                'label' => esc_html__('Border Color for Animation', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['background_animation' => 'borders'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before,
                     {{WRAPPER}} .wgl-infobox::after' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_width',
            [
                'label' => esc_html__('Wrapper Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'background_animation' => 'gradient' ],
                'size_units' => ['px', '%', 'vw', 'vh', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560],
                    '%' => ['min' => 0, 'max' => 200],
                    'vw' => ['min' => 0, 'max' => 100],
                    'vh' => ['min' => 0, 'max' => 100],
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_height',
            [
                'label' => esc_html__('Wrapper Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'background_animation' => 'gradient' ],
                'size_units' => ['px', '%', 'vw', 'vh', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 2560],
                    '%' => ['min' => 0, 'max' => 200],
                    'vw' => ['min' => 0, 'max' => 100],
                    'vh' => ['min' => 0, 'max' => 100],
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'overlay_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'background_animation' => 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--overlay-color-1: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'overlay_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'background_animation' => 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--overlay-color-2: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_location_1',
            [
                'label' => esc_html__('First Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [ 'background_animation' => 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--overlay-location-1: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'overlay_location_2',
            [
                'label' => esc_html__('Second Color Location', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ '%', 'px' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [ 'background_animation' => 'gradient' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--overlay-location-2: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_gradient_type',
            [
                'label' => esc_html__('Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => esc_html__( 'Linear', 'courto-core' ),
                    'radial' => esc_html__( 'Radial', 'courto-core' ),
                ],
                'render_type' => 'ui',
                'condition' => [ 'background_animation' => 'gradient' ],
                'default' => 'linear',
            ]
        );

        $this->add_responsive_control(
            'overlay_gradient_angle',
            [
                'label' => esc_html__('Angle', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'background_animation' => 'gradient',
                    'overlay_gradient_type' => 'linear',
                ],
                'default' => [ 'unit' => 'deg', 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--overlay-color-1) var(--overlay-location-1), var(--overlay-color-2) var(--overlay-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_gradient_position',
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
                    'background_animation' => 'gradient',
                    'overlay_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--overlay-color-1) var(--overlay-location-1), var(--overlay-color-2) var(--overlay-location-2));',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_gradient_h_position',
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
                    'background_animation' => 'gradient',
                    'overlay_gradient_type' => 'radial',
                    'overlay_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--h-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_gradient_v_position',
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
                    'background_animation' => 'gradient',
                    'overlay_gradient_type' => 'radial',
                    'overlay_gradient_position' => 'var(--h-pos) var(--v-pos)',
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox::before' => '--v-pos: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-bg-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active' => '--wgl-bg-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => '--wgl-border-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-primary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus, :active)::after' => '--wgl-border-gradient-secondary: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus)::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => '--pos-x: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => '--pos-y:{{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active::after' => 'border-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-offset: {{SIZE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-width: {{SIZE}};',
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
                    '{{WRAPPER}} .elementor-widget-container .wgl-widget__button' => '--ab-extend: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-widget__button .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:is(:hover, :focus) .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon' => '--icon-scale: {{SIZE}};',
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
                    '{{WRAPPER}} .wgl-widget__button:active .wgl-icon' => '--icon-bg-scale: {{SIZE}};',
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
                'condition' => ['layout' => ['left', 'right']],
                'options' => $avaliable_breakpoints + [ '' => esc_html__('Disable', 'courto-core') ],
                'default' => '',
            ]
        );
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new WGLInfoBoxes())->render($this, $atts);
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