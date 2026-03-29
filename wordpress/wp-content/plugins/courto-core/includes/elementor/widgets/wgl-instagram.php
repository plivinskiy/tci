<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-gallery.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Repeater,
    Utils,
    Controls_Manager,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography
};
use WGL_Extensions\{
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Elementor_Helper
};


class WGL_Instagram extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-instagram';
    }

    public function get_title()
    {
        return esc_html__('WGL Instagram', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-instagram';
    }

    public function get_keywords()
    {
        return [ 'instagram', 'social' ];
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
                'default' => '1',
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'default' => ['size' => 30],
                'selectors' => [
                    '{{WRAPPER}} .instagram__wrapper' => 'padding: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .wgl-instagram > .row, {{WRAPPER}} .wgl-instagram .wgl-carousel_wrapper' => 'margin: calc(-{{SIZE}}px / 2);',
                ],
                'render_type' => 'template',
            ]
        );

        $repeater = new Repeater();

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
            'author_thumbnail',
            [
                'label' => esc_html__('Author Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
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
            'thumbnail',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'link_thumbnail',
            [
                'label' => esc_html__('Link Image', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'info',
            [
                'label' => esc_html__('Text', 'courto-core'),
                'type' => Controls_Manager::WYSIWYG,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'show_likes',
            [
                'label' => esc_html__('Show Likes?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $repeater->add_control(
            'likes_count',
            [
                'label' => esc_html__('Count', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'condition' => ['show_likes' => 'yes'],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'author_name' => esc_html__('TINA OLSOS', 'courto-core'),
                        'info' => esc_html__('We have consistently focused on the intensity of development: the fundamental and basic part of the hands in blend with care items. Contact, rub, work... At home and in beauty institutes...', 'courto-core'),
                        'thumbnail' => Utils::get_placeholder_image_src(),
                    ],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ author_name }}}',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        WGL_Carousel_Settings::add_controls($this, [
            '3d_animation_options' => 'enabled',
            'animation_style' => [
                'default' => 'default',
            ],
            'slide_per_single' => [
                'default' => 1,
            ],
            'pagination_margin' => [
                'default' => [
                    'size' => 0
                ],
            ],
            'variable_width_height' => [
                'wrapper' => '.item_image img',
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
            'item_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .instagram__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-container' => 'margin: calc(-1 * {{TOP}}{{UNIT}}) calc(-1 * {{RIGHT}}{{UNIT}}) calc(-1 * {{BOTTOM}}{{UNIT}}) calc(-1 * {{LEFT}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .instagram__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .instagram__item, {{WRAPPER}} .instagram__item::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'selector' => '{{WRAPPER}} .instagram__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .instagram__item::before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .instagram__item',
            ]
        );

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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_name',
                'selector' => '{{WRAPPER}} .author__name',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR THUMBNAIL
         */

        $this->start_controls_section(
            'style_author_thumnail',
            [
                'label' => esc_html__('Author Thumbnail', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'author_thumnail_size',
            [
                'label' => esc_html__('Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 20, 'max' => 400],
                ],
                'default' => ['size' => 50],
            ]
        );

        $this->add_responsive_control(
            'author_thumnail_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'author_thumnail_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'author_thumnail_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'author_thumbnail_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .author__thumbnail img',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'author_thumbnail_shadow',
                'selector' => '{{WRAPPER}} .author__thumbnail img',
            ]
        );

        $this->end_controls_section();


        /**
         * STYLE -> AUTHOR ICON
         */

        $this->start_controls_section(
            'style_icon',
            [
                'label' => esc_html__('Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
		    'icon_enabled',
		    [
			    'label' => esc_html__('Use Icon', 'courto-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'default' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'condition' => ['icon_enabled' => 'yes'],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 200],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
		    'icon_padding',
		    [
			    'label' => esc_html__('Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['icon_enabled' => 'yes'],
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_margin',
		    [
			    'label' => esc_html__('Margin', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['icon_enabled' => 'yes'],
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_color',
		    [
			    'label' => esc_html__('Icon Color', 'courto-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['icon_enabled' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon::before' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
        $this->add_control(
            'icon_overlay',
            [
                'label' => esc_html__('Shape Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['icon_enabled' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .item__icon::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Images Size', 'courto-core'),
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'courto-core'),
                    '300' => esc_html__('300x300 - Medium', 'courto-core'),
                    '768' => esc_html__('768x768 - Medium Large', 'courto-core'),
                    '1024' => esc_html__('1024x1024', 'courto-core'),
                    '800' => esc_html__('800x800', 'courto-core'),
                    '680x740' => esc_html__('680x740', 'courto-core'),  // ratio = 1
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => '680x740',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'courto-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => ['img_size_string' => 'custom'],
                'description' => esc_html__('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'courto-core'),
                'default' => [
                    'width' => '700',
                    'height' => '820',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'options' => [
                    '' => esc_html__('No Crop', 'courto-core'),
                    '1:1' => esc_html('1:1'),
                    '3:2' => esc_html('3:2'),
                    '4:3' => esc_html('4:3'),
                    '6:5' => esc_html('6:5'),
                    '9:16' => esc_html('9:16'),
                    '16:9' => esc_html('16:9'),
                    '21:9' => esc_html('21:9'),
                ],
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('image');

        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'image_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'selector' => '{{WRAPPER}} .item_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .item_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .item_image::before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'image_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item_image:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}} .item_image:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .item_image:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .item_image::after',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> TEXT
         */

        $this->start_controls_section(
            'style_info',
            [
                'label' => esc_html__('Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_info',
                'selector' => '{{WRAPPER}} .item__info',
            ]
        );

        $this->add_control(
            'info_tag',
            [
                'label' => esc_html__('Text tag', 'courto-core'),
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

        $this->add_control(
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
                'prefix_class' => 'a',
                'default' => 'left',
            ]
        );

        $this->add_responsive_control(
            'info_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'default' => [
				    'top' => '22',
				    'right' => '32',
				    'bottom' => '32',
				    'left' => '32',
				    'unit' => 'px',
				    'isLinked' => false
			    ],
                'tablet_default' => [
		            'top' => '22',
		            'right' => '32',
		            'bottom' => '32',
		            'left' => '32',
		            'unit' => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .item__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'info_border',
                'selector' => '{{WRAPPER}} .item__info',
            ]
        );


        $this->add_responsive_control(
            'info_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .item__info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'info_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__info' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'info_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => '#f8f8f8',
                'selectors' => [
                    '{{WRAPPER}} .item__info' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * STYLE -> Likes
         */

        $this->start_controls_section(
            'style_likes',
            [
                'label' => esc_html__('Likes', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_likes',
                'selector' => '{{WRAPPER}} .likes_count',
            ]
        );

        $this->add_control(
            'icon_likes_enabled',
            [
                'label' => esc_html__('Icon Enabled', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'likes_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .likes_count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'likes_color',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .likes_count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        extract($this->get_settings_for_display());

        switch ($posts_per_row) {
            case '1':
                $col = 12;
                break;
            case '2':
                $col = 6;
                break;
            case '3':
                $col = 4;
                break;
            case '4':
                $col = 3;
                break;
            case '5':
                $col = '1-5';
                break;
        }

        // Wrapper attributes
        $this->add_render_attribute('wrapper', 'class', [
            'wgl-instagram',
        ]);

        // Get Author Thumbnail
        $author_image_size = $author_thumnail_size['size'] ?? '';
        $author_image_height = $author_thumnail_size['size'] ?? '';
        $author_image_width = $author_image_size ? 'width: ' . $author_image_size . 'px;' : '';
        $author_thumbnail_style = $author_image_width ? ' style="' . $author_image_width . '"' : '';

        // Get Image Post
        $img_size_string = $img_size_string ?? '';
        $img_size_array = $img_size_array ?? [];
        $img_aspect_ratio = $img_aspect_ratio ?? '';

        // Build structure
        $items_html =  '';

        if(!$use_carousel){
            $items_html .= '<div class="row">';
        }

        foreach ($items as $index => $item) {
            // Fields validation
            $author_thumbnail = $item['author_thumbnail'] ?? '';
            $thumbnail = $item['thumbnail'] ?? '';
            $attachment = get_post($thumbnail['id']);
            $image_data = wp_get_attachment_image_src($thumbnail['id'], 'full');
            $quote = $item['info'] ?? '';
            $author_name = $item['author_name'] ?? '';
            $link_author = $item['link_author'] ?? '';
            $link_thumbnail = $item['link_thumbnail'] ?? '';
            $show_likes = $item['show_likes'] ?? '';

            $has_link = !empty($link_author['url']);
            $has_link_thumbnail = !empty($link_thumbnail['url']);

            //* Image size
            $dim = null;

            if ($image_data) {
                $dim = WGL_Elementor_Helper::get_image_dimensions(
                    $img_size_array ?: $img_size_string,
                    $img_aspect_ratio,
                    $image_data
                );
            }

            if ($has_link) {
                $link_author = $this->get_repeater_setting_key('link-author', 'items', $index);
                $this->add_link_attributes($link_author, $item['link_author']);
            }

            if ($has_link_thumbnail) {
                $link_thumbnail = $this->get_repeater_setting_key('link-thumbnail', 'items', $index);
                $this->add_link_attributes($link_thumbnail, $item['link_thumbnail']);
            }

            $name_html = '<' . esc_attr($name_tag) . ' class="author__name">'
                . ($has_link ? '<a ' . $this->get_render_attribute_string($link_author) . '>' : '')
                . esc_html($author_name)
                . ($has_link ? '</a>' : '')
                . '</' . esc_attr($name_tag) . '>';

            $icon = $icon_enabled ? '<div class="item__icon">'. ($has_link ? '<a ' . $this->get_render_attribute_string($link_author) . '></a>' : '').'</div>' : '';


            $content = (bool) $show_likes && !empty($item['likes_count']) ? '<div class="likes_count'.($icon_likes_enabled ? ' show_icon' : '').'">' . wp_kses($item['likes_count'], self::get_kses_allowed_html()) . '<i class="wgl-svg-icon">'.wgl_dynamic_styles()->wgl_theme_svg()['heart'].'</i>' . '</div>' : '';
            $content .= wp_kses($quote, self::get_kses_allowed_html());

            $info_html = '';
            if(!empty($content)){
                $info_html = '<' . esc_attr($info_tag) . ' class="item__info">';
                $info_html .= $content;
                $info_html .= '</' . esc_attr($info_tag) . '>';
            }

            $author_thumbnail_html = '';
            $author_image_src = aq_resize($author_thumbnail['url'], $author_image_size, $author_image_height, true, true, true);

            if (!empty($author_image_src)) {
                $author_thumbnail_html = '<div class="author__thumbnail">'
                    . ($has_link ? '<a ' . $this->get_render_attribute_string($link_author) . '>' : '')
                    . '<img src="' . esc_url($author_image_src) . '" alt="' . esc_attr($author_name) . '" ' . $author_thumbnail_style . '>'
                    . ($has_link ? '</a>' : '')
                    . '</div>';
            }

            $image_post_html = '';

            if($dim){
                $image_url = aq_resize($image_data[0], $dim['width'], $dim['height'], true, true, true) ?: $image_data[0];
                //* Image Attachment
                $image_arr = [
                    'image' => $image_data[0],
                    'src' => $image_url,
                    'alt' => get_post_meta($thumbnail['id'], '_wp_attachment_image_alt', true),
                    'title' => $attachment->post_title,
                ];

                $this->add_render_attribute('image' . $index, [
                    'class' => 'instagram_image',
                    'src' => $image_arr['src'],
                    'alt' => $image_arr['alt']
                ]);

                $image_post_html .= '<div class="item_image">';
                $image_post_html .= $has_link_thumbnail ? '<a ' . $this->get_render_attribute_string($link_thumbnail) . '>' : '';
                $image_post_html .= '<img '. $this->get_render_attribute_string('image' . $index). '>';
                $image_post_html .= $has_link_thumbnail ? '</a>' : '';
                $image_post_html .= '</div>';
            }

            $items_html .= '<div class="instagram__wrapper' . (!$use_carousel ? ' wgl_col-' . $col : ' swiper-slide') . '">';

            $items_html .= '<div class="instagram__item">'
                . '<div class="item__header">'
                . '<div class="item__author">'
                . $author_thumbnail_html
                . '<div class="author__meta">'
                . $name_html
                . '</div>'
                . '</div>'
                . $icon
                . '</div>'
                . '<div class="item__content">'
                . $image_post_html
                . $info_html
                . '</div>'
                . '</div>';

            $items_html .= '</div>';

        }

        if(!$use_carousel){
            $items_html .= '</div>';
        }

        echo '<div  ', $this->get_render_attribute_string('wrapper'), '>',
            (!$use_carousel ? $items_html : $this->apply_carousel_settings($items_html)),
        '</div>';
    }


    protected function apply_carousel_settings($instagram_html)
    {
        $_s = $this->get_settings_for_display();
        $_s['slides_per_row'] = $_s['posts_per_row'];

        return WGL_Carousel_Settings::init($_s, $instagram_html);
    }

    protected static function get_kses_allowed_html()
    {
        return [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
        ];
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
