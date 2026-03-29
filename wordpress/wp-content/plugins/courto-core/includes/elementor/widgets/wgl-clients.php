<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-clients.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Background,
    Repeater,
    Utils
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Carousel_Settings
};

class WGL_Clients extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-clients';
    }

    public function get_title()
    {
        return esc_html__('WGL Clients', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-clients';
    }

    public function get_keywords()
    {
        return [ 'clients', 'partners', 'carousel', 'slider', 'image' ];
    }

    public function get_script_depends()
    {
        return ['wgl-widgets', 'swiper'];
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
            'section_content_general',
            ['label' => esc_html__('General', 'courto-core')]
        );

        $this->add_responsive_control(
            'item_grid',
            [
                'label' => esc_html__('Grid Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'condition' => [ 'use_carousel' => '' ],
                'options' => [
                    1 => esc_html__('1 (one)', 'courto-core'),
                    2 => esc_html__('2 (two)', 'courto-core'),
                    3 => esc_html__('3 (three)', 'courto-core'),
                    4 => esc_html__('4 (four)', 'courto-core'),
                    5 => esc_html__('5 (five)', 'courto-core'),
                    6 => esc_html__('6 (six)', 'courto-core'),
                    7 => esc_html__('7 (seven)', 'courto-core'),
                    8 => esc_html__('8 (eight)', 'courto-core'),
                    9 => esc_html__('9 (nine)', 'courto-core'),
                    10 => esc_html__('10 (ten)', 'courto-core'),
                ],
                'default' => 6,
                'selectors' => [
                    '{{WRAPPER}} .wgl-clients.carousel-false' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        $this->add_control(
            'items_carousel',
            [
                'label' => esc_html__('Carousel Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'condition' => [ 'use_carousel!' => '' ],
                'options' => [
                    1 => esc_html__('1 (one)', 'courto-core'),
                    2 => esc_html__('2 (two)', 'courto-core'),
                    3 => esc_html__('3 (three)', 'courto-core'),
                    4 => esc_html__('4 (four)', 'courto-core'),
                    5 => esc_html__('5 (five)', 'courto-core'),
                    6 => esc_html__('6 (six)', 'courto-core'),
                    7 => esc_html__('7 (seven)', 'courto-core'),
                    8 => esc_html__('8 (eight)', 'courto-core'),
                    9 => esc_html__('9 (nine)', 'courto-core'),
                    10 => esc_html__('10 (ten)', 'courto-core'),
                ],
                'default' => 6,
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
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'hover_thumbnail',
            [
                'label' => esc_html__('Hover Thumbnail', 'courto-core'),
                'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
                'label_block' => true,
                'description' => esc_html__('For \'Toggle Image\' animations only.', 'courto-core' ),
                'default' => ['url' => ''],
            ]
        );

	    $repeater->add_responsive_control(
		    'thumbnail_width',
		    [
			    'label' => esc_html__('Image/Images Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}.clients_item img:not(.lazyload),
		             {{WRAPPER}} {{CURRENT_ITEM}}.clients_item img.lazyloaded' => 'width: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}}.link_on-wrapper {{CURRENT_ITEM}}.clients_item .mask_image' => '-webkit-mask-size: {{SIZE}}{{UNIT}};',
			    ],
			    'label_block' => true,
		    ]
	    );

	    $repeater->add_responsive_control(
		    'item_wrapper_width',
		    [
			    'label' => esc_html__('Wrapper Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
                'label_block' => true,
			    'dynamic' => ['active' => true],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} .clients_image' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
        $repeater->add_responsive_control(
            'item_visibility',
            [
                'label' => esc_html__('Item Visibility', 'courto-core'),
                'description' => esc_html__('You can hide some items on responsive', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'courto-core'),
                    'display: block' => esc_html__('Show', 'courto-core'),
                    'display: none' => esc_html__('Hide', 'courto-core'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}};',
                ],
            ]
        );

        $repeater->add_control(
            'client_link',
            [
                'label' => esc_html__('Add Link', 'courto-core'),
                'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                    ['thumbnail' => ['url' => Utils::get_placeholder_image_src()]],
                ],
            ]
        );

        $this->add_responsive_control(
            'items_wrapper_width',
            [
                'label' => esc_html__('Items Wrapper Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 10, 'max' => 500 ],
                    '%' => ['min' => 10, 'max' => 100 ],
                ],
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_min_height',
            [
                'label' => esc_html__('Items Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 600],
                ],
                'default' => ['size' => 114],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_link',
            [
                'label' => esc_html__('Set Link on', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'image' => esc_html__('Image', 'courto-core'),
                    'wrapper' => esc_html__('Wrapper', 'courto-core'),
                ],
                'default' => 'wrapper',
                'prefix_class' => 'link_on-',
            ]
        );

        $this->add_control(
            'item_anim',
            [
                'label' => esc_html__('Thumbnail Animation', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'none' => esc_html__('None', 'courto-core'),
                    'ex_images' => esc_html__('Toggle Image - Fade', 'courto-core'),
                    'ex_images_ver' => esc_html__('Toggle Image - Vertical', 'courto-core'),
                    'grayscale' => esc_html__('Grayscale', 'courto-core'),
                    'opacity' => esc_html__('Opacity', 'courto-core'),
                    'zoom' => esc_html__('Zoom', 'courto-core'),
                    'contrast' => esc_html__('Contrast', 'courto-core'),
                    'blur-1' => esc_html__('Blur 1', 'courto-core'),
                    'blur-2' => esc_html__('Blur 2', 'courto-core'),
                    'invert' => esc_html__('Invert', 'courto-core'),
                    'mask_image' => esc_html__('Mask Image', 'courto-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'alignment_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'flex-start; -webkit-mask-position-x: left' => [
                        'title' => esc_html__('Left', 'courto-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center; -webkit-mask-position-x: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end; -webkit-mask-position-x: right' => [
                        'title' => esc_html__('Right', 'courto-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center; -webkit-mask-position-x: center',
                'selectors' => [
                    '{{WRAPPER}}.link_on-image .clients_image' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}}.link_on-wrapper .image_wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_v',
            [
                'label' => esc_html__('Vertical Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'flex-start; -webkit-mask-position-y: top' => [
                        'title' => esc_html__('Top', 'courto-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center; -webkit-mask-position-y: center' => [
                        'title' => esc_html__('Center', 'courto-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end; -webkit-mask-position-y: bottom' => [
                        'title' => esc_html__('Bottom', 'courto-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center; -webkit-mask-position-y: center',
                'selectors' => [
                    '{{WRAPPER}}.link_on-image .clients_image' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}}.link_on-wrapper .image_wrapper' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .wgl-clients .swiper-wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        WGL_Carousel_Settings::add_controls($this, [
            'use_carousel' => [ 'default' => 'yes' ],
            'slide_per_single' => [ 'default' => 'yes' ],
            'slider_infinite' => [ 'default' => 'yes' ],
            'slider_alignment_v' => [ 'default' => 'center'],
            'center_mode' => [ 'default' => '' ],
            'variable_width' => [ 'default' => '' ],
            'variable_width_height' => [
                'wrapper' => '.image_wrapper img',
            ],
        ]);

        /**
         * STYLES -> ITEMS
         */

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__('Items', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Items Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [ 'px' => ['min' => 0, 'max' => 100] ],
                'size_units' => ['px', 'vw'],
                'default' => ['size' => 30, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .clients_item' => '--wgl-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .clients_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'render_type' => 'template',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );
        $this->start_controls_tabs('tabs_items');

        $this->start_controls_tab(
            'tab_item_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .clients_image::before',
            ]
        );
        $this->add_control(
            'image_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_border_radius_idle',
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
                    'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_idle',
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_item_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_hover',
                'types' => ['classic', 'gradient'],
                'fields_options' => [
                    'background' => [ 'default' => 'classic' ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'courto-core' ),
                        'default' => WGL_Globals::get_primary_color(),
                    ],
                ],
                'selector' => '{{WRAPPER}} .clients_image::after',
            ]
        );
        $this->add_control(
            'image_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'image_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_hover',
                'selector' => '{{WRAPPER}} .clients_image:hover',
            ]
        );

        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['s'],
                'range' => [
                    's' => ['min' => 0, 'max' => 2, 'step' => 0.1 ],
                ],
                'default' => ['size' => 0.4, 'unit' => 's'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLES -> IMAGES
         */

        $this->start_controls_section(
            'section_style_images',
            [
                'label' => esc_html__('Images', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('images');

        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'image_mask_color_idle',
            [
                'label' => esc_html__('Change Color for Image', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'condition' => ['item_anim' => 'mask_image'],
                'selectors' => [
                    '{{WRAPPER}} .image_wrapper.mask_image' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .image_wrapper.mask_image img' => 'visibility: hidden !important',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .clients_image img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'selector' => '{{WRAPPER}} .clients_image img',
            ]
        );

        $this->add_control(
            'item_anim_opacity_idle',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['item_anim' => 'opacity'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'image_mask_color_hover',
            [
                'label' => esc_html__('Change Color for Image', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['item_anim' => 'mask_image'],
                'selectors' => [
                    '{{WRAPPER}} .image_wrapper.mask_image:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}}.link_on-wrapper .clients_image:hover img, {{WRAPPER}}.link_on-image .image_wrapper:hover img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}}.link_on-wrapper .clients_image:hover img, {{WRAPPER}}.link_on-image .image_wrapper:hover img',
            ]
        );

        $this->add_control(
            'item_anim_opacity_hover',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => ['item_anim' => 'opacity'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.link_on-wrapper .clients_image:hover img,
                     {{WRAPPER}}.link_on-image .image_wrapper:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        extract($this->get_settings_for_display());

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => [
                    'wgl-clients',
                    'anim-' . $item_anim,
                    !!$use_carousel ? 'carousel-true' : 'carousel-false',
                    !!$items_carousel ? 'items-' . $items_carousel : '',
                ],
                'data-carousel' => $use_carousel
            ]
        );

        // Render
        echo '<div ', $this->get_render_attribute_string('wrapper'), '>',
            $this->get_clients_html(),
        '</div>';
    }

    protected function get_clients_html()
    {
        extract($this->get_settings_for_display());

        $has_mask = $item_anim === 'mask_image' && !empty($image_mask_color_idle);
        $content = '';
        foreach ($list as $index => $item) {

            $has_link = !empty($item['client_link']['url']);

            $client_link = $this->get_repeater_setting_key('client_link', 'list', $index);
            $this->add_render_attribute($client_link, 'class', 'image_wrapper');

            if ($has_link) {
                $this->add_render_attribute($client_link, 'class', 'image_link');
                $this->add_link_attributes($client_link, $item['client_link']);
            }

            $client_image_idle = $this->get_repeater_setting_key('thumbnail', 'list', $index);
            $this->add_render_attribute($client_image_idle, [
                'class' => 'main_image',
                'alt' => Control_Media::get_image_alt($item['thumbnail']),
            ]);
            $url_idle = $item['thumbnail']['url'] ?? false;
            if ($url_idle) {
                $this->add_render_attribute($client_image_idle, 'src', esc_url($url_idle));
            }

            if ($has_mask) {
                $this->add_render_attribute($client_link, 'class', 'mask_image');
                $this->add_render_attribute($client_link, 'style', '-webkit-mask-image: url('.esc_url($url_idle).');');
            }

            $client_image_hover = $this->get_repeater_setting_key('hover_thumbnail', 'list', $index);
            $this->add_render_attribute($client_image_hover, [
                'class' => 'hover_image',
                'alt' => Control_Media::get_image_alt($item['hover_thumbnail']),
            ]);
            $url_hover = $item['hover_thumbnail']['url'] ?? false;
            if ($url_hover) {
                $this->add_render_attribute($client_image_hover, 'src', esc_url($url_hover));
            }

            ob_start();

            echo '<div class="clients_item elementor-repeater-item-'. $item['_id'] . (($use_carousel) ? ' swiper-slide' : '') .'">';
                echo '<div class="clients_image">';

                    echo $has_link
                        ? '<a ' . $this->get_render_attribute_string($client_link) . '>'
                        : '<div ' . $this->get_render_attribute_string($client_link) . '>';

                        if (
                            $url_hover
                            && ($item_anim == 'ex_images' || $item_anim == 'ex_images_ver')
                        ) {
                            echo '<img ', $this->get_render_attribute_string($client_image_hover), ' />';
                        }

                        echo '<img ', $this->get_render_attribute_string($client_image_idle), ' />';

                    echo $has_link
                        ? '</a>'
                        : '</div>';

                echo '</div>';
            echo '</div>';

            $content .= ob_get_clean();
        }

        return !$use_carousel ? $content : $this->apply_carousel_settings($content);
    }

    protected function apply_carousel_settings($content)
    {
        $_s = $this->get_settings_for_display();
        $_s['slides_per_row'] = $_s['items_carousel'] ?: 6;

        return WGL_Carousel_Settings::init($_s, $content);
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
