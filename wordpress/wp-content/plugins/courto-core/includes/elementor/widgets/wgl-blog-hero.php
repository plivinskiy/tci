<?php
/**
 * This template can be overridden by copying it to `courto[-child]/courto-core/elementor/widgets/wgl-blog-hero.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow
};
use WGL_Extensions\{
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\Wgl_Blog_Hero as Blog_Hero_Template
};

class Wgl_Blog_Hero extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-blog-hero';
    }

    public function get_title()
    {
        return esc_html__('WGL Blog Hero', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-blog-hero';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_style_depends()
    {
		return [ 'swiper' ];
	}

    public function get_script_depends()
    {
        return [
            'swiper',
            'jarallax',
            'jarallax-video',
            'imagesloaded',
            'isotope',
            'wgl-widgets',
        ];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> LAYOUT
         */

        $this->start_controls_section(
            'section_content_layout',
            ['label' => esc_html__('Layout', 'courto-core')]
        );

        $this->add_control(
            'blog_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'blog_subtitle',
            [
                'label' => esc_html__('Subitle', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'separator' => 'after',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'blog_layout',
            [
                'label' => esc_html__('Layout', 'courto-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'blog_columns',
            [
                'label' => esc_html__('Grid Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'label_block' => true,
                'frontend_available' => true,
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                ],
                'default' => '3',
                'selectors' => [
                    '{{WRAPPER}} .blog-style-standard' => '--posts-width: calc(100% / {{VALUE}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'col_gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-style-standard' => '--posts-col-gap: {{SIZE}}px; --posts-row-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'blog_min_height',
            [
                'label' => esc_html__('Posts Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 200, 'max' => 1000],
                ],
                'default' => ['size' => 370],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_content' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'label' => esc_html__('Image Size', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('Thumbnail - 150x150', 'courto-core'),
                    '300' => esc_html__('Medium - 300x300', 'courto-core'),
                    '768' => esc_html__('Medium Large - 768x768', 'courto-core'),
                    '1024' => esc_html__('Large - 1024x1024', 'courto-core'),
                    '740x740' => esc_html__('740x740 - 3 Columns', 'courto-core'),
                    '1140x950' => esc_html__('1140x950 - 2 Columns', 'courto-core'),
                    '1170x700' => esc_html__('1170x700 - 1 Column', 'courto-core'),
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
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'courto-core'),
                'default' => [
                    'width' => '740',
                    'height' => '740',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'img_size_string!' => 'custom',
                ],
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

        $this->add_control(
            'navigation_type',
            [
                'label' => esc_html__('Navigation Type', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['blog_layout' => ['grid', 'masonry']],
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'pagination' => esc_html__('Pagination', 'courto-core'),
                    'load_more' => esc_html__('Load More', 'courto-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_responsive_control(
            'navigation_align',
            [
                'label' => esc_html__('Navigation\'s Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['navigation_type' => 'pagination'],
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
                'prefix_class' => 'nav-%s',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'navigation_offset',
            [
                'label' => esc_html__('Navigation Margin Top', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'navigation_type' => 'pagination',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 240],
                ],
                'default' => ['size' => 22],
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
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry'],
                ],
                'min' => 1,
                'default' => 4,
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'dynamic' => ['active' => true],
                'default' => esc_html__('LOAD MORE', 'courto-core'),
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
            'hide_media',
            [
                'label' => esc_html__('Hide Media?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'media_link',
            [
                'label' => esc_html__('Clickable Post?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_media' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'hide_blog_title',
            [
                'label' => esc_html__('Hide Title?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'crop_words_title',
            [
                'label' => esc_html__('Limit Title Characters', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_blog_title' => ''],
            ]
        );

        $this->add_control(
            'title_letter_count',
            [
                'label' => esc_html__('Title Characters Amount', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'hide_blog_title' => '',
                    'crop_words_title' => 'yes'
                ],
                'min' => 1,
                'default' => 30,
            ]
        );

        $this->add_control(
            'break_words_title',
            [
                'label' => esc_html__('Break Title Words', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'hide_blog_title' => '',
                    'crop_words_title' => 'yes'
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'hide_content',
            [
                'label' => esc_html__('Hide Content?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Content Characters Amount', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_content' => ''],
                'min' => 1,
                'default' => 200,
            ]
        );

        $this->add_control(
            'read_more_hide',
            [
                'label' => esc_html__('Hide \'Read More\' button?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['read_more_hide' => ''],
                'dynamic' => ['active' => true],
                'default' => esc_html__('READ MORE', 'courto-core'),
            ]
        );

        $this->add_control(
            'hide_all_meta',
            [
                'label' => esc_html__('Hide all post meta?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_author',
            [
                'label' => esc_html__('Hide author?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'meta_comments',
            [
                'label' => esc_html__('Hide comments?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'meta_categories',
            [
                'label' => esc_html__('Hide post-meta categories?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'meta_date',
            [
                'label' => esc_html__('Hide post-meta date?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'hide_views',
            [
                'label' => esc_html__('Hide Views?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'hide_likes',
            [
                'label' => esc_html__('Hide Likes?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['hide_all_meta' => ''],
            ]
        );

        $this->add_control(
            'hide_share',
            [
                'label' => esc_html__('Hide Shares?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['hide_all_meta' => ''],
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
                'condition' => ['blog_layout' => 'carousel']
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this);

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination' => 'yes'],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'pagination_margin' => [
                'default' => [
                    'top' => '-35',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ]
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

        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls(
            $this,
            ['post_type' => 'post']
        );

        /**
         * STYLE -> POST ITEM
         */

        $this->start_controls_section(
            'section_style_post_item',
            [
                'label' => esc_html__('Post Item', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__('Content Section Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .blog-post_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('post_bg');

        $this->start_controls_tab(
            'tab_post_bg_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'post_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .image-overlay::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_post_bg_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'post_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .image-overlay::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'post_bg_hover_no_image',
            [
                'label' => esc_html__('No Image Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .hide_media .image-overlay::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> MODULE TITLE
         */

        $this->start_controls_section(
            'section_style_module_title',
            [
                'label' => esc_html__('Module Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['blog_title!' => ''],
            ]
        );

        $this->add_control(
            'heading_blog_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_title',
                'selector' => '{{WRAPPER}} .blog_title',
            ]
        );

        $this->add_responsive_control(
            'blog_title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .blog_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_blog_subtitle',
            [
                'label' => esc_html__('Subtitle', 'courto-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_subtitle',
                'selector' => '{{WRAPPER}} .blog_subtitle',
            ]
        );

        $this->add_responsive_control(
            'blog_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .blog_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> HEADINGS
         */

        $this->start_controls_section(
            'style_headings',
            [
                'label' => esc_html__('Headings', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_blog_headings',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .blog-post_title,'
                            . '{{WRAPPER}} .blog-post_title > a',
            ]
        );

        $this->add_control(
            'heading_tag',
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
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('headings');

        $this->start_controls_tab(
            'tab_headings_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'headings_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title a, {{WRAPPER}} .blog-post_quote-text, {{WRAPPER}} .link_post, {{WRAPPER}} .blog-post_quote-text, {{WRAPPER}} .blog-post_link a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_headings_hover_post',
            ['label' => esc_html__('Post Hover', 'courto-core')]
        );

        $this->add_control(
            'headings_color_hover_post',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .blog-post_title a, {{WRAPPER}} .blog-post_wrapper:hover .blog-post_quote-text, {{WRAPPER}} .blog-post_wrapper:hover .blog-post_link a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_headings_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'headings_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .blog-post_title a:hover, {{WRAPPER}} .blog-post_wrapper .blog-post_link a:hover' => 'color: {{VALUE}};',
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
                'condition' => ['hide_content' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content',
                'selector' => '{{WRAPPER}} .blog-post_text',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('content');

        $this->start_controls_tab(
            'tab_content_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'content_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text, {{WRAPPER}} .blog-post_quote-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_content_hover_post',
            ['label' => esc_html__('Post Hover', 'courto-core')]
        );

        $this->add_control(
            'content_color_hover_post',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .blog-post_text, {{WRAPPER}} .blog-post_wrapper:hover .blog-post_quote-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> META DATA
         */

        $this->start_controls_section(
            'style_meta_data',
            [
                'label' => esc_html__('Meta Data', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'hide_all_meta',
                            'operator' => '==',
                            'value' => ''
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'meta_author',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_comments',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_categories',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                                [
                                    'name' => 'meta_date',
                                    'operator' => '==',
                                    'value' => ''
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_data',
                'selector' => '{{WRAPPER}} .post_meta-wrap .meta-data',
            ]
        );

        $this->add_responsive_control(
            'meta_data_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .post_meta-wrap .meta-data' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_data_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .post_meta-wrap .meta-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_meta_data',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_meta_data_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'meta_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .meta-data' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_hover_post',
            ['label' => esc_html__('Post Hover', 'courto-core')]
        );

        $this->add_control(
            'meta_color_hover_post',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .meta-data' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'meta_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .meta-data a:hover' => 'color: {{VALUE}};',
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
            'style_cats',
            [
                'label' => esc_html__('Categories', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'categories_typo',
                'selector' => '{{WRAPPER}} .post_categories a',
            ]
        );

        $this->start_controls_tabs('cats');

        $this->start_controls_tab(
            'tab_cats_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'cats_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .post_categories a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_bg_color_idle',
            [
                'label' => esc_html__('Bacground Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .post_categories a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .post_categories a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_cats_hover_post',
            ['label' => esc_html__('Post Hover', 'courto-core')]
        );

        $this->add_control(
            'cats_color_hover_post',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .post_categories a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_bg_color_hover_post',
            [
                'label' => esc_html__('Bacground Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .post_categories a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_border_color_hover_post',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .post_categories a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_cats_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'cats_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .post_categories a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_bg_color_hover',
            [
                'label' => esc_html__('Bacground Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .post_categories a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cats_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .post_categories a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> READ MORE
         */

        $this->start_controls_section(
            'section_style_read_more',
            [
                'label' => esc_html__('Read More', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['read_more_hide' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'read_more',
                'selector' => '{{WRAPPER}} .button-read-more',
            ]
        );

        $this->add_responsive_control(
            'read_more_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .read-more-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .read-more-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_read_more',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_read_more_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'read_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .button-read-more' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .button-read-more .read-more-icon' => '--icon-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_read_more_hover_post',
            ['label' => esc_html__('Post Hover', 'courto-core')]
        );

        $this->add_control(
            'read_more_color_hover_post',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper:hover .button-read-more' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog-post_wrapper:hover .button-read-more .read-more-icon' => '--icon-color-hover: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_read_more_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'read_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_wrapper .button-read-more:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .blog-post_wrapper .button-read-more:hover .read-more-icon' => '--icon-color-hover: {{VALUE}};',
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
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry'],
                ],
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
        $this->start_controls_tab(
            'load_more_idle',
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
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new Blog_Hero_Template())->render($this, $atts);
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
