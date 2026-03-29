<?php
/**
 * This template can be overridden by copying it to
 * `yourtheme[-child]/wgl-extensions/elementor/widgets/wgl-combo-menu.php`.
 */

namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
    Group_Control_Border,
    Utils,
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Typography,
    Repeater};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // If this file is called directly, abort.

class Wgl_Combo_Menu extends Widget_Base {

	public function get_name() {
		return 'wgl-combo-menu';
	}

	public function get_title() {
		return esc_html__( 'Wgl Combo Menu', 'courto-core' );
	}

	public function get_icon() {
		return 'wgl-combo-menu';
	}

    public function get_keywords() {
        return ['menu', 'combo'];
    }

	public function get_categories() {
		return [ 'wgl-modules' ];
	}

	public function get_script_depends()
    {
        return ['jquery-appear'];
    }

	protected function register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*  Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section( 'wgl_working_section',
			[
				'label' => esc_html__( 'Menu Content', 'courto-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'thumbnail',
			[
				'label' => esc_html__( 'Image', 'courto-core' ),
				'type' => Controls_Manager::MEDIA,
			    'dynamic' => ['active' => true],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'menu_title',
			[
				'label' => esc_html__( 'Title', 'courto-core' ),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__( 'Combo Menu Title 1', 'courto-core' ),
				'default' => esc_html__( 'Combo Menu Title', 'courto-core' ),
			]
		);

		$repeater->add_control(
			'menu_desc',
			[
				'label' => esc_html__( 'Description', 'courto-core' ),
				'type' => Controls_Manager::TEXTAREA,
			    'dynamic' => ['active' => true],
				'placeholder' => esc_html__( 'Menu Description', 'courto-core' ),
				'default' => esc_html__( 'Menu Description', 'courto-core' ),
			]
		);

		$repeater->add_control(
			'menu_price',
			[
				'label' => esc_html__( 'Price', 'courto-core' ),
				'type' => Controls_Manager::TEXT,
			    'dynamic' => ['active' => true],
				'separator' => 'after',
				'placeholder' => esc_html__( '$3', 'courto-core' ),
				'default' => esc_html__( '$3', 'courto-core' ),
			]
		);

		$repeater->add_control( 'link_item',
			[
				'label' => esc_html__( 'Link', 'courto-core' ),
				'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
				'label_block' => true,
			]
		);

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Menu', 'courto-core' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[ 'menu_title' => esc_html__( 'Combo Menu Title 1', 'courto-core' ) ],
					[ 'menu_title' => esc_html__( 'Combo Menu Title 2', 'courto-core' ) ],
					[ 'menu_title' => esc_html__( 'Combo Menu Title 3', 'courto-core' ) ],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{menu_title}}',
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Style Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Styles', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Image Styles', 'courto-core' ),
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
					'px' => ['min' => 20, 'max' => 300 ],
					'%' => ['min' => 5, 'max' => 80 ],
				],
				'default' => ['size' => 90, 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .main_image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label' => esc_html__( 'Margin', 'courto-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
                'separator' => 'after',
				'default' => [
					'top' => '0',
					'right' => '20',
					'bottom' => '0',
					'left' => '0',
					'unit'  => 'px',
					'isLinked' => false
				],
                'selectors' => [
                    '{{WRAPPER}} .menu-item_image-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);

		$this->add_control(
			'title_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title Styles', 'courto-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'selector' => '{{WRAPPER}} .menu-item_title',
			]
		);

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .menu-item_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->start_controls_tabs( 'title_color_tab' );
		$this->start_controls_tab(
			'custom_title_color_normal',
			[
				'label' => esc_html__( 'Normal', 'courto-core' ),
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .menu-item_title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'custom_title_color_hover',
			[
				'label' => esc_html__( 'Hover', 'courto-core' ),
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .menu-item:hover .menu-item_title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'desc_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Description Styles', 'courto-core' ),
                'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'selector' => '{{WRAPPER}} .menu-item_desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .menu-item_desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'courto-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'default' => [
					'top' => '2',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit'  => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item_desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Price Styles', 'courto-core' ),
                'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typo',
				'selector' => '{{WRAPPER}} .menu-item_price',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .menu-item_price' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'price_margin',
			[
				'label' => esc_html__( 'Margin', 'courto-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .menu-item_price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' => esc_html__( 'Separator Between Color', 'courto-core' ),
				'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
				'default' => WGL_Globals::get_tertiary_color(0),
				'selectors' => [
					'{{WRAPPER}} .menu-item_content::after' => 'border-color: {{VALUE}};',
				],
			]
		);
        $this->add_responsive_control(
            'sep_width',
            [
                'label' => esc_html__('Separator Min-Width', 'courto-core'),
                'type' => Controls_Manager::SLIDER,
			    'dynamic' => ['active' => true],
                'size_units' => ['px', '%', 'custom'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 300 ],
                    '%' => ['min' => 0, 'max' => 50 ],
                ],
                'default' => ['size' => 0, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .menu-item_content::after' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Items Style Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'items_style_section',
			[
				'label' => esc_html__( 'Items Styles', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'items_bg',
                'label' => esc_html__('Background', 'courto-core'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .menu-item',
            ]
        );

        $this->add_responsive_control(
            'items_margin',
            [
                'label' => esc_html__( 'Margin', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_padding',
            [
                'label' => esc_html__( 'Padding', 'courto-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'default' => [
                    'top' => '43',
                    'right' => '20',
                    'bottom' => '24',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'items_border',
                'render_type' => 'template',
                'fields_options' => [
                    'border' => [ 'default' => 'solid' ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'courto-core' ),
                        'default' => [
                            'top' => 0,
                            'right' => 0,
                            'bottom' => 1,
                            'left' => 0,
                        ],
                    ],
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'courto-core' ),
                        'default' => WGL_Globals::get_secondary_color(0.3)
                    ],
                ],
                'selector' => '{{WRAPPER}} .menu-item',
            ]
        );

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Container Style Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'container_style_section',
			[
				'label' => esc_html__( 'Container Styles', 'courto-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_bg',
				'label' => esc_html__('Background', 'courto-core'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .wgl-combo-menu',
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label' => esc_html__( 'Margin', 'courto-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-combo-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'courto-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-combo-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'render_type' => 'template',
				'dynamic' => ['active' => true],
				'selector' => '{{WRAPPER}} .wgl-combo-menu',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'combo-menu', [
			'class' => [
				'wgl-combo-menu',
			],
		] );

		?>
        <div <?php echo $this->get_render_attribute_string( 'combo-menu' ); ?>><?php

		foreach ( $settings['items'] as $index => $item ) {

			if ( ! empty( $item['link_item']['url'] ) ) {
				$link_item = $this->get_repeater_setting_key( 'link_item', 'list', $index );
				$this->add_render_attribute( $link_item, 'class', 'menu-item menu-item_link' );
				$this->add_link_attributes( $link_item, $item['link_item'] );
			}

			$menu_image = $this->get_repeater_setting_key( 'thumbnail', 'list', $index );
			$this->add_render_attribute( $menu_image, [
				'class' => 'main_image',
				'src' => esc_url( $item['thumbnail']['url'] ),
				'alt' => Control_Media::get_image_alt( $item['thumbnail'] ),
			] );

			$menu_title = $this->get_repeater_setting_key( 'menu_title', 'items', $index );
			$this->add_render_attribute( $menu_title, [
				'class' => [
					'menu-item_title',
				],
			] );

			$menu_price = $this->get_repeater_setting_key( 'menu_price', 'items', $index );
			$this->add_render_attribute( $menu_price, [
				'class' => [
					'menu-item_price',
				],
			] );

			if ( ! empty( $item['link_item']['url'] ) ) {
				?><a <?php echo $this->get_render_attribute_string( $link_item ); ?>><?php
			} else {
                ?><div class="menu-item"><?php
			}
			if ( ! empty( $item['thumbnail']['url'] ) ) {
                ?><div class="menu-item_image-wrap">
                <img <?php echo $this->get_render_attribute_string( $menu_image ); ?> /></div><?php
			} ?>
            <div class="menu-item_content-wrap">
            <div class="menu-item_content"><?php
			if ( ! empty( $item['menu_title'] ) ) {
				?><h4 <?php echo $this->get_render_attribute_string( $menu_title ); ?>><?php echo esc_html( $item['menu_title'] ); ?></h4><?php
			}
			if ( ! empty( $item['menu_price'] ) ) {
				?><div <?php echo $this->get_render_attribute_string( $menu_price ); ?>><?php echo esc_html( $item['menu_price'] ); ?></div><?php
			} ?>
            </div><?php
			if ( ! empty( $item['menu_desc'] ) ) {
				?><div class="menu-item_desc"><?php echo esc_html( $item['menu_desc'] ); ?></div><?php
			} ?>
            </div><?php
			if ( ! empty( $item['link_item']['url'] ) ) {
                ?></a><?php
			} else {
                ?></div><?php
			}
		}

		?></div><?php

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