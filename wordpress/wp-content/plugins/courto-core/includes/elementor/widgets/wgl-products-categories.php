<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-products-categories.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{Group_Control_Border,
    Plugin,
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Box_Shadow};
use WGL_Extensions\{
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Elementor_Helper,
    WGL_Framework_Global_Variables as WGL_Globals
};

class WGL_Products_Categories extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-products-categories';
    }

    public function get_keywords() {
        return ['products', 'category', 'categories', 'shop', 'woocommerce'];
    }

    public function get_title()
    {
        return esc_html__('WGL Products Categories', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-products-categories';
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
        return ['jquery-appear', 'swiper'];
    }

    public static function get_term_parents_list( $term_id, $taxonomy, $args = array() ) {
        $list = '';
        $term = get_term( $term_id, $taxonomy );

        if ( is_wp_error( $term ) ) {
            return $term;
        }

        if ( ! $term ) {
            return $list;
        }

        $term_id = $term->term_id;

        $defaults = array(
                'format'    => 'name',
                'separator' => '/',
                'inclusive' => true,
        );

        $args = wp_parse_args( $args, $defaults );

        foreach ( array(  'inclusive' ) as $bool ) {
            $args[ $bool ] = wp_validate_boolean( $args[ $bool ] );
        }

        $parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );

        if ( $args['inclusive'] ) {
            array_unshift( $parents, $term_id );
        }

        $a = count($parents) - 1;
        foreach ( array_reverse( $parents ) as $index => $term_id ) {
            $parent = get_term( $term_id, $taxonomy );
            $temp_sep = $args['separator'];
            $lastElement = reset($parents);
            $first = end($parents);

            if($index == $a - 1){
                $temp_sep = '';
            }
            if( $term_id != $lastElement){
                $name   = $parent->name;
                $list .= $name . $temp_sep;
            }
        }

        return $list;
    }

    public static function categories_suggester() {
        $content = array();

        $categories = get_terms( 'product_cat' );
        foreach ( $categories as $cat ) {
            $args = array(
              'separator' => ' > ',
              'format'    => 'name',
            );

            $parent = self::get_term_parents_list( $cat->term_id, 'product_cat', array());

            $content[(string) $cat->slug] = $cat->name.(!empty($parent) ? esc_html__(' (Parent categories: (', 'courto-core') .$parent.'))' : "");
        }
        return $content;
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

        $this->add_control(
            'products_layout',
            [
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
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__('Columns', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'devices' => ['widescreen', 'desktop', 'tablet', 'mobile'],
                'min' => 1,
                'max' => 6,
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 2,
                'selectors' => [
                    '{{WRAPPER}} .wgl-products-categories' => '--columns: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'categories_gap',
            [
                'label' => esc_html__('Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'render_type' => 'template',
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'default' => [ 'size' => '30' ],
                'tablet_default' => ['size' => '30'],
                'mobile_default' => ['size' => '20'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products-categories' => '--categories-gap: {{SIZE}}{{UNIT}}',
                ],
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
                    '560' => esc_html__('560x560 - Default', 'courto-core'),
                    'full' => esc_html__('Full', 'courto-core'),
                    'custom' => esc_html__('Custom', 'courto-core'),
                ],
                'default' => '560',
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
                'default' => '1:1',
            ]
        );

		$this->add_control(
			'products_categories',
			[
				'label' => esc_html__( 'Categories', 'courto-core' ),
				'type' => Controls_Manager::SELECT2,
				'options' => self::categories_suggester(),
				'default' => [],
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' => esc_html__( 'Exclude These Categories', 'courto-core' ),
				'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'courto-core' ),
                'label_off' => esc_html__( 'Off', 'courto-core' ),
                'return_value' => 'yes',
                'description' => esc_html__('Leave empty for all','courto-core'),
			]
		);

		$this->add_control(
			'show_count',
			[
				'label' => esc_html__( 'Show Count', 'courto-core' ),
				'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'courto-core' ),
                'label_off' => esc_html__( 'Off', 'courto-core' ),
                'return_value' => 'yes',
                'default' => 'yes',
			]
		);

        $this->add_control(
            'title_position',
            [
                'label' => esc_html__('Position of Title and Count', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'under_image' => esc_html__('Beneath Image', 'courto-core'),
                    'cursor_tooltip' => esc_html__('Cursor Tooltip', 'courto-core'),
                ],
                'default' => 'under_image',
            ]
        );

		$this->add_control(
			'orderby_categories',
			[
				'label' => esc_html__( 'Order By', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => esc_html__( 'Name', 'courto-core' ),
					'slug' => esc_html__( 'Slug', 'courto-core' ),
					'description' => esc_html__( 'Description', 'courto-core' ),
					'count' => esc_html__( 'Count', 'courto-core' ),
				],
			]
		);

		$this->add_control(
			'order_categories',
			[
				'label' => esc_html__( 'Order', 'courto-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => esc_html__( 'ASC', 'courto-core' ),
					'desc' => esc_html__( 'DESC', 'courto-core' ),
				],
			]
		);

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        $this->start_controls_section(
            'section_content_carousel',
            [
                'label' => esc_html__('Carousel Options', 'courto-core'),
                'condition' => ['products_layout' => 'carousel']
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this, [
            'slide_per_single' => [ 'default' => 'yes' ],
            'slider_infinite' => [ 'default' => 'yes' ],
        ]);

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination!' => ''],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this);

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

        WGL_Carousel_Settings::add_navigation_controls($this, []);

        $this->add_control(
            'navigation_responsive_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination!' => ''],
            ]
        );
        WGL_Carousel_Settings::add_responsive_controls($this);
        $this->end_controls_section();

	    /**
	     * STYLE -> GENERAL
	     */

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .cats_item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Item Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'allowed_dimensions' => 'vertical',
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .cats_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Item Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_item');
        $this->start_controls_tab(
            'tab_item_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'item_bg_color_idle',
            [
                'label' => esc_html__('Item Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_idle',
                'selector' => '{{WRAPPER}} .cats_item-wrapper',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_item_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'item_bg_color_hover',
            [
                'label' => esc_html__('Item Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'selector' => '{{WRAPPER}} .cats_item-wrapper:hover',
            ]
        );
	    $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'image_width',
		    [
			    'label' => esc_html__('Image Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 50, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
                'default' => ['size' => 185, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .cats_item-image' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_margin',
		    [
			    'label' => esc_html__( 'Margin', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
                'condition' => [ 'image_position!' => 'absolute' ],
			    'selectors' => [
				    '{{WRAPPER}} .cats_item-wrapper .cats_item-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'image_padding',
		    [
			    'label' => esc_html__( 'Padding', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'selectors' => [
				    '{{WRAPPER}} .cats_item-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .cats_item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}}  .cats_item-image',
            ]
        );

        $this->add_control(
            'image_z_index',
            [
                'label' => esc_html__('Image z-index', 'courto-core'),
                'type' => Controls_Manager::NUMBER,
                'dynamic' => ['active' => true],
                'step' => 1,
                'default' => '-1',
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label' => esc_html__('Image Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'relative' => esc_html__('Default', 'courto-core'),
                    'absolute' => esc_html__('Absolute', 'courto-core'),
                    'static' => esc_html__('Hide', 'courto-core'),
                ],
                'selectors_dictionary' => [
                    'relative' => 'relative; opacity: 1',
                    'absolute' => 'absolute; top: 0; right: 0; bottom: 0; left: 0; opacity: var(--wgl-opacity); width: fit-content; height: fit-content;',
                    'static' => 'static; display: none',
                ],
                'default' => 'relative',
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} div.cats_item-media' => 'position: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_alignment',
            [
                'label' => esc_html__('Position', 'courto-core'),
                'type' => Controls_Manager::SELECT,
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
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => 'margin: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_image' );
        $this->start_controls_tab(
            'tab_image_idle',
            [ 'label' => esc_html__('Idle', 'courto-core') ]
        );
        $this->add_control(
            'image_bg_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-image' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .cats_item-image' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .cats_item-image',
            ]
        );

        $this->add_responsive_control(
            'image_position_horizontal_idle',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', '%', 'custom'],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => [ 'min' => 100, 'max' => 100 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_position_vertical_idle',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom'],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => [ 'min' => 100, 'max' => 100 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => '--pos-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_opacity_idle',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => '--wgl-opacity: {{SIZE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_image_hover',
            [ 'label' => esc_html__('Hover', 'courto-core') ]
        );
        $this->add_control(
            'image_bg_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item:hover .cats_item-image' => 'background-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .cats_item:hover .cats_item-image' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_border_offset_hover',
            [
                'label' => esc_html__('Border Offset', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 70, 'step' => 1],
                ],
                'condition' => [ 'image_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .cats_item:hover .tlv__media::before' => '--border-offset: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .cats_item:hover .cats_item-image',
            ]
        );

        $this->add_responsive_control(
            'image_position_horizontal_hover',
            [
                'label' => esc_html__('Horizontal Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'size_units' => [ 'px', '%', 'custom'],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => [ 'min' => 100, 'max' => 100 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => '--pos-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_position_vertical_hover',
            [
                'label' => esc_html__('Vertical Position', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => [ 'px', '%', 'custom'],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200 ],
                    '%' => [ 'min' => 100, 'max' => 100 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 0],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-media' => '--pos-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_opacity_hover',
            [
                'label' => esc_html__('Opacity', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'condition' => [ 'image_position!' => 'relative' ],
                'default' => ['size' => 1, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .cats_item:hover .cats_item-media' => '--wgl-opacity: {{SIZE}};',
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
            'section_style_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .cats_item-title',
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
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title');

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-title' => 'color: {{VALUE}};',
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
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper:hover .cats_item-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> COUNT
         */

        $this->start_controls_section(
            'section_style_count',
            [
                'label' => esc_html__('Count', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_count!' => '']
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count',
                'selector' => '{{WRAPPER}} .cats_item-count',
            ]
        );

        $this->add_control(
            'count_tag',
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
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '3',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_count');

        $this->start_controls_tab(
            'tab_count_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'count_color_idle',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_count_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );

        $this->add_control(
            'count_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .cats_item-wrapper:hover .cats_item-count' => 'color: {{VALUE}};',
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
            'style_tooltip',
            [
                'label' => esc_html__('Cursor Tooltip', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['title_position' => 'cursor_tooltip'],
            ]
        );
        $this->start_controls_tabs('tabs_cursor');
        $this->start_controls_tab(
            'tabs_cursor_title',
            ['label' => esc_html__('Title', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_title',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6',
            ]
        );
        $this->add_responsive_control(
            'tooltip_title_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tooltip_title_alignment',
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
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'tooltip_title_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tooltip_title_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tooltip_title_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6',
            ]
        );
        $this->add_control(
            'tooltip_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_h_font_color(),
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'tooltip_title_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip h6' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tabs_cursor_count',
            ['label' => esc_html__('Count', 'courto-core')]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tooltip_count',
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count',
            ]
        );
        $this->add_responsive_control(
            'tooltip_count_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 500 ],
                ],
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tooltip_count_alignment',
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
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'text-align: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'tooltip_count_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'tooltip_count_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tooltip_count_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'label' => esc_html__( 'Border Color', 'courto-core' ) ],
                ],
                'selector' => '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count',
            ]
        );
        $this->add_control(
            'tooltip_count_color',
            [
                'label' => esc_html__('Count Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'tooltip_count_bg',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '#wgl-cursor .wgl-element-{{ID}}.product-cat-tooltip .count' => 'background-color: {{VALUE}}',
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

        // Cursor
        if ('cursor_tooltip' === $_s['title_position']) {
            add_filter( 'wgl/courto_module_cursor', function () { return true; });

            $cursor_class = ' data-cursor-class="wgl-element-'.$this->get_id().' product-cat-tooltip"';
            $this->add_render_attribute( 'item',  ['class' =>  'wgl-cursor-text']  );
        }

        // Images
        $img_size_string = $_s['img_size_string'] ?? '';
        $img_size_array = $_s['img_size_array'] ?? [];
        $img_aspect_ratio = $_s['img_aspect_ratio'] ?? '';

        $this->add_render_attribute( 'wrapper', [
			'class' => [
                'wgl-products-categories',
                'carousel' == $_s['products_layout'] ? 'carousel-cats' : '',
            ],
        ] );

        $this->add_render_attribute( 'item', [
			'class' => [
                'cats_item',
                'carousel' === $_s['products_layout'] ? ' swiper-slide' : ''
            ],
        ] );

        $count_render = $count_data = $cursor_data = '';
        $slug = $cat_id = [];
        if (empty($_s['products_categories'])) {
            $slug = [];
        } else {
            foreach( $_s['products_categories'] as $cat ) {
                $slug[] = $cat;
                $category = get_term_by('slug', $cat, 'product_cat');
                $cat_id[] = $category->term_id;
            }
        }

        $args = [
            'taxonomy' => 'product_cat',
            'slug' => !$_s['exclude_categories'] ? $slug : [],
            'order' => $_s['order_categories'],
            'orderby' => $_s['orderby_categories'],
            'exclude' => $_s['exclude_categories'] ? $cat_id : [],
        ];

        $categories = get_terms( $args );

        ob_start();
        foreach( $categories as $cat ){
            if($cat){
                $image_resized_url = '';
                $title = $cat->name;

                if (!empty($_s['show_count'])){
                    $count = $cat->count;
                    $count_render = $count._nx(
                        ' Variant',
                        ' Variants',
                        $count,
                        'category count',
                        'courto-core'
                    );
                }

                $cat_link = get_term_link($cat->term_id);
                $image_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $image_data = wp_get_attachment_image_src ( $image_id, 'full' );
                if ( !empty( $image_data[ 0 ] ) ) {
                    $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                        $img_size_array ?: $img_size_string,
                        $img_aspect_ratio,
                        $image_data
                    );
                    $dimensions[ 'width' ] = $dimensions[ 'width' ] ?? $image_data[ 1 ] ?? null;
                    $dimensions[ 'height' ] = $dimensions[ 'height' ] ?? $image_data[ 2 ] ?? null;

                    $image_full_url = $image_data[ 0 ];
                    $image_resized_url = aq_resize( $image_full_url, $dimensions[ 'width' ], $dimensions[ 'height' ], true, true, true ) ?: $image_full_url;
                }


                if ('cursor_tooltip' === $_s['title_position']) {
                    if(!empty($_s['show_count'])){
                        $count_data = '<div class=\'count\'>'.$count_render.'</div>';
                    }
                    $cursor_data = ' data-cursor-text="<h6>' . $title. '</h6>' . $count_data .'"' . $cursor_class;
                }

                echo '<div '.$this->get_render_attribute_string( 'item' ). ('cursor_tooltip' === $_s['title_position'] ? $cursor_data : '').'>',
                    '<div class="cats_item-wrapper">',
                        '<a class="cats_item-link" href="'.$cat_link.'"></a>',
                        !empty($image_resized_url) ? '<div class="cats_item-media"><img class="cats_item-image" src="'. $image_resized_url .'" alt="'.esc_attr(!empty($image_alt) ? $image_alt : $title).'" /></div>' : '',
                        '<'.$_s['title_tag'].' class="cats_item-title">'.$title.'</'.$_s['title_tag'].'>',
                        !empty($_s['show_count']) ? '<span class="cats_item-count">'.$count_render.'</span>' : '',
                    '</div>',
                '</div>';
            }
        }
        $content = ob_get_clean();

        echo '<div '.$this->get_render_attribute_string( 'wrapper' ).'>',
            'carousel' === $_s['products_layout'] ? $this->apply_carousel_options($content, $_s) : $content,
        '</div>';

    }

    protected function apply_carousel_options($items_html, $_s)
    {
        $_s['categories_gap'] = !empty($_s['categories_gap']['size']) ? $_s['categories_gap'] : ['size' => '30'];
        $_s['slides_per_row'] = $_s['grid_columns'];

        $_s['responsive_gap'] = [
            'desktop_gap' => $_s['categories_gap'],
            'tablet_gap' => !empty($_s['categories_gap_tablet']['size']) ? $_s['categories_gap_tablet'] : $_s['categories_gap'],
            'mobile_gap' => !empty($_s['categories_gap_mobile']['size']) ? $_s['categories_gap_mobile'] : $_s['categories_gap'],
        ];

        return WGL_Carousel_Settings::init($_s, $items_html);
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