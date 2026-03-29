<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-products-grid.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Group_Control_Border,
	Widget_Base,
	Controls_Manager,
	Group_Control_Typography,
	Group_Control_Box_Shadow
};
use WGL_Extensions\{
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGLProductsGrid
};

class WGL_Products_Grid extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-products-grid';
    }

    public function get_keywords() {
        return ['products', 'shop', 'woocommerce'];
    }

    public function get_title()
    {
        return esc_html__('WGL Products Grid', 'courto-core');
    }

    public function get_icon()
    {
        return 'wgl-products-grid';
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
            'grid_columns',
            [
                'label' => esc_html__('Grid Columns Amount', 'courto-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'render_type' => 'template',
                'options' => [
                    '1' => esc_html__('1 (one)', 'courto-core'),
                    '2' => esc_html__('2 (two)', 'courto-core'),
                    '3' => esc_html__('3 (three)', 'courto-core'),
                    '4' => esc_html__('4 (four)', 'courto-core'),
                    '5' => esc_html__('5 (five)', 'courto-core'),
                    '6' => esc_html__('6 (six)', 'courto-core'),
                ],
                'default' => '4',
                'prefix_class' => 'columns%s-'
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'courto-core'),
                'options' => [
                    '150' => 'Thumbnail - 150x150',
                    '300' => 'Medium - 300x300',
                    '768' => 'Medium Large - 768x768',
                    '1024' => 'Large - 1024x1024',
                    '540x520' => '540x520',
                    'full' => 'Full',
                    'custom' => 'Custom',
	                '' => 'Default Woo Size',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'courto-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'description' => esc_html__('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'courto-core'),
                'condition' => [
                    'img_size_string' => 'custom',
                ],
                'default' => [
                    'width' => '540',
                    'height' => '600',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Aspect Ratio', 'courto-core'),
                'options' => [
                    '1:1' => esc_html__('1:1', 'courto-core'),
                    '3:2' => esc_html__('3:2', 'courto-core'),
                    '4:3' => esc_html__('4:3', 'courto-core'),
                    '6:5' => esc_html__('6:5', 'courto-core'),
                    '9:16' => esc_html__('9:16', 'courto-core'),
                    '16:9' => esc_html__('16:9', 'courto-core'),
                    '21:9' => esc_html__('21:9', 'courto-core'),
                    '' => esc_html__('No Crop', 'courto-core'),
                ],
	            'condition' => [
		            'img_size_string!' => '',
	            ],
                'default' => '',
            ]
        );

        $this->add_control(
            'hide_stars',
            array(
                'label' => esc_html__('Hide Stars', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .star-rating' => 'display: none;',
                ],
            )
        );

        $this->add_control(
            'show_header_products',
            array(
                'label' => esc_html__('Show Header Shop', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_res_count',
            array(
                'label' => esc_html__('Show Result Count', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
	            'condition' => [ 'show_header_products' => 'yes' ],
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'yes',
	            'default' => 'yes',
            )
        );

        $this->add_control(
            'show_sorting',
            array(
                'label' => esc_html__('Show Default Sorting', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
	            'condition' => [ 'show_header_products' => 'yes' ],
                'label_on' => esc_html__('On', 'courto-core'),
                'label_off' => esc_html__('Off', 'courto-core'),
                'return_value' => 'yes',
	            'default' => 'yes',
            )
        );

        $this->add_control(
            'isotope_filter',
            [
                'label' => esc_html__('Use Filter?', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['products_layout!' => 'carousel'],
            ]
        );

	    $this->add_control(
		    'filter_counter_enabled',
		    [
			    'label' => esc_html__('Show Number of Categories', 'courto-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['isotope_filter' => 'yes'],
		    ]
	    );

	    $this->add_control(
		    'filter_max_width_enabled',
		    [
			    'label' => esc_html__('Limit the Filter Container Width', 'courto-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['isotope_filter' => 'yes'],
		    ]
	    );

        $this->add_control(
            'filter_max_width_centered',
            [
                'label' => esc_html__('To Center This Filter Container', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'show_filter' => 'yes',
                    'filter_max_width_enabled' => 'yes',
                ],
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} div.wgl-filter_wrapper' => 'margin-left: auto; margin-right: auto;',
                ],
            ]
        );

	    $this->add_control(
		    'max_width_filter',
		    [
			    'label' => esc_html__('Filter Container Max Width (px)', 'courto-core'),
			    'type' => Controls_Manager::NUMBER,
			    'dynamic' => ['active' => true],
			    'condition' => [
				    'isotope_filter' => 'yes',
				    'filter_max_width_enabled' => 'yes',
			    ],
			    'default' => '1170',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-filter_wrapper.isotope-filter' => 'width: min(100%, {{VALUE}}px);',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_alignment',
		    [
			    'label' => esc_html__('Filter Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'condition' => ['isotope_filter' => 'yes'],
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
			    'default' => 'center',
		    ]
	    );

        $this->add_control(
            'products_navigation',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Navigation', 'courto-core'),
	            'condition' => ['products_layout!' => 'carousel'],
                'options' => [
                    '' => 'None',
                    'pagination' => 'Pagination',
                    'load_more' => 'Load More',
                ],
                'default' => '',
            ]
        );

        $this->add_responsive_control(
            'navigation_align',
            [
                'label' => esc_html__('Navigation\'s Alignment', 'courto-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['products_navigation' => 'pagination'],
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-pagination' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_offset',
            [
                'label' => esc_html__('Navigation Margin Top', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'products_navigation' => 'pagination',
                    'products_layout' => ['grid', 'masonry']
                ],
                'size_units' => ['px', 'em', 'rem'],
                'default' => ['size' => 44],
                'range' => [
                    'px' => ['min' => 0, 'max' => 240],
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
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
                'condition' => [
                    'products_navigation' => 'load_more',
                    'products_layout!' => 'carousel'
                ],
                'default' => esc_html__('4', 'courto-core'),
            ]
        );

        $this->add_control(
            'name_load_more',
            array(
                'label' => esc_html__('Button Text', 'courto-core'),
                'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
	            'default' => esc_html__('More Products', 'courto-core'),
                'condition' => [
                    'products_navigation' => 'load_more',
                    'products_layout!' => 'carousel'
                ],
            )
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

        WGL_Carousel_Settings::add_general_controls($this);

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination!' => ''],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'use_pagination' => [
                'default' => 'yes',
            ],
            'pagination_margin' => [
                'default' => [
                    'top' => '47',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
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

        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls($this, [
            'post_type' => 'product',
            'hide_tags' => true,
            'hide_cats' => true,
        ]);

	    /**
	     * STYLE -> FILTER
	     */

	    $this->start_controls_section(
		    'style_filter',
		    [
			    'label' => esc_html__('Filter', 'courto-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['isotope_filter' => 'yes'],
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
			    'label' => esc_html__('Padding', 'courto-core'),
			    'type' => Controls_Manager::DIMENSIONS,
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
        $this->add_control(
            'filter_counter_animation_idle',
            [
                'label' => esc_html__('Animation Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => ['filter_counter_enabled' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a .cat_title::before' => 'color: {{VALUE}};',
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
            'filter_extra_element_color_active',
            [
                'label' => esc_html__('Animated Element Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a::after' => 'background-color: {{VALUE}};',
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

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'products_gap',
            [
                'label' => esc_html__('Products Gap', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'range' => [
                    'px' => ['min' => 0, 'max' => 60, 'step' => 2],
                ],
	            'devices' => [ 'desktop', 'tablet', 'mobile' ],
	            'default' => [ 'size' => '30' ],
                'tablet_default' => ['size' => '30'],
                'mobile_default' => ['size' => '20'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products' => '--products-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Product Inner Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_inner_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Product Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_inner_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'courto-core'),
                'separator' => 'before',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .woo_product_content',
            ]
        );

        $this->add_control(
            'content_radius',
            [
                'label' => esc_html__('Content Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woo_product_inner_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_idle',
                'selector' => '{{WRAPPER}} .woo_product_inner_wrapper',
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
                    '{{WRAPPER}} .product:hover .woo_product_inner_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow_hover',
                'selector' => '{{WRAPPER}} .product:hover .woo_product_inner_wrapper',
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
                'condition' => ['products_title!' => ''],
            ]
        );

        $this->add_control(
            'heading_products_title',
            [
                'label' => esc_html__('Title', 'courto-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'module_title',
                'selector' => '{{WRAPPER}} .products_title',
            ]
        );

        $this->add_control(
            'heading_products_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .products_title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'products_title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .products_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_products_subtitle',
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
                'selector' => '{{WRAPPER}} .products_subtitle',
            ]
        );

        $this->add_control(
            'heading_products_subtitle_color',
            [
                'label' => esc_html__('Subtitle Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .products_subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'products_subtitle_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .products_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> STARS
         */

        $this->start_controls_section(
            'section_style_stars',
            [
                'label' => esc_html__('Stars', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'hide_stars!' => 'yes' ],
            ]
        );
        $this->add_responsive_control(
            'stars_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_stars');
        $this->start_controls_tab(
            'tab_stars_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'stars_primary_color_idle',
            [
                'label' => esc_html__('Stars Primary Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'stars_secondary_color_idle',
            [
                'label' => esc_html__('Stars Secondary Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .star-rating::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_stars_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'stars_primary_color_hover',
            [
                'label' => esc_html__('Stars Primary Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'stars_secondary_color_hover',
            [
                'label' => esc_html__('Stars Secondary Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .star-rating::before' => 'color: {{VALUE}};',
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
            'section_style_image',
            [
                'label' => esc_html__('Image', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'image_width',
		    [
			    'label' => esc_html__('Image Max Width', 'courto-core'),
			    'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
			    'size_units' => ['px', '%', 'custom'],
			    'range' => [
				    'px' => ['min' => 50, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .product .woo_product_image' => 'max-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_margin',
		    [
			    'label' => esc_html__( 'Margin', 'courto-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%', 'custom' ],
			    'selectors' => [
				    '{{WRAPPER}} .product .picture' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				    '{{WRAPPER}} .product .picture' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .product .picture::before',
            ]
        );
        $this->add_control(
            'secondary_image',
            [
                'label' => esc_html__('Show Secondary Image on Hover', 'courto-core'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .product .picture img ~ img' => 'display: block;',
                ],
            ]
        );

        $this->add_control(
            'image_overlay_color',
            [
                'label' => esc_html__('Image Overlay Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product .picture' => '--courto-shop-products-overlay: {{VALUE}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_image');
        $this->start_controls_tab(
            'tab_image_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'image_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .product .picture' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .product .picture::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .product .picture::before',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_image_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'image_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .picture' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .product:hover .picture::before' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .product:hover .picture::before',
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
                'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_item_hover',
            ['label' => esc_html__('Item Hover', 'courto-core')]
        );
        $this->add_control(
            'htitle_color_item_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Title Hover', 'courto-core')]
        );
        $this->add_control(
            'htitle_color_hover',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> PRICE
         */

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => esc_html__('Price', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price',
                'selector' => '{{WRAPPER}} .wgl-products .price',
            ]
        );

        $this->add_responsive_control(
            'price_margin',
            [
                'label' => esc_html__('Margin', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_price');

        $this->start_controls_tab(
            'tab_price_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );

        $this->add_control(
            'price_color_idle',
            [
                'label' => esc_html__('Price Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product' => '--courto-price-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'old_price_color_idle',
            [
                'label' => esc_html__('Old Price Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product' => '--courto-price-del-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_price_hover',
            ['label' => esc_html__('Hover', 'courto-core')]
        );
        $this->add_control(
            'price_color_hover',
            [
                'label' => esc_html__('Price Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product:hover' => '--courto-price-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'old_price_color_hover',
            [
                'label' => esc_html__('Old Price Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product:hover' => '--courto-price-del-color: {{VALUE}};',
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
            'section_style_button',
            [
                'label' => esc_html__('Button', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button',
                'selector' => '{{WRAPPER}} .product a.button',
            ]
        );

        $this->add_control(
            'button_width',
            [
                'label' => esc_html__('Min Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 400],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward',
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_button');
        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Button Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button::before,
				     {{WRAPPER}} .product a.wc-forward::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_color_idle',
            [
                'label' => esc_html__('Button Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'border-color: {{VALUE}};',
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
                'label' => esc_html__('Button Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .product a.button.added,
                     {{WRAPPER}} .product a.button:hover,
				     {{WRAPPER}} .product a.wc-forward:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button.added::before,
                     {{WRAPPER}} .product a.button::before,
                     {{WRAPPER}} .product a.wc-forward::before' => 'transition: .4s;',
                    '{{WRAPPER}} .product a.button.added::before,
                     {{WRAPPER}} .product a.button:hover::before,
                     {{WRAPPER}} .product a.wc-forward:hover::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__('Button Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button.added,
                     {{WRAPPER}} .product a.button:hover,
				     {{WRAPPER}} .product a.wc-forward:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'condition' => [ 'button_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} .product a.button.added,
                     {{WRAPPER}} .product a.button:hover,
				     {{WRAPPER}} .product a.wc-forward:hover' => 'border-color: {{VALUE}};',
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
                'condition' => ['products_navigation' => 'load_more'],
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
         * STYLE -> LABEL SALE
         */

        $this->start_controls_section(
            'section_style_label_sale',
            [
                'label' => esc_html__('Label Sale', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_sale',
                'selector' => '{{WRAPPER}} span.onsale',
            ]
        );

        $this->add_control(
            'label_sale_color',
            [
                'label' => esc_html__('Text Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'label_sale_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'label_sale_padding',
            [
                'label' => esc_html__('Padding', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_sale_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * STYLE -> COMPARE & WISHLIST
         */

        $this->start_controls_section(
            'section_style_compare_icon',
            [
                'label' => esc_html__('Compare and Wishlist Icon', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'compare_icon_size',
            [
                'label' => esc_html__('Icon Size', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
                'dynamic' => ['active' => true],
                'size_units' => ['px', 'em', 'custom'],
                'range' => [
                    'px' => ['min' => 6, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-icon]' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'compare_icon_border',
                'fields_options' => [
                    'width' => [ 'label' => esc_html__( 'Border Width', 'courto-core' ) ],
                    'color' => [ 'type' => Controls_Manager::HIDDEN ],
                ],
                'selector' => '{{WRAPPER}} a.woosc-btn [class*=-btn-icon], {{WRAPPER}} a.woosw-btn [class*=-btn-icon]',
            ]
        );
        $this->add_control(
            'compare_icon_radius',
            [
                'label' => esc_html__('Border Radius', 'courto-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-icon]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_compare_icon');
        $this->start_controls_tab(
            'tab_compare_icon_idle',
            ['label' => esc_html__('Idle', 'courto-core')]
        );
        $this->add_control(
            'compare_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-icon]' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'compare_icon_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-icon]' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'compare_icon_border_color_idle',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'compare_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-icon]' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_compare_icon_hover',
            ['label' => esc_html__('Icon Hover', 'courto-core')]
        );
        $this->add_control(
            'compare_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn:hover [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn:hover [class*=-btn-icon]' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'compare_icon_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn:hover [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn:hover [class*=-btn-icon]' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'compare_icon_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'courto-core' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => [ 'compare_icon_border_border!' => ['', 'none'] ],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn:hover [class*=-btn-icon],
                     {{WRAPPER}} a.woosw-btn:hover [class*=-btn-icon]' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /**
         * STYLE -> COMPARE & WISHLIST TITLE
         */

        $this->start_controls_section(
            'section_style_compare_title',
            [
                'label' => esc_html__('Compare and Wishlist Title', 'courto-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'compare_title',
                'selector' => '{{WRAPPER}} a.woosc-btn [class*=-btn-text], {{WRAPPER}} a.woosw-btn [class*=-btn-text]',
            ]
        );
        $this->add_control(
            'compare_title_color',
            [
                'label' => esc_html__('Title Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-text],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-text]' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'compare_title_bg_color',
            [
                'label' => esc_html__('Background Color', 'courto-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} a.woosc-btn [class*=-btn-text],
                     {{WRAPPER}} a.woosw-btn [class*=-btn-text]' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        (new WGLProductsGrid())->render(
            $this->get_settings_for_display(),
            $this
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
