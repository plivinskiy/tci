<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-team.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Css_Filter,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGL_Team as Team_Template,
    Includes\WGL_Cursor
};

class WGL_Team extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-team';
    }

    public function get_title()
    {
        return esc_html__('WGL Team', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-team';
    }

    public function get_keywords() {
        return ['team'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['wgl-widgets', 'swiper'];
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

        $this->add_responsive_control(
            'posts_per_row',
            [
                'label' => esc_html__('Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                    '6' => esc_html__('6 (six)', 'courto-core'),
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .wgl_module_team' => '--team-width: calc(100% / {{VALUE}});',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'col_gap',
            [
                'label' => esc_html__('Column Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl_module_team' => '--team-col-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__('Row Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl_module_team' => '--team-row-gap: {{SIZE}}px;',
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
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid_under_content.png',
                    ],
                    'list' => [
                        'title' => esc_html__('List', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_list.png',
                    ],
                    'hero' => [
                        'title' => esc_html__('Hero', 'courto-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                ],
                'default' => 'hero',
                'separator' => 'before',
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
                    '1024' => esc_html__('1024x1024 - 1 Column', 'courto-core'),
                    '540x620' => esc_html__('540x620 - 2 Columns', 'courto-core'),
                    '740x940' => esc_html__('740x940 - 3 Columns', 'courto-core'),
                    '620x790' => esc_html__('620x790 - 4 Columns', 'courto-core'),
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => '740x940',
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
                    'width' => '740',
                    'height' => '940',
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

        $this->add_control(
            'thumbnail_linked',
            [
                'label' => esc_html__('Add Link on Image', 'courto-core'),
                'separator' => 'before',
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'heading_linked',
            [
                'label' => esc_html__('Add Link on Heading', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
            'hide_title',
            [
                'label' => esc_html__('Hide Title', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_highlited_info',
            [
                'label' => esc_html__('Hide Highlighted Info', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_socials',
            [
                'label' => esc_html__('Hide Social Icons', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_content',
            [
                'label' => esc_html__('Hide Excerpt|Content', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_limit',
            [
                'label' => esc_html__('Excerpt|Content Characters Amount', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'condition' => ['hide_content!' => 'yes'],
                'label_block' => true,
                'min' => 5,
                'default' => '100',
            ]
        );

        WGL_Cursor::init(
            $this,
            [
                'section' => false,
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

	    WGL_Carousel_Settings::add_controls($this,[
            'pagination_margin' => [
                'default' => [
                    'top' => '30',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
            ],
            'variable_width_height' => [
                'wrapper' => '.member__thumbnail img',
            ],
        ]);

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls(
            $this,
            [
                'post_type' => 'team',
                'hide_cats' => true,
                'hide_tags' => true
            ]
        );

        /**
         * STYLE -> ITEM CONTAINERS
         */

        $this->start_controls_section(
            'style_item_containers',
            [
                'label' => esc_html__('Item Containers', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_box_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .wgl_module_team .team__member' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'item_box_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_box_border',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top'    => 0,
                            'right'  => 0,
                            'bottom' => 0,
                            'left'   => 0,
                        ],
                    ],
                    'color' => ['type' => Controls_Manager::HIDDEN],
                ],
                'selector' => '{{WRAPPER}} .member__wrapper',
            ]
        );

        $this->add_control(
            'item_box_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'item_box',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'item_box_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_idle',
                'selector' => '{{WRAPPER}} .member__wrapper',
            ]
        );

        $this->add_control(
            'item_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'item_box_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_box_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_box_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_hover',
                'selector' => '{{WRAPPER}} .member__wrapper:hover',
            ]
        );

        $this->add_control(
            'item_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'item_box_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> INFO
         */

        $this->start_controls_section(
            'style_info',
            [
                'label' => esc_html__('Info', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_info_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .member__info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'item_info_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_info_border',
                'render_type' => 'template',
			    'dynamic' => ['active' => true],
                'fields_options' => [
                    'border' => [ 'default' => '' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 1,
                            'right' => 1,
                            'bottom' => 1,
                            'left' => 1,
                        ],
                    ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ]
                ],
                'selector' => '{{WRAPPER}} .member__info',
            ]
        );

        $this->add_responsive_control(
		    'item_info_border_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_min_height',
            [
                'label' => esc_html__('Min Height', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', '%', 'vw', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 1000],
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__info' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'default' => ['size' => 110],
                'tablet_default' => ['size' => 110],
                'mobile_default' => ['size' => 0],
            ]
        );

        $this->start_controls_tabs('overlay_info');

        $this->start_controls_tab(
            'overlay_info_tab_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_info_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__info',
            ]
        );

        $this->add_control(
            'overlay_info_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'item_info_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper .member__info' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'overlay_info_shadow_idle',
                'selector' => '{{WRAPPER}} .member__wrapper .member__info',
            ]
        );

        $this->add_responsive_control(
            'overlay_info__backdrop_filter_idle',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__info' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'overlay_info_tab_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_info_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper:hover .member__info',
            ]
        );

        $this->add_control(
            'overlay_info_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [
                    'item_info_border_border!' => ['', 'none']
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover .member__info' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'overlay_info_shadow_hover',
                'selector' => '{{WRAPPER}} .member__wrapper:hover .member__info',
            ]
        );

        $this->add_responsive_control(
            'overlay_info__backdrop_filter_hover',
            [
                'label' => esc_html__('Backdrop Filter', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 100, 'step' => 0.5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover .member__info' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);',
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
            'style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_list_size',
            [
                'label' => esc_html__( 'Image Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'layout' => 'list' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 100, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 50.0 ],
                'selectors' => [
                    '{{WRAPPER}} .team__members.display-list .team__member > .member__wrapper .member__media' => 'flex:0 0 {{SIZE}}%;',
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
                    '{{WRAPPER}} .member__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .member__thumbnail,
                     {{WRAPPER}} .member__thumbnail img,
                     {{WRAPPER}} .member__thumbnail::before,
                     {{WRAPPER}} .member__thumbnail::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_scale_animation',
            [
                'label' => esc_html__( 'Image Hover Scale Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_responsive_control(
            'image_scale_size',
            [
                'label' => esc_html__( 'Image Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'image_scale_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail' => '--wgl-team-image-scale-size: {{SIZE}};',
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
                'default' => ['size' => 0.6],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail' => '--wgl-team-image-transition: {{SIZE}}s',
                ],
            ]
        );

        $this->add_control(
            'wrapper_image_scale_animation',
            [
                'label' => esc_html__( 'Wrapper Hover Scale Animation', 'courto-core' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'wrapper_image_scale_size',
            [
                'label' => esc_html__( 'Wrapper Scale Size', 'courto-core' ),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'wrapper_image_scale_animation!' => '' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.01 ],
                ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .member__media' => '--wgl-team-wrapper-image-scale-size: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_image_transition',
            [
                'label' => esc_html__('Wrapper Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'condition' => [ 'wrapper_image_scale_animation!' => '' ],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'default' => ['size' => 0.5],
                'selectors' => [
                    '{{WRAPPER}} .member__media' => '--wgl-team-wrapper-image-transition: {{SIZE}}s',
                ],
            ]
        );

        $this->start_controls_tabs('overlay');

        $this->start_controls_tab(
            'overlay_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__thumbnail::before',
            ]
        );

        $this->add_control(
            'overlay_blend_idle',
            [
                'label' => esc_html__('Blend Mode', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disabled', 'courto-core'),
                    'multiply' => esc_html__('Multiply', 'courto-core'),
                    'screen' => esc_html__('Screen', 'courto-core'),
                    'overlay' => esc_html__('Overlay', 'courto-core'),
                    'darken' => esc_html__('Darken', 'courto-core'),
                    'lighten' => esc_html__('Lighten', 'courto-core'),
                    'color-dodge' => esc_html__('Color Dodge', 'courto-core'),
                    'saturation' => esc_html__('Saturation', 'courto-core'),
                    'color' => esc_html__('Color', 'courto-core'),
                    'difference' => esc_html__('Difference', 'courto-core'),
                    'exclusion' => esc_html__('Exclusion', 'courto-core'),
                    'hue' => esc_html__('Hue', 'courto-core'),
                    'luminosity' => esc_html__('Luminosity', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail::before' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_notice_idle',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'overlay_blend_idle!' => '',
                    'overlay_idle_color' => ''
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Blend Mode affects only overlay color|image. Please choose one.', 'courto-core' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'overlay_idle',
                'selector' => '{{WRAPPER}} .member__thumbnail img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'overlay_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__thumbnail::after',
            ]
        );

        $this->add_control(
            'overlay_blend_hover',
            [
                'label' => esc_html__('Blend Mode', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disabled', 'courto-core'),
                    'multiply' => esc_html__('Multiply', 'courto-core'),
                    'screen' => esc_html__('Screen', 'courto-core'),
                    'overlay' => esc_html__('Overlay', 'courto-core'),
                    'darken' => esc_html__('Darken', 'courto-core'),
                    'lighten' => esc_html__('Lighten', 'courto-core'),
                    'color-dodge' => esc_html__('Color Dodge', 'courto-core'),
                    'saturation' => esc_html__('Saturation', 'courto-core'),
                    'color' => esc_html__('Color', 'courto-core'),
                    'difference' => esc_html__('Difference', 'courto-core'),
                    'exclusion' => esc_html__('Exclusion', 'courto-core'),
                    'hue' => esc_html__('Hue', 'courto-core'),
                    'luminosity' => esc_html__('Luminosity', 'courto-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail::after' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_notice_hover',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'overlay_blend_hover!' => '',
                    'overlay_hover_color' => ''
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Blend Mode affects only overlay color|image. Please choose one.', 'courto-core' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'overlay_hover',
                'selector' => '{{WRAPPER}} .member__wrapper:hover .member__thumbnail img',
            ]
        );

        $this->add_control(
            'overlay_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail,
                     {{WRAPPER}} .member__thumbnail img,
                     {{WRAPPER}} .member__thumbnail::before,
                     {{WRAPPER}} .member__thumbnail::after' => 'transition-duration: {{SIZE}}s;',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .member__name',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .member__name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .member__name',
            ]
        );

        $this->start_controls_tabs(
            'tabs_title',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__name' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__name a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> HIGHLIGHTED INFO
         */

        $this->start_controls_section(
            'style_highlighted_info',
            [
                'label' => esc_html__('Highlighted Info', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta',
                'selector' => '{{WRAPPER}} .member__highlighted',
            ]
        );

        $this->add_responsive_control(
            'highlighted_meta_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__highlighted' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'highlighted_meta_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__highlighted' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_highlighted',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_highlighted_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'highlighted_idle',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__highlighted' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'highlighted_bg_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__highlighted',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_highlighted_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'highlighted_hover',
            [
                'label' => esc_html__('Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover .member__highlighted' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'highlighted_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper:hover .member__highlighted',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'highlighted_meta_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .member__highlighted',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> SOCIALS
         */

        $this->start_controls_section(
            'style_socials',
            [
                'label' => esc_html__('Socials', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'socials_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'socials_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'socials_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'socials',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'socials_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'socials_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:before' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'socials_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'socials_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'socials_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:hover:before,
                     {{WRAPPER}} .team__member .social__icon::after' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'socials_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:hover:before' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> EXCERPT | CONTENT
         */

        $this->start_controls_section(
            'style_excerpt',
            [
                'label' => esc_html__('Excerpt | Content', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['hide_content' => ''],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt',
                'selector' => '{{WRAPPER}} .member__excerpt',
            ]
        );

	    $this->start_controls_tabs(
            'tabs_excerpt',
            ['separator' => 'before']
        );

	    $this->start_controls_tab(
            'tab_excerpt_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'excerpt_color_idle',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_excerpt_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'excerpt_color_hover',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover .member__excerpt' => 'color: {{VALUE}}',
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

        (new Team_Template())->render($this, $atts);
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
