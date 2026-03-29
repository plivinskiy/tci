<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-portfolio.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow,
    Utils
};
use WGL_Extensions\{
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGL_Portfolio as Portfolio_Template,
    Includes\WGL_Cursor,
    Includes\WGL_Webgl_Image_Hover,
    WGL_Framework_Global_Variables as WGL_Globals
};

class WGL_Portfolio extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-portfolio';
    }

    public function get_title()
    {
        return esc_html__('WGL Portfolio', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-portfolio';
    }

    public function get_keywords() {
        return ['portfolio'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [
            'swiper',
            'imagesloaded',
            'isotope',
            'wgl-widgets',
        ];
    }

    public function get_style_depends()
    {
        return [ 'swiper' ];
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
            'portfolio_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'portfolio_subtitle',
            [
                'label' => esc_html__('Subitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_sticky',
            [
                'label' => esc_html__('Module Title Sticky', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'layout' => ['grid-2'],
                ],
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => esc_html__('Show Button', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'layout' => ['grid-2']
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__( 'Text', 'courto-core' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => esc_html__( 'VIEW ALL PROJECTS', 'courto-core' ),
                'condition' => [
                    'layout' => ['grid-2'],
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__( 'Link', 'courto-core' ),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__( 'https://your-link.com', 'courto-core' ),
                'condition' => [
                    'layout' => ['grid-2'],
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                    'grid-2' => [
                        'title' => esc_html__('Grid 2', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid_2.png',
                    ],
                    'grid-3' => [
                        'title' => esc_html__('Grid 3', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid_3.png',
                    ],
                    'flex-list' => [
                        'title' => esc_html__('Flex List', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_flex_list.png',
                    ],
                    'masonry-1' => [
                        'title' => esc_html__('Masonry 1', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'masonry-2' => [
                        'title' => esc_html__('Masonry 2', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_2.png',
                    ],
                    'masonry-3' => [
                        'title' => esc_html__('Masonry 3', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_3.png',
                    ],
                    'masonry-4' => [
                        'title' => esc_html__('Masonry 4', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_4.png',
                    ],
                ],
                'default' => 'grid',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'posts_per_row',
            [
                'label' => esc_html__('Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'condition' => [
                    'layout' => ['grid', 'masonry-1', 'carousel']
                ],
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container' => '--pf-width: calc(100% / {{VALUE}});',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'grid_3_scale_size',
            [
                'label' => esc_html__( 'Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'layout' => 'grid-3',
                ],
                'size_units' => [ '%', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 67, 'unit' => '%' ],
                'tablet_default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:nth-child(4n+2)' => '--wgl-portfolio-scale-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .portfolio__item:nth-child(4n+3)' => '--wgl-portfolio-scale-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'grid_3_scale_align_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'layout' => 'grid-3',
                ],
            ]
        );

        $this->add_control(
            'grid_3_scale_align_v',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                    '{{WRAPPER}} .portfolio__item' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'layout' => 'grid-3',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
            [
                'label' => esc_html__('Grid Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'frontend_available' => true,
                'range' => [
                    'px' => ['min' => 0, 'max' => 70, 'step' => 2],
                ],
                'default' => ['size' => 30],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container' => '--pf-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['layout!' => ['grid-2']],
            ]
        );

        $this->add_responsive_control(
            'flex_image_height',
            [
                'label' => esc_html__('Images Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 200, 'max' => 800, 'step' => 10],
                ],
                'default' => ['size' => 620],
                'tablet_default' => [ 'size' => 440],
                'mobile_default' => [ 'size' => 300],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .item__image' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['layout' => ['flex-list']],
            ]
        );

        $this->add_responsive_control(
            'flex_descr_width',
            [
                'label' => esc_html__('Description Max Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 50, 'max' => 270, 'step' => 1],
                ],
                'default' => ['size' => 270],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio .item__description' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['layout' => ['flex-list']],
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label' => esc_html__('Show Filter', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'layout!' => ['carousel', 'flex-list', 'grid-2']
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_counter_enabled',
            [
                'label' => esc_html__('Use Filter Counter?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'show_filter' => 'yes',
                    'layout!' => ['carousel', 'flex-list', 'grid-2']
                ],
            ]
        );

        $this->add_control(
            'filter_max_width_enabled',
            [
                'label' => esc_html__('Limit the Filter Container Width', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'show_filter' => 'yes',
                    'layout!' => ['carousel', 'flex-list', 'grid-2']
                ],
            ]
        );$this->add_control(
        'filter_max_width_centered',
        [
            'label' => esc_html__('To Center This Filter Container', 'courto-core'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'show_filter' => 'yes',
                'layout!' => ['carousel', 'flex-list', 'grid-2'],
                'filter_max_width_enabled' => 'yes',
            ],
            'default' => 'yes',
            'selectors' => [
                '{{WRAPPER}} div.wgl-filter_wrapper' => 'margin-left: auto; margin-right: auto;',
            ],
        ]
    );

        $this->add_control(
            'filter_max_width',
            [
                'label' => esc_html__('Filter Container Max Width (px)', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'show_filter' => 'yes',
                    'filter_max_width_enabled' => 'yes',
                    'layout!' => ['carousel', 'flex-list', 'grid-2']
                ],
                'default' => '1170',
                'selectors' => [
                    '{{WRAPPER}} .wgl-filter_wrapper.isotope-filter' => 'width: min(100%, {{VALUE}}px); overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_alignment',
            [
                'label' => esc_html__( 'Filter Alignment', 'courto-core' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'condition' => [
                    'show_filter' => 'yes',
                    'layout!' => ['carousel', 'flex-list', 'grid-2']
                ],
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
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'courto-core'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                ],
                'default' => 'left',
            ]
        );

        $this->add_control(
            'image_masonry_size',
            [
                'label' => esc_html__('Image Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'layout' => ['masonry-2', 'masonry-3', 'masonry-4']
                ],
                'range' => [ 'px' => ['min' => 100, 'max' => 2560] ],
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'label' => esc_html__('Image Size', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'layout' => ['grid', 'carousel', 'flex-list', 'grid-2', 'grid-3']
                ],
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'courto-core'),
                    '300' => esc_html__('300x300 - Medium', 'courto-core'),
                    '1024' => esc_html__('1024x1024 - Large', 'courto-core'),
                    '840x840' => esc_html__('840x840 - 2 Columns', 'courto-core'),
                    '740x740' => esc_html__('740x740 - 3 Columns', 'courto-core'),
                    '886' => esc_html__('886x886 - 4 Columns Wide', 'courto-core'),
                    '1540x1088' => esc_html__('1540x1088 - Grid 2', 'courto-core'),
                    '870x620' => esc_html__('870x620 - Flex List', 'courto-core'),
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => '740x740',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'courto-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'layout' => ['grid', 'carousel', 'flex-list', 'grid-2', 'grid-3'],
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'courto-core'),
                'default' => [
                    'width' => '740',
                    'height' => '740',
                ],
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'layout' => ['grid', 'carousel', 'flex-list', 'grid-2', 'grid-3'],
                    'img_size_string!' => 'custom',
                ],
                'options' => [
                    '' => esc_html__('No Crop', 'courto-core'),
                    '1:1' => esc_html('1:1'),
                    '3:2' => esc_html('3:2'),
                    '4:3' => esc_html('4:3'),
                    '6:5' => esc_html('6:5'),
                    '9:16' => esc_html('9:16'),
                    '16:9' => esc_html('16:9'),
                    '21:9' => esc_html('21:9'),
                    '85:63,1:1' => esc_html__('Chess Type ( 85:63 / 1:1 )', 'courto-core'),
                    '1:1,85:63' => esc_html__('Chess Type ( 1:1 / 85:63 )', 'courto-core'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'remainings_loading_type',
            [
                'label' => esc_html__('Remaining Posts Loading Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['layout!' => ['carousel', 'flex-list']],
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'pagination' => esc_html__('Pagination', 'courto-core'),
                    'infinite' => esc_html__('Infinite Scroll', 'courto-core'),
                    'load_more' => esc_html__('Load More', 'courto-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'remainings_loading_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'remainings_loading_type' => 'pagination',
                    'layout!' => ['carousel', 'flex-list']
                ],
                'frontend_available' => true,
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
                    '{{WRAPPER}} .wgl-pagination' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'remainings_loading_pagination_offset',
            [
                'label' => esc_html__('Margin Top', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'remainings_loading_type' => 'pagination',
                    'layout!' => ['carousel', 'flex-list']
                ],
                'range' => [
                    'px' => ['min' => -150, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'remainings_loading_btn_items_amount',
            [
                'label' => esc_html__('Items to be loaded', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'layout!' => ['carousel', 'flex-list'],
                    'remainings_loading_type' => ['load_more', 'infinite'],
                ],
                'min' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'condition' => [
                    'layout!' => ['carousel', 'flex-list'],
                    'remainings_loading_type' => 'load_more',
                ],
                'default' => esc_html__('LOAD MORE', 'courto-core'),
            ]
        );

        $this->add_control(
            'appear_animation_enabled',
            [
                'label' => esc_html__('Appear Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'appear_animation_style',
            [
                'label' => esc_html__('Animation Style', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['appear_animation_enabled!' => ''],
                'options' => [
                    'fade-in' => esc_html__('Fade In', 'courto-core'),
                    'flip-x' => esc_html__('Flip X', 'courto-core'),
                    'flip-x-r' => esc_html__('Flip X Reverse', 'courto-core'),
                    'flip-y' => esc_html__('Flip Y', 'courto-core'),
                    'flip-y-r' => esc_html__('Flip Y Reverse', 'courto-core'),
                    'slide-top' => esc_html__('Slide Top', 'courto-core'),
                    'slide-bottom' => esc_html__('Slide Bottom', 'courto-core'),
                    'slide-left' => esc_html__('Slide Left', 'courto-core'),
                    'slide-right' => esc_html__('Slide Right', 'courto-core'),
                    'zoom' => esc_html__('Zoom', 'courto-core'),
                ],
                'default' => 'fade-in',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> APPEARANCE
         */

        $this->start_controls_section(
            'content_appearance',
            ['label' => esc_html__('Appearance', 'courto-core')]
        );

        $this->add_control(
            'gallery_mode_enabled',
            [
                'label' => esc_html__('Gallery Mode', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['layout!' => ['grid-2']],
            ]
        );

        $this->add_control(
            'use_additional_post',
            [
                'label' => esc_html__('Add Additional Post', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'add_video_background',
            [
                'label' => esc_html__('Video Background', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => '',
            ]
        );

        $this->add_control(
            'additional_post_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['use_additional_post!' => ''],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Additional Post item is fully cusomizible via `Style` tab.', 'courto-core'),
            ]
        );

        $this->add_control(
            'show_portfolio_title',
            [
                'label' => esc_html__('Show Heading', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_categories',
            [
                'label' => esc_html__('Show Categories', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_date',
            [
                'label' => esc_html__('Show Date', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'layout' => 'grid-2'
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'show_content',
            [
                'label' => esc_html__('Show Excerpt/Content', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Content Characters Amount', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'show_content' => 'yes',
                    'gallery_mode_enabled' => '',
                ],
                'min' => 1,
                'default' => 85,
            ]
        );

        $this->add_control(
            'show_icon_button',
            [
                'label' => esc_html__('Show Icon Button', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'layout' => 'grid-2'
                ],
                'default' => '',
            ]
        ); 

        $this->add_control(
            'icon_button_text',
            [
                'label' => esc_html__('Read More Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'layout' => 'grid-2',
                    'show_icon_button' => 'yes'
                ],
                'default' => esc_html__('VIEW PROJECT', 'courto-core'),
            ]
        );

        $this->add_control(
            'show_portfolio_title_first',
            [
                'label' => esc_html__('Show Title First', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'label' => esc_html__('Description', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['gallery_mode_enabled' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'layout!' => 'grid-2'
                ],
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inside_image' => esc_html__('Within Image', 'courto-core'),
                    'under_image' => esc_html__('Beneath Image', 'courto-core'),
                    'pf_cursor_tooltip' => esc_html__('Cursor Tooltip', 'courto-core'),
                ],
                'default' => 'inside_image',
            ]
        );

        $this->add_control(
            'description_animation',
            [
                'label' => esc_html__('Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => '',
                    'layout!' => 'grid-2'
                ],
                'options' => [
                    'simple' => esc_html__('Simple', 'courto-core'),
                    'sub_layer' => esc_html__('Sub-Layer', 'courto-core'),
                    'until_hover' => esc_html__('Visible Until Hover', 'courto-core'),
                    'always_visible' => esc_html__('Always Visible', 'courto-core'),
                ],
                'default' => 'always_visible',
            ]
        );

        $this->add_control(
            'show_icon_image',
            [
                'label' => esc_html__('Show Icon', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'description_position' => 'under_image',
                    'layout!' => 'grid-2'
                ],
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'description_alignment_v',
            [
                'label' => esc_html__('Description Position', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'description_position' => 'under_image',
                    'layout!' => 'grid-2'
                ],
                'toggle' => true,
                'options' => [
                    'column-reverse' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .description_under_image' => 'flex-direction: {{VALUE}};',
                ],
                'default' => 'column',
            ]
        );

        $this->add_control(
            'description_alignment',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'gallery_mode_enabled' => '',
                    'description_position!' => 'pf_cursor_tooltip',
                ],
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'courto-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .description__wrapper' => 'text-align: {{VALUE}};',
                ],
                'default' => 'left',
                'prefix_class' => 'descr-align-',
            ]
        );

        $this->add_control(
            'chess_layout',
            [
                'label' => esc_html__('Chess Layout', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'chess',
                'prefix_class' => 'layout-',
                'condition' => ['layout!' => ['grid-2', 'grid-3']],
            ]
        );

        $this->add_control(
            'chess_offset',
            [
                'label' => esc_html__('Chess Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [
                    'chess_layout!' => '',
                    'layout!' => ['grid-2', 'grid-3']
                ],
                'size_units' => ['px', 'rem'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 300],
                    'rem' => ['min' => 0.1, 'max' => 20, 'step' => 0.1],
                ],
                'default' => ['size' => '30'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-wrapper' => 'padding-top: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .wgl-portfolio_container:not(.carousel)' => 'padding-top: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}}.item-offset-even .portfolio__item:nth-child(even)' => 'margin-top: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.item-offset-odd .portfolio__item:nth-child(odd)' => 'margin-top: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'chess_odd_even',
            [
                'label' => esc_html__('Odd/Even Item Offset', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'chess_layout!' => '',
                    'layout!' => ['grid-2', 'grid-3']
                ],
                'options' => [
                    'odd' => esc_html__('Odd', 'courto-core'),
                    'even' => esc_html__('Even', 'courto-core'),
                ],
                'default' => 'odd',
                'prefix_class' => 'item-offset-'
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINKS
         */

        $this->start_controls_section(
            'content_links',
            [
                'label' => esc_html__('Links', 'courto-core'),
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_control(
            'image_has_link',
            [
                'label' => esc_html__('Add link on Image', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_has_link',
            [
                'label' => esc_html__('Add link on Heading', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['show_portfolio_title!' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'link_destination',
            [
                'label' => esc_html__('Click Action', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'title_has_link',
                            'operator' => '!==',
                            'value' => '',
                        ], [
                            'name' => 'image_has_link',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'options' => [
                    'single' => esc_html__('Open Single Page', 'courto-core'),
                    'custom' => esc_html__('Open Custom Link', 'courto-core'),
                    'popup' => esc_html__('Popup the Image', 'courto-core'),
                ],
                'default' => 'single',
            ]
        );

        $this->add_control(
            'link_custom_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['link_destination' => 'custom'],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in metabox section of each corresponding post.', 'courto-core'),
            ]
        );

        $this->add_control(
            'link_target',
            [
                'label' => esc_html__('Open link in a new tab', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'title_has_link',
                                    'operator' => '!==',
                                    'value' => '',
                                ], [
                                    'name' => 'image_has_link',
                                    'operator' => '!==',
                                    'value' => '',
                                ],
                            ],
                        ],
                        [
                            'name' => 'link_destination',
                            'operator' => '!==',
                            'value' => 'popup',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        WGL_Cursor::init(
            $this,
            [
                'section' => true,
                'condition' => ['description_position!' => 'pf_cursor_tooltip'],
            ]
        );

        /**
         * Image Effects
         */
        $this->start_controls_section(
            'wgl_webgl_image_hover_start_section',
            [
                'label' => esc_html__('Webgl Image Hover', 'courto-core'),
            ]
        );

        $this->add_control(
            'wgl_webgl_image_hover_heading',
            [
                'label' => esc_html__('WGL Webgl Image Hover Effects', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wgl_webgl_image_hover_effects',
            [
                'label' => esc_html__('Image Hover Interaction Effects', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' =>  WGL_Webgl_Image_Hover::get_instance()->image_hover_effects_list(),
                'default' => '',
            ]
        );

        $this->add_control(
            'wgl_webgl_image_hover_trigger',
            [
                'label' => esc_html__('Trigger', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'visible' => esc_html__( 'Always Visible', 'courto-core' ),
                    'hover' => esc_html__( 'On Hover', 'courto-core' ),
                ],
                'condition' => [
                    'wgl_webgl_image_hover_effects' => 'ripple',
                ],
                'default' => 'hover',
            ]
        );

        $this->add_control(
            'wgl_webgl_image_premultiplied_alpha',
            [
                'label' => esc_html__( 'Transparent Alpha', 'courto-core' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'courto-core' ),
                'label_off' => esc_html__( 'No', 'courto-core' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'wgl_webgl_image_hover_effects!' => ['', 'flowmap'],
                ],
                'description' => esc_html__( 'Enable if your image uses premultiplied alpha (useful for WebGL transparency correction).', 'courto-core' ),
            ]
        );
        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        $this->start_controls_section(
            'content_carousel',
            [
                'label' => esc_html__('Carousel Options', 'courto-core'),
                'condition' => ['layout' => 'carousel'],
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this, [
            'slider_infinite' => [
                'default' => 'yes'
            ],
            'slide_per_single' => [
                'default' => 1
            ],
            'variable_width_height' => [
                'wrapper' => '.item__image img',
            ],
        ]);

        $this->add_responsive_control(
            'center_scale_size',
            [
                'label' => esc_html__( 'Center Item Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'center_mode!' => '' ],
                'size_units' => [ '%', 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .center-mode .portfolio__item.swiper-slide-active .item__wrapper' => '--wgl-portfolio-center-scale-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'around_center_scale_size',
            [
                'label' => esc_html__( 'Items Around The Central Scale', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'center_mode!' => '' ],
                'size_units' => [ '%', 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 80, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .center-mode .portfolio__item:not(.swiper-slide-active) .item__wrapper' => '--wgl-portfolio-center-scale-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'chess_divider_before',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['chess_layout!' => ''],
            ]
        );

        $this->add_control(
            'multi_sized_layout',
            [
                'label' => esc_html__( 'Multi-sized Layout', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'scale',
                'prefix_class' => 'layout-',
            ]
        );

        $this->add_responsive_control(
            'scale_size',
            [
                'label' => esc_html__( 'Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'multi_sized_layout!' => '' ],
                'size_units' => [ '%', 'px', 'custom' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 100, 'step' => 1 ],
                ],
                'default' => [ 'size' => 80, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:nth-child(even)' => '--wgl-portfolio-scale-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'chess_layout',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'pagination_type'  => [
                'default' => 'circle_border',
            ],
        ]);

        $this->add_control(
            'pagination_navigation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_navigation_controls($this);

        $this->add_control(
            'navigation_responsive_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'customize_responsive',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_responsive_controls($this, [
            'desktop_slides' => [
                'label' => esc_html__('Columns amount', 'courto-core'),
                'max' => 5,
            ],
            'tablet_slides' => [
                'label' => esc_html__('Columns amount', 'courto-core'),
                'max' => 5,
            ],
            'mobile_slides' => [
                'label' => esc_html__('Columns amount', 'courto-core'),
                'max' => 5,
            ],
        ]);

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls($this, [
            'post_type' => 'portfolio',
            'hide_cats' => true,
            'hide_tags' => true
        ]);

        /**
         * STYLE -> FILTER
         */

        $this->start_controls_section(
            'style_filter',
            [
                'label' => esc_html__('Filter', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_filter' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );

        $this->add_control(
            'filter_cats_gap',
            [
                'label' => esc_html__('Categories Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100, 'step' => 2],
                ],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter' => '--wgl-filtet-categories-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'apply_underline_animation',
            [
                'label' => esc_html__('Apply Underline Animation', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'underline',
                'default' => 'underline',
                'prefix_class' => 'animation_',
            ]
        );

        $this->add_responsive_control(
            'filter_cats_wrapper_margin',
            [
                'label' => esc_html__('Wrapper Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-cpt_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'filter_cats_wrapper_padding',
            [
                'label' => esc_html__('Wrapper Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-cpt_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_cats_wrapper_border',
                'fields_options' => [
                    'border' => [ 'label' => esc_html__( 'Wrapper Border Type', 'courto-core' ) ],
                ],
                'selector' => '{{WRAPPER}} .wgl-cpt_header',
            ]
        );

        $this->add_responsive_control(
            'filter_cats_padding',
            [
                'label' => esc_html__('Category Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .isotope-filter a .cat_title' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->add_control(
            'filter_cats_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('filter');

        $this->start_controls_tab(
            'filter_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'filter_color_idle',
            [
                'label' => esc_html__('Category Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:not(.active)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_counter_color_idle',
            [
                'label' => esc_html__('Counter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['filter_counter_enabled' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:not(.active) .filter_counter' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:not(.active)' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_idle',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'filter_color_hover',
            [
                'label' => esc_html__('Category Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_counter_color_hover',
            [
                'label' => esc_html__('Counter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['filter_counter_enabled' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover .filter_counter' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_hover',
                'selector' => '{{WRAPPER}} .isotope-filter a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );

        $this->add_control(
            'filter_color_active',
            [
                'label' => esc_html__('Category Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_counter_color_active',
            [
                'label' => esc_html__('Counter Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['filter_counter_enabled' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active .filter_counter' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_active',
                'selector' => '{{WRAPPER}} .isotope-filter a.active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'filter_shadow_divider',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_shadow',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> MODULE TITLE
         */

        $this->start_controls_section(
            'section_style_module_title',
            [
                'label' => esc_html__('Module Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'portfolio_title',
                            'operator' => '!in',
                            'value' => [''],
                        ], [
                            'name' => 'portfolio_subtitle',
                            'operator' => '!in',
                            'value' => [''],
                        ], [
                            'name' => 'show_button',
                            'operator' => '!in',
                            'value' => [''],
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_portfolio_title_width',
            [
                'label' => esc_html__( 'Module Title Width', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1200 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_header .item_title' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_portfolio_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['portfolio_title!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_title',
                'selector' => '{{WRAPPER}} .portfolio_title',
                'condition' => ['portfolio_title!' => ''],
            ]
        );

        $this->add_control(
            'heading_portfolio_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['portfolio_title!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'portfolio_title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['portfolio_title!' => ''],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_portfolio_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['portfolio_subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_subtitle',
                'condition' => ['portfolio_subtitle!' => ''],
                'selector' => '{{WRAPPER}} .portfolio_subtitle',
            ]
        );

        $this->add_control(
            'heading_portfolio_subtitle_color',
            [
                'label' => esc_html__('Subtitle Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['portfolio_subtitle!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'portfolio_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['portfolio_subtitle!' => ''],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_portfolio_button',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['show_button!' => ''],
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs(
            'heading_portfolio_button_tabs',
            [
                'condition' => ['show_button!' => ''],
            ]
        );

        $this->start_controls_tab(
            'heading_button_idle_tab',
            ['label' => esc_html__('Idle', 'courto-core')],
        );

        $this->add_control(
            'heading_button_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_button_border_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_button_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'heading_button_hover_tab',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'heading_button_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_button_border_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'heading_button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_header-button .wgl-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> OVERLAYS
         */

        $this->start_controls_section(
            'style_overlays',
            [
                'label' => esc_html__('Overlays', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_scale_animation',
            [
                'label' => esc_html__( 'Use Custom Image Hover Scale Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'image_wrap_margin',
            [
                'label' => esc_html__('Image Wrapper Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__image-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_scale_size',
            [
                'label' => esc_html__( 'Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_scale_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1.07 ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item .item__wrapper' => '--wgl-portfolio-image-scale-size: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_scale_animation!' => '' ],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.8],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item .item__wrapper' => '--wgl-portfolio-image-transition: {{SIZE}}s',
                ],
            ]
        );

        $this->add_control(
            'images_overlay_heading',
            [
                'label' => esc_html__('Images', 'courto-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pf_bg_first',
                'fields_options' => [
                    'background' => [ 'label' => esc_html__( 'First Background', 'courto-core' ) ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .item__image-wrap::before',
            ]
        );

        $this->add_control(
            'pf_bg_first_z_index',
            [
                'label' => esc_html__('First Background z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'condition' => [ 'pf_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .item__image-wrap::before' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pf_bg_second',
                'fields_options' => [
                    'background' => [ 'label' => esc_html__( 'Second Background', 'courto-core' ) ],
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .item__image-wrap::after,{{WRAPPER}} .wgl-video-hero.portfolio-video .wgl-video-hero__video:after',
            ]
        );
        $this->add_control(
            'pf_bg_second_z_index',
            [
                'label' => esc_html__('Second Background z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'condition' => [ 'pf_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .item__image-wrap::after,{{WRAPPER}} .wgl-video-hero.portfolio-video .wgl-video-hero__video:after' => 'z-index: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_background');
        $this->start_controls_tab(
            'tab_bg_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'images_grayscale_idle',
            [
                'label' => esc_html__('Grayscale Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'img_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item .item__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_bg_first_idle',
            [
                'label' => esc_html__('Opacity for First Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'pf_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .item__image-wrap::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_bg_second_idle',
            [
                'label' => esc_html__('Opacity for Second Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'pf_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .item__image-wrap::after, {{WRAPPER}} .wgl-video-hero.portfolio-video .wgl-video-hero__video:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_bg_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'images_grayscale_hover',
            [
                'label' => esc_html__('Grayscale Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:hover .item__image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'img_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item .item__wrapper:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'pf_bg_first_hover',
            [
                'label' => esc_html__('Opacity for First Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'pf_bg_first_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:hover .item__image-wrap::before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_bg_second_hover',
            [
                'label' => esc_html__('Opacity for Second Background', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'pf_bg_second_background!' => '' ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:hover .item__image-wrap::after, {{WRAPPER}} .wgl-video-hero.portfolio-video .wgl-video-hero__video:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> DESCRIPTIONS
         */

        $this->start_controls_section(
            'style_descriptions',
            [
                'label' => esc_html__('Descriptions', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'description_border',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 0.76,
                            'right' => 0.76,
                            'bottom' => 0.76,
                            'left' => 0.76,
                        ],
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->add_control(
            'description_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('description_info');

        $this->start_controls_tab(
            'description_info_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'description_info_idle',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->add_control(
            'description_info_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'description_border_border!' => ['', 'none']
                ],
                'default' => WGL_Globals::get_tertiary_color(0.2),
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'description_info_shadow_idle',
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->add_responsive_control(
            'description_info_backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'description_info_tab_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'description_info_hover',
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} .item__wrapper:hover .item__description',
            ]
        );

        $this->add_control(
            'description_info_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'description_border_border!' => ['', 'none']
                ],
                'default' => WGL_Globals::get_tertiary_color(0),
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .item__description' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'description_info_shadow_hover',
                'selector' => '{{WRAPPER}} .item__wrapper:hover .item__description',
            ]
        );

        $this->add_responsive_control(
            'description_info_backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .item__description' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> HEADINGS
         */

        $this->start_controls_section(
            'style_headings',
            [
                'label' => esc_html__('Headings', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_portfolio_title!' => '',
                    'gallery_mode_enabled' => ''
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'headings',
                'selector' => '{{WRAPPER}} .title, {{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__title .title',
            ]
        );

        $this->add_responsive_control(
            'headings_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'headings' );

        $this->start_controls_tab(
            'headings_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'headings_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'headings_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'headings_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item .item__wrapper .title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'headings_wrapper_hover',
            ['label' => esc_html__('Wrapper Hover', 'courto-core')]
        );
        $this->add_control(
            'headings_color_wrapper_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CATEGORIES
         */

        $this->start_controls_section(
            'style_categories',
            [
                'label' => esc_html__('Categories', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_meta_categories!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'categories',
                'selector' => '{{WRAPPER}} .portfolio-category',
            ]
        );

        $this->add_responsive_control(
            'cat_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .post_cats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'cat_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'show_cat_sep',
            [
                'label' => esc_html__('Show Separator', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this->add_control(
            'cat_sep_color',
            [
                'label' => esc_html__('Separator Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cat_separator_yes .portfolio-category::before, {{WRAPPER}} .cat_separator_yes .portfolio-category::after' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cats_border',
                'fields_options' => [
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .portfolio-category',
            ]
        );
        $this->start_controls_tabs( 'categories' );
        $this->start_controls_tab(
            'categories_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_responsive_control(
            'cat_padding_idle',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'cat_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'cats_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'categories_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_responsive_control(
            'cat_padding_hover',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item .item__wrapper .portfolio-category:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'cat_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item .item__wrapper .portfolio-category:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item .item__wrapper .portfolio-category:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'cats_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .item .item__wrapper .portfolio-category:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'categories_wrapper_hover',
            ['label' => esc_html__('Wrapper Hover', 'courto-core')]
        );
        $this->add_responsive_control(
            'cat_padding_wrapper_hover',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .portfolio-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'cat_color_wrapper_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .portfolio-category' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_bg_wrapper_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .portfolio-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cat_border_color_wrapper_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'cats_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper:hover .portfolio-category' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> EXCERPT/CONTENT
         */

        $this->start_controls_section(
            'style_excerpt',
            [
                'label' => esc_html__('Excerpt|Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_content!' => ''],
            ]
        );

        $this->start_controls_tabs( 'excerpt' );
        
        $this->start_controls_tab(
            'excerpt_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'custom_content_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'excerpt_hover',
            ['label' => esc_html__('Wrapper Hover', 'courto-core')]
        );

        $this->add_control(
            'custom_content_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__wrapper .description_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->end_controls_section();

        /**
         * STYLE -> LOAD MORE BUTTON
         */

        $this->start_controls_section(
            'style_load_more',
            [
                'label' => esc_html__('Load More Button', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['remainings_loading_type' => 'load_more'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_alignment',
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
                    '{{WRAPPER}} .load_more_wrapper' => 'text-align: {{VALUE}};',
                ],
                'condition' => ['load_more_fw' => ''],
            ]
        );

        $this->add_control(
            'load_more_fw',
            [
                'label' => esc_html__('Full Width', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .button_wrapper' => 'width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->start_controls_tabs( 'load_more_btn' );
        $this->start_controls_tab( 'load_more_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'load_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'load_more_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-load_more_item:active' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'load_more_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'load_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'load_more_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .wgl-load_more_item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'load_more_active',
            ['label' => esc_html__('Active', 'courto-core')]
        );
        $this->add_control(
            'load_more_color_active',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_bg_active',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item:active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_border_color_active',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'load_more_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .wgl-load_more_item:active' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'load_more_media_heading',
            [
                'label' => esc_html__('Media', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'load_more_media_type',
            [
                'label' => esc_html__('Media Type', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'courto-core'),
                        'icon' => 'eicon-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'courto-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => 'icon'
            ]
        );
        $this->add_control(
            'load_more_media_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'library' => 'fa-solid',
                    'value' => 'fas fa-icons',
                ],
                'condition' => ['load_more_media_type' => 'icon'],
            ]
        );

        $this->add_responsive_control(
            'load_more_icon_rotate',
            [
                'label' => esc_html__( 'Icon Rotate', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => ['load_more_media_type' => 'icon'],
                'size_units' => [ 'deg', 'turn' ],
                'range' => [
                    'deg' => [ 'min' => -360, 'max' => 360 ],
                    'turn' => [ 'min' => -1, 'max' => 1, 'step' => 0.1 ],
                ],
                'default' => [
                    'size' => -45,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => '--icon-rotate: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_icon_align',
            [
                'label' => esc_html__('Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => false,
                'condition' => ['load_more_media_type' => 'icon'],
                'frontend_available' => true,
                'prefix_class' => 'load_more_icon_align-',
                'options' => [
                    'row' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'row-reverse',
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_separator_offset_x',
            [
                'label' => esc_html__('Separator Offset X', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'px',
                ],
                'condition' => [
                    'load_more_media_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}}.load_more_icon_align-row .load_more_item::after'
                        => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    '{{WRAPPER}}.load_more_icon_align-row-reverse .load_more_item::after'
                        => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_separator_offset_y',
            [
                'label' => esc_html__('Separator Offset Y', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'px',
                ],
                'condition' => [
                    'load_more_media_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item::after'
                        => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_separator_size',
            [
                'label' => esc_html__('Separator Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 150],
                ],
                'condition' => [
                    'load_more_media_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item::after'
                        => '--icon-separator-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_loading_offset',
            [
                'label' => esc_html__('Loading Icon Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'condition' => [
                    'load_more_media_type' => 'icon',
                ],
                'selectors' => [
                    '{{WRAPPER}}.load_more_icon_align-row .load_more_item::after'
                        => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    '{{WRAPPER}}.load_more_icon_align-row-reverse .load_more_wrapper.icon-yes .wgl_loading_icon'
                        => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_icon_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'load_more_icon_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_icon_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'separator' => 'after',
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_icon',
            ['condition' => ['load_more_media_type' => 'icon']]
        );

        $this->start_controls_tab(
            'load_more_icon_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'load_more_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_icon_bg_idle',
            [
                'label' => esc_html__('Separator Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'load_more_icon_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'load_more_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'load_more_icon_bg_hover',
            [
                'label' => esc_html__('Separator Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover::after, {{WRAPPER}} .load_more_item:focus::after, {{WRAPPER}} .load_more_item:active::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> ADDITIONAL POST
         */

        $this->start_controls_section(
            'style_additional_item',
            [
                'label' => esc_html__('Additional Post', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['use_additional_post!' => ''],
            ]
        );

        $this->add_control(
            'additional_post_position',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'first' => esc_html__('First Item', 'courto-core'),
                    'last' => esc_html__('Last Item', 'courto-core'),
                ],
                'default' => 'last',
            ]
        );

        $this->add_control(
            'additional_post_link',
            [
                'label' => esc_html__('Link', 'courto-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'placeholder' => esc_attr__('https://your-link.com', 'courto-core'),
            ]
        );

        $this->add_control(
            'additional_post_img_heading',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'additional_post_img_media',
            [
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_control(
            'additional_post_btn_heading',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'additional_post_btn_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Coming Soon', 'courto-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'additional_post_btn',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->add_control(
            'additional_post_btn_align_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                    '{{WRAPPER}} .additional-post .item__wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'additional_post_btn_align_v',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'additional_post_btn_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'additional_post_btn_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'additional_post_btn_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'additional_post_button',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->start_controls_tabs('additional_post_btn');

        $this->start_controls_tab(
            'addtnl_btn_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'addtnl_btn_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'addtnl_btn_bg_idle',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'addtnl_btn_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'addtnl_btn_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'addtnl_btn_bg_hover',
                'selector' => '{{WRAPPER}} .additional-post .item__button:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> Grid 2
         */

        $this->start_controls_section(
            'style_grid_2_item',
            [
                'label' => esc_html__('Grid 2', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['layout' => 'grid-2'],
            ]
        );

        $this->add_responsive_control(
            'grid_2_post_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}  .wgl-portfolio_container.grid-2 .portfolio__item:nth-child(odd) .item__wrapper .item__description' => 'padding-left: 0;',
                    '{{WRAPPER}}  .wgl-portfolio_container.grid-2 .portfolio__item:nth-child(even) .item__wrapper .item__description' => 'padding-right: 0;',
                ],
            ]
        );

        $this->add_control(
            'grid_2_btn_heading',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'grid_2_post_button_text',
                'selector' => '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a span',
            ]
        );

        $this->add_responsive_control(
            'grid_2_post_button_size',
            [
                'label' => esc_html__( 'Button Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'vw', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a' =>
                        '--size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_2_post_button_gap',
            [
                'label' => esc_html__( 'Gap', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', 'em', 'vw', 'custom' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a' => '--gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_2_btn_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => ['show_icon_button' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'grid_2_btn_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 300],
                ],
                'condition' => [
                    'show_icon_button' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a::before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('grid_2_btn', ['condition' => ['show_icon_button' => 'yes']]);

        $this->start_controls_tab(
            'grid_2_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'grid_2_color_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_text_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_btn_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a::after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_btn_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper .item__button a::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'grid_2_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'grid_2_btn_color_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper:hover .item__button a::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper:hover .item__button a span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_btn_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper:hover .item__button a::after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grid_2_btn_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-portfolio_container.grid-2 .item__wrapper:hover .item__button a::after' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CURSOR TOOLTIP
         */

        $this->start_controls_section(
            'pf_style_tooltip',
            [
                'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['description_position' => 'pf_cursor_tooltip'],
            ]
        );
        $this->start_controls_tabs('pf_tabs_cursor');
        $this->start_controls_tab(
            'pf_tabs_cursor_title',
            ['label' => esc_html__('Title', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pf_tooltip_title',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6',
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_title_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_title_alignment',
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
                'default' => 'left',
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'pf_tooltip_title_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pf_tooltip_title_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6',
            ]
        );
        $this->add_control(
            'pf_tooltip_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'pf_tooltip_title_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip h6' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'pf_tabs_cursor_cats',
            ['label' => esc_html__('Category', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pf_tooltip_cats',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats',
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_cats_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_cats_alignment',
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
                'default' => 'left',
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'pf_tooltip_cats_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'pf_tooltip_cats_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pf_tooltip_cats_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats',
            ]
        );
        $this->add_control(
            'pf_tooltip_cats_color',
            [
                'label' => esc_html__('Category Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'pf_tooltip_cats_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.portfolio-tooltip .post_cats' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new Portfolio_Template($atts, $this))->render($this);
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
