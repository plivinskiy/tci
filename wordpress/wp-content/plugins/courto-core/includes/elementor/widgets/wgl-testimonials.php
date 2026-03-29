<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-testimonials.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Utils,
    Repeater,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Background
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Cursor,
    Templates\WGL_Testimonials as Testimonials_Template
};

class WGL_Testimonials extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-testimonials';
    }

    public function get_title()
    {
        return esc_html__('WGL Testimonials', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-testimonials';
    }

    public function get_keywords()
    {
        return [ 'testimonials', 'carousel', 'slider' ];
    }

    public function get_script_depends()
    {
        return ['swiper'];
    }

    public function get_style_depends()
    {
        return [ 'swiper' ];
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

        $this->add_control(
            'posts_per_row',
            [
                'label' => esc_html__('Grid Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                ],
                'default' => '2',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => [ 'url' => Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'link_author',
            [
                'label' => esc_html__('Link Author', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'author_position',
            [
                'label' => esc_html__('Author Position', 'courto-core'),
                'type' => Controls_Manager::TEXT,
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
                'placeholder' => esc_html__('Your Title', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'placeholder' => esc_html__('Your Subtitle', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'quote_rating',
            [
                'label' => esc_html__( 'Rating', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'None', 'courto-core' ),
                    1 => esc_html__( '1 star', 'courto-core' ),
                    2 => esc_html__( '2 stars', 'courto-core' ),
                    3 => esc_html__( '3 stars', 'courto-core' ),
                    4 => esc_html__( '4 stars', 'courto-core' ),
                    5 => esc_html__( '5 stars', 'courto-core' ),
                ],
                'default' => '',
            ]
        );

        $repeater->add_control(
            'quote',
            [
                'label' => esc_html__('Quote', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__('"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ultrices mauris sed metus fermentum fermentum. Aliquam ut metus nunc. Nulla sit amet nisi id."', 'courto-core'),
            ]
        );

        $repeater->add_control(
            'image_items',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'description' => esc_html__('This option works only if "Layout - Left" is selected', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'separator' => 'after',
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        WGL_Cursor::repeater_init(
            $repeater,
            [
                'section' => false,
                'repeater' => true,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'title' => esc_html__('All family comes to the club', 'courto-core'),
                        'author_name' => esc_html__('Paul G.', 'courto-core'),
                        'author_position' => esc_html__('Member of Club', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Best coaching team', 'courto-core'),
                        'author_name' => esc_html__('Marry R.', 'courto-core'),
                        'author_position' => esc_html__('Member of Club', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Professional trainers', 'courto-core'),
                        'author_name' => esc_html__('Tomas P.', 'courto-core'),
                        'author_position' => esc_html__('Member of Club', 'courto-core'),
                    ],
                    [
                        'title' => esc_html__('Best club for my son', 'courto-core'),
                        'author_name' => esc_html__('Lola B.', 'courto-core'),
                        'author_position' => esc_html__('Member of Club', 'courto-core'),
                    ],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ author_name }}}',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'top_block' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_1.png',
                    ],
                    'bottom_block' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_4.png',
                    ],
                    'top_inline' => [
                        'title' => esc_html__('Top Inline', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_2.png',
                    ],
                    'bottom_inline' => [
                        'title' => esc_html__('Bottom Inline', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_3.png',
                    ],
                    'left_inline' => [
                        'title' => esc_html__('Left Inline', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_5.png',
                    ],
                    'left_block' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_6.png',
                    ],
                ],
                'default' => 'bottom_inline',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
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
                'prefix_class' => 'a%s',
                'default' => 'left',
            ]
        );

        $this->add_responsive_control(
            'author_alignment',
            [
                'label' => esc_html__('Author Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-editor-close',
                    ],
                    'align-items: flex-start' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'align-items: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'align-items: flex-end' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'align-items: flex-start',
                'selectors' => [
                    '{{WRAPPER}} .author__meta,
                     {{WRAPPER}} .type-left_block .item__image_wrapper' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'equal_height',
            [
                'label' => esc_html__('Equal Height', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'default' => 'yes',
                'prefix_class' => 'equal_height%s-',
            ]
        );

        $this->add_responsive_control(
            'item_height',
            [
                'label' => esc_html__('Items Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 600] ],
                'condition' => ['equal_height!' => ''],
                'size_units' => ['px', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Enable Hover Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Lift up the item on hover.', 'courto-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        WGL_Carousel_Settings::add_controls($this, [
            'use_carousel' => [ 'default' => 'yes' ],
            'use_navigation' => [ 'default' => '' ],
            'use_pagination' => [ 'default' => 'yes' ],
            'pagination_align' => [ 'default' => 'center' ],
            'slider_alignment_v' => [ 'default' => 'flex-start' ],
            '3d_animation_options' => 'enabled',
            'animation_style' => [ 'default' => 'default' ],
            'adaptive_height' => [ 'default' => '' ],
            'slide_per_single' => [ 'default' => 'yes' ],
            'slider_infinite' => [ 'default' => 'yes' ],
            'navigation_position' => [ 'default' => 'nearby' ],
            'navigation_alignment_v' => [ 'default' => 'flex-end' ],
            'navigation_alignment_h' => [ 'default' => 'center' ],
            'navigation_margin' => [
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '-70',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '-40',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ],
            'pagination_margin' => [
                'default' => [
                    'top' => '56',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ],
            'variable_width_height' => [
                'condition' => [ 'hide_option' => 'yes' ],
            ],
        ]);

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
            'item_gap',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px', 'custom'],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-testimonials' => '--wgl-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'custom'],
                'allowed_dimensions' => 'vertical',
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
                    'top' => '47',
                    'right' => '50',
                    'bottom' => '40',
                    'left' => '50',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '27',
                    'right' => '30',
                    'bottom' => '20',
                    'left' => '30',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '18',
                    'right' => '20',
                    'bottom' => '15',
                    'left' => '20',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
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
                    'unit'  => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => '#E9E6E2',
                    ],
                    'image' => [ 'label' => esc_html__( 'Background Image', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .testimonial__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'fields_options' => [
                    'border' => [ 'default' => '' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'unit'  => 'px',
                            'isLinked' => true
                        ],
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                        'default' => WGL_Globals::get_h_font_color(0.1)
                    ],
                ],
                'selector' => '{{WRAPPER}} .testimonial__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .testimonial__item',
            ]
        );

        $this->add_control(
            'item_blur',
            [
                'label' => esc_html__('Background Blur', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .item__title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
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
                    '{{WRAPPER}} .item__title' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Wrapper Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1200 ],
                    '%' => ['min' => 5, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'max-width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .item__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .item__title',
            ]
        );

        $this->add_responsive_control(
            'title_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['title_icon_enabled!' => ''],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_bg_color',
            [
                'label' => esc_html__('Title Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'style_title_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_icon_enabled',
            [
                'label' => esc_html__('Use Icon', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'prefix_class' => 'icon-',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'title_icon_display',
            [
                'label' => esc_html__( 'Display', 'courto-core' ),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'condition' => ['title_icon_enabled!' => ''],
                'options' => [
                    'title' => esc_html__( 'With Title', 'courto-core' ),
                    'block' => esc_html__( 'Top', 'courto-core' ),
                ],
                'default' => 'title',
                'prefix_class' => 'icon-',
            ]
        );
        $this->add_responsive_control(
            'title_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => ['title_icon_enabled!' => ''],
                'range' => [
                    'px' => ['min' => 10, 'max' => 200],
                ],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['title_icon_enabled!' => ''],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['title_icon_enabled!' => ''],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['title_icon_enabled!' => ''],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_icon_color',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_icon_enabled!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_icon_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['title_icon_enabled!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item .item__icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> QUOTE
         */

        $this->start_controls_section(
            'style_quote',
            [
                'label' => esc_html__('Quote', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_quote',
                'fields_options' => [
                    'typography' => [ 'default' => 'yes' ],
                    'font_size' => [
                        'default' => [ 'size' => 20, 'unit' => 'px' ],
                        'tablet_default' => [ 'size' => 18, 'unit' => 'px' ],
                        'mobile_default' => [ 'size' => 16, 'unit' => 'px' ],
                    ],
                    'line_height' => ['default' => ['size' => 1.8, 'unit' => 'em']],
                ],
                'selector' => '{{WRAPPER}} .item__quote',
            ]
        );

        $this->add_control(
            'quote_tag',
            [
                'label' => esc_html__('Quote tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
                    'top' => '12',
                    'right' => '18',
                    'bottom' => '0',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quote_border',
                'fields_options' => [
                    'border' => [ 'default' => '' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                    ],
                ],
                'selector' => '{{WRAPPER}} .item__quote',
            ]
        );

        $this->add_responsive_control(
            'quote_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'quote_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR BLOCK
         */

        $this->start_controls_section(
            'style_author',
            [
                'label' => esc_html__('Author Block', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'author_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR THUMBNAIL
         */

        $this->start_controls_section(
            'style_thumnail',
            [
                'label' => esc_html__('Author Thumbnail', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'thumbnail_size',
            [
                'label' => esc_html__('Thumbnail Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'range' => [
                    'px' => ['min' => 20, 'max' => 400],
                ],
                'default' => ['size' => 74],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => '--thumbnail-width: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'thumbnail_not_active_size',
            [
                'label' => esc_html__('Not Active Thumbnail Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'range' => [
                    'px' => ['min' => 20, 'max' => 400],
                ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => '--thumbnail-not-active-width: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail,
                    {{WRAPPER}} .author__thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'thumbnail_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .author__thumbnail',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'testimonials_thumbnail_shadow',
                'selector' => '{{WRAPPER}} .author__thumbnail',
            ]
        );

        $this->start_controls_tabs('thumbnail_tabs');

        $this->start_controls_tab(
            'tab_thumbnail_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'thumbnail_bg_color_idle',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'thumbnail_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'thumbnail_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'testimonials_triangle_color_idle',
            [
                'label' => esc_html__( 'Triangle Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'testimonials_triangle_enabled!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'testimonials_additional_color_idle',
            [
                'label' => esc_html__( 'Additional Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'layout' => 'left_block' ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_thumbnail_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'thumbnail_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__thumbnail,
                    {{WRAPPER}} .swiper-slide.swiper-slide-active .author__thumbnail' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'thumbnail_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'thumbnail_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__thumbnail,
                    {{WRAPPER}} .swiper-slide.swiper-slide-active .author__thumbnail' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'testimonials_additional_color_hover',
            [
                'label' => esc_html__( 'Additional Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'layout' => 'left_block' ],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__thumbnail::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR NAME
         */

        $this->start_controls_section(
            'style_name',
            [
                'label' => esc_html__('Author Name', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_name',
                'selector' => '{{WRAPPER}} .author__name',
            ]
        );

        $this->add_control(
            'name_tag',
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
                'default' => 'div',
            ]
        );

        $this->add_control(
            'name_font_family',
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
                    '{{WRAPPER}} .author__name' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('name_colors');
        $this->start_controls_tab(
            'tab_name_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'name_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_name_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'name_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR POSITION
         */

        $this->start_controls_section(
            'style_position',
            [
                'label' => esc_html__('Author Position', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_position',
                'selector' => '{{WRAPPER}} .author__position',
            ]
        );
        $this->add_control(
            'position_tag',
            [
                'label' => esc_html__('HTML tag', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'span',
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
            ]
        );
        $this->add_control(
            'position_font_family',
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
                    '{{WRAPPER}} .author__position' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_responsive_control(
            'position_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__position_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'position_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'position_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'position_border',
                'selector' => '{{WRAPPER}} .author__position',
            ]
        );

        $this->start_controls_tabs('position_tabs');
        $this->start_controls_tab(
            'position_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'position_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'position_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'position_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'position_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'position_tab_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'position_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__position' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'position_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__position' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'position_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'position_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .author__position' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR SUBTITLE
         */

        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Author Subtitle', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_subtitle',
                'selector' => '{{WRAPPER}} .item__subtitle',
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
                    '{{WRAPPER}} .item__subtitle' => 'font-family: var(--courto-{{VALUE}}-font-family);',
                ],
            ]
        );
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('subtitle_tabs');
        $this->start_controls_tab(
            'subtitle_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'subtitle_tab_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .testimonials__wrapper:hover .item__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /** STYLE -> RATING */

        $this->start_controls_section(
            'style_rating',
            [
                'label' => esc_html__( 'Rating', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'rating_typography',
                'exclude' => [ 'font_family', 'text_transform', 'font_style', 'text_decoration', 'line_height' ],
                'selector' => '{{WRAPPER}} .item__rating',
            ]
        );
        $this->add_responsive_control(
            'rating_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .item__rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'rating_filled_color',
            [
                'label' => esc_html__( 'Filled Stars Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'rating_empty_color',
            [
                'label' => esc_html__( 'Empty Stars Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__rating::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();


        /** STYLE -> IMAGE */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__( 'Image', 'courto-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['layout' => 'left_block'],
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );
        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Left/Right Block Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 1000] ],
                'size_units' => ['px', 'vw', 'custom'],
                'default' => ['size' => 'clamp(300px, 34vw, 510px)', 'unit' => 'custom'],
                'mobile_default' => ['size' => 'clamp(200px, 60vw, 450px)', 'unit' => 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__image,
                     {{WRAPPER}} .item__author' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'image_responsive',
            [
                'label' => esc_html__( 'Hide Image on:', 'courto-core' ),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'default' => 500,
            ]
        );
        $this->add_responsive_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '500',
                    'right' => '500',
                    'bottom' => '500',
                    'left' => '500',
                    'unit'  => 'px',
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__image_wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'left_size',
            [
                'label' => esc_html__('Left Side Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px', '%', 'custom'],
                'default' => ['size' => 55, 'unit' => '%'],
                'mobile_default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__image_wrapper' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .testimonial__item' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .testimonials__wrapper' => 'flex-wrap: wrap; align-items: center;',
                ],
            ]
        );
        $this->add_responsive_control(
            'right_size',
            [
                'label' => esc_html__('Right Side Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px', '%', 'custom'],
                'default' => ['unit' => '%'],
                'mobile_default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} div.testimonial__item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        WGL_Cursor::repeater_style_init(
            $this,
            [
                'section' => false,
                'repeater' => true,
            ]
        );
    }

    protected function render()
    {
        (new Testimonials_Template())->render(
            $this,
            $this->get_settings_for_display()
        );
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
